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
