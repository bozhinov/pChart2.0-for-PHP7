<html>
<head>
<title>pChart 2.x - Examples</title>
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

	LastOpened = null;
	LastScript = null;

	function toggleMenu(Element)
	{
		if (LastOpened != null){
			document.getElementById(LastOpened.slice(0, -5)).style.display = "none";
			document.getElementById(LastOpened).style.fontWeight = "normal";
		}
		document.getElementById(Element.slice(0, -5)).style.display = "inline";
		document.getElementById(Element).style.fontWeight = "bold";

		LastOpened = Element;
	}

	function render(PictureName)
	{
		if (LastScript != null) { 
			document.getElementById(LastScript).style.fontWeight = "normal"; 
		}
		document.getElementById(PictureName).style.fontWeight = "bold";
		LastScript = PictureName;

		ajaxRender(PictureName);
	}

	function ajaxRender(URL) // render the picture
	{
		$.ajax({
			url: URL,
			method: "POST",
			beforeSend: function(){
				$("#render").html("<center><img src='resources/wait.gif' /><br>Rendering</center>");
			},
			complete: function(){
				$("#render").html("<center><img src='example." + URL + ".php' /></center>");
				view(URL); 
			}
		});
	}

	function view(URL) // fetch the source code
	{
		$.ajax({
			type: "POST", 
			url: "fetch.src.php",
			data: {'View': URL},
			success: function (result) {
				$("#source").html(result);
			}
		});
	}
	
	$(document).ready(function() {
		$(".folder").on("click", function() {
			toggleMenu($(this).attr('id'));
		})
		$(".example").on("click", function() {
			render($(this).attr('id'));
		})
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
$Tree = [];
$DirectoryHandle = opendir(".");

while (($FileName = readdir($DirectoryHandle)) !== false)
{
	if (substr($FileName,0,8) == "example."){
		$FileHandle  = fopen($FileName, "r");
		$buffer      = fgets($FileHandle);
		$buffer      = fgets($FileHandle);
		fclose($FileHandle);

		if (substr($buffer, 0, 7) == "/* CAT:"){ # /* CAT:Misc */
			$Categorie = substr($buffer, 7, -5);
			$Tree[$Categorie][] = substr($FileName, 8, -4);
		}
	}
}

closedir($DirectoryHandle);

$_TREE_HTML = "";
ksort($Tree);
$keys = array_keys($Tree);
$LastKey = end($keys);

foreach($Tree as $Key => $Elements){

	if ($LastKey == $Key) {
		$Icon = "dash-explorer-last.png";
		$SubIcon = "dash-explorer-blank.png";
	} else {
		$Icon = "dash-explorer.png";
		$SubIcon = "dash-explorer-noleaf.png";
	}

	$_TREE_HTML .= "<table>\r\n";
	$_TREE_HTML .= "<tr>\r\n";
	$_TREE_HTML .= "	<td><img src='resources/".$Icon."' /></td>\r\n";
	$_TREE_HTML .= "	<td><img src='resources/folder.png'/></td>\r\n";
	$_TREE_HTML .= "	<td><div class='folder' id='".$Key."_main'>&nbsp;".$Key."</div></td>\r\n";
	$_TREE_HTML .= "</tr>\r\n";
	$_TREE_HTML .= "</table>\r\n";

	$_TREE_HTML .= "<table id='".$Key."' style='display: none;'><tr>\r\n";
	
	foreach($Elements as $SubKey => $Element){

		$Icon = ($SubKey == count($Elements)-1) ? "dash-explorer-last.png" : "dash-explorer.png";

		$_TREE_HTML .= "<tr>\r\n";
		$_TREE_HTML .= "	<td><img src='resources/".$SubIcon."' /></td>\r\n";
		$_TREE_HTML .= "	<td><img src='resources/".$Icon."' /></td>\r\n";
		$_TREE_HTML .= "	<td><img src='resources/application_view_tile.png' /></td>\r\n";
		$_TREE_HTML .= "	<td><div class='example' id='".$Element."'>&nbsp;<a class='smallLink' href='#'>".$Element."</a></div></td>\r\n";
		$_TREE_HTML .= "</tr>\r\n";
	}

	$_TREE_HTML .= "</table>\r\n";

}

echo $_TREE_HTML;

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