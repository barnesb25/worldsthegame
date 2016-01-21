<?php
/****************************************************************************
* Name:        deactivate.php
* Author:      Ben Barnes
* Date:        2016-01-20
* Purpose:     Deactivate page
*****************************************************************************/
if (count($_POST)) {
	getGlobals_deactivate($getPage_connection2);
	performAction_deactivate($getPage_connection2);
	getGlobals_deactivate($getPage_connection2);
	header("Location: index.php?page=deactivate");
	exit();
} else {
	getGlobals_deactivate($getPage_connection2);
	showTitle("Deactivate");
	compileMenu($getPage_connection2,"deactivate");
	showWarning($getPage_connection2);
	showDeactivateInfo($getPage_connection2);
	require "section4.txt";
	resetSession(true);
} // else
?>