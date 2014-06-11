<?php
// include moodle API and any supplementary files/API
require_once('../../config.php');
require_once('lib.php');

// declare any globals we need to use
global $CFG, $USER;

// check for all required variables
$blockid = required_param('blockid', PARAM_INT);
$courseid = required_param('courseid',PARAM_INT);
$groupid = optional_param('groupid',0,PARAM_INT);

$params['blockid'] = $blockid;
$params['courseid'] = $courseid;
$params['groupid'] = $groupid;

// Test if user has rights to access the page
include('authorization.php');
 
$PAGE->set_url('/blocks/pbltool/view.php', $params);
$PAGE->set_title(get_string('pluginname','block_pbltool'));
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('print');

echo $OUTPUT->header();
block_pbltool_print_page($courseid,$blockid,$groupid);
echo $OUTPUT->footer();
?>
