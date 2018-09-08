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
$myPicture = new pDraw(300,300);

/* Draw the background */
$myPicture->drawFilledRectangle(0,0,300,300,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);

/* Overlay with a gradient */
$myPicture->drawGradientArea(0,0,300,300,DIRECTION_VERTICAL,["StartColor"=>new pColor(219,231,139,50),"EndColor"=>new pColor(1,138,68,50)]);
$myPicture->drawGradientArea(0,0,300,20,DIRECTION_VERTICAL, ["StartColor"=>new pColor(0,0,0,80),"EndColor"=>new pColor(100,100,100,80)]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,299,299,["Color"=>new pColor(0,0,0)]);

/* Write the picture title */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6]);
$myPicture->drawText(10,13,"pSpring - Draw spring charts",["Color"=>new pColor(255,255,255)]);

/* Set the graph area boundaries*/ 
$myPicture->setGraphArea(20,20,280,280);

/* Set the default font properties */ 
$myPicture->setFontProperties(["FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>9,"Color"=>new pColor(80,80,80)]);

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color"=>new pColor(0,0,0,10)]);

/* Create the pSpring object */ 
$SpringChart = new pSpring($myPicture);

/* Create some nodes */ 
$SpringChart->addNode(0,["Name"=>"","Shape"=>NODE_SHAPE_SQUARE,"FreeZone"=>60,"Size"=>20,"NodeType"=>NODE_TYPE_CENTRAL]);
$SpringChart->addNode(1,["Name"=>"Johanna","Connections"=>[0]]);
$SpringChart->addNode(2,["Name"=>"Martin","Connections"=>[0]]);
$SpringChart->addNode(3,["Name"=>"Kevin","Connections"=>[1]]);
$SpringChart->addNode(4,["Name"=>"Alex","Connections"=>[1]]);
$SpringChart->addNode(5,["Name"=>"Julia","Connections"=>[1]]);
$SpringChart->addNode(6,["Name"=>"Lena","Connections"=>[2]]);
$SpringChart->addNode(7,["Name"=>"Elisa","Connections"=>[2]]);
$SpringChart->addNode(8,["Name"=>"Omar","Connections"=>[2]]);

/* Set the nodes color */ 
$SpringChart->setNodesColor([0],["Color"=>new pColor(215,163,121),"BorderColor"=>new pColor(166,115,74)]);
$SpringChart->setNodesColor([1,5,6,7],["Color"=>new pColor(245,183,241),"Surrounding"=>-30]);
$SpringChart->setNodesColor([2,3,4,8],["Color"=>new pColor(183,224,245),"Surrounding"=>-30]);

/* Draw the spring chart */ 
$Result = $SpringChart->drawSpring(["DrawQuietZone"=>TRUE]);

/* Output the statistics */ 
// print_r($Result);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.spring.png");

?>