<?php
/****************************************************************************
* Name:        forum.php
* Author:      Ben Barnes
* Date:        2016-02-20
* Purpose:     Forum page
*****************************************************************************/
getGlobals_forum($getPage_connection2);
showTitle("Forum");
compileMenu($getPage_connection2,"forum");
showWarning($getPage_connection2);
showForumInfo($getPage_connection2);
require "section4.txt";
resetSession(true);
?>