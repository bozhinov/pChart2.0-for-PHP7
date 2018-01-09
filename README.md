pChart 2.1 library for PHP 7 (7-Compatible branch)
===================

The good old pChart got an overhaul!

For a compatible version, please check the "7-Compatible" branch:
 - All examples work fine with zero code modifications to them
 - Code was beautified
 - Made minor improvements and added some speed ups
 

pChart 2.2 (Master branch)
===================
This version that will NOT work with your existing code.<br />
Please check the change log for the complete list of changes.<br />

Major changes:
 - Bootstrapped
 - Exceptions
 - Introduced pColor & pColorGradient
 - Moved functions around
 - Cache can use SQLite for storage
 - Code cleanup
 - Added support for compression and filters in PNG output
 - ImageMapper JavaScript re-write using jQuery
 - ImageMapper can use SQLite for storage
 - Removed DelayedLoader
 
 The sandbox and all examples work as expected.<br />
 Code is being tested against PHP versions 7.0, 7.1 and 7.2
 
 Word of advice, keep it secure.
 - Set write permissions only to the "cache" folder if you are going to be using it
 - Make sure content of the "cache" folder not is visible to the rest of the world