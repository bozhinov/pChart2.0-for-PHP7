<?php
/*
pImageMapSQLite - pChart core class

Version     : 0.2
Made by     : Momchil Bozhinov
Last Update : 22/01/2018
*/

namespace pChart\pImageMap;

class pImageMapSQLite extends \pChart\pDraw implements pImageMapInterface
{
	/* Image map */
	var $DbSQLite;
	var $DbTable;
	var $ImageMapBuffer = [];
	
	/* Class constructor */
	function __construct(int $XSize, int $YSize, bool $TransparentBackground = FALSE, string $UniqueID = "imageMap", string $StorageFile = "temp/imageMap.db")
	{
		$this->DbTable = $UniqueID;
		$this->DbSQLite = new \PDO("sqlite:".$StorageFile); 
		$this->DbSQLite->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		
		/* Create the IM Db */
		$this->InitDb();
		
		/* Initialize the parent */
		parent::__construct($XSize, $YSize, $TransparentBackground);
	}

	/* Create Db schema */
	function InitDb(){

		try{
			$q = $this->DbSQLite->prepare("CREATE TABLE IF NOT EXISTS ".$this->DbSQLite->quote($this->DbTable)." (Type TEXT, Plots BLOB, Color TEXT, Title TEXT, Message TEXT);");
			$q->execute();
		} catch(\PDOException $e) {
			throw \pChart\pException::ImageMapSQLiteException($e->getMessage());
		}
	}
	
	function __destruct()
	{
		if (!empty($this->ImageMapBuffer)){
			try{

				/* flush existing image map */
				$q = $this->DbSQLite->prepare("DELETE FROM ".$this->DbSQLite->quote($this->DbTable).";");
				$q->execute();

				/* store the new image map */
				$this->DbSQLite->beginTransaction();
				$q = $this->DbSQLite->prepare("INSERT INTO ".$this->DbSQLite->quote($this->DbTable)." VALUES(:Type, :Plots, :Color, :Title, :Message)");
				
				foreach($this->ImageMapBuffer as $entry){
					$q->bindParam(':Type',	$entry[0], \PDO::PARAM_STR);
					$q->bindParam(':Plots', $entry[1], \PDO::PARAM_STR);
					$q->bindParam(':Color', $entry[2], \PDO::PARAM_STR);
					$q->bindParam(':Title', $entry[3], \PDO::PARAM_STR);
					$q->bindParam(':Message', $entry[4], \PDO::PARAM_STR);
					$q->execute();
				}

				$this->DbSQLite->commit();
			} catch(\PDOException $e) {
				throw \pChart\pException::ImageMapSQLiteException($e->getMessage());
			}
		}

		parent::__destruct();
	}
	
	function ImageMapExists(){

		$match = [];
		try{
			$q = $this->DbSQLite->prepare("SELECT \"Type\" FROM ".$this->DbSQLite->quote($this->DbTable).";");
			$q->execute();
			$match = $q->fetch(\PDO::FETCH_ASSOC);
			return (empty($match)) ? FALSE : TRUE;

		} catch(\PDOException $e) {
			throw \pChart\pException::ImageMapSQLiteException($e->getMessage());
		}
	}

	/* Add a zone to the image map */
	function addToImageMap(string $Type, string $Plots, string $Color = "", string $Title = "", string $Message = "", bool $HTMLEncode = FALSE)
	{
		/* Encode the characters in the image map in HTML standards */
		$Title = str_replace("&#8364;", "\u20AC", $Title); # Momchil TODO TEST THIS
		$Title = htmlentities($Title, ENT_QUOTES);
		
		if ($HTMLEncode) {
			$Message = htmlentities($Message, ENT_QUOTES);
		}

		$this->ImageMapBuffer[] = [$Type,$Plots,$Color,$Title,$Message];

	}

	/* Remove VOID values from an image map custom values array */
	function stripFromSerie(string $SerieName, array $Values)
	{
		if (!isset($this->myData->Data["Series"][$SerieName])) {
			throw \pChart\pException::ImageMapInvalidSerieName($SerieName);
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
	
	private function formatOutput(array $buffer)
	{
		$ret = [];

		foreach($buffer as $array) {
			$ret[] = array_values($array);
		}
		
		return $ret;
	}
	
	/* Dump the image map */
	/* Momchil: this function relies on the fact that the ImageMap for the image already exists */
	function dumpImageMap()
	{
		try{
			$q = $this->DbSQLite->prepare("SELECT * FROM ".$this->DbSQLite->quote($this->DbTable).";");
			$q->execute();
			$match = $q->fetchAll(\PDO::FETCH_ASSOC);
			echo json_encode($this->formatOutput($match));

		} catch(\PDOException $e) {
			throw \pChart\pException::ImageMapSQLiteException($e->getMessage());
		}
	
		/* When the image map is returned to the client, the script ends */
		exit();
	}

}

?>