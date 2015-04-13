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




require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot.'/blocks/pbltool/lib.php');


class pbltool_form extends moodleform {

    function definition() {
        global $CFG;
        global $COURSE;

        $mform =& $this->_form;
        $mform->addElement('header', 'displayinfo', get_string('textfields', 'block_pbltool'));


        // add page title element
        $mform->addElement('text','pagetitle',get_string('pagetitle','block_pbltool'));
        $mform->addRule('pagetitle', null, 'required', null, 'client');

        // add display text field
        $mform->addElement('htmleditor', 'displaytext', get_string('displayedhtml', 'block_pbltool'));
        $mform->setType('displaytexttext', PARAM_RAW);
        $mform->addRule('displaytext', null, 'required', null, 'client');

        // add filename selection
        $mform->addElement('choosecoursefile', 'filename', get_string('displayfile', 'block_pbltool'), array('courseid'=>$COURSE->id));

        //add picturefields header
        $mform->addElement('header', 'pictureinfo', get_string('picturefields', 'block_pbltool'));

        // add display picture yes/no option
        $mform->addElement('selectyesno', 'displaypicture', get_string('displaypicture','block_pbltool'));
        $mform->setDefault('displaypicture', 1);

        // add image selector radio buttons
        $images = block_pbltool_images();
        $radioarray=array();
        for($i = 0; $i < count($images); $i++){
            $radioarray[] =&$mform->createElement('radio', 'picture', '',$images[$i],$i);
        }

        $mform->addGroup($radioarray, 'radioar', get_string('pictureselect', 'block_pbltool'), array(' '), false);

        // add description field
        $attributes=array('size'=>'50', 'maxlength'=>'100');
        $mform->addElement('text', 'description', get_string('picturedesc', 'block_pbltool'), $attributes); $mform->setType('description', PARAM_TEXT);
        $mform->setType('description', PARAM_TEXT);

        // add optional grouping
        $mform->addElement('header', 'optional', get_string('optional','form'), null, false);

        // add date_time selector in optional area
        $mform->addElement('date_time_selector', 'displaydate', get_string('displaydate', 'block_pbltool'), array('optional'=>true));
        $mform->setAdvanced('optional');

        $mform->addElement('hidden','blockid');
        $mform->addElement('hidden','courseid');
        $mform->addElement('hidden','f');
        $mform->addElement('hidden','c');

        $this->add_action_buttons();

    }
}
