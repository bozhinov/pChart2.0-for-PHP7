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
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"pSpring - Draw spring charts",["Color"=>new pColor(255,255,255)]);

/* Set the graph area boundaries*/ 
$myPicture->setGraphArea(20,20,280,280);

/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>9,"Color"=>new pColor(80,80,80)));

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"Color"=>new pColor(0,0,0,10)]);

/* Create the pSpring object */ 
$SpringChart = new pSpring($myPicture);

/* Create some nodes */ 
$SpringChart->addNode(0,["Shape"=>NODE_SHAPE_SQUARE,"FreeZone"=>60,"Size"=>20,"NodeType"=>NODE_TYPE_CENTRAL]);
$SpringChart->addNode(1,["Connections"=>[0]]);
$SpringChart->addNode(2,["Connections"=>[0]]);
$SpringChart->addNode(3,["Shape"=>NODE_SHAPE_TRIANGLE,"Connections"=>[1]]);
$SpringChart->addNode(4,["Shape"=>NODE_SHAPE_TRIANGLE,"Connections"=>[1]]);
$SpringChart->addNode(5,["Shape"=>NODE_SHAPE_TRIANGLE,"Connections"=>[1]]);
$SpringChart->addNode(6,["Connections"=>[2]]);
$SpringChart->addNode(7,["Connections"=>[2]]);
$SpringChart->addNode(8,["Connections"=>[2]]);

/* Set the nodes color */ 
$SpringChart->setNodesColor([0],["Color"=>new pColor(215,163,121),"BorderColor"=>new pColor(166,115,74)]);
$SpringChart->setNodesColor([1,2],["Color"=>new pColor(150,215,121),"Surrounding"=>-30]);
$SpringChart->setNodesColor([3,4,5],["Color"=>new pColor(216,166,14),"Surrounding"=>-30]);
$SpringChart->setNodesColor([6,7,8],["Color"=>new pColor(179,121,215),"Surrounding"=>-30]);

/* Set the link properties */ 
$SpringChart->linkProperties(0,1,["Color"=>new pColor(255,0,0),"Ticks"=>2]);
$SpringChart->linkProperties(0,2,["Color"=>new pColor(255,0,0),"Ticks"=>2]);

/* Draw the spring chart */ 
$Result = $SpringChart->drawSpring();

/* Output the statistics */ 
// print_r($Result);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.spring.relations.png");

?>