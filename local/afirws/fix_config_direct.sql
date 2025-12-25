-- Correction directe de la configuration Moodle
-- Exécuter dans la base de données Moodle

-- Désactiver forcelogin (permet aux visiteurs de voir le site)
UPDATE mdl_config SET value = '0' WHERE name = 'forcelogin';

-- Activer autologinguests (connexion automatique des invités)
UPDATE mdl_config SET value = '1' WHERE name = 'autologinguests';

-- S'assurer que le rôle guest est correct (ID = 6 généralement)
UPDATE mdl_config SET value = '6' WHERE name = 'guestroleid';

-- Configuration de la page d'accueil
UPDATE mdl_config SET value = '0' WHERE name = 'forceloginforprofile';

-- Vérification
SELECT name, value FROM mdl_config WHERE name IN ('forcelogin', 'autologinguests', 'guestroleid', 'forceloginforprofile');
