<?php

defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '\core\event\course_viewed',
        'callback' => 'local_afirws\observer::redirect_non_logged_users',
    ],
    [
        'eventname' => '\core\event\user_loggedout',
        'callback' => 'local_afirws\observer::redirect_to_landing',
    ],
];
