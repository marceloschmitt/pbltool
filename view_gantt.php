<?php 
// include moodle API and any supplementary files/API
require_once('../../config.php');
require_once('lib.php');
 
// declare any globals we need to use
global $CFG, $USER, $DB;
 
// check for all required variables
$blockid = required_param('blockid', PARAM_INT);
$courseid = required_param('courseid',PARAM_INT);
$groupid = required_param('groupid',PARAM_INT);

include('authorization.php');

print_map($courseid,$blockid,$groupid,$teacher);
?>
