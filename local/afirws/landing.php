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

require_once('../../config.php');
require_login(null, false);

// Définir le contexte de la page
$context = context_system::instance();
$PAGE->set_context($context);

$PAGE->set_url('/local/afirws/landing.php');
$PAGE->set_pagelayout('frontpage');

// Désactiver la barre de navigation standard
$PAGE->navbar->ignore_active();
// Ne pas essayer de supprimer des éléments de navigation pour éviter les erreurs

// Définir le titre et l'en-tête de la page
$PAGE->set_title(get_string('pluginname', 'local_afirws'));
$PAGE->set_heading(get_string('pluginname', 'local_afirws'));

// Ajouter du contenu personnalisé
$content = html_writer::tag('h1', 'Bienvenue sur AFI Formation');
$content .= html_writer::tag('p', 'Découvrez nos formations professionnelles de qualité.');

// Afficher la page
echo $OUTPUT->header();
echo $content;
echo $OUTPUT->footer();
