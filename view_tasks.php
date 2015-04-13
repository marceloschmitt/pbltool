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




// include moodle API and any supplementary files/API
require_once('../../config.php');
require_once('lib.php');

// declare any globals we need to use
global $CFG, $USER;

// check for all required variables
$blockid = required_param('blockid', PARAM_INT);
$courseid = required_param('courseid',PARAM_INT);
$groupid = optional_param('groupid',0,PARAM_INT);

$params['blockid'] = $blockid;
$params['courseid'] = $courseid;
$params['groupid'] = $groupid;

include('authorization.php');

$PAGE->set_url('/blocks/pbltool/view_tasks.php', $params);
$PAGE->set_title(get_string('pluginname','block_pbltool'));
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('print');

echo $OUTPUT->header();
block_pbltool_print_page($courseid,$blockid,$groupid,'tasks',$teacher);
echo $OUTPUT->footer();
