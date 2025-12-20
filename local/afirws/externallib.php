<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->libdir . '/modinfolib.php');

class local_afirws_external extends external_api {
    public static function set_section_summary_parameters() {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'Course id'),
            'sectionnumber' => new external_value(PARAM_INT, 'Section number (0 for Generalities)'),
            'summary' => new external_value(PARAM_RAW, 'Section summary (HTML allowed)'),
            'summaryformat' => new external_value(PARAM_INT, 'Summary format (1 = HTML)', VALUE_DEFAULT, FORMAT_HTML),
        ]);
    }

    public static function set_section_summary($courseid, $sectionnumber, $summary, $summaryformat = FORMAT_HTML) {
        global $DB;

        $params = self::validate_parameters(self::set_section_summary_parameters(), [
            'courseid' => $courseid,
            'sectionnumber' => $sectionnumber,
            'summary' => $summary,
            'summaryformat' => $summaryformat,
        ]);

        $course = get_course($params['courseid']);
        $context = context_course::instance($course->id);
        self::validate_context($context);
        require_capability('moodle/course:update', $context);

        $section = $DB->get_record('course_sections', [
            'course' => $course->id,
            'section' => $params['sectionnumber'],
        ], '*', MUST_EXIST);

        $section->summary = $params['summary'];
        $section->summaryformat = $params['summaryformat'];
        $section->timemodified = time();
        $DB->update_record('course_sections', $section);

        rebuild_course_cache($course->id, true);

        return [
            'success' => true,
            'sectionid' => (int)$section->id,
        ];
    }

    public static function set_section_summary_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the update was successful'),
            'sectionid' => new external_value(PARAM_INT, 'Updated section id'),
        ]);
    }

    public static function set_course_visibility_parameters() {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'Course id'),
            'visible' => new external_value(PARAM_BOOL, 'Whether the course is visible'),
        ]);
    }

    public static function set_course_visibility($courseid, $visible) {
        $params = self::validate_parameters(self::set_course_visibility_parameters(), [
            'courseid' => $courseid,
            'visible' => $visible,
        ]);

        $course = get_course($params['courseid']);
        $context = context_course::instance($course->id);
        self::validate_context($context);
        require_capability('moodle/course:update', $context);

        update_course((object)[
            'id' => $course->id,
            'visible' => $params['visible'] ? 1 : 0,
        ]);

        rebuild_course_cache($course->id, true);

        return [
            'success' => true,
            'courseid' => (int)$course->id,
            'visible' => $params['visible'] ? true : false,
        ];
    }

    public static function set_course_visibility_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the update was successful'),
            'courseid' => new external_value(PARAM_INT, 'Updated course id'),
            'visible' => new external_value(PARAM_BOOL, 'Whether the course is visible'),
        ]);
    }

    public static function set_course_dates_parameters() {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'Course id'),
            'startdate' => new external_value(PARAM_INT, 'Course startdate (unix timestamp)', VALUE_DEFAULT, 0),
            'enddate' => new external_value(PARAM_INT, 'Course enddate (unix timestamp)', VALUE_DEFAULT, 0),
        ]);
    }

    public static function set_course_dates($courseid, $startdate = 0, $enddate = 0) {
        $params = self::validate_parameters(self::set_course_dates_parameters(), [
            'courseid' => $courseid,
            'startdate' => $startdate,
            'enddate' => $enddate,
        ]);

        $course = get_course($params['courseid']);
        $context = context_course::instance($course->id);
        self::validate_context($context);
        require_capability('moodle/course:update', $context);

        update_course((object)[
            'id' => $course->id,
            'startdate' => (int)$params['startdate'],
            'enddate' => (int)$params['enddate'],
        ]);

        rebuild_course_cache($course->id, true);

        return [
            'success' => true,
            'courseid' => (int)$course->id,
            'startdate' => (int)$params['startdate'],
            'enddate' => (int)$params['enddate'],
        ];
    }

    public static function set_course_dates_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the update was successful'),
            'courseid' => new external_value(PARAM_INT, 'Updated course id'),
            'startdate' => new external_value(PARAM_INT, 'Course startdate (unix timestamp)'),
            'enddate' => new external_value(PARAM_INT, 'Course enddate (unix timestamp)'),
        ]);
    }

    public static function find_course_module_parameters() {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'Course id'),
            'modname' => new external_value(PARAM_ALPHANUMEXT, 'Module type (e.g. quiz, assign)'),
            'name' => new external_value(PARAM_TEXT, 'Activity name to match'),
        ]);
    }

    public static function find_course_module($courseid, $modname, $name) {
        $params = self::validate_parameters(self::find_course_module_parameters(), [
            'courseid' => $courseid,
            'modname' => $modname,
            'name' => $name,
        ]);

        $course = get_course($params['courseid']);
        $context = context_course::instance($course->id);
        self::validate_context($context);
        require_capability('moodle/course:view', $context);

        $modinfo = get_fast_modinfo($course);
        foreach ($modinfo->get_cms() as $cm) {
            if (!$cm) {
                continue;
            }
            if ($cm->modname !== $params['modname']) {
                continue;
            }
            if ($cm->name !== $params['name']) {
                continue;
            }

            return [
                'found' => true,
                'cmid' => (int)$cm->id,
                'instanceid' => (int)$cm->instance,
                'courseid' => (int)$course->id,
            ];
        }

        return [
            'found' => false,
            'cmid' => 0,
            'instanceid' => 0,
            'courseid' => (int)$course->id,
        ];
    }

    public static function find_course_module_returns() {
        return new external_single_structure([
            'found' => new external_value(PARAM_BOOL, 'Whether a matching activity was found'),
            'cmid' => new external_value(PARAM_INT, 'Course module id (0 if not found)'),
            'instanceid' => new external_value(PARAM_INT, 'Module instance id (0 if not found)'),
            'courseid' => new external_value(PARAM_INT, 'Course id'),
        ]);
    }

    public static function set_quiz_access_times_parameters() {
        return new external_function_parameters([
            'quizid' => new external_value(PARAM_INT, 'Quiz instance id'),
            'timeopen' => new external_value(PARAM_INT, 'Open time (unix timestamp)', VALUE_DEFAULT, 0),
            'timeclose' => new external_value(PARAM_INT, 'Close time (unix timestamp)', VALUE_DEFAULT, 0),
            'timelimit' => new external_value(PARAM_INT, 'Time limit (seconds)', VALUE_DEFAULT, 0),
        ]);
    }

    public static function set_quiz_access_times($quizid, $timeopen = 0, $timeclose = 0, $timelimit = 0) {
        global $DB;

        $params = self::validate_parameters(self::set_quiz_access_times_parameters(), [
            'quizid' => $quizid,
            'timeopen' => $timeopen,
            'timeclose' => $timeclose,
            'timelimit' => $timelimit,
        ]);

        $quiz = $DB->get_record('quiz', ['id' => $params['quizid']], '*', MUST_EXIST);

        $course = get_course($quiz->course);
        $cm = get_coursemodule_from_instance('quiz', $quiz->id, $course->id, false, MUST_EXIST);
        $context = context_module::instance($cm->id);
        self::validate_context($context);
        require_capability('mod/quiz:manage', $context);

        $quiz->timeopen = (int)$params['timeopen'];
        $quiz->timeclose = (int)$params['timeclose'];
        $quiz->timelimit = (int)$params['timelimit'];
        $quiz->timemodified = time();
        $DB->update_record('quiz', $quiz);

        rebuild_course_cache($course->id, true);

        return [
            'success' => true,
            'quizid' => (int)$quiz->id,
            'courseid' => (int)$course->id,
            'timeopen' => (int)$quiz->timeopen,
            'timeclose' => (int)$quiz->timeclose,
            'timelimit' => (int)$quiz->timelimit,
        ];
    }

    public static function set_quiz_access_times_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the update was successful'),
            'quizid' => new external_value(PARAM_INT, 'Quiz instance id'),
            'courseid' => new external_value(PARAM_INT, 'Course id'),
            'timeopen' => new external_value(PARAM_INT, 'Open time (unix timestamp)'),
            'timeclose' => new external_value(PARAM_INT, 'Close time (unix timestamp)'),
            'timelimit' => new external_value(PARAM_INT, 'Time limit (seconds)'),
        ]);
    }
}
