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
 * Script pour corriger la configuration Moodle
 *
 * @package    local_afirws
 * @copyright  2025 AFI Formation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Inclure config.php pour accéder à Moodle
require_once(__DIR__ . '/../../config.php');

echo "=== Correction de la configuration Moodle ===\n";

// Vérifier et corriger forcelogin
$forcelogin = get_config('core', 'forcelogin');
echo "État actuel de forcelogin: " . ($forcelogin ? 'activé' : 'désactivé') . "\n";

if ($forcelogin) {
    echo "Désactivation de forcelogin...\n";
    set_config('forcelogin', 0);
    echo "forcelogin désactivé\n";
}

// Vérifier et corriger autologinguests
$autologinguests = get_config('core', 'autologinguests');
echo "État actuel de autologinguests: " . ($autologinguests ? 'activé' : 'désactivé') . "\n";

if (!$autologinguests) {
    echo "Activation de autologinguests...\n";
    set_config('autologinguests', 1);
    echo "autologinguests activé\n";
}

// Vérifier les permissions du rôle invité
$guestrole = $DB->get_record('role', array('shortname' => 'guest'));
if ($guestrole) {
    echo "Rôle invité trouvé (ID: {$guestrole->id})\n";
    
    // Vérifier si le rôle invité peut voir la page d'accueil
    $context = context_system::instance();
    $has_capability = has_capability('moodle/site:config', $context, $guestrole->id);
    echo "Capacité de configuration: " . ($has_capability ? 'oui' : 'non') . "\n";
    
    // Donner les permissions de base pour voir la page d'accueil
    assign_capability('moodle/course:view', CAP_ALLOW, $guestrole->id, $context->id);
    assign_capability('moodle/site:readallmessages', CAP_ALLOW, $guestrole->id, $context->id);
    echo "Permissions de base accordées au rôle invité\n";
}

// Vider les caches
echo "Vidage des caches...\n";
purge_all_caches();

echo "Configuration corrigée avec succès!\n";
echo "Veuillez vous déconnecter et tester à nouveau.\n";
