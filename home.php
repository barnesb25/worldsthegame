<?php
/****************************************************************************
* Name:        home.php
* Author:      Ben Barnes
* Date:        2016-01-03
* Purpose:     Home page
*****************************************************************************/
if (count($_POST)) {
	getGlobals_home($getPage_connection2);
	performAction_home($getPage_connection2);
	getGlobals_home($getPage_connection2);
	header("Location: index.php?page=home");
	exit();
} else if (count($_GET) > 1) {
	getGlobals_home($getPage_connection2);
	performAction_home($getPage_connection2);
	getGlobals_home($getPage_connection2);
	header("Location: index.php?page=home");
	exit();
} else {
	getGlobals_home($getPage_connection2);
	showTitle("Home");
	compileMenu($getPage_connection2,"home");
	showWarning($getPage_connection2);
	showHomeTitle($getPage_connection2);
	showHomeInfo($getPage_connection2);
	require "section4.txt";
	resetSession(true);
} // else
?>