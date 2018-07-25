pChart 2.1 library for PHP 7 (7-Compatible branch)
===================

The good old pChart got an overhaul!

 - All examples work fine with zero code modifications to them
 - Code was beautified
 - Made minor improvements and added some speed ups
 

pChart 2.2
===================
This version that will NOT work with your existing code.<br />
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
 
 The sandbox and all examples work as expected.<br />
 Code is being tested against PHP versions 7.0, 7.1, 7.2 & 7.3
 
 Word of advice, keep it secure:
 - Set write permissions only to the "cache" and "temp" folders if you are going to be using them.
 - Make sure the content of these folders is not visible to the rest of the world.
