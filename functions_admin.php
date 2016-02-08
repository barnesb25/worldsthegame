<?php
/****************************************************************************
 * Name:        functions_admin.php
 * Author:      Ben Barnes
 * Date:        2016-01-29
 * Purpose:     Admin functions page
 *****************************************************************************/

/*-----------------------------------------------*/
/********************************
 Admin Page Functions
 ********************************/
/*-----------------------------------------------*/

/********************************
 getGlobals_admin
 get and set global variables for admin page
 ********************************/
function getGlobals_admin($getPage_connection2) {
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
} // getGlobals_admin

/********************************
 performAction_admin
 calls action for admin if requested and valid
 ********************************/
function performAction_admin($getPage_connection2,$getPage_connection3) {
	if ($_SESSION["action"] == "reset_world") {
		resetWorld($getPage_connection3);
	} else if ($_SESSION["action"] == "custom_script") {
		customScript($getPage_connection3);
	} // else if
} // performAction_admin

/********************************
 showAdminInfo
 visualize admin information and input
 ********************************/
function showAdminInfo($getPage_connection2) {
	$nationInfo = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
	$continentInfo = getContinentInfo($getPage_connection2, $nationInfo["home"]);

	echo "        <div class=\"spacing-from-menu well well-lg standard-text\">\n";

	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Control Panel        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseControlPanel\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseControlPanel\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	echo "                  <form action=\"index.php?page=admin\" method=\"post\">\n";
	echo "                    <input type=\"hidden\" name=\"page\" value=\"admin\" />\n";
	
	echo "                    <div class=\"form-group form-group-sm\">\n";
	echo "                      <button onclick=\"loadButton(this)\" value=\"custom_script\" name=\"action\" id=\"custom_script\" type=\"submit\" class=\"btn btn-md btn-primary\">Run Custom Script</button>\n";
	echo "                    </div>\n";

	echo "                    <div class=\"form-group form-group-sm\">\n";
	echo "                      <label class=\"control-label\" for=\"reset_world\">WARNING: ALL WORLD DATA WILL BE LOST!  ADMINISTRATOR PASSWORD WILL BE RESET TO DEFAULT!</label>\n";
	echo "                      <button onclick=\"loadButton(this)\" value=\"reset_world\" name=\"action\" id=\"reset_world\" type=\"submit\" class=\"btn btn-md btn-danger\">Reset World to Defaults</button>\n";
	echo "                    </div>\n";

	echo "                  </form>\n";

	echo "                </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";

	echo "        </div>\n";
} // showAdminInfo

/*-----------------------------------------------*/
/********************************
 Admin Action Functions
 ********************************/
/*-----------------------------------------------*/

/********************************
 customScript
 validation and processing for running custom script
 ********************************/
function customScript($getPage_connection3) {
	if ($_SESSION["admin"] == 1) {
		if (strlen($_SESSION["action"]) >= 1) {
			if ($_SESSION["action"] == "custom_script") {
				require "custom.php";
				$_SESSION["success_message"] = "Script has been run successfully!";
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: not valid admin.";
	} // else
} // customScript

/********************************
 resetWorld
 validation and processing for resetting whole world to defaults
 ********************************/
function resetWorld($getPage_connection3) {
	if ($_SESSION["admin"] == 1) {
		if (strlen($_SESSION["action"]) >= 1) {
			if ($_SESSION["action"] == "reset_world") {										
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE agreements")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE claims")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE combatlog")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE continents")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE improvementsmap")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE nations")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE offers")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE organizations")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE production")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE rankings")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE resourcesmap")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE tilesmap")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE trade")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE transport")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE unitsmap")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else
				if ($stmt = $getPage_connection3->prepare("TRUNCATE TABLE users")) {
					$stmt->execute();
					$stmt->close();
				} else {
				} // else	

				if ($stmt = $getPage_connection3->prepare("SELECT id FROM market ORDER BY id ASC")) {
					$stmt->execute();
					$stmt->store_result();
					$stmt->bind_result($r_id);
				
					while ($stmt->fetch()) {
						$next_markets = $r_id;							
						$marketInfo1 = getMarketInfo($getPage_connection3, $next_markets);
						setMarketInfo($getPage_connection3, $next_markets, $marketInfo1["name"], 100);						
					} // while
					$stmt->close();
				} else {					
				} // else
				
				resetSession(false);			
					
				// Create Admin Account, starter continent
					
				$new_salt = "";
				$new_username = "administrator";
				$new_avatar = "images/users/avatar.png";
				$allowed_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
				$chars_length = 63;
				for ($i=0; $i<51; $i++) {
					$new_salt .= $allowed_chars[mt_rand(0,$chars_length)];
				} // for
				$new_date = date("Y-m-d H:i:s");
				$new_token = mt_rand(1000,9999);
				$new_thread = mt_rand(100,999);
				$final_salt = '$2y$09$'.$new_salt.'$';
				$created_password = crypt("qwertyuiop".$new_salt,$final_salt);
				addUserInfo($getPage_connection3,$new_username,$new_avatar,$new_date,$new_date,$created_password,$new_salt,$new_token,$new_thread,1);
				$new_userid = $getPage_connection3->insert_id;
				$new_name = "Administrator";
				$new_formal = "Administrator";
				addProductionInfo($getPage_connection3,$new_userid,100,array(0=>2,1=>2,2=>2,3=>2,4=>2,5=>2,6=>2,7=>2),array(0=>2,1=>2,2=>2,3=>2,4=>2,5=>2,6=>2,7=>2));
				addRankingInfo($getPage_connection3,$new_userid,999,999,999,999,999);
				$new_routes = array(0=>0);
				$new_worth = array(0=>0);
				$new_offers = array(0=>0);
				addTradeInfo($getPage_connection3,$new_userid,$new_routes,0);
				
				$capitalBuilt = false;
				
				$availableTiles = array(0=>0,1=>0,2=>0,3=>0,4=>0);
				$finalTiles = array(0=>0,1=>0);
				
				$availableContinent = 0;
				if ($stmt = $getPage_connection3->prepare("SELECT id FROM continents ORDER BY id ASC")) {
					$stmt->execute();
					$stmt->store_result();
					$stmt->bind_result($r_id);

					while ($stmt->fetch()) {
						$next_continents = $r_id;
						$availableTiles = array(0=>0,1=>0,2=>0,3=>0,4=>0);
					
						$next_tiles = 1;
						$counter1 = 0;
						if ($stmt2 = $getPage_connection3->prepare("SELECT id FROM tilesmap ORDER BY id ASC")) {
							$stmt2->execute();
							$stmt2->store_result();
							$stmt2->bind_result($r_id1);

							while ($stmt2->fetch()) {
								$next_tiles = $r_id1;
								$tileInfoD = getTileInfoByID($getPage_connection3,$next_tiles);
						
								if ($tileInfoD["continent"] == $next_continents && $tileInfoD["owner"] == 0 && $tileInfoD["terrain"] != 2) {
									$tileInfoDWest = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"]);
									$tileInfoDNorthWest = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"] - 1);
									$tileInfoDNorth = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"] - 1);
									$tileInfoDNorthEast = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"] + 1, $tileInfoD["ypos"] - 1);
									$tileInfoDEast = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"] + 1, $tileInfoD["ypos"]);
									$tileInfoDSouthEast = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"] + 1, $tileInfoD["ypos"] + 1);
									$tileInfoDSouth = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"], $tileInfoD["ypos"] + 1);
									$tileInfoDSouthWest = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"] + 1);
										
									if ( ($tileInfoDWest["continent"] == $next_continents && $tileInfoDWest["owner"] == 0 && $tileInfoDWest["terrain"] != 2) ||
											($tileInfoDNorthWest["continent"] == $next_continents && $tileInfoDNorthWest["owner"] == 0 && $tileInfoDNorthWest["terrain"] != 2) ||
											($tileInfoDNorth["continent"] == $next_continents && $tileInfoDNorth["owner"] == 0 && $tileInfoDNorth["terrain"] != 2) ||
											($tileInfoDNorthEast["continent"] == $next_continents && $tileInfoDNorthEast["owner"] == 0 && $tileInfoDNorthEast["terrain"] != 2) ||
											($tileInfoDEast["continent"] == $next_continents && $tileInfoDEast["owner"] == 0 && $tileInfoDEast["terrain"] != 2) ||
											($tileInfoDSouthEast["continent"] == $next_continents && $tileInfoDSouthEast["owner"] == 0 && $tileInfoDSouthEast["terrain"] != 2) ||
											($tileInfoDSouth["continent"] == $next_continents && $tileInfoDSouth["owner"] == 0 && $tileInfoDSouth["terrain"] != 2) ||
											($tileInfoDSouthWest["continent"] == $next_continents && $tileInfoDSouthWest["owner"] == 0 && $tileInfoDSouthWest["terrain"] != 2) )
									{
										$availableTiles[$counter1] = $tileInfoD["id"];
										$counter1++;
									} // if
								} // if
						
								if ($counter1 == 4) {
									$availableContinent = $next_continents;
								} // if
							} // while
							$stmt2->close();
						} else {
						} // else
					
						if ($counter1 == 4) {
							break;
						} // if
					} // while
					$stmt->close();
				} else {
					$next_continents = 0;
				} // else
				
				// get available tiles from newly created continent if no continent is available
				if ($availableContinent < 1) {
					$continent1 = generateContinent($getPage_connection3);
				
					$next_continents = $continent1;
						
					$availableContinent = $next_continents;
				
					$availableTiles = array(0=>0,1=>0,2=>0,3=>0,4=>0);
						
					$counter1 = 0;
					if ($stmt = $getPage_connection3->prepare("SELECT id FROM tilesmap ORDER BY id ASC")) {
						$stmt->execute();
						$stmt->store_result();
						$stmt->bind_result($r_result);

						while ($stmt->fetch()) {
							$next_tiles = $r_result;
							$tileInfoD = getTileInfoByID($getPage_connection3,$next_tiles);
					
							if ($tileInfoD["continent"] == $next_continents && $tileInfoD["owner"] == 0 && ($tileInfoD["terrain"] != 2 && $tileInfoD["terrain"] != 3)) {
								$tileInfoDWest = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"]);
								$tileInfoDNorthWest = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"] - 1);
								$tileInfoDNorth = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"] - 1);
								$tileInfoDNorthEast = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"] + 1, $tileInfoD["ypos"] - 1);
								$tileInfoDEast = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"] + 1, $tileInfoD["ypos"]);
								$tileInfoDSouthEast = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"] + 1, $tileInfoD["ypos"] + 1);
								$tileInfoDSouth = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"], $tileInfoD["ypos"] + 1);
								$tileInfoDSouthWest = getTileInfo($getPage_connection3, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"] + 1);
								
								if ( ($tileInfoDWest["continent"] == $next_continents && $tileInfoDWest["owner"] == 0 && ($tileInfoDWest["terrain"] != 2 && $tileInfoDWest["terrain"] != 3)) ||
									($tileInfoDNorthWest["continent"] == $next_continents && $tileInfoDNorthWest["owner"] == 0 && ($tileInfoDNorthWest["terrain"] != 2 && $tileInfoDNorthWest["terrain"] != 3)) ||
										($tileInfoDNorth["continent"] == $next_continents && $tileInfoDNorth["owner"] == 0 && ($tileInfoDNorth["terrain"] != 2 && $tileInfoDNorth["terrain"] != 3)) ||
										($tileInfoDNorthEast["continent"] == $next_continents && $tileInfoDNorthEast["owner"] == 0 && ($tileInfoDNorthEast["terrain"] != 2 && $tileInfoDNorthEast["terrain"] != 3)) ||
										($tileInfoDEast["continent"] == $next_continents && $tileInfoDEast["owner"] == 0 && ($tileInfoDEast["terrain"] != 2 && $tileInfoDEast["terrain"] != 3)) ||
										($tileInfoDSouthEast["continent"] == $next_continents && $tileInfoDSouthEast["owner"] == 0 && ($tileInfoDSouthEast["terrain"] != 2 && $tileInfoDSouthEast["terrain"] != 3)) ||
										($tileInfoDSouth["continent"] == $next_continents && $tileInfoDSouth["owner"] == 0 && ($tileInfoDSouth["terrain"] != 2 && $tileInfoDSouth["terrain"] != 3)) ||
										($tileInfoDSouthWest["continent"] == $next_continents && $tileInfoDSouthWest["owner"] == 0 && ($tileInfoDSouthWest["terrain"] != 2 && $tileInfoDSouthWest["terrain"] != 3)) )
								{
									$availableTiles[$counter1] = $tileInfoD["id"];
									$counter1++;
								} // if
							} // if								
						} // while
						$stmt->close();
					} else {
					} // else
				} // if
				
				$sameTile = true;
				$randTiles = array(0=>0,1=>0);
				while ($sameTile === true) {
					$randTiles[0] = mt_rand(1,count($availableTiles) - 2);
						
					$randDirection = mt_rand(1,2);
						
					if ($randDirection == 1) {
						$randTiles[1] = $randTiles[0] - 1;
					} else if ($randDirection == 2) {
						$randTiles[1] = $randTiles[0] + 1;
					} // else if
						
					if ($randTiles[0] == $randTiles[1]) {
						$sameTile = true;
					} else {
						$sameTile = false;
					} // else
				} // while
				
				$finalTiles[0] = $availableTiles[$randTiles[0]];
				$finalTiles[1] = $availableTiles[$randTiles[1]];
					
				$claims = array(0=>0);
				for ($c=0; $c < 3; $c++) {
					if (isset($finalTiles[$c])) {
						if ($finalTiles[$c] > 0) {
							addClaimInfo($getPage_connection3,10,$new_userid,$finalTiles[$c]);
						} // if
					} // if
				} // for
				
				for ($j=0; $j < 3; $j++) {
					if (isset($finalTiles[$j])) {
						if ($finalTiles[$j] > 0) {
							$tileInfo2 = getTileInfoByID($getPage_connection3,$finalTiles[$j]);
							setTileInfo($getPage_connection3,$tileInfo2["id"],$tileInfo2["continent"],$tileInfo2["xpos"],$tileInfo2["ypos"],$tileInfo2["terrain"],$tileInfo2["resources"],$tileInfo2["improvements"],$new_userid,$tileInfo2["claims"],$tileInfo2["population"]);
							if ($capitalBuilt === false) {
								addImprovementInfo($getPage_connection3, $tileInfo2["continent"], $tileInfo2["xpos"], $tileInfo2["ypos"], 1, 1, array(0=>0), array(0=>$new_userid), "Capital City"); // add capital
								addImprovementInfo($getPage_connection3, $tileInfo2["continent"], $tileInfo2["xpos"], $tileInfo2["ypos"], 4, 1, array(0=>0), array(0=>$new_userid), "First Farm"); // add farm
								$capitalBuilt = true;
							} // if
						} // if
					} // if
				} // for
				
				addNationInfo($getPage_connection3,$new_userid,$new_name,$availableContinent,$new_formal,"",12,5000,0,3,2500,5,0,5,0,array(0=>0),array(0=>0),array(0=>5,1=>0,2=>0,3=>5,4=>2,5=>5,6=>0,7=>5),array(0=>5,1=>5,2=>5,3=>5),2000,0);					

				//
				
				$_SESSION["success_message"] = "World has been reset successfully!";
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: action is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: not valid admin.";
	} // else
} // resetWorld
?>