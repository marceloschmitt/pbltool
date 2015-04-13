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


class block_pbltool extends block_base {

// Função de inicialização
    function init() {
        $this->title = get_string('pbltool', 'block_pbltool');
	$this->name = get_string('Not_configured', 'block_pbltool');
	$this->date_begin = time();
	$this->date_finish = time();
     }
    

// Função para escrever no bloco
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
                                   
            // variável anota se pode editar o bloco

        return $this->content;
       
    }

//Função que permite customização do bloco (botão Edit)
// a partir do arquivo config_instance.html
    function instance_allow_config() {
        return true;
    }

 // Função para remover um bloco
    function instance_delete(){
	global $DB;
    	$recordset = $DB->get_records("block_pbltool_projects",array('blockid'=>$this->instance->id));
    	foreach($recordset AS $row) {
    		 $DB->delete_records('block_pbltool_tasks',array('project'=> $row->id));
    	}
        $DB->delete_records('block_pbltool_projects',array('blockid' => $this->instance->id));
    }

// Função a ser chamada após init()
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
 
// Função que permite múltiplas instâncias em um curso
    function instance_allow_multiple() {
        return true;
    }

// Função que permite a configuração global
// a partir do arquivo config_global.html e
// da função config_save()
    function has_config() {
        return false;
    }

// Função para ser utilizada em conjunto com a
// configuração global
    function config_save($data) {
        if(isset($data['block_pbltool_strict'])) {
            set_config('block_pbltool_strict', '1');
        }
        else {
            set_config('block_pbltool_strict', '0');
        }
        return true;
    }

// Função para ser utilizada em conjunto com a
// configuração global
    function instance_config_save($data,$nolongerused=false) {
        // Clean the data if we have to
        global $CFG;
        if(!empty($CFG->block_pbltool_strict)) {
            $data->text = strip_tags($data->text);
        }

        // And now forward to the default implementation defined in the parent class
        return parent::instance_config_save($data);
    }

//Funções para controlar o visual
    function hide_header() {
        return false;
    }

// Função para determinar onde o módulo aparece
    function applicable_formats() {
     return array('all' => true);
     return array('site-index' => true,
                     'course-view' => true,
                     'mod' => true);
    }
}
