<?php
/****************************************************************************
* Name:        info.php
* Author:      Ben Barnes
* Date:        2016-01-20
* Purpose:     Info page
*****************************************************************************/
if (count($_POST)) {
	getGlobals_info($getPage_connection2);
	performAction_info($getPage_connection2);
	getGlobals_info($getPage_connection2);
	header("Location: index.php?page=info");
	exit();
} else {
	getGlobals_info($getPage_connection2);
	showTitle("Info");
	compileMenu($getPage_connection2,"info");
	showWarning($getPage_connection2);
	showPageTitle($getPage_connection2,"Info",false);
	showInfoInfo($getPage_connection2);
	require "section4.txt";
	resetSession(true);
} // else
?>