<?php
/****************************************************************************
* Name:        trade.php
* Author:      Ben Barnes
* Date:        2016-01-03
* Purpose:     Trade page
*****************************************************************************/
if (count($_POST)) {
	getGlobals_trade($getPage_connection2);
	performAction_trade($getPage_connection2);
	getGlobals_trade($getPage_connection2);
	header("Location: index.php?page=trade");
	exit();
} else {
	getGlobals_trade($getPage_connection2);
	showTitle("Trade");
	compileMenu($getPage_connection2,"trade");
	showWarning($getPage_connection2);
	showTradeInfo($getPage_connection2);
	require "section4.txt";
	resetSession(true);
} // else
?>