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
 * Configuration override pour le plugin local_afirws
 *
 * @package    local_afirws
 * @copyright  2025 AFI Formation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Désactiver forcelogin pour permettre aux visiteurs de voir la page d'accueil
if (!isset($CFG->forcelogin)) {
    $CFG->forcelogin = false;
}

// Activer l'auto-login pour les invités
if (!isset($CFG->autologinguests)) {
    $CFG->autologinguests = true;
}

// Définir le rôle par défaut pour les invités
if (!isset($CFG->guestroleid)) {
    $CFG->guestroleid = 6; // ID du rôle guest par défaut dans Moodle
}

// Définir le rôle par défaut pour la page d'accueil
if (!isset($CFG->defaultfrontpageroleid)) {
    $CFG->defaultfrontpageroleid = 6; // Rôle guest pour la page d'accueil
}
