 The sandbox and all examples work as expected.<br />
 Code is being tested against PHP versions 7.0 to 7.4
 
 Word of advice, keep it secure:
 - Set write permissions only to the "cache" and "temp" folders if you are going to be using them.
 - Make sure the content of these folders is not visible to the rest of the world.
 
 Please check the readme.txt file for details on licensing.
 

pChart 2.1 library for PHP 7 (7-Compatible branch)
===================

The good old pChart got an overhaul!

 - All examples work fine with zero code modifications to them
 - Code was beautified
 - Made minor improvements and added some speed ups


pChart 2.2
===================
This version will NOT work with your existing code, but supports PHP 7.0 & 7.1<br />
Please check the change log for the complete list of changes.<br />

Major changes:
 - Code cleanup
 - Bootstrapped
 - Exceptions
 - Introduced pColor & pColorGradient
 - Moved functions around
 - Added support for compression and filters in PNG output
 - Cache: added PDO SQLite storage option
 - ImageMapper: JavaScript re-write using jQuery
 - ImageMapper: added PDO SQLite storage option
 - Removed DelayedLoader


 pChart 2.3 (recommended)
===================
Goals:
 - Reduce the use of the hard disk to fonts only
 - Eliminate not-exactly-free 3rd party components
 - Add the first batch of new features since 2011

Major changes:
 - PHP 7.2+ required
 - Introduce pQRCode /* Check my PHP-QRCode-fork repo */
 - Replace all fonts with Open Font licensed ones
 - Performace boost
 - pBarcode to own dir
 - No more config files (palettes & barcode db)


  pChart 2.4
===================
Goals:
 - Introduce pPyramid
 - Replace exsting barcode implementation. Add more barcode types
 - Remove pImageMap, pCache & pSpring

