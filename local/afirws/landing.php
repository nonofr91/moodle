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

$PAGE->set_url('/local/afirws/landing.php');
$PAGE->set_pagelayout('frontpage');
$PAGE->set_title(get_string('pluginname', 'local_afirws'));
$PAGE->set_heading(get_string('pluginname', 'local_afirws'));

// Désactiver la barre de navigation standard
$PAGE->navbar->ignore_active();
$PAGE->navbar->clear();

// Désactiver le pied de page standard
$PAGE->set_heading('');

// Ajouter du contenu personnalisé
$content = html_writer::tag('h1', 'Bienvenue sur AFI Formation');
$content .= html_writer::tag('p', 'Découvrez nos formations professionnelles de qualité.');

// Afficher la page
echo $OUTPUT->header();
echo $content;
echo $OUTPUT->footer();
