<?php
/****************************************************************************
* Name:        settings.php
* Author:      Ben Barnes
* Date:        2015-12-21
* Purpose:     Settings page
*****************************************************************************/
if (count($_POST)) {
	getGlobals_settings($getPage_connection2);
	performAction_settings($getPage_connection2);
	getGlobals_settings($getPage_connection2);
	header("Location: index.php?page=settings");
	exit();
} else {
	getGlobals_settings($getPage_connection2);
	showTitle("Settings");
	compileMenu($getPage_connection2,"settings");
	showWarning($getPage_connection2);
	showSettingsInfo($getPage_connection2);
	require "section4.txt";
	resetSession(true);
} // else
?>