<html>
<head>
<title>pChart - Examples</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<style>
	body { background-color: #F0F0F0; font-family: tahoma; font-size: 14px;}
	td { font-family: tahoma; font-size: 11px; margin: 0px; padding: 0px; border: 0px; }
	div.folder { cursor: hand; cursor: pointer; }
	a.smallLink:link, a.smallLink:visited { text-decoration: none; color: #6A6A6A; }
	a.smallLink:hover { text-decoration: underline; color: #6A6A6A; }
	div.type01 { 
		display:table-cell;
		padding: 10px;
		border: 2px solid #FFFFFF;
		background-image: url("resources/dash.png");
		font-size: 10px;
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

 /* Build a list of the examples & categories */
$tree = [];
foreach (glob("example.*") as $fileName){

	$fileHandle  = fopen($fileName, "r");
	$buffer      = fgets($fileHandle);
	$buffer      = fgets($fileHandle);
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

	$treeHTML .= "<table>\r\n";
	$treeHTML .= "<tr>\r\n";
	$treeHTML .= "	<td><img src='resources/".$icon."' /></td>\r\n";
	$treeHTML .= "	<td><img src='resources/folder.png'/></td>\r\n";
	$treeHTML .= "	<td><div class='folder' id='".$key."_main'>&nbsp;".$key."</div></td>\r\n";
	$treeHTML .= "</tr>\r\n";
	$treeHTML .= "</table>\r\n";

	$treeHTML .= "<table id='".$key."' style='display: none;'><tr>\r\n";
	
	foreach($elements as $subKey => $element){

		$icon = ($subKey == count($elements)-1) ? "dash-explorer-last.png" : "dash-explorer.png";

		$treeHTML .= "<tr>\r\n";
		$treeHTML .= "	<td><img src='resources/".$subIcon."' /></td>\r\n";
		$treeHTML .= "	<td><img src='resources/".$icon."' /></td>\r\n";
		$treeHTML .= "	<td><img src='resources/application_view_tile.png' /></td>\r\n";
		$treeHTML .= "	<td><div class='example' id='".$element."'>&nbsp;<a class='smallLink' href='#'>".$element."</a></div></td>\r\n";
		$treeHTML .= "</tr>\r\n";
	}

	$treeHTML .= "</table>\r\n";

}

echo $treeHTML;

?>
	</div>

	<div style='width:200px; padding: 10 0 0 10'>
		<img src='resources/application_view_list.png'/><a class='smallLink' href='sandbox/' target="_blank"> Try the Sandbox</a>
	</div>

</div>
<div style="margin-left: 280px;"> 

	<div>
		<img src='resources/chart_bar.png'/> Rendering area
	</div>

	<div class="type01" id="render">
		<img src='resources/accept.png'/> Click on an example to render it!
	</div>

	<br/>
	<br/>

	<div>
		<img src='resources/application_view_list.png'/> Source area
	</div>

	<div class="type01">
		<div id="source">
			<img src='resources/accept.png'/> Click on an example to get its source!
		</div>
	</div>

</div>

</body>
</html>