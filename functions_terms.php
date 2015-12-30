<?php
/****************************************************************************
 * Name:        functions_terms.php
 * Author:      Ben Barnes
 * Date:        2015-12-29
 * Purpose:     Terms functions page
 *****************************************************************************/

/********************************
 getGlobals_terms
 get and set global variables for terms page
 ********************************/
function getGlobals_terms($getPage_connection2) {
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
} // getGlobals_terms

/********************************
 showTermsInfo
 visualize terms information and input
 ********************************/
function showTermsInfo($getPage_connection2) {
	$termsInfo = getTermsInfo($getPage_connection2, 1);
	
	echo "        <div class=\"spacing-from-menu well well-lg standard-text\">\n";
	echo "          <div class=\"well info_well\">\n";
	echo "            Version ".$termsInfo["version"]." - Last updated: ".$termsInfo["date"]."<br />";
	
	echo "            <br /><br />\n\n".$termsInfo["text"]."<br />\n\n";

	echo "          </div>\n";
	echo "        </div>\n";
} // showTermsInfo
?>