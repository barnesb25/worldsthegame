<?php
/****************************************************************************
 * Name:        functions_home.php
 * Author:      Ben Barnes
 * Date:        2016-02-20
 * Purpose:     Home functions page
 *****************************************************************************/

/********************************
 getGlobals_home
 get and set global variables for home page
 ********************************/
function getGlobals_home($getPage_connection2) {
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
		
	if (count($_POST)) {
		// post: register username
		if (isset($_POST["register_username"])) {
			$_SESSION["register_username"] = cleanString($_POST["register_username"],true);
		} else {
			$_SESSION["register_username"] = "";
		} // else
	
		// post: register password
		if (isset($_POST["register_password"])) {
			$_SESSION["register_password"] = cleanString($_POST["register_password"],true);
		} else {
			$_SESSION["register_password"] = "";
		} // else
			
		// post: register confirm password
		if (isset($_POST["register_confirm_password"])) {
			$_SESSION["register_confirm_password"] = cleanString($_POST["register_confirm_password"],true);
		} else {
			$_SESSION["register_confirm_password"] = "";
		} // else
	
		// post: register nation
		if (isset($_POST["register_nation"])) {
			$_SESSION["register_nation"] = cleanString($_POST["register_nation"],false);
		} else {
			$_SESSION["register_nation"] = "";
		} // else
	
		// post: register formal nation name
		if (isset($_POST["register_formal"])) {
			$_SESSION["register_formal"] = cleanString($_POST["register_formal"],false);
		} else {
			$_SESSION["register_formal"] = "";
		} // else
	
		// post current action
		if (isset($_POST["action"])) {
			$_SESSION["action"] = cleanString($_POST["action"],true);
		} else {
			$_SESSION["action"] = "";
		} // else
	} else if (count($_GET) > 1) {
		// post current action
		if (isset($_GET["action"])) {
			$_SESSION["action"] = cleanString($_GET["action"],true);
		} else {
			$_SESSION["action"] = "";
		} // else
	} // else if
} // getGlobals_home

/********************************
 performAction_home
 calls action for homes if requested and valid
 ********************************/
function performAction_home($getPage_connection2) {
	if ($_SESSION["action"] == "register") {
		registerUser($getPage_connection2);
	} else if ($_SESSION["action"] == "logout") {
		logoutUser($getPage_connection2);
	} // else
} // performAction_home

/********************************
 showHomeTitle
 visualize home title
 ********************************/
function showHomeTitle($getPage_connection2) {
	echo "        <div class=\"page-header spacing-from-menu\">\n";
	echo "          <img class=\"front\" src=\"images/home_logo.png\" alt=\"Worlds: The Game\" />\n          <br />\n";
	echo "          <h1>Build Your Nation Today!</h1>\n";
	echo "        </div>\n\n";
} // showHomeTitle

/********************************
 showHomeInfo
 visualize home information and input
 ********************************/
function showHomeInfo($getPage_connection2) {
	echo "        <div class=\"spacing-from-menu well well-lg standard-text\">\n";
	echo "          <form action=\"index.php?page=home\" method=\"post\">\n";
	echo "            <input name=\"action\" type=\"hidden\" value=\"register\" />\n";
	echo "            <div class=\"container\">\n";
	echo "              <div class=\"row\">\n";
	echo "                <div class=\"col-xs-12 col-sm-6 col-md-6\">\n";
	echo "                  <h1>Register now!</h1>\n";
	echo "                  <div class=\"form-group form-group-sm\">\n";
	echo "                    <label for=\"register_username\">Username</label>\n";
	echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Your in-game user account name.  Should be 5-35 characters.\" name=\"register_username\" type=\"text\" class=\"form-control\" id=\"register_username\" placeholder=\"Username\" />\n";
	echo "                  </div>\n";
	echo "                  <div class=\"form-group form-group-sm\">\n";
	echo "                    <label for=\"register_password\">Password</label>\n";
	echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Your account's password.  Should be 8-35 characters.\" name=\"register_password\" type=\"password\" class=\"form-control\" id=\"register_password\" placeholder=\"Password\" />\n";
	echo "                  </div>\n";
	echo "                  <div class=\"form-group form-group-sm\">\n";
	echo "                    <label for=\"register_confirm_password\">Confirm Password</label>\n";
	echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Confirm your account's password.  Should be 8-35 characters.\" name=\"register_confirm_password\" type=\"password\" class=\"form-control\" id=\"register_confirm_password\" placeholder=\"Password\" />\n";
	echo "                  </div>\n";
	echo "                  <div class=\"form-group form-group-sm\">\n";
	echo "                    <label for=\"register_nation\">Nation Name</label>\n";
	echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Your nation's regular name - this is the name referenced by other nations and the game engine.  You can't change this!\" name=\"register_nation\" type=\"text\" class=\"form-control\" id=\"register_nation\" placeholder=\"Russia\" />\n";
	echo "                  </div>\n";
	echo "                  <div class=\"form-group form-group-sm\">\n";
	echo "                    <label for=\"register_formal\">Formal Nation Name</label>\n";
	echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Your nation's long formal name.  You can change this later on.\" name=\"register_formal\" type=\"text\" class=\"form-control\" id=\"register_formal\" placeholder=\"Union of Soviet Socialist Republics\" />\n";
	echo "                  </div>\n";
	echo "                  <div class=\"form-group form-group-sm\">\n";
	echo "                    <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Click here when you've filled out all the fields!\" value=\"register\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-lg\">Register</button>\n";
	echo "                    <br /><br />\n";
	echo "                    <p class=\"sm-standard-text\">* By registering you agree to all the terms and conditions of the Site.</p>\n";
	echo "                  </div>\n";
	echo "                </div>\n";
	echo "                <div class=\"registration_ad col-xs-12 col-sm-6 col-md-6\">\n";
	echo "                  Registration is 100% free and easy!\n";
	echo "                  <br /><br /><br />\n";
	echo "                  <img src=\"images/registration.png\" alt=\"Register today!\" />\n";
	echo "                </div>\n";
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </form>\n";
	echo "        </div>\n";
} // showHomeInfo

/*-----------------------------------------------*/
/********************************
 Home Action Functions
 ********************************/
/*-----------------------------------------------*/

/********************************
 registerUser
 creates new user account if valid
 ********************************/
function registerUser($getPage_connection2) {
	if ($_SESSION["action"] == "register") {
		if (strlen($_SESSION["register_username"]) >= 5 && strlen($_SESSION["register_username"]) <= 35) {
			if (strlen($_SESSION["register_password"]) >= 8 && strlen($_SESSION["register_password"]) <= 35 && strlen($_SESSION["register_confirm_password"]) >= 8 && strlen($_SESSION["register_confirm_password"]) <= 35) {
				if ($_SESSION["register_confirm_password"] == $_SESSION["register_password"]) {
					$userInfoA = getUserInfoByName($getPage_connection2,$_SESSION["register_username"]);
					if ($userInfoA["id"] >= 1) {
						$_SESSION["warning_message"] = "Cannot complete action: Username is already in use.";
					} else {
						$new_salt = "";
						$new_username = $_SESSION["register_username"];
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
						$created_password = crypt($_SESSION["register_password"].$new_salt,$final_salt);
						addUserInfo($getPage_connection2,$new_username,$new_avatar,$new_date,$new_date,$created_password,$new_salt,$new_token,$new_thread,0);
						$new_userid = $getPage_connection2->insert_id;
						$new_name = $_SESSION["register_nation"];
						$new_formal = $_SESSION["register_formal"];
						addProductionInfo($getPage_connection2,$new_userid,100,array(0=>2,1=>2,2=>2,3=>2,4=>2,5=>2,6=>2,7=>2),array(0=>2,1=>2,2=>2,3=>2,4=>2,5=>2,6=>2,7=>2));
						addRankingInfo($getPage_connection2,$new_userid,999,999,999,999,999);
						$new_routes = array(0=>0);
						$new_worth = array(0=>0);
						$new_offers = array(0=>0);
						addTradeInfo($getPage_connection2,$new_userid,$new_routes,0);
						
						$capitalBuilt = false;
	
						$availableTiles = array(0=>0,1=>0,2=>0,3=>0,4=>0);
						$finalTiles = array(0=>0,1=>0);
	
						$availableContinent = 0;
						if ($stmt = $getPage_connection2->prepare("SELECT id FROM continents ORDER BY id ASC")) {
							$stmt->execute();
							$stmt->store_result();
							$stmt->bind_result($r_id);
							
							while ($stmt->fetch()) {	
								$next_continents = $r_id;
								$availableTiles = array(0=>0,1=>0,2=>0,3=>0,4=>0);
		
								$counter1 = 0;
								if ($stmt2 = $getPage_connection2->prepare("SELECT id FROM tilesmap ORDER BY id ASC")) {
									$stmt2->execute();
									$stmt2->store_result();
									$stmt2->bind_result($r_id1);

									while ($stmt2->fetch()) {
										$next_tiles = $r_id1;
										$tileInfoD = getTileInfoByID($getPage_connection2,$next_tiles);
																
										if ($tileInfoD["continent"] == $next_continents && $tileInfoD["owner"] == 0 && $tileInfoD["terrain"] != 2) {																
											$tileInfoDWest = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"]);
											$tileInfoDNorthWest = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"] - 1);
											$tileInfoDNorth = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"] - 1);
											$tileInfoDNorthEast = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"] + 1, $tileInfoD["ypos"] - 1);
											$tileInfoDEast = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"] + 1, $tileInfoD["ypos"]);
											$tileInfoDSouthEast = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"] + 1, $tileInfoD["ypos"] + 1);
											$tileInfoDSouth = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"], $tileInfoD["ypos"] + 1);
											$tileInfoDSouthWest = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"] + 1);								
											
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
						} // else				
						
						// get available tiles from newly created continent if no continent is available
						if ($availableContinent < 1) {
							$continent1 = generateContinent($getPage_connection2);					
	
							$next_continents = $continent1;
							
							$availableContinent = $next_continents;
	
							$availableTiles = array(0=>0,1=>0,2=>0,3=>0,4=>0);
							
							$next_tiles = 1;
							$counter1 = 0;
							if ($stmt = $getPage_connection2->prepare("SELECT id FROM tilesmap ORDER BY id ASC")) {
								$stmt->execute();
								$stmt->store_result();
								$stmt->bind_result($r_id);
								
								while ($stmt->fetch()) {
									$next_tiles = $r_id;
									$tileInfoD = getTileInfoByID($getPage_connection2,$next_tiles);
											
									if ($tileInfoD["continent"] == $next_continents && $tileInfoD["owner"] == 0 && ($tileInfoD["terrain"] != 2 && $tileInfoD["terrain"] != 3)) {
										$tileInfoDWest = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"]);
										$tileInfoDNorthWest = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"] - 1);
										$tileInfoDNorth = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"] - 1);
										$tileInfoDNorthEast = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"] + 1, $tileInfoD["ypos"] - 1);
										$tileInfoDEast = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"] + 1, $tileInfoD["ypos"]);
										$tileInfoDSouthEast = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"] + 1, $tileInfoD["ypos"] + 1);
										$tileInfoDSouth = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"], $tileInfoD["ypos"] + 1);
										$tileInfoDSouthWest = getTileInfo($getPage_connection2, $tileInfoD["continent"], $tileInfoD["xpos"] - 1, $tileInfoD["ypos"] + 1);
										
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
									addClaimInfo($getPage_connection2,10,$new_userid,$finalTiles[$c]);
								} // if
							} // if
						} // for
	
						for ($j=0; $j < 3; $j++) {
							if (isset($finalTiles[$j])) {
								if ($finalTiles[$j] > 0) {
									$tileInfo2 = getTileInfoByID($getPage_connection2,$finalTiles[$j]);
									setTileInfo($getPage_connection2,$tileInfo2["id"],$tileInfo2["continent"],$tileInfo2["xpos"],$tileInfo2["ypos"],$tileInfo2["terrain"],$tileInfo2["resources"],$tileInfo2["improvements"],$new_userid,$tileInfo2["claims"],$tileInfo2["population"]);
									if ($capitalBuilt === false) {
										addImprovementInfo($getPage_connection2, $tileInfo2["continent"], $tileInfo2["xpos"], $tileInfo2["ypos"], 1, 1, array(0=>0), array(0=>$new_userid),"Capital City"); // add capital
										addImprovementInfo($getPage_connection2, $tileInfo2["continent"], $tileInfo2["xpos"], $tileInfo2["ypos"], 4, 1, array(0=>0), array(0=>$new_userid), "First Farm"); // add farm
										$capitalBuilt = true;
									} // if
								} // if
							} // if
						} // for
	
						addNationInfo($getPage_connection2,$new_userid,$new_name,$availableContinent,$new_formal,"",12,5000,0,3,2500,5,0,5,0,array(0=>0),array(0=>0),array(0=>5,1=>0,2=>0,3=>5,4=>2,5=>5,6=>0,7=>5),array(0=>5,1=>5,2=>5,3=>5),2000,0);
						
						// go through y positions
						for ($y = 1; $y < 21; $y++ ) {
							// go through x positions
							for ($x = 1; $x < 21; $x++ ) {
								$mapContentString = "";
								$mapContentToken = 0;

								for ($qw=0; $qw < 5; $qw++) {
									if ($qw == 0) {
										$_SESSION["overlay"] = "terrain";
									} else if ($qw == 1) {
										$_SESSION["overlay"] = "control";
									} else if ($qw == 2) {
										$_SESSION["overlay"] = "claims";
									} else if ($qw == 3) {
										$_SESSION["overlay"] = "units";
									} else if ($qw == 4) {
										$_SESSION["overlay"] = "nations";
									} // else if
										
									$mapContent_generated = array("",0);
									$mapContent_generated = generateMapTile($getPage_connection2,$availableContinent,$x,$y);
										
									$mapContentString = $mapContent_generated[0];
									$mapContentToken = $mapContent_generated[1];
										
									if ($_SESSION["overlay"] == "terrain") {
										$_SESSION["terrainMapContentsTokens"][$y][$x] = $mapContentToken;
										$_SESSION["terrainMapContents"][$y][$x] = $mapContentString;
									} else if ($_SESSION["overlay"] == "control") {
										$_SESSION["controlMapContentsTokens"][$y][$x] = $mapContentToken;
										$_SESSION["controlMapContents"][$y][$x] = $mapContentString;
									} else if ($_SESSION["overlay"] == "claims") {
										$_SESSION["overlayMapContentsTokens"][$y][$x] = $mapContentToken;
										$_SESSION["overlayMapContents"][$y][$x] = $mapContentString;
									} else if ($_SESSION["overlay"] == "units") {
										$_SESSION["unitsMapContentsTokens"][$y][$x] = $mapContentToken;
										$_SESSION["unitsMapContents"][$y][$x] = $mapContentString;
									} else if ($_SESSION["overlay"] == "nations") {
										$_SESSION["nationsMapContentsTokens"][$y][$x] = $mapContentToken;
										$_SESSION["nationsMapContents"][$y][$x] = $mapContentString;
									} else {
										$_SESSION["nationsMapContentsTokens"][$y][$x] = $mapContentToken;
										$_SESSION["nationsMapContents"][$y][$x] = $mapContentString;
									} // else
								} // for
								
							} // for
						} // for
						addMapMemoryInfo($getPage_connection2, $new_userid, $_SESSION["terrainMapContents"], $_SESSION["controlMapContents"], $_SESSION["claimsMapContents"], $_SESSION["unitsMapContents"], $_SESSION["nationsMapContents"], $_SESSION["terrainMapContentsTokens"], $_SESSION["controlMapContentsTokens"], $_SESSION["claimsMapContentsTokens"], $_SESSION["unitsMapContentsTokens"], $_SESSION["nationsMapContentsTokens"]);
						
						
						$_SESSION["success_message"] = "User has been registered successfully!";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: Passwords are not matching, double check your password fields.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: Password must be 8-35 characters.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: Username must be 8-35 characters.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: registration is invalid.";
	} // else
} // registerUser

/********************************
 logoutUser
 logs out current user
 ********************************/
function logoutUser($getPage_connection2) {
	if ($_SESSION["action"] == "logout") {
		// only logout if logged in...
		if ($_SESSION["login"] == 1) {
			setMapMemoryInfo($getPage_connection2, $_SESSION["user_id"], $_SESSION["terrainMapContents"], $_SESSION["controlMapContents"], $_SESSION["claimsMapContents"], $_SESSION["unitsMapContents"], $_SESSION["nationsMapContents"], $_SESSION["terrainMapContentsTokens"], $_SESSION["controlMapContentsTokens"], $_SESSION["claimsMapContentsTokens"], $_SESSION["unitsMapContentsTokens"], $_SESSION["nationsMapContentsTokens"]);
			resetSession(false);
		} // if
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: user is already logged out.";
	} // else
} // logoutUser
?>