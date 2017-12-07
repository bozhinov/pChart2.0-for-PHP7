<?php 

if (php_sapi_name() != "cli") {
	chdir("../");
}
	
spl_autoload_register(function ($class_name) {
	include $class_name . ".php";
});


?>