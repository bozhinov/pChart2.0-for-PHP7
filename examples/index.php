<html>
<head>
<title>pChart - Examples</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<style>
	body { background-color: #F0F0F0; font-family: tahoma; font-size: 14px; }
	div.folder { cursor: hand; }
	div.example > a:link, a:visited { text-decoration: none; color: #6A6A6A; font-size: 11px; }
	div.example > a:hover { text-decoration: underline; }
	#render, #source {
		display:table-cell;
		padding: 10px;
		border: 2px solid #FFFFFF;
		background-image: url("resources/dash.png");
	}
</style>
<script src='resources/jquery-3.4.1.min.js' type="text/javascript"></script>
<script>
	var LastOpened = null;
	var LastScript = null;

	$(document).ready(function() {

		$(".folder").on("click", function() {

			if (LastOpened != null){
				document.getElementById(LastOpened.slice(0, -5)).style.display = "none";
				document.getElementById(LastOpened).style.fontWeight = "normal";
			}

			LastOpened = $(this).attr('id');

			document.getElementById(LastOpened.slice(0, -5)).style.display = "inline";
			document.getElementById(LastOpened).style.fontWeight = "bold";
		});

		$(".example").on("click", function() {

			if (LastScript != null) {
				document.getElementById(LastScript).style.fontWeight = "normal"; 
			}

			LastScript = $(this).attr('id');

			document.getElementById(LastScript).style.fontWeight = "bold";

			$("#render").html("<center><img src='example." + LastScript + ".php' /></center>");

			$.ajax({
				type: "POST", 
				url: "fetch.src.php",
				data: {'View': LastScript},
				success: function (result) {
					$("#source").html(result);
				}
			});
		});

	});
</script>
</head>

<body>

<div style="float: left;">
	<div style='background-color: white; border: 2px solid #FFFFFF;'>
		<div style='padding: 1px; padding-bottom: 3px; color: #000000; background-color:#D0D0D0; width: 220px;'>
			<img src='resources/application_view_list.png'/>
			 Examples folder contents
		</div>
<?php

session_start();

if (!isset($_SESSION['html'])){

	 /* Build a list of the examples & categories */
	$tree = [];
	foreach (glob("example.*") as $fileName){

		$fileHandle = fopen($fileName, "r");
		if ($fileHandle === false) {
			continue;
		}
		$buffer = fgets($fileHandle);
		$buffer = fgets($fileHandle);
		fclose($fileHandle);

		if (substr($buffer, 0, 7) == "/* CAT:"){ # /* CAT:Misc */
			$cat = substr($buffer, 7, -5);
			$tree[$cat][] = substr($fileName, 8, -4);
		}
	}

	ksort($tree);
	$keys = array_keys($tree);
	$lastKey = end($keys);

	$treeHTML = "";

	foreach($tree as $key => $elements){

		if ($lastKey == $key) {
			$icon = "dash-explorer-last.png";
			$subIcon = "dash-explorer-blank.png";
		} else {
			$icon = "dash-explorer.png";
			$subIcon = "dash-explorer-noleaf.png";
		}

		$treeHTML .= "<div class='folder' id='".$key."_main'>\r\n";
		$treeHTML .= "	<img src='resources/".$icon."'/>\r\n";
		$treeHTML .= "	<img src='resources/folder.png'/>\r\n";
		$treeHTML .= "	&nbsp;".$key."\r\n";
		$treeHTML .= "</div>\r\n";

		$treeHTML .= "<div id='".$key."' style='display: none;'>\r\n";
		
		foreach($elements as $subKey => $element){

			$icon = ($subKey == count($elements)-1) ? "dash-explorer-last.png" : "dash-explorer.png";

			$treeHTML .= "<div class='example' id='".$element."'>\r\n";
			$treeHTML .= "	<img src='resources/".$subIcon."' />\r\n";
			$treeHTML .= "	<img src='resources/".$icon."' />\r\n";
			$treeHTML .= "	<img src='resources/application_view_tile.png' />\r\n";
			$treeHTML .= "	&nbsp;<a href='#'>".$element."</a>\r\n";
			$treeHTML .= "</div>\r\n";
		}

		$treeHTML .= "</div>\r\n";
	}

	$_SESSION['html'] = $treeHTML;
}

echo $_SESSION['html'];
?>

	</div>

	<div style='width:200px; padding: 10 0 0 10'>
		<img src='resources/application_view_list.png'/><a href='sandbox/' target="_blank"> Try the Sandbox</a>
	</div>

</div>
<div style="margin-left: 280px;"> 

	<img src='resources/chart_bar.png'/> Rendering area
	<div id="render">
		<img src='resources/accept.png'/> Click on an example to render it!
	</div>

	<br/>
	<br/>

	<img src='resources/application_view_list.png'/> Source area
	<div id="source">
		<img src='resources/accept.png'/> Click on an example to get its source!
	</div>

</div>

</body>
</html>