<?php

if (php_sapi_name() != "cli") {
	exit();
}

$start = microtime(true);

$_IGNORED = [
	"examples/example.pyramid.php"
];

foreach (glob("examples/example.*") as $fileName){
	if (!in_array($fileName, $_IGNORED)){
		echo "Processing: ".$fileName."\r\n";
		ob_start();
		$code = file_get_contents($fileName, TRUE);
		eval('?>' . $code . '<?php ');
		$string = ob_get_contents();
		ob_end_clean();
	} else {
		echo "Ignored: ".$fileName."\r\n";
	}
}

$time_elapsed_secs = microtime(true) - $start;

echo 'Total Execution Time: '.$time_elapsed_secs;

