<?php
if (isset($_POST["View"]))
{
	$result = preg_match("/^[a-z,A-Z,0-9,\.]{6,40}$/", $_POST["View"], $matches);
	if ($result){
		if (file_exists("example.".$matches[0].".php")){
			highlight_file("example.".$matches[0].".php"); 
		}
	}
	exit();
}
?>
<html>
<head>
<title>pChart 2.x - examples rendering</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<style>
	body       { background-color: #F0F0F0; font-family: tahoma; font-size: 14px; height: 100%; overflow: auto;}
	table      { margin: 0px; padding: 0px; border: 0px; }
	tr         { margin: 0px; padding: 0px; border: 0px; }
	td         { font-family: tahoma; font-size: 11px; margin: 0px; padding: 0px; border: 0px; }
	div.folder { cursor: hand; cursor: pointer; }
	a.smallLink:link     { text-decoration: none; color: #6A6A6A; }
	a.smallLink:visited  { text-decoration: none; color: #6A6A6A; }
	a.smallLink:hover    { text-decoration: underline; color: #6A6A6A; }
</style>
<script src='resources/jquery-3.3.1.min.js' type="text/javascript"></script>
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
			url: "index.php",
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
<table style='border: 2px solid #FFFFFF;'>
	<tr><td>
		<div style='font-size: 11px; padding: 2px; color: #FFFFFF; background-color: #666666; border-bottom: 3px solid #484848;'>&nbsp;Navigation</div>
		<table style='padding: 1px; background-color: #E0E0E0; border: 1px solid #D0D0D0; border-top: 1px solid #FFFFFF;'>
			<tr>
			 <td width=16><img src='resources/application_view_tile.png'/></td>
			 <td width=100>&nbsp;<b>Examples</b></td>
			 <td width=16><img src='resources/application_view_list.png'/></td>
			 <td width=100>&nbsp;<a class='smallLink' href='sandbox/'>Sandbox</a></td>
			 <td width=16><img src='resources/application_view_list.png'/></td>
			 <td width=100>&nbsp;<a class='smallLink' href='imageMap/'>Image Map</a></td>
			</tr>
		</table>
	</td></tr>
</table>

<br/>
<table>
	<tr><td valign='top'>
<?php

/* Determine the current package version */
$FileHandle  = fopen("../readme.txt", "r");
for ($i=0; $i<=5; $i++) {
	$buffer = fgets($FileHandle); 
}
fclose($FileHandle);
# Change if readme.txt no longer binary
$Version = trim(substr($buffer, 39, 16));

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
			$Categorie = substr($buffer, 7);
			$Categorie = substr($Categorie, 0, -5);
		
			$Tree[$Categorie][] = str_replace(["example.",".php"], ["",""], $FileName);
		}
	}
}

closedir($DirectoryHandle);

echo <<<EOHTML
<table style='border: 2px solid #FFFFFF;'>
	<tr><td>
	<div style='font-size: 11px; padding: 2px; color: #FFFFFF; background-color: #666666; border-bottom: 3px solid #484848; width: 222px;'>&nbsp;Release $Version</div>
	<div style='border: 3px solid #D0D0D0; border-top: 1px solid #FFFFFF; background-color: #FAFAFA; width: 220px; overflow: auto'>
	<div style='padding: 1px; padding-bottom: 3px; color: #000000; background-color:#D0D0D0;'>
	 <table><tr>
	  <td><img src='resources/application_view_list.png'/></td>
	  <td>&nbsp;Examples folder contents</td>
	 </tr></table>
	</div>     
EOHTML;

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

	$_TREE_HTML .= "<table noborder cellpadding=0 cellspacing=0>\r\n";
	$_TREE_HTML .= "<tr valign='middle'>\r\n";
	$_TREE_HTML .= "	<td><img src='resources/".$Icon."' /></td>\r\n";
	$_TREE_HTML .= "	<td><img src='resources/folder.png'/></td>\r\n";
	$_TREE_HTML .= "	<td><div class='folder' id='".$Key."_main'>&nbsp;".$Key."</div></td>\r\n";
	$_TREE_HTML .= "</tr>\r\n";
	$_TREE_HTML .= "</table>\r\n";

	$_TREE_HTML .= "<table id='".$Key."' style='display: none;' noborder cellpadding=0 cellspacing=0><tr>\r\n";
	
	foreach($Elements as $SubKey => $Element){

		$Icon = ($SubKey == count($Elements)-1) ? "dash-explorer-last.png" : "dash-explorer.png";

		$_TREE_HTML .= "<tr valign='middle'>\r\n";
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
	</td></tr>
</table>
</td>
<td width=20></td>
<td valign='top' style='padding-top: 5px; font-size: 12px;'>

<table><tr><td><img src='resources/chart_bar.png'/></td><td>&nbsp;Rendering area</td></tr></table>

<div style='display:table-cell; padding: 10px; border: 2px solid #FFFFFF; vertical-align: middle; overflow: auto; background-image: url("resources/dash.png");'>
	<div style='font-size: 10px;' id="render">
		<table><tr><td><img src='resources/accept.png'/></td><td>Click on an example to render it!</td></tr></table>
	</div>
</div>

<br/><br/>

<table><tr><td><img src='resources/application_view_list.png'/></td><td>&nbsp;Source area</td></tr></table>

<div style='display:table-cell; padding: 10px; border: 2px solid #FFFFFF; vertical-align: middle; overflow: auto; background-image: url("resources/dash.png");'>
	<div style='font-size: 10px;' id="source" style='width: 700px;'>
		<table><tr><td><img src='resources/accept.png'/></td><td>Click on an example to get its source!</td></tr></table>
	</div>
</div>

</td>
</tr>
</table>
</body>
</html>