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


 
class block_pbltool_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
        global $DB;

	$id = required_param('id',PARAM_INT);

	$mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        $mform->addElement('text', 'config_name', get_string('configtitle', 'block_pbltool'));
        $mform->setType('config_title', PARAM_RAW);        

	$mform->addElement('date_selector', 'config_date_begin', get_string('begin_date','block_pbltool'));
        $mform->setType('config_date_begin', PARAM_RAW);        

	$mform->addElement('date_selector', 'config_date_finish', get_string('finish_date','block_pbltool'));
        $mform->setType('config_date_finish', PARAM_RAW);        
	
	$forum = array();
	$forums = $DB->get_records('forum', array('course' => $id),null,'id,name');
	foreach($forums AS $indice=>$valor)
		$forum[$indice] = $valor->name;	
        $mform->addElement('select', 'config_forum', get_string('choose_forum','block_pbltool'), $forum);

	$chat = array();
	$chats = $DB->get_records('chat', array('course' => $id),null,'id,name');
        foreach($chats AS $indice=>$valor)
		$chat[$indice] = $valor->name;	
        $mform->addElement('select', 'config_chat', get_string('choose_chat','block_pbltool'), $chat);
    }
}
