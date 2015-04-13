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



// function to draw tab and call correspondent function
//
function tab($courseid,$blockid,$forum,$chatid,$groupid,$current_tab = 'description')
{
    global $CFG;

    $tabs = array();
    $row  = array();
    $inactive = array();
    $activated = array();
    
   	if(!$groupid)// imprime o map somente se houver grupo
    	$inactive[] = 'map';
    
    $row[] = new tabobject('description', $CFG->wwwroot.'/blocks/pbltool/view.php?blockid='.$blockid.'&courseid='.$courseid.'&groupid='.$groupid, get_string('description','block_pbltool'));
    $row[] = new tabobject('tasks', $CFG->wwwroot.'/blocks/pbltool/view_tasks.php?blockid='.$blockid.'&courseid='.$courseid.'&groupid='.$groupid , get_string('tasks','block_pbltool'));

    $row[] = new tabobject('map', $CFG->wwwroot.'/blocks/pbltool/view_gantt.php?blockid='.$blockid.'&courseid='.$courseid.'&groupid='.$groupid.'" onclick="this.target=\'map\'; return openpopup(\'/blocks/pbltool/view_gantt.php?blockid='.$blockid.'&courseid='.$courseid.'&groupid='.$groupid.'\' ,\'map\', \'resizable=1,scrollbars=1,directories=o,location=0,menubar=0,toolbar=0,status=0,width=800,height=450\');', get_string('map','block_pbltool'));
    $row[] = new tabobject('forum', $CFG->wwwroot.'/mod/forum/view.php?f='.$forum . '&group=' . $groupid . '" onclick="this.target=\'forum\'; return openpopup(\'/mod/forum/view.php?f='.$forum   .'&group=' . $groupid .'\' ,\'forum\', \'resizable=1,scrollbars=1,directories=o,location=0,menubar=0,toolbar=0,status=0,width=1000,height=600\');',get_string('forum','block_pbltool'));
    
    if($groupid)
    	$row[] = new tabobject('chat', $CFG->wwwroot.'/mod/chat/gui_header_js/index.php?id='.$chatid.'&groupid='.$groupid. '" onclick="this.target=\'chat\'; return openpopup(\'/mod/chat/gui_header_js/index.php?id='.$chatid.'&groupid='.$groupid .'\' ,\'chat\', \'resizable=1,scrollbars=1,directories=o,location=0,menubar=0,toolbar=0,status=0,width=800,height=450\');',get_string('groupchat', 'block_pbltool'));
    else
    	$row[] = new tabobject('chat', $CFG->wwwroot.'/mod/chat/gui_header_js/index.php?id='.$chatid.'&groupid='.$groupid. '" onclick="this.target=\'chat\'; return openpopup(\'/mod/chat/gui_header_js/index.php?id='.$chatid.'&groupid='.$groupid .'\' ,\'chat\', \'resizable=1,scrollbars=1,directories=o,location=0,menubar=0,toolbar=0,status=0,width=800,height=450\');',get_string('Chat', 'block_pbltool'));
    
    
    if(count($row) > 1) {
        $tabs[] = $row;
        return print_tabs($tabs, $current_tab, $inactive, $activated,true);
    }
}