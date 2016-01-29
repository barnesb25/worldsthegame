<?php
/****************************************************************************
 * Name:        functions_deactivate.php
 * Author:      Ben Barnes
 * Date:        2016-01-29
 * Purpose:     Deactivate functions page
 *****************************************************************************/

/********************************
 getGlobals_deactivate
 get and set global variables for deactivate page
 ********************************/
function getGlobals_deactivate($getPage_connection2) {
	// session: admin
	if (isset($_SESSION["admin"])) {
		$_SESSION["admin"] = cleanString($_SESSION["admin"],true);
	} else {
		$_SESSION["admin"] = 0;
	} // else
		
	if (count($_POST)) {	 
		// post: current action
		if (isset($_POST["action"])) {
			$_SESSION["action"] = cleanString($_POST["action"],true);
		} else {
			$_SESSION["action"] = "";
		} // else
	
		// post: current password
		if (isset($_POST["current_password"])) {
			$_SESSION["current_password"] = cleanString($_POST["current_password"],true);
		} else {
			$_SESSION["current_password"] = "";
		} // else
	} else if (count($_GET)) {		
	} // else if

	// session: nation_id
	if (isset($_SESSION["nation_id"])) {
		$_SESSION["nation_id"] = cleanString($_SESSION["nation_id"],true);
	} else {
		$_SESSION["nation_id"] = 0;
	} // else

	// session: user_id
	if (isset($_SESSION["user_id"])) {
		$_SESSION["user_id"] = cleanString($_SESSION["user_id"],true);
	} else {
		$_SESSION["user_id"] = 0;
	} // else

	// get info
	//$_SESSION["userInfo"] = getUserInfo($getPage_connection2,$_SESSION["user_id"]);
	//$_SESSION["nationInfo"] = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
} // getGlobals_deactivate

/********************************
 performAction_deactivate
 calls action for deactivate if requested and valid
 ********************************/
function performAction_deactivate($getPage_connection2) {
	if ($_SESSION["action"] == "yes") {
		deactivateAccount($getPage_connection2);
	} else if ($_SESSION["action"] == "no") {
		//returnToMap
	}  // else if
} // performAction_deactivate

/********************************
 showDeactivateInfo
 visualize deactivate information and input
 ********************************/
function showDeactivateInfo($getPage_connection2) {
	$nationInfo = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
	$productionInfo = getProductionInfo($getPage_connection2,$_SESSION["nation_id"]);

	echo "        <div class=\"spacing-from-menu well well-lg standard-text\">\n";

	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Change user account deactivate.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Deactivate Account Confirmation        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseChange\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseChange\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	echo "                Are you sure you want to deactivate your account?\n";
	echo "                <form action=\"index.php?page=deactivate\" method=\"post\">\n";
	echo "                  <input type=\"hidden\" name=\"page\" value=\"deactivate\" />\n";
	echo "                  <br />\n";
	echo "                  <div class=\"form-group form-group-sm\">\n";
	echo "                    <label class=\"control-label\" for=\"currentPassword\">Current Password:</label>\n";
	echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Current password of user account.  This is required to make any changes to your account.\" name=\"current_password\" type=\"password\" class=\"form-control input-md\" id=\"currentPassword\" placeholder=\"password\" />\n";
	echo "                  </div>\n";
	echo "                  <div class=\"form-group form-group-sm\">\n";
	echo "                    <a href=\"index.php?page=settings\" onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Get me out of here and back to the game!\" value=\"no\" name=\"action\" id=\"no\" type=\"submit\" class=\"btn btn-md btn-success\">No!  Get me out of here!</a>\n";
	echo "                    <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Deactivate your account.  This will disable access to your account!\" value=\"yes\" name=\"action\" id=\"yes\" type=\"submit\" class=\"btn btn-md btn-danger\">Yes!  I want to deactivate!</button>\n";	
	echo "                  </div>\n";
	echo "                </form>\n";
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";

	echo "        </div>\n";
} // showDeactivateInfo

/*-----------------------------------------------*/
/********************************
 Deactivate Action Functions
 ********************************/
/*-----------------------------------------------*/

function deactivateAccount($getPage_connection2) {
	if ($_SESSION["action"] == "yes") {
		if (strlen($_SESSION["current_password"]) > 0) {
			$userInfo1 = getUserInfoByName($getPage_connection2,$_SESSION["username"]);
			if ($userInfo1["id"] >= 1) {
				$final_salt = '$2y$09$'.$userInfo1["salt"].'$';
				$created_password = crypt($_SESSION["current_password"].$userInfo1["salt"],$final_salt);
				$created_string = hash('sha512', $created_password.$userInfo1["token"]);
				$actual_string = hash('sha512', $userInfo1["password"].$userInfo1["token"]);

				if ($actual_string == $created_string) {
					// setup inaccessible passwords
					setUserInfo($getPage_connection2,$userInfo1["id"],$userInfo1["name"],$userInfo1["avatar"],$userInfo1["joined"],$userInfo1["lastplayed"],"aaaaaaab","aaaaaaab",1212,$userInfo1["thread"],$userInfo1["admin"]);
					resetSession(false);
					$_SESSION["success_message"] = "User de-activation has been registered successfully!";
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: invalid user password credentials submitted.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: invalid user submitted.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: invalid user password credentials submitted.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: invalid action.";
	} // else
} // deactivateAccount
?>