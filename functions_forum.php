<?php
/****************************************************************************
 * Name:        functions_forum.php
 * Author:      Ben Barnes
 * Date:        2016-01-18
 * Purpose:     Forum functions page
 *****************************************************************************/

/********************************
 getGlobals_forum
 get and set global variables for forum page
 ********************************/
function getGlobals_forum($getPage_connection2) {
	// get session: admin
	if (isset($_SESSION["admin"])) {
		$_SESSION["admin"] = cleanString($_SESSION["admin"],true);
	} else {
		$_SESSION["admin"] = 0;
	} // else
	 
	// get session: nation_id
	if (isset($_SESSION["nation_id"])) {
		$_SESSION["nation_id"] = cleanString($_SESSION["nation_id"],true);
	} else {
		$_SESSION["nation_id"] = 0;
	} // else

	// get session: user_id
	if (isset($_SESSION["user_id"])) {
		$_SESSION["user_id"] = cleanString($_SESSION["user_id"],true);
	} else {
		$_SESSION["user_id"] = 0;
	} // else

	// get info
	//$_SESSION["userInfo"] = getUserInfo($getPage_connection2,$_SESSION["user_id"]);
	//$_SESSION["nationInfo"] = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
} // getGlobals_forum

/********************************
 showForumInfo
 visualize forum information and input
 ********************************/
function showForumInfo($getPage_connection2) {
	$nationInfo = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
	echo "        <div class=\"spacing-from-menu well well-lg standard-text\">\n";
	echo "          <div class=\"well info_well\">\n";
	echo "            Click on the below link to enter the forum.\n";
	echo "            <br />\n";
	//echo "            Please note that the forum is an external entity and abides by all policies set forth by the forum host.\n";
	//echo "            <br />\n";
	echo "            Follow the rules.\n";
	echo "            <br />\n";
	echo "            <br />\n";
	echo "            <h2><a href=\"http://worldsthegame.com/phpBB3\" target=\"_blank\">ENTER</a></h2>\n";
	echo "          </div>\n";
	echo "        </div>\n";
} // showForumInfo
?>