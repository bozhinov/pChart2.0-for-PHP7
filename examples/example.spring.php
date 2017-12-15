<?php   
/* CAT:Spring chart */

/* pChart library inclusions */
require_once("bootstrap.php");

use pChart\{
	pDraw,
	pSpring
};

/* Create the pChart object */
$myPicture = new pDraw(300,300);

/* Draw the background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,300,300,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,300,300,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,300,20,DIRECTION_VERTICAL,["StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>100,"EndG"=>100,"EndB"=>100,"Alpha"=>80]);

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,299,299,["R"=>0,"G"=>0,"B"=>0]);

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"pSpring - Draw spring charts",["R"=>255,"G"=>255,"B"=>255]);

/* Set the graph area boundaries*/ 
$myPicture->setGraphArea(20,20,280,280);

/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>9,"R"=>80,"G"=>80,"B"=>80));

/* Enable shadow computing */ 
$myPicture->setShadow(TRUE,["X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10]);

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
$SpringChart->setNodesColor([0],["R"=>215,"G"=>163,"B"=>121,"BorderR"=>166,"BorderG"=>115,"BorderB"=>74]);
$SpringChart->setNodesColor([1,5,6,7],["R"=>245,"G"=>183,"B"=>241,"Surrounding"=>-30]);
$SpringChart->setNodesColor([2,3,4,8],["R"=>183,"G"=>224,"B"=>245,"Surrounding"=>-30]);

/* Draw the spring chart */ 
$Result = $SpringChart->drawSpring(["DrawQuietZone"=>TRUE]);

/* Output the statistics */ 
// print_r($Result);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("temp/example.spring.png");

?>