<?php
/****************************************************************************
 * Name:        functions_settings.php
 * Author:      Ben Barnes
 * Date:        2016-01-29
 * Purpose:     Settings functions page
 *****************************************************************************/

/********************************
 getGlobals_settings
 get and set global variables for settings page
 ********************************/
function getGlobals_settings($getPage_connection2) {
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
	
		// post: setting password
		if (isset($_POST["setting_password"])) {
			$_SESSION["setting_password"] = cleanString($_POST["setting_password"],true);
		} else {
			$_SESSION["setting_password"] = "";
		} // else
	
		// post: setting password confirm
		if (isset($_POST["setting_password_confirm"])) {
			$_SESSION["setting_password_confirm"] = cleanString($_POST["setting_password_confirm"],true);
		} else {
			$_SESSION["setting_password_confirm"] = "";
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
} // getGlobals_settings

/********************************
 performAction_settings
 calls action for settings if requested and valid
 ********************************/
function performAction_settings($getPage_connection2) {
	if ($_SESSION["action"] == "change") {
		changeSettings($getPage_connection2);
	} // if
} // performAction_settings

/********************************
 showSettingsInfo
 visualize settings information and input
 ********************************/
function showSettingsInfo($getPage_connection2) {
	$nationInfo = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
	$productionInfo = getProductionInfo($getPage_connection2,$_SESSION["nation_id"]);

	echo "        <div class=\"spacing-from-menu well well-lg standard-text\">\n";

	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Change user account settings.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Change Settings        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseChange\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseChange\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	echo "                <form action=\"index.php?page=settings\" method=\"post\">\n";
	echo "                  <input type=\"hidden\" name=\"page\" value=\"settings\" />\n";
	echo "                  <div class=\"form-group form-group-sm\">\n";
	echo "                    <label class=\"control-label\" for=\"currentPassword\">Current Password:</label>\n";
	echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Current password of user account.  This is required to make any changes to your account.\" name=\"current_password\" type=\"password\" class=\"form-control input-md\" id=\"currentPassword\" placeholder=\"password\" />\n";
	echo "                  </div>\n";
	echo "                  <br />\n";
	echo "                  <div class=\"form-group form-group-sm\">\n";
	echo "                    <label class=\"control-label\" for=\"changePassword\">New Password:</label>\n";
	echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"New password of user account.  Should be 8-35 characters.\" name=\"setting_password\" type=\"password\" class=\"form-control input-md\" id=\"changePassword\" placeholder=\"password\" />\n";
	echo "                  </div>\n";
	echo "                  <div class=\"form-group form-group-sm\">\n";
	echo "                    <label class=\"control-label\" for=\"changePasswordConfirm\">Confirm New Password:</label>\n";
	echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Confirm new password of user account.  Should be 8-35 characters.\" name=\"setting_password_confirm\" type=\"password\" class=\"form-control input-md\" id=\"changePasswordConfirm\" placeholder=\"password\" />\n";
	echo "                  </div>\n";
	echo "                  <div class=\"form-group form-group-sm\">\n";
	echo "                    <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Submit changes to user account.\" value=\"change\" name=\"action\" id=\"change\" type=\"submit\" class=\"btn btn-md btn-primary\">Change Settings</button>\n";
	echo "                  </div>\n";
	echo "                </form>\n";
	echo "                <br />\n";
	echo "                <br />\n";
	echo "                ==========";
	echo "                <form action=\"index.php?page=deactivate\" method=\"get\">\n";
	echo "                  <input type=\"hidden\" name=\"page\" value=\"deactivate\" />\n";
	echo "                  <div class=\"form-group form-group-sm\">\n";
	echo "                    <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Deactivate your account.  This will disable access to your account!\" value=\"deactivate\" name=\"action\" id=\"deactivate\" type=\"submit\" class=\"btn btn-md btn-danger\">Deactivate Account</button>\n";
	echo "                  </div>\n";
	echo "                </form>\n";
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";

	echo "        </div>\n";
} // showSettingsInfo

/*-----------------------------------------------*/
/********************************
 Settings Action Functions
 ********************************/
/*-----------------------------------------------*/

function changeSettings($getPage_connection2) {
	if ($_SESSION["action"] == "change") {
		if (strlen($_SESSION["current_password"]) > 0) {
			$userInfo1 = getUserInfoByName($getPage_connection2,$_SESSION["username"]);
			if ($userInfo1["id"] >= 1) {
				$final_salt = '$2y$09$'.$userInfo1["salt"].'$';
				$created_password = crypt($_SESSION["current_password"].$userInfo1["salt"],$final_salt);
				$created_string = hash('sha512', $created_password.$userInfo1["token"]);
				$actual_string = hash('sha512', $userInfo1["password"].$userInfo1["token"]);

				if ($actual_string == $created_string) {
					if ($_SESSION["setting_password"] == $_SESSION["setting_password_confirm"]) {
						if ((strlen($_SESSION["setting_password"]) >= 8 && strlen($_SESSION["setting_password"]) <= 35) && (strlen($_SESSION["setting_password_confirm"]) >= 8 && strlen($_SESSION["setting_password_confirm"]) <= 35)) {
							$new_salt = "";
							$allowed_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
							$chars_length = 63;
							for ($i=0; $i<51; $i++) {
								$new_salt .= $allowed_chars[mt_rand(0,$chars_length)];
							} // for
							$new_token = mt_rand(1000,9999);
							$final_salt = '$2y$09$'.$new_salt.'$';
							$created_password = crypt($_SESSION["setting_password"].$new_salt,$final_salt);
							setUserInfo($getPage_connection2,$userInfo1["id"],$userInfo1["name"],$userInfo1["avatar"],$userInfo1["joined"],$userInfo1["lastplayed"],$created_password,$new_salt,$new_token,$userInfo1["thread"],$userInfo1["admin"]);;

							$created_string2 = hash('sha512', $created_password.$new_token);
							$_SESSION["user_id"] = $userInfo1["id"];
							$_SESSION["username"] = $userInfo1["name"];
							$_SESSION["login_string"] = $created_string2;
							$_SESSION["login"] = 1;
							$_SESSION["nation_id"] = $userInfo1["id"];
							$_SESSION["admin"] = $userInfo1["admin"];
							$_SESSION["pageTypeInfo"] = getPageTypeInfo($getPage_connection2,"map");

							$_SESSION["success_message"] = "User has been updated successfully!";
						} else {
							$_SESSION["warning_message"] = "Cannot complete action: New password must be 8-35 characters.";
						} // else
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: new password not submitted correctly.  Check to make sure both new password fields are identically submitted.";
					} // else

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
} // changeSettings
?>