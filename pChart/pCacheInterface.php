<?php
/*
pCacheInterface

Version     : 0.1-dev
Made by     : Momchil Bozhinov
Last Update : 01/01/2018

This file can be distributed under the license you can find at:
http://www.pchart.net/license

You can find the whole class documentation on the pChart web site.
*/

namespace pChart;

Interface pCacheInterface
{
	/* Class creator */
	function __construct(array $Settings = [], string $uniqueId);
	
	/* For when you need to work with multiple cached images */
	function changeID(string $uniqueId);

	/* Flush the cache contents */
	function flush();

	/* Write the generated picture to the cache */
	function writeToCache($pChartObject);

	/* Remove object older than the specified TS */
	function removeOlderThan($Expiry);

	/* Remove an object from the cache */
	function remove();

	/* Remove with specified criteria */
	function dbRemoval($Settings);

	function isInCache($Verbose = FALSE, $UpdateHitsCount = FALSE);

	/* Automatic output method based on the calling interface */
	function autoOutput($Destination = "output.png");

	function strokeFromCache();

	function saveFromCache($Destination);

	function getFromCache();
}

?>