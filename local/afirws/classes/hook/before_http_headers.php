<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_afirws\hook;

use core\hook\output\before_http_headers;

/**
 * Hook callback for before_http_headers
 */
class before_http_headers implements before_http_headers {
    
    /**
     * Callback to redirect guests to landing page
     */
    public static function callback(before_http_headers $hook): void {
        global $PAGE;
        
        // Only redirect on the homepage and for non-logged-in users
        if (!isloggedin() && isset($PAGE) && $PAGE->pagetype === 'site-index') {
            // Redirect to landing page
            redirect(new \moodle_url('/local/afirws/landing_redirect.php'));
            exit;
        }
    }
}
