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



require_once('../../config.php');

require_once('lib.php');

 
// declare any globals we need to use

global $CFG, $USER, $DB;
 
// check for all required variables

$blockid = required_param('blockid', PARAM_INT);

$courseid = required_param('courseid',PARAM_INT);

$groupid = required_param('groupid',PARAM_INT);


include('authorization.php');

print_map($courseid,$blockid,$groupid,$teacher);

?>
