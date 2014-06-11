<?php
namespace block_pbltool\event;
defined('MOODLE_INTERNAL') || die();
class view_gantt extends \core\event\base {
    protected function init() {
        $this->data['crud'] = 'r'; // c(reate), r(ead), u(pdate), d(elete)
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
	$this->data['objecttable'] = 'block_pbltool_projects';
    }
 
    public static function get_name() {
        return get_string('eventview_gantt', 'block_pbltool');
    }
 
    public function get_description() {
        return "User {$this->userid} - Project {$this->objectid} - Group {$this->other}";
    }
}
?>
