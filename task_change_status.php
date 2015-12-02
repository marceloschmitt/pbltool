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


require_once('../../config.php');

global $DB;
$courseid = required_param('courseid',PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$id = required_param('id', PARAM_INT);
$status = required_param('status', PARAM_INT);

include("authorization.php");

unset($toform);
$toform = new stdClass();
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