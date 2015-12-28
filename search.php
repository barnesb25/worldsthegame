<?php
/****************************************************************************
* Name:        search.php
* Author:      Ben Barnes
* Date:        2015-12-28
* Purpose:     Search page
*****************************************************************************/
if (count($_POST)) {
	getGlobals_search($getPage_connection2);
	performAction_search($getPage_connection2);
	getGlobals_search($getPage_connection2);
	header("Location: index.php?page=search");
	exit();
} else {
	getGlobals_search($getPage_connection2);
	showTitle("Search");
	compileMenu($getPage_connection2,"search");
	showWarning($getPage_connection2);
	showPageTitle($getPage_connection2,"Search",true);
	showSearchInfo($getPage_connection2);
	require "section4.txt";
	resetSession(true);
} // else
?>