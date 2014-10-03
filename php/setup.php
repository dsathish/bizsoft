<?php
 

$root_path = realpath(dirname(__FILE__) . '/../');
$include_paths = get_include_path();
set_include_path($include_paths.PATH_SEPARATOR."$root_path/lib");

require('conf/setup.inc.php');

?>
