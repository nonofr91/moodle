<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Redirection vers la landing page pour les visiteurs non connectés
 */

require_once(dirname(__FILE__) . '/../../config.php');

// Si l'utilisateur n'est pas connecté, rediriger vers la landing page
if (!isloggedin() || isguestuser()) {
    redirect(new moodle_url('/local/afirws/landing_redirect.php'));
    exit;
}

// Sinon, rediriger vers la page d'accueil Moodle normale
redirect(new moodle_url('/'));
exit;
