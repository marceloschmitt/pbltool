<?php
/*
 * Created on 03/04/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot.'/blocks/pbltool/lib.php');


class project_description_form extends moodleform {

    function definition() {
        global $CFG;
        global $COURSE;

        $mform =& $this->_form;
        $mform->addElement('header', 'displayinfo', get_string('general'));

        // add display text field
        $mform->addElement('htmleditor', 'description', get_string('description', 'block_pbltool'));
        $mform->setType('displaytexttext', PARAM_RAW);
        $mform->addRule('description', null, 'required', null, 'client');

        $mform->addElement('hidden','blockid');
        $mform->setType('blockid', PARAM_INT);
        $mform->addElement('hidden','courseid');
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('hidden','groupid');
        $mform->setType('groupid', PARAM_INT);
        $mform->addElement('hidden','id');
        $mform->setType('id',PARAM_INT);
        $this->add_action_buttons();

    }
}
?>
