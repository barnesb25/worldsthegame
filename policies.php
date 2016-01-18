<?php
/****************************************************************************
* Name:        policies.php
* Author:      Ben Barnes
* Date:        2016-01-18
* Purpose:     Policies page
*****************************************************************************/
if (count($_POST)) {
	getGlobals_policies($getPage_connection2);
	performAction_policies($getPage_connection2);
	getGlobals_policies($getPage_connection2);
	header("Location: index.php?page=policies");
	exit();
} else {
	getGlobals_policies($getPage_connection2);
	showTitle("Policies");
	compileMenu($getPage_connection2,"policies");
	showWarning($getPage_connection2);
	showPoliciesInfo($getPage_connection2);
	require "section4.txt";
	resetSession(true);
} // else
?>