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


// Ensure we have a valid courseid and can load the associated course object.

if (!($course = $DB->get_record('course', array('id' => $courseid)))) {
    print_error(get_string('invalidcourse', 'block_pbltool'));
}

// Ensure the user has access to this course.
require_login($course);

// Ensure the block exits.
if (!($block = $DB->get_record("block_instances", array("id" => $blockid)))) {
    error("Block ID was incorrect or no longer exists", $CFG->wwwroot."/course/view.php?id=$courseid");
}

// Ensure the block belongs to the course.
/*if ($block->pageid != $courseid) {
    error("Block ID does not belong to course",$CFG->wwwroot."/course/view.php?id=$courseid");
}
*/

$context = context_course::instance($courseid);

// Ensure the user has appropriate permissions to access this area.
require_capability('block/pbltool:viewpages', $context);

// Ensure the user belongs to the group if not admin and if not entering block.
if ($groupid && !has_capability('block/pbltool:managepages', $context)) {
    $teacher = false;
    $aux = groups_get_all_groups($courseid, $USER->id);
    if (!$aux) { // If no group is defined.
        error('No group definition',$CFG->wwwroot."/course/view.php?id=$courseid");
    }
    foreach ($aux as $group);
   	if ($group->id != $groupid) {
        error('You are trying to access a group that you are not authorized',
            $CFG->wwwroot."/course/view.php?id=$courseid");
    }
}
else {
	$teacher = true;
}