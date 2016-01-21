<?php
/****************************************************************************
 * Name:        functions_policies.php
 * Author:      Ben Barnes
 * Date:        2016-01-20
 * Purpose:     Policies functions page
 *****************************************************************************/

/*-----------------------------------------------*/
/********************************
 Policies Page Functions
 ********************************/
/*-----------------------------------------------*/

/********************************
 getGlobals_policies
 get and set global variables for policies page
 ********************************/
function getGlobals_policies($getPage_connection2) {
	// get session: admin
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
	
		// post: formal name input for changing formal name
		if (isset($_POST["formalname"])) {
			$_SESSION["formal_name"] = cleanString($_POST["formalname"],false);
		} else {
			$_SESSION["formal_name"] = 0;
		} // else
	
		// post: formal name input for changing formal name
		if (isset($_POST["prod-percent"])) {
			$_SESSION["prod_percent"] = cleanString($_POST["prod-percent"],true);
		} else {
			$_SESSION["prod_percent"] = 0;
		} // else
			
		// post: array of production targets
		if (isset($_POST["prod"])) {
			$_SESSION["prod"] = $_POST["prod"];
		} else {
			$_SESSION["prod"] = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0);
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
} // getGlobals_policies

/********************************
 performAction_policies
 calls action for policies if requested and valid
 ********************************/
function performAction_policies($getPage_connection2) {
	if ($_SESSION["action"] == "aplus") {
		addAuthority($getPage_connection2);
	} else if ($_SESSION["action"] == "aminus") {
		removeAuthority($getPage_connection2);
	} else if ($_SESSION["action"] == "eplus") {
		addEconomy($getPage_connection2);
	} else if ($_SESSION["action"] == "eminus") {
		removeEconomy($getPage_connection2);
	} else if ($_SESSION["action"] == "formal") {
		changeFormalName($getPage_connection2);
	} else if ($_SESSION["action"] == "flag") {
		changeFlag($getPage_connection2);
	} else if ($_SESSION["action"] == "prod") {
		setProduction($getPage_connection2);
	} // else if
} // performAction_policies

/********************************
 showPoliciesInfo
 visualize policies information and input
 ********************************/
function showPoliciesInfo($getPage_connection2) {
	$nationInfo = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
	$continentInfo = getContinentInfo($getPage_connection2, $nationInfo["home"]);
	$productionInfo = getProductionInfo($getPage_connection2,$_SESSION["nation_id"]);
	$authorityReport = getAuthorityReport($nationInfo["authority"]);
	$economyReport = getEconomyReport($nationInfo["economy"]);
	$rankingInfo = getRankingInfo($getPage_connection2,$_SESSION["nation_id"]);

	echo "        <div class=\"spacing-from-menu well well-lg standard-text\">\n";

	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View basic govermental policies.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Government        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseGovernment\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseGovernment\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	echo "                <h3>".$nationInfo["formal"]."</h3>\n";
	echo "                <br />\n";
	if (strlen($nationInfo["flag"]) >= 1) {
		echo "                <img class=\"info_flag\" src=\"".$nationInfo["flag"]."\" alt=\"Flag of ".$nationInfo["name"]."\" />\n";
	} else {
		echo "                <img class=\"info_flag\" src=\"images/blank.png\" alt=\"Flag of ".$nationInfo["name"]."\" />\n";
	} // else
	echo "                <br />\n";
	echo "                <br />\n";
	echo "                <br />\n";
	echo "                Home Continent: ".$continentInfo["name"]." (".$nationInfo["home"].") \n";
	echo "                <br />\n";
	echo "                Authority: ".$nationInfo["authority"]."\n";
	echo "                <br />\n";
	echo "                ".$authorityReport."\n";
	echo "                <br />\n";
	echo "                Economy: ".$nationInfo["economy"]."\n";
	echo "                <br />\n";
	echo "                ".$economyReport."\n";
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";

	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Change basic govermental policies.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Change Policies        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseChange\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseChange\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	echo "                <label class=\"control-label\">Reform Government:</label> \n";
	echo "                  <form action=\"index.php?page=policies\" method=\"post\">\n";
	echo "                    <input type=\"hidden\" name=\"page\" value=\"policies\" />\n";
	if ($nationInfo["authorityChanged"] == 0) {
		echo "                    <div class=\"form-group form-group-sm\">\n";
		if ($nationInfo["authority"] > 0) {
			echo "                      <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Decrease authority.  This will mean less state control of society.\" value=\"aminus\" name=\"action\" id=\"authority_decrease\" type=\"submit\" class=\"btn btn-md btn-primary\"><span class=\"glyphicon glyphicon-minus\"></span></button>\n";
		} // if
		echo "                Authority\n";
		if ($nationInfo["authority"] < 10) {
			echo "                      <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Increase authority.  This will mean more state control of society.\" value=\"aplus\" name=\"action\" id=\"authority_increase\" type=\"submit\" class=\"btn btn-md btn-primary\"><span class=\"glyphicon glyphicon-plus\"></span></button>\n";
		} // if
		echo "                    </div>\n";
	} else {
		echo "                    Authority has already been reformed this turn. <br />\n";
	} // else
	if ($nationInfo["economyChanged"] == 0) {
		echo "                    <div class=\"form-group form-group-sm\">\n";
		if ($nationInfo["economy"] > 0) {
			echo "                      <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Decrease economic control.  This will mean less state intervention in the economy.\" value=\"eminus\" name=\"action\" id=\"economy_decrease\" type=\"submit\" class=\"btn btn-md btn-primary\"><span class=\"glyphicon glyphicon-minus\"></span></button>\n";
		} // if
		echo "                        Economy\n";
		if ($nationInfo["economy"] < 10) {
			echo "                      <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Increase economic control.  This will mean more state intervention in the economy.\" value=\"eplus\" name=\"action\" id=\"economy_increase\" type=\"submit\" class=\"btn btn-md btn-primary\"><span class=\"glyphicon glyphicon-plus\"></span></button>\n";
		} // if
		echo "                    </div>\n";
	} else {
		echo "                    Economy has already been reformed this turn. <br />\n";
	} // else

	echo "                    <div class=\"form-group form-group-sm\">\n";
	echo "                      <label class=\"control-label\" for=\"formal\">Formal Name:</label>\n";
	echo "                      <input name=\"formalname\" type=\"text\" class=\"form-control input-md\" id=\"formal\" placeholder=\"New Formal Name of Nation\" />\n";
	echo "                      <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Change long formal name of nation.  This will not affect the regular short name of your nation!\" value=\"formal\" name=\"action\" id=\"formal_change\" type=\"submit\" class=\"btn btn-md btn-primary\">Change Formal Name</button>\n";
	echo "                    </div>\n";

	echo "                  </form>\n";

	echo "                  <form action=\"index.php?page=policies&amp;action=flag\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "                    <div class=\"form-group form-group-sm\">\n";
	echo "                      <label class=\"control-label\" for=\"fileToUpload\">Upload New Flag:</label>\n";
	echo "                      <br />\n";
	echo "                      <span data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Select an image file from your computer to be your new flag.\" class=\"btn btn-med btn-default btn-file\">\n";
	echo "                        Browse <input type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\" />\n";
	echo "                      </span>\n";
	echo "                      <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Upload your new flag.\" value=\"flag\" name=\"action\" type=\"submit\" class=\"btn btn-md btn-primary\">Change Flag</button>\n";
	echo "                    </div>\n";
	echo "                  </form>\n";

	echo "                </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";

	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Change production expenditure and prioritization.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Production        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseProduction\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseProduction\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	echo "                <form action=\"index.php?page=policies\" method=\"post\">\n";
	echo "                  <div class=\"form-group form-group-sm\">\n";
	echo "                    <input type=\"hidden\" name=\"page\" value=\"policies\" />\n";
	echo "                    <label class=\"control-label\" for=\"slider1\">Production Spending Percentage:</label>\n";
	echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Change the amount of production you spend for this turn.  Remember that production does not transfer over to the next turn!\" name=\"prod-percent\" id=\"slider1\" type=\"range\" min=\"0\" max=\"100\" step=\"5\" value=\"".$productionInfo["spending"]."\" class=\"\" />\n";
	echo "                    <br />\n";
	
	$productionInfo1 = getProductionInfo($getPage_connection2, $_SESSION["nation_id"]);
	
	if ($productionInfo1["id"] >= 1) {
		for ($k=0; $k < count($productionInfo1["goods"]) + 1; $k++ ) {
			$new_index = $k + 1;
			
			$goodsInfo2 = getGoodsInfo($getPage_connection2, $new_index);
			
			if ($goodsInfo2["id"] >= 1) {
				echo "                    <label class=\"control-label\" for=\"slider_prod-".$goodsInfo2["name"]."\">Emphasize ".$goodsInfo2["name"].":</label>\n";
				echo "                    ".$goodsInfo2["productionRequired"]." Production, ".$goodsInfo2["foodRequired"]." Food";
				for ($t=0; $t < count($goodsInfo2["resourceTypesRequired"]); $t++) {
					if ($goodsInfo2["resourceTypesRequired"][$t] >= 1) {
						echo ", ";
						$resourceTypeInfo1 = getResourceTypeInfo($getPage_connection2,$goodsInfo2["resourceTypesRequired"][$t]);
						echo $goodsInfo2["resourceQuantitiesRequired"][$t]." ".$resourceTypeInfo1["name"];
					} // if
				} // for
				echo "\n";
				$productionType = $goodsInfo2["id"];			
				if (strlen($productionType) >= 1) {
					echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Change prioritization of producing this good.\" name=\"prod[".$k."]\" id=\"slider_prod-".$productionType."\" type=\"range\" min=\"0\" max=\"3\" step=\"1\" value=\"".$productionInfo1["goods"][$k]."\" />\n";
				} else {
					echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Change prioritization of producing this good.\" name=\"prod[".$k."]\" id=\"slider_prod-".$productionType."\" type=\"range\" min=\"0\" max=\"3\" step=\"1\" value=\"0\" />\n";
				} // else	
			} // if
		} // for
	} // if

	echo "                    <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Submit changes to production expenditure and prioritization.\" value=\"prod\" name=\"action\" id=\"prod_change\" type=\"submit\" class=\"btn btn-md btn-primary\">Set Production</button>\n";
	echo "                  </div>\n";
	echo "                </form>\n";
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";

	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View general information about nation.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">General        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseGeneral\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseGeneral\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	echo "                Production: ".$nationInfo["production"].", #".$rankingInfo["production"]." in world\n";
	echo "                <br />\n";
	echo "                Money: ".$nationInfo["money"].", #".$rankingInfo["money"]." in world\n";
	echo "                <br />\n";
	echo "                Debt: ".$nationInfo["debt"]."\n";
	echo "                <br />\n";
	echo "                Happiness: ".$nationInfo["happiness"].", #".$rankingInfo["happiness"]." in world\n";
	echo "                <br />\n";
	echo "                Food: ".$nationInfo["food"].", #".$rankingInfo["food"]." in world\n";
	echo "                <br />\n";
	echo "                Population: ".$nationInfo["population"].", #".$rankingInfo["population"]." in world\n";
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";

	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View goods inventory statistics of nation.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Goods        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseGoods\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseGoods\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	for ($zz=0; $zz < count($nationInfo["goods"]); $zz++) {
		$goodsInfoK = getGoodsInfo($getPage_connection2,$zz+1);
		echo "                ".$goodsInfoK["name"].": ".$nationInfo["goods"][$zz]."\n";
		echo "                <br />\n";
	} // for
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";
	
	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View resources inventory statistics of nation.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Resources        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseResources\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseResources\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	for ($zz=0; $zz < count($nationInfo["resources"]); $zz++) {
		$resourceTypeInfoK = getResourceTypeInfo($getPage_connection2,$zz+1);
		echo "                ".$resourceTypeInfoK["name"].": ".$nationInfo["resources"][$zz]."\n";
		echo "                <br />\n";
	} // for
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";

	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View organizations that nation is currently a member of.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Organizations        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseOrganizations\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseOrganizations\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	echo "                ";
	$belongstoOrgs = false;
	for ($z=0; $z < count($nationInfo["organizations"]); $z++) {
		if ($z >= 1 && $nationInfo["organizations"][0] > 0) {
			echo ", ";
		} // if
		if ($nationInfo["organizations"][$z] >= 1) {
			$organizationInfo1 = getOrganizationInfo($getPage_connection2,$nationInfo["organizations"][$z]);
			echo "<a href=\"index.php?page=info&amp;section=orgs&amp;info_id=".$organizationInfo1["id"]."\">".$organizationInfo1["name"]."</a>";
			if (strlen($organizationInfo1["name"]) >= 1) {
				$belongsToOrgs = true;
			} // if
		}  // if
	} // for
	echo "                \n";
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";
	echo "        </div>\n";
} // showPoliciesInfo

/********************************
 getAuthorityReport
 sums up the current state of authority in governmental affairs
 ********************************/
function getAuthorityReport($authority) {
	$report = "";
	if ($authority == 0) {
		$report = "This nation favours anarchy.";
	} else if ($authority == 1) {
		$report = "This nation favours drastically less governmental authority.";
	} else if ($authority == 2) {
		$report = "This nation favours a lot less governmental authority.";
	} else if ($authority == 3) {
		$report = "This nation favours less governmental authority.";
	} else if ($authority == 4) {
		$report = "This nation favours somewhat less governmental authority.";
	} else if ($authority == 5) {
		$report = "This nation favours some governmental authority.";
	} else if ($authority == 6) {
		$report = "This nation favours somewhat more governmental authority.";
	} else if ($authority == 7) {
		$report = "This nation favours more governmental authority.";
	} else if ($authority == 8) {
		$report = "This nation favours a lot more governmental authority.";
	} else if ($authority == 9) {
		$report = "This nation favours drastically more governmental authority.";
	} else if ($authority == 10) {
		$report = "This nation favours complete governmental authority.";
	} // else if
	return $report;
} // getAuthorityReport

/********************************
 getEconomyReport
 sums up the current state of economy in governmental affairs
 ********************************/
function getEconomyReport($economy) {
	$report = "";
	if ($economy == 0) {
		$report = "This nation favours complete laissez-faire.";
	} else if ($economy == 1) {
		$report = "This nation favours drastically less governmental interference in the economy.";
	} else if ($economy == 2) {
		$report = "This nation favours a lot less governmental interference in the economy.";
	} else if ($economy == 3) {
		$report = "This nation favours less governmental interference in the economy.";
	} else if ($economy == 4) {
		$report = "This nation favours somewhat less governmental interference in the economy.";
	} else if ($economy == 5) {
		$report = "This nation favours some governmental interference in the economy.";
	} else if ($economy == 6) {
		$report = "This nation favours somewhat more governmental interference in the economy.";
	} else if ($economy == 7) {
		$report = "This nation favours more governmental interference in the economy.";
	} else if ($economy == 8) {
		$report = "This nation favours a lot more governmental interference in the economy.";
	} else if ($economy == 9) {
		$report = "This nation favours drastically more governmental interference in the economy.";
	} else if ($economy == 10) {
		$report = "This nation favours complete state-control of the economy.";
	} // else if
	return $report;
} // getEconomyReport

/*-----------------------------------------------*/
/********************************
 Policies Action Functions
 ********************************/
/*-----------------------------------------------*/

/********************************
 addAuthority
 validation and processing for adding to authority
 ********************************/
function addAuthority($getPage_connection2) {
	if (strlen($_SESSION["action"]) >= 1) {
		if ($_SESSION["action"] == "aplus") {
			if ($_SESSION["nation_id"] >= 1) {
				$nationInfoQ = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				if ($nationInfoQ["authorityChanged"] != 1) {
					if ($nationInfoQ["authority"] < 10) {
						$new_authority = $nationInfoQ["authority"] + 1;
						setNationInfo($getPage_connection2,$nationInfoQ["id"],$nationInfoQ["name"],$nationInfoQ["home"],$nationInfoQ["formal"],$nationInfoQ["flag"],$nationInfoQ["production"],$nationInfoQ["money"],$nationInfoQ["debt"],$nationInfoQ["happiness"],$nationInfoQ["food"],$new_authority,1,$nationInfoQ["economy"],$nationInfoQ["economyChanged"],$nationInfoQ["organizations"],$nationInfoQ["invites"],$nationInfoQ["goods"],$nationInfoQ["resources"],$nationInfoQ["population"],$nationInfoQ["strike"]);
						$_SESSION["success_message"] = "Nation's authority has been reformed successfully!";
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: authority cannot be increased any further.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: economy has already been reformed this turn.";
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
} // addAuthority

/********************************
 removeAuthority
 validation and processing for removing from authority
 ********************************/
function removeAuthority($getPage_connection2) {
	if (strlen($_SESSION["action"]) >= 1) {
		if ($_SESSION["action"] == "aminus") {
			if ($_SESSION["nation_id"] >= 1) {
				$nationInfoQ = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				if ($nationInfoQ["authorityChanged"] != 1) {
					if ($nationInfoQ["authority"] > 0) {
						$new_authority = $nationInfoQ["authority"] - 1;
						setNationInfo($getPage_connection2,$nationInfoQ["id"],$nationInfoQ["name"],$nationInfoQ["home"],$nationInfoQ["formal"],$nationInfoQ["flag"],$nationInfoQ["production"],$nationInfoQ["money"],$nationInfoQ["debt"],$nationInfoQ["happiness"],$nationInfoQ["food"],$new_authority,1,$nationInfoQ["economy"],$nationInfoQ["economyChanged"],$nationInfoQ["organizations"],$nationInfoQ["invites"],$nationInfoQ["goods"],$nationInfoQ["resources"],$nationInfoQ["population"],$nationInfoQ["strike"]);
						$_SESSION["success_message"] = "Nation's authority has been reformed successfully!";
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: authority cannot be decreased any further.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: economy has already been reformed this turn.";
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
} // removeAuthority

/********************************
 addEconomy
 validation and processing for adding to economy
 ********************************/
function addEconomy($getPage_connection2) {
	if (strlen($_SESSION["action"]) >= 1) {
		if ($_SESSION["action"] == "eplus") {
			if ($_SESSION["nation_id"] >= 1) {
				$nationInfoQ = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				if ($nationInfoQ["economyChanged"] != 1) {
					if ($nationInfoQ["authority"] < 10) {
						$new_economy = $nationInfoQ["economy"] + 1;
						setNationInfo($getPage_connection2,$nationInfoQ["id"],$nationInfoQ["name"],$nationInfoQ["home"],$nationInfoQ["formal"],$nationInfoQ["flag"],$nationInfoQ["production"],$nationInfoQ["money"],$nationInfoQ["debt"],$nationInfoQ["happiness"],$nationInfoQ["food"],$nationInfoQ["authority"],$nationInfoQ["authorityChanged"],$new_economy,1,$nationInfoQ["organizations"],$nationInfoQ["invites"],$nationInfoQ["goods"],$nationInfoQ["resources"],$nationInfoQ["population"],$nationInfoQ["strike"]);
						$_SESSION["success_message"] = "Nation's economy has been reformed successfully!";
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: economy cannot be increased any further.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: economy has already been reformed this turn.";
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
} // addEconomy

/********************************
 removeEconomy
 validation and processing for removing from economy
 ********************************/
function removeEconomy($getPage_connection2) {
	if (strlen($_SESSION["action"]) >= 1) {
		if ($_SESSION["action"] == "eminus") {
			if ($_SESSION["nation_id"] >= 1) {
				$nationInfoQ = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				if ($nationInfoQ["economyChanged"] != 1) {
					if ($nationInfoQ["authority"] > 0) {
						$new_economy = $nationInfoQ["economy"] - 1;
						setNationInfo($getPage_connection2,$nationInfoQ["id"],$nationInfoQ["name"],$nationInfoQ["home"],$nationInfoQ["formal"],$nationInfoQ["flag"],$nationInfoQ["production"],$nationInfoQ["money"],$nationInfoQ["debt"],$nationInfoQ["happiness"],$nationInfoQ["food"],$nationInfoQ["authority"],$nationInfoQ["authorityChanged"],$new_economy,1,$nationInfoQ["organizations"],$nationInfoQ["invites"],$nationInfoQ["goods"],$nationInfoQ["resources"],$nationInfoQ["population"],$nationInfoQ["strike"]);
						$_SESSION["success_message"] = "Nation's economy has been reformed successfully!";
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: economy cannot be decreased any further.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: economy has already been reformed this turn.";
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
} // removeEconomy

/********************************
 changeFormalName
 validation and processing for changing nation's formal name
 ********************************/
function changeFormalName($getPage_connection2) {
	if (strlen($_SESSION["action"]) >= 1) {
		if ($_SESSION["action"] == "formal") {
			if ($_SESSION["nation_id"] >= 1) {
				if (strlen($_SESSION["formal_name"]) >= 1) {
					$nationInfoQ = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
					$new_formal = $_SESSION["formal_name"];
					setNationInfo($getPage_connection2,$nationInfoQ["id"],$nationInfoQ["name"],$nationInfoQ["home"],$new_formal,$nationInfoQ["flag"],$nationInfoQ["production"],$nationInfoQ["money"],$nationInfoQ["debt"],$nationInfoQ["happiness"],$nationInfoQ["food"],$nationInfoQ["authority"],$nationInfoQ["authorityChanged"],$nationInfoQ["economy"],$nationInfoQ["economyChanged"],$nationInfoQ["organizations"],$nationInfoQ["invites"],$nationInfoQ["goods"],$nationInfoQ["resources"],$nationInfoQ["population"],$nationInfoQ["strike"]);
					$_SESSION["success_message"] = "Nation's formal name has has been changed successfully!";
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: formal name is not valid.";
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
} // changeFormalName

/********************************
 changeFlag
 validation and processing for uploading flag image file and changing image path
 ********************************/
function changeFlag($getPage_connection2) {
	if (strlen($_SESSION["action"]) >= 1) {
		if ($_SESSION["action"] == "flag") {
			if ($_SESSION["nation_id"] >= 1) {
				$flagArray = uploadFile($getPage_connection2); // tries to upload new file and delete old one
				if (strlen($flagArray["path"]) >= 2 && strlen($flagArray["error"]) <= 1) {
					$nationInfoP = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
					$new_flag = $flagArray["path"];
					setNationInfo($getPage_connection2,$_SESSION["nation_id"],$nationInfoP["name"],$nationInfoP["home"],$nationInfoP["formal"],$new_flag,$nationInfoP["production"],$nationInfoP["money"],$nationInfoP["debt"],$nationInfoP["happiness"],$nationInfoP["food"],$nationInfoP["authority"],$nationInfoP["authorityChanged"],$nationInfoP["economy"],$nationInfoP["economyChanged"],$nationInfoP["organizations"],$nationInfoP["invites"],$nationInfoP["goods"],$nationInfoP["resources"],$nationInfoP["population"],$nationInfoP["strike"]);
					$_SESSION["success_message"] = "Cannot complete action: Upload was successful, flag is changed!";
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: upload failed.  ".$flagArray["error"]."  Double-check your image to make sure it is valid!";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
	} // else
} // changeFlag

/********************************
 uploadFile
 validation and processing for image uploading

 SPECIAL THANKS TO w3schools.com for this upload code!
 ********************************/
function uploadFile($getPage_connection2) {
	$returnArray = array("path"=>"","error"=>"");
	$target_dir = "images/nations/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

	// Check if image file is a actual image or fake image
	if (isset($_POST["submit"])) {
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if ($check !== false) {
			//echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			//echo "File is not an image.";
			$uploadOk = 0;
			$returnArray["error"] = "File is not a valid image type.".$_FILES["fileToUpload"]["name"];
		} // else
	} // if

	// Check if file already exists
	if (file_exists($target_file)) {
		//echo "Sorry, file already exists.";
		$uploadOk = 0;
		$returnArray["error"] = "File already exists, try renaming your file.";
	} // if

	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 1500000) {
		//echo "Sorry, your file is too large.";
		$uploadOk = 0;
		$returnArray["error"] = "File is too large (must be under 1.5 MB).";
	} // if

	// Allow certain file formats
	if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
		//echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
		$returnArray["error"] = "File must be only JPG, JPEG, PNG or GIF.";
	} // if

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		//echo "Sorry, your file was not uploaded.";
		$returnArray["path"] = "";
		// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			// remove old file
			$nationInfo1 = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
			if (strlen($nationInfo1["flag"]) >= 1) {
				unlink($nationInfo1["flag"]);
			} // if
			$returnArray["path"] = $target_file;
		} else {
			//echo "Sorry, there was an error uploading your file.";
			$returnArray["path"] = "";
			$returnArray["error"] = "An error occured while uploading.";
		} // else
	} // else

	return $returnArray;
} // uploadFile

/********************************
 setProduction
 validation and processing for setting production policies
 ********************************/
function setProduction($getPage_connection2) {
	if (strlen($_SESSION["action"]) >= 1) {
		if ($_SESSION["action"] == "prod") {
			if ($_SESSION["nation_id"] >= 1) {
				if ($_SESSION["prod_percent"] >= 0) {	
					if (count($_SESSION["prod"]) > 6) {
						$validProd = true;
						for ($xx=0; $xx < count($_SESSION["prod"]); $xx++) {
							if ($_SESSION["prod"][$xx] >= 0) {
								$validProd = true;
							} else {
								$validProd = false;
								break;
							} // else
						} // for
						
						if ($validProd === true) {
							$productionInfoZZ = getProductionInfo($getPage_connection2, $_SESSION["nation_id"]);
							setProductionInfo($getPage_connection2,$_SESSION["nation_id"],$_SESSION["prod_percent"],$_SESSION["prod"], $productionInfoZZ["ratios"]);
							$_SESSION["success_message"] = "Nation's production policies have been changed successfully!";
							error_log("set prod");
						} else {
							$_SESSION["warning_message"] = "Cannot complete action: one or more production variables is/are not defined.";
						} // else
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: insufficient production values submitted.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: insufficient production values submitted.";
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
} // setProduction
?>