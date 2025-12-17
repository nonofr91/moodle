<?php

defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_afirws_set_section_summary' => [
        'classname' => 'local_afirws_external',
        'methodname' => 'set_section_summary',
        'classpath' => 'local/afirws/externallib.php',
        'description' => 'Set the summary of a course section (e.g. section 0 for Generalities).',
        'type' => 'write',
        'ajax' => false,
        'capabilities' => 'moodle/course:update',
    ],
];
