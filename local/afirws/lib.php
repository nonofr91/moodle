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
 * Hook pour le plugin local_afirws
 *
 * @package    local_afirws
 * @copyright  2025 AFI Formation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Callback qui s'exécute après la configuration du site
 */
function local_afirws_after_config() {
    global $CFG;
    
    // S'assurer que les paramètres sont corrects
    if (!isset($CFG->forcelogin)) {
        $CFG->forcelogin = false;
    }
    
    if (!isset($CFG->autologinguests)) {
        $CFG->autologinguests = true;
    }
    
    // Forcer le thème Boost (déjà actif)
    $CFG->theme = 'boost';
    
    // Activer le mode designer pour les overrides de templates
    $CFG->themedesignermode = true;
}
