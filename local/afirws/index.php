<?php

defined('MOODLE_INTERNAL') || die();

require(__DIR__ . '/../../config.php');

// Check if user is logged in
if (isloggedin() && !isguestuser()) {
    // If logged in, redirect to dashboard or my page
    redirect(new moodle_url('/my/'));
} else {
    // If not logged in, redirect to landing page
    redirect(new moodle_url('/local/afirws/landing.php#catalogue'));
}
