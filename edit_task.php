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
global $CFG, $USER,$DB, $PAGE;

require_once('task_form.php');
$courseid = required_param('courseid',PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$id = optional_param('id',0, PARAM_INT);

include('authorization.php');

if (!$toform = $DB->get_record('block_pbltool_tasks', array('id' => $id)))
        error(get_string('nopage', 'block_pbltool', $id));
        
$form = new task_form($teacher,$toform->status);

/************************* ainda vai ter de testar id **************************/
if ($form->is_cancelled())
    redirect("$CFG->wwwroot/blocks/pbltool/view_tasks.php?blockid=$blockid&courseid=$courseid&groupid=$groupid");

else if ($fromform = $form->get_data())
{
	if(($fromform->prev_status < 4 || $fromform->prev_status == 6) && 
			($fromform->status == 4 || $fromform->status == 5))
	{	$fromform->timefinished = time();
	}

    if (!$DB->update_record('block_pbltool_tasks', $fromform))
            print_error(get_string('updateerror', 'block_pbltool'));

        /********* Log Task update **********/
        $event = \block_pbltool\event\update_task::create(array(
                'objectid' => $blockid,
                'context'=> $PAGE->context,
                'other'=> "$teacher : $groupid - Task $id",
                ));
        $event->trigger();
        /****************************************/

    redirect("$CFG->wwwroot/blocks/pbltool/view_tasks.php?blockid=$blockid&courseid=$courseid&groupid=$groupid");
}
else 
{ 
    //form didn't validate or this is the first display

    	$toform->blockid = $blockid;
    	$toform->courseid = $courseid;
    	$toform->groupid = $groupid;
    	$toform->id=$id;
	$toform->prev_status=$toform->status;

	$params = array();
	$params['blockid'] = $blockid;
	$params['courseid'] = $courseid;
	$params['groupid'] = $groupid;
	$params['id'] = $id;

	$PAGE->set_url('/blocks/pbltool/edit_task.php', $params);
	$PAGE->set_title(get_string('pluginname','block_pbltool'));
	$PAGE->set_heading($course->fullname);
	$PAGE->set_pagelayout('print');

	echo $OUTPUT->header();

    	$form->set_data($toform); 
    	$form->display(); 

	echo $OUTPUT->footer();
}
