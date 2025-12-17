<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/course/lib.php');

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
}
