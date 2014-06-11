<?php
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

?>
