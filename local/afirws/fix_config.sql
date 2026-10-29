-- Script SQL pour corriger la configuration Moodle
-- Exécuter dans la base de données Moodle

-- Désactiver forcelogin
UPDATE mdl_config SET value = '0' WHERE name = 'forcelogin';

-- Activer autologinguests
UPDATE mdl_config SET value = '1' WHERE name = 'autologinguests';

-- Insérer si les paramètres n'existent pas
INSERT IGNORE INTO mdl_config (name, value) VALUES ('forcelogin', '0');
INSERT IGNORE INTO mdl_config (name, value) VALUES ('autologinguests', '1');

-- Vérifier les paramètres
SELECT name, value FROM mdl_config WHERE name IN ('forcelogin', 'autologinguests');
