<?php
/*
pCacheSqlite - Cache images to SQLite database

Requires SQLite PDO ext

Version     : 0.1-dev
Made by     : Momchil Bozhinov
Last Update : 01/01/2018

This file can be distributed under the license you can find at:
http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/

namespace pChart\pCache;

class pCacheSQLite implements pCacheInterface
{
	var $DbSQLite;
	var $DbPath;
	var $Id;

	/* Class creator */
	function __construct(array $Settings = [], string $uniqueId)
	{

		$CacheFolder = isset($Settings["CacheFolder"]) ? $Settings["CacheFolder"] : "cache";
		
		#if (!is_dir($CacheFolder)){
		#	mkdir($CacheFolder, 0775);
		#}

		$this->Id = md5($uniqueId);

		/* blocking the file access to the cache seems a good idea 
		<Files ~ "\cache">
			Order allow,deny
			Deny from all
		</Files> 
		*/

		$this->DbPath = isset($Settings["DbPath"]) ? $Settings["DbPath"] : "sql.cache.db";
		$this->DbPath = $CacheFolder . "/" . $this->DbPath;

		$this->DbSQLite = new \PDO("sqlite:".$this->DbPath); 
		$this->DbSQLite->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		/* Create the cache Db */
		if (!file_exists($this->DbPath)){
			$this->InitDb();
		} else {
			if (filesize($this->DbPath) < 10000){ # freshly created is 12288
				$this->InitDb();
			}
		}
	}

	/* Create Db schema */
	function InitDb(){

		try{
			$q = $this->DbSQLite->prepare("CREATE TABLE cache (Id TEXT,time INTEGER,hits INTEGER,data BLOB,PRIMARY KEY(Id));");
			$q->execute();
		} catch(\PDOException $e) {
			throw \pChart\pException::SQLiteException($e->getMessage());
		}

	}
	
	/* For when you need to work with multiple cached images */
	function changeID(string $uniqueId){
		$this->Id = md5($uniqueId);
	}

	/* Flush the cache contents */
	function flush()
	{
		if (file_exists($this->DbPath)){
			unlink($this->DbPath);
		}
		$this->InitDb();
	}

	/* Write the generated picture to the cache */
	function writeToCache($pChartObject)
	{

		if (!($pChartObject instanceof \pChart\pDraw)){
			die("pCache needs a pDraw object. Please check the examples.");
		}

		/* Create a temporary stream */
		$TempHandle = fopen("php://temp", "wb");

		/* Flush the picture to a temporary file */
		imagepng($pChartObject->Picture, $TempHandle);

		/* Get the picture raw contents */
		rewind($TempHandle);
		$Raw = "";
		while (!feof($TempHandle)) {
			$Raw.= fread($TempHandle, 16384); # 16kb chunks
		}
		/* Close the temporary stream */
		fclose($TempHandle);

		$time = time();
		/* Save picture to cache */
		try{
			$q = $this->DbSQLite->prepare("INSERT INTO cache VALUES(:Id, :time, 0, :data);");
			$q->bindParam(':Id',	$this->Id, \PDO::PARAM_STR);
			$q->bindParam(':time', 	$time, \PDO::PARAM_INT);
			$q->bindParam(':data', 	$Raw, \PDO::PARAM_STR);
			$q->execute();
		} catch(\PDOException $e) {
			throw \pChart\pException::SQLiteException($e->getMessage());
		}

	}

	/* Remove object older than the specified TS */
	function removeOlderThan(int $Expiry)
	{
		$this->dbRemoval(["Expiry" => $Expiry]);
	}

	/* Remove an object from the cache */
	function remove()
	{
		$this->dbRemoval(["Name" => $this->Id]);
	}

	/* Remove with specified criteria */
	function dbRemoval(array $Settings)
	{
		$ID = isset($Settings["Name"]) ? $Settings["Name"] : "";
		$Expiry = isset($Settings["Expiry"]) ? $Settings["Expiry"] : -(24 * 60 * 60);
		$TS = time() - $Expiry;

		/* Single file removal */
		if ($ID != "") {
			/* If it's not in the cache DB, go away */
			if (!$this->isInCache()) {
				throw \pChart\pException::SQLiteException(" ID ".$ID ." not in cache!");
			}
		}
		
		try{
			if ($ID != "") {
				$statement = "DELETE FROM cache WHERE Id= :Id;";
			} else {
				$statement = "DELETE FROM cache WHERE time > :from;";
			}
			$q = $this->DbSQLite->prepare($statement);
			$q->bindParam(':Id', $ID, \PDO::PARAM_STR);
			$q->bindParam(':from', $TS, \PDO::PARAM_INT);
			$q->execute();

		} catch(\PDOException $e) {
			throw pException::SQLiteException($e->getMessage());
		}

	}

	function isInCache(bool $Verbose = FALSE, bool $UpdateHitsCount = FALSE)
	{
		try{
			$q = $this->DbSQLite->prepare("SELECT Id,hits,data FROM cache WHERE Id= :Id;");
			$q->bindParam(':Id', $this->Id, \PDO::PARAM_STR);
			$q->execute();
			$match = $q->fetch(\PDO::FETCH_ASSOC);
			if ($match != FALSE){
				if ($UpdateHitsCount) {
					$match["hits"]++;
					$q = $this->DbSQLite->prepare("UPDATE cache SET hits= :hits WHERE Id= :Id;");
					$q->bindParam(':Id',   $this->Id, \PDO::PARAM_STR);
					$q->bindParam(':hits', $match["hits"], \PDO::PARAM_INT);
					$q->execute();
				}
				return ($Verbose) ? $match["data"] : TRUE;
			} else {
				return FALSE;
			}

		} catch(\PDOException $e) {
			throw \pChart\pException::SQLiteException($e->getMessage());
		}
		
	}

	/* Automatic output method based on the calling interface */
	function autoOutput(string $Destination = "output.png")
	{
		if (php_sapi_name() == "cli") {
			$this->saveFromCache($Destination);
		} else {
			$this->strokeFromCache();
		}
	}

	function strokeFromCache()
	{
		/* Get the raw picture from the cache */
		$Picture = $this->getFromCache();
		/* Do we have a hit? */
		if (!$Picture) {
			return FALSE;
		} else {
			header('Content-type: image/png');
			echo $Picture;
		}
	}

	function saveFromCache(string $Destination)
	{
		/* Get the raw picture from the cache */
		$Picture = $this->getFromCache();
		/* Do we have a hit? */
		if (!$Picture) {
			return FALSE;
		} else {
			/* Flush the picture to a file */
			file_put_contents($Destination, $Picture);
		}

	}

	function getFromCache()
	{
		/* Lookup for the picture in the cache */
		$CacheInfo = $this->isInCache(TRUE, TRUE);

		/* Not in the cache */
		if (!$CacheInfo) { 
			return FALSE;
		} else {
			/* Return back the raw picture data */
			return $CacheInfo;
		}

	}
}

?>