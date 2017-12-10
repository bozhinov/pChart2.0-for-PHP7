<?php

/*
pException - pChart core class

Version     : 0.1
Made by     : Created by Momchil Bozhinov
Last Update : 27/11/2017

This file can be distributed under the license you can find at:
http://www.pchart.net/license

*/

namespace pChart;

class pException extends \Exception
{

	public static function InvalidDimentions($text)
	{
		return new static(sprintf('pChart: %s', $text));
	}

	public static function InvalidImageType($text)
	{
		return new static(sprintf('pChart: %s', $text));
	}

	public static function InvalidInput($text)
	{
		return new static(sprintf('pChart: %s', $text));
	}

	public static function InvalidResourcePath($text)
	{
		return new static(sprintf('pChart: %s', $text));
	}

	public static function ImageMapInvalidID($text)
	{
		return new static(sprintf('pChart: %s', $text));
	}

	public static function ImageMapSessionNotStarted()
	{
		return new static('pChart: Yon need to start session before you can use the session storage');
	}

	public static function ImageMapInvalidSerieName($text)
	{
		return new static(sprintf('pChart: The serie name "%s" was not found in the dataset', $text));
	}
	
	public static function SQLiteException($text)
	{
		return new static(sprintf('pCache: %s', $text));
	}
}

?>