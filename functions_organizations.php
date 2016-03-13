<?php
/****************************************************************************
 * Name:        functions_organizations.php
 * Author:      Ben Barnes
 * Date:        2016-02-20
 * Purpose:     Organizations functions page
 *****************************************************************************/

/********************************
 getGlobals_organizations
 get and set global variables for organizations page
 ********************************/
function getGlobals_organizations($getPage_connection2) {
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
	
		// post: organization id
		if (isset($_POST["org"])) {
			$_SESSION["org"] = cleanString($_POST["org"],true);
		} else {
			$_SESSION["org"] = "";
		} // else
	
		// post: name for organization
		if (isset($_POST["orgname"])) {
			$_SESSION["name"] = cleanString($_POST["orgname"],false);
		} else {
			$_SESSION["name"] = "";
		} // else
	
		// post: nation for organization
		if (isset($_POST["orgnation"])) {
			$_SESSION["nation"] = cleanString($_POST["orgnation"],false);
		} else {
			$_SESSION["nation"] = "";
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
} // getGlobals_organizations

/********************************
 performAction_organizations
 calls action for organizations if requested and valid
 ********************************/
function performAction_organizations($getPage_connection2) {
	if ($_SESSION["action"] == "create") {
		createOrganization($getPage_connection2);
	} else if ($_SESSION["action"] == "invite") {
		inviteNation($getPage_connection2);
	} else if ($_SESSION["action"] == "kick") {
		kickNation($getPage_connection2);
	} else if ($_SESSION["action"] == "appoint") {
		appointManager($getPage_connection2);
	} else if ($_SESSION["action"] == "dismiss") {
		dismissManager($getPage_connection2);
	} else if ($_SESSION["action"] == "leave") {
		leaveOrganization($getPage_connection2);
	} else if ($_SESSION["action"] == "join") {
		joinOrganization($getPage_connection2);
	} else if ($_SESSION["action"] == "decline") {
		declineOrganization($getPage_connection2);
	} // else if
} // performAction_organizations

/********************************
 showOrganizationsInfo
 visualize organizations information and input
 ********************************/
function showOrganizationsInfo($getPage_connection2) {
	$nationInfo = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
	$authorityReport = getAuthorityReport($nationInfo["authority"]);
	$economyReport = getEconomyReport($nationInfo["economy"]);

	echo "        <div class=\"spacing-from-menu well well-lg standard-text\">\n";

	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Top 5 Organizations, according to the previous turn's statistics.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Top Organizations        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseTop\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseTop\" class=\"panel-body collapse in\">\n";

	for ($a=1; $a < 6; $a++) {
		$found = false;
		$organization = array("id"=>0,"name"=>"","members"=>0,"managers"=>0,"pending"=>0,"ranking"=>0);
		if ($stmt = $getPage_connection2->prepare("SELECT id,name,members,managers,ranking,pending FROM organizations WHERE ranking=? ORDER BY ranking DESC LIMIT 1")) {
			$stmt->bind_param("i", $a);
			$stmt->execute();
			$stmt->bind_result($r_id,$r_name,$r_members,$r_managers,$r_pending,$r_ranking);
			$stmt->fetch();
			$organization["id"] = $r_id;
			$organization["name"] = $r_name;
			if (stripos($r_members,",")) {
				$organization["members"] = explode(",",$r_members);
			} else {
				$organization["members"] = array(0=>$r_members);
			} // else
			if (stripos($r_managers,",")) {
				$organization["managers"] = explode(",",$r_managers);
			} else {
				$organization["managers"] = array(0=>$r_managers);
			} // else
			if (stripos($r_pending,",")) {
				$organization["pending"] = explode(",",$r_pending);
			} else {
				$organization["pending"] = array(0=>$r_pending);
			} // else
			$organization["ranking"] = $r_ranking;

			if ($organization["id"] >= 1) {
				$found = true;
			} // if
			$stmt->close();
		} else {
			break;
		} // else
		if ($found === true) {
			if (count($organization["members"]) >= 2) {
				echo "              ".$a.". ".$organization["name"]." - ".count($organization["members"])." members\n";
			} else {
				echo "              ".$a.". ".$organization["name"]." - ".count($organization["members"])." member\n";
			} // else
			echo "                <br />\n";
		} // if
	} // for

	echo "              </div>\n";
	echo "            </div>\n";

	echo "            <div class=\"panel panel-info\">\n";
	echo "              <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Create a new organization.\" class=\"panel-heading\">\n";
	echo "                <h3 class=\"panel-title\">Create New        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseCreate\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "              </div>\n";
	echo "              <div id=\"collapseCreate\" class=\"panel-body collapse in\">\n";
	echo "                <div class=\"col-md-8 col-center\">\n";
	echo "                  <form action=\"index.php?page=organizations\" method=\"post\">\n";
	echo "                    <input type=\"hidden\" name=\"page\" value=\"organizations\" />\n";
	echo "                    <div class=\"form-group form-group-sm\">\n";
	echo "                      <label class=\"control-label\" for=\"orgname\">Name:</label>\n";
	echo "                      <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Specify name of organization.\" name=\"orgname\" type=\"text\" class=\"form-control input-md\" id=\"orgname\" placeholder=\"Name of New Organization\" />\n";
	echo "                      <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Submit new organization.\" value=\"create\" name=\"action\" id=\"org_create\" type=\"submit\" class=\"btn btn-md btn-primary\">Create</button>\n";
	echo "                    </div>\n";
	echo "                  </form>\n";
	echo "                </div>\n";
	echo "              </div>\n";
	echo "            </div>\n";

	for ($z=0; $z < count($nationInfo["invites"]); $z++) {
		if ($nationInfo["invites"][$z] > 0) {
			$organizationInfo1 = getOrganizationInfo($getPage_connection2,$nationInfo["invites"][$z]);
			echo "            <div class=\"panel panel-info\">\n";
			echo "              <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Organization controls: invite.\" class=\"panel-heading\">\n";
			echo "                <h3 class=\"panel-title\">".$organizationInfo1["name"]."        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseOrg".$z."\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
			echo "              </div>\n";
			echo "              <div id=\"collapseOrg".$z."\" class=\"panel-body collapse in\">\n";
			echo "                <div class=\"col-md-8 col-center\">\n";
			echo "                  <form action=\"index.php?page=organizations\" method=\"post\">\n";
			echo "                    <input type=\"hidden\" name=\"page\" value=\"organizations\" />\n";
			echo "                    <input type=\"hidden\" name=\"org\" value=\"".$organizationInfo1["id"]."\" />\n";
			echo " 					  You have been invited to this organization.  Do you wish to join it?\n";
			echo "                    <div class=\"form-group form-group-sm\">\n";
			echo "                      <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Accept invitation to organization.\" value=\"join\" name=\"action\" id=\"join_org\" type=\"submit\" class=\"btn btn-md btn-success info_button\">Join Org</button>\n";
			echo "                      <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Decline invitation to organization.\" value=\"decline\" name=\"action\" id=\"decline_org\" type=\"submit\" class=\"btn btn-md btn-danger info_button\">Decline Org</button>\n";
			echo "                    </div>\n";
			echo "                  </form>\n";
			echo "                </div>\n";
			echo "              </div>\n";
			echo "            </div>\n";
		} // if
	} // for


	for ($z=0; $z < count($nationInfo["organizations"]); $z++) {
		if ($nationInfo["organizations"][$z] > 0) {
			$organizationInfo1 = getOrganizationInfo($getPage_connection2,$nationInfo["organizations"][$z]);
			echo "            <div class=\"panel panel-info\">\n";
			echo "              <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Organization controls.\" class=\"panel-heading\">\n";
			echo "                <h3 class=\"panel-title\">".$organizationInfo1["name"]."        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseOrg".$z."\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
			echo "              </div>\n";
			echo "              <div id=\"collapseOrg".$z."\" class=\"panel-body collapse in\">\n";
			echo "                <div class=\"col-md-8 col-center\">\n";
			echo "                  <form action=\"index.php?page=organizations\" method=\"post\">\n";
			echo "                    <input type=\"hidden\" name=\"page\" value=\"organizations\" />\n";
			echo "                    <input type=\"hidden\" name=\"org\" value=\"".$organizationInfo1["id"]."\" />\n";
			echo "                    Current managers:\n";
			echo "                    <br />\n";
			for ($b=0; $b < count($organizationInfo1["managers"]); $b++) {
				$nationInfoManager = getNationInfo($getPage_connection2,$organizationInfo1["managers"][$b]);
				if ($b > 0) {
					echo ", \n";
				} // if
				echo "                    ".$nationInfoManager["name"]."\n";
			} // for
			echo "                    <br />\n";
			echo "                    Current members:\n";
			echo "                    <br />\n";
			for ($b=0; $b < count($organizationInfo1["members"]); $b++) {
				$nationInfoMember = getNationInfo($getPage_connection2,$organizationInfo1["members"][$b]);
				if ($b > 0) {
					echo ", \n";
				} // if
				echo "                    ".$nationInfoMember["name"]."\n";
			} // for
			echo "                    <br />\n";
			echo "                    <div class=\"form-group form-group-sm\">\n";
			for ($a=0; $a < count($organizationInfo1["managers"]); $a++) {
				if ($organizationInfo1["managers"][$a] == $_SESSION["nation_id"]) {
					echo "                      <input name=\"orgnation\" type=\"text\" class=\"form-control input-md\" id=\"orgnation\" placeholder=\"Name of Target Nation\" />\n";
					echo "                      <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Invite nation to the organization.\" value=\"invite\" name=\"action\" id=\"org_invite\" type=\"submit\" class=\"btn btn-md btn-success\">Invite Nation</button>\n";
					echo "                      <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Kick nation from the organization.\" value=\"kick\" name=\"action\" id=\"org_kick\" type=\"submit\" class=\"btn btn-md btn-warning\">Kick Nation</button>\n";
					echo "                      <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Appoint nation to be a manager of the organization.\" value=\"appoint\" name=\"action\" id=\"org_appoint\" type=\"submit\" class=\"btn btn-md btn-success\">Appoint Manager</button>\n";
					echo "                      <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Dismiss nation from being a manager of the organization.\" value=\"dismiss\" name=\"action\" id=\"org_dismiss\" type=\"submit\" class=\"btn btn-md btn-warning\">Dismiss Manager</button>\n";
					break;
				} // if
			} // for
			echo "                      <br />\n";
			echo "                      <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Leave organization membership.\" value=\"leave\" name=\"action\" id=\"org_leave\" type=\"submit\" class=\"btn btn-md btn-danger info_button\">Leave Org</button>\n";
			echo "                    </div>\n";
			echo "                  </form>\n";
			echo "                </div>\n";
			echo "              </div>\n";
			echo "            </div>\n";
		} // if
	} // for
	echo "              </div>\n";
	echo "        </div>\n";
} // showOrganizationsInfo

/*-----------------------------------------------*/
/********************************
 Organizations Action Functions
 ********************************/
/*-----------------------------------------------*/

/********************************
 createOrganization
 validation and processing for creating new organization
 ********************************/
function createOrganization($getPage_connection2) {
	if (strlen($_SESSION["action"]) >= 1) {
		if ($_SESSION["action"] == "create") {
			if ($_SESSION["nation_id"] >= 1) {
				$nationInfoQ = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				if (strlen($_SESSION["name"]) >= 1) {
					$members = array(0=>$nationInfoQ["id"]);
					$managers = array(0=>$nationInfoQ["id"]);
					$pending = array(0=>0);
					addOrganizationInfo($getPage_connection2,$nationInfoQ["id"],$_SESSION["name"],$members,$managers,$pending,9999);
					$_SESSION["success_message"] = "Organization has has been created successfully!";
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: name is not valid.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: nation is not valid.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
	} // else
} // createOrganization

/********************************
 inviteNation
 validation and processing for inviting nation to organization
 ********************************/
function inviteNation($getPage_connection2) {
	if (strlen($_SESSION["action"]) >= 1) {
		if ($_SESSION["action"] == "invite") {
			if ($_SESSION["nation_id"] >= 1) {
				$nationInfoQ = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				if (strlen($_SESSION["nation"]) >= 1) {
					$nationInfoSelect = getNationInfoByName($getPage_connection2,$_SESSION["nation"]);
					$organizationInfoSelect = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
					if ($nationInfoSelect["id"] >= 1) {
						if ($_SESSION["org"] >= 1) {
							$validManager = false;
							for ($a=0; $a < count($organizationInfoSelect["managers"]); $a++) {
								if ($organizationInfoSelect["managers"][$a] == $_SESSION["nation_id"]) {
									$validManager = true;
									break;
								} // if
							} // for

							if ($validManager === true) {
								$nationInfoM = getNationInfo($getPage_connection2,$nationInfoSelect["id"]);
								$new_invites = $nationInfoM["invites"];
								$new_index = count($nationInfoM["invites"]) + 1;
								$new_invites[$new_index] = $_SESSION["org"];
								 
								setNationInfo($getPage_connection2,$nationInfoSelect["id"],$nationInfoSelect["name"],$nationInfoSelect["home"],$nationInfoSelect["formal"],$nationInfoSelect["flag"],$nationInfoSelect["production"],$nationInfoSelect["money"],$nationInfoSelect["debt"],$nationInfoSelect["happiness"],$nationInfoSelect["food"],$nationInfoSelect["authority"],$nationInfoSelect["authorityChanged"],$nationInfoSelect["economy"],$nationInfoSelect["economyChanged"],$nationInfoSelect["organizations"],$new_invites,$nationInfoSelect["goods"],$nationInfoSelect["resources"],$nationInfoSelect["population"],$nationInfoSelect["strike"]);

								$organizationInfoM = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
								$new_pending = $organizationInfoM["pending"];
								$new_index = count($organizationInfoM["pending"]) + 1;
								$new_pending[$new_index] = $nationInfoSelect["id"];

								setOrganizationInfo($getPage_connection2,$_SESSION["org"],$organizationInfoSelect["name"],$organizationInfoSelect["members"],$organizationInfoSelect["managers"],$new_pending,$organizationInfoSelect["ranking"]);

								$_SESSION["success_message"] = "Nation has been invited successfully!";
							} else {
								$_SESSION["warning_message"] = "Cannot complete action: current nation is not a valid manager.";
							} // else
						} else {
							$_SESSION["warning_message"] = "Cannot complete action: organization is not valid.";
						} // else
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: name is not valid.".$nationInfoSelect["id"];
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: name is not valid.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: nation is not valid.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
	} // else
} // inviteNation

/********************************
 kickNation
 validation and processing for kicking nation from organization
 ********************************/
function kickNation($getPage_connection2) {
	if (strlen($_SESSION["action"]) >= 1) {
		if ($_SESSION["action"] == "kick") {
			if ($_SESSION["nation_id"] >= 1) {
				$nationInfoQ = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				if (strlen($_SESSION["nation"]) >= 1) {
					$nationInfoSelect = getNationInfoByName($getPage_connection2,$_SESSION["nation"]);
					$organizationInfoSelect = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
					if ($nationInfoSelect["id"] >= 1) {
						if ($_SESSION["org"] >= 1) {
							$validManager = false;
							for ($a=0; $a < count($organizationInfoSelect["managers"]); $a++) {
								if ($organizationInfoSelect["managers"][$a] == $_SESSION["nation_id"]) {
									$validManager = true;
									break;
								} // if
							} // for

							if ($validManager === true) {
								$new_organizations = array(0=>0);
								$counter = 0;
								$nationInfoM = getNationInfo($getPage_connection2,$nationInfoSelect["id"]);
								for ($z = 0;$z < count($nationInfoM["organizations"]); $z++) {
									if ($nationInfoM["organizations"][$z] != $_SESSION["org"]) {
										$new_organizations[$counter] = $nationInfoM["organizations"][$z];
										$counter++;
									} // if
								} // for
								setNationInfo($getPage_connection2,$nationInfoSelect["id"],$nationInfoSelect["name"],$nationInfoSelect["home"],$nationInfoSelect["formal"],$nationInfoSelect["flag"],$nationInfoSelect["production"],$nationInfoSelect["money"],$nationInfoSelect["debt"],$nationInfoSelect["happiness"],$nationInfoSelect["food"],$nationInfoSelect["authority"],$nationInfoSelect["authorityChanged"],$nationInfoSelect["economy"],$nationInfoSelect["economyChanged"],$new_organizations,$nationInfoSelect["invites"],$nationInfoSelect["goods"],$nationInfoSelect["resources"],$nationInfoSelect["population"],$nationInfoSelect["strike"]);

								$new_members = array(0=>0);
								$counter = 0;
								$organizationInfoM = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
								for ($z = 0;$z < count($organizationInfoM["members"]); $z++) {
									if ($nationInfoSelect["id"] != $organizationInfoM["members"][$z]) {
										$new_members[$counter] = $organizationInfoM["members"][$z];
										$counter++;
									} // if
								} // for
								$new_managers = array(0=>0);
								$counter = 0;
								$organizationInfoM = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
								for ($z = 0;$z < count($organizationInfoM["managers"]); $z++) {
									if ($nationInfoSelect["id"] != $organizationInfoM["managers"][$z]) {
										$new_managers[$counter] = $organizationInfoM["managers"][$z];
										$counter++;
									} // if
								} // for
								setOrganizationInfo($getPage_connection2,$_SESSION["org"],$organizationInfoSelect["name"],$new_members,$new_managers,$organizationInfoSelect["pending"],$organizationInfoSelect["ranking"]);

								$_SESSION["success_message"] = "Nation has has been kicked successfully!";
							} else {
								$_SESSION["warning_message"] = "Cannot complete action: current nation is not a valid manager.";
							} // else
						} else {
							$_SESSION["warning_message"] = "Cannot complete action: organization is not valid.";
						} // else
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: name is not valid.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: name is not valid.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: nation is not valid.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
	} // else
} // kickNation

/********************************
 appointManager
 validation and processing for appoint nation as manager of organization
 ********************************/
function appointManager($getPage_connection2) {
	if (strlen($_SESSION["action"]) >= 1) {
		if ($_SESSION["action"] == "appoint") {
			if ($_SESSION["nation_id"] >= 1) {
				$nationInfoQ = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				if (strlen($_SESSION["nation"]) >= 1) {
					$nationInfoSelect = getNationInfoByName($getPage_connection2,$_SESSION["nation"]);
					$organizationInfoSelect = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
					if ($nationInfoSelect["id"] >= 1) {
						if ($_SESSION["org"] >= 1) {
							$validManager = false;
							for ($a=0; $a < count($organizationInfoSelect["managers"]); $a++) {
								if ($organizationInfoSelect["managers"][$a] == $_SESSION["nation_id"]) {
									$validManager = true;
									break;
								} // if
							} // for

							if ($validManager === true) {
								$nationInfoM = getNationInfo($getPage_connection2,$nationInfoSelect["id"]);
								$nationIsMember = false;
								for ($q=0; $q < count($nationInfoM["organizations"]); $q++) {
									if ($nationInfoM["organizations"][$q] == $_SESSION["org"]) {
										$nationIsMember = true;
										break;
									} // if
								} // for
								if ($nationIsMember === false) {
									$new_organizations = $nationInfoM["organizations"];
									$new_index = count($nationInfoM["organizations"]) + 1;
									$new_organizations[$new_index] = $_SESSION["org"];

									setNationInfo($getPage_connection2,$nationInfoSelect["id"],$nationInfoSelect["name"],$nationInfoSelect["home"],$nationInfoSelect["formal"],$nationInfoSelect["flag"],$nationInfoSelect["production"],$nationInfoSelect["money"],$nationInfoSelect["debt"],$nationInfoSelect["happiness"],$nationInfoSelect["food"],$nationInfoSelect["authority"],$nationInfoSelect["authorityChanged"],$nationInfoSelect["economy"],$nationInfoSelect["economyChanged"],$new_organizations,$nationInfoSelect["invites"],$nationInfoSelect["goods"],$nationInfoSelect["resources"],$nationInfoSelect["population"],$nationInfoSelect["strike"]);

									$organizationInfoM = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
									$new_members = $organizationInfoM["members"];
									$new_index = count($organizationInfoM["members"]) + 1;
									$new_members[$new_index] = $nationInfoSelect["id"];

									setOrganizationInfo($getPage_connection2,$_SESSION["org"],$organizationInfoSelect["name"],$new_members,$organizationInfoSelect["managers"],$organizationInfoSelect["pending"],$organizationInfoSelect["ranking"]);
								} // if

								$organizationInfoM = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
								$new_managers = $organizationInfoM["managers"];
								$new_index = count($organizationInfoM["managers"]) + 1;
								$new_managers[$new_index] = $nationInfoSelect["id"];

								setOrganizationInfo($getPage_connection2,$_SESSION["org"],$organizationInfoSelect["name"],$organizationInfoSelect["members"],$new_managers,$organizationInfoSelect["pending"],$organizationInfoSelect["ranking"]);

								$_SESSION["success_message"] = "Nation has has been appointed as manager successfully!";
							} else {
								$_SESSION["warning_message"] = "Cannot complete action: current nation is not a valid manager.";
							} // else
						} else {
							$_SESSION["warning_message"] = "Cannot complete action: organization is not valid.";
						} // else
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: name is not valid.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: name is not valid.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: nation is not valid.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
	} // else
} // appointManager

/********************************
 dismissManager
 validation and processing for dismissing nation as manager of organization
 ********************************/
function dismissManager($getPage_connection2) {
	if (strlen($_SESSION["action"]) >= 1) {
		if ($_SESSION["action"] == "dismiss") {
			if ($_SESSION["nation_id"] >= 1) {
				$nationInfoQ = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				if (strlen($_SESSION["nation"]) >= 1) {
					$nationInfoSelect = getNationInfoByName($getPage_connection2,$_SESSION["nation"]);
					$organizationInfoSelect = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
					if ($nationInfoSelect["id"] >= 1) {
						if ($_SESSION["org"] >= 1) {
							$validManager = false;
							for ($a=0; $a < count($organizationInfoSelect["managers"]); $a++) {
								if ($organizationInfoSelect["managers"][$a] == $_SESSION["nation_id"]) {
									$validManager = true;
									break;
								} // if
							} // for

							if ($validManager === true) {
								$new_organizations = array(0=>0);
								$counter = 0;
								$nationInfoM = getNationInfo($getPage_connection2,$nationInfoSelect["id"]);
								$nationIsMember = false;
								for ($q=0; $q < count($nationInfoM["organizations"]); $q++) {
									if ($nationInfoM["organizations"][$q] == $_SESSION["org"]) {
										$nationIsMember = true;
										break;
									} // if
								} // for
								if ($nationIsMember === true) {
									$new_managers = array(0=>0);
									$counter = 0;
									$organizationInfoM = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
									for ($z = 0;$z < count($organizationInfoM["managers"]); $z++) {
										if ($nationInfoSelect["id"] != $organizationInfoM["managers"][$z]) {
											$new_managers[$counter] = $organizationInfoM["managers"][$z];
											$counter++;
										} // if
									} // for
									setOrganizationInfo($getPage_connection2,$_SESSION["org"],$organizationInfoSelect["name"],$organizationInfoSelect["members"],$new_managers,$organizationInfoSelect["pending"],$organizationInfoSelect["ranking"]);

									$_SESSION["success_message"] = "Nation has has been dismissed as manager successfully!";
								} // if
							} else {
								$_SESSION["warning_message"] = "Cannot complete action: current nation is not a valid manager.";
							} // else
						} else {
							$_SESSION["warning_message"] = "Cannot complete action: organization is not valid.";
						} // else
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: name is not valid.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: name is not valid.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: nation is not valid.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
	} // else
} // dismissManager

/********************************
 leaveOrganization
 validation and processing for leaving organization
 ********************************/
function leaveOrganization($getPage_connection2) {
	if (strlen($_SESSION["action"]) >= 1) {
		if ($_SESSION["action"] == "leave") {
			if ($_SESSION["nation_id"] >= 1) {
				$nationInfoSelect = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				$organizationInfoSelect = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
				if ($nationInfoSelect["id"] >= 1) {
					if ($_SESSION["org"] >= 1) {
						$new_organizations = array(0=>0);
						$counter = 0;
						$nationInfoM = getNationInfo($getPage_connection2,$nationInfoSelect["id"]);
						for ($z = 0;$z < count($nationInfoM["organizations"]); $z++) {
							if ($nationInfoM["organizations"][$z] != $_SESSION["org"]) {
								$new_organizations[$counter] = $nationInfoM["organizations"][$z];
								$counter++;
							} // if
						} // for
						setNationInfo($getPage_connection2,$nationInfoSelect["id"],$nationInfoSelect["name"],$nationInfoSelect["home"],$nationInfoSelect["formal"],$nationInfoSelect["flag"],$nationInfoSelect["production"],$nationInfoSelect["money"],$nationInfoSelect["debt"],$nationInfoSelect["happiness"],$nationInfoSelect["food"],$nationInfoSelect["authority"],$nationInfoSelect["authorityChanged"],$nationInfoSelect["economy"],$nationInfoSelect["economyChanged"],$new_organizations,$nationInfoSelect["invites"],$nationInfoSelect["goods"],$nationInfoSelect["resources"],$nationInfoSelect["population"],$nationInfoSelect["strike"]);

						$new_members = array(0=>0);
						$counter = 0;
						$organizationInfoM = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
						for ($z = 0;$z < count($organizationInfoM["members"]); $z++) {
							if ($nationInfoSelect["id"] != $organizationInfoM["members"][$z]) {
								$new_members[$counter] = $organizationInfoM["members"][$z];
								$counter++;
							} // if
						} // for
						$new_managers = array(0=>0);
						$counter = 0;
						$organizationInfoM = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
						for ($z = 0;$z < count($organizationInfoM["managers"]); $z++) {
							if ($nationInfoSelect["id"] != $organizationInfoM["managers"][$z]) {
								$new_managers[$counter] = $organizationInfoM["managers"][$z];
								$counter++;
							} // if
						} // for
						setOrganizationInfo($getPage_connection2,$_SESSION["org"],$organizationInfoSelect["name"],$new_members,$new_managers,$organizationInfoSelect["pending"],$organizationInfoSelect["ranking"]);

						$_SESSION["success_message"] = "Your nation has left the organization successfully!";
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: organization is not valid.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: name is not valid.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: nation is not valid.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
	} // else
} // leaveOrganization

/********************************
 joinOrganization
 validation and processing for nation joining organization
 ********************************/
function joinOrganization($getPage_connection2) {
	if (strlen($_SESSION["action"]) >= 1) {
		if ($_SESSION["action"] == "join") {
			if ($_SESSION["nation_id"] >= 1) {
				$nationInfoSelect = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				if ($_SESSION["org"] >= 1) {
					$organizationInfoSelect = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
					$nationInfoM = getNationInfo($getPage_connection2,$nationInfoSelect["id"]);
					$new_organizations = $nationInfoM["organizations"];
					$new_index = count($nationInfoM["organizations"]) + 1;
					$new_organizations[$new_index] = $_SESSION["org"];

					$new_invites = array(0=>0);
					$counter = 0;
					$nationInfoM = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
					for ($z = 0;$z < count($nationInfoM["invites"]); $z++) {
						if ($_SESSION["org"] != $nationInfoM["invites"][$z]) {
							$new_invites[$counter] = $nationInfoM["invites"][$z];
							$counter++;
						} // if
					} // for

					setNationInfo($getPage_connection2,$nationInfoSelect["id"],$nationInfoSelect["name"],$nationInfoSelect["home"],$nationInfoSelect["formal"],$nationInfoSelect["flag"],$nationInfoSelect["production"],$nationInfoSelect["money"],$nationInfoSelect["debt"],$nationInfoSelect["happiness"],$nationInfoSelect["food"],$nationInfoSelect["authority"],$nationInfoSelect["authorityChanged"],$nationInfoSelect["economy"],$nationInfoSelect["economyChanged"],$new_organizations, $new_invites,$nationInfoSelect["goods"],$nationInfoSelect["resources"],$nationInfoSelect["population"],$nationInfoSelect["strike"]);

					$organizationInfoM = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
					$new_members = $organizationInfoM["members"];
					$new_index = count($organizationInfoM["members"]) + 1;
					$new_members[$new_index] = $nationInfoSelect["id"];

					$new_pending = array(0=>0);
					$counter = 0;
					$organizationInfoM = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
					for ($z = 0;$z < count($organizationInfoM["pending"]); $z++) {
						if ($_SESSION["nation_id"] != $organizationInfoM["pending"][$z]) {
							$new_pending[$counter] = $organizationInfoM["pending"][$z];
							$counter++;
						} // if
					} // for

					setOrganizationInfo($getPage_connection2,$_SESSION["org"],$organizationInfoSelect["name"],$new_members,$organizationInfoSelect["managers"],$new_pending,$organizationInfoSelect["ranking"]);

					$_SESSION["success_message"] = "Nation has joined organization successfully!";
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: organization is not valid.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: nation is not valid.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
	} // else
} // joinOrganization

/********************************
 declineOrganization
 validation and processing for nation decling organization
 ********************************/
function declineOrganization($getPage_connection2) {
	if (strlen($_SESSION["action"]) >= 1) {
		if ($_SESSION["action"] == "decline") {
			if ($_SESSION["nation_id"] >= 1) {
				$nationInfoSelect = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				if ($_SESSION["org"] >= 1) {
					$organizationInfoSelect = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);

					$new_invites = array(0=>0);
					$counter = 0;
					$nationInfoM = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
					for ($z = 0;$z < count($nationInfoM["invites"]); $z++) {
						if ($_SESSION["org"] != $nationInfoM["invites"][$z]) {
							$new_invites[$counter] = $nationInfoM["invites"][$z];
							$counter++;
						} // if
					} // for
					 
					setNationInfo($getPage_connection2,$nationInfoSelect["id"],$nationInfoSelect["name"],$nationInfoSelect["home"],$nationInfoSelect["formal"],$nationInfoSelect["flag"],$nationInfoSelect["production"],$nationInfoSelect["money"],$nationInfoSelect["debt"],$nationInfoSelect["happiness"],$nationInfoSelect["food"],$nationInfoSelect["authority"],$nationInfoSelect["authorityChanged"],$nationInfoSelect["economy"],$nationInfoSelect["economyChanged"],$nationInfoSelect["organizations"], $new_invites,$nationInfoSelect["goods"],$nationInfoSelect["resources"],$nationInfoSelect["population"],$nationInfoSelect["strike"]);

					$new_pending = array(0=>0);
					$counter = 0;
					$organizationInfoM = getOrganizationInfo($getPage_connection2,$_SESSION["org"]);
					for ($z = 0;$z < count($organizationInfoM["pending"]); $z++) {
						if ($_SESSION["nation_id"] != $organizationInfoM["pending"][$z]) {
							$new_pending[$counter] = $organizationInfoM["pending"][$z];
							$counter++;
						} // if
					} // for

					setOrganizationInfo($getPage_connection2,$_SESSION["org"],$organizationInfoSelect["name"],$organizationInfoSelect["members"],$organizationInfoSelect["managers"],$new_pending,$organizationInfoSelect["ranking"]);

					$_SESSION["success_message"] = "Nation has declined organization successfully!";
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: organization is not valid.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: nation is not valid.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
	} // else
} // declineOrganization
?>