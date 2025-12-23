<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_afirws_install() {
    global $CFG;
    
    // Définir la page d'accueil par défaut si ce n'est pas déjà fait
    if (!isset($CFG->defaulthomepage)) {
        set_config('defaulthomepage', HOMEPAGE_SITE);
    }
    
    // Définir la page d'accueil pour les utilisateurs non connectés
    if (!isset($CFG->frontpage) || $CFG->frontpage == '' || $CFG->frontpage == 'none') {
        set_config('frontpage', 'local_afirws_landing');
    }
    
    return true;
}
