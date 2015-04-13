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
global $CFG, $USER;

require_once('task_form.php');
$courseid = required_param('courseid',PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$projectid = required_param('project',PARAM_INT);

include('authorization.php');

$form = new task_form($teacher);

/************************* tem de testar o project **************************/


if ($form->is_cancelled())
{   redirect("$CFG->wwwroot/blocks/pbltool/view_tasks.php?blockid=$blockid&courseid=$courseid&groupid=$groupid");
}
else if ($fromform = $form->get_data())
{
    $fromform->timecreated = time();
    if (!($x = $DB->insert_record('block_pbltool_tasks', $fromform)))
        error(get_string('updateerror', 'block_pbltool'));
                     
        /********* Log Task creation **********/
        $event = \block_pbltool\event\add_task::create(array(
                'objectid' => $blockid,
                'context'=> $PAGE->context,
                'other'=> "$teacher : $groupid - Task $x",
                ));
        $event->trigger();
        /****************************************/
                    
    redirect("$CFG->wwwroot/blocks/pbltool/view_tasks.php?blockid=$blockid&courseid=$courseid&groupid=$groupid");
}
else
{
    //form didn't validate or this is the first display
    $toform = new stdClass;
    $toform->blockid = $blockid;
    $toform->courseid = $courseid;
    $toform->groupid = $groupid;
    $toform->project=$projectid;

	$params = array();
        $params['blockid'] = $blockid;
        $params['courseid'] = $courseid;
        $params['groupid'] = $groupid;
        $params['projectid'] = $projectid;

        $PAGE->set_url('/blocks/pbltool/add_task.php', $params);

$PAGE->set_title(get_string('pbltool','block_pbltool'));
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('print');
echo $OUTPUT->header();
    $form->set_data($toform);

    $form->display();
echo $OUTPUT->footer();
}