<?php


/**
* draws the necessary tabs when the viewer enters any page
* the tabs viewed depends whether the viewer is admin/teacher or student
* @author  Pallavi Maiya and Karthik Hebbar C -India, 2009
* @version $Id: index.php,v 1.9 2009/05/31 mudrd8mz Exp $
* @package mod/miniproject
*/

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

?>
