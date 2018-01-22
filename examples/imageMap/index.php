<?php 
if (isset($_GET["View"]))
{
	$result = preg_match("/^[a-z,A-Z,0-9,\.]{3,30}$/", $_GET["View"], $matches);
	if ($result){
		if (file_exists("scripts/".$matches[0].".php")){
			highlight_file("scripts/".$matches[0].".php"); 
		}
	}
	exit();
}
?>
<html>
<head>
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<link rel='stylesheet' type='text/css' href='imagemap.css'/>
<script src='../resources/jquery-3.3.1.min.js' type="text/javascript"></script>
<script src='jquery.imagemap.js' type="text/javascript"></script>
<script>
$(document).ready(function() {
	
	function showExample(FileName)
	{
		$("#render").html("<img src='scripts/"+FileName+".php' id='testPicture' class='pChartPicture'/>");
		$.get("index.php?View="+FileName).done(function(data) {
				$("#source").html(data); 
			});

		$('#testPicture').addImageMap('pictureMap','scripts/'+FileName+'.php?ImageMap=get');
	}
	
	$('[id^="Hover-"]').on("click", function() {
		showExample(($(this).attr('id')).substring(6));
	});
		
});
</script>
<title>pChart 2.3.x - Image map</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<style>
	body       { background-color: #F0F0F0; font-family: tahoma; font-size: 14px;}
	td  	   { font-family: tahoma; font-size: 11px; }
	div.txt    { font-family: tahoma; font-size: 11px; width: 660px; padding: 15px; }
	div.folder { cursor: hand; cursor: pointer; }
	a.smallLink:link    { text-decoration: none; color: #6A6A6A; }
	a.smallLink:visited { text-decoration: none; color: #6A6A6A; }
	a.smallLink:hover   { text-decoration: underline; color: #6A6A6A; }
	a.pChart { text-decoration: none; color: #6A6A6A; }
	a img, img { border: none; }
</style>
</head>
<body>
<table style='border: 2px solid #FFFFFF;'>
	<tr>
		<td>
			<div style='font-size: 11px; padding: 2px; color: #FFFFFF; background-color: #666666; border-bottom: 3px solid #484848;'>&nbsp;Navigation</div>
			<table style='padding: 1px; background-color: #E0E0E0; border: 1px solid #D0D0D0; border-top: 1px solid #FFFFFF;'>
				<tr>
					<td width=16><img src='../resources/application_view_tile.png' /></td>
					<td width=100>&nbsp;<a class='smallLink' href='../'>Examples</a></td>
					<td width=16><img src='../resources/application_view_list.png' /></td>
					<td width=100>&nbsp;<a class='smallLink' href='../sandbox/'>Sandbox</a></td>
					<td width=16><img src='../resources/application_view_list.png' /></td>
					<td width=100>&nbsp;<b>Image Map</b></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br/>

<table>
	<tr>
	<td valign='top'>
	<table style='border: 2px solid #FFFFFF;'>
		<tr>
			<td>
<?php
	/* Determine the current package version */
	$FileHandle  = fopen("../../readme.txt", "r");
	for ($i=0; $i<=5; $i++) {
		$buffer = fgets($FileHandle); 
	}
	fclose($FileHandle);
	# Change if readme.txt no longer binary
	$Version = trim(substr($buffer, 39, 16));

echo <<<EOHTML
		<div style='font-size: 11px; padding: 2px; color: #FFFFFF; background-color: #666666; border-bottom: 3px solid #484848; width: 222px;'>&nbsp;Release $Version</div>
				<div style='border: 3px solid #D0D0D0; border-top: 1px solid #FFFFFF; background-color: #FAFAFA; width: 220px; overflow: auto'>
					<div style='padding: 1px; padding-bottom: 3px; color: #000000; background-color:#D0D0D0;'>
						<table><tr><td><img src='../resources/application_view_list.png' /></td><td>&nbsp;Examples folder contents</td></tr></table>
					</div>
EOHTML;

	/* Build a list of the examples & categories */
	$DirectoryHandle = opendir("scripts");
	{
		$Tree = [];
		while (($FileName = readdir($DirectoryHandle)) !== false)
		{
			if (!in_array($FileName,[".","..","2DPie.sqlite.example","2DPie.session.example"])){
				$Tree[] = substr($FileName, 0, -4);
			}
		}
	}

	foreach($Tree as $Key => $Element)
	{
		$Icon = ($Key == count($Tree)-1) ? "../resources/dash-explorer-last.png" : "../resources/dash-explorer.png";

		echo "<table noborder cellpadding=0 cellspacing=0>\r\n";
		echo " <tr valign='middle'>\r\n";
		echo "  <td><img src='".$Icon."' /></td>\r\n";
		echo "  <td><img src='../resources/application_view_tile.png' /></td>\r\n";
		echo "  <td><div class='folder' id=".chr(34)."Hover-".$Element.chr(34).">&nbsp;".$Element."</div></td>\r\n";
		echo " </tr>\r\n";
		echo "</table>\r\n";
	}
?>
				</div>
			</td>
		</tr>
	</table>
	</td>
	<td width=20></td>
	<td valign='top' style='padding-top: 5px; font-size: 12px;'>

		<table><tr><td><img src='../resources/chart_bar.png' /></td><td>&nbsp;Rendering area</td></tr></table>

		<div style='display:table-cell; padding: 10px; border: 2px solid #FFFFFF; vertical-align: middle; overflow: auto; background-image: url("../resources/dash.png");'>
			<div style='font-size: 10px;' id="render">
				<table><tr><td><img src='../resources/accept.png' /></td><td>Click on an example to render it!</td></tr></table>
			</div>
		</div>

		<br/><br/>

		<table><tr><td><img src='../resources/application_view_list.png' /></td><td>&nbsp;HTML Source area</td></tr></table>

		<div style='display:table-cell; padding: 10px;  border: 2px solid #FFFFFF; vertical-align: middle; overflow: auto; background-image: url("../resources/dash.png");'>
			<div id="htmlsource" style='width: 800px; font-size: 13px; font-family: Lucida Console'>
				&lt;head&gt;<br/>
				&nbsp;&nbsp; &lt;link href='imagemap.css' type='text/css' rel='stylesheet'&gt;&lt;/link&gt;<br/>
				&nbsp;&nbsp; &lt;script src="jquery-3.3.1.min.js" type="text/javascript"&gt;&lt;/script&gt;<br/>
				&nbsp;&nbsp; &lt;script src="jquery.imagemap.js" &nbsp;type="text/javascript"&gt;&lt;/script&gt;<br/>
				&nbsp;&nbsp; &lt;script&gt;<br/>
				&nbsp;&nbsp; $(document).ready(function() {<br/>
				&nbsp;&nbsp; &nbsp;&nbsp;   $("#testPicture").addImageMap("pictureMap","scripts/draw.php?ImageMap=get",{SmoothMove:true});<br/>
				&nbsp;&nbsp; });<br/>
				&nbsp;&nbsp; &lt;/script&gt;<br/>
				&lt;/head&gt;<br/>
				&lt;body&gt;<br/>
				&nbsp;&nbsp;  &lt;img src="draw.php" id="testPicture" /&gt;<br/>
				&lt;/body&gt;<br/>
			</div>
		</div>

	   <br/><br/>

		<table>
			<tr>
				<td><img src='../resources/application_view_list.png' /></td>
				<td>&nbsp;PHP Source area</td>
			</tr>
		</table>

		<div style='display:table-cell; padding: 10px;  border: 2px solid #FFFFFF; vertical-align: middle; overflow: auto; background-image: url("../resources/dash.png");'>
			<div style='font-size: 10px;' id="source" style='width: 700px;'>
				<table><tr><td><img src='../resources/accept.png' /></td><td>Click on an example to get its source!</td></tr></table>
			</div>
		</div>

	</td>
	</tr>
</table>
</body>
</html>