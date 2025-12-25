-- Correction rapide - exécuter dans la base de données
UPDATE mdl_config SET value = '0' WHERE name = 'forcelogin';
UPDATE mdl_config SET value = '1' WHERE name = 'autologinguests';

-- Vérifier
SELECT name, value FROM mdl_config WHERE name IN ('forcelogin', 'autologinguests');
