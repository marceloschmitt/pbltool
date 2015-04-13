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



function block_pbltool_print_page($courseid,$blockid,$groupid,$tab='description',$teacher=false,$return = FALSE) {
    global $CFG, $USER, $COURSE,$DB, $OUTPUT;
    include_once($CFG->dirroot.'/lib/filelib.php');
    require_once('project_description_form.php');
    require_once('tabs.php');

    $output = '';
    // Get block instance
    $record = new stdClass;
    $instance = new stdClass;
    if(!$record = $DB->get_record('block_instances', array('id'=>$blockid))){
        error(get_string('geterror', 'block_pbltool'));
    }
    $instance = block_instance('pbltool',$record);
    $forum = $instance->forum;
    $chat = $instance->chat;

    $context = context_system::instance();
    if(has_capability('block/pbltool:managepages', $context))
    { 
        $output .= $OUTPUT->heading($instance->title.' ('.
        						get_string('teacher','block_pbltool').'): '.
        						date('d/m/y',$instance->date_begin).' - '.
        						date('d/m/y',$instance->date_finish), 
        						 2, 'main', TRUE);
        						
        $output .= tab($courseid,$blockid,$forum,$chat,$groupid,$tab);
        $output .= print_groups($blockid,$courseid,$forum,$chat,$groupid,$tab);
        if($groupid)
            switch($tab)
            {
                case 'description': $output .= print_project_description($courseid,$blockid,$groupid,$forum,$chat,true);
                                    break;

                case 'tasks':       $output .= print_project_tasks($courseid,$blockid,$groupid,$forum,$chat,$teacher);
                                    break;
            }

    }
    else
    { 
        $aux = groups_get_all_groups($courseid,$USER->id);
        if(!$aux)
        {           print_error(get_string('nogroup user', 'block_pbltool'),"block_pbltool");
        }
        foreach($aux as $group);
        $output .= $OUTPUT->heading($instance->title.' ('.get_string('group','block_pbltool').
        						$group->name.'): '.
        						date('d/m/y',$instance->date_begin).' - '.
        						date('d/m/y',$instance->date_finish), 
        						2, 'main', TRUE);
        $output .= tab($courseid,$blockid,$forum,$chat,$group->id,$tab);
    	if($group->id) {
    		$output .= $OUTPUT->box_start('generalbox','Grou menber');
    		$output .= '<b>'.get_string('group_members','block_pbltool').': </b>';
    		$users = groups_get_members($group->id, $fields='u.*', $sort='firstname ASC');
	    	foreach($users AS $user){
	    		$output .= ' - '.	$user->firstname.' '.$user->lastname;
	    	}
	    }
	    $output .= $OUTPUT->box_end();
	    
        switch($tab)
        {
            case 'description': $output .= print_project_description($courseid,$blockid,$group->id,$forum,$chat);
                                break;

            case 'tasks':       $output .= print_project_tasks($courseid,$blockid,$group->id,$forum,$chat,$teacher);
                                break;
          /*  case 'map':         $output .= print_map($courseid,$blockid,$group->id,$teacher);
                                break;
*/
        }

    }




    if ($return) {
        return $output;
    }
    else {

        print $output;
    }

}



function print_project_description($courseid,$blockid,$groupid,$forum,$chat,$admin=false)
{

    global $CFG,$USER,$DB,$OUTPUT,$PAGE;
 
     
    if(!$record = $DB->get_record('block_pbltool_projects', array('blockid' => $blockid, 'groupid' => $groupid)))
    {
 $record = new stdClass();
        $record->blockid = $blockid;
        $record->groupid = $groupid;
        $record->description = get_string('description','block_pbltool');
        $record->status = 0;
        if(!$DB->insert_record('block_pbltool_projects',$record))
        {   error(get_string('inserterror' , 'block_pbltool'));
        }
    }

	/********* Log Description view **********/
        $event = \block_pbltool\event\view_description::create(array(
                'objectid' => $blockid,
                'context'=> $PAGE->context,
                'other'=> "$admin : $groupid",
                ));
        $event->trigger();
	/****************************************/

     $output = $OUTPUT->box_start('generalbox', '' );
     $output .= '<center><h4>'.get_string('description','block_pbltool').'</H4>';
     if($record->status) {
        $output .= ' ('.get_string('approved','block_pbltool').')';
     }
     else {
        $output .= ' ('.get_string('discussion','block_pbltool').')';
     }
     $output .= '<br></center>';
     $output .= $OUTPUT->box_end();
     $output .= $OUTPUT->box_start('coursebox');
     $output .= $record->description;
     $output .= $OUTPUT->box_end();
     $output .= '<center> <table><tr>';
     if(!$record->status) {
        $output .= '<td>';
        $output .= "<form action=\"edit_project_description.php\" method=\"get\">";
        $output .= "<input type=\"hidden\" name=\"courseid\" value=\"$courseid\" />";
        $output .= "<input type=\"hidden\" name=\"blockid\" value=\"$blockid\" />";
        $output .= "<input type=\"hidden\" name=\"groupid\" value=\"$groupid\" />";
        $output .= "<input type=\"submit\" value=\"".get_string('edit','block_pbltool')."\" >";
        $output .= "</form>";
        $output .= '</td>';

        if($admin) {
            $output .= '<td>';
            $output .= "<form action=\"description_accept.php\" method=\"get\">";
            $output .= "<input type=\"hidden\" name=\"courseid\" value=\"$courseid\" />";
            $output .= "<input type=\"hidden\" name=\"blockid\" value=\"$blockid\" />";
            $output .= "<input type=\"hidden\" name=\"groupid\" value=\"$groupid\" />";
            $output .= "<input type=\"submit\" value=\"".get_string('approve','block_pbltool')."\" >";
            $output .= "</form>";
            $output .= '</td>';

        }
     }
     else {
        if($admin) {
            $output .= '<td>';
            $output .= "<form action=\"description_open.php\" method=\"get\">";
            $output .= "<input type=\"hidden\" name=\"courseid\" value=\"$courseid\" />";
            $output .= "<input type=\"hidden\" name=\"blockid\" value=\"$blockid\" />";
            $output .= "<input type=\"hidden\" name=\"groupid\" value=\"$groupid\" />";
            $output .= "<input type=\"submit\" value=\"".get_string('put_in_discussion','block_pbltool')."\" >";
            $output .= "</form>";
            $output .= '</td>';
        }
     }
     $output .= '</tr></table>';


         return $output;
}


function print_project_tasks($courseid,$blockid,$groupid,$forum,$chatid,$teacher)
{
    global $CFG;
    global $USER;
    global $DB;
    global $OUTPUT;
	global $PAGE;

     
    if(!$record = $DB->get_record('block_pbltool_projects', array('blockid' => $blockid, 'groupid' => $groupid)))
    {
       error('tentou acessar a página de tarefas antes de criar a descrição');
    }
    
    /* Botão para inserir tarefa */
    $output = '<center>';
    $output .= "<form action=\"add_task.php\" method=\"get\">";
    $output .= "<input type=\"hidden\" name=\"courseid\" value=\"$courseid\" />";
    $output .= "<input type=\"hidden\" name=\"blockid\" value=\"$blockid\" />";
    $output .= "<input type=\"hidden\" name=\"groupid\" value=\"$groupid\" />";
    $output .= "<input type=\"hidden\" name=\"project\" value=\"$record->id\" />";
    $output .= "<input type=\"submit\" value=\"".get_string('add_task','block_pbltool')."\" >";
    $output .= "</form>";
    
    $project = $record->id;
    $recordset = $DB->get_records("block_pbltool_tasks",array('project'=>$project),'timebegin, timeplannedend');
    $output .= $OUTPUT->box_start('generalbox', '' );
    $output .= '<center><H4>'.get_string('tasks','block_pbltool').'</H4></center>';
    if($recordset){
        $output .= '<table width="100%"  cellpadding="5" cellspacing="1" class="generaltable boxaligncenter">';
        $output .= '<tr>
                <th style="vertical-align:top; text-align:left;;white-space:nowrap;" class="header c0 firstcol" scope="col">'.get_string('title','block_pbltool').'</th>
                <th style="vertical-align:top; text-align:center;;white-space:nowrap;" class="header c1" scope="col">'.get_string('begin_date','block_pbltool').'</th>
                <th style="vertical-align:top; text-align:center;;white-space:nowrap;" class="header c2" scope="col">'.get_string('finish_date','block_pbltool').'</th>
                <th style="vertical-align:top; text-align:center;;white-space:nowrap;" class="header c3" scope="col">'.get_string('Progress','block_pbltool').'</th>
                <th style="vertical-align:top; text-align:center;;white-space:nowrap;" class="header c4" scope="col">'.get_string('Status','block_pbltool').'</th>
                <th style="vertical-align:top; text-align:center;;white-space:nowrap;" class="header c5 lastcol" scope="col">'.get_string('Actions','block_pbltool').'</th></tr>';
        $rowtype = '0';
        
        foreach($recordset as $row) {
            $output .= '<tr class="r'.$rowtype.'">';
            $output .= '<td style=" text-align:left;" class="cell c0">'.substr($row->name,0,50).'</td>
                        <td style=" text-align:center;" class="cell c1 ">'.date('d/m/y',$row->timebegin).'</td>
                        <td style=" text-align:center;" class="cell c2 ">'.date('d/m/y',$row->timeplannedend).'</td>
                        <td style=" text-align:center;" class="cell c3 ">';
            $output.=$row->progress.'%';
            if($row->status == 4 || $row->status == 5)
                $output .= ' ('.date('d/m/y',$row->timefinished).')';
                        
            $output .= '</td>';
 			$output .= '<td style=" text-align:center;" class="cell c4"><img src="images/';
	            switch($row->status)
	            {	case 0: $output .= 'working.png';
	            			break;
	            	case 1: $output .= 'question.png';
	            			break;
		            case 2: $output .= 'ongoing.png';
		            		break;
		            case 3: $output .= 'recycle.png';
		            		break;
		            case 4: $output .= 'pointing.png';
		            		break;
		            case 5: $output .= 'checkmark.png';
		            		break;
		            case 6: $output .= 'redcross.png';
		            		break;
	            }
            $output .= '" width="20"></td>';
                        
            /* Print action buttons */
           	$output .= '<td style=" text-align:left" class="cell c5 lastcol">';
           	$output .= '<a href='.$CFG->wwwroot.'/blocks/pbltool/edit_task.php?courseid='.$courseid.'&blockid='.$blockid.'&groupid='.$groupid.'&id='.$row->id.'>
                            <img src="'.$OUTPUT->pix_url('t/edit').'" alt="'.get_string('edit').'"></a>&nbsp';
	        if($teacher){
	        	$output .= '<a href='.$CFG->wwwroot.'/blocks/pbltool/delete_task.php?courseid='.$courseid.'&blockid='.$blockid.'&groupid='.$groupid.'&id='.$row->id.'>
                            <img src="'.$OUTPUT->pix_url('t/delete').'" alt="'. get_string('delete').'"></a>&nbsp;&nbsp;';
	        	switch($row->status) {
	        		case 1: $output .= '<a href='.$CFG->wwwroot.'/blocks/pbltool/task_change_status.php?courseid='.$courseid.'&blockid='.$blockid.'&groupid='.$groupid.'&id='.$row->id.'&status=2>
	        							<img src="images/ongoing.png" width="15"></a>&nbsp;&nbsp;';
	        				$output .= '<a href='.$CFG->wwwroot.'/blocks/pbltool/task_change_status.php?courseid='.$courseid.'&blockid='.$blockid.'&groupid='.$groupid.'&id='.$row->id.'&status=3>
	        							<img src="images/recycle.png" width="15"></a>&nbsp;&nbsp;';
	        				break;
	        		case 4: $output .= '<a href='.$CFG->wwwroot.'/blocks/pbltool/task_change_status.php?courseid='.$courseid.'&blockid='.$blockid.'&groupid='.$groupid.'&id='.$row->id.'&status=5>
	        							<img src="images/checkmark.png" width="15"></a>&nbsp;&nbsp;';
	        				$output .= '<a href='.$CFG->wwwroot.'/blocks/pbltool/task_change_status.php?courseid='.$courseid.'&blockid='.$blockid.'&groupid='.$groupid.'&id='.$row->id.'&status=6>
	        							<img src="images/redcross.png" width="15"></a>';
	        				break;
	        		default:
	        	}
	        }
	        else {
	        	switch($row->status) {
	        		case 0: 
	        		case 3:	$output .= '<a href='.$CFG->wwwroot.'/blocks/pbltool/delete_task.php?courseid='.$courseid.'&blockid='.$blockid.'&groupid='.$groupid.'&id='.$row->id.'>
                            <img src="'.$OUTPUT->pix_url('t/delete').'" alt="'. get_string('delete').'"></a>&nbsp;&nbsp;';
	        				$output .= '<a href='.$CFG->wwwroot.'/blocks/pbltool/task_change_status.php?courseid='.$courseid.'&blockid='.$blockid.'&groupid='.$groupid.'&id='.$row->id.'&status=1>
                            			<img src="images/question.png" width="15"></a>&nbsp;&nbsp;';
	        				break;
	        		case 2: 
	        		case 6: $output .= '<a href='.$CFG->wwwroot.'/blocks/pbltool/task_change_status.php?courseid='.$courseid.'&blockid='.$blockid.'&groupid='.$groupid.'&id='.$row->id.'&status=4>
	        							<img src="images/pointing.png" width="15"></a>&nbsp;';
	        				break;
	        		
	        		
	        	}
	        }
	        $output .= '</tr>';
	        /* End printing action buttons */
                       
            $rowtype = ($rowtype + 1) % 2;
        }
        $output .= '</table>';
    }
    else
        $output .= 'nenhuma tarefa definida';
    $output .= $OUTPUT->box_end();
   

    $output .= print_legend();


        /********* Log Task view **********/
        $event = \block_pbltool\event\view_tasks::create(array(
           	'objectid' => $blockid,
                'context'=> $PAGE->context,
                'other'=> "$teacher : $groupid",
                ));
        $event->trigger();
        /****************************************/

    return $output;
}


function print_legend()
{
	$legend = array('working','question','ongoing','recycle','pointing','checkmark','redcross');
    $output = '<table width="80%"  cellpadding="5" cellspacing="1" >';
    foreach($legend AS $x) {
    	$output .= '<tr>';
		$output .= '<td width = "30" style=" text-align:left;" class="cell c0"><img src="images/'.$x.'.png" width="20"></td>';
		$output .= '<td style=" text-align:left;" class="cell c1 lastcol">'.get_string($x,'block_pbltool').'</td>';
		$output .= "</tr>";
    }
	$output .= "</table>";
	
	return $output;
}

function print_map($courseid,$blockid,$groupid,$teacher)
{
global $USER,$DB,$PAGE;

// Get block instance
$record = new stdClass;
$instance = new stdClass;
if(!$record = $DB->get_record('block_instances', array('id'=>$blockid))){
    echo "Erro a ser refeito";
    exit;
}

$instance = block_instance('pbltool',$record);


require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_gantt.php');
$graph = new GanttGraph();
$graph->title->Set(date('d/m/y',$instance->date_begin).' - '.date('d/m/y',$instance->date_finish));
$graph->title->Set($instance->name);
$graph->title->SetFont(FF_FONT2,FS_BOLD,12);
$graph->subtitle->Set(date('d/m/y',$instance->date_begin).' - '.date('d/m/y',$instance->date_finish));

// Setup some "very" nonstandard colors
$graph->SetMarginColor('lightgreen@0.8');
$graph->SetBox(true,'yellow:0.6',2);
$graph->SetFrame(true,'darkgreen',4);
$graph->scale->divider->SetColor('yellow:0.6');
$graph->scale->dividerh->SetColor('yellow:0.6');

// Explicitely set the date range
// (Autoscaling will of course also work)
$graph->SetDateRange(date('Y-m-d',$instance->date_begin),date('Y-m-d',$instance->date_finish));

$timestamp_start = $instance->date_begin;
$timestamp_end = $instance->date_finish;
$difference = abs($timestamp_end - $timestamp_start); // that's it!
$months = floor($difference/(60*60*24*30));

if($months < 5)
{
	$graph->scale->week->SetStyle(WEEKSTYLE_FIRSTDAY2);
	$graph->ShowHeaders(GANTT_HDAY | GANTT_HWEEK | GANTT_HMONTH | GANTT_HYEAR);
}
else
        $graph->ShowHeaders(GANTT_HWEEK | GANTT_HMONTH | GANTT_HYEAR);

$graph->scale->month->grid->SetColor('gray');
$graph->scale->month->grid->Show(true);
$graph->scale->year->grid->SetColor('gray');
$graph->scale->year->grid->Show(true);

// For the titles we also add a minimum width of 100 pixels for the Task name column
$graph->scale->actinfo->SetColTitles(
    array('Name','Start','Finish'),array(100));
$graph->scale->actinfo->SetBackgroundColor('green:0.5@0.5');
//$graph->scale->actinfo->SetFont(FF_FONT1,FS_NORMAL,10);
$graph->scale->actinfo->vgrid->SetStyle('solid');
$graph->scale->actinfo->vgrid->SetColor('gray');

if(!$record = $DB->get_record('block_pbltool_projects', array('blockid' => $blockid, 'groupid' => $groupid)))
    {
       error('tentou acessar a página de tarefas antes de criar a descrição');
    }
$project = $record->id;
if(!$recordset = $DB->get_records("block_pbltool_tasks",array("project"=>$project)))
{
               echo('Não há tarefas');
               exit;
}

$task = 0;
foreach($recordset as $row) {
	$data[$task] = array($task,array("$row->name", date('y-m-d',$row->timebegin),date('y-m-d',$row->timeplannedend))
          , date('y-m-d',$row->timebegin),date('y-m-d',$row->timeplannedend),FF_ARIAL,FS_NORMAL,8);
	$data_aux[$task] = $row->progress;
	$task++;
}


// Create the bars and add them to the gantt chart
for($i=0; $i<count($data); ++$i) {
    $bar = new GanttBar($data[$i][0],$data[$i][1],$data[$i][2],$data[$i][3],$data_aux[$i]."%",20);
  //  if( count($data[$i])>4 )
 //       $bar->title->SetFont($data[$i][4],$data[$i][5],$data[$i][6]);
    $bar->SetPattern(BAND_RDIAG,"yellow");
    $bar->SetFillColor("gray");
    $bar->progress->Set($data_aux[$i]/100);
    $bar->progress->SetPattern(GANTT_SOLID,"darkgreen");
    $graph->Add($bar);
}
// Create a miletone
$milestone = new MileStone($i+1,'',date('Y-m-d',time()),date('Y-m-d',time()));
$milestone->title->SetColor("black");
$milestone->title->SetFont(FF_FONT1,FS_BOLD);
$graph->Add($milestone);
 


// Output the chart
$graph->Stroke();

        /********* Log Gantt view **********/
        $event = \block_pbltool\event\view_gantt::create(array(
                'objectid' => $blockid,
                'context'=> $PAGE->context,
                'other'=> "$teacher : $groupid",
                ));
        $event->trigger();
        /****************************************/
}



// Print groups for the administration to select what he wants to see
function print_groups($blockid,$courseid,$forum,$chatid,$groupid,$tab)
{
    global $CFG, $OUTPUT;
    $output = '';
    $aux = groups_get_all_groups($courseid);
    $output .= $OUTPUT->box_start('generalbox','Grupos');

    if(!$aux) // Se não foram definidos grupos
    {
        $output .= get_string('nogroup','block_pbltool');
    }
    foreach($aux as $group)
    {   if($group->id == $groupid) // Selected group
            $output .= ' | <B>'.$group->name.'</B> | ';
        else
        {   $output .= '<a href="'.$CFG->wwwroot;
            switch($tab) {
                case 'description': $output .= '/blocks/pbltool/view.php';
                                    break;
                case 'tasks':       $output .= '/blocks/pbltool/view_tasks.php';
                                    break;
            }
            $output .= '?blockid='.$blockid.'&courseid='.$courseid.'&groupid='.$group->id.
                       '"> |  '.$group->name.' |  </a>';
        }
    }
	if($groupid) {
			$output .= '<br>';
	    	$users = groups_get_members($groupid, $fields='u.*', $sort='firstname ASC');
	    	if($users)
		    	foreach($users AS $user){
		    		$output .= ' - '.$user->firstname.' '.$user->lastname;
		    	}
		    else
		    	$output .= "<h4>".get_string('no_users','block_pbltool')."</h4>"; 
	    }
    $output .= $OUTPUT->box_end();
    return $output;
}