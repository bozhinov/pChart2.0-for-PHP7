<?php 

if (isset($_GET["Action"]) && $_GET["Action"] == "ViewPHP") {
	goCheck($_GET["Script"]); 
	exit();
} 

function goCheck($Script)
{
	$Script = stripslashes($Script);
	$Script = preg_replace(["/\//","/\:/","/scripts/"], ["","","scripts/"],$Script);

	if (file_exists($Script)){
		highlight_file($Script);
	} else {
		echo "Script source code cannot be fetched."; 
	}
	exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<script src='jquery-3.2.1.min.js' type="text/javascript"></script>
<script src='imagemap.js' type="text/javascript"></script>
<script>
$(document).ready(function() {
	
	function showExample(FileName)
	{
		$("#render").html("<img src='scripts/"+FileName+".php?Seed="+Math.random(100)+"' id='testPicture' alt='' class='pChartPicture'/>");
		$.get("index.php?Action=ViewPHP&Script=scripts/"+FileName+".php").done(function(data) {
				$("#source").html(data.replace("/\<BR\>/")); 
			});

		var Mapper = new ImageMapper();
		Mapper.addImage('testPicture','pictureMap','scripts/'+FileName+'.php?ImageMap=get');
	}
	
	$('[id^="Hover-"]').click(function() {
		showExample((this.id).substring(6));
	});
	
});
</script>
<title>pChart 2.x - Image map</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<style>
	body       { background-color: #F0F0F0; font-family: tahoma; font-size: 14px;}
	td  	     { font-family: tahoma; font-size: 11px; }
	div.txt    { font-family: tahoma; font-size: 11px; width: 660px; padding: 15px; }
	div.folder { cursor: hand; cursor: pointer; }
	a.smallLink:link    { text-decoration: none; color: #6A6A6A; }
	a.smallLink:visited { text-decoration: none; color: #6A6A6A; }
	a.smallLink:hover   { text-decoration: underline; color: #6A6A6A; }
	a.smallLinkGrey:link     { text-decoration: none; color: #6A6A6A; }
	a.smallLinkGrey:visited  { text-decoration: none; color: #6A6A6A; }
	a.smallLinkGrey:hover    { text-decoration: underline; color: #6A6A6A; }
	a.smallLinkBlack:link    { text-decoration: none; color: #000000; }
	a.smallLinkBlack:visited { text-decoration: none; color: #000000; }
	a.smallLinkBlack:hover   { text-decoration: underline; color: #000000; }
	a.pChart { text-decoration: none; color: #6A6A6A; }
	a img, img { border: none; }
</style>
</head>
<body>
<?php
	/* Determine the current package version */
	$FileHandle  = fopen("../../readme.txt", "r");
	for ($i=0; $i<=5; $i++) {
		$buffer = fgets($FileHandle, 4096);
	}
	fclose($FileHandle);
	$Values  = preg_split("/:/",$buffer);
	$Values  = preg_split("/ /",$Values[1]);
	$Version = strip_tags($Values[1]);

	/* Build a list of the examples & categories */
	$DirectoryHandle = opendir("scripts");
	{
		$Tree = [];
		while (($FileName = readdir($DirectoryHandle)) !== false)
		{
			if ( !in_array($FileName,[".",".."])){
				$Tree[] = substr($FileName, 0, -4);
			}
		}
	}
?>

 <table style='border: 2px solid #FFFFFF;'>
 <tr><td>
  <div style='font-size: 11px; padding: 2px; color: #FFFFFF; background-color: #666666; border-bottom: 3px solid #484848;'>&nbsp;Navigation</div>
  <table style='padding: 1px; background-color: #E0E0E0; border: 1px solid #D0D0D0; border-top: 1px solid #FFFFFF;'><tr>
   <td width=16><img src='../resources/application_view_tile.png' width=16 height=16 alt=''/></td>
   <td width=100>&nbsp;<a class=smallLinkGrey href='../'>Examples</a></td>
   <td width=16><img src='../resources/application_view_list.png' width=16 height=16 alt=''/></td>
   <td width=100>&nbsp;<a class=smallLinkGrey href='../sandbox/'>Sandbox</a></td>
   <td width=16><img src='../resources/application_view_list.png' width=16 height=16 alt=''/></td>
   <td width=100>&nbsp;<a class=smallLinkGrey href='../delayedLoader/'>Delayed loader</a></td>
   <td width=16><img src='../resources/application_view_list.png' width=16 height=16 alt=''/></td>
   <td width=100>&nbsp;<b>Image Map</b></td>
  </tr></table>
 </td></tr>
 </table>

 <br/>

 <table>
 <tr><td valign='top'>
  <table style='border: 2px solid #FFFFFF;'>
  <tr><td>
   <div style='font-size: 11px; padding: 2px; color: #FFFFFF; background-color: #666666; border-bottom: 3px solid #484848; width: 222px;'>&nbsp;Release <?php echo $Version; ?></div>
   <div style='border: 3px solid #D0D0D0; border-top: 1px solid #FFFFFF; background-color: #FAFAFA; width: 220px; overflow: auto'>
   <div style='padding: 1px; padding-bottom: 3px; color: #000000; background-color:#D0D0D0;'>
    <table>
		<tr>
		 <td><img src='../resources/application_view_list.png' width=16 height=16 alt=''/></td>
		 <td>&nbsp;Examples folder contents</td>
		</tr>
	</table>
   </div>
<?php
	foreach($Tree as $Key => $Element)
	{
		$Icon = ($Key == count($Tree)-1) ? "../resources/dash-explorer-last.png" : "../resources/dash-explorer.png";

		echo "<table noborder cellpadding=0 cellspacing=0>\r\n";
		echo " <tr valign=middle>\r\n";
		echo "  <td><img src='".$Icon."' width=16 height=20 alt=''/></td>\r\n";
		echo "  <td><img src='../resources/application_view_tile.png' width=16 height=16 alt=''/></td>\r\n";
		echo "  <td><div class=folder id=".chr(34)."Hover-".$Element.chr(34).">&nbsp;".$Element."</div></td>\r\n";
		echo " </tr>\r\n";
		echo "</table>\r\n";
	}
?>
   </div>
  </td></tr>
  </table>
 </td><td width=20></td><td valign='top' style='padding-top: 5px; font-size: 12px;'>
  <table>
	  <tr>
	   <td><img src='../resources/chart_bar.png' width=16 height=16 alt=''/></td>
	   <td>&nbsp;Rendering area</td>
	   </tr>
   </table>

   <div style='display:table-cell; padding: 10px; border: 2px solid #FFFFFF; vertical-align: middle; overflow: auto; background-image: url("../resources/dash.png");'>
    <div style='font-size: 10px;' id=render>
     <table>
		<tr><td><img src='../resources/accept.png' width=16 height=16 alt=""/></td><td>Click on an example to render it!</td></tr>
	 </table>
    </div>
   </div>

   <br/>
   <br/>

   <table>
	   <tr>
		<td><img src='../resources/application_view_list.png' width=16 height=16 alt=''/></td>
		<td>&nbsp;HTML Source area</td>
	   </tr>
   </table>

   <div style='display:table-cell; padding: 10px;  border: 2px solid #FFFFFF; vertical-align: middle; overflow: auto; background-image: url("../resources/dash.png");'>
    <div id=htmlsource style='width: 700px; font-size: 13px; font-family: Lucida Console'>

     &lt;html&gt;<br/>
     &lt;head&gt;<br/>
     &nbsp;&nbsp; &lt;style&gt;<br/>
     &nbsp;&nbsp;&nbsp;&nbsp;   div.pChartPicture { border: 0px; }<br/>
     &nbsp;&nbsp; &lt;/style&gt;<br/>
     &lt;/head&gt;<br/>
     &lt;body&gt;<br/>
	 &nbsp;&nbsp; &lt;script src="jquery-3.2.1.min.js" type="text/javascript"&gt;&lt;/script&gt;<br/>
	 &nbsp;&nbsp; &lt;script src="imagemap.js" type="text/javascript"&gt;&lt;/script&gt;<br/>
     &nbsp;&nbsp;  &lt;img src="draw.php" id="testPicture" alt="" class="pChartPicture"/&gt;<br/>
     &lt;/body&gt;<br/>
     &lt;script&gt;<br/>
     &nbsp;&nbsp;  var Mapper = new ImageMapper();<br/>
	 &nbsp;&nbsp;	Mapper.addImage("testPicture","pictureMap","draw.php?ImageMap=get");<br/>
     &lt;/script&gt;<br/>

    </div>
   </div>

   <br/>
   <br/>

	<table>
		<tr>
			<td><img src='../resources/application_view_list.png' width=16 height=16 alt=''/></td>
			<td>&nbsp;PHP Source area</td>
		</tr>
	</table>

   <div style='display:table-cell; padding: 10px;  border: 2px solid #FFFFFF; vertical-align: middle; overflow: auto; background-image: url("../resources/dash.png");'>
    <div style='font-size: 10px;' id=source style='width: 700px;'>
     <table><tr><td><img src='../resources/accept.png' width=16 height=16 alt=""/></td><td>Click on an example to get its source!</td></tr></table>
    </div>
   </div>

</td>
</tr>
</table>
</body>
</html>