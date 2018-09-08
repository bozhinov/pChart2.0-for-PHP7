<?php   
/* CAT:Spring chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pColor,
	pDraw,
	pSpring
};

/* Create the pChart object */
$myPicture = new pDraw(600,600);

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,600,600,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,600,600, DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,600,20,  DIRECTION_VERTICAL,["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(50,50,50,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,599,599,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"pSpring - Draw spring charts",["Color"=>new pColor(255,255,255)]);

/* Set the graph area boundaries*/ 
$myPicture->setGraphArea(20,20,580,580);

/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>9,"Color"=>new pColor(80,80,80)));

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color"=>new pColor(0,0,0,10)]);

/* Create the pSpring object */ 
$SpringChart = new pSpring($myPicture);

/* Set the default parameters for newly added nodes */ 
$SpringChart->setNodeDefaults(["FreeZone"=>70]);

/* Create 11 random nodes */ 
for($i=0;$i<=10;$i++)
{
	$Connections = []; 
	$RdCx = rand(0,1);
	
	for($j=0;$j<=$RdCx;$j++)
	{
		$RandCx = rand(0,10);
		if($RandCx != $j)
		{
			$Connections[] = $RandCx;
		}
	}

	$SpringChart->addNode($i,["Name"=>"Node ".$i,"Connections"=>$Connections]);
}

/* Draw the spring chart */ 
$Result = $SpringChart->drawSpring(["DrawQuietZone"=>TRUE,"Algorithm"=>ALGORITHM_CIRCULAR,"RingSize"=>100]); //WEIGHTED

/* Output the statistics */ 
// print_r($Result);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.spring.complex.png");

?>