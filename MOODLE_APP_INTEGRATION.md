# Documentation technique Moodle (pour intégration App)

## 0) Contexte / Objectif
L’instance Moodle sert de backend de formation + d’examens. L’app doit pouvoir :

- Créer des sessions à partir de cours “templates”.
- Créer des examens à partir de templates d’examen.
- Piloter l’ouverture/fermeture (visibilité), les dates de cours, et pour les examens les fenêtres d’accès quiz (timeopen/timeclose/timelimit).
- Maintenir une organisation “multi-parcours” (ex: `SB01`) via des catégories Moodle.

Toutes les actions automatiques se font via **Moodle Web Services REST** : fonctions core + plugin custom `local_afirws`.

---

## 1) Endpoint REST / Auth

- Base URL : `https://moodle.sipc-cgt.fr/webservice/rest/server.php`
- Format : `moodlewsrestformat=json`
- Auth : `wstoken=<TOKEN>`

Squelette (curl) :

```bash
curl -sS -G "https://moodle.sipc-cgt.fr/webservice/rest/server.php" \
  --data-urlencode "wstoken=$MOODLE_TOKEN" \
  --data-urlencode "moodlewsrestformat=json" \
  --data-urlencode "wsfunction=<FUNCTION_NAME>" \
  --data-urlencode "<param>=<value>"
```

---

## 2) Structure des catégories (TENANT_01)

Catégorie “racine fonctionnelle” :

- `TENANT_01` : `categoryid=4`

Sous-catégories principales :

- **TEMPLATES** : `categoryid=16` (`idnumber=TENANT_01_TEMPLATES`)
- **SESSIONS** : `categoryid=17` (`idnumber=TENANT_01_SESSIONS`)
- **EXAMENS** : `categoryid=18` (`idnumber=TENANT_01_EXAMENS`)

Sous-catégories par parcours `SB01` :

- **TEMPLATES/SB01** : `categoryid=19` (`TENANT_01_TEMPLATES_SB01`)
- **SESSIONS/SB01** : `categoryid=20` (`TENANT_01_SESSIONS_SB01`)
- **EXAMENS/SB01** : `categoryid=21` (`TENANT_01_EXAMENS_SB01`)

Invariant attendu côté app : la catégorie “destination” d’une duplication dépend du type (`session` vs `exam`) et du `parcours`.

---

## 3) Objets de référence (SB01)

### 3.1 Template “cours” (formation)

- Template cours SB01 : `courseid=29`
  - `shortname=T01_SB01`
  - `fullname=[SB01] TRAITEMENT DE TEXTE (MICROSOFT WORD)`

### 3.2 Template “examen”

- Template examen SB01 : `courseid=35`
  - `shortname=T01_EXAM_SB01`
  - `fullname=[TEMPLATE EXAM] SB01`
  - `categoryid=19` (TEMPLATES/SB01)

### 3.3 Instance examen (exemple)

- Examen SB01 DS20250002 : `courseid=36`
  - `shortname=EXAM-SB01-DS20250002`
  - `fullname=[EXAM] SB01 - DS20250002`
  - `categoryid=21` (EXAMENS/SB01)

### 3.4 Instance session (exemple)

- Session SB01 DS20250003 : `courseid=37`
  - `shortname=SESSION-SB01-DS20250003`
  - `fullname=[SESSION] SB01 - DS20250003`
  - `categoryid=20` (SESSIONS/SB01)

---

## 4) Conventions de nommage

### 4.1 Sessions

- `shortname = SESSION-<PARCOURS>-<REF>`
- `fullname = [SESSION] <PARCOURS> - <REF>`

Ex:

- `SESSION-SB01-DS20250003`
- `[SESSION] SB01 - DS20250003`

### 4.2 Examens

- `shortname = EXAM-<PARCOURS>-<REF>`
- `fullname = [EXAM] <PARCOURS> - <REF>`

Ex:

- `EXAM-SB01-DS20250002`
- `[EXAM] SB01 - DS20250002`

---

## 5) Règles de nettoyage / contenu (Option A)

### 5.1 Sessions

- On **garde** “Annonces” (forum news) dans les sessions.

### 5.2 Examens

- On **supprime** “Annonces” sur :
  - le template examen
  - les instances d’examen
- On positionne `newsitems=0` sur les examens.

---

## 6) Plugin custom `local_afirws`

### 6.1 Objectif

Fonctions WS supplémentaires pour que l’app puisse piloter :

- visibilité d’un cours
- dates de cours
- recherche d’un module dans un cours (ex: quiz par nom)
- paramètres d’accès quiz (timeopen/timeclose/timelimit)
- écriture de contenu “conditions d’examen” en section Généralités

### 6.2 Fonctions exposées (noms exacts)

- `local_afirws_set_course_visibility`
- `local_afirws_set_course_dates`
- `local_afirws_find_course_module`
- `local_afirws_set_quiz_access_times`
- `local_afirws_set_section_summary`

#### 6.2.1 `local_afirws_set_course_visibility`

Params :

- `courseid` (int)
- `visible` (0|1)

Exemple :

```bash
curl -sS -G "https://moodle.sipc-cgt.fr/webservice/rest/server.php" \
  --data-urlencode "wstoken=$MOODLE_TOKEN" \
  --data-urlencode "moodlewsrestformat=json" \
  --data-urlencode "wsfunction=local_afirws_set_course_visibility" \
  --data-urlencode "courseid=37" \
  --data-urlencode "visible=0"
```

Retour (exemple) :

```json
{"success":true,"courseid":37,"visible":false}
```

#### 6.2.2 `local_afirws_set_course_dates`

Params :

- `courseid` (int)
- `startdate` (epoch seconds)
- `enddate` (epoch seconds)

Exemple :

```bash
curl -sS -G "https://moodle.sipc-cgt.fr/webservice/rest/server.php" \
  --data-urlencode "wstoken=$MOODLE_TOKEN" \
  --data-urlencode "moodlewsrestformat=json" \
  --data-urlencode "wsfunction=local_afirws_set_course_dates" \
  --data-urlencode "courseid=37" \
  --data-urlencode "startdate=1766335200" \
  --data-urlencode "enddate=1768927200"
```

Retour (exemple) :

```json
{"success":true,"courseid":37,"startdate":1766335200,"enddate":1768927200}
```

#### 6.2.3 `local_afirws_find_course_module`

Params :

- `courseid` (int)
- `modname` (string) ex: `quiz`, `forum`
- `name` (string) ex: `QCM SB01`

Usage : retrouver un module dans un cours quand on veut ensuite en déduire le `quizid` (instance quiz) ou le `cmid` à supprimer.

#### 6.2.4 `local_afirws_set_quiz_access_times`

Params :

- `quizid` (int) = instance quiz (pas `cmid`)
- `timeopen` (epoch seconds)
- `timeclose` (epoch seconds)
- `timelimit` (seconds)

#### 6.2.5 `local_afirws_set_section_summary`

Params :

- `courseid` (int)
- `sectionnumber` (int) : `0` = Généralités
- `summary` (string HTML)
- `summaryformat` (int) : généralement `1`

---

## 7) WebServices core utilisés

### 7.1 Dupliquer un cours

WS : `core_course_duplicate_course`

Params :

- `courseid` (source)
- `fullname`
- `shortname`
- `categoryid` (destination)

Exemple (session) :

```bash
curl -sS -G "https://moodle.sipc-cgt.fr/webservice/rest/server.php" \
  --data-urlencode "wstoken=$MOODLE_TOKEN" \
  --data-urlencode "moodlewsrestformat=json" \
  --data-urlencode "wsfunction=core_course_duplicate_course" \
  --data-urlencode "courseid=29" \
  --data-urlencode "fullname=[SESSION] SB01 - DS20250003" \
  --data-urlencode "shortname=SESSION-SB01-DS20250003" \
  --data-urlencode "categoryid=20"
```

Retour (exemple) :

```json
{"id":37,"shortname":"SESSION-SB01-DS20250003"}
```

### 7.2 Retrouver un cours par champ

WS : `core_course_get_courses_by_field`

Exemple :

```bash
curl -sS -G "https://moodle.sipc-cgt.fr/webservice/rest/server.php" \
  --data-urlencode "wstoken=$MOODLE_TOKEN" \
  --data-urlencode "moodlewsrestformat=json" \
  --data-urlencode "wsfunction=core_course_get_courses_by_field" \
  --data-urlencode "field=shortname" \
  --data-urlencode "value=SESSION-SB01-DS20250003"
```

### 7.3 Supprimer des modules (ex: “Annonces”)

WS : `core_course_delete_modules`

- `cmids[0]=<cmid>`

Note : souvent `null` en réponse JSON en cas de succès.

### 7.4 Lire les quiz d’un cours (inclut `attempts`)

WS : `mod_quiz_get_quizzes_by_courses`

Champ important :

- `attempts` :
  - `1` = une seule tentative
  - `0` = tentatives illimitées

---

## 8) Workflows d’automatisation côté app

### 8.1 Workflow “Créer une session”

Inputs :

- `parcours` (ex: `SB01`)
- `ref` (ex: `DS20250003`)
- `startdate`, `enddate` (epoch)

Étapes :

1) `core_course_duplicate_course` (source template cours)
2) `local_afirws_set_course_visibility(courseid, visible=0)`
3) `local_afirws_set_course_dates(courseid, startdate, enddate)`
4) `local_afirws_set_course_visibility(courseid, visible=1)` au moment d’ouverture

Option A : garder “Annonces” sur session.

### 8.2 Workflow “Créer un examen”

Inputs :

- `parcours` (ex: `SB01`)
- `ref` (ex: `DS20250002`)
- fenêtre quiz : `timeopen`, `timeclose`, `timelimit`

Étapes :

1) `core_course_duplicate_course` (source template examen)
2) Nettoyage examen : supprimer “Annonces” + `newsitems=0`
3) `local_afirws_set_section_summary` (conditions d’examen en section 0)
4) Vérifier quiz “1 tentative” via `mod_quiz_get_quizzes_by_courses`
5) `local_afirws_set_quiz_access_times(quizid, timeopen, timeclose, timelimit)`
6) `local_afirws_set_course_visibility` et `local_afirws_set_course_dates`

---

## 9) Notes / pièges

- `cmid` != `quizid`.
  - `local_afirws_set_quiz_access_times` prend **`quizid`**.
- `core_course_delete_modules` peut renvoyer `null` même en succès.
- Une instance déjà dupliquée n’hérite pas d’un changement fait après coup sur le template.

