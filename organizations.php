<?php
/****************************************************************************
* Name:        organizations.php
* Author:      Ben Barnes
* Date:        2016-01-29
* Purpose:     Organizations page
*****************************************************************************/
if (count($_POST)) {
	getGlobals_organizations($getPage_connection2);
	performAction_organizations($getPage_connection2);
	getGlobals_organizations($getPage_connection2);
	header("Location: index.php?page=organizations");
	exit();
} else {
	getGlobals_organizations($getPage_connection2);
	showTitle("Organizations");
	compileMenu($getPage_connection2,"organizations");
	showWarning($getPage_connection2);
	showOrganizationsInfo($getPage_connection2);
	require "section4.txt";
	resetSession(true);
} // else
?>