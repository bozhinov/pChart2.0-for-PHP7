<?php   
/* CAT:Spring chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pDraw,
	pSpring
};

/* Create the pChart object */
$myPicture = new pDraw(600,600);

/* Draw the background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,600,600,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,600,600,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,600,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>100,"EndG"=>100,"EndB"=>100,"Alpha"=>80));

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,599,599,array("R"=>0,"G"=>0,"B"=>0));

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"pSpring - Draw spring charts",array("R"=>255,"G"=>255,"B"=>255));

/* Set the graph area boundaries*/ 
$myPicture->setGraphArea(20,20,580,580);

/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>9,"R"=>80,"G"=>80,"B"=>80));

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

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