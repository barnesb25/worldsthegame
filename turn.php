<?php
/****************************************************************************
 * Name:        turn.php
 * Author:      Ben Barnes
 * Date:        2015-12-21
 * Purpose:     End turn functions page
 *****************************************************************************/

/*-----------------------------------------------*/
/********************************
 End Turn Functions
 ********************************/
/*-----------------------------------------------*/

/********************************
 endTurn
 Main end turn function
 ********************************/
function endTurn($getPage_connection2) {
	// Illegal Conflicts, Tables Cleanup, Claims+Control Updates, etc.
	preliminaryUpdates($getPage_connection2);

	// Run through end turn processing for all nations
	updateNations($getPage_connection2);

	// Global post-script end turn processing
	updateGlobe($getPage_connection2);

	// creates a new continent if current ones are starting to fill up
	checkContinents($getPage_connection2);
} // endTurn

function preliminaryUpdates($getPage_connection2) {
	$limit_allUnits = 0;
	if ($stmt = $getPage_connection2->prepare("SELECT COUNT(id) FROM unitsmap")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->fetch();
		$limit_allUnits = $r_result;
		$stmt->close();
	} else {
		$endTurnFailed = "failed";
	} // else

	$limit_allTiles = 0;
	if ($stmt = $getPage_connection2->prepare("SELECT COUNT(id) FROM tilesmap")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->fetch();
		$limit_allTiles = $r_result;
		$stmt->close();
	} else {
		$endTurnFailed = "failed";
	} // else

	$limit_allOffers = 0;
	if ($stmt = $getPage_connection2->prepare("SELECT COUNT(id) FROM offers")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->fetch();
		$limit_allOffers = $r_result;
		$stmt->close();
	} else {
		$endTurnFailed = "failed";
	} // else

	// Illegal Conflicts and Table Cleanup

	// removed declined offers and empty or invalid offers and offers which have reached the turn limit
	if ($limit_allOffers >= 1) {
		if ($stmt = $getPage_connection2->prepare("SELECT id FROM offers ORDER BY id ASC")) {
			$stmt->execute();
			$stmt->bind_result($r_result);
			$stmt->store_result();

			while ($stmt->fetch()) {	
				$next_offers = $r_result;
				$offerInfo1 = array("id"=>0,"fromNation"=>0,"toNation"=>0,"givingItems"=>"","receivingItems"=>"","turns"=>0,"counter"=>0,"status"=>0);
				if ($stmt = $getPage_connection2->prepare("SELECT id,fromNation,toNation,givingItems,receivingItems,turns,counter,status FROM offers WHERE id=? LIMIT 1")) {
					$stmt->bind_param("i", $next_offers);
					$stmt->execute();
					$stmt->bind_result($r_id, $r_fromNation, $r_toNation, $r_givingItems, $r_receivingItems, $r_turns, $r_counter, $r_status);
					$stmt->fetch();
					$offerInfo1["id"] = $r_id;
					$offerInfo1["fromNation"] = $r_fromNation;
					$offerInfo1["toNation"] = $r_toNation;
					if (stripos($r_givingItems,",")) {
						$offerInfo1["givingItems"] = explode(",",$r_givingItems);
					} else {
						$offerInfo1["givingItems"] = array(0=>$r_givingItems);
					} // else
					if (stripos($r_receivingItems,",")) {
						$offerInfo1["receivingItems"] = explode(",",$r_receivingItems);
					} else {
						$offerInfo1["receivingItems"] = array(0=>$r_receivingItems);
					} // else
					$offerInfo1["turns"] = $r_turns;
					$offerInfo1["counter"] = $r_counter;
					$offerInfo1["status"] = $r_status;
					$stmt->close();
				} else {
				}
	
				if ($offerInfo1["status"] == 2 || ($offerInfo1["givingItems"][0] < 1 && $offerInfo1["receivingItems"][0] < 1) || ($offerInfo1["toNation"] < 1 && $offerInfo1["fromNation"] < 1)) {
					deleteOfferInfo($getPage_connection2,$next_offers);
				} // if
	
				if ($offerInfo1["counter"] == $offerInfo1["turns"]) {
					deleteOfferInfo($getPage_connection2,$next_offers);
				} // if
			} // while
			$stmt->close();
		} else {
		} // else
	} // if

	// search through all units and look for illegally placed, dead, invalid level, or striking units remove them
	// also reset used movement
	if ($limit_allUnits >= 1) {
		if ($stmt = $getPage_connection2->prepare("SELECT id FROM unitsmap ORDER BY id ASC")) {
			$stmt->execute();
			$stmt->bind_result($r_result);
			$stmt->store_result();

			while ($stmt->fetch()) {
				$next_units = $r_result;
				$illegal = false;
				$unitInfoW = getUnitInfoByID($getPage_connection2,$next_units);
				$unitTypeInfoW = getUnitTypeInfo($getPage_connection2,$unitInfoW["type"]);
				$tileInfoW = getTileInfo($getPage_connection2,$unitInfoW["continent"],$unitInfoW["xpos"],$unitInfoW["ypos"]);
				$coastal = isItCoast($getPage_connection2,$tileInfoW);
	
				// reset used movement
				setUnitInfo($getPage_connection2,$unitInfoW["id"],$unitInfoW["continent"],$unitInfoW["xpos"],$unitInfoW["ypos"],$unitInfoW["health"],0,$unitInfoW["name"],$unitInfoW["type"],$unitInfoW["owner"],$unitInfoW["level"],$unitInfoW["transport"],$unitInfoW["created"],0.0);
				
				// land unit
				if ($unitTypeInfoW["water"] != 1 && $tileInfoW["terrain"] == 2) {
					$illegal = true;
					// water unit
				} else if ($unitTypeInfoW["water"] == 1 && $tileInfoW["terrain"] != 2 && $coastal === false) {
					$illegal = true;
				} else {
					$illegal = false;
				} // else
	
				if ($unitInfoW["health"] < 1 || $unitInfoW["level"] < 1) {
					$illegal = true;
				} else {
					$illegal = false;
				} // else
	
				$nationInfoA = getNationInfo($getPage_connection2,$unitInfoW["owner"]);
	
				if ($nationInfoA["strike"] > 1) {
					$new_strike = $nationInfoA["strike"] - 1;
					$illegal = true;
				} else {
					$new_strike = $nationInfoA["strike"];
				} // else
	
				if ($illegal === true) {
					deleteUnitInfo($getPage_connection2,$next_units);
					setNationInfo($getPage_connection2,$nationInfoA["id"],$nationInfoA["name"],$nationInfoA["home"],$nationInfoA["formal"],$nationInfoA["flag"],$nationInfoA["production"],$nationInfoA["money"],$nationInfoA["debt"],$nationInfoA["happiness"],$nationInfoA["food"],$nationInfoA["authority"],$nationInfoA["authorityChanged"],$nationInfoA["economy"],$nationInfoA["economyChanged"],$nationInfoA["organizations"],$nationInfoA["invites"],$nationInfoA["goods"],$nationInfoA["resources"],$nationInfoA["population"],$new_strike);
				} // if
			} // while
			$stmt->close();
		} else {
		} // else
	} // if
	
	if ($limit_allTiles >= 1) {						
		// search through all tiles and look for illegally placed improvements or dead or invalid level improvements and remove them
		if ($stmt = $getPage_connection2->prepare("SELECT id FROM tilesmap ORDER BY id ASC")) {
			$stmt->execute();
			$stmt->bind_result($r_result);
			$stmt->store_result();

			while ($stmt->fetch()) {
				$next_tiles = $r_result;
				$tileInfoW = getTileInfoByID($getPage_connection2,$next_tiles);
				
				
				for ($w=0; $w < count($tileInfoW["improvements"]); $w++) {
					$illegal = false;
					$improvementInfoW = getImprovementInfo($getPage_connection2,$tileInfoW["improvements"][$w]);
					$improvementTypeInfoW = getImprovementTypeInfo($getPage_connection2,$improvementInfoW["type"]);
	
					$resourceCheck = array(0=>true);
					
					// are any resources actually required
					if (count($improvementTypeInfoW["resourcesRequired"]) > 1) {
						if ($improvementTypeInfoW["resourcesRequired"][1] > 0) {
							$validResourcesRequired = true;
						} else {
							$validResourcesRequired = false;
						} // else				
					} else {
						if ($improvementTypeInfoW["resourcesRequired"][0] > 0) {
							$validResourcesRequired = true;
						} else {
							$validResourcesRequired = false;
						} // else			
					} // else
	
					if ($validResourcesRequired === true) {
						for ($a=0; $a < count($improvementTypeInfoW["resourcesRequired"]); $a++) {
							for ($b=0; $b < count($tileInfoW["resources"]); $b++) {
								$resourceCheck[$a] = false;
								$resourceInfoWW = getResourceInfo($getPage_connection2, $tileInfoW["resources"][$b]);
								if ($resourceInfoWW["type"] == $improvementTypeInfoW["resourcesRequired"][$a]) {
									$resourceCheck[$a] = true;
									break;
								} // if
							} // for
						} // for
						for ($a=0; $a < count($improvementTypeInfoW["resourcesRequired"]); $a++) {
							if ($resourceCheck[$a] === false) {
								$illegal = true;
								break;
							} // if
						} // for
					} // if
	
					$terrainCheck = false;
					for ($a=0; $a < count($improvementTypeInfoW["terrainTypeRequired"]); $a++) {
						if ($tileInfoW["terrain"] == $improvementTypeInfoW["terrainTypeRequired"][$a]) {
							$terrainCheck = true;
							break;
						} // if
					} // for
	
					if ($terrainCheck === false) {
						$illegal = true;
					} // if
	
					if ($improvementInfoW["level"] < 1) {
						$illegal = true;
					} // if
	
					if ($illegal === true) {
						deleteImprovementInfo($getPage_connection2,$improvementInfoW["id"],$improvementInfoW["continent"],$improvementInfoW["xpos"],$improvementInfoW["ypos"]);
					} // if
				} // for
			} // while
			$stmt->close();
		} else {
		} // else

		// setup new claims and change control if needed
		if ($stmt = $getPage_connection2->prepare("SELECT id FROM tilesmap ORDER BY id ASC")) {
			$stmt->execute();
			$stmt->bind_result($r_result);
			$stmt->store_result();

			while ($stmt->fetch()) {
				$next_tiles1 = $r_result;
				$tileInfoW = getTileInfoByID($getPage_connection2,$next_tiles1);
	
				$unitInfoW = getUnitInfo($getPage_connection2,$tileInfoW["continent"],$tileInfoW["xpos"],$tileInfoW["ypos"]);
				$alreadyClaimed = false;
				if ($unitInfoW["id"] >= 1) {				
					// remove improvement ownership from old player who lost tile control
					if ($unitInfoW["owner"] != $tileInfoW["owner"]) {
						for ($r=0; $r < count($tileInfoW["improvements"]); $r++) {
							$improvementInfoW = getImprovementInfo($getPage_connection2,$tileInfoW["improvements"][$r]);
							if ($improvementInfoW["id"] >= 1) {
								$new_owners = array(0=>0);
								$alreadyOwned = false;
								
								$counter = 0;
								for ($s=0; $s < count($improvementInfoW["owners"]); $s++) {
									if ($improvementInfoW["owners"][$s] != $tileInfoW["owner"]) {
										$new_owners[$counter] = $improvementInfoW["owners"][$s];
										$counter++;
									} // if
									if ($improvementInfoW["owners"][$s] == $unitInfoW["owner"]) {
										$alreadyOwned = true;
									}  // if
								} // for
								
								if ($alreadyOwned === false) {
									$new_index = count($new_owners) + 1;
									$new_owners[$new_index] = $unitInfoW["owner"];
								} // if
								
								setImprovementInfo($getPage_connection2,$improvementInfoW["id"],$improvementInfoW["continent"],$improvementInfoW["xpos"],$improvementInfoW["ypos"],$improvementInfoW["type"],$improvementInfoW["level"],$improvementInfoW["usingResources"],$new_owners);
							} // if
						} // for
					} // if
					
					setTileInfo($getPage_connection2,$tileInfoW["id"],$tileInfoW["continent"],$tileInfoW["xpos"],$tileInfoW["ypos"],$tileInfoW["terrain"],$tileInfoW["resources"],$tileInfoW["improvements"],$unitInfoW["owner"],$tileInfoW["claims"],$tileInfoW["population"]);
					
					for ($v=0; $v < count($tileInfoW["claims"]); $v++) {
						$claimsInfoW = getClaimInfo($getPage_connection2,$tileInfoW["claims"][$v]);
						if ($claimsInfoW["owner"] == $unitInfoW["owner"]) {
							$alreadyClaimed = true;
							break;
						} // if
					} // for
					if ($alreadyClaimed === false) {
						addClaimInfo($getPage_connection2,10,$unitInfoW["owner"], $tileInfoW["id"]);
					} // if
					
					// Claims leaking for dominant bordering tiles
					
					// check for dominant claimed tiles
					
					$claimState1 = checkClaimsState($getPage_connection2, $tileInfoW, $tileInfoW["owner"]);
					
					// if dominant, bleed claim to neighbouring tiles
					if ($claimState1 == 1) {
						// add to left
						if ($tileInfoW["xpos"] > 1) {
							// West
							$tileInfoT = getTileInfo($getPage_connection2, $tileInfoW["continent"], $tileInfoW["xpos"] - 1, $tileInfoW["ypos"]);
							$claimStateT = checkClaimsState($getPage_connection2, $tileInfoT, $tileInfoW["owner"]);
							
							if ($claimStateT != 1 && $claimStateT > 1 && $tileInfoT["owner"] > 0) {
								$claimFound = false;
								for ($j=0; $j < count($tileInfoT["claims"]); $j++) {
									$claimInfoT = getClaimInfo($getPage_connection2, $tileInfoT["claims"][$j]);
									
									if ($claimInfoT["owner"] == $tileInfoW["owner"]) {
										setClaimInfo($getPage_connection2, $claimInfoT["id"], $claimInfoT["strength"] + 3, $claimInfoT["owner"]);
										$claimFound = true;
										break;
									} // if
								} // for							
								if ($claimFound === false) {
									addClaimInfo($getPage_connection2, 3, $tileInfoW["owner"], $tileInfoT["id"]);
								} // if
							} // if
							
							if ($tileInfoW["ypos"] > 1) {
								// Northwest
								$tileInfoT = getTileInfo($getPage_connection2, $tileInfoW["continent"], $tileInfoW["xpos"] - 1, $tileInfoW["ypos"] - 1);
								$claimStateT = checkClaimsState($getPage_connection2, $tileInfoT, $tileInfoW["owner"]);
								
								if ($claimStateT != 1 && $claimStateT > 1 && $tileInfoT["owner"] > 0) {
									$claimFound = false;
									for ($j=0; $j < count($tileInfoT["claims"]); $j++) {
										$claimInfoT = getClaimInfo($getPage_connection2, $tileInfoT["claims"][$j]);
								
										if ($claimInfoT["owner"] == $tileInfoW["owner"]) {
											setClaimInfo($getPage_connection2, $claimInfoT["id"], $claimInfoT["strength"] + 3, $claimInfoT["owner"]);
											$claimFound = true;
											break;
										} // if
									} // for
									if ($claimFound === false) {
										addClaimInfo($getPage_connection2, 3, $tileInfoT["owner"], $tileInfoT["id"]);
									} // if
								} // if
							} // if
							
							if ($tileInfoW["ypos"] < 20) {
								// Southwest
								$tileInfoT = getTileInfo($getPage_connection2, $tileInfoW["continent"], $tileInfoW["xpos"] - 1, $tileInfoW["ypos"] + 1);
								$claimStateT = checkClaimsState($getPage_connection2, $tileInfoT, $tileInfoW["owner"]);
									
								if ($claimStateT != 1 && $claimStateT > 1 && $tileInfoT["owner"] > 0) {
									$claimFound = false;
									for ($j=0; $j < count($tileInfoT["claims"]); $j++) {
										$claimInfoT = getClaimInfo($getPage_connection2, $tileInfoT["claims"][$j]);
											
										if ($claimInfoT["owner"] == $tileInfoW["owner"]) {
											setClaimInfo($getPage_connection2, $claimInfoT["id"], $claimInfoT["strength"] + 3, $claimInfoT["owner"]);
											$claimFound = true;
											break;
										} // if
									} // for
									if ($claimFound === false) {
										addClaimInfo($getPage_connection2, 3, $tileInfoT["owner"], $tileInfoT["id"]);
									} // if
								} // if
							} // if
						} // if
	
						// add to right
						if ($tileInfoW["xpos"] < 20) {
							// East
							$tileInfoT = getTileInfo($getPage_connection2, $tileInfoW["continent"], $tileInfoW["xpos"] + 1, $tileInfoW["ypos"]);
							$claimStateT = checkClaimsState($getPage_connection2, $tileInfoT, $tileInfoW["owner"]);
						
							if ($claimStateT != 1 && $claimStateT > 1 && $tileInfoT["owner"] > 0) {
								$claimFound = false;
								for ($j=0; $j < count($tileInfoT["claims"]); $j++) {
									$claimInfoT = getClaimInfo($getPage_connection2, $tileInfoT["claims"][$j]);
						
									if ($claimInfoT["owner"] == $tileInfoW["owner"]) {
										setClaimInfo($getPage_connection2, $claimInfoT["id"], $claimInfoT["strength"] + 3, $claimInfoT["owner"]);
										$claimFound = true;
										break;
									} // if
								} // for
								if ($claimFound === false) {
									addClaimInfo($getPage_connection2, 3, $tileInfoW["owner"], $tileInfoT["id"]);
								} // if
							} // if
						
							if ($tileInfoW["ypos"] > 1) {
								// Northeast
								$tileInfoT = getTileInfo($getPage_connection2, $tileInfoW["continent"], $tileInfoW["xpos"] + 1, $tileInfoW["ypos"] - 1);
								$claimStateT = checkClaimsState($getPage_connection2, $tileInfoT, $tileInfoW["owner"]);
									
								if ($claimStateT != 1 && $claimStateT > 1 && $tileInfoT["owner"] > 0) {
									$claimFound = false;
									for ($j=0; $j < count($tileInfoT["claims"]); $j++) {
										$claimInfoT = getClaimInfo($getPage_connection2, $tileInfoT["claims"][$j]);
											
										if ($claimInfoT["owner"] == $tileInfoW["owner"]) {
											setClaimInfo($getPage_connection2, $claimInfoT["id"], $claimInfoT["strength"] + 3, $claimInfoT["owner"]);
											$claimFound = true;
											break;
										} // if
									} // for
									if ($claimFound === false) {
										addClaimInfo($getPage_connection2, 3, $tileInfoW["owner"], $tileInfoT["id"]);
									} // if
								} // if
							} // if
						
							if ($tileInfoW["ypos"] < 20) {
								// Southeast
								$tileInfoT = getTileInfo($getPage_connection2, $tileInfoW["continent"], $tileInfoW["xpos"] + 1, $tileInfoW["ypos"] + 1);
								$claimStateT = checkClaimsState($getPage_connection2, $tileInfoT, $tileInfoW["owner"]);
						
								if ($claimStateT != 1 && $claimStateT > 1 && $tileInfoT["owner"] > 0) {
									$claimFound = false;
									for ($j=0; $j < count($tileInfoT["claims"]); $j++) {
										$claimInfoT = getClaimInfo($getPage_connection2, $tileInfoT["claims"][$j]);
						
										if ($claimInfoT["owner"] == $tileInfoW["owner"]) {
											setClaimInfo($getPage_connection2, $claimInfoT["id"], $claimInfoT["strength"] + 3, $claimInfoT["owner"]);
											$claimFound = true;
											break;
										} // if
									} // for
									if ($claimFound === false) {
										addClaimInfo($getPage_connection2, 3, $tileInfoW["owner"], $tileInfoT["id"]);
									} // if
								} // if
							} // if
						} // if
						
						if ($tileInfoW["ypos"] > 1) {
							// North
							$tileInfoT = getTileInfo($getPage_connection2, $tileInfoW["continent"], $tileInfoW["xpos"], $tileInfoW["ypos"] - 1);
							$claimStateT = checkClaimsState($getPage_connection2, $tileInfoT, $tileInfoW["owner"]);
								
							if ($claimStateT != 1 && $claimStateT > 1 && $tileInfoT["owner"] > 0) {
								$claimFound = false;
								for ($j=0; $j < count($tileInfoT["claims"]); $j++) {
									$claimInfoT = getClaimInfo($getPage_connection2, $tileInfoT["claims"][$j]);
										
									if ($claimInfoT["owner"] == $tileInfoW["owner"]) {
										setClaimInfo($getPage_connection2, $claimInfoT["id"], $claimInfoT["strength"] + 3, $claimInfoT["owner"]);
										$claimFound = true;
										break;
									} // if
								} // for
								if ($claimFound === false) {
									addClaimInfo($getPage_connection2, 3, $tileInfoW["owner"], $tileInfoT["id"]);
								} // if
							} // if
						} // if
						
						if ($tileInfoW["ypos"] < 20) {
							// South
							$tileInfoT = getTileInfo($getPage_connection2, $tileInfoW["continent"], $tileInfoW["xpos"], $tileInfoW["ypos"] + 1);
							$claimStateT = checkClaimsState($getPage_connection2, $tileInfoT, $tileInfoW["owner"]);
								
							if ($claimStateT != 1 && $claimStateT > 1 && $tileInfoT["owner"] > 0) {
								$claimFound = false;
								for ($j=0; $j < count($tileInfoT["claims"]); $j++) {
									$claimInfoT = getClaimInfo($getPage_connection2, $tileInfoT["claims"][$j]);
										
									if ($claimInfoT["owner"] == $tileInfoW["owner"]) {
										setClaimInfo($getPage_connection2, $claimInfoT["id"], $claimInfoT["strength"] + 3, $claimInfoT["owner"]);
										$claimFound = true;
										break;
									} // if
								} // for
								if ($claimFound === false) {
									addClaimInfo($getPage_connection2, 3, $tileInfoW["owner"], $tileInfoT["id"]);
								} // if
							} // if
						} // if					
					} // if
				} // if
			} // while
			$stmt->close();
		} else {
		} // else
	} // if
	
	// Update claims strengths
	
	$limit_allClaims = 0;
	if ($stmt = $getPage_connection2->prepare("SELECT COUNT(id) FROM claims")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->fetch();
		$limit_allClaims = $r_result;
		$stmt->close();
	} else {
		$endTurnFailed = "failed";
	} // else
	
	if ($limit_allClaims >= 1) {
		if ($stmt = $getPage_connection2->prepare("SELECT id FROM claims ORDER BY id ASC")) {
			$stmt->execute();
			$stmt->bind_result($r_result);
			$stmt->store_result();

			while ($stmt->fetch()) {
				$next_claims = $r_result;
				$claimInfoW = getClaimInfo($getPage_connection2,$next_claims);
		
				$nationInfoC = getNationInfo($getPage_connection2,$claimInfoW["owner"]);
		
				// start removing claim strength if the strike is still going on above 3
				if ($nationInfoC["strike"] > 3) {
					if (($claimInfoW["strength"] - 10) >= 0) { 
						$new_strength = $claimInfoW["strength"] - 10;
					} else {
						$new_strength = 0;
					} // else
					$new_strike = $nationInfoC["strike"] - 1;
					setNationInfo($getPage_connection2,$nationInfoC["id"],$nationInfoC["name"],$nationInfoC["home"],$nationInfoC["formal"],$nationInfoC["flag"],$nationInfoC["production"],$nationInfoC["money"],$nationInfoC["debt"],$nationInfoC["happiness"],$nationInfoC["food"],$nationInfoC["authority"],$nationInfoC["authorityChanged"],$nationInfoC["economy"],$nationInfoC["economyChanged"],$nationInfoC["organizations"],$nationInfoC["invites"],$nationInfoC["goods"],$nationInfoC["resources"],$nationInfoC["population"],$new_strike);
				} else {
					if (($claimInfoW["strength"] + 5) <= 1000) {
						$new_strength = $claimInfoW["strength"] + 5;
					} else {
						$new_strength = 1000;
					} // else
				} // else
		
				setClaimInfo($getPage_connection2,$next_claims,$new_strength,$claimInfoW["owner"]);
			} // while
			$stmt->close();
		} else {
		} // else
	} // if
} // preliminaryUpdates

function updateNations($getPage_connection2) {
	// main player sequence
	$_SESSION["scriptOutput"] = "<br /><br />Running main sequence...<br />";
	if ($stmt = $getPage_connection2->prepare("SELECT id FROM nations ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->store_result();
		
		while ($stmt->fetch()) {
			$next_nations = $r_result;
			$_SESSION["scriptOutput"] .= "<br />Setup nation...<br />";
			$nationInfoW = getNationInfo($getPage_connection2,$next_nations);
			$tradeInfoW = getTradeInfo($getPage_connection2,$next_nations);
	
			$formula = 0.0;
			$tax = 0.0;
			$new_production = 0;
			$new_money = $nationInfoW["money"];
			$new_food = $nationInfoW["food"];
			$new_happiness = 0.0;
			$new_goods = $nationInfoW["goods"];
			$new_resources = $nationInfoW["resources"];
			$new_limit = $tradeInfoW["limit"];
			$new_routes = array(0=>0);
			$new_worth = array(0=>0);
			$old_population = $nationInfoW["population"];
			$new_population = 0.0;
			$foreignTradePercent = 0.0;
			$productionPercent = 0.0;
			$efficiencyPercent = 0.0;
			$happinessPenalty = 0.0;
			$money_upkeep = 0.0;
			$food_upkeep = 0.0;
			$new_debt = $nationInfoW["debt"];
			$money_debt = 0.0;
			$food_debt = 0.0;
			$foreignTradePercent = $nationInfoW["economy"]*10;
			$productionPercent = 100 - $foreignTradePercent;
			$efficiencyPercent = $nationInfoW["authority"]*10;
			$happinessPenalty = $nationInfoW["authority"]*5;
			$productionInfoW = getProductionInfo($getPage_connection2,$next_nations);
			$using_production = 0.0;
			$used_production = $using_production;
			$new_prod = $new_goods;
			$new_strike = $nationInfoW["strike"];
			$new_tilesOwned = 0;
			$tradeCount = 0;
	
			$limit_allTilesOwned = 0;
			if ($stmt = $getPage_connection2->prepare("SELECT COUNT(id) FROM tilesmap WHERE owner=?")) {
				$stmt->bind_param("i", $next_nations);
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->fetch();
				$limit_allTilesOwned = $r_result;
				$stmt->close();
			} else {
				$endTurnFailed = "failed";
			} // else
	
			$limit_allImprovements = 0;
			if ($stmt = $getPage_connection2->prepare("SELECT COUNT(id) FROM improvementsmap")) {
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->fetch();
				$limit_allImprovements = $r_result;
				$stmt->close();
			} else {
				$endTurnFailed = "failed";
			} // else
	
			/********************************
			 Base Additions (including revenue) from improvements, tiles, etc.
			 ********************************/
	
			// get tiles info for production/claims/bonus addition
			$_SESSION["scriptOutput"] .= "Get tiles info for production/claims/bonus addition...<br />";
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM tilesmap ORDER BY id ASC LIMIT 1")) {
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->fetch();
				$next_tiles = $r_result;
				$stmt->close();
			} else {
				$next_tiles = 0;
			} // else
			while ($next_tiles > 0) {			
				$tileInfoW = getTileInfoByID($getPage_connection2, $next_tiles);				
						
				if ($tileInfoW["owner"] == $next_nations) {					
					// add claim strength if claim is dominant and tile is controlled
					$claimsStateW = checkClaimsState($getPage_connection2, $tileInfoW, $next_nations);				
					if ($claimsStateW == 1) {
						for ($e=0; $e < count($tileInfoW["claims"]); $e++) {
							$claimInfoW = getClaimInfo($getPage_connection2, $tileInfoW["claims"][$e]);
							if ($claimInfoW["owner"] == $next_nations) {
								if ($claimInfoW["strength"] > 0) {
									$bonus_new_strength = 0;
									for ($c=0; $c < count($tileInfoW["improvements"]); $c++) {
										$improvementInfoV = getImprovementInfo($getPage_connection2, $tileInfoW["improvements"][$c]);
										if ($improvementInfoV["type"] == 1) {
											$bonus_new_strength = 3;
										} // if
									} // for
									$new_strength = $bonus_new_strength + $claimInfoW["strength"] + 2;
								} // if
								setClaimInfo($getPage_connection2, $claimInfoW["id"], $new_strength, $claimInfoW["owner"]);
							} // if
						} // for
					} // if
					
					// trade
					$_SESSION["scriptOutput"] .= "Trade Count adjust...<br />";
					
					for ($ss=0; $ss < count ($tileInfoW["improvements"]); $ss++) {
						$improvementInfoF = getImprovementInfo($getPage_connection2,$tileInfoW["improvements"][$ss]);
						if ($improvementInfoF["type"] == 1 || $improvementInfoF["type"] == 2) {
							for ($cc=0; $cc < count($improvementInfoF["owners"]); $cc++) {
								if ($improvementInfoF["owners"] == $next_nations) {
									if ($improvementInfoF["type"] == 1) {
										$tradeCount++;
									} else if ($improvementInfoF["type"] == 2) {
										$tradeCount = $tradeCount + 2;
									} // else if
								} // if
							} // for
						} // if
					} // for
					
					// claims for happiness
					$_SESSION["scriptOutput"] .= "Happiness+Claims adjust...<br />";
					
					$claimsStateW = checkClaimsState($getPage_connection2, $tileInfoW, $next_nations);				
					
					// set happiness addition dependent on claim to tile
					
					// if current nation claims successfully
					if ($claimsStateW == 1) {
						$new_happiness = $new_happiness + 5;
					
					// if enemy nation claims successfully
					} else if ($claimsStateW == 2) {
						$new_happiness = $new_happiness + 1;
					
					// if claim is contested and player is involved
					} else if ($claimsStateW == 3) {
						$new_happiness = $new_happiness + 2;
					
					// if claim is contested and player is not involved
					} else if ($claimsStateW == 4) {
					
					// default to enemy claim
					} else {
						// bad
						$new_happiness = $new_happiness + 1;
					} // else
					
					// terrain production modifiers
					
					$_SESSION["scriptOutput"] .= "Terrain production modifiers...<br />";
																		
					$terrainInfoW = array("productionModifier"=>0);
					if ($stmt = $getPage_connection2->prepare("SELECT productionModifier FROM terrain WHERE id=? LIMIT 1")) {
						$stmt->bind_param("i", $tileInfoW["terrain"]);
						$stmt->execute();
						$stmt->bind_result($r_productionModifier);
						$stmt->fetch();
						$terrainInfoW["productionModifier"] = $r_productionModifier;
						$stmt->close();
					} else {
					} // else
					
					if ($terrainInfoW["productionModifier"] >= 1) {
						$mod = 0.01;
						$new_production = $new_production + (($terrainInfoW["productionModifier"]*$mod)*2.0);
					} else {
						$new_production = $new_production + 2;
					} // else
				} // if
	
				if ($stmt = $getPage_connection2->prepare("SELECT id FROM tilesmap WHERE id = (SELECT MIN(id) FROM tilesmap WHERE id > ?) ORDER BY id LIMIT 1")) {
					$stmt->bind_param("i", $next_tiles);
					$stmt->execute();
					$stmt->bind_result($r_result);
					$stmt->fetch();
					$next_tiles = $r_result;
					$stmt->close();
				} else {
					$next_tiles = 0;
				} // else
			} // while
			
			// add to nation variables based on tile number
			$new_money = $new_money + (2*$limit_allTilesOwned);
			
			//  get improvements
			$_SESSION["scriptOutput"] .= "Get improvements info for production, money, population mods...<br />";
			
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM improvementsmap ORDER BY id ASC LIMIT 1")) {
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->fetch();
				$next_improvements = $r_result;
				$stmt->close();
			} else {
				$next_improvements = 0;
			} // else
			while ($next_improvements > 0) {
				$improvementInfoW = getImprovementInfo($getPage_connection2,$next_improvements);
			
				for ($z=0; $z < count($improvementInfoW["owners"]); $z++) {
					if ($improvementInfoW["owners"][$z] == $next_nations) {
						// capital
						if ($improvementInfoW["type"] == 1) {
							$new_money = $new_money + ((60.0*(0.25*$improvementInfoW["level"])) / count($improvementInfoW["owners"]));
							$new_production = $new_production + ((20.0*(0.25*$improvementInfoW["level"])) / count($improvementInfoW["owners"]));					
							
							$tileInfoG = getTileInfo($getPage_connection2, $improvementInfoW["continent"], $improvementInfoW["xpos"], $improvementInfoW["ypos"]);				
							$generate_population = mt_rand(200.0*(0.25*$improvementInfoW["level"]), 800.0*(0.25*$improvementInfoW["level"]));
							$new_tile_population = $tileInfoG["population"] + ($generate_population / count($improvementInfoW["owners"]));						
							setTileInfo($getPage_connection2, $tileInfoG["id"], $tileInfoG["continent"], $tileInfoG["xpos"], $tileInfoG["ypos"], $tileInfoG["terrain"], $tileInfoG["resources"], $tileInfoG["improvements"], $tileInfoG["owner"], $tileInfoG["claims"], $new_tile_population);
							
						// town
						} else if ($improvementInfoW["type"] == 2) {
							$new_money = $new_money + ((30.0*(0.25*$improvementInfoW["level"])) / count($improvementInfoW["owners"]));
							
							$tileInfoG = getTileInfo($getPage_connection2, $improvementInfoW["continent"], $improvementInfoW["xpos"], $improvementInfoW["ypos"]);
							$generate_population = mt_rand(200.0*(0.25*$improvementInfoW["level"]), 400.0*(0.25*$improvementInfoW["level"]));
							$new_tile_population = $tileInfoG["population"] + ($generate_population / count($improvementInfoW["owners"]));
							setTileInfo($getPage_connection2, $tileInfoG["id"], $tileInfoG["continent"], $tileInfoG["xpos"], $tileInfoG["ypos"], $tileInfoG["terrain"], $tileInfoG["resources"], $tileInfoG["improvements"], $tileInfoG["owner"], $tileInfoG["claims"], $new_tile_population);
						
						// industry
						} else if ($improvementInfoW["type"] == 3) {
							$new_production = $new_production + ((20.0*(0.25*$improvementInfoW["level"])) / count($improvementInfoW["owners"]));
						
						// farm
						} else if ($improvementInfoW["type"] == 4) {
							$generate_food = mt_rand(1000.0*(0.25*$improvementInfoW["level"]), 6000.0*(0.25*$improvementInfoW["level"]));
							$new_food = $new_food + ($generate_food / count($improvementInfoW["owners"]));
						
						// depot
						} else if ($improvementInfoW["type"] == 5) {
						
						// mill
						} else if ($improvementInfoW["type"] == 6) {
							for ($d=0; $d < count($improvementInfoW["usingResources"]); $d++) {
								$resourceInfoW = getResourceInfo($getPage_connection2,$improvementInfoW["usingResources"][$d]);
								if ($resourceInfoW["type"] == 1 && $resourceInfoW["capacity"] >= 5) {
									$initialExtract = 0.02*$resourceInfoW["capacity"];
									$extractAmount = (($initialExtract*(0.25*$improvementInfoW["level"])) / count($improvementInfoW["owners"]));
									$new_resources[0] = $new_resources[0] + $extractAmount;
									$new_capacity = $resourceInfoW["capacity"] - $extractAmount;
									setResourceInfo($getPage_connection2,$resourceInfoW["id"],$resourceInfoW["type"],$new_capacity);
									break;
								} // if
							} // for
						
						// reserve
						} else if ($improvementInfoW["type"] == 7) {
							$new_happiness = $new_happiness + (0.01 / count($improvementInfoW["owners"]));
						
						// mine
						} else if ($improvementInfoW["type"] == 8) {
							for ($d=0; $d < count($improvementInfoW["usingResources"]); $d++) {
								$resourceInfoW = getResourceInfo($getPage_connection2,$improvementInfoW["usingResources"][$d]);
								if ($resourceInfoW["type"] == 2 && $resourceInfoW["capacity"] >= 5) {
									$initialExtract = 0.02*$resourceInfoW["capacity"];
									$extractAmount = (($initialExtract*(0.25*$improvementInfoW["level"])) / count($improvementInfoW["owners"]));								
									$new_resources[1] = $new_resources[1] + $extractAmount;
									$new_capacity = $resourceInfoW["capacity"] - $extractAmount;
									setResourceInfo($getPage_connection2,$resourceInfoW["id"],$resourceInfoW["type"],$new_capacity);
									break;
								} // if
							} // for
						
						// well
						} else if ($improvementInfoW["type"] == 9) {
							for ($d=0; $d < count($improvementInfoW["usingResources"]); $d++) {
								$resourceInfoW = getResourceInfo($getPage_connection2,$improvementInfoW["usingResources"][$d]);
								if ($resourceInfoW["type"] == 3 && $resourceInfoW["capacity"] >= 5) {
									$initialExtract = 0.02*$resourceInfoW["capacity"];
									$extractAmount = (($initialExtract*(0.25*$improvementInfoW["level"])) / count($improvementInfoW["owners"]));
									$new_resources[2] = $new_resources[2] + $extractAmount;
									$new_capacity = $resourceInfoW["capacity"] - $extractAmount;
									setResourceInfo($getPage_connection2,$resourceInfoW["id"],$resourceInfoW["type"],$new_capacity);
									break;
								} // if
							} // for
						
						// dam
						} else if ($improvementInfoW["type"] == 10) {
							for ($d=0; $d < count($improvementInfoW["usingResources"]); $d++) {
								$resourceInfoW = getResourceInfo($getPage_connection2,$improvementInfoW["usingResources"][$d]);
								if ($resourceInfoW["type"] == 4 && $resourceInfoW["capacity"] >= 5) {
									$new_production = $new_production + ((22.0*(0.25*$improvementInfoW["level"])) / count($improvementInfoW["owners"]));
									setResourceInfo($getPage_connection2,$resourceInfoW["id"],$resourceInfoW["type"],$new_capacity);
									break;
								} // if
							} // for					
						} // else if
					} // if
				} // for
			
				if ($stmt = $getPage_connection2->prepare("SELECT id FROM improvementsmap WHERE id = (SELECT MIN(id) FROM improvementsmap WHERE id > ?) ORDER BY id LIMIT 1")) {
					$stmt->bind_param("i", $next_improvements);
					$stmt->execute();
					$stmt->bind_result($r_result);
					$stmt->fetch();
					$next_improvements = $r_result;
					$stmt->close();
				} else {
					$next_improvements = 0;
				} // else
			} // while
			
			/********************************
			 HAPPINESS EFFECTS
			 ********************************/	
			$_SESSION["scriptOutput"] .= "Change happiness...<br />";
			
			// penalty for lack of consumer goods (basic and luxury), less penalty with more goods
			// luxury goods provide bigger boost
			$consumptionPenalty = 3.25 * $limit_allTilesOwned;
			$goodsPenalty = 0.0;
			if ($consumptionPenalty >= (($new_goods[4]*0.00003)*$limit_allTilesOwned) + (($new_goods[5]*0.00005)*$limit_allTilesOwned)) {
				$goodsPenalty = $consumptionPenalty - (($new_goods[4]*0.00003)*$limit_allTilesOwned) + (($new_goods[5]*0.00005)*$limit_allTilesOwned);
			} else {
				$goodsPenalty = 0.0;
			} // else
			
			// happiness
			$new_happiness = ($new_happiness - ((($happinessPenalty + $goodsPenalty)*$new_happiness)/100)) / $limit_allTilesOwned;
			
			// set the rate of effect of happiness on production,taxation
			// Happiest
			if ($new_happiness >= 4) {
				$new_production = $new_production + ($new_production*0.25);
				$efficiencyPercent = $efficiencyPercent + ($efficiencyPercent*0.25);
				// Very Happy
			} else if ($new_happiness >= 3) {
				$new_production = $new_production + ($new_production*0.10);
				$efficiencyPercent = $efficiencyPercent + ($efficiencyPercent*0.10);
				// Happy
			} else if ($new_happiness >= 2) {
				$new_production = $new_production + ($new_production*0.05);
				$efficiencyPercent = $efficiencyPercent + ($efficiencyPercent*0.05);
				// Content (nothing happens)
			} else if ($new_happiness >= 1) {
				// Unhappy
			} else {
				$new_production = $new_production - ($new_production*0.25);
				$efficiencyPercent = $efficiencyPercent - ($efficiencyPercent*0.25);
			} // else
			
			/********************************
			 TRADE
			 ********************************/
					
			$_SESSION["scriptOutput"] .= "Update trade routes, worth, offers...<br />";
			
			// cut off trade routes to the limit that exists and update trade info
			// this limits trade routes to currently controlled tiles
			$new_limit = $tradeCount;
			if (count($tradeInfoW["routes"]) < $new_limit) {
				for ($r=0; $r < $new_limit; $r++) {
					$new_routes[$r] = $tradeInfoW["routes"][$r];
				} // for
			} else {
				for ($r=0; $r < count($tradeInfoW["routes"]); $r++) {
					$new_routes[$r] = $tradeInfoW["routes"][$r];
				} // for
			} // else
			setTradeInfo($getPage_connection2,$tradeInfoW["id"],$next_nations,$new_routes,$new_limit);
					
			// do individual trades action if current nation involved
			$next_offers = 1;
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM offers ORDER BY id ASC LIMIT 1")) {
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->fetch();
				$next_offers = $r_result;
				$stmt->close();
			} else {
				$next_offers = 0;
			} // else
			while ($next_offers > 0) {
				$offerInfoW = getOfferInfo($getPage_connection2,$next_offers);
				if ($offerInfoW["fromNation"] == $next_nations) {
					if ($offerInfoW["turns"] > $offerInfoW["counter"]) {
						if ($offerInfoW["status"] == 1) {
							processOffer($getPage_connection2,$offersInfoW);
							
							$new_offer = $offerInfoW["counter"] + 1;
							
							// remove if trade offer has expired
							if ($new_counter > $offerInfoW["turns"]) {
								deleteOfferInfo($getPage_connection2, $offerInfoW["id"]);
							} else {
								setOfferInfo($getPage_connection2, $offerInfoW["id"], $offerInfoW["fromNation"], $offerInfoW["toNation"], $offerInfoW["givingItems"], $offerInfoW["receivingItems"], $offerInfoW["givingQuantities"], $offerInfoW["receivingQuantities"], $offerInfoW["givingTypes"], $offerInfoW["receivingTypes"], $offerInfoW["turns"], $new_counter, $offerInfoW["status"]);
							} // else						
						} // if
					} // if
				} // if
			
				if ($stmt = $getPage_connection2->prepare("SELECT id FROM offers WHERE id = (SELECT MIN(id) FROM offers WHERE id > ?) ORDER BY id LIMIT 1")) {
					$stmt->bind_param("i", $next_offers);
					$stmt->execute();
					$stmt->bind_result($r_result);
					$stmt->fetch();
					$next_offers = $r_result;
					$stmt->close();
				} else {
					$next_offers = 0;
				} // else
			} // while
			
			// trade agreements
			$tradeBonus = 0.0;
			$productionBonus = 0.0;
			$happinessPenaltyFromTrade = 0.0;
			for ($v=0; $v < count($tradeInfoW["routes"]); $v++) {
				$agreementInfoQ = getAgreementInfo($getPage_connection2, $tradeInfoW["routes"][$v]);
				if ($agreementInfoQ["fromNation"] == $next_nations) {
					if ($offerInfoW["status"] == 1) {
						// receive money/production
						$worth1 = 0.0; // total worth of trade
						$nationFrom1 = getNationInfo($getPage_connection2, $agreementInfoQ["fromNation"]);
						$nationTo1 = getNationInfo($getPage_connection2, $agreementInfoQ["toNation"]);
						
						// figure out wealth and production worth
						$totalWealth = $nationFrom1["money"] + $nationTo1["money"];
						$totalProduction = $nationFrom1["production"] + $nationTo1["production"];
						
						// high policy number = protectionism, low policy number = free trade 
						// max 25% bonus from policy for either production or wealth
						$tradeBonus = $tradeBonus + (($totalWealth*(0.01 + (0.025* (10-$agreementInfoQ["policy"])))) / 2);
						$productionBonus = $productionBonus + (($totalProduction*(0.008 + (0.025*($agreementInfoQ["policy"])))) / 2); 		
						
						// happiness penalty for clashing ideologies
						if ($agreementInfoQ["policy"] > $nationInfoW["economy"]) {
							if (($agreementInfoQ["policy"] - $nationInfoW["economy"]) > 0 && ($agreementInfoQ["policy"] - $nationInfoW["economy"]) <= 1) {
								$happinessPenaltyFromTrade = $happinessPenaltyFromTrade + 0.005;						
							} else if (($agreementInfoQ["policy"] - $nationInfoW["economy"]) >= 2 && ($agreementInfoQ["policy"] - $nationInfoW["economy"]) <= 3) {
								$happinessPenaltyFromTrade = $happinessPenaltyFromTrade + 0.01;
							} else if (($agreementInfoQ["policy"] - $nationInfoW["economy"]) >= 4 && ($agreementInfoQ["policy"] - $nationInfoW["economy"]) <= 5) {
								$happinessPenaltyFromTrade = $happinessPenaltyFromTrade + 0.02;
							} else if (($agreementInfoQ["policy"] - $nationInfoW["economy"]) >= 6 && ($agreementInfoQ["policy"] - $nationInfoW["economy"]) <= 7) {
								$happinessPenaltyFromTrade = $happinessPenaltyFromTrade + 0.03;
							} else if (($agreementInfoQ["policy"] - $nationInfoW["economy"]) >= 8 && ($agreementInfoQ["policy"] - $nationInfoW["economy"]) <= 9) {
								$happinessPenaltyFromTrade = $happinessPenaltyFromTrade + 0.04;		
							} else if (($agreementInfoQ["policy"] - $nationInfoW["economy"]) >= 10) {
								$happinessPenaltyFromTrade = $happinessPenaltyFromTrade + 0.05;
							} // else if
						} else if ($agreementInfoQ["policy"] < $nationInfoW["economy"]) {
							if (($agreementInfoQ["economy"] - $nationInfoW["policy"]) > 0 && ($agreementInfoQ["economy"] - $nationInfoW["policy"]) <= 1) {
								$happinessPenaltyFromTrade = $happinessPenaltyFromTrade + 0.005;						
							} else if (($agreementInfoQ["economy"] - $nationInfoW["policy"]) >= 2 && ($agreementInfoQ["economy"] - $nationInfoW["policy"]) <= 3) {
								$happinessPenaltyFromTrade = $happinessPenaltyFromTrade + 0.01;
							} else if (($agreementInfoQ["economy"] - $nationInfoW["policy"]) >= 4 && ($agreementInfoQ["economy"] - $nationInfoW["policy"]) <= 5) {
								$happinessPenaltyFromTrade = $happinessPenaltyFromTrade + 0.02;
							} else if (($agreementInfoQ["economy"] - $nationInfoW["policy"]) >= 6 && ($agreementInfoQ["economy"] - $nationInfoW["policy"]) <= 7) {
								$happinessPenaltyFromTrade = $happinessPenaltyFromTrade + 0.03;
							} else if (($agreementInfoQ["economy"] - $nationInfoW["policy"]) >= 8 && ($agreementInfoQ["economy"] - $nationInfoW["policy"]) <= 9) {
								$happinessPenaltyFromTrade = $happinessPenaltyFromTrade + 0.04;		
							} else if (($agreementInfoQ["economy"] - $nationInfoW["policy"]) >= 10) {
								$happinessPenaltyFromTrade = $happinessPenaltyFromTrade + 0.05;
							} // else if
						} else {						
						}
						
						$new_counter = $agreementInfoQ["counter"] + 1;
						
						// remove if trade agreement has expired
						if ($new_counter > $agreementInfoQ["turns"]) {
							deleteAgreementInfo($getPage_connection2, $agreementInfoQ["id"]);
						} else {					
							setAgreementInfo($getPage_connection2, $agreementInfoQ["id"], $agreementInfoQ["policy"], $agreementInfoQ["turns"], $new_counter, $agreementInfoQ["toNation"], $agreementInfoQ["fromNation"], $agreementInfoQ["status"]);				
						} // else
					} // if
				} // if
			} // for
			
			$new_happiness = $new_happiness - $happinessPenaltyFromTrade;
			
			$new_production = $new_production + $productionBonus;
			
			$formula = $tradeBonus * ($foreignTradePercent*0.01);
			
			// tax
			$tax = ($new_population*0.1)*($efficiencyPercent*0.01);
					
			// production
			$formula = $formula + (($productionPercent*0.01) * ($new_production * 10));
			
			/********************************
			 PRODUCTION COSTS
			********************************/
			
			$_SESSION["scriptOutput"] .= "Produce stuff...<br />";
			
			// figure out spending percentage
			$using_production = $new_production*($productionInfoW["spending"]*0.01);
			// set production used to new spending amount
			$used_production = $using_production;
			// percentage of expenditure
			$ratios = $productionInfoW["ratios"];
			
			for ($t=0; $t < count($ratios); $t++) {
				$ratio_id = $t + 1;
				$goodsInfoW = getGoodsInfo($getPage_connection2,$ratio_id);
							
				if ($goodsInfoW["id"] >= 1) {		
					$availableProduction = ($used_production*($ratios[$t]*0.01));
					
					// problem with this while loop: it's endless
					while ($availableProduction > 0) {
						$canProduce = false;
				
						if ($goodsInfoW["productionRequired"] <= $availableProduction) {
							if ($goodsInfoW["foodRequired"] <= $new_food) {
								$checkForResourceTypes = true;
								if (count($goodsInfoW["resourceTypesRequired"]) <= 1) {
									if ($goodsInfoW["resourceTypesRequired"][0] == 0) {
										$checkForResourceTypes = false;
									} // if
								} // if
								
								if ($checkForResourceTypes === true) {
									for ($h=0; $h < count($goodsInfoW["resourceTypesRequired"]); $h++) {
										// if resources are required
										if ($goodsInfoW["resourceTypesRequired"][$h] >= 1) {
											$resourceArraySlot1 = $goodsInfoW["resourceTypesRequired"][$h] - 1;
											if ($new_resources[$resourceArraySlot1] >= $goodsInfoW["resourceQuantitiesRequired"][$h]) {
												$canProduce = true;
											} else {
												$canProduce = false;
												break;
											} // else
										} else {
											$canProduce = true;
										} // else
									} // for
								} else {
									$canProduce = true;
								} // else
							} else {
								$canProduce = false;
							} // else
						} else {
							$canProduce = false;
						} // else
				
						if ($canProduce === true) {
							for ($j=0; $j < count($goodsInfoW["improvementTypesRequired"]); $j++) {
								$typeCount = 0;
								// if improvements are required
								if ($goodsInfoW["improvementTypesRequired"][$j] >= 1) {														
									$next_improvements2 = 1;
									if ($stmt = $getPage_connection2->prepare("SELECT id FROM improvementsmap ORDER BY id ASC LIMIT 1")) {
										$stmt->execute();
										$stmt->bind_result($r_result);
										$stmt->fetch();
										$next_improvements2 = $r_result;
										$stmt->close();
									} else {
										$next_improvements2 = 0;
									} // else
									while ($next_improvements2 > 0) {
										$improvementInfoY = getImprovementInfo($getPage_connection2,$next_improvements2);
										if ($improvementInfoY["type"] == $goodsInfoW["improvementTypesRequired"][$j]) {
											for ($f=0; $f < count($improvementInfoY["owners"]); $f++) {
												if ($improvementInfoY["owners"][$f] == $next_nations) {
													$typeCount++;
												} // if
											} // for
										} // if	
	
										if ($typeCount >= $goodsInfoW["improvementQuantitiesRequired"][$j]) {
											$canProduce = true;
											break;
										} // if
									
										if ($stmt = $getPage_connection2->prepare("SELECT id FROM improvementsmap WHERE id = (SELECT MIN(id) FROM improvementsmap WHERE id > ?) ORDER BY id LIMIT 1")) {
											$stmt->bind_param("i", $next_improvements2);
											$stmt->execute();
											$stmt->bind_result($r_result);
											$stmt->fetch();
											$next_improvements2 = $r_result;
											$stmt->close();
										} else {
											$next_improvements2 = 0;
										} // else
									} // while	
	
									if ($typeCount < $goodsInfoW["improvementQuantitiesRequired"][$j]) {
										$canProduce = false;
										break;
									} // if
								} // if
							} // for
						} // if
				
						// able to produce good, proceed
						if ($canProduce === true) {
							// subtract from available production, nation's food total, nation's resources totals
							$availableProduction = $availableProduction - $goodsInfoW["productionRequired"]; 
							$new_food = $new_food - $goodsInfoW["foodRequired"];		
							if ($checkForResourceTypes === true) {
								for ($k=0; $k < count($goodsInfoW["resourceTypesRequired"]); $k++) {
									// if resources are required
									if ($goodsInfoW["resourceTypesRequired"][$k] >= 1) {
										$resourceArraySlot = $goodsInfoW["resourceTypesRequired"][$k] - 1;
										$new_resources[$resourceArraySlot] = $new_resources[$resourceArraySlot] - $goodsInfoW["resourceQuantitiesRequired"][$k];
									} // if
								} // for
							} // if
							
							// add new good
							$new_goods[$t] = $new_goods[$t] + 1;
						} else {
							break;
						} // else
					} // while
				} // if
			} // for
			
			
			$new_production = $new_production - $used_production;
			
			$_SESSION["scriptOutput"] .= "prod: ".$new_production."<br />";
			$_SESSION["scriptOutput"] .= "formula: ".$formula."<br />";
			$_SESSION["scriptOutput"] .= "tax: ".$tax."<br />";
			$_SESSION["scriptOutput"] .= "money: ".$new_money."<br />";
			
			// final money calculation
			$new_money = $new_money + $formula + $tax;
			
			/********************************
			 COSTS AND UPKEEP
			 ********************************/
			
			$_SESSION["scriptOutput"] .= "Unit upkeep...<br />";
			
			// unit upkeep
			$next_units = 1;
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM unitsmap ORDER BY id ASC LIMIT 1")) {
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->fetch();
				$next_units = $r_result;
				$stmt->close();
			} else {
				$next_units = 0;
			} // else
			while ($next_units > 0) {
				$unitInfoW = getUnitInfoByID($getPage_connection2,$next_units);
			
				if ($unitInfoW["owner"] == $next_nations) {
					$unitTypeInfoW = getUnitTypeInfo($getPage_connection2,$unitInfoW["type"]);
			
					$tileInfoW = getTileInfo($getPage_connection2,$unitInfoW["continent"],$unitInfoW["xpos"],$unitInfoW["ypos"]);
					$terrainInfoW = getTerrainInfo($getPage_connection2,$tileInfoW["terrain"]);
					
					// figure out nearest supply depot for supply line distance
						
					$next_improvements1 = 1;
					$best_difference = 9999;
					$best_continent = 0;
					if ($stmt = $getPage_connection2->prepare("SELECT id FROM improvementsmap ORDER BY id ASC LIMIT 1")) {
						$stmt->execute();
						$stmt->bind_result($r_result);
						$stmt->fetch();
						$next_improvements1 = $r_result;
						$stmt->close();
					} else {
						$next_improvements1 = 0;
					} // else
					while ($next_improvements1 > 0) {
						$improvementInfoWA = getImprovementInfo($getPage_connection2, $next_improvements1);
						
						if ($improvementInfoWA["id"] >= 1) {						
							for ($v=0; $v < count($improvementInfoWA["owners"]); $v++) {		
								if ($improvementInfoWA["owners"][$v] == $next_nations) {	
									if ($improvementInfoWA["type"] == 5) {		
										$current_difference = 0;
										if ($improvementInfoWA["continent"] == $unitInfoW["continent"]) {
											$best_difference = 0;
											$best_continent = $improvementInfoWA["continent"];
											break;
										} else if ($improvementInfoWA["continent"] > $unitInfoW["continent"]) {
											$current_difference = $improvementInfoWA["continent"] - $unitInfoW["continent"];
										} else if ($improvementInfoWA["continent"] < $unitInfoW["continent"]) {
											$current_difference = $unitInfoW["continent"] - $improvementInfoWA["continent"];
										} // else if
												
										if ($current_difference < $best_difference) {
											$best_difference = $current_difference;
											$best_continent = $improvementInfoWA["continent"];
										} // if
									} // if
								} // if
							} // for
						} // if
							
						if ($stmt = $getPage_connection2->prepare("SELECT id FROM improvementsmap WHERE id = (SELECT MIN(id) FROM improvementsmap WHERE id > ?) ORDER BY id LIMIT 1")) {
							$stmt->bind_param("i", $next_improvements1);
							$stmt->execute();
							$stmt->bind_result($r_result);
							$stmt->fetch();
							$next_improvements1 = $r_result;
							$stmt->close();
						} else {
							$next_improvements1 = 0;
						} // else
					} // while
					
					$distance = $best_difference;
					
					$distanceModifier = 5*$distance;
					
					if ($terrainInfoW["upkeepModifier"] >= 1) {
						$mod = 0.01;
						$food_upkeep = $food_upkeep + ($unitTypeInfoW["foodRequired"] /4) + (($unitTypeInfoW["foodRequired"] /4)*(($terrainInfoW["upkeepModifier"] + $distanceModifier)*$mod));
						$money_upkeep = $money_upkeep + ($unitTypeInfoW["baseCost"] /4) + (($unitTypeInfoW["baseCost"] /4)*(($terrainInfoW["upkeepModifier"] + $distanceModifier)*$mod));
					} else {
						$food_upkeep = $food_upkeep + ($unitTypeInfoW["foodRequired"]/4);
						$money_upkeep = $money_upkeep + ($unitTypeInfoW["baseCost"]/4);
					} // else
				} // if
			
				if ($stmt = $getPage_connection2->prepare("SELECT id FROM unitsmap WHERE id = (SELECT MIN(id) FROM unitsmap WHERE id > ?) ORDER BY id LIMIT 1")) {
					$stmt->bind_param("i", $next_units);
					$stmt->execute();
					$stmt->bind_result($r_result);
					$stmt->fetch();
					$next_units = $r_result;
					$stmt->close();
				} else {
					$next_units = 0;
				} // else
			} // while
			
			$_SESSION["scriptOutput"] .= "Improvement upkeep...<br />";
			
			// improvement upkeep and nation's population update
			$next_tiles = 1;
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM tilesmap ORDER BY id ASC LIMIT 1")) {
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->fetch();
				$next_tiles = $r_result;
				$stmt->close();
			} else {
				$next_tiles = 0;
			} // else
			while ($next_tiles > 0) {
				$tileInfoW = getTileInfoByID($getPage_connection2,$next_tiles);
				$terrainInfoW = getTerrainInfo($getPage_connection2,$tileInfoW["terrain"]);
			
				for ($y=0; $y < count($tileInfoW["improvements"]); $y++) {
					$improvementInfoW = getImprovementInfo($getPage_connection2,$tileInfoW["improvements"][$y]);
					$improvementTypeInfoW = getImprovementTypeInfo($getPage_connection2, $improvementInfoW["type"]);
					for ($z=0; $z < count($improvementInfoW["owners"]); $z++) {
						if ($improvementInfoW["owners"][$z] == $next_nations) {
							// figure out distance costs
							if ($nationInfoW["home"] > $tileInfoW["continent"]) {
								$distanceModifier = $nationInfoW["home"] - $tileInfoW["continent"];
							} else {
								$distanceModifier = $tileInfoW["continent"] - $nationInfoW["home"];
							}
	
							$mod = 0.01;
							$money_upkeep = $money_upkeep + ($improvementTypeInfoW["baseCost"] / count($improvementInfoW["owners"])) + (($improvementTypeInfoW["baseCost"] / count($improvementInfoW["owners"])) *( ($distanceModifier + $terrainInfoW["upkeepModifier"])*$mod));
						} // if
					} // for
				} // for
				
				if ($tileInfoW["owner"] == $next_nations) {
					$new_population = $new_population + $tileInfoW["population"];			
				} // if
			
				if ($stmt = $getPage_connection2->prepare("SELECT id FROM tilesmap WHERE id = (SELECT MIN(id) FROM tilesmap WHERE id > ?) ORDER BY id LIMIT 1")) {
					$stmt->bind_param("i", $next_tiles);
					$stmt->execute();
					$stmt->bind_result($r_result);
					$stmt->fetch();
					$next_tiles = $r_result;
					$stmt->close();
				} else {
					$next_tiles = 0;
				} // else
			} // while
			
			/********************************
			FOOD
			********************************/
			
			$_SESSION["scriptOutput"] .= "Feeding time...<br />";
			$_SESSION["scriptOutput"] .= "food: ".$new_food."<br />";			
			
			// food debt
			if ($new_food < $food_upkeep) {
				$food_debt = $food_upkeep - $new_food;
				$new_food = 0;
			} else {
				$new_food = $new_food - $food_upkeep;
			} // else
			
			// if food can feed new population, then allow for the growth
			if ($new_food >= $new_population) {			
				$surplus = $new_food - $new_population;
				$popGrowth = 0.10*($surplus / $limit_allTilesOwned);
				$popGrowthInt = round($popGrowth, 0, PHP_ROUND_HALF_UP);
				
				$new_population = 0; // reset population to assign new value based on pop changes
				
				$next_tiles = 1;
				if ($stmt = $getPage_connection2->prepare("SELECT id FROM tilesmap ORDER BY id ASC LIMIT 1")) {
					$stmt->execute();
					$stmt->bind_result($r_result);
					$stmt->fetch();
					$next_tiles = $r_result;
					$stmt->close();
				} else {
					$next_tiles = 0;
				} // else
				while ($next_tiles > 0) {				
					$tileInfoD = getTileInfoByID($getPage_connection2, $next_tiles);
					
					$new_tile_population = 0;
					
					if ($tileInfoD["owner"] == $next_nations) {
						$new_tile_population = $tileInfoD["population"] + $popGrowthInt;
						setTileInfo($getPage_connection2, $tileInfoD["id"], $tileInfoD["continent"], $tileInfoD["xpos"], $tileInfoD["ypos"], $tileInfoD["terrain"], $tileInfoD["resources"], $tileInfoD["improvements"], $tileInfoD["owner"], $tileInfoD["claims"], $new_tile_population);			
						$new_population = $new_population + $new_tile_population;
					} // if
					
					if ($stmt = $getPage_connection2->prepare("SELECT id FROM tilesmap WHERE id = (SELECT MIN(id) FROM tilesmap WHERE id > ?) ORDER BY id LIMIT 1")) {
						$stmt->bind_param("i", $next_tiles);
						$stmt->execute();
						$stmt->bind_result($r_result);
						$stmt->fetch();
						$next_tiles = $r_result;
						$stmt->close();
					} else {
						$next_tiles = 0;
					} // else
				} // while					
				
			// if food cannot even sustain the population, it shrinks
			} else if ($new_food < $new_population) {			
				// if there is food debt, then set food debt + random number to be deficit
				if ($food_debt >= 1) {
					$rand = mt_rand(1,50);			
					$deficit = $food_debt + $rand;
					$popShrink = 0.15*($deficit / $limit_allTilesOwned);
					$popShrinkInt = round($popShrink, 0, PHP_ROUND_HALF_DOWN);
					
					$new_population = 0; // reset population to assign new value based on pop changes
						
					$next_tiles = 1;
					if ($stmt = $getPage_connection2->prepare("SELECT id FROM tilesmap ORDER BY id ASC LIMIT 1")) {
						$stmt->execute();
						$stmt->bind_result($r_result);
						$stmt->fetch();
						$next_tiles = $r_result;
						$stmt->close();
					} else {
						$next_tiles = 0;
					} // else
					while ($next_tiles > 0) {
						$tileInfoD = getTileInfoByID($getPage_connection2, $next_tiles);
					
						$new_tile_population = 0;
					
						if ($tileInfoD["owner"] == $next_nations) {
							$new_tile_population = $tileInfoD["population"] - $popShrinkInt;
							if ($new_tile_population < 0) {
								$new_tile_population = 0;
							} // if
							setTileInfo($getPage_connection2, $tileInfoD["id"], $tileInfoD["continent"], $tileInfoD["xpos"], $tileInfoD["ypos"], $tileInfoD["terrain"], $tileInfoD["resources"], $tileInfoD["improvements"], $tileInfoD["owner"], $tileInfoD["claims"], $new_tile_population);
							$new_population = $new_population + $new_tile_population;
						} // if
					
						if ($stmt = $getPage_connection2->prepare("SELECT id FROM tilesmap WHERE id = (SELECT MIN(id) FROM tilesmap WHERE id > ?) ORDER BY id LIMIT 1")) {
							$stmt->bind_param("i", $next_tiles);
							$stmt->execute();
							$stmt->bind_result($r_result);
							$stmt->fetch();
							$next_tiles = $r_result;
							$stmt->close();
						} else {
							$next_tiles = 0;
						} // else
					} // while
				
				// otherwise just use difference between population and food and random number to be deficit
				} else {
					$rand = mt_rand(1,100);
					$deficit = ($new_population - $new_food) + $rand;
					$popShrink = 0.25*($deficit / $limit_allTilesOwned);
					$popShrinkInt = round($popShrink, 0, PHP_ROUND_HALF_DOWN);
					
					$new_population = 0; // reset population to assign new value based on pop changes
						
					$next_tiles = 1;
					if ($stmt = $getPage_connection2->prepare("SELECT id FROM tilesmap ORDER BY id ASC LIMIT 1")) {
						$stmt->execute();
						$stmt->bind_result($r_result);
						$stmt->fetch();
						$next_tiles = $r_result;
						$stmt->close();
					} else {
						$next_tiles = 0;
					} // else
					while ($next_tiles > 0) {
						$tileInfoD = getTileInfoByID($getPage_connection2, $next_tiles);
					
						$new_tile_population = 0;
					
						if ($tileInfoD["owner"] == $next_nations) {
							$new_tile_population = $tileInfoD["population"] - $popShrinkInt;						
							if ($new_tile_population < 0) {
								$new_tile_population = 0;
							} // if
							setTileInfo($getPage_connection2, $tileInfoD["id"], $tileInfoD["continent"], $tileInfoD["xpos"], $tileInfoD["ypos"], $tileInfoD["terrain"], $tileInfoD["resources"], $tileInfoD["improvements"], $tileInfoD["owner"], $tileInfoD["claims"], $new_tile_population);
							$new_population = $new_population + $new_tile_population;
						} // if
					
						if ($stmt = $getPage_connection2->prepare("SELECT id FROM tilesmap WHERE id = (SELECT MIN(id) FROM tilesmap WHERE id > ?) ORDER BY id LIMIT 1")) {
							$stmt->bind_param("i", $next_tiles);
							$stmt->execute();
							$stmt->bind_result($r_result);
							$stmt->fetch();
							$next_tiles = $r_result;
							$stmt->close();
						} else {
							$next_tiles = 0;
						} // else
					} // while
				} // else
			} // else if	
			
			/********************************
			 DEBT COLLECTION
			********************************/
				
			$_SESSION["scriptOutput"] .= "Money and Debts...<br />";
			
			// pay off debt before anything else
			if ($new_debt > 0) {
				if ($new_money < $new_debt) {
					$money_debt = $new_debt - $new_money; // reduce the debt if nothing else
					$new_debt = $money_debt;
					$new_money = 0;
				} else {
					$new_money = $new_money - $new_debt;
					$new_debt = 0;
				} // else
			} // if
			if ($new_money < $money_upkeep) {
				$money_debt = $money_upkeep - $new_money;
				$new_debt = $money_debt;
				$new_money = 0;
			} else {
				$new_money = $new_money - $money_upkeep;
			} // else
			
			$_SESSION["scriptOutput"] .= "Debt collecting...<br />";
			
			// money debt collecting
			
			// increase strike counter if debt is owed
			if ($new_debt > 0) {
				$new_strike = $new_strike + 1;
			} // if
			
			if ($new_food < 0) {
				$new_food = 0;
			} // if
							
			/********************************
			 SET NEW VARIABLES
			 ********************************/
			$_SESSION["scriptOutput"] .= "Update database...<br />";
			setNationInfo($getPage_connection2,$next_nations,$nationInfoW["name"],$nationInfoW["home"],$nationInfoW["formal"],$nationInfoW["flag"],$new_production,$new_money,$new_debt,$new_happiness,$new_food,$nationInfoW["authority"],0,$nationInfoW["economy"],0,$nationInfoW["organizations"],$nationInfoW["invites"],$new_goods,$new_resources,$new_population,$new_strike);
		} // while
		$stmt->close();
	} else {
	} // else
	
	$_SESSION["scriptOutput"] .= "That's a wrap, script complete.<br />";
} // updateNations

function updateGlobe($getPage_connection2) {
	// change requirements of luxury and basic goods

	// set luxurygoods
	$goodsInfoW = getGoodsInfo($getPage_connection2,5);
	
	if (($goodsInfoW["productionRequired"]-2) > 1) {
		$new_productionRequired = mt_rand(($goodsInfoW["productionRequired"]-2),($goodsInfoW["productionRequired"]+2));
	} else {
		$new_productionRequired = mt_rand(1,($goodsInfoW["productionRequired"]+2));
	} // else
		
	if ($new_productionRequired < 1) {
		$new_productionRequired = 1;
	} // if
		
	$rand_metals = 2;
	$rand_wood = 1;		
	$rand_metals = mt_rand(2,10);
	$rand_wood = mt_rand(2,10);
	$new_resourceTypesRequired = array(0=>0);
	$new_resourceQuantitiesRequired = array(0=>0);
	
	if ($rand_wood > 0) {
		$new_resourceTypesRequired[0] = 1;
		$new_resourceQuantitiesRequired[0] = $rand_wood;
	} // if
	if ($rand_metals > 0) {
		$new_resourceTypesRequired[1] = 2;
		$new_resourceQuantitiesRequired[1] = $rand_metals;
	} // if
	
	setGoodsInfo($getPage_connection2,$goodsInfoW["id"],$goodsInfoW["name"],$new_productionRequired,$goodsInfoW["foodRequired"],$new_resourceTypesRequired,$new_resourceQuantitiesRequired,$goodsInfoW["improvementTypesRequired"],$goodsInfoW["improvementQuantitiesRequired"],$goodsInfoW["improvementLevelRequired"],$goodsInfoW["buyStrength"],$goodsInfoW["sellStrength"]);

	// set basicgoods
	$goodsInfoW = getGoodsInfo($getPage_connection2,6);
	
	if (($goodsInfoW["productionRequired"]-2) > 1) {
		$new_productionRequired = mt_rand(($goodsInfoW["productionRequired"]-2),($goodsInfoW["productionRequired"]+2));
	} else {
		$new_productionRequired = mt_rand(0,($goodsInfoW["productionRequired"]+2));
	} // else
		
	if ($new_productionRequired < 1) {
		$new_productionRequired = 1;
	} // if
			
	$rand_metals = 1;
	$rand_wood = 1;		
	$rand_metals = mt_rand(1,8);
	$rand_wood = mt_rand(1,8);
	$new_resourceTypesRequired = array(0=>0);
	$new_resourceQuantitiesRequired = array(0=>0);
	
	if ($rand_wood > 0) {
		$new_resourceTypesRequired[0] = 1;
		$new_resourceQuantitiesRequired[0] = $rand_wood;
	} // if
	if ($rand_metals > 0) {
		$new_resourceTypesRequired[1] = 2;
		$new_resourceQuantitiesRequired[1] = $rand_metals;
	} // if
	
	setGoodsInfo($getPage_connection2,$goodsInfoW["id"],$goodsInfoW["name"],$new_productionRequired,$goodsInfoW["foodRequired"],$new_resourceTypesRequired,$new_resourceQuantitiesRequired,$goodsInfoW["improvementTypesRequired"],$goodsInfoW["improvementQuantitiesRequired"],$goodsInfoW["improvementLevelRequired"],$goodsInfoW["buyStrength"],$goodsInfoW["sellStrength"]);

	// set new market rates
	if ($stmt = $getPage_connection2->prepare("SELECT id FROM goods ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->store_result();

		while ($stmt->fetch()) {
			$next_goods = $r_result;
			$goodsInfoW = getGoodsInfo($getPage_connection2,$next_goods);
			$marketInfoW = getMarketInfo($getPage_connection2,$next_goods);
	
			$rate = 0;
			// if buy strength is more, increase rate, otherwise decrease or remain constant
			if ($goodsInfoW["buyStrength"] > $goodsInfoW["sellStrength"]) {
				$randRateBonus = mt_rand(1,4);
				$rate = $marketInfoW["rate"] + $randRateBonus;
			} else if ($goodsInfoW["buyStrength"] < $goodsInfoW["sellStrength"]) {
				$randFlux = mt_rand(1,4);
				// random drop or rise (75% more likely drop)
				if ($randFlux == 1) {
					$randRateBonus = mt_rand(1,2);
					$rate = $marketInfoW["rate"] + $randRateBonus;
				} else {
					$randRateBonus = mt_rand(1,4);
					$rate = $marketInfoW["rate"] - $randRateBonus;
				} // else
			} else {
				$randFlux = mt_rand(1,2);
				// random drop or rise (50/50)
				if ($randFlux == 1) {
					$randRateBonus = mt_rand(1,2);
					$rate = $marketInfoW["rate"] + $randRateBonus;
				} else {
					$randRateBonus = mt_rand(1,2);
					$rate = $marketInfoW["rate"] - $randRateBonus;
				} // else		
			} // else
	
			if ($rate >= 1) {
				setMarketInfo($getPage_connection2,$next_goods,$marketInfoW["name"],$rate);
			} else {
				setMarketInfo($getPage_connection2,$next_goods,$marketInfoW["name"],0);
			} // else
			// reset strength of good for next turn (no reset for now)
			//setGoodsInfo($getPage_connection2,$goodsInfoW["id"],$goodsInfoW["name"],$goodsInfoW["productionRequired"],$goodsInfoW["foodRequired"],$goodsInfoW["resourceTypesRequired"],$goodsInfoW["resourceQuantitiesRequired"],$goodsInfoW["improvementTypesRequired"],$goodsInfoW["improvementQuantitiesRequired"],$goodsInfoW["improvementLevelRequired"],100,100);
		} // while
		$stmt->close();
	} else {
	} // else

	// add to turn counter for trade offers
	if ($stmt = $getPage_connection2->prepare("SELECT id FROM offers ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->store_result();

		while ($stmt->fetch()) {
			$next_offers = $r_result;
			$offerInfo1 = getOfferInfo($getPage_connection2,$next_offers);
	
			$new_counter = $offerInfo1["counter"] + 1;
	
			setOfferInfo($getPage_connection2,$offerInfo1["id"],$offerInfo1["fromNation"],$offerInfo1["toNation"],$offerInfo1["givingItems"],$offerInfo1["receivingItems"],$offerInfo1["givingQuantities"],$offerInfo1["receivingQuantities"],$offerInfo1["givingTypes"],$offerInfo1["receivingTypes"],$offerInfo1["turns"],$new_counter,$offerInfo1["status"]);
		} // while
		$stmt->close();
	} else {
	} // else

	// remove empty organizations, automatically appoint new manager if there is no manager (new manager is the oldest joined member)

	if ($stmt = $getPage_connection2->prepare("SELECT id FROM organizations ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->store_result();

		while ($stmt->fetch()) {
			$next_organizations = $r_result;
			$organizationInfo1 = getOrganizationInfo($getPage_connection2,$next_organizations);
	
			$new_managers = array(0=>0);
			if (count($organizationInfo1["members"]) == 1) {
				if ($organizationInfo1["members"][0] == 0) {
					deleteOrganizationInfo($getPage_connection2,$next_organizations);
				} // if
			} else if (count($organizationInfo1["members"]) > 1) {
				if (count($organizationInfo1["managers"]) == 1) {
					if ($organizationInfo1["managers"][0] == 0) {
						$new_managers[0] = $organizationInfo1["members"][0];
						setOrganizationInfo($getPage_connection2,$next_organizations,$organizationInfo1["name"],$organizationInfo1["members"],$new_managers,$organizationInfo1["pending"],$organizationInfo1["ranking"]);
					} // if
				} else {
				} // else
			} else {
				deleteOrganizationInfo($getPage_connection2,$next_organizations);
			} // else
		} // while
		$stmt->close();
	} else {
	} // else

	// set rankings
	
	if ($stmt = $getPage_connection2->prepare("SELECT id FROM nations ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->store_result();

		$offset = 0;
		
		while ($stmt->fetch()) {
			$next_nations = $r_result;
			$rank = $offset + 1;
			
			// production
			
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM nations ORDER BY production DESC LIMIT 1 OFFSET ?")) {
				$stmt->bind_param("i", $offset);
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->fetch();
				$next_production_nation = $r_result;
				$stmt->close();
			} else {
			} // else
				
			if ($stmt = $getPage_connection2->prepare("UPDATE rankings SET production=? WHERE nation=?")) {
				$stmt->bind_param("ii", $rank, $next_production_nation);
				$stmt->execute();
				$stmt->close();
			} else {
			} // else
	
			// money
				
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM nations ORDER BY money DESC LIMIT 1 OFFSET ?")) {
				$stmt->bind_param("i", $offset);
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->fetch();
				$next_money_nation = $r_result;
				$stmt->close();
			} else {
			} // else
				
			if ($stmt = $getPage_connection2->prepare("UPDATE rankings SET money=? WHERE nation=?")) {
				$stmt->bind_param("ii", $rank, $next_money_nation);
				$stmt->execute();
				$stmt->close();
			} else {
			} // else
	
			// happiness
				
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM nations ORDER BY happiness DESC LIMIT 1 OFFSET ?")) {
				$stmt->bind_param("i", $offset);
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->fetch();
				$next_happiness_nation = $r_result;
				$stmt->close();
			} else {
			} // else
				
			if ($stmt = $getPage_connection2->prepare("UPDATE rankings SET happiness=? WHERE nation=?")) {
				$stmt->bind_param("ii", $rank, $next_happiness_nation);
				$stmt->execute();
				$stmt->close();
			} else {
			} // else
				
			// food
				
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM nations ORDER BY food DESC LIMIT 1 OFFSET ?")) {
				$stmt->bind_param("i", $offset);
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->fetch();
				$next_food_nation = $r_result;
				$stmt->close();
			} else {
			} // else
				
			if ($stmt = $getPage_connection2->prepare("UPDATE rankings SET food=? WHERE nation=?")) {
				$stmt->bind_param("ii", $rank, $next_food_nation);
				$stmt->execute();
				$stmt->close();
			} else {
			} // else
				
			// population
				
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM nations ORDER BY population DESC LIMIT 1 OFFSET ?")) {
				$stmt->bind_param("i", $offset);
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->fetch();
				$next_population_nation = $r_result;
				$stmt->close();
			} else {
			} // else
				
			if ($stmt = $getPage_connection2->prepare("UPDATE rankings SET population=? WHERE nation=?")) {
				$stmt->bind_param("ii", $rank, $next_population_nation);
				$stmt->execute();
				$stmt->close();
			} else {
			} // else
		
			$offset++;
		} // while
		$stmt->close();
	} else {
		$next_offers = 0;
	} // else
} // updateGlobe

function checkContinents ($getPage_connection2) {
	$availableContinent = 0;
	if ($stmt = $getPage_connection2->prepare("SELECT id FROM continents ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->store_result();

		while ($stmt->fetch()) {
			$next_continents = $r_result;
			$availableTiles = array(0=>0);
	
			$next_tiles = 1;
			$counter1 = 0;
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM tilesmap ORDER BY id ASC LIMIT 1")) {
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->fetch();
				$next_tiles = $r_result;
				$stmt->close();
			} else {
				$next_tiles = 0;
			} // else
			while ($next_tiles > 0) {	
				$tileInfoD = getTileInfoByID($getPage_connection2, $next_tiles);
			
				if ($tileInfoD["continent"] == $next_continents && $tileInfoD["owner"] == 0 && $tileInfoD["terrain"] != 2) {
					$availableTiles[$counter1] = $tileInfoD["id"];
					$counter1++;
				} // if
			
				if ($counter1 == 4) {
					$availableContinent = $next_continents;
					break 2;
				} // if
			
				if ($stmt = $getPage_connection2->prepare("SELECT id FROM tilesmap WHERE id = (SELECT MIN(id) FROM tilesmap WHERE id > ?) ORDER BY id LIMIT 1")) {
					$stmt->bind_param("i", $next_tiles);
					$stmt->execute();
					$stmt->bind_result($r_result);
					$stmt->fetch();
					$next_tiles = $r_result;
					$stmt->close();
				} else {
					$next_tiles = 0;
				} // else
			} // while
		} // while
		$stmt->close();
	} else {
	} // else

	if ($availableContinent < 1) {
		generateContinent($getPage_connection2);
	} // if
} // checkContinents
?>