<?php

defined('MOODLE_INTERNAL') || die();

$callbacks = [
    [
        'hook' => \core\hook\output\before_http_headers::class,
        'callback' => [\local_afirws\hook\before_http_headers::class, 'callback'],
        'priority' => 0,
    ],
];
