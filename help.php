<?php
/****************************************************************************
* Name:        help.php
* Author:      Ben Barnes
* Date:        2016-01-29
* Purpose:     Help page
*****************************************************************************/
getGlobals_help($getPage_connection2);
showTitle("Help");
compileMenu($getPage_connection2,"help");
showWarning($getPage_connection2);
showHelpInfo($getPage_connection2);
require "section4.txt";
resetSession(true);
?>