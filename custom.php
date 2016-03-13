<?php
/****************************************************************************
* Name:        custom.php
* Author:      Ben Barnes
* Date:        2016-02-20
* Purpose:     Custom script page
*****************************************************************************/
	
if ($_SESSION["admin"] == 1) {
	endTurn($getPage_connection3);

	echo "Running admin script...";
} else {
	echo "Illegal administration attempt: you do not have the required admin clearance to use this script.";
} // else
	
?>