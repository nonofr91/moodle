<?php

namespace local_afirws;

defined('MOODLE_INTERNAL') || die();

class observer {

    /**
     * Redirect non-logged users to landing page when they try to access homepage
     */
    public static function redirect_non_logged_users(\core\event\course_viewed $event) {
        global $CFG, $USER;
        
        // Only redirect if user is not logged in and trying to access course 1 (site homepage)
        if (!isloggedin() && !isguestuser() && $event->courseid == 1) {
            redirect(new moodle_url('/local/afirws/landing.php#catalogue'));
        }
    }

    /**
     * Redirect to landing page after logout
     */
    public static function redirect_to_landing(\core\event\user_loggedout $event) {
        redirect(new moodle_url('/local/afirws/landing.php#catalogue'));
    }
}
