<?php
/*
pImageMap - pChart core class

Version     : 0.1
Made by     : Forked by Momchil Bozhinov from the original pImage class from Jean-Damien POGOLOTTI
Last Update : 27/11/2017

This file can be distributed under the license you can find at:
http://www.pchart.net/license

*/

namespace pChart\pImageMap;

/* Image map handling */
define("IMAGE_MAP_STORAGE_FILE", 680001);
define("IMAGE_MAP_STORAGE_SESSION", 680002);

class pImageMapFile extends \pChart\pDraw implements pImageMapInterface
{
	/* Image map */
	var $ImageMapIndex = "pChart"; // Name of the session array
	var $ImageMapStorageMode = NULL; // Save the current imagemap storage mode
	var $ImageMapFileName = NULL;
	var $ImageMapBuffer = [];
	
	/* Class constructor */
	function __construct(int $XSize, int $YSize, bool $TransparentBackground = FALSE, $Name = "pChart", int $StorageMode = IMAGE_MAP_STORAGE_SESSION, string $UniqueID = "imageMap", string $StorageFolder = "temp")
	{
		/* Initialize the image map methods */
		$this->ImageMapIndex = $Name;
		$this->ImageMapStorageMode = $StorageMode;
		$this->ImageMapFileName = $StorageFolder . "/" . $UniqueID . ".map";
		
		if ($StorageMode == IMAGE_MAP_STORAGE_SESSION) {
			if (!isset($_SESSION)) {
				throw pException::ImageMapSessionNotStarted();
			}
		} 
		
		/* Initialize the parent */
		parent::__construct($XSize, $YSize, $TransparentBackground);
	}
	
	function __destruct(){
		
		if (!empty($this->ImageMapBuffer)){
			if ($this->ImageMapStorageMode == IMAGE_MAP_STORAGE_SESSION) {

				$_SESSION[$this->ImageMapIndex] = $this->ImageMapBuffer;

			} elseif ($this->ImageMapStorageMode == IMAGE_MAP_STORAGE_FILE) {

				file_put_contents($this->ImageMapFileName, $this->formatOutput($this->ImageMapBuffer)); # truncates the file
			}
		}

		parent::__destruct();
	}
	
	private function formatOutput(array $buffer)
	{
		$ret = "";
		
		foreach($buffer as $array) {
			$ret .= $array[0] . chr(1) . $array[1] . chr(1) . $array[2] . chr(1) . $array[3] . chr(1) . $array[4] . "\r\n";
		}
		
		return $ret;
	}
	
	/* does the image map already exist */
	function ImageMapExists(){
		return file_exists($this->ImageMapFileName);
	}
	
	/* Add a zone to the image map */
	function addToImageMap(string $Type, string $Plots, string $Color = "", string $Title = "", string $Message = "", bool $HTMLEncode = FALSE)
	{
		/* Encode the characters in the imagemap in HTML standards */
		$Title = str_replace("&#8364;", "\u20AC", $Title); # Momchil TODO TEST THIS
		$Title = htmlentities($Title, ENT_QUOTES); #, "ISO-8859-15"); # As of PHP 5.6 The default value for the encoding parameter = the default_charset config option.
		
		if ($HTMLEncode) {
			$Message = htmlentities($Message, ENT_QUOTES);
			#$Message = str_replace("&lt;", "<", $Message); # Seems covered Example #1 A htmlentities() example
			#$Message = str_replace("&gt;", ">", $Message); # http://php.net/manual/en/function.htmlentities.php
		}

		$this->ImageMapBuffer[] = [$Type,$Plots,$Color,$Title,$Message];

	}

	/* Remove VOID values from an imagemap custom values array */
	function stripFromSerie(string $SerieName, array $Values)
	{
		if (!isset($this->myData->Data["Series"][$SerieName])) {
			throw pException::ImageMapInvalidSerieName($SerieName);
		}

		$Result = [];
		foreach($this->myData->Data["Series"][$SerieName]["Data"] as $Key => $Value) {
			if ($Value != VOID && isset($Values[$Key])) {
				$Result[] = $Values[$Key];
			}
		}

		return $Result;
	}
	
	/* Replace the title of one image map series */
	function replaceImageMapTitle(string $OldTitle, $NewTitle)
	{
				
		if (is_array($NewTitle)) {
			$ID = 0;
			$NewTitle = $this->stripFromSerie($OldTitle, $NewTitle);
			foreach($this->ImageMapBuffer as $Key => $Settings) {
				if ($Settings[3] == $OldTitle && isset($NewTitle[$ID])) {
					$this->ImageMapBuffer[$Key][3] = $NewTitle[$ID];
					$ID++;
				}
			}
		} else {
			foreach($this->ImageMapBuffer as $Key => $Settings) {
				if ($Settings[3] == $OldTitle) {
					$this->ImageMapBuffer[$Key][3] = $NewTitle;
				}
			}
		}
		
	}

	/* Replace the values of the image map contents */
	function replaceImageMapValues(string $Title, array $Values)
	{

		$Values = $this->stripFromSerie($Title, $Values);
		$ID = 0;

		foreach($this->ImageMapBuffer as $Key => $Settings) {
			if ($Settings[3] == $Title) {
				if (isset($Values[$ID])) {
					$this->ImageMapBuffer[$Key][4] = $Values[$ID];
				}
				$ID++;
			}
		}
	
	}

	/* Dump the image map */
	/* Momchil: this function relies on the fact that the ImageMap for the image already exists */
	function dumpImageMap()
	{
		if ($this->ImageMapStorageMode == IMAGE_MAP_STORAGE_SESSION) {
			if (isset($_SESSION[$this->ImageMapIndex])){
				
				echo $this->formatOutput($_SESSION[$this->ImageMapIndex]);
			
			} else {
				throw pException::ImageMapInvalidID("ImageMap index ".$this->ImageMapIndex. " does not exist in session storage!");
			}
		
		} elseif ($this->ImageMapStorageMode == IMAGE_MAP_STORAGE_FILE) {
			
			if (file_exists($this->ImageMapFileName)){
				
				echo file_get_contents($this->ImageMapFileName);
				
			} else {
				throw pException::ImageMapInvalidID("ImageMap index ".$this->ImageMapIndex. " does not exist in file storage!");
			}
		}

		/* When the image map is returned to the client, the script ends */
		exit();
	}

}

?>