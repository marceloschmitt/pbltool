<?php
class block_pbltool extends block_base {

// Fun��o de inicializa��o
    function init() {
        $this->title = get_string('pbltool', 'block_pbltool');
	$this->name = get_string('Not_configured', 'block_pbltool');
	$this->date_begin = time();
	$this->date_finish = time();
     }
    

// Fun��o para escrever no bloco
    function get_content() {
        global $COURSE, $CFG ;
        if ($this->content !== NULL) {
            return $this->content;
        }
        $this->content = new stdClass;
	$this->content->text = '<a href="'.$CFG->wwwroot.'/blocks/pbltool/view.php?blockid='.$this->instance->id.
                                '&courseid='.$COURSE->id.'"><b>'.$this->name.'</b></a><br>';
//        $this->content->text .= $this->config->text; // coloca em content o que foi configurado
		$this->content->footer = get_string('begin_date','block_pbltool').': '.date('d/m/y',$this->date_begin);
		$this->content->footer .= '<br>'.get_string('finish_date','block_pbltool').': '.date('d/m/y',$this->date_finish);
                                   
            // vari�vel anota se pode editar o bloco

        return $this->content;
       
    }

//Fun��o que permite customiza��o do bloco (bot�o Edit)
// a partir do arquivo config_instance.html
    function instance_allow_config() {
        return true;
    }

 // Fun��o para remover um bloco
    function instance_delete(){
	global $DB;
    	$recordset = $DB->get_records("block_pbltool_projects",array('blockid'=>$this->instance->id));
    	foreach($recordset AS $row) {
    		 $DB->delete_records('block_pbltool_tasks',array('project'=> $row->id));
    	}
        $DB->delete_records('block_pbltool_projects',array('blockid' => $this->instance->id));
    }

// Fun��o a ser chamada ap�s init()
    function specialization() {
    	
	if(empty($this->config->forum) || empty($this->config->chat))
       		$this->alert = 'Forum/chat not configured'; 
	if(!empty($this->config->name)) 
       		$this->name = $this->config->name; 
	if(!empty($this->config->date_begin)) 
       		$this->date_begin = $this->config->date_begin;
	if(!empty($this->config->date_finish)) 
       		$this->date_finish = $this->config->date_finish; 
	if(!empty($this->config->forum)) 
       		$this->forum = $this->config->forum; 
	if(!empty($this->config->chat)) 
       		$this->chat = $this->config->chat; 
    
	}
 
// Fun��o que permite m�ltiplas inst�ncias em um curso
    function instance_allow_multiple() {
        return true;
    }

// Fun��o que permite a configura��o global
// a partir do arquivo config_global.html e
// da fun��o config_save()
    function has_config() {
        return false;
    }

// Fun��o para ser utilizada em conjunto com a
// configura��o global
    function config_save($data) {
        if(isset($data['block_pbltool_strict'])) {
            set_config('block_pbltool_strict', '1');
        }
        else {
            set_config('block_pbltool_strict', '0');
        }
        return true;
    }

// Fun��o para ser utilizada em conjunto com a
// configura��o global
    function instance_config_save($data,$nolongerused=false) {
        // Clean the data if we have to
        global $CFG;
        if(!empty($CFG->block_pbltool_strict)) {
            $data->text = strip_tags($data->text);
        }

        // And now forward to the default implementation defined in the parent class
        return parent::instance_config_save($data);
    }

//Fun��es para controlar o visual
    function hide_header() {
        return false;
    }
/*    function preferred_width() {
        // The preferred value is in pixels
        return 200;
    } 
    function html_attributes() {
        return array(
            'class'       => 'sideblock block_'. $this->name(),
        );
    }
*/
// Fun��o para determinar onde o m�dulo aparece
    function applicable_formats() {
     return array('all' => true);
     return array('site-index' => true,
                     'course-view' => true,
                     'mod' => true);
    }
}
