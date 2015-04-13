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
