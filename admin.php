<?php
/****************************************************************************
* Name:        admin.php
* Author:      Ben Barnes
* Date:        2016-01-29
* Purpose:     Admin page
*****************************************************************************/

if ($_SESSION["admin"] == 1) {
	if (count($_POST)) {
		getGlobals_admin($getPage_connection2);
		performAction_admin($getPage_connection2,$getPage_connection3);
		getGlobals_admin($getPage_connection2);
		header("Location: index.php?page=admin");
		exit();
	} else {
		getGlobals_admin($getPage_connection2);
		showTitle("Admin");
		compileMenu($getPage_connection2,"admin");
		showWarning($getPage_connection2);
		showAdminInfo($getPage_connection2);
		require "section4.txt";
		resetSession(true);
	} // else
} else {
	echo "Illegal administration attempt: you do not have the required admin clearance to use this script.";
} // else
?>