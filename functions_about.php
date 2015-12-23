<?php
/****************************************************************************
 * Name:        functions_about.php
 * Author:      Ben Barnes
 * Date:        2015-12-21
 * Purpose:     About functions page
 *****************************************************************************/

/********************************
 getGlobals_about
 get and set global variables for about page
 ********************************/
function getGlobals_about($getPage_connection2) {
	// session: admin
	if (isset($_SESSION["admin"])) {
		$_SESSION["admin"] = cleanString($_SESSION["admin"],true);
	} else {
		$_SESSION["admin"] = 0;
	} // else
	 
	// session: user_id
	if (isset($_SESSION["user_id"])) {
		$_SESSION["user_id"] = cleanString($_SESSION["user_id"],true);
	} else {
		$_SESSION["user_id"] = 0;
	} // else

	// get info
	$_SESSION["userInfo"] = getUserInfo($getPage_connection2,$_SESSION["user_id"]);
} // getGlobals_about

/********************************
 showAboutInfo
 visualize about information and input
 ********************************/
function showAboutInfo($getPage_connection2) {
	$aboutInfo = getAboutInfo($getPage_connection2, 1);
	
	echo "        <div class=\"spacing-from-menu well well-lg standard-text\">\n";
	echo "          <div class=\"well info_well\">\n";
	
	echo "            <br />\n\n".$aboutInfo["text"]."<br />\n\n";

	echo "          </div>\n";
	echo "        </div>\n";
} // showAboutInfo
?>