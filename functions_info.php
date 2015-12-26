<?php
/****************************************************************************
 * Name:        functions_info.php
 * Author:      Ben Barnes
 * Date:        2015-12-25
 * Purpose:     Info functions page
 *****************************************************************************/

/*-----------------------------------------------*/
/********************************
 Info Page Functions
 ********************************/
/*-----------------------------------------------*/

/********************************
 getGlobals_info
 get and set global variables for info page
 ********************************/
function getGlobals_info($getPage_connection2) {
	// session: admin
	if (isset($_SESSION["admin"])) {
		$_SESSION["admin"] = cleanString($_SESSION["admin"],true);
	} else {
		$_SESSION["admin"] = 0;
	} // else

	if (count($_GET) > 1) {
		// get: info id
		if (isset($_GET["info_id"])) {
			$_SESSION["info_id"] = cleanString($_GET["info_id"],true);
		} else {
			$_SESSION["info_id"] = 0;
		} // else
			
		// get: section
		if (isset($_GET["section"])) {
			$_SESSION["section"] = cleanString($_GET["section"],true);
		} else {
			$_SESSION["section"] = 0;
		} // else
	} // if

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
	$_SESSION["userInfo"] = getUserInfo($getPage_connection2,$_SESSION["user_id"]);
	$_SESSION["nationInfo"] = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
} // getGlobals_info

/********************************
 performAction_info
 calls action for info if requested and valid
 ********************************/
function performAction_info($getPage_connection2) {

} // performAction_info

/********************************
 showInfoInfo
 visualize info information and input
 ********************************/
function showInfoInfo($getPage_connection2) {
	echo "        <div class=\"spacing-from-menu well well-lg standard-text\">\n";
	
	if ($_SESSION["section"] == "nations") {		
		$nationInfo = getNationInfo($getPage_connection2,$_SESSION["info_id"]);
		$continentInfo = getContinentInfo($getPage_connection2, $nationInfo["home"]);
		$authorityReport = getAuthorityReport($nationInfo["authority"]);
		$economyReport = getEconomyReport($nationInfo["economy"]);
		$rankingInfo = getRankingInfo($getPage_connection2,$_SESSION["info_id"]);
		
		echo "          <div class=\"panel panel-info\">\n";
		echo "            <div class=\"panel-heading\">\n";
		echo "              <h3 class=\"panel-title\">Government        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseGovernment\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
		echo "            </div>\n";
		echo "            <div id=\"collapseGovernment\" class=\"panel-body collapse in\">\n";
		echo "              <div class=\"col-md-8 col-center\">\n";
		echo "                <h3>".$nationInfo["formal"]."</h3>\n";
		echo "                <br />\n";
		echo "                <img class=\"info_flag\" src=\"".$nationInfo["flag"]."\" alt=\"Flag of ".$nationInfo["name"]."\" />\n";
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
		echo "            <div class=\"panel-heading\">\n";
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
		echo "            <div class=\"panel-heading\">\n";
		echo "              <h3 class=\"panel-title\">Organizations        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseOrganizations\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
		echo "            </div>\n";
		echo "            <div id=\"collapseOrganizations\" class=\"panel-body collapse in\">\n";
		echo "              <div class=\"col-md-8 col-center\">\n";
		echo "                ";
		$belongstoOrgs = false;
		for ($z=0; $z < count($nationInfo["organizations"]); $z++) {
			if ($z >= 1) {
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
	
	} else {
		$organizationInfo = getOrganizationInfo($getPage_connection2,$_SESSION["info_id"]);
		
		echo "          <div class=\"panel panel-info\">\n";
		echo "            <div class=\"panel-heading\">\n";
		echo "              <h3 class=\"panel-title\">General        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseGeneral\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
		echo "            </div>\n";
		echo "            <div id=\"collapseGeneral\" class=\"panel-body collapse in\">\n";
		echo "              <div class=\"col-md-8 col-center\">\n";
		echo "                <h3>".$organizationInfo["name"]."</h3>\n";
		echo "                <br />\n";
		echo "                Ranking: ".$organizationInfo["ranking"]." \n";
		echo "                <br />\n";
		echo "                <br />\n";
		
		echo "                Managers: ".count($organizationInfo["managers"])."\n";
		echo "                <br />\n";
		
		for ($z=0; $z < count($organizationInfo["managers"]); $z++) {
			if ($z >= 1) {
				echo ", ";
			} // if
			if ($organizationInfo["managers"][$z] >= 1) {
				$nationInfo1 = getNationInfo($getPage_connection2,$organizationInfo["managers"][$z]);
				echo "<a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$nationInfo1["id"]."\">".$nationInfo1["name"]."</a>";
			}  // if
		} // for
		
		echo "                <br />\n";
		
		echo "                Members: ".count($organizationInfo["members"])."\n";
		echo "                <br />\n";

		for ($za=0; $z < count($organizationInfo["members"]); $za++) {
			if ($za >= 1) {
				echo ", ";
			} // if
			if ($organizationInfo["members"][$za] >= 1) {
				$nationInfo1 = getNationInfo($getPage_connection2,$organizationInfo["members"][$za]);
				echo "<a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$nationInfo1["id"]."\">".$nationInfo1["name"]."</a>";
			}  // if
		} // for
		
		echo "              </div>\n";
		echo "            </div>\n";
		echo "          </div>\n";
	} // else
	echo "        </div>\n";
} // showInfoInfo

/*-----------------------------------------------*/
/********************************
 Info Action Functions
 ********************************/
/*-----------------------------------------------*/

?>