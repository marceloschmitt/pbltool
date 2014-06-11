<?php
require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot.'/blocks/pbltool/lib.php');
 
class task_form extends moodleform {
    private $teacher = false;
    private $status = 0;
    
 
    function __construct($teacher = 0, $status = 0, $view = false)
    {	$this->teacher = $teacher;
    	$this->status = $status;
    	$this->view = $view;
    	parent::__construct();
    }


 
    function definition() {
        global $CFG;
        global $COURSE;
 
        $mform =& $this->_form;   
        $mform->addElement('header', 'displayinfo', get_string('taskdefinition', 'block_pbltool'));


        // add
        $mform->addElement('text','name',get_string('tasktitle','block_pbltool'),array('size' => 50, 'maxlength' => 100));
        $mform->setType('name', PARAM_RAW);
        $mform->addRule('name', null, 'required', null, 'client');

        // add display text field
        $mform->addElement('htmleditor', 'description', get_string('taskdescription', 'block_pbltool'));
        $mform->setType('description', PARAM_RAW);
        $mform->addRule('description', null, 'required', null, 'server');
        $mform->addElement('date_selector', 'timebegin', get_string('begin_date','block_pbltool'));
        $mform->addElement('date_selector', 'timeplannedend', get_string('finish_date','block_pbltool'));
        $progress_array = array(0=>'0%',25=>'25%',50=>'50%',75=>'75%',100=>'100%');
	$mform->addElement('select','progress',get_string('Progress','block_pbltool'),$progress_array);
        $mform->addElement('hidden','project');
 	$mform->setType('project', PARAM_INT);
	$mform->addElement('hidden','courseid');
 	$mform->setType('courseid', PARAM_INT);
	$mform->addElement('hidden','groupid');
 	$mform->setType('groupid', PARAM_INT);
	$mform->addElement('hidden','blockid');
 	$mform->setType('blockid', PARAM_INT);
	$mform->addElement('hidden','id');
 	$mform->setType('id', PARAM_INT);
	$mform->addElement('hidden','prev_status');
 	$mform->setType('prev_status', PARAM_INT);
        
	$legend = array(get_string('working','block_pbltool'),
		get_string('question','block_pbltool'),
        	get_string('ongoing','block_pbltool'),get_string('recycle','block_pbltool'),
        	get_string('pointing','block_pbltool'),get_string('checkmark','block_pbltool'),
        	get_string('redcross','block_pbltool'));
        $mform->addElement('select','status',get_string('Status','block_pbltool'),$legend);
        if($this->teacher == false){
        	$mform->getElement('status')->freeze();
        } 
	// If not teacher and not planning, freeze descriptions 
        if(!$this->teacher && $this->status) {	
	        $mform->getElement('name')->freeze();
	        $mform->getElement('description')->freeze();
	        $mform->getElement('timebegin')->freeze();
	        $mform->getElement('timeplannedend')->freeze();
	        $mform->getElement('status')->freeze();
        }

	// If not teacher and not working, freeze progress
	if(!$this->teacher && $this->status != 2) {
	        $mform->getElement('progress')->freeze();
        } 

	// If not teacher and not planning and not working, just look
        if(!$this->teacher && $this->status != 0 && $this->status!= 2) {
        	$mform->addElement('submit','cancel',get_string('Back_to_task_list','block_pbltool'));
	        $mform->closeHeaderBefore('cancel');
        }
        else 
        	$this->add_action_buttons();
    } 
} 
?> 
