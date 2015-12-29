<?php
/****************************************************************************
 * Name:        generic.php
 * Author:      Ben Barnes
 * Date:        2015-12-28
 * Purpose:     Miscellaneous functions page
 *****************************************************************************/

/********************************
 getConnection
 Connects to database
 ********************************/
function getConnection($getConnection_host,$getConnection_user,$getConnection_password,$getConnection_database,$getConnection_port) {
	// checks to add port in variables or not
	if (isset($getConnection_port)) {
		if (strlen($getConnection_port) >= 1) {
			$getConnection_mysqli = new mysqli($getConnection_host, $getConnection_user, $getConnection_password, $getConnection_database, $getConnection_port);
		} else {
			$getConnection_mysqli = new mysqli($getConnection_host, $getConnection_user, $getConnection_password, $getConnection_database);
		} // else
	} else {
		$getConnection_mysqli = new mysqli($getConnection_host, $getConnection_user, $getConnection_password, $getConnection_database);
	} // else

	if ($getConnection_mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $getConnection_mysqli->connect_errno . ") " . $getConnection_mysqli->connect_error;
	} else {
		return $getConnection_mysqli;
	} // else
} // getConnection

/********************************
 getPage
 Execute correct script files
 ********************************/
function getPage($getPage_connection,$getPage_connection2,$getPage_connection3) {
	// use GET page variable to define incoming page
	if (isset($_GET["page"])) {
		$currentPage = cleanString($_GET["page"],true);
	// otherwise default to home
	} else {
		$currentPage = "home";
	} // else
	$_SESSION["pageTypeInfo"] = getPageTypeInfo($getPage_connection2,$currentPage); // layout and login

	// get login status
	$loggedInArray = checkLoginStatus($getPage_connection2);

	// if just logging in and successful logged in,
	if ($loggedInArray["loggingIn"] === true && $loggedInArray["status"] === true) {
		require "map.php";
	// if not just logging in but succesfully logged in already,
	} else if ($loggedInArray["loggingIn"] === false && $loggedInArray["status"] === true) {
		// check for admin privilege
		if ($_SESSION["pageTypeInfo"]["admin"] == 1) {
			if ($_SESSION["admin"] == 1) {
				require $_SESSION["pageTypeInfo"]["layout"];
			} else {
				require "map.php";
			} // else
		// otherwise if admin privilege is not required,
		} else {
			// if home page,
			if ($_SESSION["pageTypeInfo"]["layout"] == "home.php") { 
				// unless logging out, default to map page
				$clean_action = cleanString($_GET["action"], true);
				if ($clean_action == "logout") {
					require "home.php";
				} else {
					require "map.php";
				} // else
			} else {
				require $_SESSION["pageTypeInfo"]["layout"];
			} // else
		} // else
	// default
	} else {
		// if page requires login access or admin send to default
		if ($_SESSION["pageTypeInfo"]["login"] == 1 || $_SESSION["pageTypeInfo"]["admin"] == 1) {
			require "home.php";
		} else {
			require $_SESSION["pageTypeInfo"]["layout"];
		} // else
	} // else
} // getPage

/********************************
 checkLoginStatus
 ********************************/
function checkLoginStatus($getPage_connection2) {
	$loginArray = array("status"=>false,"loggingIn"=>false);
	$loggingIn = false;

	// if login info has been submitted
	if (isset($_POST["username"]) && isset($_POST["password"])) {
		$cleaned_username = cleanString($_POST["username"],true);
		$cleaned_password = cleanString($_POST["password"],true);
		if (strlen($cleaned_username) >= 1 && strlen($cleaned_password) >= 1) {
			$loggingIn = true;
		} // if
	} // if

	if (!(isset($_SESSION["login"]))) {
		$_SESSION["login"] = 0;
	} // if

	if ($_SESSION["login"] != 1 && $loggingIn === true) {
		$userInfo1 = getUserInfoByName($getPage_connection2,$cleaned_username);
		if ($userInfo1["id"] >= 1) {
			$final_salt = '$2y$09$'.$userInfo1["salt"].'$';
			$created_password = crypt($cleaned_password.$userInfo1["salt"],$final_salt);
			$created_string = hash('sha512', $created_password.$userInfo1["token"]);
			$actual_string = hash('sha512', $userInfo1["password"].$userInfo1["token"]);

			if ($actual_string == $created_string) {
				$_SESSION["user_id"] = $userInfo1["id"];
				$_SESSION["username"] = $cleaned_username;
				$_SESSION["login_string"] = $created_string;
				$_SESSION["login"] = 1;
				$_SESSION["nation_id"] = $userInfo1["id"];
				$_SESSION["admin"] = $userInfo1["admin"];
				$new_date = date("Y-m-d H:i:s");
				$nationInfoLogin = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				setUserInfo($getPage_connection2,$userInfo1["id"],$userInfo1["name"],$userInfo1["avatar"],$userInfo1["joined"],$new_date,$userInfo1["password"],$userInfo1["salt"],$userInfo1["token"],$userInfo1["thread"],$userInfo1["admin"]);
				$_SESSION["success_message"] = "User has logged in successfully!";
				$_SESSION["pageTypeInfo"] = getPageTypeInfo($getPage_connection2,"map");
				$loginArray["status"] = true;
				$loginArray["loggingIn"] = true;
				$_POST["continent"] = $nationInfoLogin["home"];
				$_SESSION["continent_id"] = $nationInfoLogin["home"];
				$_SESSION["xpos"] = 1;
				$_SESSION["ypos"] = 1;
				$_POST["overlay"] = "nations";
				$_GET["overlay"] = "nations";
				$_SESSION["overlay"] = "nations";
			} else {
				$_SESSION["login"] = 0;
				$loginArray["status"] = false;
				$loginArray["loggingIn"] = false;
				$_SESSION["success_message"] = "";
				$_SESSION["user_id"] = 0;
				$_SESSION["username"] = "";
				$_SESSION["nation_id"] = 0;
				$_SESSION["admin"] = 0;
				$_SESSION["warning_message"] = "Cannot complete action: invalid user password credentials submitted.";
			} // else
		} else {
			$_SESSION["login"] = 0;
			$loginArray["status"] = false;
			$loginArray["loggingIn"] = false;
			$_SESSION["success_message"] = "";
			$_SESSION["user_id"] = 0;
			$_SESSION["username"] = "";
			$_SESSION["nation_id"] = 0;
			$_SESSION["admin"] = 0;
			$_SESSION["warning_message"] = "Cannot complete action: invalid user name credentials submitted.";
		} // else
	} else {
		if (isset($_SESSION["login_string"])) {
			if (isset($_SESSION["user_id"])) {
				$userInfo1 = getUserInfo($getPage_connection2,$_SESSION["user_id"]);
				if ($_SESSION["login_string"] == hash('sha512',$userInfo1["password"].$userInfo1["token"])) {
					$_SESSION["login"] = 1;
					$loginArray["status"] = true;
					$loginArray["loggingIn"] = false;
					$_SESSION["success_message"] = "";
					$_SESSION["user_id"] = $userInfo1["id"]; // unique ID number of user
					$_SESSION["username"] = $userInfo1["name"]; // unique string name of user
					$_SESSION["nation_id"] = $userInfo1["id"]; // nation
					$_SESSION["admin"] = $userInfo1["admin"]; // admin
				} // if
			} else {
				$_SESSION["login"] = 0;
				$loginArray["status"] = false;
				$loginArray["loggingIn"] = false;
				$_SESSION["success_message"] = "";
				$_SESSION["user_id"] = 0;
				$_SESSION["username"] = "";
				$_SESSION["nation_id"] = 0;
				$_SESSION["admin"] = 0;
			} // else
		} else {
			$_SESSION["login"] = 0;
			$loginArray["status"] = false;
			$loginArray["loggingIn"] = false;
			$_SESSION["success_message"] = "";
			$_SESSION["user_id"] = 0;
			$_SESSION["username"] = "";
			$_SESSION["nation_id"] = 0;
			$_SESSION["admin"] = 0;
		} // else
	} // else

	return $loginArray;
} // checkLoginStatus

function resetLoginSession() {
}

// heavily 'cleans' a given string variable, removing many unwanted elements and re-creating a syntax-friendly string
// if $cleanreg set to true, use a regular expression replacement
function cleanString($var,$cleanreg) {
	$var = trim($var);
	$var = strip_tags($var);
	$var = htmlentities($var, ENT_QUOTES, "UTF-8", $double_encode = true);
	// strict cleaning: uses regular expression replacement to replace with empty
	if ($cleanreg === true) {
		$var = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $var); // XSS protection as we might print this value
	} // if
	return $var;
} // cleanString

// similar to cleanString, but leaves white space more intact for preserving formatting
// if $cleanreg set to true, use a regular expression replacement
function cleanStringLite($var) {
	$var = strip_tags($var);
	$var = htmlentities($var, ENT_QUOTES, "UTF-8", $double_encode = true);
	return $var;
} // cleanString

// checks for brute force attacks and gives appropriate feedback
// returns 1 for clearing of possible brute force attack
function checkBrute ($checkBrute_connection) {
	return 1;
	//NOT DONE
} // checkBrute

function resetSession($keepCores) {
	if ($keepCores === true) {
		if (isset($_SESSION["user_id"])) {
			$saved_user_id = $_SESSION["user_id"];
		} else {
			$saved_user_id = 0;
		} // else
		if (isset($_SESSION["username"])) {
			$saved_username = $_SESSION["username"];
		} else {
			$saved_username = "";
		} // else
		if (isset($_SESSION["login_string"])) {
			$saved_login_string = $_SESSION["login_string"];
		} else {
			$saved_login_string = "";
		} // else
		if (isset($_SESSION["login"])) {
			$saved_login = $_SESSION["login"];
		} else {
			$saved_login = 0;
		} // else
		if (isset($_SESSION["nation_id"])) {
			$saved_nation_id = $_SESSION["nation_id"];
		} else {
			$saved_nation_id = 0;
		} // else
		if (isset($_SESSION["admin"])) {
			$saved_admin = $_SESSION["admin"];
		} else {
			$saved_admin = 0;
		} // else
		if (isset($_SESSION["pageTypeInfo"])) {
			$saved_pageTypeInfo = $_SESSION["pageTypeInfo"];
		} else {
			$saved_pageTypeInfo = 0;
		} // else

		if (isset($_SESSION["continent_id"])) {
			$saved_continent_id = $_SESSION["continent_id"];
		} else {
			$saved_continent_id = 0;
		} // else
		if (isset($_SESSION["xpos"])) {
			$saved_xpos = $_SESSION["xpos"];
		} else {
			$saved_xpos = 0;
		} // else
		if (isset($_SESSION["ypos"])) {
			$saved_ypos = $_SESSION["ypos"];
		} else {
			$saved_ypos = 0;
		} // else
		if (isset($_SESSION["overlay"])) {
			$saved_overlay = $_SESSION["overlay"];
		} else {
			$saved_overlay = 0;
		} // else

		session_unset();

		$_SESSION["user_id"] = $saved_user_id;
		$_SESSION["username"] = $saved_username;
		$_SESSION["login_string"] = $saved_login_string;
		$_SESSION["login"] = $saved_login;
		$_SESSION["nation_id"] = $saved_nation_id;
		$_SESSION["admin"] = $saved_admin;
		$_SESSION["pageTypeInfo"] = $saved_pageTypeInfo;
		$_SESSION["continent_id"] = $saved_continent_id;
		$_SESSION["xpos"] = $saved_xpos;
		$_SESSION["ypos"] = $saved_ypos;
		$_SESSION["overlay"] = $saved_overlay;
	} else {
		session_unset();
	} // else
	unset($POST);
	unset($GET);
} // resetSession

/********************************
 generateContinent
 Automated script for creating new continent
 ********************************/
function generateContinent($getPage_connection2) {
	$limit_allTiles = 0;
	if ($stmt = $getPage_connection2->prepare("SELECT COUNT(id) FROM tilesmap")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->fetch();
		$limit_allTiles = $r_result;
		$stmt->close();
	} else {
	} // else

	addContinentInfo($getPage_connection2,"name");
	$new_continent = $getPage_connection2->insert_id;

	$new_resources = array(0=>0);

	for ($y = 1; $y < 21; $y++) {
		for ($x = 1; $x < 21; $x++) {
			// if border tile, it has to be water!
			if ($x == 1 || $x == 20 || $y == 1 || $y == 20) {
				$new_terrain = 2;
				addTileInfo($getPage_connection2,$new_continent,$x,$y,$new_terrain,$new_resources,array(0=>0),0,array(0=>0),1000);

				// random generate resources where applicable
				for ($v=0; $v < 4; $v++) {
					$rand1 = mt_rand(1,5); // rolls for choosing resource
					$rand2 = mt_rand(1,10); // rolls for probability

					if ($rand1 == 3) {
						// fossil fuels = 40% chance
						if ($rand2 <= 4) {
							$randQuantity = mt_rand(100,10000);
							// generate resource
							addResourceInfo($getPage_connection2,$new_continent,$x,$y,3,$randQuantity);
						} // if
					} // if
				} // for
			} else {
				// if middle strip,
				if ($x < 12 && $x > 8) {
					$randTerrain = mt_rand(1,23);
					// if upper or lower centre tiles
				} else if ((($x > 6 && $x < 14) && ($y > 1 && $y < 6)) || (($x > 6 && $x < 14) && ($y > 16 && $y < 20))) {
					$randTerrain = mt_rand(1,30);
					// if corner land tiles,
				} else if ((($x == 2 || $x == 3) && ($y == 2 || $y == 3)) || (($x == 19 || $x == 18) && ($y == 2 || $y == 3)) || (($x == 2 || $x == 3) && ($y == 19 || $y == 18)) || (($x == 19 || $x == 18) && ($y == 19 || $y == 18))) {
					$randTerrain = mt_rand(1,40);
					// if centre tiles,
				} else if (($x > 6 && $x < 14) && ($y > 6 && $y < 14)) {
					$randTerrain = mt_rand(1,70);
				} else {
					$randTerrain = mt_rand(1,100);
				} // else

				// 20 % chance for water tile on non-border tiles based on normal calculations
				if ($randTerrain <= 20) {
					$new_terrain = 2;
					addTileInfo($getPage_connection2,$new_continent,$x,$y,$new_terrain,$new_resources,array(0=>0),0,array(0=>0),1000);
						
					// random generate resources where applicable
					for ($v=0; $v < 4; $v++) {
						$rand1 = mt_rand(1,5); // rolls for choosing resource
						$rand2 = mt_rand(1,10); // rolls for probability

						if ($rand1 == 3) {
							// fossil fuels = 20% chance
							if ($rand2 <= 2) {
								$randQuantity = mt_rand(100,10000);
								// generate resource
								addResourceInfo($getPage_connection2,$new_continent,$x,$y,3,$randQuantity);
							} // if
						} // if
					} // for
				} else {
					$new_terrain = mt_rand(1,6);
					// 2/5 chance for grassland
					if ($new_terrain == 2) {
						// if close to equator, focus on desert and grass
						if ($y > 8 && $y < 12) {
							$randTerrain2 = mt_rand(1,10);
							if ($randTerrain2 <= 5) {
								$new_terrain = 3;
							} else {
								$new_terrain = 1;
							} // else
							// if close to poles, focus on mountains and marsh
						} else if ($y <= 5 || $y >= 15) {
							$randTerrain2 = mt_rand(1,10);
							if ($randTerrain2 <= 5) {
								$new_terrain = 5;
							} else {
								$new_terrain = 4;
							} // else
							// default to grass
						} else {
							$new_terrain = 1;
						} // else
					} // if
						
					// extra chance for grass
					if ($new_terrain == 6) {
						$new_terrain = 1;
					} // if
						
					addTileInfo($getPage_connection2,$new_continent,$x,$y,$new_terrain,$new_resources,array(0=>0),0,array(0=>0),1000);
						
					// random generate resources where applicable
						
					for ($v=0; $v < 3; $v++) {
						$rand1 = mt_rand(1,4); // rolls for choosing resource
						$rand2 = mt_rand(1,10); // rolls for probability

						if ($rand1 == 1) {
							// wood = 70% chance
							if ($rand2 <= 7) {
								$resourceTypeInfo1 = getResourceTypeInfo($getPage_connection2,1);
								$incompatible = false;
								for ($f=0; $f < count($resourceTypeInfo1["incompatibleWith"]); $f++) {
									if ($resourceTypeInfo1["incompatibleWith"][$f] == $new_terrain) {
										$incompatible = true;
										break;
									} // if
								} // for
								if ($incompatible === false) {
									$randQuantity = mt_rand(10,10000);
									// generate resource
									addResourceInfo($getPage_connection2,$new_continent,$x,$y,1,$randQuantity);
								} // if
							} // if
						} else if ($rand1 == 2) {
							// metals = 50% chance
							if ($rand2 <= 5) {
								$resourceTypeInfo1 = getResourceTypeInfo($getPage_connection2,2);
								$incompatible = false;
								for ($f=0; $f < count($resourceTypeInfo1["incompatibleWith"]); $f++) {
									if ($resourceTypeInfo1["incompatibleWith"][$f] == $new_terrain) {
										$incompatible = true;
										break;
									} // if
								} // for
								if ($incompatible === false) {
									$randQuantity = mt_rand(10,10000);
									// generate resource
									addResourceInfo($getPage_connection2,$new_continent,$x,$y,2,$randQuantity);
								} // if
							} // if
						} else if ($rand1 == 3) {
							// fossil fuels = 30% chance
							if ($rand2 <= 3) {
								$resourceTypeInfo1 = getResourceTypeInfo($getPage_connection2,1);
								$incompatible = false;
								for ($f=0; $f < count($resourceTypeInfo1["incompatibleWith"]); $f++) {
									if ($resourceTypeInfo1["incompatibleWith"][$f] == $new_terrain) {
										$incompatible = true;
										break;
									} // if
								} // for
								if ($incompatible === false) {
									$randQuantity = mt_rand(10,10000);
									// generate resource
									addResourceInfo($getPage_connection2,$new_continent,$x,$y,3,$randQuantity);
								} // if
							} // if
						} else if ($rand1 == 4) {
							// river = 60% chance
							if ($rand2 <= 6) {
								$resourceTypeInfo1 = getResourceTypeInfo($getPage_connection2,1);
								$incompatible = false;
								for ($f=0; $f < count($resourceTypeInfo1["incompatibleWith"]); $f++) {
									if ($resourceTypeInfo1["incompatibleWith"][$f] == $new_terrain) {
										$incompatible = true;
										break;
									} // if
								} // for
								if ($incompatible === false) {
									$randQuantity = 1; // always only 1 river
									// generate resource
									addResourceInfo($getPage_connection2,$new_continent,$x,$y,4,$randQuantity);
								} // if
							} // if
						} // for
					} // else
				} // else
			} // for
		} // for
	} // for
	
	return $new_continent;
} // generateContinent

/********************************
 processOffer
 attempts to trade (individual offer)
 ********************************/
function processOffer($getPage_connection2,$offerInfo1) {
	$payup = false;
	if ($offerInfo1["givingItems"][0] > 0 || $offerInfo1["receivingItems"][0] > 0) {
		// toNation: the target nation of action
		// fromNation: the source nation of action
		$toNationInfo = getNationInfo($getPage_connection2,$offerInfo1["toNation"]);
		$fromNationInfo = getNationInfo($getPage_connection2,$offerInfo1["fromNation"]);

		$notEnough = array(0=>false,1=>"");

		for ($zz=0; $zz < count($toNationInfo["goods"]); $zz++) {
			$new_to_goods = $toNationInfo["goods"][$zz];
		} // for
		for ($zz=0; $zz < count($toNationInfo["resources"]); $zz++) {
			$new_to_resources = $toNationInfo["resources"][$zz];
		} // for
		$new_to_food = $toNationInfo["food"];
		$new_to_money = $toNationInfo["money"];

		for ($zz=0; $zz < count($fromNationInfo["goods"]); $zz++) {
			$new_from_goods = $fromNationInfo["goods"][$zz];
		} // for
		for ($zz=0; $zz < count($fromNationInfo["resources"]); $zz++) {
			$new_from_resources = $fromNationInfo["resources"][$zz];
		} // for
		$new_from_food = $fromNationInfo["food"];
		$new_from_money = $fromNationInfo["money"];

		if ($offerInfo1["givingItems"][0] > 0) {
			for ($z=0; $z < count($offerInfo1["givingItems"]); $z++) {
				$new_bonus = 1;

				for ($y=0; ($y*10) > $offerInfo1["givingQuantities"][$z]; $y++) {
					$new_bonus = $y + $new_bonus;
				} // for

				// set new sell strength
				if ($offerInfo1["givingTypes"][$z] == "goods") {
					$itemInfo1 = getGoodsInfo($getPage_connection2,$offerInfo1["givingItems"][$z]);
					$new_buyStrength = $itemInfo1["buyStrength"];
					$new_sellStrength = $itemInfo1["sellStrength"] + $new_bonus;
					setGoodsInfo($getPage_connection2,$itemInfo1["id"],$itemInfo1["name"],$itemInfo1["productionRequired"],$itemInfo1["resourceTypesRequired"],$itemInfo1["resourceQuantitiesRequired"],$itemInfo1["improvementTypesRequired"],$itemInfo1["improvementQuantitiesRequired"],$itemInfo1["improvementLevelRequired"],$new_buyStrength,$new_sellStrength);

					for ($zz=0; $zz < count($fromNationInfo["goods"]); $zz++) {
						if ($offerInfo1["givingQuantities"][$z] <= $fromNationInfo["goods"][$zz]) {
							$new_to_goods[$zz] = $toNationInfo["goods"][$zz] + $offerInfo1["givingQuantities"][$z];
							$new_from_goods[$zz] = $fromNationInfo["goods"][$zz] - $offerInfo1["givingQuantities"][$z];
						} else {
							$notEnough[0] = true;
							$notEnough[1] = "offer";
							break;
						} // else
					} // for

				} else if ($offerInfo1["givingTypes"][$z] == "resources") {
					$itemInfo1 = getResourceTypeInfo($getPage_connection2,$offerInfo1["givingItems"][$z]);
					$new_buyStrength = $itemInfo1["buyStrength"];
					$new_sellStrength = $itemInfo1["sellStrength"] + $new_bonus;
					setResourceTypeInfo($getPage_connection2,$itemInfo1["id"],$itemInfo1["name"],$itemInfo1["incompatibleWith"],$itemInfo1["image"],$new_buyStrength,$new_sellStrength);

					for ($zz=0; $zz < count($fromNationInfo["resources"]); $zz++) {
						if ($offerInfo1["givingQuantities"][$z] <= $fromNationInfo["resources"][$zz]) {
							$new_to_resources[$zz] = $toNationInfo["resources"][$zz] + $offerInfo1["givingQuantities"][$z];
							$new_from_resources[$zz] = $fromNationInfo["resources"][$zz] - $offerInfo1["givingQuantities"][$z];
						} else {
							$notEnough[0] = true;
							$notEnough[1] = "offer";
							break;
						} // else
					} // for

				} else {
					if ($offerInfo1["givingQuantities"][$z] <= $fromNationInfo["money"]) {
						$new_to_money = $toNationInfo["money"] + $offerInfo1["givingQuantities"][$z];
						$new_from_money = $fromNationInfo["money"] - $offerInfo1["givingQuantities"][$z];
					} else {
						$notEnough[0] = true;
						$notEnough[1] = "offer";
						break;
					} // else
				} // else
			} // for
		} // if

		if ($offerInfo1["receivingItems"][0] > 0) {
			for ($z=0; $z < count($offerInfo1["receivingItems"]); $z++) {
				// set new buy strength
				if ($offerInfo1["receivingTypes"][$z] == "goods") {
					$itemInfo1 = getGoodsInfo($getPage_connection2,$offerInfo1["receivingItems"][$z]);
					$new_buyStrength = $itemInfo1["buyStrength"] + $new_bonus;
					$new_sellStrength = $itemInfo1["sellStrength"];
					setGoodsInfo($getPage_connection2,$itemInfo1["id"],$itemInfo1["name"],$itemInfo1["productionRequired"],$itemInfo1["resourceTypesRequired"],$itemInfo1["resourceQuantitiesRequired"],$itemInfo1["improvementTypesRequired"],$itemInfo1["improvementQuantitiesRequired"],$itemInfo1["improvementLevelRequired"],$new_buyStrength,$new_sellStrength);

					for ($zz=0; $zz < count($fromNationInfo["goods"]); $zz++) {
						if ($offerInfo1["givingQuantities"][$z] <= $fromNationInfo["goods"][$zz]) {
							$new_to_goods[$zz] = $toNationInfo["goods"][$zz] - $offerInfo1["givingQuantities"][$z];
							$new_from_goods[$zz] = $fromNationInfo["goods"][$zz] + $offerInfo1["givingQuantities"][$z];
						} else {
							$notEnough[0] = true;
							$notEnough[1] = "demand";
							break;
						} // else
					} // for

				} else if ($offerInfo1["receivingTypes"][$z] == "resources") {
					$itemInfo1 = getResourceTypeInfo($getPage_connection2,$offerInfo1["receivingItems"][$z]);
					$new_buyStrength = $itemInfo1["buyStrength"] + $new_bonus;
					$new_sellStrength = $itemInfo1["sellStrength"];
					setResourceTypeInfo($getPage_connection2,$itemInfo1["id"],$itemInfo1["name"],$itemInfo1["incompatibleWith"],$itemInfo1["image"],$new_buyStrength,$new_sellStrength);

					for ($zz=0; $zz < count($fromNationInfo["resources"]); $zz++) {
						if ($offerInfo1["givingQuantities"][$z] <= $fromNationInfo["resources"][$zz]) {
							$new_to_resources[$zz] = $toNationInfo["resources"][$zz] - $offerInfo1["givingQuantities"][$z];
							$new_from_resources[$zz] = $fromNationInfo["resources"][$zz] + $offerInfo1["givingQuantities"][$z];
						} else {
							$notEnough[0] = true;
							$notEnough[1] = "offer";
							break;
						} // else
					} // for

				} else if ($offerInfo1["receivingTypes"][$z] == "food") {
					if ($offerInfo1["receivingQuantities"][$z] <= $toNationInfo["food"]) {
						$new_to_food = $toNationInfo["food"] - $offerInfo1["receivingQuantities"][$z];
						$new_from_food = $fromNationInfo["food"] + $offerInfo1["receivingQuantities"][$z];
					} else {
						$notEnough[0] = true;
						$notEnough[1] = "demand";
						break;
					} // else

				} else {
					if ($offerInfo1["receivingQuantities"][$z] <= $toNationInfo["money"]) {
						$new_to_money = $toNationInfo["money"] - $offerInfo1["receivingQuantities"][$z];
						$new_from_money = $fromNationInfo["money"] + $offerInfo1["receivingQuantities"][$z];
					} else {
						$notEnough[0] = true;
						$notEnough[1] = "demand";
						break;
					} // else
				} // else
			} // for
		} // if

		if ($notEnough[0] === false) {
			// give items
			setNationInfo($getPage_connection2,$toNationInfo["id"],$toNationInfo["name"],$toNationInfo["home"],$toNationInfo["formal"],$toNationInfo["flag"],$toNationInfo["production"],$new_to_money,$toNationInfo["debt"],$toNationInfo["happiness"],$new_to_food,$toNationInfo["authority"],$toNationInfo["authorityChanged"],$toNationInfo["economy"],$toNationInfo["economyChanged"],$toNationInfo["organizations"],$toNationInfo["invites"],$new_to_goods,$new_to_resources,$toNationInfo["population"],$toNationInfo["strike"]);
			// receive items
			setNationInfo($getPage_connection2,$fromNationInfo["id"],$fromNationInfo["name"],$fromNationInfo["home"],$fromNationInfo["formal"],$fromNationInfo["flag"],$fromNationInfo["production"],$new_from_money,$fromNationInfo["debt"],$fromNationInfo["happiness"],$new_from_food,$fromNationInfo["authority"],$fromNationInfo["authorityChanged"],$fromNationInfo["economy"],$fromNationInfo["economyChanged"],$fromNationInfo["organizations"],$fromNationInfo["invites"],$new_from_goods,$new_from_resources,$fromNationInfo["population"],$fromNationInfo["strike"]);
			// set offer status
			setOfferInfo($getPage_connection2,$_SESSION["action_id"],$offerInfo1["fromNation"],$offerInfo1["toNation"],$offerInfo1["givingItems"],$offerInfo1["receivingItems"],$offerInfo1["givingQuantities"],$offerInfo1["receivingQuantities"],$offerInfo1["givingTypes"],$offerInfo1["receivingTypes"],$offerInfo1["turns"],$offerInfo1["counter"],1);
		} else {
			$payup = true;
		} // else
	} else {
	} // else

	// if trade is not completed in full, freeze the route for the nation at fault
	if ($payup === true) {
		// who's responsible?
		// target nation's fault
		if ($notEnough[1] == "demand") {
			for ($x=0; $x < count($toTradeInfo["offers"]); $x++) {
				$new_worth = $toTradeInfo["worth"];
				$new_worth[$x] = 0;
				setTradeInfo($getPage_connection2,$toTradeInfo["id"],$toTradeInfo["nation"],$toTradeInfo["routes"],$new_worth,$toTradeInfo["offers"],$toTradeInfo["limit"]);
			} // for
			// source nation's fault
		} else {
			for ($x=0; $x < count($fromTradeInfo["offers"]); $x++) {
				$new_worth = $fromTradeInfo["worth"];
				$new_worth[$x] = 0;
				setTradeInfo($getPage_connection2,$fromTradeInfo["id"],$fromTradeInfo["nation"],$fromTradeInfo["routes"],$new_worth,$fromTradeInfo["offers"],$fromTradeInfo["limit"]);
			} // for
		} // else
	} // if
} // processOffer

/********************************
 combat
 validation and processing for combat between units, usually called from moveUnit
 ********************************/
function combat($getPage_connection2,$continent,$xpos,$ypos,$attacker,$defender,$amphibian) {
	if ($continent >= 1 && $xpos >= 1 && $ypos >= 1 && $attacker["id"] >= 1 && $defender["id"] >= 1 && $amphibian >= 0) {
		$tileInfoB = getTileInfo($getPage_connection2,$continent,$xpos,$ypos);
		$terrainInfoB = getTerrainInfo($getPage_connection2,$tileInfoB["terrain"]);

		$unitTypeInfoAttacker = getUnitTypeInfo($getPage_connection2,$attacker["type"]);
		$unitTypeInfoDefender = getUnitTypeInfo($getPage_connection2,$defender["type"]);
		
		$attackerReconLevel = array(0=>0);
		$defenderReconLevel = array(0=>0);
		$attackerReconExp = array(0=>0);
		$defenderReconExp = array(0=>0);
		$attackerReconBonus = 0;
		$defenderReconBonus = 0;
		
		$attackerArtilleryLevel = array(0=>0);
		$defenderArtilleryLevel = array(0=>0);
		$attackerArtilleryPower = array(0=>0);
		$defenderArtilleryPower = array(0=>0);
		$attackerArtilleryBonus = 0;
		$defenderArtilleryBonus = 0;
		
		// Experience Bonus from Recon
				
		$counter = 0;
		// goes through -2 to +2 x and y positions looking for attacker recon to aid attacker
		for ($x=-2; $x < 3; $x++) {
			for ($y=-2; $y < 3; $y++) {
				$unitInfoZ = getUnitInfo($getPage_connection2,$attacker["continent"],$attacker["xpos"] + $x,$attacker["ypos"] + $y);
				if ($unitInfoZ["id"] >= 1) {
					if ($unitInfoZ["type"] == 5) {
						$unitTypeInfoZ = getUnitTypeInfo($getPage_connection2,$unitInfoZ["type"]);
						$attackerReconLevel[$counter] = $unitInfoZ["level"];
						$attackerReconExp[$counter] = 2;
						$counter++;
					} // if
				} // if
			} // for
		} // for
		
		$counter = 0;
		// goes through -2 to +2 x and y positions looking for defender recon to aid defender
		for ($x=-2; $x < 3; $x++) {
			for ($y=-2; $y < 3; $y++) {
				$unitInfoZ = getUnitInfo($getPage_connection2,$defender["continent"],$defender["xpos"] + $x,$defender["ypos"] + $y);
				if ($unitInfoZ["id"] >= 1) {
					if ($unitInfoZ["type"] == 5) {
						$unitTypeInfoZ = getUnitTypeInfo($getPage_connection2,$unitInfoZ["type"]);
						$defenderReconLevel[$counter] = $unitInfoZ["level"];
						$defenderReconExp[$counter] = 2;
						$counter++;
					} // if
				} // if
			} // for
		} // for
		if ($attackerReconLevel[0] >= 1) {
			// add recon bonus
			for ($e = 0; $e < count($attackerReconLevel); $e++) {
				$attackerReconBonus += ($attackerReconLevel[$e] * 0.25) + ($attackerReconExp[$e]);
			} // for
		} // if
		if ($defenderReconLevel[0] >= 1) {
			for ($e = 0; $e < count($defenderReconLevel); $e++) {
				$defenderReconBonus += ($defenderReconLevel[$e] * 0.25) + ($defenderReconExp[$e]);
			} // for
		} // if
		
		$attackerExp = $attacker["exp"] + $attackerReconBonus;
		$defenderExp = $defender["exp"] + $defenderReconBonus;
		
		// Power Bonus from Artillery
		
		$counter = 0;
		// goes through -2 to +2 x and y positions looking for attacker artillery to aid attacker
		for ($x=-2; $x < 3; $x++) {
			for ($y=-2; $y < 3; $y++) {
				$unitInfoZ = getUnitInfo($getPage_connection2,$attacker["continent"],$attacker["xpos"] + $x,$attacker["ypos"] + $y);
				if ($unitInfoZ["id"] >= 1) {
					if ($unitInfoZ["type"] == 4) {
						$unitTypeInfoZ = getUnitTypeInfo($getPage_connection2,$unitInfoZ["type"]);
						$attackerArtilleryLevel[$counter] = $unitInfoZ["level"];
						//$attackerArtilleryPower[$counter] = $unitTypeInfoZ["attack"];
						$attackerArtilleryPower[$counter] = 2;
						$counter++;
					} // if
				} // if
			} // for
		} // for
		
		$counter = 0;
		// goes through -2 to +2 x and y positions looking for defender artillery to aid defender
		for ($x=-2; $x < 3; $x++) {
			for ($y=-2; $y < 3; $y++) {
				$unitInfoZ = getUnitInfo($getPage_connection2,$defender["continent"],$defender["xpos"] + $x,$defender["ypos"] + $y);
				if ($unitInfoZ["id"] >= 1) {
					if ($unitInfoZ["type"] == 4) {
						$unitTypeInfoZ = getUnitTypeInfo($getPage_connection2,$unitInfoZ["type"]);
						$defenderArtilleryLevel[$counter] = $unitInfoZ["level"];
						//$defenderArtilleryPower[$counter] = $unitTypeInfoZ["attack"];
						$defenderArtilleryPower[$counter] = 2;
						$counter++;
					} // if
				} // if
			} // for
		} // for
		if ($attackerArtilleryLevel[0] >= 1) {
			// add artillery bonus
			for ($e = 0; $e < count($attackerArtilleryLevel); $e++) {
				$attackerArtilleryBonus += ($attackerArtilleryLevel[$e] * 0.25) + ($attackerArtilleryPower[$e]);
			} // for
		} // if
		if ($defenderArtilleryLevel[0] >= 1) {
			for ($e = 0; $e < count($defenderArtilleryLevel); $e++) {
				$defenderArtilleryBonus += ($defenderArtilleryLevel[$e] * 0.25) + ($defenderArtilleryPower[$e]);
			} // for
		} // if
		
		$attackerA = $attackerA + $attackerArtilleryBonus;
		$attackerD = $attackerD + $attackerArtilleryBonus;
		$defenderA = $defenderA + $defenderArtilleryBonus;
		$defenderD = $defenderD + $defenderArtilleryBonus;

		$attackerA += $unitTypeInfoAttacker["attack"] + (($attacker["level"] * 0.25) + ($attackerExp * 0.05));
		$attackerD += $unitTypeInfoAttacker["defense"] + (($attacker["level"] * 0.25) + ($attackerExp * 0.05));
		$defenderA += $unitTypeInfoDefender["attack"] + (($defender["level"] * 0.25) + ($defenderExp * 0.05));
		$defenderD += $unitTypeInfoDefender["defense"] + (($defender["level"] * 0.25) + ($defenderExp * 0.05));

		$magnitude = 0;
		$formula = 0; // 1=winning,2=tie,3=losing

		$defenderModifier = (0.01*$terrainInfoB["attackModifier"]) * $defenderD; // add terrain modifier for defense bonus

		if ($amphibian == 1) {
			$defenderModifier = (0.25) * $defenderD; // add another +25% for amphibian assaults
		} // if

		// figure out which formula to use
		if ($attackerA > ($defenderD + $defenderModifier)) {
			// figure out category of combat
			if (($attackerA - ($defenderD + $defenderModifier)) >= 4) {
				$magnitude = 5;
			} else if (($attackerA - ($defenderD + $defenderModifier)) >= 3) {
				$magnitude = 4;
			} else if (($attackerA - ($defenderD + $defenderModifier)) >= 2) {
				$magnitude = 3;
			} else if (($attackerA - ($defenderD + $defenderModifier)) >= 1) {
				$magnitude = 2;
			} else {
				$magnitude = 1;
			} // else
			$formula = 1;
		} else if ($attackerA == ($defenderD + $defenderModifier)) {
			$formula = 2;
		} else {
			// figure out category of combat
			if ((($defenderD + $defenderModifier) - $attackerA) >= 4) {
				$magnitude = 5;
			} else if ((($defenderD + $defenderModifier) - $attackerA) >= 3) {
				$magnitude = 4;
			} else if ((($defenderD + $defenderModifier) - $attackerA) >= 2) {
				$magnitude = 3;
			} else if ((($defenderD + $defenderModifier) - $attackerA) >= 1) {
				$magnitude = 2;
			} else {
				$magnitude = 1;
			} // else
			$formula = 3;
		} // else

		$rand_attackerDamage = mt_rand(1,100);

		$health_percent_attacker = 0;
		$health_percent_defender = 0;
		$new_defender_health = $defender["health"];
		$new_attacker_health = $attacker["health"];

		if ($formula == 1) {
			if ($magnitude == 5) {
				// 10% chance of receiving damage
				if ($rand_attackerDamage <= 10) {
					// attacker is damaged
					// health - 0-20% of health
					$health_percent_attacker = $attacker["health"] * (1 - ((mt_rand(8,10))*0.1) );
					$new_attacker_health = $attacker["health"] - $health_percent_attacker;
				} // if
				// defender is damaged
				// health - 80-100% of health
				$health_percent_defender = $defender["health"] * (1 - ((mt_rand(0,2))*0.1) );
				$new_defender_health = $attacker["health"] - $health_percent_defender;
					
			} else if ($magnitude == 4) {
				// 15% chance of receiving damage
				if ($rand_attackerDamage <= 15) {
					// attacker is damaged
					// health - 20-40% of health
					$health_percent_attacker = $attacker["health"] * (1 - ((mt_rand(6,8))*0.1) );
					$new_attacker_health = $attacker["health"] - $health_percent_attacker;
				} // if
				// defender is damaged
				// health - 60-80% of health
				$health_percent_defender = $defender["health"] * (1 - ((mt_rand(2,4))*0.1) );
				$new_defender_health = $attacker["health"] - $health_percent_defender;

			} else if ($magnitude == 3) {
				// 20% chance of receiving damage
				if ($rand_attackerDamage <= 20) {
					// attacker is damaged
					// health - 40-60% of health
					$health_percent_attacker = $attacker["health"] * (1 - ((mt_rand(4,6))*0.1) );
					$new_attacker_health = $attacker["health"] - $health_percent_attacker;
				} // if
				// defender is damaged
				// health - 40-60% of health
				$health_percent_defender = $defender["health"] * (1 - ((mt_rand(4,6))*0.1) );
				$new_defender_health = $attacker["health"] - $health_percent_defender;
					
			} else if ($magnitude == 2) {
				// 25% chance of receiving damage
				if ($rand_attackerDamage <= 25) {
					// attacker is damaged
					// health - 60-80% of health
					$health_percent_attacker = $attacker["health"] * (1 - ((mt_rand(2,4))*0.1) );
					$new_attacker_health = $attacker["health"] - $health_percent_attacker;
				} // if
				// defender is damaged
				// health - 20-40% of health
				$health_percent_defender = $defender["health"] * (1 - ((mt_rand(6,8))*0.1) );
				$new_defender_health = $attacker["health"] - $health_percent_defender;
					
			} else if ($magnitude == 1) {
				// 30% chance of receiving damage
				if ($rand_attackerDamage <= 30) {
					// attacker is damaged
					// health - 80-100% of health
					$health_percent_attacker = $attacker["health"] * (1 - ((mt_rand(0,2))*0.1) );
					$new_attacker_health = $attacker["health"] - $health_percent_attacker;
				} // if
				// defender is damaged
				// health - 0-20% of health
				$health_percent_defender = $defender["health"] * (1 - ((mt_rand(8,10))*0.1) );
				$new_defender_health = $attacker["health"] - $health_percent_defender;
			} // else if
			// set health
			setUnitInfo($getPage_connection2,$defender["id"],$defender["continent"],$defender["xpos"],$defender["ypos"],$new_defender_health,$defender["used"],$defender["name"],$defender["type"],$defender["owner"],$defender["level"],$defender["transport"],$defender["created"],$defender["exp"]);
			setUnitInfo($getPage_connection2,$attacker["id"],$attacker["continent"],$attacker["xpos"],$attacker["ypos"],$new_attacker_health,$attacker["used"],$attacker["name"],$attacker["type"],$attacker["owner"],$attacker["level"],$attacker["transport"],$attacker["created"],$attacker["exp"]);
		} else if ($formula == 2) {
			// 50% chance of receiving or dealing damage
			if ($rand_attackerDamage <= 50) {
				// attacker is damaged
				// health - 10-100% of health
				$health_percent_attacker = $attacker["health"] * (1 - ((mt_rand(1,10))*0.1) );
				$new_attacker_health = $attacker["health"] - $health_percent_attacker;
			} else {
				// defender is damaged
				// health - 10-100% of health
				$health_percent_defender = $defender["health"] * (1 - ((mt_rand(1,10))*0.1) );
				$new_defender_health = $attacker["health"] - $health_percent_defender;
			} // else
			// set health
			setUnitInfo($getPage_connection2,$defender["id"],$defender["continent"],$defender["xpos"],$defender["ypos"],$new_defender_health,$defender["used"],$defender["name"],$defender["type"],$defender["owner"],$defender["level"],$defender["transport"],$defender["created"],$defender["exp"]);
			setUnitInfo($getPage_connection2,$attacker["id"],$attacker["continent"],$attacker["xpos"],$attacker["ypos"],$new_attacker_health,$attacker["used"],$attacker["name"],$attacker["type"],$attacker["owner"],$attacker["level"],$attacker["transport"],$attacker["created"],$attacker["exp"]);
		} else if ($formula == 3) {
			if ($magnitude == 5) {
				// 10% chance of receiving damage
				if ($mt_rand_defenderDamage <= 10) {
					// defender is damaged
					// health - 0-20% of health
					$health_percent_defender = $defender["health"] * (1 - ((mt_rand(8,10))*0.1) );
					$new_defender_health = $attacker["health"] - $health_percent_defender;
				} // if
				// attacker is damaged
				// health - 80-100% of health
				$health_percent_attacker = $attacker["health"] * (1 - ((mt_rand(0,2))*0.1) );
				$new_attacker_health = $attacker["health"] - $health_percent_attacker;
					
			} else if ($magnitude == 4) {
				// 15% chance of receiving damage
				if ($mt_rand_defenderDamage <= 15) {
					// defender is damaged
					// health - 20-40% of health
					$health_percent_defender = $defender["health"] * (1 - ((mt_rand(6,8))*0.1) );
					$new_defender_health = $attacker["health"] - $health_percent_defender;
				} // if
				// attacker is damaged
				// health - 60-80% of health
				$health_percent_attacker = $attacker["health"] * (1 - ((mt_rand(2,4))*0.1) );
				$new_attacker_health = $attacker["health"] - $health_percent_attacker;

			} else if ($magnitude == 3) {
				// 20% chance of receiving damage
				if ($mt_rand_defenderDamage <= 20) {
					// defender is damaged
					// health - 40-60% of health
					$health_percent_defender = $defender["health"] * (1 - ((mt_rand(4,6))*0.1) );
					$new_defender_health = $attacker["health"] - $health_percent_defender;
				} // if
				// attacker is damaged
				// health - 40-60% of health
				$health_percent_attacker = $attacker["health"] * (1 - ((mt_rand(4,6))*0.1) );
				$new_attacker_health = $attacker["health"] - $health_percent_attacker;
					
			} else if ($magnitude == 2) {
				// 25% chance of receiving damage
				if ($mt_rand_defenderDamage <= 25) {
					// defender is damaged
					// health - 60-80% of health
					$health_percent_defender = $defender["health"] * (1 - ((mt_rand(2,4))*0.1) );
					$new_defender_health = $attacker["health"] - $health_percent_defender;
				} // if
				// attacker is damaged
				// health - 20-40% of health
				$health_percent_attacker = $attacker["health"] * (1 - ((mt_rand(6,8))*0.1) );
				$new_attacker_health = $attacker["health"] - $health_percent_attacker;
					
			} else if ($magnitude == 1) {
				// 30% chance of receiving damage
				if ($mt_rand_defenderDamage <= 30) {
					// defender is damaged
					// health - 80-100% of health
					$health_percent_defender = $defender["health"] * (1 - ((mt_rand(0,2))*0.1) );
					$new_defender_health = $attacker["health"] - $health_percent_defender;
				} // if
				// attacker is damaged
				// health - 0-20% of health
				$health_percent_attacker = $attacker["health"] * (1 - ((mt_rand(8,10))*0.1) );
				$new_attacker_health = $attacker["health"] - $health_percent_attacker;
			} // else if
			// set health
			setUnitInfo($getPage_connection2,$defender["id"],$defender["continent"],$defender["xpos"],$defender["ypos"],$new_defender_health,$defender["used"],$defender["name"],$defender["type"],$defender["owner"],$defender["level"],$defender["transport"],$defender["created"],$defender["exp"]);
			setUnitInfo($getPage_connection2,$attacker["id"],$attacker["continent"],$attacker["xpos"],$attacker["ypos"],$new_attacker_health,$attacker["used"],$attacker["name"],$attacker["type"],$attacker["owner"],$attacker["level"],$attacker["transport"],$attacker["created"],$attacker["exp"]);
		} else {
		} // else

		$newDefender = getUnitInfoByID($getPage_connection2,$defender["id"]);
		$newAttacker = getUnitInfoByID($getPage_connection2,$attacker["id"]);

		$defeat = "";
		$attackerWins = false;

		$new_used = $newAttacker["used"] + 1;

		// if either party is killed, remove them from map
		if ($newDefender["health"] <= 0 || $newAttacker["health"] <= 0) {
			// defender dies
			if ($newDefender["health"] <= 0) {
				$defeat = "Unit ".$newDefender["name"]." has been killed in combat!";
				$attackerWins = true;
				if ($newDefender["transport"] >= 1) {
					$transportInfo = getTransportInfo($getPage_connection2,$newDefender["transport"]);
					for ($a=0; $a < count($transportInfo); $a++) {
						deleteUnitInfo($getPage_connection2,$transportInfo["list"][$a]);
					} // for
				} // if
				deleteUnitInfo($getPage_connection2,$newDefender["id"]);

				// add experience
				$new_exp = 1 + $newAttacker["exp"];
				setUnitInfo($getPage_connection2,$newAttacker["id"],$newAttacker["continent"],$newAttacker["xpos"],$newAttacker["ypos"],$new_attacker_health,$newAttacker["used"],$newAttacker["name"],$newAttacker["type"],$newAttacker["owner"],$newAttacker["level"],$newAttacker["transport"],$newAttacker["created"],$new_exp);

				// if amphibian combat
				if ($amphibian == 1) {
					$unitInfoTransport = getUnitInfo($getPage_connection2,$_SESSION["action_id"]);
					$transportInfoTransport = getTransportInfo($getPage_connection2,$unitInfoTransport["transport"]);

					// set new transport list
					$new_transport_list = array(0=>0);
					$counterB = 0;
					for ($b=0; $b < count($transportInfoTransport); $b++) {
						if ($b != 1) {
							$new_transport_list[$counterB] = $transportInfoTransport["list"][$b];
							$counterB++;
						} // if
					} // for
					setTransportInfo($getPage_connection2,$unitInfoTransport["transport"],$new_transport_list);
				} // if

				setUnitInfo($getPage_connection2,$_SESSION["action_id"],$_SESSION["new_continent"],$_SESSION["new_xpos"],$_SESSION["new_ypos"],$newAttacker["health"],$new_used,$newAttacker["name"],$newAttacker["type"],$newAttacker["owner"],$newAttacker["level"],$newAttacker["transport"],$newAttacker["created"],$newAttacker["exp"]);
			} // if
			// attacker dies
			if ($newAttacker["health"] <= 0) {
				$defeat = "Unit ".$newAttacker["name"]." has been killed in combat!";
				$attackerWins = false;

				if ($newAttacker["transport"] >= 1) {
					$transportInfo = getTransportInfo($getPage_connection2,$newAttacker["transport"]);
					for ($a=0; $a < count($transportInfo); $a++) {
						deleteUnitInfo($getPage_connection2,$transportInfo["list"][$a]);
					} // for
				} // if
				deleteUnitInfo($getPage_connection2,$newAttacker["id"]);

				// add experience
				$new_exp = 1 + $newDefender["exp"];
				setUnitInfo($getPage_connection2,$newDefender["id"],$newDefender["continent"],$newDefender["xpos"],$newDefender["ypos"],$new_attacker_health,$newDefender["used"],$newDefender["name"],$newDefender["type"],$newDefender["owner"],$newDefender["level"],$newDefender["transport"],$newDefender["created"],$new_exp);

			} // if
			// otherwise bounce!
		} // if
		$attackerNationInfo = getNationInfo($getPage_connection2, $newAttacker["owner"]);
		$defenderNationInfo = getNationInfo($getPage_connection2, $newDefender["owner"]);
		$br1 = "<br />";
		$enteredInto = "Unit ".$newAttacker["name"]." of ".$attackerNationInfo["name"]." has entered into combat with unit ".$newDefender["name"]." of ".$defenderNationInfo["name"]." !";
		$suffering = "Unit ".$newAttacker["name"]." has suffered ".$health_percent_attacker." damage.  Unit ".$newDefender["name"]." has suffered ".$health_percent_defender." damage!";
		$_SESSION["success_message"] = $enteredInto.$br1.$suffering.$br1.$defeat;	
		$log_message = $enteredInto."  ".$suffering."  ".$defeat;
		$new_date = date("Y-m-d H:i:s");
		addCombatLogInfo($getPage_connection2, $new_date, $log_message, $attackerNationInfo["id"], $defenderNationInfo["id"]);
	} // if
} // combat

/********************************
 isitCoast
 finds out if the tile is a coastal tile
 ********************************/
function isItCoast($getPage_connection2,$tileInfoA) {
	$isCoast = false;
	$coast = array("xpos"=>0,"ypos"=>0);
	if ($tileInfoA["id"] >= 1) {
		// if current tile is land,
		if ($tileInfoA["terrain"] != 2) {
			$tileInfoN = getTileInfo($getPage_connection2,$tileInfoA["continent"],$tileInfoA["xpos"],$tileInfoA["ypos"] - 1);
			if ($tileInfoN["terrain"] == 2) {
				$coast["xpos"] = $tileInfoN["xpos"];
				$coast["ypos"] = $tileInfoN["ypos"];
			} // if
			$tileInfoNE = getTileInfo($getPage_connection2,$tileInfoA["continent"],$tileInfoA["xpos"] + 1,$tileInfoA["ypos"] - 1);
			if ($tileInfoNE["terrain"] == 2) {
				$coast["xpos"] = $tileInfoNE["xpos"];
				$coast["ypos"] = $tileInfoNE["ypos"];
			} // if
			$tileInfoE = getTileInfo($getPage_connection2,$tileInfoA["continent"],$tileInfoA["xpos"] + 1,$tileInfoA["ypos"]);
			if ($tileInfoE["terrain"] == 2) {
				$coast["xpos"] = $tileInfoE["xpos"];
				$coast["ypos"] = $tileInfoE["ypos"];
			} // if
			$tileInfoSE = getTileInfo($getPage_connection2,$tileInfoA["continent"],$tileInfoA["xpos"] + 1,$tileInfoA["ypos"] + 1);
			if ($tileInfoSE["terrain"] == 2) {
				$coast["xpos"] = $tileInfoSE["xpos"];
				$coast["ypos"] = $tileInfoSE["ypos"];
			} // if
			$tileInfoS = getTileInfo($getPage_connection2,$tileInfoA["continent"],$tileInfoA["xpos"],$tileInfoA["ypos"] + 1);
			if ($tileInfoS["terrain"] == 2) {
				$coast["xpos"] = $tileInfoS["xpos"];
				$coast["ypos"] = $tileInfoS["ypos"];
			} // if
			$tileInfoSW = getTileInfo($getPage_connection2,$tileInfoA["continent"],$tileInfoA["xpos"] - 1,$tileInfoA["ypos"] + 1);
			if ($tileInfoSW["terrain"] == 2) {
				$coast["xpos"] = $tileInfoSW["xpos"];
				$coast["ypos"] = $tileInfoSW["ypos"];
			} // if
			$tileInfoW = getTileInfo($getPage_connection2,$tileInfoA["continent"],$tileInfoA["xpos"] - 1,$tileInfoA["ypos"]);
			if ($tileInfoW["terrain"] == 2) {
				$coast["xpos"] = $tileInfoW["xpos"];
				$coast["ypos"] = $tileInfoW["ypos"];
			} // if
			$tileInfoNW = getTileInfo($getPage_connection2,$tileInfoA["continent"],$tileInfoA["xpos"] - 1,$tileInfoA["ypos"] - 1);
			if ($tileInfoNW["terrain"] == 2) {
				$coast["xpos"] = $tileInfoNW["xpos"];
				$coast["ypos"] = $tileInfoNW["ypos"];
			} // if

			if ($coast["xpos"] >= 1 && $coast["ypos"] >= 1) {
				$isCoast = true;
			} else {
				$isCoast = false;
			} // else
		} // if
	} // if

	return $isCoast;
} // isItCoast

function checkClaimsState($getPage_connection2,$tileInfoZ,$ref_nation) {
	$returnClaimInfo = 0; // state returned
	// 1 = player successfully claims
	// 2 = enemy successfully claims
	// 3 = player's, contested
	// 4 = enemy's, contested
	
	$validClaimsPresent = true;

	
	$claimInfo1 = getClaimInfo($getPage_connection2,$tileInfoZ["claims"][0]);
	
	// figure out if any claims actually exist
	if (count($tileInfoZ["claims"]) > 1) {
		if ($tileInfoZ["claims"][1] > 0) {
			$validClaimsPresent = true;
		} else {
			$validClaimsPresent = false;
		} // else
	} else {
		if ($tileInfoZ["claims"][0] > 0) {
			$validClaimsPresent = true;
		} else {
			$validClaimsPresent = false;
		} // else	
	} // else
		
	$claim_id = 0;
	$playerContesting = false;
	$claimIsContested = false;
	
	if ($validClaimsPresent === true) {
		if (count($tileInfoZ["claims"]) <= 1) {
			$claim_id = $claimInfo1["owner"];
			$playerContesting = false;
			$claimIsContested = false;
		} else {
			$currentClaim = 0;
			$greatestClaim = 0;
			// gets the greatest claim and returns the id of the greatest claimant
			for ($z=0;$z < count($tileInfoZ["claims"]); $z++) {
				$claimInfo2 = getClaimInfo($getPage_connection2,$tileInfoZ["claims"][$z]);
				$currentClaim = $claimInfo2["strength"];
				if ($z == 0) {
					$greatestClaim = $claimInfo2["strength"];
					$currentClaim = $greatestClaim;
					$claim_id = $claimInfo2["owner"];
				} else {
					// if current claim is greater than the greatest claim plus 10%, it is now the greatest claim
					if ($currentClaim > ($greatestClaim + (0.10*$greatestClaim))) {
						$greatestClaim = $currentClaim;
						$claim_id = $claimInfo2["owner"];
					} // if
				} // else
			} // for
			if (count($tileInfoZ["claims"]) >= 2) {
				$currentClaim = 0;
				for ($z=0;$z < count($tileInfoZ["claims"]); $z++) {
					$claimInfo2 = getClaimInfo($getPage_connection2,$tileInfoZ["claims"][$z]);
					$currentClaim = $claimInfo2["strength"];
					// if current claim is greater than or equal to the greatest claim minus 10%, the claim is now contested
					if ($currentClaim >= ($greatestClaim - (0.10*$greatestClaim))) {
						if ($claimInfo2["owner"] == $claim_id) {
							$claimIsContested = false;
						} else {
							$claimIsContested = true;
							if ($claimInfo2["owner"] == $ref_nation  || $claim_id == $ref_nation ) {
								$playerContesting = true;
							} // if
							$claim_id = 0;
							break;
						} // else
					} else {
						$claimIsContested = false;
					} // else
				} // for
			} // if
		} // else
			

		// if current nation claims successfully
		if ($claim_id == $ref_nation) {
			$returnClaimInfo = 1;
			// if enemy nation claims successfully
		} else if ($claim_id != $ref_nation  && $claim_id >= 1) {
			$returnClaimInfo = 2;
			// if claim is contested and player is involved
		} else if ($claimIsContested === true && $playerContesting === true) {
			$returnClaimInfo = 3;
			// if claim is contested and player is not involved
		} else if ($claimIsContested === true && $playerContesting === false) {
			$returnClaimInfo = 4;
			// default to enemy claim
		} else {
			$returnClaimInfo = 0;
		} // else
	} else {
		$returnClaimInfo = 0;
	} // else

	return $returnClaimInfo;
} // checkClaimsState

/* 
 * Figure out Percentage ratios for production of goods 
 */
function checkProductionRatios($getPage_connection2,$info) {
	$high = 0.0;
	$med = 0.0;
	$low = 0.0;
	$mh = 0.0;
	$highPerc = 0.0;
	$medPerc = 0.0;
	$lowPerc = 0.0;
	$returnArray = array(0=>0.0,1=>0.0,2=>0.0,3=>0.0,4=>0.0,5=>0.0,6=>0.0,7=>0.0);
	$mhPercUsed = false;

	for ($vv=0; $vv < count($info["goods"]); $vv++) {
		if ($info["goods"][$vv] == 1) {
			$low++;
		} else if ($info["goods"][$vv] == 2) {
			$med++;
		} else if ($info["goods"][$vv] == 3) {
			$high++;
		} else {
		} // else
	} // for

	// high priority is given 65%,
	// medium " is given 25%,
	// low " is given 10%,
	// if either high or medium does not exist, they combine to form 90%

	// if there is no high, instead get med-high
	if ($high <= 0) {
		$mh = $high + $med;
	} // if
	// if there is no med, instead get med-high
	if ($med <= 0) {
		$mh = $high + $med;
	} // if

	// if there is no high but med, or no med but high, use med-high
	if ( ($high <= 0 && $med > 0) || ($high > 0 && $med <= 0)) {
		$mhPercUsed = true;
		// if there is no low,
		if ($low <= 0) {
			$mhPerc = 100.0/$mh;
		} else {
			$mhPerc = 90.0/$mh;
			$lowPerc = 10.0/$low;
		} // else
		// if there is both high and med,
	} else if ($high > 0 && $med > 0) {
		$highPerc = 65.0/$high;
		// if there is no low,
		if ($low <= 0) {
			$medPerc = 35.0/$med;
		} else {
			$medPerc = 25.0/$med;
			$lowPerc = 10.0/$low;
		} // else
		// if neither high or med are present, use only low
	} else {
		$lowPerc = 100.0/$low;
	} // else

	for ($vq=0; $vq < count($info["goods"]); $vq++) {
		if ($info["goods"][$vq] == 1) {
			$returnArray[$vq] = $lowPerc;
		} else if ($info["goods"][$vq] == 2) {
			if ($mhPercUsed === true) {
				$returnArray[$vq] = $mhPerc;
			} else {
				$returnArray[$vq] = $medPerc;
			} // else
		} else if ($info["goods"][$vq] == 3) {
			if ($mhPercUsed === true) {
				$returnArray[$vq] = $mhPerc;
			} else {
				$returnArray[$vq] = $highPerc;
			} // else
		} else {
		} // else
	} // for

	return $returnArray;
} // checkProductionRatios
?>