
 +----------------------------------------------------------------------------+
 ¦                                                                            ¦
 ¦   pChart - a PHP Charting library                                          ¦
 ¦                                                                            ¦
 ¦   Version            : 2.4.0                                               ¦
 ¦   Based on code by   : Jean-Damien POGOLOTTI                               ¦
 ¦   Maintained by      : Momchil Bozhinov     				      ¦
 ¦   Last Update        : 27/07/2021                                          ¦
 ¦                                                                            ¦
 +----------------------------------------------------------------------------+

 = WHAT CAN pCHART DO FOR YOU? --------------------------------------------------

  pChart is a PHP framework that will help you to create anti-aliased charts or
  pictures directly  from your web  server. You can  then display the result in
  the client browser, send it by mail or insert it into PDFs. 

 = PACKAGE CONTENTS -------------------------------------------------------------
 
 -
 +- /pChart		This folder contains the library core classes
 ¦   +- /Barcodes	Classes to draw all sorts of barcodes
 ¦   +- pBubble		Class to draw bubble charts
 ¦   +- pCharts		Class to draw several types of charts
 ¦   +- pColor		Data structure for colors
 ¦   +- pColorGradient	Data structure for gradient color
 ¦   +- pConf		Data structure for Barcodes
 ¦   +- pData		Class to manipulate chart data
 ¦   +- pDraw		Core drawing functions
 ¦   +- pException	Exceptions for all classes
 ¦   +- pIndicator	Class to draw indicators
 ¦   +- pPie	        Class to draw pie charts
 ¦   +- pPyramid        Class to draw pyramid charts
 ¦   +- pRadar	        Class to draw radar charts
 ¦   +- pScatter	Class to draw scatter charts
 ¦   +- pSpring		Class to draw spring charts
 ¦   +- pStock		Class to draw stock charts
 ¦   +- pSurface	Class to draw surface charts
 ¦
 +- /pChart		This folder contains the library core classes
 ¦
 +- /fonts		This folder contains a bunch of TTF fonts
 ¦
 +- /examples		This folder contains some PHP examples
 ¦   ¦
 ¦   +- resources	Images, icons and jQuery
 ¦   +- sandbox		Powerful dev. tool to design your charts
 ¦
 +- /temp		This folder is used for the examples output
 ¦
 +- change.log		History of all the changes since the 2.1.4a
 +- readme.txt		This file

 = PREREQUISITES ----------------------------------------------------------------

  The pChart library is compatible with PHP 7.2+ versions.
  It requires the GD PHP extension with FreeType support installed.
  PDF417 requires bcmath extension for 32bit versions of PHP
  QRCode requires mbstring extension for mode Kanji

 = BARCODES ---------------------------------------------------------------------

  The pChart library now incldes support for the following barcode types:
	- QRCode, Aztec, DMTX, PDF417, Codabar, ITF, UPC, Code39, Code93, Code128

 = RUNNING THE EXAMPLES ---------------------------------------------------------

  pChart is  shipped with  examples (located  in the /examples folder) that you
  can either render from a web page using your http and pointing to this folder
  or from the command line invoking the php interpreter.

  On windows OS,  assuming that  your PHP binaries  are correctly configured in
  the PATH environment variable you can also execute the BuildAll.cmd batch file.

 = LICENSE ----------------------------------------------------------------------

  The code from the original pChart library is released under two different
  licenses. If your application is not a commercial one (eg: you make no money 
  by redistributing it) then the GNU GPLv3 license (General Public License) applies.
  This license allows you to freely integrate this library in your applications,
  modify the code and redistribute it in bundled packages as long as your application
  is also distributed with the GPL license. 

  The GPLv3 license description can be found in the LICENSE file.

  If your  application can't  meet the GPL  license or is a commercial one (eg:
  the library is  integrated in a software or an appliance you're selling) then
  you'll have to buy a commercial  license. With this license you don't need to
  make publicly available your application code under the GPL license terms.

  Commercial license prices vary.
  Please consult the web page : http://www.pchart.net/license

 = LICENSE FOR CODE BY MOMCHIL BOZHINOV -----------------------------------------
 
  The following components are distributed under MIT license (LICENSE.momchil.txt):
    pColor
    pColorGradient
    pConf
    pException

  The same license applies to any code modifications I made to the original code
  and all the barcode libraries.

 = EXTERNAL COPYRIGHTS -----------------------------------------------------------

  Those external  components are  only provided as a base to run examples. 
  The pChart library does not depend on any of them.

  Famfamfam icons has been made by Mark James

  The provided font files are downloaded from fonts.google.com and are licensed under OFL 1.1

   +- Gayathri-Regular.ttf      Designer SMC
   +- Signika-Regular.ttf       Designer Anna Giedrys
   +- Cairo-Regular.ttf         Designer Multiple Designers
   +- Dosis-Light	        Designer Impallari Type
   +- Abel-Regular.ttf	        Designer MADType
   +- PressStart2P-Regular.ttf	Designer CodeMan38

 = SUPPORT --------------------------------------------------------------------

  Since  the  beginning, pChart  is a community  driven project. You're missing
  feature then ask! I will try and get it implemented  in the future version or
  you'll be guided to create a class extension for your own needs. 

  Original pChart portal : http://www.pchart.net
  Documentation		 : php -S 127.0.0.1:8080
  Report issues          : https://github.com/bozhinov/pChart2.0-for-PHP7/issues