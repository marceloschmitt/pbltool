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
global $CFG, $USER, $SITE, $DB;

$courseid = required_param('courseid',PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$id = required_param('id', PARAM_INT);
$confirm = optional_param('confirm',0,PARAM_INT);

include('authorization.php');

/* verifica se a tarefa existe */
	if(! $pbltoolpage = $DB->get_record('block_pbltool_tasks', array('id' => $id))){
		error(get_string('nopage','block_pbltool',$id));
	}

        $params = array();
        $params['blockid'] = $blockid;
        $params['courseid'] = $courseid;
        $params['groupid'] = $groupid;
        $params['id'] = $id;

        $PAGE->set_url('/blocks/pbltool/delete_task.php', $params);
        $PAGE->set_title(get_string('pluginname','block_pbltool'));
        $PAGE->set_heading($course->fullname);
        $PAGE->set_pagelayout('print');

        echo $OUTPUT->header();
	if(!$confirm){
    		$optionsno = array('courseid'=>$courseid,'blockid'=>$blockid,'groupid'=>$groupid);
    		$optionsyes = array ('id'=>$id,'courseid'=>$courseid,'blockid'=>$blockid,'groupid'=>$groupid,'confirm'=>1, 'sesskey'=>sesskey());
    		echo $OUTPUT->heading(get_string('confirmdelete', 'block_pbltool'),2);
    		echo $OUTPUT->confirm(get_string('deletepage', 'block_pbltool'),
                   new moodle_url('delete_task.php',$optionsyes),
	           new moodle_url($CFG->wwwroot.'/blocks/pbltool/view_tasks.php',$optionsno));
	}
	else { 
    		if (confirm_sesskey()) {
        		if (! $DB->delete_records('block_pbltool_tasks',array('id' => $id))) {
            			error('deleterror','block_pbltool');
        		}
 
			/********* Log Task delete: **********/
        		$event = \block_pbltool\event\delete_task::create(array(
                		'objectid' => $blockid,
                		'context'=> $PAGE->context,
                		'other'=> "$teacher : $groupid - Task $id",
                	));
        		$event->trigger();
       			/****************************************/        
    		}
    		else {
        		error('sessionerror','block_pbltool');
    		}
    		redirect("$CFG->wwwroot/blocks/pbltool/view_tasks.php?courseid=$courseid&blockid=$blockid&groupid=$groupid");
	}
	echo $OUTPUT->footer();