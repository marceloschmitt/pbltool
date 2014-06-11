<?php
namespace block_pbltool\event;
defined('MOODLE_INTERNAL') || die();
class add_task extends \core\event\base {
    protected function init() {
        $this->data['crud'] = 'c'; // c(reate), r(ead), u(pdate), d(elete)
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
	$this->data['objecttable'] = 'block_pbltool_projects';
    }
 
    public static function get_name() {
        return get_string('eventadd_task', 'block_pbltool');
    }
 
    public function get_description() {
        return "User {$this->userid} - Project {$this->objectid} - Group {$this->other}";
    }
 
}
?>
