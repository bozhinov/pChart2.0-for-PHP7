<?php

if (php_sapi_name() != "cli") {
	exit();
}

echo "\r\n Using PHP ".phpversion()."\r\n\r\n";

$bench_start = microtime(true);

$bench_ignored = [
	"examples/example.pyramid.php"
];

function bench_progress_bar($done, $total, $info="", $width=50) {
    $perc = round(($done * 100) / $total);
    $bar = round(($width * $perc) / 100);
    return sprintf("%s%%[%s>%s]%s\r", $perc, str_repeat("=", $bar), str_repeat(" ", $width-$bar), $info);
}

$bench_allFiles = glob("examples/example.*");
$bench_countFiles = count($bench_allFiles);
$bench_i = 0;

foreach ($bench_allFiles as $bench_fileName){
	if (!in_array($bench_fileName, $bench_ignored)){
		//echo "Processing: ".$bench_fileName."\r\n";
		eval('?>' . file_get_contents($bench_fileName, TRUE) . '<?php ');
	} #else {
		#echo "Ignored: ".$bench_fileName."\r\n";
	#}
	$bench_i++;
	echo bench_progress_bar($bench_i, $bench_countFiles);
}

$time_elapsed_secs = microtime(true) - $bench_start;

echo "\r\n\r\n Total Execution Time: ".$time_elapsed_secs;

