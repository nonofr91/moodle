<?php

require(__DIR__ . '/../../config.php');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/afirws/landing.php'));
$PAGE->set_pagelayout('frontpage');
$PAGE->set_title('AFIR - Formations');
$PAGE->set_heading('Association de Formation des Imprimeurs Rotativistes');

$logo = new moodle_url('/local/afirws/assets/AFIR_col_100.png');

$primaryctaurl = new moodle_url('/login/index.php');
$primaryctatext = 'Accéder à l\'espace de formation';
if (isloggedin() && !isguestuser()) {
    $primaryctaurl = new moodle_url('/my/');
    $primaryctatext = 'Aller au tableau de bord';
}

echo $OUTPUT->header();

?>
<div class="container py-4">
    <div class="afir-hero card border-0 mb-4">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-12 col-md-7">
                    <div class="d-flex align-items-center mb-3">
                        <img src="<?php echo $logo; ?>" alt="AFIR" style="height:56px; width:auto;" class="mr-3" />
                        <div>
                            <div class="text-uppercase small afir-hero-kicker">Centre de formation</div>
                            <h1 class="h2 mb-0">Formations professionnelles en imprimerie</h1>
                        </div>
                    </div>
                    <p class="lead mb-3">Des parcours concrets, orientés terrain, pour développer les compétences techniques et organisationnelles des équipes.</p>
                    <div class="d-flex flex-wrap">
                        <a class="btn btn-primary mr-2 mb-2" href="<?php echo $primaryctaurl; ?>"><?php echo $primaryctatext; ?></a>
                        <a class="btn btn-outline-light mb-2" href="#catalogue">Voir les formations</a>
                    </div>
                </div>
                <div class="col-12 col-md-5 mt-4 mt-md-0">
                    <div class="afir-hero-panel p-3 p-md-4">
                        <div class="h5 mb-2">Pour qui ?</div>
                        <div class="mb-2">Opérateurs, conducteurs, techniciens maintenance, encadrement, fonctions support.</div>
                        <div class="h5 mt-3 mb-2">Formats</div>
                        <div>Présentiel, blended-learning, accompagnement intra/ inter.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="catalogue" class="mb-4">
        <h2 class="h4 mb-3">Nos formations</h2>
        <div class="row">
            <div class="col-12 col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="afir-badge mb-2">Technique</div>
                        <h3 class="h5">Impression & procédés</h3>
                        <p class="mb-0">Comprendre les procédés, maîtriser les réglages, optimiser la qualité et réduire les rebuts.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="afir-badge mb-2">Maintenance</div>
                        <h3 class="h5">Maintenance & fiabilisation</h3>
                        <p class="mb-0">Méthodes de diagnostic, prévention, sécurité machine et amélioration continue.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="afir-badge mb-2">Organisation</div>
                        <h3 class="h5">Qualité, sécurité, production</h3>
                        <p class="mb-0">Standardiser, sécuriser, piloter les indicateurs et renforcer la performance de l'atelier.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-info mt-2">
            Le catalogue ci-dessus est un modèle. Dis-moi la liste exacte des formations (titres + 1 phrase) et je l'adapte.
        </div>
    </div>

    <div class="card border-0 afir-cta">
        <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-center">
            <div class="mr-md-4">
                <h2 class="h5 mb-1">Vous souhaitez un devis ou une session sur mesure ?</h2>
                <div class="text-muted">Contactez-nous et décrivez votre besoin (effectifs, objectifs, contraintes, dates).</div>
            </div>
            <div class="ml-md-auto mt-3 mt-md-0">
                <a class="btn btn-primary" href="<?php echo $primaryctaurl; ?>"><?php echo $primaryctatext; ?></a>
            </div>
        </div>
    </div>
</div>
<?php

echo $OUTPUT->footer();
