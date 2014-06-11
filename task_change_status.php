<?php
require_once('../../config.php');

global $DB;
$courseid = required_param('courseid',PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$id = required_param('id', PARAM_INT);
$status = required_param('status', PARAM_INT);

include("authorization.php");

unset($toform);
$toform->id = $id;
$toform->status = $status;
if($status == 4) {
	$toform->timefinished = time();
	$toform->progress = 100;
}
if($status == 6) {
	$toform->timefinished = 0;
	$toform->progress = 75;
}

if (!$DB->update_record('block_pbltool_tasks', $toform))
    error(get_string('updateerror', 'block_pbltool'));

/********* Log Task update **********/
$event = \block_pbltool\event\change_task_status::create(array(
         'objectid' => $blockid,
         'context'=> $PAGE->context,
         'other'=> "$teacher : $groupid - Task $id - Status $status",
         ));
$event->trigger();
/****************************************/
redirect("$CFG->wwwroot/blocks/pbltool/view_tasks.php?blockid=$blockid&courseid=$courseid&groupid=$groupid");

?>
