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
 * Bibliothèque du thème Boost personnalisé
 *
 * @package    local_afirws
 * @copyright  2025 AFI Formation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Factory pour le renderer personnalisé
 */
class theme_overridden_renderer_factory extends \theme_boost\output\renderer_factory {
    
    /**
     * Créer le renderer personnalisé
     */
    public function get_renderer(\moodle_page $page, $component, $subtype = null, $target = null) {
        if ($component === 'core' && $subtype === null) {
            return new \theme_boost\core_renderer($page, $target);
        }
        return parent::get_renderer($page, $component, $subtype, $target);
    }
}
