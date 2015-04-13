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
global $CFG, $USER,$DB;
 
require_once('project_description_form.php');
$courseid = required_param('courseid',PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$id = optional_param('id',0, PARAM_INT);

$params['blockid'] = $blockid;
$params['courseid'] = $courseid;
$params['groupid'] = $groupid;
 
include('authorization.php');
 
$form = new project_description_form();
 
if ($form->is_cancelled())
{ 
    redirect("$CFG->wwwroot/blocks/pbltool/view.php?blockid=$blockid&courseid=$courseid&groupid=$groupid");
} 
else if ($fromform = $form->get_data())
{
    if (!$DB->update_record('block_pbltool_projects', $fromform))
        print_error(get_string('updateerror', 'block_pbltool'));


        /********* Log Description update **********/
        $event = \block_pbltool\event\update_description::create(array(
                'objectid' => $blockid,
                'context'=> $PAGE->context,
                'other'=> "$teacher : $groupid",
                ));
        $event->trigger();
        /****************************************/
     
         
    redirect("$CFG->wwwroot/blocks/pbltool/view.php?blockid=$blockid&courseid=$courseid&groupid=$groupid");
}
else
{ 
    //form didn't validate or this is the first display
    if ($id != 0)
    {
        if (!$toform = $DB->get_record('block_pbltool', array('id' => $id)))
            error(get_string('nopage', 'block_pbltool', $id));
    }
    else {
        $toform = new stdClass;
    }
    $toform->blockid = $blockid;
    $toform->courseid = $courseid;
    $toform->groupid = $groupid;


$PAGE->set_url('/blocks/pbltool/edit_project_description.php', $params);
$PAGE->set_title(get_string('pluginname','block_pbltool'));
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('print');

echo $OUTPUT->header();

    $record = $DB->get_record('block_pbltool_projects', array('blockid' => $blockid, 'groupid' => $groupid));
    $toform->description = $record->description;
    $toform->id = $record->id;
    $form->set_data($toform);

    $form->display();
    echo $OUTPUT->footer();
}