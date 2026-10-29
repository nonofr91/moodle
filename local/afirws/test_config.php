<?php
// Fichier de test pour vérifier la configuration
define('MOODLE_INTERNAL', true);
require_once(dirname(__FILE__) . '/../../config.php');

echo "<h1>Test de configuration Moodle</h1>";

// Vérifier les paramètres de configuration
echo "<h2>Paramètres de configuration :</h2>";
echo "forcelogin: " . (isset($CFG->forcelogin) ? ($CFG->forcelogin ? 'true' : 'false') : 'non défini') . "<br>";
echo "autologinguests: " . (isset($CFG->autologinguests) ? ($CFG->autologinguests ? 'true' : 'false') : 'non défini') . "<br>";
echo "guestroleid: " . (isset($CFG->guestroleid) ? $CFG->guestroleid : 'non défini') . "<br>";
echo "defaultfrontpageroleid: " . (isset($CFG->defaultfrontpageroleid) ? $CFG->defaultfrontpageroleid : 'non défini') . "<br>";

// Vérifier si le plugin est activé
echo "<h2>Plugin local_afirws :</h2>";
$plugininfo = core_plugin_manager::instance()->get_plugin_info('local_afirws');
if ($plugininfo) {
    echo "Plugin trouvé : " . $plugininfo->versiondb . "<br>";
    echo "Version actuelle : " . $plugininfo->versiondisk . "<br>";
    echo "Statut : " . ($plugininfo->is_enabled() ? 'Activé' : 'Désactivé') . "<br>";
} else {
    echo "Plugin non trouvé<br>";
}

// Vérifier les tables de configuration
echo "<h2>Configuration dans la base de données :</h2>";
global $DB;
$config_forcelogin = get_config('core', 'forcelogin');
$config_autologinguests = get_config('core', 'autologinguests');

echo "forcelogin (DB): " . ($config_forcelogin ? 'true' : 'false') . "<br>";
echo "autologinguests (DB): " . ($config_autologinguests ? 'true' : 'false') . "<br>";

?>
