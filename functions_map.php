<?php
/****************************************************************************
 * Name:        functions_map.php
 * Author:      Ben Barnes
 * Date:        2016-01-03
 * Purpose:     Map functions page
 *****************************************************************************/

/*-----------------------------------------------*/
/********************************
 Map Page Functions
 ********************************/
/*-----------------------------------------------*/

/********************************
 getGlobals_map
 get and set global variables for map page
 ********************************/
function getGlobals_map($getPage_connection2) {	
	// session: admin
	if (isset($_SESSION["admin"])) {
		$_SESSION["admin"] = cleanString($_SESSION["admin"],true);
	} else {
		$_SESSION["admin"] = 0;
	} // else
		
	if (count($_POST)) {		
		// post: current continent id
		if (isset($_POST["continent"])) {
			$_SESSION["continent_id"] = cleanString($_POST["continent"],true);
		} else {
			$_SESSION["continent_id"] = 1;
		} // else
		
		// post: current x position
		if (isset($_POST["xpos"])) {
			$_SESSION["xpos"] = cleanString($_POST["xpos"],true);
		} else {
			$_SESSION["xpos"] = 1;
		} // else
		
		// post: current y position
		if (isset($_POST["ypos"])) {
			$_SESSION["ypos"] = cleanString($_POST["ypos"],true);
		} else {
			$_SESSION["ypos"] = 1;
		} // else
		
		// post: current overlay state
		if (isset($_POST["overlay"])) {
			$_SESSION["overlay"] = cleanString($_POST["overlay"],true);
		} else {
			$_SESSION["overlay"] = "terrain";
		} // else
		
		// post: current action
		if (isset($_POST["action"])) {
			$_SESSION["action"] = cleanString($_POST["action"],true);
		} else {
			$_SESSION["action"] = "";
		} // else
		
		// post: current using ID
		if (isset($_POST["using"])) {
			$_SESSION["using"] = cleanString($_POST["using"],true);
		} else {
			$_SESSION["using"] = 0;
		} // else
		
		// post: current ID for action
		if (isset($_POST["actionid"])) {
			$_SESSION["action_id"] = cleanString($_POST["actionid"],true);
		} else {
			$_SESSION["action_id"] = 0;
		} // else
		
		// post: new x pos for movement
		if (isset($_POST["newxpos"])) {
			$_SESSION["new_xpos"] = cleanString($_POST["newxpos"],true);
		} else {
			$_SESSION["new_xpos"] = 0;
		} // else
		
		// post: new y pos for movement
		if (isset($_POST["newypos"])) {
			$_SESSION["new_ypos"] = cleanString($_POST["newypos"],true);
		} else {
			$_SESSION["new_ypos"] = 0;
		} // else
		
		// post: new continent ID for movement
		if (isset($_POST["newcontinent"])) {
			$_SESSION["new_continent"] = cleanString($_POST["newcontinent"],true);
		} else {
			$_SESSION["new_continent"] = 0;
		} // else						
	} else if (count($_GET)) {		
		// get: current continent id
		if (isset($_GET["continent"])) {
			$_SESSION["continent_id"] = cleanString($_GET["continent"],true);
		} else {
			if (!(isset($_SESSION["continent_id"]))) {
				$_SESSION["continent_id"] = 1;
			} // if
		} // else
		
		// get: current x position
		if (isset($_GET["xpos"])) {
			$_SESSION["xpos"] = cleanString($_GET["xpos"],true);
		} else {
			if (!(isset($_SESSION["xpos"]))) {
				$_SESSION["xpos"] = 1;
			} // if
		} // else
		
		// get: current y position
		if (isset($_GET["ypos"])) {
			$_SESSION["ypos"] = cleanString($_GET["ypos"],true);
		} else {
			if (!(isset($_SESSION["ypos"]))) {
				$_SESSION["ypos"] = 1;
			} // if
		} // else
		
		// get: current overlay state
		if (isset($_GET["overlay"])) {
			$_SESSION["overlay"] = cleanString($_GET["overlay"],true);
		} else {
			if (!(isset($_SESSION["overlay"]))) {
				$_SESSION["overlay"] = "terrain";
			} // if
		} // else
		
		// get: current action
		if (isset($_GET["action"])) {
			$_SESSION["action"] = cleanString($_GET["action"],true);
		} else {
			$_SESSION["action"] = "";
		} // else
		
		// get: current using ID
		if (isset($_GET["using"])) {
			$_SESSION["using"] = cleanString($_GET["using"],true);
		} else {
			$_SESSION["using"] = 0;
		} // else
		
		// get: current ID for action
		if (isset($_GET["actionid"])) {
			$_SESSION["action_id"] = cleanString($_GET["actionid"],true);
		} else {
			$_SESSION["action_id"] = 0;
		} // else
		
		// get: new x pos for movement
		if (isset($_GET["newxpos"])) {
			$_SESSION["new_xpos"] = cleanString($_GET["newxpos"],true);
		} else {
			$_SESSION["new_xpos"] = 0;
		} // else
		
		// get: new y pos for movement
		if (isset($_GET["newypos"])) {
			$_SESSION["new_ypos"] = cleanString($_GET["newypos"],true);
		} else {
			$_SESSION["new_ypos"] = 0;
		} // else
		
		// get: new continent ID for movement
		if (isset($_GET["newcontinent"])) {
			$_SESSION["new_continent"] = cleanString($_GET["newcontinent"],true);
		} else {
			$_SESSION["new_continent"] = 0;
		} // else	
	} // else if
		
	// session: nation_id
	if (isset($_SESSION["nation_id"])) {
		$_SESSION["nation_id"] = cleanString($_SESSION["nation_id"],true);
	} else {
		$_SESSION["nation_id"] = 0;
	} // else
	
	// session: userid
	if (isset($_SESSION["user_id"])) {
		$_SESSION["user_id"] = cleanString($_SESSION["user_id"],true);
	} else {
		$_SESSION["user_id"] = 0;
	} // else
	
	// get next continent id if it exists
	$next_continent = $_SESSION["continent_id"] + 1;
	$nextContinentInfo = getContinentInfo($getPage_connection2,$next_continent);
	if ($nextContinentInfo["id"] < 1) {
		$next_continent = $_SESSION["continent_id"];
	} // if
	
	// get previous continent id if able
	if ($_SESSION["continent_id"] > 1) {
		$prev_continent = $_SESSION["continent_id"] - 1;
	} else {
		$prev_continent = $_SESSION["continent_id"];
	} // else
	
	$_SESSION["next_continent"] = $next_continent;
	$_SESSION["prev_continent"] = $prev_continent;
	
	// get info
	//$_SESSION["continentInfo"] = getContinentInfo($getPage_connection2,$_SESSION["next_continent"]);
	$_SESSION["tileInfo"] = getTileInfo($getPage_connection2,$_SESSION["continent_id"],$_SESSION["xpos"],$_SESSION["ypos"]);
	//$_SESSION["terrainInfo"] = getTerrainInfo($getPage_connection2,$_SESSION["tileInfo"]["terrain"]);
	//$_SESSION["userInfo"] = getUserInfo($getPage_connection2,$_SESSION["user_id"]);
} // getGlobals_map

/********************************
 performAction_map
 calls action for map if requested and valid
 ********************************/
function performAction_map($getPage_connection2) {
	if ($_SESSION["action"] == "unit-remove") {
		removeUnit($getPage_connection2);
	} else if ($_SESSION["action"] == "unit-unload") {
		unloadUnit($getPage_connection2);
	} else if ($_SESSION["action"] == "unit-move") {
		moveUnit($getPage_connection2);
	} else if ($_SESSION["action"] == "unit-transport") {
		transportUnit($getPage_connection2);
	} else if ($_SESSION["action"] == "improvement-upgrade") {
		upgradeImprovement($getPage_connection2);
	} else if ($_SESSION["action"] == "improvement-remove") {
		removeImprovement($getPage_connection2);
	} else if ($_SESSION["action"] == "improvement-build") {
		buildImprovement($getPage_connection2);
	} else if ($_SESSION["action"] == "unit-upgrade") {
		upgradeUnit($getPage_connection2);
	} else if ($_SESSION["action"] == "unit-build") {
		buildUnit($getPage_connection2);
	} // else if
} // performAction_map

/********************************
 showContinentTitle
 visualize continent selection and title
 ********************************/
function showContinentTitle($getPage_connection2) {
	echo "        <div class=\"page-header spacing-from-menu\">\n";
	echo "\n          <div class=\"tile-header\">\n";
	echo "              <h1>Continent ".$_SESSION["continent_id"]." - ";
	if ($_SESSION["xpos"] == 0 || $_SESSION["ypos"] == 0) {
		echo " Tile Options\n";
	} else {
		$nationInfo1 = getNationInfo($getPage_connection2,$_SESSION["tileInfo"]["owner"]);
		if (strlen($nationInfo1["name"]) >= 2) {
			echo " Tile ".$_SESSION["xpos"].",".$_SESSION["ypos"]." - ".$nationInfo1["name"]."\n";
		} else {
			echo " Tile ".$_SESSION["xpos"].",".$_SESSION["ypos"]."\n";
		} // else
	} // else
	echo "            </h1>\n";
	echo "          </div>\n";
	echo "        </div>\n\n";
	echo "        <div class=\"col-md-12\">\n";
} // showContinentTitle

/********************************
 showMap
 visualize map tiles with overlay
 ********************************/
function showMap($getPage_connection2) {
	echo "          <div class=\"spacing-from-menu row\">\n";
	echo "            <div class=\"well well-lg map_well\">\n";
	echo "              <div id=\"map\">\n";
	//$a = 1;
	// go through y positions
	for ($y = 1; $y < 21; $y++ ) {
		// go through x positions
		for ($x = 1; $x < 21; $x++ ) {
			$tileInfo1 = getTileInfo($getPage_connection2,$_SESSION["continent_id"],$x,$y);
			if ($tileInfo1["id"] >= 1) {
				
				$terrainInfo1 = array("name"=>"","image"=>"");
				if ($stmt99 = $getPage_connection2->prepare("SELECT name,image FROM terrain WHERE id=? LIMIT 1")) {
					$stmt99->bind_param("i", $tileInfo1["terrain"]);
					$stmt99->execute();
					$stmt99->bind_result($r_name,$r_image);
					$stmt99->fetch();
					$terrainInfo1["name"] = $r_name;
					$terrainInfo1["image"] = $r_image;
					$stmt99->close();
				} else {
				} // else
				
				$unitInfo1 = array("id"=>0,"type"=>0);
				if ($stmt98 = $getPage_connection2->prepare("SELECT id,type FROM unitsmap WHERE continent=? AND xpos=? AND ypos=? LIMIT 1")) {
					$stmt98->bind_param("iii", $tileInfo1["continent"], $tileInfo1["xpos"], $tileInfo1["ypos"]);
					$stmt98->execute();
					$stmt98->bind_result($r_id,$r_type);
					$stmt98->fetch();
					$unitInfo1["id"] = $r_id;
					$unitInfo1["type"] = $r_type;
					$stmt98->close();
				} else {
				} // else
					
				$unitTypeInfo1 = array("id"=>0,"name"=>"","image"=>"","selected"=>"");
				if ($stmt89 = $getPage_connection2->prepare("SELECT id,name,image,selected FROM units WHERE id=? LIMIT 1")) {
					$stmt89->bind_param("i", $unitInfo1["type"]);
					$stmt89->execute();
					$stmt89->bind_result($r_id,$r_name,$r_image,$r_selected);
					$stmt89->fetch();
					$unitTypeInfo1["id"] = $r_id;
					$unitTypeInfo1["name"] = $r_name;
					$unitTypeInfo1["image"] = $r_image;
					$unitTypeInfo1["selected"] = $r_selected;
					$stmt89->close();
				} else {
				} // else
					
				if ($_SESSION["overlay"] == "terrain") {
					echo "                <div class=\"tile_container\"><a class=\"tile_link\" href=\"index.php?page=map&amp;continent=".$_SESSION["continent_id"]."&amp;xpos=".$x."&amp;ypos=".$y."&amp;overlay=".$_SESSION["overlay"]."\">";
					
					if ($unitInfo1["id"] >= 1) {
						if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
							echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."\" src=\"".$unitTypeInfo1["selected"]."\" alt=\"".$terrainInfo1["name"]."\" />";
						} else {
							echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."\" src=\"".$unitTypeInfo1["image"]."\" alt=\"".$terrainInfo1["name"]."\" />";
						} // else
					} else {
						if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
							echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."\" src=\"images/selected.png\" alt=\"".$terrainInfo1["name"]."\" />";
						} else {
							echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."\" src=\"images/blank.png\" alt=\"".$terrainInfo1["name"]."\" />";
						} // else
					} // else
					echo "</a></div>\n";

				} else if ($_SESSION["overlay"] == "control") {
					echo "                <div class=\"tile_container\"><a class=\"tile_link\" href=\"index.php?page=map&amp;continent=".$_SESSION["continent_id"]."&amp;xpos=".$x."&amp;ypos=".$y."&amp;overlay=".$_SESSION["overlay"]."\">";
					
					if ($tileInfo1["owner"] == $_SESSION["nation_id"]) {
						if ($unitInfo1["id"] >= 1) {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_friendly\" src=\"".$unitTypeInfo1["selected"]."\" alt=\"1\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_friendly\" src=\"".$unitTypeInfo1["image"]."\" alt=\"1\" />";
							} // else
						} else {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_friendly\" src=\"images/selected.png\" alt=\"0\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_friendly\" src=\"images/blank.png\" alt=\"0\" />";
							} // else
						} // else
					} else {
						if ($unitInfo1["id"] >= 1) {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"".$unitTypeInfo1["selected"]."\" alt=\"0\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"".$unitTypeInfo1["image"]."\" alt=\"0\" />";
							} // else
						} else {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"images/selected.png\" alt=\"0\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"images/blank.png\" alt=\"0\" />";
							} // else
						} // else
					} // else
					echo "</a></div>\n";

				} else if ($_SESSION["overlay"] == "claims") {
					echo "                <div class=\"tile_container\"><a class=\"tile_link\" href=\"index.php?page=map&amp;continent=".$_SESSION["continent_id"]."&amp;xpos=".$x."&amp;ypos=".$y."&amp;overlay=".$_SESSION["overlay"]."\">";
					
					$claimState = checkClaimsState($getPage_connection2, $tileInfo1, $_SESSION["nation_id"]);
							
					// if current nation claims successfully
					if ($claimState == 1) {
						if ($unitInfo1["id"] >= 1) {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_friendly\" src=\"".$unitTypeInfo1["selected"]."\" alt=\"1\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_friendly\" src=\"".$unitTypeInfo1["image"]."\" alt=\"1\" />";
							} // else
						} else {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_friendly\" src=\"images/selected.png\" alt=\"1\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_friendly\" src=\"images/blank.png\" alt=\"1\" />";
							} // else
						} // else
					// if enemy nation claims successfully
					} else if ($claimState == 2) {
						if ($unitInfo1["id"] >= 1) {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"".$unitTypeInfo1["selected"]."\" alt=\"0\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"".$unitTypeInfo1["image"]."\" alt=\"0\" />";
							} // else		
						} else {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"images/selected.png\" alt=\"0\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"images/blank.png\" alt=\"0\" />";
							} // else
						} // else				
					// if claim is contested and player is involved
					} else if ($claimState == 3) {
						if ($unitInfo1["id"] >= 1) {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_player_contested\" src=\"".$unitTypeInfo1["selected"]."\" alt=\"0\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_player_contested\" src=\"".$unitTypeInfo1["image"]."\" alt=\"0\" />";
							} // else
						} else {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"images/selected.png\" alt=\"0\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"images/blank.png\" alt=\"0\" />";
							} // else
						} // else
					// if claim is contested and player is not involved
					} else if ($claimState == 4) {
						if ($unitInfo1["id"] >= 1) {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy_contested\" src=\"".$unitTypeInfo1["selected"]."\" alt=\"0\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy_contested\" src=\"".$unitTypeInfo1["image"]."\" alt=\"0\" />";
							} // else
						} else {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"images/selected.png\" alt=\"0\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"images/blank.png\" alt=\"0\" />";
							} // else
						} // else
					// default to enemy claim
					} else {
						if ($unitInfo1["id"] >= 1) {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"".$unitTypeInfo1["selected"]."\" alt=\"0\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"".$unitTypeInfo1["image"]."\" alt=\"0\" />";
							} // else
						} else {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"images/selected.png\" alt=\"0\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"images/blank.png\" alt=\"0\" />";
							} // else
						} // else
					} // else
						
					echo "</a></div>\n";

				} else if ($_SESSION["overlay"] == "units") {
					echo "                <div class=\"tile_container\"><a class=\"tile_link\" href=\"index.php?page=map&amp;continent=".$_SESSION["continent_id"] ."&amp;xpos=".$x."&amp;ypos=".$y."&amp;overlay=".$_SESSION["overlay"]."\">";
					
					if ($unitInfo1["id"] >= 1) {
						if ($unitInfo1["owner"] == $_SESSION["nation_id"] ) {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_friendly\" src=\"".$unitTypeInfo1["selected"]."\" alt=\"".$unitTypeInfo1["name"]."\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_friendly\" src=\"".$unitTypeInfo1["image"]."\" alt=\"".$unitTypeInfo1["name"]."\" />";								
							} // else
						} else {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"".$unitTypeInfo1["selected"]."\" alt=\"".$unitTypeInfo1["name"]."\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"".$unitTypeInfo1["image"]."\" alt=\"".$unitTypeInfo1["name"]."\" />";
							} // else
						} // else
					} else {
						if ($tileInfo1["owner"] == $_SESSION["nation_id"] ) {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_friendly\" src=\"images/selected.png\" alt=\"".$terrainInfo1["name"]."\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_friendly\" src=\"images/blank.png\" alt=\"".$terrainInfo1["name"]."\" />";
							} // else
						} else {
							if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"images/selected.png\" alt=\"".$terrainInfo1["name"]."\" />";
							} else {
								echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_enemy\" src=\"images/blank.png\" alt=\"".$terrainInfo1["name"]."\" />";
							} // else
						} // else
					} // else
					echo "</a></div>\n";
					
				} else if ($_SESSION["overlay"] == "nations") {
					echo "                <div class=\"tile_container\"><a class=\"tile_link\" href=\"index.php?page=map&amp;continent=".$_SESSION["continent_id"]."&amp;xpos=".$x."&amp;ypos=".$y."&amp;overlay=".$_SESSION["overlay"]."\">";
					
					$nationsMap = array(0=>0);
					
					// associate nation with colour
					$foundNation = false;
					for ($d=0; $d < count($nationsMap); $d++) {
						if ($tileInfo1["owner"] == $nationsMap[$d]) {
							$foundNation = true;
							break;
						} // if
					} // for
					
					// add nation to array of nations present if not already found
					if ($foundNation === false) {
						$new_index_nationMaps = count($nationsMap);
						$nationsMap[$new_index_nationMaps] = $tileInfo1["owner"];
					} // if			
					
					// list nations according to colour
					for ($u=0; $u < count($nationsMap); $u++) {
						$nation_index = $u;
						if (count($nationsMap) > 20) {
							for ($m=0; ( (double)(count($nationsMap)) / ((double)$m*20) ) < 1.0 ; $m++) {
								$nation_index = $u - (20*$m);
							} // for
						} // if
						$nation1 = $nation_index;
						if ($nationsMap[$u] == $tileInfo1["owner"]) {
							if ($unitInfo1["id"] >= 1) {
								if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
									echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_nation_".$nation1."\" src=\"".$unitTypeInfo1["selected"]."\" alt=\"0\" />"; 
								} else {
									echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_nation_".$nation1."\" src=\"".$unitTypeInfo1["image"]."\" alt=\"0\" />";
								} // else
							} else {
								if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
									echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_nation_".$nation1."\" src=\"images/selected.png\" alt=\"0\" />";
								} else {
									echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."_nation_".$nation1."\" src=\"images/blank.png\" alt=\"0\" />";
								} // else								
							} // else
						} // if
					} // for						
					
					echo "</a></div>\n";
				} else {
					echo "                <div class=\"tile_container\"><a class=\"tile_link\" href=\"index.php?page=map&amp;continent=".$_SESSION["continent_id"] ."&amp;xpos=".$x."&amp;ypos=".$y."&amp;overlay=".$_SESSION["overlay"]."\">";
					
					if ($unitInfo1["id"] >= 1) {
						if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
							echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."\" src=\"".$unitTypeInfo1["selected"]."\" alt=\"".$terrainInfo1["name"]."\" />";
						} else {
							echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."\" src=\"".$unitTypeInfo1["image"]."\" alt=\"".$terrainInfo1["name"]."\" />";
						} // else
					} else {
						if ($tileInfo1["xpos"] == $_SESSION["xpos"] && $tileInfo1["ypos"] == $_SESSION["ypos"]) {
							echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."\" src=\"images/selected.png\" alt=\"".$terrainInfo1["name"]."\" />";
						} else {
							echo "<img class=\"tile_img ".strtolower($terrainInfo1["name"])."\" src=\"images/blank.png\" alt=\"".$terrainInfo1["name"]."\" />";
						} // else
					} // else
					echo "</a></div>\n";
				} // else

				//$a++;
			} // if
		} // for
		echo "                <div class=\"clear\"></div>\n";
	} // for
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";
} // showMap

/********************************
 showMapInfo
 visualize current map tile information
 ********************************/
function showMapInfo($getPage_connection2) {
	echo "          <div class=\"row\">\n";
	echo "            <div class=\"well well-lg row\">\n";

	// Command Module
	$unitInfo = getUnitInfo($getPage_connection2,$_SESSION["continent_id"],$_SESSION["xpos"],$_SESSION["ypos"]);
	echo "              <div class=\"col-sm-4\">\n";

	echo "                <div class=\"panel panel-danger\">\n";
	echo "                  <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Command your units on the present tile.\" class=\"panel-heading\">\n";
	echo "                    <h3 class=\"panel-title\">Command        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseCommand\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "                  </div>\n";
	echo "                  <div id=\"collapseCommand\" class=\"panel-body standard-text collapse in\">\n";
	if ($unitInfo["id"] >= 1) {
		$unitTypeInfo = getUnitTypeInfo($getPage_connection2,$unitInfo["type"]);
		$currentPlayer = false;
		$nationInfoX = getNationInfo($getPage_connection2,$unitInfo["owner"]);
		if ($unitInfo["owner"] == $_SESSION["nation_id"]) {
			$currentPlayer = true;
		} // if
		echo "                    <ul class=\"list-group\">\n";
		echo "                      <li class=\"list-group-item\">\n";
		echo "                        ".$unitInfo["name"]."\n";
		echo "                        <br />\n";
		echo "                        ".$unitTypeInfo["name"].", ".$unitInfo["health"]." HP\n";
		echo "                        <br />\n";
		echo "                        Level ".$unitInfo["level"]."\n";
		echo "                        <br />\n";
		echo "                        Experience: ".$unitInfo["exp"]."\n";
		echo "                        <br />\n";
		echo "                        Owned by <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$nationInfoX["id"]."\">".$nationInfoX["name"]."</a>\n";
		echo "                      </li>\n";
		if ($currentPlayer === true) {
			echo "                      <li class=\"list-group-item\">\n";
			echo "                        <div class=\"row\">\n";
			echo "                          Modify/Abilities\n                          <br />\n                          <br />\n";
			echo "                        </div>\n";
			echo "                        <div class=\"row\">\n";
			if ($unitInfo["level"] < 20) {
				echo "                          <div class=\"col-xs-3\">\n";
				echo "                            <form action=\"index.php?page=map&amp;overlay=units\" method=\"post\">\n";
				echo "                              <input type=\"hidden\" name=\"continent\" value=\"".$_SESSION["continent_id"]."\" />\n";
				echo "                              <input type=\"hidden\" name=\"xpos\" value=\"".$_SESSION["xpos"]."\" />\n";
				echo "                              <input type=\"hidden\" name=\"ypos\" value=\"".$_SESSION["ypos"]."\" />\n";
				echo "                              <input type=\"hidden\" name=\"actionid\" value=\"".$unitInfo["id"]."\" />\n";
				echo "                              <input type=\"hidden\" name=\"action\" value=\"unit-upgrade\" />\n";
				echo "                              <input type=\"hidden\" name=\"overlay\" value=\"units\" />\n";
				echo "                              <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Upgrade unit.\" value=\"map\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-sm\"><img src=\"images/buttons/btn_upgrade.png\" alt=\"Upgrade\" /></button>\n";
				echo "                            </form>\n";
				echo "                          </div>\n";
			} // if
			echo "                          <div class=\"col-xs-3\">\n";
			echo "                            <form action=\"index.php?page=map&amp;overlay=units\" method=\"post\">\n";
			echo "                              <input type=\"hidden\" name=\"continent\" value=\"".$_SESSION["continent_id"]."\" />\n";
			echo "                              <input type=\"hidden\" name=\"xpos\" value=\"".$_SESSION["xpos"]."\" />\n";
			echo "                              <input type=\"hidden\" name=\"ypos\" value=\"".$_SESSION["ypos"]."\" />\n";
			echo "                              <input type=\"hidden\" name=\"actionid\" value=\"".$unitInfo["id"]."\" />\n";
			echo "                              <input type=\"hidden\" name=\"action\" value=\"unit-remove\" />\n";
			echo "                              <input type=\"hidden\" name=\"overlay\" value=\"units\" />\n";
			echo "                              <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Remove unit.\" value=\"map\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-sm\"><img src=\"images/buttons/btn_remove.png\" alt=\"Remove\" /></button>\n";
			echo "                            </form>\n";
			echo "                          </div>\n";

			echo "                          <div class=\"col-xs-3\">\n";
			echo "                          </div>\n";

			echo "                        </div>\n";

			echo "                      </li>\n";
		} // if

		$directionsX = array(0=>-1,1=>0,2=>1,3=>-1,4=>0,5=>1,6=>-1,7=>0,8=>1);
		$directionsY = array(0=>-1,1=>-1,2=>-1,3=>0,4=>0,5=>0,6=>1,7=>1,8=>1);
		$directionsName = array(0=>"Northwest",1=>"North",2=>"Northeast",3=>"West",4=>"Hold",5=>"East",6=>"Southwest",7=>"South",8=>"Southeast");

		// unloading

		if ($currentPlayer === true) {
			$notTransport = false;
			$notUnload = false;
			// land units: can't move out of continent
			if ($unitInfo["transport"] >= 1) {
				$transportInfo = getTransportInfo($getPage_connection2,$unitInfo["transport"]);
				if ($transportInfo["id"] >= 1) {
					if (count($transportInfo["list"]) >= 2) {
						$unitInfoI = getUnitInfoByID($getPage_connection2,$transportInfo["list"][1]);
						echo "                      <li class=\"list-group-item\">\n";
						echo "                        <div class=\"row\">\n";
						$carrying = count($transportInfo["list"]) - 1;
						echo "                          Carrying ".$carrying." of 4\n                          <br />\n                          <br />\n";
						echo "                          Unload unit ".$unitInfoI["name"].": \n                          <br />\n                          <br />\n";
					} else {
						$notUnload = true;
					} // else
				} else {
					$notTransport = true;
				} // else
			} else {
				$notTransport = true;
			} // else

			// if the unit is transport,
			if ($notTransport === false) {
				// if units can be unloaded
				if ($notUnload === false) {
					$unitTypeInfoI = getUnitTypeInfo($getPage_connection2,$unitInfoI["type"]);
					if ($unitInfoI["used"] < $unitTypeInfoI["movement"]) {
						for ($hh=0; $hh < count($directionsX); $hh++) {
							$newcontinent = $_SESSION["continent_id"];
							$newxpos = $_SESSION["xpos"] + $directionsX[$hh];
							$newypos = $_SESSION["ypos"] + $directionsY[$hh];
							$tileInfoQ = getTileInfo($getPage_connection2,$newcontinent,$newxpos,$newypos);
							$grey = false;
							$unload = false;

							if ($tileInfoQ["terrain"] == 2) {
								$unload = false;
							} else {
								$unload = true;
							} // else

							if ($unload === true) {
								if ($directionsName[$hh] == "Northwest") {
									if ($_SESSION["xpos"] > 1 && $_SESSION["ypos"] > 1) {
										$grey = false;
									} else {
										$grey = true;
									} // else
								} else if ($directionsName[$hh] == "North") {
									if ($_SESSION["ypos"] > 1) {
										$grey = false;
									} else {
										$grey = true;
									} // else
								} else if ($directionsName[$hh] == "Northeast") {
									if ($_SESSION["xpos"] < 20 && $_SESSION["ypos"] > 1) {
										$grey = false;
									} else {
										$grey = true;
									} // else
								} else if ($directionsName[$hh] == "West") {
									if ($_SESSION["xpos"] > 1) {
										$grey = false;
									} else {
										$grey = true;
									} // else
								} else if ($directionsName[$hh] == "Hold") {
									$grey = true;
								} else if ($directionsName[$hh] == "East") {
									if ($_SESSION["xpos"] < 20) {
										$grey = false;
									} else {
										$grey = true;
									} // else
								} else if ($directionsName[$hh] == "Southwest") {
									if ($_SESSION["xpos"] > 1 && $_SESSION["ypos"] < 20) {
										$grey = false;
									} else {
										$grey = true;
									} // else
								} else if ($directionsName[$hh] == "South") {
									if ($_SESSION["ypos"] < 20) {
										$grey = false;
									} else {
										$grey = true;
									} // else
								} else if ($directionsName[$hh] == "Southeast") {
									if ($_SESSION["xpos"] < 20 && $_SESSION["ypos"] < 20) {
										$grey = false;
									} else {
										$grey = true;
									} // else
								} else {
									$grey = true;
								} // else
							} else {
								$grey = true;
							} // else

							if ($hh == 0) {
								echo "                        <div class=\"row\">\n";
							} else if ($hh == 3 || $hh == 6) {
								echo "                        </div>\n                        <div class=\"row\">\n";
							} // else if

							if ($grey === true) {
								echo "                        <div class=\"col-xs-3\">\n";
								echo "                          <form action=\"index.php?page=map&amp;overlay=units\" method=\"post\">\n";
								echo "                            <button disabled value=\"map\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-sm\"><img src=\"images/buttons/btn_greyed.png\" alt=\"\" /></button>\n";
								echo "                          </form>\n";
								echo "                        </div>\n";
							} else {
								echo "                        <div class=\"col-xs-3\">\n";
								echo "                          <form action=\"index.php?page=map&amp;overlay=units\" method=\"post\">\n";
								echo "                            <input type=\"hidden\" name=\"continent\" value=\"".$_SESSION["continent_id"]."\" />\n";
								echo "                            <input type=\"hidden\" name=\"xpos\" value=\"".$_SESSION["xpos"]."\" />\n";
								echo "                            <input type=\"hidden\" name=\"ypos\" value=\"".$_SESSION["ypos"]."\" />\n";
								echo "                            <input type=\"hidden\" name=\"actionid\" value=\"".$unitInfo["id"]."\" />\n";
								echo "                            <input type=\"hidden\" name=\"action\" value=\"unit-unload\" />\n";
								echo "                            <input type=\"hidden\" name=\"overlay\" value=\"units\" />\n";
								echo "                            <input type=\"hidden\" name=\"newcontinent\" value=\"".$newcontinent."\" />\n";
								echo "                            <input type=\"hidden\" name=\"newxpos\" value=\"".$newxpos."\" />\n";
								echo "                            <input type=\"hidden\" name=\"newypos\" value=\"".$newypos."\" />\n";
								echo "                            <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Unload unit.\" value=\"map\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-sm\"><img src=\"images/buttons/btn_unload_".strtolower($directionsName[$hh]).".png\" alt=\"".$directionsName[$hh]." Unload\" /></button>\n";
								echo "                          </form>\n";
								echo "                        </div>\n";
							} // else
							if ($hh == 8) {
								echo "                        </div>\n";
							} // if
						} // for
					} else {
						echo "                          Unit can move no further this turn.\n";
						echo "                        </div>\n";
					} // else
				} // if
				echo "                      </li>\n";
			} // if
		} // if

		// transport/move
		if ($currentPlayer === true) {
			echo "                      <li class=\"list-group-item\">\n";
			echo "                        <div class=\"row\">\n";
			echo "                          Move\n                          <br />\n                          <br />\n";
			echo "                        </div>\n";
			if ($unitInfo["used"] < $unitTypeInfo["movement"]) {
				// land units
				if ($unitTypeInfo["water"] == 0) {
					for ($hh=0; $hh < count($directionsX); $hh++) {
						$newcontinent = $_SESSION["continent_id"];
						$newxpos = $_SESSION["xpos"] + $directionsX[$hh];
						$newypos = $_SESSION["ypos"] + $directionsY[$hh];
						$tileInfoQ = getTileInfo($getPage_connection2,$newcontinent,$newxpos,$newypos);
						$grey = false;
						$load = false;
						$transport = false;
						$verb = "move";

						if ($tileInfoQ["terrain"] == 2) {
							$load = true;
						} else {
							$load = false;
						} // else

						if ($load === true) {
							$transport = false;
							$unitInfoTemp1 = getUnitInfo($getPage_connection2,$newcontinent,$newxpos,$newypos);
							if ($unitInfoTemp1["id"] >= 1) {
								if ($unitInfoTemp1["type"] == 10 && $unitInfoTemp1["owner"] == $_SESSION["nation_id"]) {
									if ($unitInfoTemp1["transport"] >= 1) {
										$transportInfoTemp1 = getTransportInfo($getPage_connection2,$unitInfoTemp1["transport"]);
										if ($transportInfoTemp1["id"] >= 1) {
											if (count($transportInfoTemp1["list"]) >= 1 && count($transportInfoTemp1["list"]) < 5) {
												$transport = true;
											} else {
												$transport = false;
											} // else
										} else {
											$transport = false;
										} // else
									} else {
										$transport = false;
									} // else
								} else {
									$transport = false;
								} // else
							} else {
								$transport = false;
							} // else

							if ($transport === true) {
								if ($directionsName[$hh] == "Northwest") {
									if ($_SESSION["xpos"] > 1 && $_SESSION["ypos"] > 1) {
										$grey = false;
										$verb = "transport";
									} else {
										$grey = true;
									} // else
								} else if ($directionsName[$hh] == "North") {
									if ($_SESSION["ypos"] > 1) {
										$grey = false;
										$verb = "transport";
									} else {
										$grey = true;
									} // else
								} else if ($directionsName[$hh] == "Northeast") {
									if ($_SESSION["xpos"] < 20 && $_SESSION["ypos"] > 1) {
										$grey = false;
										$verb = "transport";
									} else {
										$grey = true;
									} // else
								} else if ($directionsName[$hh] == "West") {
									if ($_SESSION["xpos"] > 1) {
										$grey = false;
										$verb = "transport";
									} else {
										$grey = true;
									} // else
								} else if ($directionsName[$hh] == "Hold") {
									$grey = true;
								} else if ($directionsName[$hh] == "East") {
									if ($_SESSION["xpos"] < 20) {
										$grey = false;
										$verb = "transport";
									} else {
										$grey = true;
									} // else
								} else if ($directionsName[$hh] == "Southwest") {
									if ($_SESSION["xpos"] > 1 && $_SESSION["ypos"] < 20) {
										$grey = false;
										$verb = "transport";
									} else {
										$grey = true;
									} // else
								} else if ($directionsName[$hh] == "South") {
									if ($_SESSION["ypos"] < 20) {
										$grey = false;
										$verb = "transport";
									} else {
										$grey = true;
									} // else
								} else if ($directionsName[$hh] == "Southeast") {
									if ($_SESSION["xpos"] < 20 && $_SESSION["ypos"] < 20) {
										$grey = false;
										$verb = "transport";
									} else {
										$grey = true;
									} // else
								} else {
									$grey = true;
								} // else
							} else {
								$grey = true;
							} // else
						} else {
							if ($directionsName[$hh] == "Northwest") {
								if ($_SESSION["xpos"] > 1 && $_SESSION["ypos"] > 1) {
									$grey = false;
								} else {
									$grey = true;
								} // else
							} else if ($directionsName[$hh] == "North") {
								if ($_SESSION["ypos"] > 1) {
									$grey = false;
								} else {
									$grey = true;
								} // else
							} else if ($directionsName[$hh] == "Northeast") {
								if ($_SESSION["xpos"] < 20 && $_SESSION["ypos"] > 1) {
									$grey = false;
								} else {
									$grey = true;
								} // else
							} else if ($directionsName[$hh] == "West") {
								if ($_SESSION["xpos"] > 1) {
									$grey = false;
								} else {
									$grey = true;
								} // else
							} else if ($directionsName[$hh] == "Hold") {
								$grey = true;
							} else if ($directionsName[$hh] == "East") {
								if ($_SESSION["xpos"] < 20) {
									$grey = false;
								} else {
									$grey = true;
								} // else
							} else if ($directionsName[$hh] == "Southwest") {
								if ($_SESSION["xpos"] > 1 && $_SESSION["ypos"] < 20) {
									$grey = false;
								} else {
									$grey = true;
								} // else
							} else if ($directionsName[$hh] == "South") {
								if ($_SESSION["ypos"] < 20) {
									$grey = false;
								} else {
									$grey = true;
								} // else
							} else if ($directionsName[$hh] == "Southeast") {
								if ($_SESSION["xpos"] < 20 && $_SESSION["ypos"] < 20) {
									$grey = false;
								} else {
									$grey = true;
								} // else
							} else {
								$grey = true;
							} // else
						} // else

						if ($hh == 0) {
							echo "                        <div class=\"row\">\n";
						} else if ($hh == 3 || $hh == 6) {
							echo "                        </div>\n                        <div class=\"row\">\n";
						} // else if

						if ($grey === true) {
							echo "                        <div class=\"col-xs-3\">\n";
							echo "                          <form action=\"index.php?page=map&amp;overlay=units\" method=\"post\">\n";
							echo "                            <button disabled value=\"map\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-sm\"><img src=\"images/buttons/btn_greyed.png\" alt=\"\" /></button>\n";
							echo "                          </form>\n";
							echo "                        </div>\n\n";
						} else {
							echo "                        <div class=\"col-xs-3\">\n";
							echo "                          <form action=\"index.php?page=map&amp;overlay=units\" method=\"post\">\n";
							echo "                            <input type=\"hidden\" name=\"continent\" value=\"".$_SESSION["continent_id"]."\" />\n";
							echo "                            <input type=\"hidden\" name=\"xpos\" value=\"".$_SESSION["xpos"]."\" />\n";
							echo "                            <input type=\"hidden\" name=\"ypos\" value=\"".$_SESSION["ypos"]."\" />\n";
							echo "                            <input type=\"hidden\" name=\"actionid\" value=\"".$unitInfo["id"]."\" />\n";
							echo "                            <input type=\"hidden\" name=\"action\" value=\"unit-".$verb."\" />\n";
							echo "                            <input type=\"hidden\" name=\"overlay\" value=\"units\" />\n";
							echo "                            <input type=\"hidden\" name=\"newcontinent\" value=\"".$newcontinent."\" />\n";
							echo "                            <input type=\"hidden\" name=\"newxpos\" value=\"".$newxpos."\" />\n";
							echo "                            <input type=\"hidden\" name=\"newypos\" value=\"".$newypos."\" />\n";
							if ($verb == "transport") {
								echo "                            <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Transport ".$directionsName[$hh].".\" value=\"map\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-sm\"><img src=\"images/buttons/btn_unload_".strtolower($directionsName[$hh]).".png\" alt=\"".$directionsName[$hh]." Transport\" /></button>\n";
							} else {
								echo "                            <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Move ".$directionsName[$hh].".\" value=\"map\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-sm\"><img src=\"images/buttons/btn_".strtolower($directionsName[$hh]).".png\" alt=\"".$directionsName[$hh]." Move\" /></button>\n";
							} // else
							echo "                          </form>\n";
							echo "                        </div>\n";
						} // else

						if ($hh == 8) {
							echo "                        </div>\n\n";
						} // if
					} // for
				// water units
				} else {
					for ($hh=0; $hh < count($directionsX); $hh++) {
						$newcontinent = $_SESSION["continent_id"];
						$newxpos = $_SESSION["xpos"] + $directionsX[$hh];
						$newypos = $_SESSION["ypos"] + $directionsY[$hh];
						$tileInfoQ = getTileInfo($getPage_connection2,$newcontinent,$newxpos,$newypos);
						$grey = false;
						$isWater = false;

						$coastBool = isItCoast($getPage_connection2,$tileInfoQ);
						if ($coastBool === true) {
							$coastCheck = true;
						} else {
							$coastCheck = false;
						} // else

						if ($tileInfoQ["terrain"] == 2) {
							$isWater = true;
						} else {
							$isWater = false;
						} // else

						if ($isWater === true || $coastCheck === true) {
							if ($directionsName[$hh] == "Northwest") {
								if ($_SESSION["xpos"] > 1 && $_SESSION["ypos"] > 1) {
									$grey = false;
								} else {
									if ($_SESSION["continent_id"] >= 2) {
										$newcontinent = $_SESSION["continent_id"] - 1;
									} else {
										$newcontinent = $_SESSION["continent_id"];
									} // else
									$grey = false;
								} // else
							} else if ($directionsName[$hh] == "North") {
								if ($_SESSION["ypos"] > 1) {
									$grey = false;
								} else {
									if ($_SESSION["continent_id"] >= 2) {
										$newcontinent = $_SESSION["continent_id"] - 1;
									} else {
										$newcontinent = $_SESSION["continent_id"];
									} // else
									$grey = false;
								} // else
							} else if ($directionsName[$hh] == "Northeast") {
								if ($_SESSION["xpos"] < 20 && $_SESSION["ypos"] > 1) {
									$grey = false;
								} else {
									$nextContinent = getContinentInfo($getPage_connection2,$_SESSION["continent_id"]+1);
									if ($nextContinent["id"] >= 1) {
										$newcontinent = $_SESSION["continent_id"] + 1;
									} else {
										$newcontinent = $_SESSION["continent_id"];
									} // else
									$grey = false;
								} // else
							} else if ($directionsName[$hh] == "West") {
								if ($_SESSION["xpos"] > 1) {
									$grey = false;
								} else {
									if ($_SESSION["continent_id"] >= 2) {
										$newcontinent = $_SESSION["continent_id"] - 1;
									} else {
										$newcontinent = $_SESSION["continent_id"];
									} // else
									$grey = false;
								} // else
							} else if ($directionsName[$hh] == "Hold") {
								$grey = true;
							} else if ($directionsName[$hh] == "East") {
								if ($_SESSION["xpos"] > 20) {
									$grey = false;
								} else {
									$nextContinent = getContinentInfo($getPage_connection2,$_SESSION["continent_id"]+1);
									if ($nextContinent["id"] >= 1) {
										$newcontinent = $_SESSION["continent_id"] + 1;
									} else {
										$newcontinent = $_SESSION["continent_id"];
									} // else
									$grey = false;
								} // else
							} else if ($directionsName[$hh] == "Southwest") {
								if ($_SESSION["xpos"] > 1 && $_SESSION["ypos"] < 20) {
									$grey = false;
								} else {
									if ($_SESSION["continent_id"] >= 2) {
										$newcontinent = $_SESSION["continent_id"] - 1;
									} else {
										$newcontinent = $_SESSION["continent_id"];
									} // else
									$grey = false;
								} // else
							} else if ($directionsName[$hh] == "South") {
								if ($_SESSION["xpos"] > 20) {
									$grey = false;
								} else {
									$nextContinent = getContinentInfo($getPage_connection2,$_SESSION["continent_id"]+1);
									if ($nextContinent["id"] >= 1) {
										$newcontinent = $_SESSION["continent_id"] + 1;
									} else {
										$newcontinent = $_SESSION["continent_id"];
									} // else
									$grey = false;
								} // else
							} else if ($directionsName[$hh] == "Southeast") {
								if ($_SESSION["xpos"] > 20 && $_SESSION["ypos"] < 20) {
									$grey = false;
								} else {
									$nextContinent = getContinentInfo($getPage_connection2,$_SESSION["continent_id"]+1);
									if ($nextContinent["id"] >= 1) {
										$newcontinent = $_SESSION["continent_id"] + 1;
									} else {
										$newcontinent = $_SESSION["continent_id"];
									} // else
									$grey = false;
								} // else
							} else {
								$grey = true;
							} // else
						} else {
							$grey = true;
						} // else

						if ($hh == 0) {
							echo "                        <div class=\"row\">\n";
						} else if ($hh == 3 || $hh == 6) {
							echo "                      </div>\n                      <div class=\"row\">\n";
						} // else if

						if ($grey === true) {
							echo "                        <div class=\"col-xs-3\">\n";
							echo "                          <form action=\"index.php?page=map&amp;overlay=units\" method=\"post\">\n";
							echo "                            <button disabled value=\"map\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-sm\"><img src=\"images/buttons/btn_greyed.png\" alt=\"\" /></button>\n";
							echo "                          </form>\n";
							echo "                        </div>\n\n";
						} else {
							echo "                        <div class=\"col-xs-3\">\n";
							echo "                          <form action=\"index.php?page=map&amp;overlay=units\" method=\"post\">\n";
							echo "                            <input type=\"hidden\" name=\"continent\" value=\"".$_SESSION["continent_id"]."\" />\n";
							echo "                            <input type=\"hidden\" name=\"xpos\" value=\"".$_SESSION["xpos"]."\" />\n";
							echo "                            <input type=\"hidden\" name=\"ypos\" value=\"".$_SESSION["ypos"]."\" />\n";
							echo "                            <input type=\"hidden\" name=\"actionid\" value=\"".$unitInfo["id"]."\" />\n";
							echo "                            <input type=\"hidden\" name=\"action\" value=\"unit-move\" />\n";
							echo "                            <input type=\"hidden\" name=\"overlay\" value=\"units\" />\n";
							echo "                            <input type=\"hidden\" name=\"newcontinent\" value=\"".$newcontinent."\" />\n";
							echo "                            <input type=\"hidden\" name=\"newxpos\" value=\"".$newxpos."\" />\n";
							echo "                            <input type=\"hidden\" name=\"newypos\" value=\"".$newypos."\" />\n";
							echo "                            <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Move ".$directionsName[$hh].".\" value=\"map\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-sm\"><img src=\"images/buttons/btn_".strtolower($directionsName[$hh]).".png\" alt=\"".$directionsName[$hh]." Move\" /></button>\n";
							echo "                          </form>\n";
							echo "                        </div>\n\n";
						} // else

						if ($hh == 8) {
							echo "                        </div>\n\n";
						} // if
					} // for
				} // else
			} else {
				echo "                        Unit can move no further this turn.\n";
			} // else
			echo "                      </li>\n";
			echo "                    </ul>\n";
		} // if
	} // if
	echo "                  </div>\n";
	echo "                </div>\n";
	echo "              </div><!-- /.col-sm-4 -->\n\n";

	// Improve Module
	echo "              <div class=\"col-sm-4\">\n";
	echo "                <div class=\"panel panel-success\">\n";
	echo "                  <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Add and modify your improvements on the present tile.\" class=\"panel-heading\">\n";
	echo "                    <h3 class=\"panel-title\">Improve        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseImprove\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "                  </div>\n";
	echo "                  <div id=\"collapseImprove\" class=\"panel-body standard-text collapse in\">\n";
	for ($q = 0; $q < count($_SESSION["tileInfo"]["improvements"]); $q++) {
		$improvementInfo = getImprovementInfo($getPage_connection2,$_SESSION["tileInfo"]["improvements"][$q]);
		if ($improvementInfo["id"] >= 1) {
			echo "                    <ul class=\"list-group\">\n";
			$improvementTypeInfo = getImprovementTypeInfo($getPage_connection2,$improvementInfo["type"]);
			$currentPlayer = false;
			echo "                      <li class=\"list-group-item\">\n";
			echo "                        ".$improvementTypeInfo["name"]."\n";
			echo "                        <br />\n";
			echo "                        Level ".$improvementInfo["level"]."\n";
			echo "                        <br />\n";
			if ($improvementInfo["usingResources"][0] > 0) {
				echo "                        Using Resources: ";
				for ($u=0; $u < count($improvementInfo["usingResources"]); $u++) {
					if ($improvementInfo["usingResources"][$u] > 0) {
						if ($u > 0) {
							echo ", ";
						} // if
						$resourceInfoQ = getResourceInfo($getPage_connection2,$improvementInfo["usingResources"][$u]);
						$resourceTypeInfoQ = getResourceTypeInfo($getPage_connection2, $resourceInfoQ["type"]);
						echo $resourceTypeInfoQ["name"];
					} // if
				} // for
			} // if								
			echo "\n                        <br />\n";			
			echo "                        Owned by ";
			for ($z = 0; $z < count($improvementInfo["owners"]); $z++) {
				$nationInfoY = getNationInfo($getPage_connection2,$improvementInfo["owners"][$z]);
				if ($z >= 1) {
					echo ", <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$nationInfoY["id"]."\">".$nationInfoY["name"]."</a>";
				} else {
					echo "<a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$nationInfoY["id"]."\">".$nationInfoY["name"]."</a>";
				} // else
				if ($improvementInfo["owners"][$z] == $_SESSION["nation_id"]) {
					$currentPlayer = true;
				} // if
			} // for
			echo "\n                      </li>\n";
			if ($currentPlayer === true) {
				echo "                      <li class=\"list-group-item\">\n";
				echo "                        <div class=\"row\">\n";
				echo "                          Modify/Abilities\n                          <br />\n                          <br />\n";
				echo "                        </div>\n";
				echo "                        <div class=\"row\">\n";
				if ($improvementInfo["level"] < 20) {
					echo "                          <div class=\"col-xs-3\">\n";
					echo "                            <form action=\"index.php?page=map&amp;overlay=terrain\" method=\"post\">\n";
					echo "                              <input type=\"hidden\" name=\"continent\" value=\"".$_SESSION["continent_id"]."\" />\n";
					echo "                              <input type=\"hidden\" name=\"xpos\" value=\"".$_SESSION["xpos"]."\" />\n";
					echo "                              <input type=\"hidden\" name=\"ypos\" value=\"".$_SESSION["ypos"]."\" />\n";
					echo "                              <input type=\"hidden\" name=\"actionid\" value=\"".$improvementInfo["id"]."\" />\n";
					echo "                              <input type=\"hidden\" name=\"action\" value=\"improvement-upgrade\" />\n";
					echo "                              <input type=\"hidden\" name=\"overlay\" value=\"control\" />\n";
					echo "                              <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Upgrade improvement.\" value=\"map\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-sm\"><img src=\"images/buttons/btn_upgrade.png\" alt=\"Upgrade\" /></button>\n";
					echo "                            </form>\n";
					echo "                          </div>\n";
				} // if
				echo "                          <div class=\"col-xs-3\">\n";
				echo "                            <form action=\"index.php?page=map&amp;overlay=terrain\" method=\"post\">\n";
				echo "                              <input type=\"hidden\" name=\"continent\" value=\"".$_SESSION["continent_id"]."\" />\n";
				echo "                              <input type=\"hidden\" name=\"xpos\" value=\"".$_SESSION["xpos"]."\" />\n";
				echo "                              <input type=\"hidden\" name=\"ypos\" value=\"".$_SESSION["ypos"]."\" />\n";
				echo "                              <input type=\"hidden\" name=\"actionid\" value=\"".$improvementInfo["id"]."\" />\n";
				echo "                              <input type=\"hidden\" name=\"action\" value=\"improvement-remove\" />\n";
				echo "                              <input type=\"hidden\" name=\"overlay\" value=\"control\" />\n";
				echo "                              <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Remove improvement.\" value=\"map\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-sm\"><img src=\"images/buttons/btn_remove.png\" alt=\"Remove\" /></button>\n";
				echo "                            </form>\n";
				echo "                          </div>\n";
				echo "                        </div>\n";
				echo "                      </li>\n";
				// if it is a depot, allow construction of military units
				if ($improvementInfo["type"] == 5) {
					echo "                    <li class=\"list-group-item\">\n";
					echo "                      <div class=\"row\">\n";
					echo "                        Train\n                        <br />\n                        <br />\n";
					echo "                      </div>\n";
					$nationInfoZ = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);

					$wrapCounter = 0;
					if ($stmt = $getPage_connection2->prepare("SELECT id FROM units ORDER BY id ASC")) {
						$stmt->execute();
						$stmt->bind_result($r_result);
						$stmt->store_result();

						while ($stmt->fetch()) {
							$next_unitTypes = $r_result;
							$unitTypeInfo = getUnitTypeInfo($getPage_connection2,$next_unitTypes);
	
							if ($unitTypeInfo["water"] == 1) {
								$coastBool = isItCoast($getPage_connection2,$_SESSION["tileInfo"]);
								if ($coastBool === true) {
									$coastCheck = true;
								} else {
									$coastCheck = false;
								} // else
							} else {
								$coastCheck = true;
							} // else
	
							if ($coastCheck === true) {
								if ($unitTypeInfo["baseCost"] <= $nationInfoZ["money"]) {
									$notEnough = false;
									for ($zz=0; $zz < count($unitTypeInfo["goodsRequired"]); $zz++) {
										if ($unitTypeInfo["goodsRequired"][$zz] > $nationInfoZ["goods"][$zz]) {
											$notEnough = true;
											break;
										} // if
									} // for
									if ($notEnough === false) {
										$wrapCounter++;
										if ($wrapCounter == 1) {
											echo "                        <div class=\"row\">\n";
										} // if
										echo "                          <div class=\"col-xs-3\">\n";
										echo "                            <form action=\"index.php?page=map&amp;overlay=units\" method=\"post\">\n";
										echo "                              <input type=\"hidden\" name=\"continent\" value=\"".$_SESSION["continent_id"]."\" />\n";
										echo "                              <input type=\"hidden\" name=\"xpos\" value=\"".$_SESSION["xpos"]."\" />\n";
										echo "                              <input type=\"hidden\" name=\"ypos\" value=\"".$_SESSION["ypos"]."\" />\n";
										echo "                              <input type=\"hidden\" name=\"actionid\" value=\"".$unitTypeInfo["id"]."\" />\n";
										echo "                              <input type=\"hidden\" name=\"action\" value=\"unit-build\" />\n";
										echo "                              <input type=\"hidden\" name=\"overlay\" value=\"units\" />\n";
										echo "                              <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Train ".$unitTypeInfo["name"].".\" value=\"map\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-sm\"><img src=\"".$unitTypeInfo["image"]."\" alt=\"Train ".$unitTypeInfo["name"]."\" /></button>\n";
										echo "                            </form>\n";
										echo "                          </div>\n";
										if ($wrapCounter == 3) {
											$wrapCounter = 0;
											echo "                        </div>\n";
										} // if
									} // if
								} // if
							} // if
	
							if ($next_unitTypes < 1 && $wrapCounter != 0) {
								echo "                      </div>\n";
							} // if
						} // while

						$stmt->close();
					} else {
					} // else
						
					echo "                      </li>\n";
				} // if
			} // if
			echo "                    </ul>\n";
		} // if
	} // for

	$currentPlayer = false;
	if ($_SESSION["tileInfo"]["owner"] == $_SESSION["nation_id"]) {
		$currentPlayer = true;
	} // if
	if ($currentPlayer === true) {
		echo "                    <ul class=\"list-group\">\n";
		echo "                      <li class=\"list-group-item\">\n";
		echo "                        Build\n                        <br />\n                        <br />\n";
		$availableResources = array(0=>0);
		$counter = 1;
		// loop tile's resources
		for ($q = 0; $q < count($_SESSION["tileInfo"]["resources"]); $q++) {
			$resourceIsUsed = false;
			$resourceInfo = getResourceInfo($getPage_connection2,$_SESSION["tileInfo"]["resources"][$q]);
			// only proceed if capacity exists above 0
			if ($resourceInfo["capacity"] >= 1) {
				$resourceTypeInfo = getResourceTypeInfo($getPage_connection2,$resourceInfo["type"]);
				// loop tile's improvements
				for ($a = 0; $a < count($_SESSION["tileInfo"]["improvements"]); $a++) {
					$improvementInfo = getImprovementInfo($getPage_connection2,$_SESSION["tileInfo"]["improvements"][$a]);
					// if tile improvement is currently using resource, check
					for ($d=0; $d < count($improvementInfo["usingResources"]); $d++) {
						if ($improvementInfo["usingResources"][$d] == $resourceInfo["id"]) {
							$resourceIsUsed = true;
							break;
						} else {
							$resourceIsUsed = false;
						} // else
					} // for
					if ($resourceIsUsed === true) {
						break;
					} // if
				} // for
			} else {
				$resourceIsUsed = true;
			} // else
			// set available resources not used and with capacity left
			if ($resourceIsUsed === false) {
				$availableResources[$counter] = $resourceInfo;
				$counter++;
			} // if
		} // for

		// loop improvement types

		$wrapCounter = 0;
		if ($stmt = $getPage_connection2->prepare("SELECT id FROM improvements ORDER BY id ASC")) {
			$stmt->execute();
			$stmt->bind_result($r_result);
			$stmt->store_result();

			while ($stmt->fetch()) {
				$next_improvementTypes = $r_result;
				$improvementTypeInfo1 = getImprovementTypeInfo($getPage_connection2,$next_improvementTypes);
				$addToCounter = false;
				$terrainIsValid = false;
				// loop terrain requirements
				for ($b = 0; $b < count($improvementTypeInfo1["terrainTypeRequired"]); $b++) {
					// if tile terrain type is valid, check
					if ($improvementTypeInfo1["terrainTypeRequired"][$b] == $_SESSION["tileInfo"]["terrain"]) {
						$terrainIsValid = true;
						break;
					} else {
						$terrainIsValid = false;
					} // else
				} // for
	
				// if terrain is valid, check for resource requirements
				if ($terrainIsValid === true) {
					// if no requirements just post it, otherwise proceed to loop
					if ($improvementTypeInfo1["resourcesRequired"][0] == 0) {
						if (count($_SESSION["tileInfo"]["improvements"]) < 5) {
							// capital: only 1 can be built, check for home continent, if one is set then capital exists
							if ($improvementTypeInfo1["id"] == 1) {
								$nationInfoC = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
								if ($nationInfoC["home"] == 0) {
									$wrapCounter++;
									if ($wrapCounter == 1) {
										echo "                        <div class=\"row\">\n";
									} // if
									echo "                          <div class=\"col-xs-3\">\n";
									echo "                            <form action=\"index.php?page=map&amp;overlay=units\" method=\"post\">\n";
									echo "                              <input type=\"hidden\" name=\"continent\" value=\"".$_SESSION["continent_id"]."\" />\n";
									echo "                              <input type=\"hidden\" name=\"xpos\" value=\"".$_SESSION["xpos"]."\" />\n";
									echo "                              <input type=\"hidden\" name=\"ypos\" value=\"".$_SESSION["ypos"]."\" />\n";
									echo "                              <input type=\"hidden\" name=\"actionid\" value=\"".$next_improvementTypes."\" />\n";
									echo "                              <input type=\"hidden\" name=\"action\" value=\"improvement-build\" />\n";
									echo "                              <input type=\"hidden\" name=\"overlay\" value=\"units\" />\n";
									echo "                              <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Build ".$improvementTypeInfo1["name"].".\" value=\"map\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-sm\"><img src=\"".$improvementTypeInfo1["image"]."\" alt=\"Build ".$improvementTypeInfo1["name"]."\" /></button>\n";
									echo "                            </form>\n";
									echo "                          </div>\n";
									if ($wrapCounter == 3) {
										$wrapCounter = 0;
										echo "                        </div>\n";
									} // if									
								} // if															
							} else {
								$wrapCounter++;
								if ($wrapCounter == 1) {
									echo "                        <div class=\"row\">\n";
								} // if
								echo "                          <div class=\"col-xs-3\">\n";
								echo "                            <form action=\"index.php?page=map&amp;overlay=units\" method=\"post\">\n";
								echo "                              <input type=\"hidden\" name=\"continent\" value=\"".$_SESSION["continent_id"]."\" />\n";
								echo "                              <input type=\"hidden\" name=\"xpos\" value=\"".$_SESSION["xpos"]."\" />\n";
								echo "                              <input type=\"hidden\" name=\"ypos\" value=\"".$_SESSION["ypos"]."\" />\n";
								echo "                              <input type=\"hidden\" name=\"actionid\" value=\"".$next_improvementTypes."\" />\n";
								echo "                              <input type=\"hidden\" name=\"action\" value=\"improvement-build\" />\n";
								echo "                              <input type=\"hidden\" name=\"overlay\" value=\"units\" />\n";
								echo "                              <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Build ".$improvementTypeInfo1["name"].".\" value=\"map\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-sm\"><img src=\"".$improvementTypeInfo1["image"]."\" alt=\"Build ".$improvementTypeInfo1["name"]."\" /></button>\n";
								echo "                            </form>\n";
								echo "                          </div>\n";
								if ($wrapCounter == 3) {
									$wrapCounter = 0;
									echo "                        </div>\n";
								} // if
							} // else
						} // if
					} else {
						$checkResource = array(0=>false);
						// loop resource requirements
						for ($t = 0; $t < count($improvementTypeInfo1["resourcesRequired"]); $t++) {
							// loop available resources
							for ($z = 0; $z < count($availableResources); $z++) {
								// if tile terrain type is valid, check
								if ($improvementTypeInfo1["resourcesRequired"][$t] == $availableResources[$z]["type"]) {
									$checkResource[$t] = true;
									break;
								} else {
								} // else
							} // for
						} // for
	
						$illegal = false;
						for ($a=0; $a < count($improvementTypeInfo1["resourcesRequired"]); $a++) {
							if (isset($checkResource[$a])) {
								if ($checkResource[$a] === false) {
									$illegal = true;
									break;
								} // if
							} else {
								$illegal = true;
								break;
							} // else
						} // for
	
						if ($illegal === false) {
							if (count($_SESSION["tileInfo"]["improvements"]) < 5) {
								$wrapCounter++;
								if ($wrapCounter == 1) {
									echo "                        <div class=\"row\">\n";
								} // if
								echo "                          <div class=\"col-xs-3\">\n";
								echo "                            <form action=\"index.php?page=map&amp;overlay=units\" method=\"post\">\n";
								echo "                              <input type=\"hidden\" name=\"continent\" value=\"".$_SESSION["continent_id"]."\" />\n";
								echo "                              <input type=\"hidden\" name=\"xpos\" value=\"".$_SESSION["xpos"]."\" />\n";
								echo "                              <input type=\"hidden\" name=\"ypos\" value=\"".$_SESSION["ypos"]."\" />\n";
								echo "                              <input type=\"hidden\" name=\"actionid\" value=\"".$next_improvementTypes."\" />\n";
								echo "                              <input type=\"hidden\" name=\"action\" value=\"improvement-build\" />\n";
								echo "                              <input type=\"hidden\" name=\"overlay\" value=\"units\" />\n";
								echo "                              <button onclick=\"loadButton(this)\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Build ".$improvementTypeInfo1["name"].".\" value=\"map\" name=\"page\" type=\"submit\" class=\"btn btn-primary btn-sm\"><img src=\"".$improvementTypeInfo1["image"]."\" alt=\"Build ".$improvementTypeInfo1["name"]."\" /></button>\n";
								echo "                            </form>\n";
								echo "                          </div>\n";
								if ($wrapCounter == 3) {
									$wrapCounter = 0;
									echo "                        </div>\n";
								} // if
							} // if
						} // if
					} // else
				} // if
	
				if ($next_improvementTypes < 1 && $wrapCounter != 0) {
					echo "                        </div>\n";
				} // if
			} // while
			$stmt->close();
		} else {
		} // else
		echo "                      </li>\n";
		echo "                    </ul>\n";
	} // if
	echo "                  </div>\n";
	echo "                </div>\n";
	echo "              </div><!-- /.col-sm-4 -->\n\n";

	// Info Module
	echo "              <div class=\"col-sm-4\">\n";
	echo "                <div class=\"panel panel-info\">\n";
	echo "                  <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Check out more information about the present tile.\" class=\"panel-heading\">\n";
	echo "                    <h3 class=\"panel-title\">Info        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseInfo\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "                  </div>\n";
	echo "                  <div id=\"collapseInfo\" class=\"panel-body standard-text collapse in\">\n";
	$nationInfoN = getNationInfo($getPage_connection2,$_SESSION["tileInfo"]["owner"]);
	echo "                    <ul class=\"list-group\">\n";
	echo "                      Overview:\n";
	echo "                      <li class=\"list-group-item\">\n";
	echo "                        Continent: ".$_SESSION["continent_id"]."\n";
	echo "                      </li>\n";
	echo "                      <li class=\"list-group-item\">\n";
	echo "                        Position: ".$_SESSION["xpos"].", ".$_SESSION["ypos"]."\n";
	echo "                      </li>\n";
	echo "                      <li class=\"list-group-item\">\n";
	echo "                        Population: ".$_SESSION["tileInfo"]["population"]."\n";
	echo "                      </li>\n";
	echo "                      <li class=\"list-group-item\">\n";
	if (isset($nationInfoN["name"])) {
		if (strlen($nationInfoN["name"]) >= 1) {
			echo "                        Owner: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$nationInfoN["id"]."\">".$nationInfoN["name"]."</a>\n";
		} else {
			echo "                        Owner: None\n";
		} // else
	} else {
		echo "                        Owner: None\n";
	} // else
	echo "                      </li>\n";
	echo "                    </ul>\n";
	echo "                    <ul class=\"list-group\">\n";
	$hasResourceInfo = false;
	for ($q = 0; $q < count($_SESSION["tileInfo"]["resources"]); $q++) {
		$resourceInfo = getResourceInfo($getPage_connection2,$_SESSION["tileInfo"]["resources"][$q]);
		if ($resourceInfo["id"] >= 1) {
			if ($hasResourceInfo === false) {
				echo "                      Resources:\n";
				$hasResourceInfo = true;
			} // if
			$resourceTypeInfo = getResourceTypeInfo($getPage_connection2,$resourceInfo["type"]);
			echo "                      <li class=\"list-group-item\">\n";
			echo "                        ".$resourceTypeInfo["name"].", Capacity: ".$resourceInfo["capacity"]."\n";
			echo "                      </li>\n";
		} // if
	} // for
	echo "                    </ul>\n";
	echo "                    <ul class=\"list-group\">\n";
	$hasClaimInfo = false;
	for ($q = 0; $q < count($_SESSION["tileInfo"]["claims"]); $q++) {
		$claimInfoV = getClaimInfo($getPage_connection2,$_SESSION["tileInfo"]["claims"][$q]);
		if ($claimInfoV["id"] >= 1) {
			if ($hasClaimInfo === false) {
				echo "                      Claims:\n";
				$hasClaimInfo = true;
			} // if
			$nationInfoV = getNationInfo($getPage_connection2, $claimInfoV["owner"]);
			echo "                      <li class=\"list-group-item\">\n";
			echo "                        ".$nationInfoV["name"]." Claim: ".$claimInfoV["strength"]."\n";
			echo "                      </li>\n";
		} // if
	} // for
	echo "                    </ul>\n";
	echo "                    <ul class=\"list-group\">\n";
	$hasImprovementInfo = false;
	for ($q = 0; $q < count($_SESSION["tileInfo"]["improvements"]); $q++) {
		$improvementInfoH = getImprovementInfo($getPage_connection2,$_SESSION["tileInfo"]["improvements"][$q]);
		if ($improvementInfoH["id"] >= 1) {
			if ($hasImprovementInfo === false) {
				echo "                      Improvements:\n";
				$hasImprovementInfo = true;
			} // if
			$improvementTypeInfoH = getImprovementTypeInfo($getPage_connection2,$improvementInfoH["type"]);
			echo "                      <li class=\"list-group-item\">\n";
			echo "                        ".$improvementTypeInfoH["name"].", Level: ".$improvementInfoH["level"]."\n";
			echo "                      </li>\n";
		} // if
	} // for
	echo "                    </ul>\n";
	echo "                  </div>\n";
	echo "                </div>\n";
	echo "              </div><!-- /.col-sm-4 -->\n\n";

	echo "            </div>\n";

	echo "          </div>\n";
	echo "        </div>\n";
} // showMapInfo

/*-----------------------------------------------*/
/********************************
 Map Action Functions
 ********************************/
/*-----------------------------------------------*/

/********************************
 removeUnit
 validation and processing for removing unit
 ********************************/
function removeUnit($getPage_connection2) {
	if ($_SESSION["action_id"] >= 1) {
		$unitInfoW = getUnitInfoByID($getPage_connection2,$_SESSION["action_id"]);
		// is it a valid entity?
		if ($unitInfoW["continent"] == $_SESSION["continent_id"] && $unitInfoW["xpos"] == $_SESSION["xpos"] && $unitInfoW["ypos"] == $_SESSION["ypos"]) {
			// is it owned by current player?
			if ($unitInfoW["owner"] == $_SESSION["nation_id"]) {
				$_SESSION["success_message"] = "Player's unit ".$unitInfoW["name"]." has been removed successfully!";
				$unitTypeInfoW = getUnitTypeInfo($getPage_connection2,$unitInfoW["type"]);
				$nationInfo1 = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				$new_goods = array(0=>0);
				for ($zz=0; $zz < count($nationInfo1["goods"]); $zz++) {
					$new_goods[$zz] = $nationInfo1["goods"][$zz] + $unitTypeInfoW["goodsRequired"][$zz];
				} // for
				$new_money = $nationInfo1["money"] + $unitTypeInfoW["baseCost"];
				$new_food = $nationInfo1["food"] + $unitTypeInfoW["foodRequired"];
				setNationInfo($getPage_connection2,$nationInfo1["id"],$nationInfo1["name"],$nationInfo1["home"],$nationInfo1["formal"],$nationInfo1["flag"],$nationInfo1["production"],$new_money,$nationInfo1["debt"],$nationInfo1["happiness"],$new_food,$nationInfo1["authority"],$nationInfo1["authorityChanged"],$nationInfo1["economy"],$nationInfo1["economyChanged"],$nationInfo1["organizations"],$nationInfo1["invites"],$new_goods,$nationInfo1["resources"],$nationInfo1["population"],$nationInfo1["strike"]);
				deleteUnitInfo($getPage_connection2,$_SESSION["action_id"],$_SESSION["continent_id"],$_SESSION["xpos"],$_SESSION["ypos"]);
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: unit is not owned by current player.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: unit is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: unit is not valid.";
	} // else
} // removeUnit

/********************************
 moveUnit
 validation and processing for moving unit
 ********************************/
function moveUnit($getPage_connection2) {
	$validMove = false;
	if ($_SESSION["action_id"] >= 1) {
		$unitInfoW = getUnitInfoByID($getPage_connection2,$_SESSION["action_id"]);
		// is it a valid entity?
		if ($unitInfoW["continent"] == $_SESSION["continent_id"] && $unitInfoW["xpos"] == $_SESSION["xpos"] && $unitInfoW["ypos"] == $_SESSION["ypos"]) {
			// is it owned by current player?
			if ($unitInfoW["owner"] == $_SESSION["nation_id"]) {
				$unitTypeInfoW = getUnitTypeInfo($getPage_connection2,$unitInfoW["type"]);
				// does unit have movement left?
				if ($unitInfoW["used"] < $unitTypeInfoW["movement"]) {
					$tileInfoW = getTileInfo($getPage_connection2,$_SESSION["new_continent"],$_SESSION["new_xpos"],$_SESSION["new_ypos"]);
					$terrainInfoW = getTerrainInfo($getPage_connection2,$tileInfoW["terrain"]);
					if (($terrainInfoW["movementRestriction"] >= 1 && ($unitInfoW["used"] < $terrainInfoW["movementRestriction"])) || ($terrainInfoW["movementRestriction"] == 0)) {
						// land
						if ($unitTypeInfoW["water"] == 0) {
							if ($tileInfoW["terrain"] != 2 && $tileInfoW["terrain"] >= 1) {
								if (($_SESSION["new_continent"] == $_SESSION["continent_id"]) && (($_SESSION["new_xpos"] >= $_SESSION["xpos"] - 1) && ($_SESSION["new_xpos"] <= $_SESSION["xpos"] + 1)) && (($_SESSION["new_ypos"] >= $_SESSION["ypos"] - 1) && ($_SESSION["new_ypos"] <= $_SESSION["ypos"] + 1))) {
									$validMove = true;
								} // if
							} // if
							// water
						} else {
							$coastBool = isItCoast($getPage_connection2,$tileInfoW);
							if ($coastBool === true) {
								$coastCheck = true;
							} else {
								$coastCheck = false;
							} // else
							if ($tileInfoW["terrain"] == 2 && $tileInfoW["terrain"] >= 1) {
								if ((($_SESSION["new_continent"] >= $_SESSION["continent_id"] - 1) && ($_SESSION["new_continent"] <= $_SESSION["continent_id"] + 1)) && (($_SESSION["new_xpos"] >= $_SESSION["xpos"] - 1) && ($_SESSION["new_xpos"] <= $_SESSION["xpos"] + 1)) && (($_SESSION["new_ypos"] >= $_SESSION["ypos"] - 1) && ($_SESSION["new_ypos"] <= $_SESSION["ypos"] + 1))) {
									$validMove = true;
								} // if
							} else if ($coastCheck === true) {
								if ((($_SESSION["new_continent"] >= $_SESSION["continent_id"] - 1) && ($_SESSION["new_continent"] <= $_SESSION["continent_id"] + 1)) && (($_SESSION["new_xpos"] >= $_SESSION["xpos"] - 1) && ($_SESSION["new_xpos"] <= $_SESSION["xpos"] + 1)) && (($_SESSION["new_ypos"] >= $_SESSION["ypos"] - 1) && ($_SESSION["new_ypos"] <= $_SESSION["ypos"] + 1))) {
									$validMove = true;
								} // if
							} // else if
						} // else
						// move is valid, move on to processing
						if ($validMove === true) {
							$unitInfoC = getUnitInfo($getPage_connection2,$_SESSION["new_continent"],$_SESSION["new_xpos"],$_SESSION["new_ypos"]);
							$unitTypeInfoC = getUnitTypeInfo($getPage_connection2,$unitInfoC["type"]);

							// if enemy unit is found on destination tile, there is conflict!
							if ($unitInfoC["id"] >= 1 && $unitInfoC["owner"] != $_SESSION["nation_id"]) {
								// land units can only attack other land units, water units can attack both, with the exception of artillery which can attack water units as well
								if (($unitTypeInfoW["water"] == 0 && $unitTypeInfoC["water"] != 1) || ($unitTypeInfoW["water"] == 0 && $unitTypeInfoC["water"] == 1 && $unitInfoW["type"] == 4) || $unitTypeInfoW["water"] == 1) {
									combat($getPage_connection2,$_SESSION["new_continent"],$_SESSION["new_xpos"],$_SESSION["new_ypos"],$unitInfoW,$unitInfoC,0);
								} else {
									$_SESSION["warning_message"] = "Cannot complete action: unit cannot attack specified unit.";
								} // else
								// if friendly unit is found on destination tile, current unit does not move
							} else if ($unitInfoC["id"] >= 1 && $unitInfoC["owner"] == $_SESSION["nation_id"]) {
								$_SESSION["warning_message"] = "Cannot complete action: unit cannot move into another friendly unit's territory.";
								// else move as normal
							} else {
								$new_used = $unitInfoW["used"] + 1;
								$_SESSION["success_message"] = "Player's unit ".$unitInfoW["name"]." has been moved successfully!";
								setUnitInfo($getPage_connection2,$_SESSION["action_id"],$_SESSION["new_continent"],$_SESSION["new_xpos"],$_SESSION["new_ypos"],$unitInfoW["health"],$new_used,$unitInfoW["name"],$unitInfoW["type"],$unitInfoW["owner"],$unitInfoW["level"],$unitInfoW["transport"],$unitInfoW["created"],$unitInfoW["exp"]);
							} // else
						} else {
							$_SESSION["warning_message"] = "Cannot complete action: unit cannot move to specified tile.";
						} // else
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: unit does not have the required movement available for terrain type.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: unit does not have enough movement.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: unit is not owned by current player.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: unit is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: unit is not valid.";
	} // else
} // moveUnit

/********************************
 unloadUnit
 validation and processing for unloading unit
 ********************************/
function unloadUnit($getPage_connection2) {
	$validMove = false;
	if ($_SESSION["action_id"] >= 1) {
		$unitInfoW = getUnitInfoByID($getPage_connection2,$_SESSION["action_id"]);
		// is it a valid entity?
		if ($unitInfoW["continent"] == $_SESSION["continent_id"] && $unitInfoW["xpos"] == $_SESSION["xpos"] && $unitInfoW["ypos"] == $_SESSION["ypos"]) {
			// is it owned by current player?
			if ($unitInfoW["owner"] == $_SESSION["nation_id"]) {
				$unitTypeInfoW = getUnitTypeInfo($getPage_connection2,$unitInfoW["type"]);
				if ($unitInfoW["transport"] >= 1) {
					$transportInfoW = getTransportInfo($getPage_connection2,$unitInfoW["transport"]);
					if ($transportInfoW["id"] >= 1) {
						if (count($transportInfoW["list"]) >= 1) {
							$unitID = $transportInfoW["list"][1];
							$unitInfoWA = getUnitInfoByID($getPage_connection2,$unitID);
							$unitTypeInfoWA = getUnitTypeInfo($getPage_connection2,$unitInfoWA["type"]);

							// does unit have movement left?
							if ($unitInfoWA["used"] < $unitTypeInfoWA["movement"]) {
								$tileInfoW = getTileInfo($getPage_connection2,$_SESSION["new_continent"],$_SESSION["new_xpos"],$_SESSION["new_ypos"]);
								$terrainInfoW = getTerrainInfo($getPage_connection2,$tileInfoW["terrain"]);
								if (($terrainInfoW["movementRestriction"] >= 1 && ($unitInfoWA["used"] < $terrainInfoW["movementRestriction"])) || ($terrainInfoW["movementRestriction"] == 0)) {
									// land
									if ($unitTypeInfoWA["water"] == 0) {
										if ($tileInfoW["terrain"] != 2 && $tileInfoW["terrain"] >= 1) {
											if (($_SESSION["new_continent"] == $_SESSION["continent_id"]) && (($_SESSION["new_xpos"] >= $_SESSION["xpos"] - 1) && ($_SESSION["new_xpos"] <= $_SESSION["xpos"] + 1)) && (($_SESSION["new_ypos"] >= $_SESSION["ypos"] - 1) && ($_SESSION["new_ypos"] <= $_SESSION["ypos"] + 1))) {
												$validMove = true;
											} // if
										} // if
										// water
									} else {
										if ($tileInfoW["terrain"] == 2 && $tileInfoW["terrain"] >= 1) {
											if ((($_SESSION["new_continent"] >= $_SESSION["continent_id"] - 1) && ($_SESSION["new_continent"] <= $_SESSION["continent_id"] + 1)) && (($_SESSION["new_xpos"] >= $_SESSION["xpos"] - 1) && ($_SESSION["new_xpos"] <= $_SESSION["xpos"] + 1)) && (($_SESSION["new_ypos"] >= $_SESSION["ypos"] - 1) && ($_SESSION["new_ypos"] <= $_SESSION["ypos"] + 1))) {
												$validMove = true;
											} // if
										} // if
									} // else
									// move is valid, move on to processing
									if ($validMove === true) {
										$unitInfoC = getUnitInfo($getPage_connection2,$_SESSION["new_continent"],$_SESSION["new_xpos"],$_SESSION["new_ypos"]);
										$unitTypeInfoC = getUnitTypeInfo($getPage_connection2,$unitInfoC["type"]);

										// if enemy unit is found on destination tile, there is conflict!
										if ($unitInfoC["id"] >= 1 && $unitInfoC["owner"] != $_SESSION["nation_id"]) {
											// land units can only attack other land units, water units can attack both, with the exception of artillery which can attack water units as well
											if (($unitTypeInfoWA["water"] == 0 && $unitTypeInfoC["water"] != 1) || ($unitTypeInfoWA["water"] == 0 && $unitTypeInfoC["water"] == 1 && $unitInfoWA["type"] == 4) || $unitTypeInfoWA["water"] == 1) {
												combat($getPage_connection2,$_SESSION["new_continent"],$_SESSION["new_xpos"],$_SESSION["new_ypos"],$unitInfoWA,$unitInfoC,1);
											} else {
												$_SESSION["warning_message"] = "Cannot complete action: unit cannot attack specified unit.";
											} // else
											// if friendly unit is found on destination tile, current unit does not move
										} else if ($unitInfoC["id"] >= 1 && $unitInfoC["owner"] == $_SESSION["nation_id"]) {
											$_SESSION["warning_message"] = "Cannot complete action: unit cannot move into another friendly unit's territory.";
											// else move as normal
										} else {
											$new_used = $unitInfoWA["used"] + 1;
											$_SESSION["success_message"] = "Player's unit ".$unitInfoWA["name"]." has been moved successfully!";

											// set new transport list
											$new_transport_list = array(0=>0);
											$counterB = 0;
											for ($b=0; $b < count($transportInfoW); $b++) {
												if ($b != 1) {
													$new_transport_list[$counterB] = $transportInfoW["list"][$b];
													$counterB++;
												} // if
											} // for
											setTransportInfo($getPage_connection2,$unitInfoW["transport"],$new_transport_list);

											setUnitInfo($getPage_connection2,$unitInfoWA["id"],$_SESSION["new_continent"],$_SESSION["new_xpos"],$_SESSION["new_ypos"],$unitInfoWA["health"],$new_used,$unitInfoWA["name"],$unitInfoWA["type"],$unitInfoWA["owner"],$unitInfoWA["level"],$unitInfoWA["transport"],$unitInfoW["created"],$unitInfoW["exp"]);
										} // else
									} else {
										$_SESSION["warning_message"] = "Cannot complete action: unit cannot move to specified tile.";
									} // else
								} else {
									$_SESSION["warning_message"] = "Cannot complete action: unit does not have the required movement available for terrain type.";
								} // else
							} else {
								$_SESSION["warning_message"] = "Cannot complete action: unit does not have enough movement.";
							} // else
						} else {
							$_SESSION["warning_message"] = "Cannot complete action: transport is not available.";
						} // else
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: transport is not available.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: transport is not available.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: unit is not owned by current player.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: unit is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: unit is not valid.";
	} // else
} // unloadUnit

/********************************
 transportUnit
 validation and processing for transporting unit to ship
 ********************************/
function transportUnit($getPage_connection2) {
	$validTransport = false;
	if ($_SESSION["action_id"] >= 1) {
		$unitInfoW = getUnitInfoByID($getPage_connection2,$_SESSION["action_id"]);
		// is it a valid entity?
		if ($unitInfoW["continent"] == $_SESSION["continent_id"] && $unitInfoW["xpos"] == $_SESSION["xpos"] && $unitInfoW["ypos"] == $_SESSION["ypos"]) {
			// is it owned by current player?
			if ($unitInfoW["owner"] == $_SESSION["nation_id"]) {
				$unitTypeInfoW = getUnitTypeInfo($getPage_connection2,$unitInfoW["type"]);
				// does unit have movement left?
				if ($unitInfoW["used"] < $unitTypeInfoW["movement"]) {
					$tileInfoW = getTileInfo($getPage_connection2,$_SESSION["new_continent"],$_SESSION["new_xpos"],$_SESSION["new_ypos"]);
					// land
					if ($unitTypeInfoW["water"] == 0) {
						// only water is applicable
						if ($tileInfoW["terrain"] == 2) {
							if (($_SESSION["new_continent"] == $_SESSION["continent_id"]) && (($_SESSION["new_xpos"] >= $_SESSION["xpos"] - 1) && ($_SESSION["new_xpos"] <= $_SESSION["xpos"] + 1)) && (($_SESSION["new_ypos"] >= $_SESSION["ypos"] - 1) && ($_SESSION["new_ypos"] <= $_SESSION["ypos"] + 1))) {
								$validTransport = true;
							} // if
						} // if
						// water units can't transport other water units!
					} else {
						$validTransport = false;
					} // else
					// transport is valid, move on to processing
					if ($validTransport === true) {
						$unitInfoC = getUnitInfo($getPage_connection2,$_SESSION["new_continent"],$_SESSION["new_xpos"],$_SESSION["new_ypos"]);

						// if friendly unit is found on destination tile, current unit does not move
						if ($unitInfoC["id"] >= 1) {
							// if transport is owned by player proceed
							if ($unitInfoC["owner"] == $_SESSION["nation_id"]) {
								$transportInfoC = getTransportInfo($getPage_connection2,$unitInfoC["transport"]);
								if ($transportInfoC["id"] >= 1) {
									if (count($transportInfoC["list"]) < 5) {
										$new_index = count($transportInfoC) + 1;
										$transportInfoC["list"][$new_index] = $_SESSION["action_id"];
										setTransportInfo($getPage_connection2,$unitInfoC["transport"],$transportInfoC["list"]);
										$new_used = $unitInfoW["used"] + 1;
										$_SESSION["success_message"] = "Player's unit ".$unitInfoW["name"]." has been transported successfully!";
										setUnitInfo($getPage_connection2,$_SESSION["action_id"],$_SESSION["new_continent"],-1,-1,$unitInfoW["health"],$new_used,$unitInfoW["name"],$unitInfoW["type"],$unitInfoW["owner"],$unitInfoW["level"],$unitInfoW["transport"],$unitInfoW["created"],$unitInfoW["exp"]);
									} else {
										$_SESSION["warning_message"] = "Cannot complete action: transport is at full capacity.";
									} // else
								} else {
									$_SESSION["warning_message"] = "Cannot complete action: transport is invalid.";
								} // else
							} else {
								$_SESSION["warning_message"] = "Cannot complete action: transport not owned by player.";
							} // else
						} else {
							$_SESSION["warning_message"] = "Cannot complete action: unit cannot move to specified tile.";
						} // else
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: unit cannot move to specified tile.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: unit does not have enough movement.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: unit is not owned by current player.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: unit is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: unit is not valid.";
	} // else
} // transportUnit

/********************************
 upgradeImprovement
 validation and processing for upgrading improvement
 ********************************/
function upgradeImprovement($getPage_connection2) {
	$validImprovement = false;
	if ($_SESSION["action_id"] >= 1) {
		$improvementInfoW = getImprovementInfo($getPage_connection2,$_SESSION["action_id"]);
		// is it a valid entity?
		$tileInfoW = getTileInfo($getPage_connection2,$_SESSION["continent_id"],$_SESSION["xpos"],$_SESSION["ypos"]);
		for ($q=0;$q < count($tileInfoW["improvements"]);$q++) {
			if ($tileInfoW["improvements"][$q] == $_SESSION["action_id"]) {
				$validImprovement = true;
				break;
			} // if
		} // for
		if ($validImprovement === true) {
			$validImprovement = false;
			for ($q=0;$q < count($improvementInfoW["owners"]);$q++) {
				if ($improvementInfoW["owners"][$q] == $_SESSION["nation_id"]) {
					$validImprovement = true;
					break;
				} // if
			} // for
			// is it owned by current player?
			if ($validImprovement === true) {
				$improvementTypeInfoW = getImprovementTypeInfo($getPage_connection2,$improvementInfoW["type"]);
				$nationInfoW = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				$final_cost = $improvementTypeInfoW["baseCost"] * ($improvementInfoW["level"] + 1);
				if ($nationInfoW["money"] >= $final_cost) {
					$_SESSION["success_message"] = "Player's improvement has been upgraded successfully!";
					$new_money = $nationInfoW["money"] - $final_cost;
					setNationInfo($getPage_connection2,$_SESSION["nation_id"],$nationInfoW["name"],$nationInfoW["home"],$nationInfoW["formal"],$nationInfoW["flag"],$nationInfoW["production"],$new_money,$nationInfoW["debt"],$nationInfoW["happiness"],$nationInfoW["food"],$nationInfoW["authority"],$nationInfoW["authorityChanged"],$nationInfoW["economy"],$nationInfoW["economyChanged"],$nationInfoW["organizations"],$nationInfoW["invites"],$nationInfoW["goods"],$nationInfoW["resources"],$nationInfoW["population"],$nationInfoW["strike"]);
					$new_level = $improvementInfoW["level"] + 1;
					setImprovementInfo($getPage_connection2,$_SESSION["action_id"],$improvementInfoW["continent"],$improvementInfoW["xpos"],$improvementInfoW["ypos"],$improvementInfoW["type"],$new_level,$improvementInfoW["usingResources"],$improvementInfoW["owners"]);
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: current player cannot afford this expense.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: improvement is not owned by current player.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: improvement is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: improvement is not valid.";
	} // else
} // upgradeImprovement

/********************************
 removeImprovement
 validation and processing for removing unit
 ********************************/
function removeImprovement($getPage_connection2) {
	$validImprovement = false;
	if ($_SESSION["action_id"] >= 1) {
		$improvementInfoW = getImprovementInfo($getPage_connection2,$_SESSION["action_id"]);
		// is it a valid entity?
		$tileInfoW = getTileInfo($getPage_connection2,$_SESSION["continent_id"],$_SESSION["xpos"],$_SESSION["ypos"]);
		for ($q=0;$q < count($tileInfoW["improvements"]);$q++) {
			if ($tileInfoW["improvements"][$q] == $_SESSION["action_id"]) {
				$validImprovement = true;
				break;
			} // if
		} // for
		if ($validImprovement === true) {
			$validImprovement = false;
			for ($q=0;$q < count($improvementInfoW["owners"]);$q++) {
				if ($improvementInfoW["owners"][$q] == $_SESSION["nation_id"]) {
					$improvementTypeInfoW = getImprovementTypeInfo($getPage_connection2,$improvementInfoW["type"]);
					$validImprovement = true;
					break;
				} // if
			} // for
			// is it owned by current player?
			if ($validImprovement === true) {
				$nationInfoW = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				$new_money = $nationInfoW["money"] + $improvementTypeInfoW["baseCost"];
				setNationInfo($getPage_connection2,$nationInfoW["id"],$nationInfoW["name"],$nationInfoW["home"],$nationInfoW["formal"],$nationInfoW["flag"],$nationInfoW["production"],$new_money,$nationInfoW["debt"],$nationInfoW["happiness"],$nationInfoW["food"],$nationInfoW["authority"],$nationInfoW["authorityChanged"],$nationInfoW["economy"],$nationInfoW["economyChanged"],$nationInfoW["organizations"],$nationInfoW["invites"],$nationInfoW["goods"],$nationInfoW["resources"],$nationInfoW["population"],$nationInfoW["strike"]);
				$_SESSION["success_message"] = "Player's improvement has been removed successfully!";
				deleteImprovementInfo($getPage_connection2,$_SESSION["action_id"],$_SESSION["continent_id"],$_SESSION["xpos"],$_SESSION["ypos"]);
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: improvement is not owned by current player.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: improvement is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: improvement is not valid.";
	} // else
} // removeImprovement

/********************************
 buildImprovement
 validation and processing for building improvement
 ********************************/
function buildImprovement($getPage_connection2) {
	if ($_SESSION["action_id"] >= 1) {
		$improvementTypeInfoW = getImprovementTypeInfo($getPage_connection2,$_SESSION["action_id"]);
		$tileInfoW = getTileInfo($getPage_connection2,$_SESSION["continent_id"],$_SESSION["xpos"],$_SESSION["ypos"]);

		$validImprovement = false;
		$availableResources = array(0=>0);
		$counter = 0;
		$using = array(0=>0);
		
		$proceed = false;
		
		$nationInfoE = getNationInfo($getPage_connection2, $_SESSION["nation_id"]);
		
		// if home continent already exists, capital is built, ignore build command
		if ($improvementTypeInfoW["id"] == 1 && $nationInfoE["home"] > 0) {
			$proceed = false;
		} else {
			$proceed = true;
		} // else
		
		if ($proceed === true) {
			// loop tile's resources
			for ($q = 0; $q < count($tileInfoW["resources"]); $q++) {
				$resourceIsUsed = false;
				$resourceInfo = getResourceInfo($getPage_connection2,$tileInfoW["resources"][$q]);
				// only proceed if capacity exists above 0
				if ($resourceInfo["capacity"] >= 1) {
					$resourceTypeInfo = getResourceTypeInfo($getPage_connection2,$resourceInfo["type"]);
					// loop tile's improvements
					for ($a = 0; $a < count($tileInfoW["improvements"]); $a++) {
						$improvementInfo = getImprovementInfo($getPage_connection2,$tileInfoW["improvements"][$a]);
						// if tile improvement is currently using resource, check
						for ($d=0; $d < count($improvementInfo["usingResources"]); $d++) {
							if ($improvementInfo["usingResources"][$d] == $resourceInfo["id"]) {
								$resourceIsUsed = true;
								break;
							} else {
								$resourceIsUsed = false;
							} // else
						} // for
						if ($resourceIsUsed === true) {
							break;
						} // if
					} // for
				} else {
					$resourceIsUsed = true;
				} // else
				// set available resources not used and with capacity left
				if ($resourceIsUsed === false) {
					$availableResources[$counter] = $resourceInfo;
					$counter++;
				} // if
			} // for
			$terrainIsValid = false;
			// loop terrain requirements
			for ($b = 0; $b < count($improvementTypeInfoW["terrainTypeRequired"]); $b++) {
				// if tile terrain type is valid, check
				if ($improvementTypeInfoW["terrainTypeRequired"][$b] == $tileInfoW["terrain"]) {
					$terrainIsValid = true;
					break;
				} else {
					$terrainIsValid = false;
				} // else
			} // for
			// if terrain is valid, check for resource requirements
			if ($terrainIsValid === true) {
				$resourceIsValid = false;
	
				// if no requirements just proceed, otherwise proceed to loop
				if ($improvementTypeInfoW["resourcesRequired"][0] == 0) {
					$validImprovement = true;
				} else {
					$checkResource = array(0=>false);
					$using = array(0=>0);
					// loop resource requirements
					for ($t = 0; $t < count($improvementTypeInfoW["resourcesRequired"]); $t++) {
						// loop available resources
						for ($z = 0; $z < count($availableResources); $z++) {
							// if tile terrain type is valid, check
							if ($improvementTypeInfoW["resourcesRequired"][$t] == $availableResources[$z]["type"]) {
								$checkResource[$t] = true;
								$using[$t] = $availableResources[$z]["id"];
								break;
							} else {
							} // else
						} // for
					} // for
	
					$illegal = false;
					for ($a=0; $a < count($improvementTypeInfoW["resourcesRequired"]); $a++) {
						if (isset($checkResource[$a])) {
							if ($checkResource[$a] === false) {
								$illegal = true;
								break;
							} // if
						} else {
							$illegal = true;
							break;
						} // else
					} // for
	
					if ($illegal === false) {
						$validImprovement = true;
					} // if
				} // else
			} // if
		} // if
		if ($validImprovement === true) {
			// are improvement slots maxed out?
			if (count($tileInfoW["improvements"]) < 5) {
				// is tile owned by current player?
				if ($tileInfoW["owner"] == $_SESSION["nation_id"]) {
					$nationInfoW = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
					$final_cost = $improvementTypeInfoW["baseCost"];
					if ($nationInfoW["money"] >= $final_cost) {
						$_SESSION["success_message"] = "Player's improvement has been built successfully!";
						$new_money = $nationInfoW["money"] - $final_cost;
						$new_owners = array(0=>$_SESSION["nation_id"]);
						// change home continent if capital built
						// add population if town or capital is built
						if ($improvementTypeInfoW["id"] == 1) {
							$new_population = $nationInfoE["population"] + 1000;
							$new_home = $_SESSION["continent_id"];
							setNationInfo($getPage_connection2,$nationInfoE["id"],$nationInfoE["name"],$new_home,$nationInfoE["formal"],$nationInfoE["flag"],$nationInfoE["production"],$nationInfoE["money"],$nationInfoE["debt"],$nationInfoE["happiness"],$nationInfoE["food"],$nationInfoE["authority"],$nationInfoE["authorityChanged"],$nationInfoE["economy"],$nationInfoE["economyChanged"],$nationInfoE["organizations"],$nationInfoE["invites"],$nationInfoE["goods"],$nationInfoE["resources"],$new_population,$nationInfoE["strike"]);								
						} else if ($improvementTypeInfoW["id"] == 2) {
							$new_population = $nationInfoE["population"] + 1000;
							setNationInfo($getPage_connection2,$nationInfoE["id"],$nationInfoE["name"],$nationInfoE["home"],$nationInfoE["formal"],$nationInfoE["flag"],$nationInfoE["production"],$nationInfoE["money"],$nationInfoE["debt"],$nationInfoE["happiness"],$nationInfoE["food"],$nationInfoE["authority"],$nationInfoE["authorityChanged"],$nationInfoE["economy"],$nationInfoE["economyChanged"],$nationInfoE["organizations"],$nationInfoE["invites"],$nationInfoE["goods"],$nationInfoE["resources"],$new_population,$nationInfoE["strike"]);
						} // else if
						addImprovementInfo($getPage_connection2,$_SESSION["continent_id"],$_SESSION["xpos"],$_SESSION["ypos"],$_SESSION["action_id"],1,$using,$new_owners);
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: current player cannot afford this expense.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: tile is not owned by current player.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: this tile has its maximum capacity of improvements fulfilled.";
			} // else
		} else {
			if ($proceed === false) {
				$_SESSION["warning_message"] = "Cannot complete action: the construction of this improvement type has reached its limit already.";
			} else if ($terrainIsValid === false) {
				$_SESSION["warning_message"] = "Cannot complete action: terrain is incompatible with improvement type.";
			} else if ($resourceIsValid === false) {
				$_SESSION["warning_message"] = "Cannot complete action: improvement type requires resource that is not currently available in sufficient quantity on this tile.";
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: improvement construction cannot be completed due to zoning restrictions.";
			} // else
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: improvement construction is not valid.";
	} // else
} // buildImprovement

/********************************
 upgradeUnit
 validation and processing for upgrading improvement
 ********************************/
function upgradeUnit($getPage_connection2) {
	if ($_SESSION["action_id"] >= 1) {
		$unitInfoW = getUnitInfoByID($getPage_connection2,$_SESSION["action_id"]);
		// is it a valid entity?
		if ($unitInfoW["continent"] == $_SESSION["continent_id"] && $unitInfoW["xpos"] == $_SESSION["xpos"] && $unitInfoW["ypos"] == $_SESSION["ypos"]) {
			// is it owned by current player?
			if ($unitInfoW["owner"] == $_SESSION["nation_id"]) {
				$unitTypeInfoW = getUnitTypeInfo($getPage_connection2,$unitInfoW["type"]);
				$nationInfoW = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
				$final_cost = $unitTypeInfoW["baseCost"] * ($unitInfoW["level"] + 1);
				if ($nationInfoW["money"] >= $final_cost) {
					$_SESSION["success_message"] = "Player's unit ".$unitInfoW["name"]." has been upgraded successfully!";
					$new_money = $nationInfoW["money"] - $final_cost;
					setNationInfo($getPage_connection2,$_SESSION["nation_id"],$nationInfoW["name"],$nationInfoW["home"],$nationInfoW["formal"],$nationInfoW["flag"],$nationInfoW["production"],$new_money,$nationInfoW["debt"],$nationInfoW["happiness"],$nationInfoW["food"],$nationInfoW["authority"],$nationInfoW["authorityChanged"],$nationInfoW["economy"],$nationInfoW["economyChanged"],$nationInfoW["organizations"],$nationInfoW["invites"],$nationInfoW["goods"],$nationInfoW["resources"],$nationInfoW["population"],$nationInfoW["strike"]);
					$new_level = $unitInfoW["level"] + 1;
					setUnitInfo($getPage_connection2,$_SESSION["action_id"],$unitInfoW["continent"],$unitInfoW["xpos"],$unitInfoW["ypos"],$unitInfoW["health"],$unitInfoW["used"],$unitInfoW["name"],$unitInfoW["type"],$unitInfoW["owner"],$new_level,$unitInfoW["transport"],$unitInfoW["created"],$unitInfoW["exp"]);
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: current player cannot afford this expense.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: unit is not owned by current player.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: unit is not valid.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: unit is not valid.";
	} // else
} // upgradeUnit

/********************************
 buildUnit
 validation and processing for building unit
 ********************************/
function buildUnit($getPage_connection2) {
	$tileInfoW = getTileInfo($getPage_connection2,$_SESSION["continent_id"],$_SESSION["xpos"],$_SESSION["ypos"]);
	$improvementValid = false;

	// go through improvements
	for ($a = 0; $a < count($tileInfoW["improvements"]); $a++) {
		$improvementInfoW = getImprovementInfo($getPage_connection2,$tileInfoW["improvements"][$a]);
		// is it a depot?
		if ($improvementInfoW["type"] == 5) {
			// are any of the owners of the depot the player?
			for ($b = 0; $b < count($improvementInfoW); $b++) {
				if ($improvementInfoW["owners"][$b] == $_SESSION["nation_id"]) {
					$improvementValid = true;
					break;
				} else {
					$improvementValid = false;
				} // else
			} // for
		} // if
		if ($improvementValid === true) {
			break;
		} // if
	} // for

	// if valid depot exists on tile, proceed
	if ($_SESSION["action_id"] >= 1 && $improvementValid === true) {
		$unitTypeInfoW = getUnitTypeInfo($getPage_connection2,$_SESSION["action_id"]);
		$nationInfoW = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
		// is it a valid entity?
		if ($unitTypeInfoW["id"] >= 1) {

			if ($unitTypeInfoW["water"] == 1) {
				if (isItCoast($getPage_connection2,$tileInfoW) === true) {
					$coastCheck = true;
				} else {
					$coastCheck = false;
				} // else
			} else {
				$coastCheck = true;
			} // else

			// can player afford this expense?
			if ($coastCheck === true) {
				if ($unitTypeInfoW["baseCost"] <= $nationInfoW["money"]) {
					$notEnough = false;
					for ($zz=0; $zz < count($unitTypeInfoW["goodsRequired"]); $zz++) {
						if ($unitTypeInfoW["goodsRequired"][$zz] > $nationInfoW["goods"][$zz]) {
							$notEnough = true;
							break;
						} // if
					} // for
					if ($notEnough === false) {
						$unitInfoD = getUnitInfo($getPage_connection2,$tileInfoW["continent"],$tileInfoW["xpos"],$tileInfoW["ypos"]);
						if ($unitInfoD["id"] < 1) {
							$_SESSION["success_message"] = "Unit has been trained successfully!";
							$new_money = $nationInfoW["money"] - $unitTypeInfoW["baseCost"];
							$new_goods = array(0=>0);
							for ($zz=0; $zz < count($nationInfoW["goods"]); $zz++) {
								$new_goods[$zz] = $nationInfoW["goods"][$zz] - $unitTypeInfoW["goodsRequired"][$zz];
							} // for
							$new_food = $nationInfoW["food"] - $unitTypeInfoW["foodRequired"];
							setNationInfo($getPage_connection2,$_SESSION["nation_id"],$nationInfoW["name"],$nationInfoW["home"],$nationInfoW["formal"],$nationInfoW["flag"],$nationInfoW["production"],$new_money,$nationInfoW["debt"],$nationInfoW["happiness"],$new_food,$nationInfoW["authority"],$nationInfoW["authorityChanged"],$nationInfoW["economy"],$nationInfoW["economyChanged"],$nationInfoW["organizations"],$nationInfoW["invites"],$new_goods,$nationInfoW["resources"],$nationInfoW["population"],$nationInfoW["strike"]);
							 
							$mt_rand = mt_rand(100,9999);
							$new_name = "Unit ".$mt_rand;
							// if transport, add to transport list
							if ($_SESSION["action_id"] == 10) {
								addTransportInfo($getPage_connection2,"0");
								$new_transport_id = $getPage_connection2->insert_id;
							} else {
								$new_transport_id = 0;
							} // else
							
							$tileInfoX = getTileInfo($getPage_connection2,$_SESSION["continent_id"],$_SESSION["xpos"],$_SESSION["ypos"]);
								
							addUnitInfo($getPage_connection2,$_SESSION["continent_id"],$_SESSION["xpos"],$_SESSION["ypos"],$unitTypeInfoW["health"],$unitTypeInfoW["movement"],$new_name,$_SESSION["action_id"],$_SESSION["nation_id"],1,$new_transport_id,$tileInfoX["id"],0.0);
						} else {
							$_SESSION["warning_message"] = "Cannot complete action: there is already a unit on that current tile.";
						} // else
					} // if
				} // if
			} else {
				if ($coastCheck === true) {
					$_SESSION["warning_message"] = "Cannot complete action: player cannot afford this unit.";
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: unit type requires coast to train.";
				} // else
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: unit is not valid.";
		} // else
	} else {
		if ($improvementValid === true) {
			$_SESSION["warning_message"] = "Cannot complete action: unit requires depot for training.";
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: unit is not valid.";
		} // else
	} // else
} // buildUnit
?>