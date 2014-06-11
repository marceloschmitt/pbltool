<?php
global $DB;
require_once('../../config.php');

$courseid = required_param('courseid',PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);

//ensure we have a valid courseid and can load the associated course object
if (! $course = $DB->get_record('course', array('id' => $courseid)) ) {
    error(get_string('invalidcourse', 'block_pbltool', $courseid));
}

// ensure the user has access to this course
require_login($course);

// ensure the block exits
if (! $block = $DB->get_record("block_instances", array("id" => $blockid))) {
    error("Block ID was incorrect or no longer exists");
}

// ensure the block belongs to the course
//if ($block->pageid != $courseid) {
//    error("Block ID does not belong to course");
//}


require_capability('block/pbltool:managepages', get_context_instance(CONTEXT_COURSE, $courseid));


$record = $DB->get_record('block_pbltool_projects', array('blockid' => $blockid, 'groupid' => $groupid));
$toform->id = $record->id;
$toform->status = 1;

if (!$DB->update_record('block_pbltool_projects', $toform))
    print_error(get_string('updateerror', 'block_pbltool'));

         
        /********* Log Accept Description **********/
        $event = \block_pbltool\event\accept_description::create(array(
                'objectid' => $blockid,
                'context'=> $PAGE->context,
                'other'=> "1 : $groupid",
                ));
        $event->trigger();
        /****************************************/


redirect("$CFG->wwwroot/blocks/pbltool/view.php?blockid=$blockid&courseid=$courseid&groupid=$groupid");

?>
