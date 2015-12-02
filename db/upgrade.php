<?php
// This file keeps track of upgrades to
// the search block
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installation to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

function xmldb_block_pbltool_upgrade($oldversion=0) {
global $CFG, $THEME,  $DB;

$dbman = $DB->get_manager(); /// loads ddl manager and xmldb classes

$result = true;


return true;
/// And upgrade begins here. For each one, you'll need one
/// block of code similar to the next one. Please, delete
/// this comment lines once this file start handling proper
/// upgrade code.
    if ($result && $oldversion < 2014061101) {

   	upgrade_block_savepoint(true, 2015120201, 'pbltool');
        
    }
    

}
?>
