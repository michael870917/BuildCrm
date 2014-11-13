<?php
unset($CFG);
global $CFG;
$CFG = new stdClass();
$CFG->dataroot = dirname(__file__);
$CFG->dirMainClassBO = "/lib/php/com/crmPath/bo/";
$CFG->dirMainClassDAO = "/lib/php/com/crmPath/dao/";
$CFG->dirMainClassSecurity = "/lib/php/com/crmPath/misc/Security.php";
$CFG->dirMainClassUTIL = "/lib/php/application/Utilities.php";

?>