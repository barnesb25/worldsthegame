<?php
/****************************************************************************
* Name:        about.php
* Author:      Ben Barnes
* Date:        2016-02-20
* Purpose:     About page
*****************************************************************************/
getGlobals_about($getPage_connection2);
showTitle("About");
compileMenu($getPage_connection2,"about");
showWarning($getPage_connection2);
showPageTitle($getPage_connection2,"About",false);
showAboutInfo($getPage_connection2);
require "section4.txt";
resetSession(true);
?>