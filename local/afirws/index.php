<?php
/**
 * Redirection vers la landing page
 */

// Si ce n'est pas un utilisateur connecté, rediriger vers la landing page
if (!isloggedin() || isguestuser()) {
    // Rediriger vers la landing page personnalisée
    header('Location: /local/afirws/landing_redirect.php');
    exit();
}

// Sinon, continuer vers le index.php normal de Moodle
require_once(dirname(__FILE__) . '/../../index.php');
