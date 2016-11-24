pChart 2.0 library for PHP 7
===================

The good old pChart got a overhaul

 - All examples work fine with zero code modifications to them
 - Code was beautified
 - Made some minor improvements and added some speed ups
 
There is still stuff I don't yet understand. Like:
 
if ($Alpha == 100) { 
	$this->drawAlphaPixel($X, $Y, 100, $R, $G, $B, true);
} else {
	$this->drawAlphaPixel($X, $Y, $Alpha, $R, $G, $B, true);
}

or 

if ($StartAngle > 180) {
	$Visible[$Slice]["Start"] = TRUE;
}	else {
	$Visible[$Slice]["Start"] = TRUE; 
}

Hopefully I can get Jean-Damien to help out
				
				