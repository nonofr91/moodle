<?php

defined('MOODLE_INTERNAL') || die();

/**
 * AFIR Web Services local plugin library functions
 */

/**
 * Hook to redirect non-logged users to landing page
 * This function is called during Moodle initialization
 */
function local_afirws_extend_navigation(global_navigation $nav) {
    // This hook is called early in page setup
}

/**
 * Hook to redirect non-logged users to landing page
 */
function local_afirws_before_http_headers() {
    global $CFG, $PAGE;
    
    // Only redirect if user is not logged in
    if (!isloggedin() && !isguestuser()) {
        $currenturl = $PAGE->url;
        $currentpath = $currenturl->get_path();
        
        // Don't redirect if already on landing page or login pages
        $publicpages = [
            '/local/afirws/landing.php',
            '/login/index.php',
            '/login/forgot_password.php',
            '/login/signup.php',
            '/admin/tool/dataprivacy/summary.php',
            '/local/afirws/'
        ];
        
        $ispublicpage = false;
        foreach ($publicpages as $publicpage) {
            if (strpos($currentpath, $publicpage) !== false) {
                $ispublicpage = true;
                break;
            }
        }
        
        // If accessing homepage (root URL) and not logged in, redirect to landing page
        if (!$ispublicpage && ($currentpath === '/' || $currentpath === '/index.php' || $currentpath === '')) {
            redirect(new moodle_url('/local/afirws/landing.php#catalogue'));
        }
    }
}
