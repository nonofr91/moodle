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

    'local_afirws_set_course_visibility' => [
        'classname' => 'local_afirws_external',
        'methodname' => 'set_course_visibility',
        'classpath' => 'local/afirws/externallib.php',
        'description' => 'Set course visibility (hide/show).',
        'type' => 'write',
        'ajax' => false,
        'capabilities' => 'moodle/course:update',
    ],

    'local_afirws_set_course_dates' => [
        'classname' => 'local_afirws_external',
        'methodname' => 'set_course_dates',
        'classpath' => 'local/afirws/externallib.php',
        'description' => 'Set course start/end dates.',
        'type' => 'write',
        'ajax' => false,
        'capabilities' => 'moodle/course:update',
    ],

    'local_afirws_find_course_module' => [
        'classname' => 'local_afirws_external',
        'methodname' => 'find_course_module',
        'classpath' => 'local/afirws/externallib.php',
        'description' => 'Find an activity in a course by module type and name. Returns cmid and instanceid.',
        'type' => 'read',
        'ajax' => false,
        'capabilities' => 'moodle/course:view',
    ],

    'local_afirws_set_quiz_access_times' => [
        'classname' => 'local_afirws_external',
        'methodname' => 'set_quiz_access_times',
        'classpath' => 'local/afirws/externallib.php',
        'description' => 'Set quiz open/close times and time limit.',
        'type' => 'write',
        'ajax' => false,
        'capabilities' => 'mod/quiz:manage',
    ],
];
