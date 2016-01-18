<?php
/****************************************************************************
* Name:        map.php
* Author:      Ben Barnes
* Date:        2016-01-18
* Purpose:     Map page
*****************************************************************************/
if (count($_POST)) {
	getGlobals_map($getPage_connection2);
	performAction_map($getPage_connection2);
	getGlobals_map($getPage_connection2);
	header("Location: index.php?page=map");
	exit();
} else {
	getGlobals_map($getPage_connection2);
	showTitle("Map");
	compileMenu($getPage_connection2,"map");
	showWarning($getPage_connection2);
	showMap($getPage_connection2);
	showMapInfo($getPage_connection2);
	require "section4.txt";
	resetSession(true);
} // else
?>