<?php
/****************************************************************************
 * Name:        mysql.php
 * Author:      Ben Barnes
 * Date:        2015-12-21
 * Purpose:     MySQL access functions page
 *****************************************************************************/

// get about info
function getAboutInfo($s_connection,$s_id) {
	$about = array("id"=>0,"text"=>"");
	if ($stmt = $s_connection->prepare("SELECT id,text FROM about WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_text);
		$stmt->fetch();
		$about["id"] = $r_id;
		$about["text"] = $r_text;
		$stmt->close();
	} else {
	} // else

	return $about;
} // getAboutInfo

function setAboutInfo($s_connection,$s_id,$s_text) {
	if ($stmt = $s_connection->prepare("UPDATE about SET text=? WHERE id=?")) {
		$stmt->bind_param("si", $s_text, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setAboutInfo

// get agreements
function getAgreementInfo($s_connection,$s_id) {
	$agreement = array("id"=>0,"policy"=>0,"turns"=>0,"counter"=>0,"toNation"=>0,"fromNation"=>0,"status"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,policy,turns,counter,toNation,fromNation,status FROM agreements WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_policy,$r_turns,$r_counter,$r_toNation,$r_fromNation,$r_status);
		$stmt->fetch();
		$agreement["id"] = $r_id;
		$agreement["policy"] = $r_policy;
		$agreement["turns"] = $r_turns;
		$agreement["counter"] = $r_counter;
		$agreement["toNation"] = $r_toNation;
		$agreement["fromNation"] = $r_fromNation;
		$agreement["status"] = $r_status;
		$stmt->close();
	} else {
	} // else

	return $agreement;
} // getAgreementInfo

function setAgreementInfo($s_connection,$s_id,$s_policy,$s_turns,$s_counter,$s_toNation,$s_fromNation,$s_status) {
	if ($stmt = $s_connection->prepare("UPDATE agreements SET policy=?,turns=?,counter=?,toNation=?,fromNation=?,status=? WHERE id=?")) {
		$stmt->bind_param("iiiiiii", $s_policy, $s_turns, $s_counter, $s_toNation, $s_fromNation, $s_status, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setAgreementInfo

function addAgreementInfo($s_connection,$s_policy,$s_turns,$s_counter,$s_toNation,$s_fromNation,$s_status) {
	if ($stmt = $s_connection->prepare("INSERT INTO agreements (policy,turns,counter,toNation,fromNation,status) VALUES (?,?,?,?,?,?)")) {
		$stmt->bind_param("iiiiii", $s_policy,$s_turns,$s_counter,$s_toNation,$s_fromNation,$s_status);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // addAgreementInfo

function deleteAgreementInfo($s_connection,$s_id) {
	if ($stmt = $s_connection->prepare("DELETE FROM agreements WHERE id=?")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // deleteOfferInfo

// get claims
function getClaimInfo($s_connection,$s_id) {
	$claim = array("id"=>0,"strength"=>0,"owner"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,strength,owner FROM claims WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_strength,$r_owner);
		$stmt->fetch();
		$claim["id"] = $r_id;
		$claim["strength"] = $r_strength;
		$claim["owner"] = $r_owner;
		$stmt->close();
	} else {
	} // else

	return $claim;
} // getClaimInfo

function setClaimInfo($s_connection,$s_id,$s_strength,$s_owner) {
	if ($stmt = $s_connection->prepare("UPDATE claims SET strength=?,owner=? WHERE id=?")) {
		$stmt->bind_param("dii", $s_strength, $s_owner, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setClaimInfo

function addClaimInfo($s_connection,$s_strength,$s_owner,$s_tile) {
	if ($stmt = $s_connection->prepare("INSERT INTO claims (strength,owner) VALUES (?,?)")) {
		$stmt->bind_param("di", $s_strength, $s_owner);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
		
	$new_id = $s_connection->insert_id;	
	$claimInfoDD = getClaimInfo($s_connection, $new_id);
	$tileInfoDD = getTileInfoByID($s_connection, $s_tile);	
	$new_claims = $tileInfoDD["claims"];
	$new_index = count($new_claims) + 1;
	$new_claims[$new_index] = $claimInfoDD["id"];
	setTileInfo($s_connection, $tileInfoDD["id"], $tileInfoDD["continent"], $tileInfoDD["xpos"], $tileInfoDD["ypos"], $tileInfoDD["terrain"], $tileInfoDD["resources"], $tileInfoDD["improvements"], $tileInfoDD["owner"], $new_claims, $tileInfoDD["population"]);
} // addClaimInfo

// continents
function getContinentInfo($s_connection,$s_id) {
	$continent = array("id"=>0,"name"=>"");
	if ($stmt = $s_connection->prepare("SELECT id,name FROM continents WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_continent_id,$r_continent_name);
		$stmt->fetch();
		$continent["id"] = $r_continent_id;
		$continent["name"] = $r_continent_name;
		$stmt->close();
	} else {
	} // else

	return $continent;
} // getContinentInfo

function setContinentInfo($s_connection,$s_id,$s_name) {
	if ($stmt = $s_connection->prepare("UPDATE continents SET name=? WHERE id=?")) {
		$stmt->bind_param("si", $s_name, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setContinentInfo

function addContinentInfo($s_connection,$s_name) {
	if ($stmt = $s_connection->prepare("INSERT INTO continents (name) VALUES (?)")) {
		$stmt->bind_param("s", $s_name);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // addContinentInfo

// goods
function getGoodsInfo($s_connection,$s_id) {
	$goods = array("id"=>0,"name"=>"","productionRequired"=>0,"foodRequired"=>0,"resourceTypesRequired"=>array(0=>""),"resourceQuantitiesRequired"=>array(0=>""),"improvementTypesRequired"=>array(0=>""),"improvementQuantitiesRequired"=>array(0=>""),"improvementLevelRequired"=>array(0=>""),"buyStrength"=>0,"sellStrength"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,name,productionRequired,foodRequired,resourceTypesRequired,resourceQuantitiesRequired,improvementTypesRequired,improvementQuantitiesRequired,improvementLevelRequired,buyStrength,sellStrength FROM goods WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_name,$r_productionRequired,$r_foodRequired,$r_resourceTypesRequired,$r_resourceQuantitiesRequired,$r_improvementTypesRequired,$r_improvementQuantitiesRequired,$r_improvementLevelRequired,$r_buyStrength,$r_sellStrength);
		$stmt->fetch();
		$goods["id"] = $r_id;
		$goods["name"] = $r_name;
		$goods["productionRequired"] = $r_productionRequired;
		$goods["foodRequired"] = $r_foodRequired;
		if (stripos($r_resourceTypesRequired,",")) {
			$goods["resourceTypesRequired"] = explode(",",$r_resourceTypesRequired);
		} else {
			$goods["resourceTypesRequired"] = array(0=>$r_resourceTypesRequired);
		} // else
		if (stripos($r_resourceQuantitiesRequired,",")) {
			$goods["resourceQuantitiesRequired"] = explode(",",$r_resourceQuantitiesRequired);
		} else {
			$goods["resourceQuantitiesRequired"] = array(0=>$r_resourceQuantitiesRequired);
		} // else
		if (stripos($r_improvementTypesRequired,",")) {
			$goods["improvementTypesRequired"] = explode(",",$r_improvementTypesRequired);
		} else {
			$goods["improvementTypesRequired"] = array(0=>$r_improvementTypesRequired);
		} // else
		if (stripos($r_improvementQuantitiesRequired,",")) {
			$goods["improvementQuantitiesRequired"] = explode(",",$r_improvementQuantitiesRequired);
		} else {
			$goods["improvementQuantitiesRequired"] = array(0=>$r_improvementQuantitiesRequired);
		} // else
		if (stripos($r_improvementLevelRequired,",")) {
			$goods["improvementLevelRequired"] = explode(",",$r_improvementLevelRequired);
		} else {
			$goods["improvementLevelRequired"] = array(0=>$r_improvementLevelRequired);
		} // else
		$goods["buyStrength"] = $r_buyStrength;
		$goods["sellStrength"] = $r_sellStrength;
		$stmt->close();
	} else {
	} // else

	return $goods;
} // getGoodsInfo

function getGoodsInfoByName($s_connection,$s_name) {
	$goods = array("id"=>0,"name"=>"","productionRequired"=>0,"foodRequired"=>0,"resourceTypesRequired"=>array(0=>""),"resourceQuantitiesRequired"=>array(0=>""),"improvementTypesRequired"=>array(0=>""),"improvementQuantitiesRequired"=>array(0=>""),"improvementLevelRequired"=>array(0=>""),"buyStrength"=>0,"sellStrength"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,name,productionRequired,foodRequired,resourceTypesRequired,resourceQuantitiesRequired,improvementTypesRequired,improvementQuantitiesRequired,improvementLevelRequired,buyStrength,sellStrength FROM goods WHERE name LIKE ? LIMIT 1")) {
		$stmt->bind_param("s", $s_name);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_name,$r_productionRequired,$r_foodRequired,$r_resourceTypesRequired,$r_resourceQuantitiesRequired,$r_improvementTypesRequired,$r_improvementQuantitiesRequired,$r_improvementLevelRequired,$r_buyStrength,$r_sellStrength);
		$stmt->fetch();
		$goods["id"] = $r_id;
		$goods["name"] = $r_name;
		$goods["productionRequired"] = $r_productionRequired;
		$goods["foodRequired"] = $r_foodRequired;
		if (stripos($r_resourceTypesRequired,",")) {
			$goods["resourceTypesRequired"] = explode(",",$r_resourceTypesRequired);
		} else {
			$goods["resourceTypesRequired"] = array(0=>$r_resourceTypesRequired);
		} // else
		if (stripos($r_resourceQuantitiesRequired,",")) {
			$goods["resourceQuantitiesRequired"] = explode(",",$r_resourceQuantitiesRequired);
		} else {
			$goods["resourceQuantitiesRequired"] = array(0=>$r_resourceQuantitiesRequired);
		} // else
		if (stripos($r_improvementTypesRequired,",")) {
			$goods["improvementTypesRequired"] = explode(",",$r_improvementTypesRequired);
		} else {
			$goods["improvementTypesRequired"] = array(0=>$r_improvementTypesRequired);
		} // else
		if (stripos($r_improvementQuantitiesRequired,",")) {
			$goods["improvementQuantitiesRequired"] = explode(",",$r_improvementQuantitiesRequired);
		} else {
			$goods["improvementQuantitiesRequired"] = array(0=>$r_improvementQuantitiesRequired);
		} // else
		if (stripos($r_improvementLevelRequired,",")) {
			$goods["improvementLevelRequired"] = explode(",",$r_improvementLevelRequired);
		} else {
			$goods["improvementLevelRequired"] = array(0=>$r_improvementLevelRequired);
		} // else
		$goods["buyStrength"] = $r_buyStrength;
		$goods["sellStrength"] = $r_sellStrength;
		$stmt->close();
	} else {
	} // else

	return $goods;
} // getGoodsInfoByName

function setGoodsInfo($s_connection,$s_id,$s_name,$s_productionRequired,$s_foodRequired,$s_resourceTypesRequired,$s_resourceQuantitiesRequired,$s_improvementTypesRequired,$s_improvementQuantitiesRequired,$s_improvementLevelRequired,$s_buyStrength,$s_sellStrength) {
	if (count($s_resourceTypesRequired) > 1) {
		$new_resourceTypesRequired = implode(",",$s_resourceTypesRequired);
	} else {
		$new_resourceTypesRequired = $s_resourceTypesRequired[0];
	} // else
	if (count($s_resourceQuantitiesRequired) > 1) {
		$new_resourceQuantitiesRequired = implode(",",$s_resourceQuantitiesRequired);
	} else {
		$new_resourceQuantitiesRequired = $s_resourceQuantitiesRequired[0];
	} // else
	if (count($s_improvementTypesRequired) > 1) {
		$new_improvementTypesRequired = implode(",",$s_improvementTypesRequired);
	} else {
		$new_improvementTypesRequired = $s_improvementTypesRequired[0];
	} // else
	if (count($s_improvementQuantitiesRequired) > 1) {
		$new_improvementQuantitiesRequired = implode(",",$s_improvementQuantitiesRequired);
	} else {
		$new_improvementQuantitiesRequired = $s_improvementQuantitiesRequired[0];
	} // else
	if (count($s_improvementLevelRequired) > 1) {
		$new_improvementLevelRequired = implode(",",$s_improvementLevelRequired);
	} else {
		$new_improvementLevelRequired = $s_improvementLevelRequired[0];
	} // else
	if ($stmt = $s_connection->prepare("UPDATE goods SET name=?,productionRequired=?,foodRequired=?,resourceTypesRequired=?,resourceQuantitiesRequired=?,improvementTypesRequired=?,improvementQuantitiesRequired=?,improvementLevelRequired=?,buyStrength=?,sellStrength=? WHERE id=?")) {
		$stmt->bind_param("sddsssssiii", $s_name, $s_productionRequired, $s_foodRequired, $new_resourceTypesRequired, $new_resourceQuantitiesRequired, $new_improvementTypesRequired, $new_improvementQuantitiesRequired, $new_improvementLevelRequired, $s_buyStrength, $s_sellStrength, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setGoodsInfo

// helpcategories
function getHelpCategoriesInfo($s_connection,$s_id) {
	$helpcategories = array("id"=>0,"title"=>"","text"=>"");
	if ($stmt = $s_connection->prepare("SELECT id,title,text FROM helpcategories WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_title,$r_text);
		$stmt->fetch();
		$helpcategories["id"] = $r_id;
		$helpcategories["title"] = $r_title;
		$helpcategories["text"] = $r_text;
		$stmt->close();
	} else {
	} // else

	return $helpcategories;
} // getHelpcategoriesInfo

function setHelpCategoriesInfo($s_connection,$s_id,$s_title,$s_text) {
	if ($stmt = $s_connection->prepare("UPDATE helpcategories SET title=?,text=? WHERE WHERE id=?")) {
		$stmt->bind_param("ssi", $s_title, $s_text, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setHelpcategoriesInfo

// helpsubcategories
function getHelpSubcategoriesInfo($s_connection,$s_id) {
	$helpsubcategories = array("id"=>0,"category"=>0,"title"=>"","text"=>"");
	if ($stmt = $s_connection->prepare("SELECT id,category,title,text FROM helpsubcategories WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_category,$r_title,$r_text);
		$stmt->fetch();
		$helpsubcategories["id"] = $r_id;
		$helpsubcategories["category"] = $r_category;
		$helpsubcategories["title"] = $r_title;
		$helpsubcategories["text"] = $r_text;
		$stmt->close();
	} else {
	} // else

	return $helpsubcategories;
} // getHelpsubcategoriesInfo

function setHelpSubcategoriesInfo($s_connection,$s_id,$s_category,$s_title,$s_text) {
	if ($stmt = $s_connection->prepare("UPDATE helpsubcategories SET category=?,title=?,text=? WHERE WHERE id=?")) {
		$stmt->bind_param("issi", $s_category, $s_title, $s_text, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setHelpsubcategoriesInfo

// improvements
function getImprovementTypeInfo($s_connection,$s_id) {
	$improvementType = array("id"=>0,"name"=>"","resourcesRequired"=>array(0=>""),"terrainTypeRequired"=>array(0=>""),"baseCost"=>0,"image"=>"");
	if ($stmt = $s_connection->prepare("SELECT id,name,resourcesRequired,terrainTypeRequired,baseCost,image FROM improvements WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_name,$r_resourcesRequired,$r_terrainTypeRequired,$r_baseCost,$r_image);
		$stmt->fetch();
		$improvementType["id"] = $r_id;
		$improvementType["name"] = $r_name;
		if (stripos($r_resourcesRequired,",")) {
			$improvementType["resourcesRequired"] = explode(",",$r_resourcesRequired);
		} else {
			$improvementType["resourcesRequired"] = array(0=>$r_resourcesRequired);
		} // else
		if (stripos($r_terrainTypeRequired,",")) {
			$improvementType["terrainTypeRequired"] = explode(",",$r_terrainTypeRequired);
		} else {
			$improvementType["terrainTypeRequired"] = array(0=>$r_terrainTypeRequired);
		} // else
		$improvementType["baseCost"] = $r_baseCost;
		$improvementType["image"] = $r_image;
		$stmt->close();
	} else {
	} // else

	return $improvementType;
} // getImprovementTypeInfo

function setImprovementTypeInfo($s_connection,$s_id,$s_name,$s_resourcesRequired,$s_terrainTypeRequired,$s_baseCost,$s_image) {
	if (count($s_resourcesRequired) > 1) {
		$new_resourcesRequired = implode(",",$s_resourcesRequired);
	} else {
		$new_resourcesRequired = $s_resourcesRequired[0];
	} // else
	if (count($s_terrainTypeRequired) > 1) {
		$new_terrainTypeRequired = implode(",",$s_terrainTypeRequired);
	} else {
		$new_terrainTypeRequired = $s_terrainTypeRequired[0];
	} // else
	if ($stmt = $s_connection->prepare("UPDATE improvements SET name=?,resourcesRequired=?,terrainTypeRequired=?,baseCost=?,image=? WHERE id=?")) {
		$stmt->bind_param("sssdsi", $s_name, $new_resourcesRequired, $new_terrainTypeRequired, $s_baseCost, $s_image, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setImprovementTypeInfo

// improvementsmap
function getImprovementInfo($s_connection,$s_id) {
	$improvement = array("id"=>0,"continent"=>0,"xpos"=>0,"ypos"=>0,"type"=>0,"level"=>0,"usingResources"=>array(0=>0),"owners"=>array(0=>""));
	if ($stmt = $s_connection->prepare("SELECT id,continent,xpos,ypos,type,level,usingResources,owners FROM improvementsmap WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_continent,$r_xpos,$r_ypos,$r_type,$r_level,$r_usingResources,$r_owners);
		$stmt->fetch();
		$improvement["id"] = $r_id;
		$improvement["continent"] = $r_continent;
		$improvement["xpos"] = $r_xpos;
		$improvement["ypos"] = $r_ypos;
		$improvement["type"] = $r_type;
		$improvement["level"] = $r_level;
		if (stripos($r_usingResources,",")) {
			$improvement["usingResources"] = explode(",",$r_usingResources);
		} else {
			$improvement["usingResources"] = array(0=>$r_usingResources);
		} // else
		if (stripos($r_owners,",")) {
			$improvement["owners"] = explode(",",$r_owners);
		} else {
			$improvement["owners"] = array(0=>$r_owners);
		} // else
		$stmt->close();
	} else {
	} // else

	return $improvement;
} // getImprovementInfo

function setImprovementInfo($s_connection,$s_id,$s_continent,$s_xpos,$s_ypos,$s_type,$s_level,$s_usingResources,$s_owners) {
	if (count($s_usingResources) > 1) {
		$new_usingResources = implode(",",$s_usingResources);
	} else {
		$new_usingResources = $s_usingResources[0];
	} // else
	if (count($s_owners) > 1) {
		$new_owners = implode(",",$s_owners);
	} else {
		$new_owners = $s_owners[0];
	} // else
	if ($stmt = $s_connection->prepare("UPDATE improvementsmap SET continent=?,xpos=?,ypos=?,type=?,level=?,usingResources=?,owners=? WHERE id=?")) {
		$stmt->bind_param("iiiiissi", $s_continent, $s_xpos, $s_ypos, $s_type, $s_level, $new_usingResources, $new_owners, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setImprovementInfo

function deleteImprovementInfo($s_connection,$s_id,$s_continent,$s_xpos,$s_ypos) {
	$new_improvements = array(0=>0);
	if ($stmt = $s_connection->prepare("DELETE FROM improvementsmap WHERE id=?")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
	$counter = 0;
	$tileInfoM = getTileInfo($s_connection,$s_continent,$s_xpos,$s_ypos);
	for ($z = 0;$z < count($tileInfoM["improvements"]); $z++) {
		if ($s_id != $tileInfoM["improvements"][$z]) {
			$new_improvements[$counter] = $tileInfoM["improvements"][$z];
			$counter++;
		} // if
	} // for
	setTileInfo($s_connection,$tileInfoM["id"],$tileInfoM["continent"],$tileInfoM["xpos"],$tileInfoM["ypos"],$tileInfoM["terrain"],$tileInfoM["resources"],$new_improvements,$tileInfoM["owner"],$tileInfoM["claims"],$tileInfoM["population"]);
} // deleteImprovementInfo

function addImprovementInfo($s_connection,$s_continent,$s_xpos,$s_ypos,$s_type,$s_level,$s_usingResources,$s_owners) {
	$new_id = 0;
	if (count($s_usingResources) > 1) {
		$new_usingResources = implode(",",$s_usingResources);
	} else {
		$new_usingResources = $s_usingResources[0];
	} // else
	if (count($s_owners) > 1) {
		$new_owners = implode(",",$s_owners);
	} else {
		$new_owners = $s_owners[0];
	} // else
	if ($stmt = $s_connection->prepare("INSERT INTO improvementsmap (continent,xpos,ypos,type,level,usingResources,owners) VALUES (?,?,?,?,?,?,?)")) {
		$stmt->bind_param("iiiiiss", $s_continent, $s_xpos, $s_ypos, $s_type, $s_level, $new_usingResources, $new_owners);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else

	$new_id = $s_connection->insert_id;
	$tileInfoM = getTileInfo($s_connection,$s_continent,$s_xpos,$s_ypos);
	$new_improvements = $tileInfoM["improvements"];
	$new_index = count($tileInfoM["improvements"]) + 1;
	$new_improvements[$new_index] = $new_id;
	setTileInfo($s_connection,$tileInfoM["id"],$tileInfoM["continent"],$tileInfoM["xpos"],$tileInfoM["ypos"],$tileInfoM["terrain"],$tileInfoM["resources"],$new_improvements,$tileInfoM["owner"],$tileInfoM["claims"],$tileInfoM["population"]);
} // addImprovementInfo

// market
function getMarketInfo($s_connection,$s_id) {
	$market = array("id"=>0,"name"=>"","rate"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,name,rate FROM market WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_name,$r_rate);
		$stmt->fetch();
		$market["id"] = $r_id;
		$market["name"] = $r_name;
		$market["rate"] = $r_rate;
		$stmt->close();
	} else {
	} // else

	return $market;
} // getMarketInfo

function setMarketInfo($s_connection,$s_id,$s_name,$s_rate) {
	if ($stmt = $s_connection->prepare("UPDATE market SET name=?,rate=? WHERE id=?")) {
		$stmt->bind_param("sii", $s_name, $s_rate, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setMarketInfo

// nations
function getNationInfo($s_connection,$s_id) {
	$nation = array("id"=>0,"name"=>"","home"=>0,"formal"=>"","flag"=>"","production"=>0,"money"=>0,"debt"=>0,"happiness"=>0,"food"=>0,"authority"=>0,"authorityChanged"=>0,"economy"=>0,"economyChanged"=>0,"organizations"=>array(0=>0),"invites"=>array(0=>0),"goods"=>array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0),"resources"=>array(0=>0,1=>0,2=>0,3=>0,4=>0),"population"=>0,"strike"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,name,home,formal,flag,production,money,debt,happiness,food,authority,authorityChanged,economy,economyChanged,organizations,invites,goods,resources,population,strike FROM nations WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_name,$r_home,$r_formal,$r_flag,$r_production,$r_money,$r_debt,$r_happiness,$r_food,$r_authority,$r_authorityChanged,$r_economy,$r_economyChanged,$r_organizations,$r_invites,$r_goods,$r_resources,$r_population,$r_strike);
		$stmt->fetch();
		$nation["id"] = $r_id;
		$nation["name"] = $r_name;
		$nation["home"] = $r_home;
		$nation["formal"] = $r_formal;
		$nation["flag"] = $r_flag;
		$nation["production"] = $r_production;
		$nation["money"] = $r_money;
		$nation["debt"] = $r_debt;
		$nation["happiness"] = $r_happiness;
		$nation["food"] = $r_food;
		$nation["authority"] = $r_authority;
		$nation["authorityChanged"] = $r_authorityChanged;
		$nation["economy"] = $r_economy;
		$nation["economyChanged"] = $r_economyChanged;
		if (stripos($r_organizations,",")) {
			$nation["organizations"] = explode(",",$r_organizations);
		} else {
			$nation["organizations"] = array(0=>$r_organizations);
		} // else
		if (stripos($r_invites,",")) {
			$nation["invites"] = explode(",",$r_invites);
		} else {
			$nation["invites"] = array(0=>$r_invites);
		} // else
		if (stripos($r_goods,",")) {
			$nation["goods"] = explode(",",$r_goods);
		} else {
			$nation["goods"] = array(0=>$r_goods);
		} // else
		if (stripos($r_resources,",")) {
			$nation["resources"] = explode(",",$r_resources);
		} else {
			$nation["resources"] = array(0=>$r_resources);
		} // else
		$nation["population"] = $r_population;
		$nation["strike"] = $r_strike;
		$stmt->close();
	} else {
	} // else

	return $nation;
} // getNationInfo

function getNationInfoByName($s_connection,$s_name) {
	$nation = array("id"=>0,"name"=>"","home"=>0,"formal"=>"","flag"=>"","production"=>0,"money"=>0,"debt"=>0,"happiness"=>0,"food"=>0,"authority"=>0,"authorityChanged"=>0,"economy"=>0,"economyChanged"=>0,"organizations"=>array(0=>0),"invites"=>array(0=>0),"goods"=>array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0),"resources"=>array(0=>0,1=>0,2=>0,3=>0,4=>0),"population"=>0,"strike"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,name,home,formal,flag,production,money,debt,happiness,food,authority,authorityChanged,economy,economyChanged,organizations,invites,goods,resources,population,strike FROM nations WHERE name LIKE ? LIMIT 1")) {
		$stmt->bind_param("s", $s_name);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_name,$r_home,$r_formal,$r_flag,$r_production,$r_money,$r_debt,$r_happiness,$r_food,$r_authority,$r_authorityChanged,$r_economy,$r_economyChanged,$r_organizations,$r_invites,$r_goods,$r_resources,$r_population,$r_strike);
		$stmt->fetch();
		$nation["id"] = $r_id;
		$nation["name"] = $r_name;
		$nation["home"] = $r_home;
		$nation["formal"] = $r_formal;
		$nation["flag"] = $r_flag;
		$nation["production"] = $r_production;
		$nation["money"] = $r_money;
		$nation["debt"] = $r_debt;
		$nation["happiness"] = $r_happiness;
		$nation["food"] = $r_food;
		$nation["authority"] = $r_authority;
		$nation["authorityChanged"] = $r_authorityChanged;
		$nation["economy"] = $r_economy;
		$nation["economyChanged"] = $r_economyChanged;
		if (stripos($r_organizations,",")) {
			$nation["organizations"] = explode(",",$r_organizations);
		} else {
			$nation["organizations"] = array(0=>$r_organizations);
		} // else
		if (stripos($r_invites,",")) {
			$nation["invites"] = explode(",",$r_invites);
		} else {
			$nation["invites"] = array(0=>$r_invites);
		} // else
		if (stripos($r_goods,",")) {
			$nation["goods"] = explode(",",$r_goods);
		} else {
			$nation["goods"] = array(0=>$r_goods);
		} // else
		if (stripos($r_resources,",")) {
			$nation["resources"] = explode(",",$r_resources);
		} else {
			$nation["resources"] = array(0=>$r_resources);
		} // else
		$nation["population"] = $r_population;
		$nation["strike"] = $r_strike;
		$stmt->close();
	} else {
	} // else

	return $nation;
} // getNationInfoByName

function setNationInfo($s_connection,$s_id,$s_name,$s_home,$s_formal,$s_flag,$s_production,$s_money,$s_debt,$s_happiness,$s_food,$s_authority,$s_authorityChanged,$s_economy,$s_economyChanged,$s_organizations,$s_invites,$s_goods,$s_resources,$s_population,$s_strike) {
	if (count($s_organizations) > 1) {
		$new_organizations = implode(",",$s_organizations);
	} else {
		$new_organizations = $s_organizations[0];
	} // else
	if (count($s_invites) > 1) {
		$new_invites = implode(",",$s_invites);
	} else {
		$new_invites = $s_invites[0];
	} // else
	if (count($s_goods) > 1) {
		$new_goods = implode(",",$s_goods);
	} else {
		$new_goods = $s_goods[0];
	} // else
	if (count($s_resources) > 1) {
		$new_resources = implode(",",$s_resources);
	} else {
		$new_resources = $s_resources[0];
	} // else
	if ($stmt = $s_connection->prepare("UPDATE nations SET name=?,home=?,formal=?,flag=?,production=?,money=?,debt=?,happiness=?,food=?,authority=?,authorityChanged=?,economy=?,economyChanged=?,organizations=?,invites=?,goods=?,resources=?,population=?,strike=? WHERE id=?")) {
		$stmt->bind_param("sissdddddiiiissssiii", $s_name, $s_home, $s_formal, $s_flag, $s_production, $s_money, $s_debt, $s_happiness, $s_food, $s_authority, $s_authorityChanged, $s_economy, $s_economyChanged, $new_organizations, $new_invites, $new_goods, $new_resources, $s_population, $s_strike, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setNationInfo

function addNationInfo($s_connection,$s_id,$s_name,$s_home,$s_formal,$s_flag,$s_production,$s_money,$s_debt,$s_happiness,$s_food,$s_authority,$s_authorityChanged,$s_economy,$s_economyChanged,$s_organizations,$s_invites,$s_goods,$s_resources,$s_population,$s_strike) {
	if (count($s_organizations) > 1) {
		$new_organizations = implode(",",$s_organizations);
	} else {
		$new_organizations = $s_organizations[0];
	} // else
	if (count($s_invites) > 1) {
		$new_invites = implode(",",$s_invites);
	} else {
		$new_invites = $s_invites[0];
	} // else
	if (count($s_goods) > 1) {
		$new_goods = implode(",",$s_goods);
	} else {
		$new_goods = $s_goods[0];
	} // else
	if (count($s_resources) > 1) {
		$new_resources = implode(",",$s_resources);
	} else {
		$new_resources = $s_resources[0];
	} // else
	if ($stmt = $s_connection->prepare("INSERT INTO nations (id,name,home,formal,flag,production,money,debt,happiness,food,authority,authorityChanged,economy,economyChanged,organizations,invites,goods,resources,population,strike) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
		$stmt->bind_param("isissdddddiiiissssii", $s_id, $s_name, $s_home, $s_formal, $s_flag, $s_production, $s_money, $s_debt, $s_happiness, $s_food, $s_authority, $s_authorityChanged, $s_economy, $s_economyChanged, $new_organizations, $new_invites, $new_goods, $new_resources, $s_population, $s_strike);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // addNationInfo

// offers
function getOfferInfo($s_connection,$s_id) {
	$offer = array("id"=>0,"fromNation"=>0,"toNation"=>0,"givingItems"=>"","receivingItems"=>"","givingQuantities"=>"","receivingQuantities"=>"","givingTypes"=>"","receivingTypes"=>"","turns"=>0,"counter"=>0,"status"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,fromNation,toNation,givingItems,receivingItems,givingQuantities,receivingQuantities,givingTypes,receivingTypes,turns,counter,status FROM offers WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id, $r_fromNation, $r_toNation, $r_givingItems, $r_receivingItems, $r_givingQuantities, $r_receivingQuantities, $r_givingTypes, $r_receivingTypes, $r_turns, $r_counter, $r_status);
		$stmt->fetch();
		$offer["id"] = $r_id;
		$offer["fromNation"] = $r_fromNation;
		$offer["toNation"] = $r_toNation;
		if (stripos($r_givingItems,",")) {
			$offer["givingItems"] = explode(",",$r_givingItems);
		} else {
			$offer["givingItems"] = array(0=>$r_givingItems);
		} // else
		if (stripos($r_receivingItems,",")) {
			$offer["receivingItems"] = explode(",",$r_receivingItems);
		} else {
			$offer["receivingItems"] = array(0=>$r_receivingItems);
		} // else
		if (stripos($r_givingQuantities,",")) {
			$offer["givingQuantities"] = explode(",",$r_givingQuantities);
		} else {
			$offer["givingQuantities"] = array(0=>$r_givingQuantities);
		} // else
		if (stripos($r_receivingQuantities,",")) {
			$offer["receivingQuantities"] = explode(",",$r_receivingQuantities);
		} else {
			$offer["receivingQuantities"] = array(0=>$r_receivingQuantities);
		} // else
		if (stripos($r_givingTypes,",")) {
			$offer["givingTypes"] = explode(",",$r_givingTypes);
		} else {
			$offer["givingTypes"] = array(0=>$r_givingTypes);
		} // else
		if (stripos($r_receivingTypes,",")) {
			$offer["receivingTypes"] = explode(",",$r_receivingTypes);
		} else {
			$offer["receivingTypes"] = array(0=>$r_receivingTypes);
		} // else
		$offer["turns"] = $r_turns;
		$offer["counter"] = $r_counter;
		$offer["status"] = $r_status;
		$stmt->close();
	} else {
	} // else

	return $offer;
} // getOfferInfo

function setOfferInfo($s_connection,$s_id,$s_fromNation,$s_toNation,$s_givingItems,$s_receivingItems,$s_givingQuantities,$s_receivingQuantities,$s_givingTypes,$s_receivingTypes,$s_turns,$s_counter,$s_status) {
	if (count($s_givingItems) > 1) {
		$new_givingItems = implode(",",$s_givingItems);
	} else {
		$new_givingItems = $s_givingItems[0];
	} // else
	if (count($s_receivingItems) > 1) {
		$new_receivingItems = implode(",",$s_receivingItems);
	} else {
		$new_receivingItems = $s_receivingItems[0];
	} // else
	if (count($s_givingQuantities) > 1) {
		$new_givingQuantities = implode(",",$s_givingQuantities);
	} else {
		$new_givingQuantities = $s_givingQuantities[0];
	} // else
	if (count($s_receivingQuantities) > 1) {
		$new_receivingQuantities = implode(",",$s_receivingQuantities);
	} else {
		$new_receivingQuantities = $s_receivingQuantities[0];
	} // else
	if (count($s_givingTypes) > 1) {
		$new_givingTypes = implode(",",$s_givingTypes);
	} else {
		$new_givingTypes = $s_givingTypes[0];
	} // else
	if (count($s_receivingTypes) > 1) {
		$new_receivingTypes = implode(",",$s_receivingTypes);
	} else {
		$new_receivingTypes = $s_receivingTypes[0];
	} // else
	if ($stmt = $s_connection->prepare("UPDATE offers SET fromNation=?,toNation=?,givingItems=?,receivingItems=?,givingQuantities=?,receivingQuantities=?,givingTypes=?,receivingTypes=?,turns=?,counter=?,status=? WHERE id=?")) {
		$stmt->bind_param("iissssssiiii", $s_fromNation, $s_toNation, $new_givingItems, $new_receivingItems, $new_givingQuantities, $new_receivingQuantities, $new_givingTypes, $new_receivingTypes, $s_status, $s_turns, $s_counter, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setOfferInfo

function addOfferInfo($s_connection,$s_fromNation,$s_toNation,$s_givingItems,$s_receivingItems,$s_givingQuantities,$s_receivingQuantities,$s_givingTypes,$s_receivingTypes,$s_turns,$s_counter,$s_status) {
	if (count($s_givingItems) > 1) {
		$new_givingItems = implode(",",$s_givingItems);
	} else {
		$new_givingItems = $s_givingItems[0];
	} // else
	if (count($s_receivingItems) > 1) {
		$new_receivingItems = implode(",",$s_receivingItems);
	} else {
		$new_receivingItems = $s_receivingItems[0];
	} // else
	if (count($s_givingQuantities) > 1) {
		$new_givingQuantities = implode(",",$s_givingQuantities);
	} else {
		$new_givingQuantities = $s_givingQuantities[0];
	} // else
	if (count($s_receivingQuantities) > 1) {
		$new_receivingQuantities = implode(",",$s_receivingQuantities);
	} else {
		$new_receivingQuantities = $s_receivingQuantities[0];
	} // else
	if (count($s_givingTypes) > 1) {
		$new_givingTypes = implode(",",$s_givingTypes);
	} else {
		$new_givingTypes = $s_givingTypes[0];
	} // else
	if (count($s_receivingTypes) > 1) {
		$new_receivingTypes = implode(",",$s_receivingTypes);
	} else {
		$new_receivingTypes = $s_receivingTypes[0];
	} // else
	if ($stmt = $s_connection->prepare("INSERT INTO offers (fromNation,toNation,givingItems,receivingItems,givingQuantities,receivingQuantities,givingTypes,receivingTypes,turns,counter,status) VALUES (?,?,?,?,?,?,?,?,?,?,?)")) {
		$stmt->bind_param("iissssssiii", $s_fromNation, $s_toNation, $new_givingItems, $new_receivingItems, $new_givingQuantities, $new_receivingQuantities, $new_givingTypes, $new_receivingTypes, $s_turns, $s_counter, $s_status);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // addOfferInfo

function deleteOfferInfo($s_connection,$s_id) {
	if ($stmt = $s_connection->prepare("DELETE FROM offers WHERE id=?")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // deleteOfferInfo

// organizations
function getOrganizationInfo($s_connection,$s_id) {
	$organization = array("id"=>0,"name"=>"","members"=>array(0=>""),"pending"=>array(0=>""),"ranking"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,name,members,managers,pending,ranking FROM organizations WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
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
		$stmt->close();
	} else {
	} // else

	return $organization;
} // getOrganizationInfo

function setOrganizationInfo($s_connection,$s_id,$s_name,$s_members,$s_managers,$s_pending,$s_ranking) {
	if (count($s_members) > 1) {
		$new_members = implode(",",$s_members);
	} else {
		$new_members = $s_members[0];
	} // else
	if (count($s_managers) > 1) {
		$new_managers = implode(",",$s_managers);
	} else {
		$new_managers = $s_managers[0];
	} // else
	if (count($s_pending) > 1) {
		$new_pending = implode(",",$s_pending);
	} else {
		$new_pending = $s_pending[0];
	} // else
	if ($stmt = $s_connection->prepare("UPDATE organizations SET name=?,members=?,managers=?,pending=?,ranking=? WHERE id=?")) {
		$stmt->bind_param("ssssii", $s_name, $new_members, $new_managers, $new_pending, $s_ranking, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setOrganizationInfo

function addOrganizationInfo($s_connection,$s_nation,$s_name,$s_members,$s_managers,$s_pending,$s_ranking) {
	$new_id = 0;
	if (count($s_members) > 1) {
		$new_members = implode(",",$s_members);
	} else {
		$new_members = $s_members[0];
	} // else
	if (count($s_managers) > 1) {
		$new_managers = implode(",",$s_managers);
	} else {
		$new_managers = $s_managers[0];
	} // else
	if (count($s_pending) > 1) {
		$new_pending = implode(",",$s_pending);
	} else {
		$new_pending = $s_pending[0];
	} // else
	if ($stmt = $s_connection->prepare("INSERT INTO organizations (name,members,managers,pending,ranking) VALUES (?,?,?,?,?)")) {
		$stmt->bind_param("ssssi", $s_name, $new_members, $new_managers, $new_pending, $s_ranking);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
	$new_id = $s_connection->insert_id;
	$nationInfoM = getNationInfo($s_connection,$s_nation);
	$new_organizations = $nationInfoM["organizations"];
	$new_index = count($nationInfoM["organizations"]) + 1;
	$new_organizations[$new_index] = $new_id;

	setNationInfo($s_connection,$nationInfoM["id"],$nationInfoM["name"],$nationInfoM["home"],$nationInfoM["formal"],$nationInfoM["flag"],$nationInfoM["production"],$nationInfoM["money"],$nationInfoM["debt"],$nationInfoM["happiness"],$nationInfoM["food"],$nationInfoM["authority"],$nationInfoM["authorityChanged"],$nationInfoM["economy"],$nationInfoM["economyChanged"],$new_organizations,$nationInfoM["invites"],$nationInfoM["goods"],$nationInfoM["resources"],$nationInfoM["population"],$nationInfoM["strike"]);
} // addOrganizationInfo

function deleteOrganizationInfo($s_connection,$s_id) {
	if ($stmt = $s_connection->prepare("DELETE FROM organizations WHERE id=?")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else

	if ($stmt = $getPage_connection2->prepare("SELECT id FROM nations ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->store_result();

		while ($stmt->fetch()) {	
			$next_nations = $r_result;
			$nationInfo2 = getNationInfo($s_connection,$next_nations);
			$new_organizations = array(0=>0);
			$counter1 = 0;
			for ($xx=0; $xx < count($nationInfo2["organizations"]); $xx++) {
				if ($nationInfo2["organizations"][$xx] != $s_id) {
					$new_organizations[$counter1] = $nationInfo2["organizations"][$xx];
					$counter1++;
				} // if
			} // for
			setNationInfo($s_connection,$nationInfo2["id"],$nationInfo2["name"],$nationInfo2["home"],$nationInfo2["formal"],$nationInfo2["flag"],$nationInfo2["production"],$nationInfo2["money"],$nationInfo2["debt"],$nationInfo2["happiness"],$nationInfo2["food"],$nationInfo2["authority"],$nationInfo2["authorityChanged"],$nationInfo2["economy"],$nationInfo2["economyChanged"],$new_organizations, $nationInfo2["invites"],$nationInfo2["goods"],$nationInfo2["resources"],$nationInfo2["population"],$nationInfo2["strike"]);
		} // while
		$stmt->close();
	} else {
	} // else
} // deleteOrganizationInfo

// overlays
function getOverlayInfo($s_connection,$s_id) {
	$overlay = array("id"=>0,"name"=>"");
	if ($stmt = $s_connection->prepare("SELECT id,name FROM overlays WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_name);
		$stmt->fetch();
		$overlay["id"] = $r_id;
		$overlay["name"] = $r_name;
		$stmt->close();
	} else {
	} // else

	return $overlay;
} // getOverlayInfo

function setOverlayInfo($s_connection,$s_id,$s_name) {
	if ($stmt = $s_connection->prepare("UPDATE overlays SET name=? WHERE id=?")) {
		$stmt->bind_param("si", $s_name, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setOverlayInfo

// pagetypes
function getPageTypeInfo($s_connection,$s_name) {
	$pageType = array("id"=>0,"name"=>"","layout"=>"","login"=>0,"admin"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,name,layout,login,admin FROM pagetypes WHERE name=? LIMIT 1")) {
		$stmt->bind_param("s", $s_name);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_name,$r_layout,$r_login,$r_admin);
		$stmt->fetch();
		$pageType["id"] = $r_id;
		$pageType["name"] = $r_name;
		$pageType["layout"] = $r_layout;
		$pageType["login"] = $r_login;
		$pageType["admin"] = $r_admin;
		$stmt->close();
	} else {
	} // else

	return $pageType;
} // getPageTypeInfo

function setPageTypeInfo($s_connection,$s_id,$s_name,$s_layout,$s_login,$s_admin) {
	if ($stmt = $s_connection->prepare("UPDATE pagetypes SET name=?,layout=?,login=?,admin=? WHERE id=?")) {
		$stmt->bind_param("ssiii", $s_name, $s_layout, $s_login, $s_admin, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setPageTypeInfo

// production
function getProductionInfo($s_connection,$s_nation) {
	$production = array("id"=>0,"nation"=>0,"spending"=>0,"goods"=>array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0),"ratios"=>array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0));
	if ($stmt = $s_connection->prepare("SELECT id,nation,spending,goods,ratios FROM production WHERE nation=? LIMIT 1")) {
		$stmt->bind_param("i", $s_nation);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_nation,$r_spending,$r_goods,$r_ratios);
		$stmt->fetch();
		$production["id"] = $r_id;
		$production["nation"] = $r_nation;
		$production["spending"] = $r_spending;		
		if (stripos($r_goods,",")) {
			$production["goods"] = explode(",",$r_goods);
		} else {
			$production["goods"] = array(0=>$r_goods);
		} // else
		if (stripos($r_ratios,",")) {
			$production["ratios"] = explode(",",$r_ratios);
		} else {
			$production["ratios"] = array(0=>$r_ratios);
		} // else
		$stmt->close();
	} else {
	} // else

	return $production;
} // getProductionInfo

function setProductionInfo($s_connection,$s_nation,$s_spending,$s_goods,$s_ratios) {
	if (count($s_goods) > 1) {
		$new_goods = implode(",",$s_goods);
	} else {
		$new_goods = $s_goods[0];
	} // else	
	if (count($s_ratios) > 1) {
		$new_ratios = implode(",",$s_ratios);
	} else {
		$new_ratios = $s_ratios[0];
	} // else
	
	if ($stmt = $s_connection->prepare("UPDATE production SET nation=?,spending=?,goods=?,ratios=? WHERE nation=?")) {
		$stmt->bind_param("iissi", $s_nation, $s_spending, $new_goods, $new_ratios, $s_nation);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
		
	$productionInfoA = getProductionInfo($s_connection, $s_nation);
		
	$new_ratios = checkProductionRatios($s_connection, $productionInfoA);
	
	if (count($new_ratios) > 1) {
		$new_ratios = implode(",",$new_ratios);
	} else {
		$new_ratios = $new_ratios[0];
	} // else
	
	if ($stmt = $s_connection->prepare("UPDATE production SET nation=?,spending=?,goods=?,ratios=? WHERE nation=?")) {
		$stmt->bind_param("iissi", $s_nation, $s_spending, $new_goods, $new_ratios, $s_nation);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setProductionInfo

function addProductionInfo($s_connection,$s_nation,$s_spending,$s_goods,$s_ratios) {
	if (count($s_goods) > 1) {
		$new_goods = implode(",",$s_goods);
	} else {
		$new_goods = $s_goods[0];
	} // else
	if (count($s_ratios) > 1) {
		$new_ratios = implode(",",$s_ratios);
	} else {
		$new_ratios = $s_ratios[0];
	} // else
	if ($stmt = $s_connection->prepare("INSERT INTO production (nation,spending,goods,ratios) VALUES (?,?,?,?)")) {
		$stmt->bind_param("iiss", $s_nation, $s_spending, $new_goods, $new_ratios);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
		
	$productionInfoA = getProductionInfo($s_connection, $s_nation);
	
	$new_ratios = checkProductionRatios($s_connection, $productionInfoA);
	
	if (count($new_ratios) > 1) {
		$new_ratios = implode(",",$new_ratios);
	} else {
		$new_ratios = $new_ratios[0];
	} // else
	
	if ($stmt = $s_connection->prepare("UPDATE production SET nation=?,spending=?,goods=?,ratios=? WHERE nation=?")) {
		$stmt->bind_param("iissi", $s_nation, $s_spending, $new_goods, $new_ratios, $s_nation);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // addProductionInfo

// rankings
function getRankingInfo($s_connection,$s_nation) {
	$ranking = array("id"=>0,"nation"=>0,"production"=>0,"money"=>0,"happiness"=>0,"food"=>0,"population"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,nation,production,money,happiness,food,population FROM rankings WHERE nation=? LIMIT 1")) {
		$stmt->bind_param("i", $s_nation);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_nation,$r_production,$r_money,$r_happiness,$r_food,$r_population);
		$stmt->fetch();
		$ranking["id"] = $r_id;
		$ranking["nation"] = $r_nation;
		$ranking["production"] = $r_production;
		$ranking["money"] = $r_money;
		$ranking["happiness"] = $r_happiness;
		$ranking["food"] = $r_food;
		$ranking["population"] = $r_population;
		$stmt->close();
	} else {
	} // else

	return $ranking;
} // getRankingInfo

function setRankingInfo($s_connection,$s_nation,$s_production,$s_money,$s_happiness,$s_food,$s_population) {
	if ($stmt = $s_connection->prepare("UPDATE rankings SET production=?,money=?,happiness=?,food=?,population=? WHERE nation=?")) {
		$stmt->bind_param("iiiiii", $s_production, $s_money, $s_happiness, $s_food, $s_population, $s_nation);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setRankingInfo

function addRankingInfo($s_connection,$s_nation,$s_production,$s_money,$s_happiness,$s_food,$s_population) {
	if ($stmt = $s_connection->prepare("INSERT INTO rankings (nation,production,money,happiness,food,population) VALUES (?,?,?,?,?,?)")) {
		$stmt->bind_param("iiiiii", $s_nation, $s_production, $s_money, $s_happiness, $s_food, $s_population);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // addRankingInfo

// resources
function getResourceTypeInfo($s_connection,$s_id) {
	$resourceType = array("id"=>0,"name"=>"","incompatibleWith"=>array(0=>""),"image"=>"","buyStrength"=>0,"sellStrength"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,name,incompatibleWith,image,buyStrength,sellStrength FROM resources WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_name,$r_incompatibleWith,$r_image,$r_buyStrength,$r_sellStrength);
		$stmt->fetch();
		$resourceType["id"] = $r_id;
		$resourceType["name"] = $r_name;
		if (stripos($r_incompatibleWith,",")) {
			$resourceType["incompatibleWith"] = explode(",",$r_incompatibleWith);
		} else {
			$resourceType["incompatibleWith"] = array(0=>$r_incompatibleWith);
		} // else
		$resourceType["image"] = $r_image;
		$resourceType["buyStrength"] = $r_buyStrength;
		$resourceType["sellStrength"] = $r_sellStrength;
		$stmt->close();
	} else {
	} // else

	return $resourceType;
} // getResourceTypeInfo

function getResourceTypeInfoByName($s_connection,$s_name) {
	$resourceType = array("id"=>0,"name"=>"","incompatibleWith"=>array(0=>""),"image"=>"","buyStrength"=>0,"sellStrength"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,name,incompatibleWith,image,buyStrength,sellStrength FROM resources WHERE name LIKE ? LIMIT 1")) {
		$stmt->bind_param("s", $s_name);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_name,$r_incompatibleWith,$r_image,$r_buyStrength,$r_sellStrength);
		$stmt->fetch();
		$resourceType["id"] = $r_id;
		$resourceType["name"] = $r_name;
		if (stripos($r_incompatibleWith,",")) {
			$resourceType["incompatibleWith"] = explode(",",$r_incompatibleWith);
		} else {
			$resourceType["incompatibleWith"] = array(0=>$r_incompatibleWith);
		} // else
		$resourceType["image"] = $r_image;
		$resourceType["buyStrength"] = $r_buyStrength;
		$resourceType["sellStrength"] = $r_sellStrength;
		$stmt->close();
	} else {
	} // else

	return $resourceType;
} // getResourceTypeInfoByName

function setResourceTypeInfo($s_connection,$s_id,$s_name,$s_incompatibleWith,$s_image,$s_buyStrength,$s_sellStrength) {
	if ($stmt = $s_connection->prepare("UPDATE resources SET name=?,incompatibleWith=?,image=?,buyStrength=?,sellStrength=? WHERE id=?")) {
		$stmt->bind_param("sssiii", $s_name, $s_incompatibleWith, $s_image, $s_buyStrength, $s_sellStrength, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setResourceTypeInfo

// resourcesmap
function getResourceInfo($s_connection,$s_id) {
	$resource = array("id"=>0,"type"=>0,"capacity"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,type,capacity FROM resourcesmap WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_type,$r_capacity);
		$stmt->fetch();
		$resource["id"] = $r_id;
		$resource["type"] = $r_type;
		$resource["capacity"] = $r_capacity;
		$stmt->close();
	} else {
	} // else

	return $resource;
} // getResourceInfo

function setResourceInfo($s_connection,$s_id,$s_type,$s_capacity) {
	if ($stmt = $s_connection->prepare("UPDATE resourcesmap SET type=?,capacity=? WHERE id=?")) {
		$stmt->bind_param("iii", $s_type, $s_capacity, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setResourceInfo

function addResourceInfo($s_connection,$s_continent,$s_xpos,$s_ypos,$s_type,$s_capacity) {
	$new_id = 0;
	if ($stmt = $s_connection->prepare("INSERT INTO resourcesmap (type,capacity) VALUES (?,?)")) {
		$stmt->bind_param("ii", $s_type, $s_capacity);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
	$new_id = $s_connection->insert_id;
	$tileInfoM = getTileInfo($s_connection,$s_continent,$s_xpos,$s_ypos);
	$new_resources = $tileInfoM["resources"];
	$new_index = count($tileInfoM["resources"]) + 1;
	$new_resources[$new_index] = $new_id;
	$_SESSION["something"] = $new_id;
	setTileInfo($s_connection,$tileInfoM["id"],$tileInfoM["continent"],$tileInfoM["xpos"],$tileInfoM["ypos"],$tileInfoM["terrain"],$new_resources,$tileInfoM["improvements"],$tileInfoM["owner"],$tileInfoM["claims"],$tileInfoM["population"]);
} // addResourceInfo

function deleteResourceInfo($s_connection,$s_id,$s_continent,$s_xpos,$s_ypos) {
	$new_resources = array(0=>0);
	if ($stmt = $s_connection->prepare("DELETE FROM resourcesmap WHERE id=?")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
	$counter = 0;
	$tileInfoM = getTileInfo($s_connection,$s_continent,$s_xpos,$s_ypos);
	for ($z = 0;$z < count($tileInfoM["resources"]); $z++) {
		if ($s_id != $tileInfoM["resources"][$z]) {
			$new_resources[$counter] = $tileInfoM["resources"][$z];
			$counter++;
		} // if
	} // for
	setTileInfo($s_connection,$tileInfoM["id"],$tileInfoM["continent"],$tileInfoM["xpos"],$tileInfoM["ypos"],$tileInfoM["terrain"],$new_resources,$tileInfoM["improvements"],$tileInfoM["owner"],$tileInfoM["claims"],$tileInfoM["population"]);
} // deleteResourceInfo

// terms
function getTermsInfo($s_connection,$s_id) {
	$terms = array("id"=>0,"text"=>"","date"=>"","version"=>0.0);
	if ($stmt = $s_connection->prepare("SELECT id,text,date,version FROM terms WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_text,$r_date,$r_version);
		$stmt->fetch();
		$terms["id"] = $r_id;
		$terms["text"] = $r_text;
		$terms["date"] = $r_date;
		$terms["version"] = $r_version;
		$stmt->close();
	} else {
	} // else

	return $terms;
} // getTermsInfo

function setTermsInfo($s_connection,$s_id,$s_text,$s_date,$s_version) {
	if ($stmt = $s_connection->prepare("UPDATE terms SET text=?,date=?,version=? WHERE id=?")) {
		$stmt->bind_param("ssdi", $s_text, $s_date, $s_version, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setTermsInfo

// terrain
function getTerrainInfo($s_connection,$s_id) {
	$terrain = array("id"=>0,"name"=>"","image"=>"","movementRestriction"=>0,"productionModifier"=>0,"upkeepModifier"=>0,"attackModifier"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,name,image,movementRestriction,productionModifier,upkeepModifier,attackModifier FROM terrain WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_name,$r_image,$r_movementRestriction,$r_productionModifier,$r_upkeepModifier,$r_attackModifier);
		$stmt->fetch();
		$terrain["id"] = $r_id;
		$terrain["name"] = $r_name;
		$terrain["image"] = $r_image;
		$terrain["movementRestriction"] = $r_movementRestriction;
		$terrain["productionModifier"] = $r_productionModifier;
		$terrain["upkeepModifier"] = $r_upkeepModifier;
		$terrain["attackModifier"] = $r_attackModifier;
		$stmt->close();
	} else {
	} // else

	return $terrain;
} // getTerrainInfo

function setTerrainInfo($s_connection,$s_id,$s_name,$s_image,$s_movementRestriction,$s_productionModifier,$s_upkeepModifier,$s_attackModifier) {
	if ($stmt = $s_connection->prepare("UPDATE terrain SET name=?,image=?,movementRestriction=?,productionModifier=?,upkeepModifier=?,attackModifier=? WHERE id=?")) {
		$stmt->bind_param("ssiiiii", $s_name, $s_image, $s_movementRestriction, $s_productionModifier, $s_upkeepModifier, $s_attackModifier, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setTerrainInfo

// tilesmap
function getTileInfo($s_connection,$s_continent_id,$s_x,$s_y) {
	$tile = array("id"=>0,"continent"=>0,"xpos"=>0,"ypos"=>0,"terrain"=>0,"resources"=>array(0=>""),"improvements"=>array(0=>""),"owner"=>0,"claims"=>array(0=>""),"population"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,continent,xpos,ypos,terrain,resources,improvements,owner,claims,population FROM tilesmap WHERE continent=? AND xpos=? AND ypos=? LIMIT 1")) {
		$stmt->bind_param("iii", $s_continent_id, $s_x, $s_y);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_continent,$r_xpos,$r_ypos,$r_terrain,$r_resources,$r_improvements,$r_owner,$r_claims,$r_population);
		$stmt->fetch();
		$tile["id"] = $r_id;
		$tile["continent"] = $r_continent;
		$tile["xpos"] = $r_xpos;
		$tile["ypos"] = $r_ypos;
		$tile["terrain"] = $r_terrain;
		if (stripos($r_resources,",")) {
			$tile["resources"] = explode(",",$r_resources);
		} else {
			$tile["resources"] = array(0=>$r_resources);
		} // else
		if (stripos($r_improvements,",")) {
			$tile["improvements"] = explode(",",$r_improvements);
		} else {
			$tile["improvements"] = array(0=>$r_improvements);
		} // else
		$tile["owner"] = $r_owner;
		if (stripos($r_claims,",")) {
			$tile["claims"] = explode(",",$r_claims);
		} else {
			$tile["claims"] = array(0=>$r_claims);
		} // else
		$tile["population"] = $r_population;
		$stmt->close();
	} else {
	} // else

	return $tile;
} // getTileInfo

function getTileInfoByID($s_connection,$s_id) {
	$tile = array("id"=>0,"continent"=>0,"xpos"=>0,"ypos"=>0,"terrain"=>0,"resources"=>array(0=>""),"improvements"=>array(0=>""),"owner"=>0,"claims"=>array(0=>""),"population"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,continent,xpos,ypos,terrain,resources,improvements,owner,claims,population FROM tilesmap WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_continent,$r_xpos,$r_ypos,$r_terrain,$r_resources,$r_improvements,$r_owner,$r_claims,$r_population);
		$stmt->fetch();
		$tile["id"] = $r_id;
		$tile["continent"] = $r_continent;
		$tile["xpos"] = $r_xpos;
		$tile["ypos"] = $r_ypos;
		$tile["terrain"] = $r_terrain;
		if (stripos($r_resources,",")) {
			$tile["resources"] = explode(",",$r_resources);
		} else {
			$tile["resources"] = array(0=>$r_resources);
		} // else
		if (stripos($r_improvements,",")) {
			$tile["improvements"] = explode(",",$r_improvements);
		} else {
			$tile["improvements"] = array(0=>$r_improvements);
		} // else
		$tile["owner"] = $r_owner;
		if (stripos($r_claims,",")) {
			$tile["claims"] = explode(",",$r_claims);
		} else {
			$tile["claims"] = array(0=>$r_claims);
		} // else
		$tile["population"] = $r_population;
		$stmt->close();
	} else {
	} // else

	return $tile;
} // getTileInfoByID

function setTileInfo($s_connection,$s_id,$s_continent,$s_xpos,$s_ypos,$s_terrain,$s_resources,$s_improvements,$s_owner,$s_claims,$s_population) {
	if (count($s_resources) > 1) {
		$new_resources = implode(",",$s_resources);
	} else {
		$new_resources = $s_resources[0];
	} // else
	if (count($s_improvements) > 1) {
		$new_improvements = implode(",",$s_improvements);
	} else {
		$new_improvements = $s_improvements[0];
	} // else
	if (count($s_claims) > 1) {
		$new_claims = implode(",",$s_claims);
	} else {
		$new_claims = $s_claims[0];
	} // else
	if ($stmt = $s_connection->prepare("UPDATE tilesmap SET continent=?,xpos=?,ypos=?,terrain=?,resources=?,improvements=?,owner=?,claims=?,population=? WHERE id=?")) {
		$stmt->bind_param("iiiissisii", $s_continent, $s_xpos, $s_ypos, $s_terrain, $new_resources, $new_improvements, $s_owner, $new_claims, $s_population, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setTileInfo

function addTileInfo($s_connection,$s_continent,$s_xpos,$s_ypos,$s_terrain,$s_resources,$s_improvements,$s_owner,$s_claims,$s_population) {
	if (count($s_resources) > 1) {
		$new_resources = implode(",",$s_resources);
	} else {
		$new_resources = $s_resources[0];
	} // else
	if (count($s_improvements) > 1) {
		$new_improvements = implode(",",$s_improvements);
	} else {
		$new_improvements = $s_improvements[0];
	} // else
	if (count($s_claims) > 1) {
		$new_claims = implode(",",$s_claims);
	} else {
		$new_claims = $s_claims[0];
	} // else
	if ($stmt = $s_connection->prepare("INSERT INTO tilesmap (continent,xpos,ypos,terrain,resources,improvements,owner,claims,population) VALUES (?,?,?,?,?,?,?,?,?)")) {
		$stmt->bind_param("iiiissisi", $s_continent, $s_xpos, $s_ypos, $s_terrain, $new_resources, $new_improvements, $s_owner, $new_claims, $s_population);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // addTileInfo

// trade
function getTradeInfo($s_connection,$s_nation) {
	$trade = array("id"=>0,"nation"=>0,"routes"=>array(0=>0),"limit"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,nation,routes,`limit` FROM trade WHERE nation=? LIMIT 1")) {
		$stmt->bind_param("i", $s_nation);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_nation,$r_routes,$r_limit);
		$stmt->fetch();
		$trade["id"] = $r_id;
		$trade["nation"] = $r_nation;
		if (stripos($r_routes,",")) {
			$trade["routes"] = explode(",",$r_routes);
		} else {
			$trade["routes"] = array(0=>$r_routes);
		} // else
		$trade["limit"] = $r_limit;
		$stmt->close();
	} else {
	} // else

	return $trade;
} // getTradeInfo

function setTradeInfo($s_connection,$s_id,$s_nation,$s_routes,$s_limit) {
	if (count($s_routes) > 1) {
		$new_routes = implode(",",$s_routes);
	} else {
		$new_routes = $s_routes[0];
	} // else
	if ($stmt = $s_connection->prepare("UPDATE trade SET nation=?,routes=?,`limit`=? WHERE id=?")) {
		$stmt->bind_param("isii",$s_nation,$new_routes,$s_limit,$s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setTradeInfo

function addTradeInfo($s_connection,$s_nation,$s_routes,$s_limit) {
	if (count($s_routes) > 1) {
		$new_routes = implode(",",$s_routes);
	} else {
		$new_routes = $s_routes[0];
	} // else
	if ($stmt = $s_connection->prepare("INSERT INTO trade (nation,routes,`limit`) VALUES (?,?,?)")) {
		$stmt->bind_param("isi",$s_nation,$new_routes,$s_limit);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // addTradeInfo

/*
// trade
function getTradeInfo($s_connection,$s_nation) {
	$trade = array("id"=>0,"nation"=>0,"routes"=>array(0=>0),"worth"=>array(0=>0),"offers"=>array(0=>0),"limit"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,nation,routes,worth,offers,`limit` FROM trade WHERE nation=? LIMIT 1")) {
		$stmt->bind_param("i", $s_nation);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_nation,$r_routes,$r_worth,$r_offers,$r_limit);
		$stmt->fetch();
		$trade["id"] = $r_id;
		$trade["nation"] = $r_nation;
		if (stripos($r_routes,",")) {
			$trade["routes"] = explode(",",$r_routes);
		} else {
			$trade["routes"] = array(0=>$r_routes);
		} // else
		if (stripos($r_worth,",")) {
			$trade["worth"] = explode(",",$r_worth);
		} else {
			$trade["worth"] = array(0=>$r_worth);
		} // else
		if (stripos($r_offers,",")) {
			$trade["offers"] = explode(",",$r_offers);
		} else {
			$trade["offers"] = array(0=>$r_offers);
		} // else
		$trade["limit"] = $r_limit;
		$stmt->close();
	} else {
	} // else

	return $trade;
} // getTradeInfo

function setTradeInfo($s_connection,$s_id,$s_nation,$s_routes,$s_worth,$s_offers,$s_limit) {
	if (count($s_routes) > 1) {
		$new_routes = implode(",",$s_routes);
	} else {
		$new_routes = $s_routes[0];
	} // else
	if (count($s_worth) > 1) {
		$new_worth = implode(",",$s_worth);
	} else {
		$new_worth = $s_worth[0];
	} // else
	if (count($s_offers) > 1) {
		$new_offers = implode(",",$s_offers);
	} else {
		$new_offers = $s_offers[0];
	} // else
	if ($stmt = $s_connection->prepare("UPDATE trade SET nation=?,routes=?,worth=?,offers,=?,`limit`=? WHERE id=?")) {
		$stmt->bind_param("isssii",$s_nation,$new_routes,$new_worth,$new_offers,$s_limit,$s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setTradeInfo

function addTradeInfo($s_connection,$s_nation,$s_routes,$s_worth,$s_offers,$s_limit) {
	if (count($s_routes) > 1) {
		$new_routes = implode(",",$s_routes);
	} else {
		$new_routes = $s_routes[0];
	} // else
	if (count($s_worth) > 1) {
		$new_worth = implode(",",$s_worth);
	} else {
		$new_worth = $s_worth[0];
	} // else
	if (count($s_offers) > 1) {
		$new_offers = implode(",",$s_offers);
	} else {
		$new_offers = $s_offers[0];
	} // else
	if ($stmt = $s_connection->prepare("INSERT INTO trade (nation,routes,worth,offers,`limit`) VALUES (?,?,?,?,?)")) {
		$stmt->bind_param("isssi",$s_nation,$new_routes,$new_worth,$new_offers,$s_limit);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // addTradeInfo
*/

// transport
function getTransportInfo($s_connection,$s_id) {
	$transport = array("id"=>0,"list"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,list FROM transport WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_list);
		$stmt->fetch();
		$transport["id"] = $r_id;
		if (stripos($r_list,",")) {
			$transport["list"] = explode(",",$r_list);
		} else {
			$transport["list"] = array(0=>$r_list);
		} // else
		$stmt->close();
	} else {
	} // else

	return $transport;
} // getTransportInfo

function setTransportInfo($s_connection,$s_id,$s_list) {
	if (count($s_list) > 1) {
		$new_list = implode(",",$s_list);
	} else {
		$new_list = $s_list[0];
	} // else
	if ($stmt = $s_connection->prepare("UPDATE transport SET list=? WHERE id=?")) {
		$stmt->bind_param("si", $new_list, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setTransportInfo

function addTransportInfo($s_connection,$s_list) {
	if (count($s_list) > 1) {
		$new_list = implode(",",$s_list);
	} else {
		$new_list = $s_list[0];
	} // else
	if ($stmt = $s_connection->prepare("INSERT INTO transport (list) VALUES (?)")) {
		$stmt->bind_param("s", $new_list);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // addTransportInfo

// units
function getUnitTypeInfo($s_connection,$s_id) {
	$unitType = array("id"=>0,"name"=>"","attack"=>0,"defense"=>0,"movement"=>0,"health"=>0,"water"=>0,"foodRequired"=>0,"goodsRequired"=>array(0=>0),"baseCost"=>0,"image"=>"","selected"=>"");
	if ($stmt = $s_connection->prepare("SELECT id,name,attack,defense,movement,health,water,foodRequired,goodsRequired,baseCost,image,selected FROM units WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_name,$r_attack,$r_defense,$r_movement,$r_health,$r_water,$r_foodRequired,$r_goodsRequired,$r_baseCost,$r_image,$r_selected);
		$stmt->fetch();
		$unitType["id"] = $r_id;
		$unitType["name"] = $r_name;
		$unitType["attack"] = $r_attack;
		$unitType["defense"] = $r_defense;
		$unitType["movement"] = $r_movement;
		$unitType["health"] = $r_health;
		$unitType["water"] = $r_water;
		$unitType["foodRequired"] = $r_foodRequired;
		if (stripos($r_goodsRequired,",")) {
			$unitType["goodsRequired"] = explode(",",$r_goodsRequired);
		} else {
			$unitType["goodsRequired"] = array(0=>$r_goodsRequired);
		} // else
		$unitType["baseCost"] = $r_baseCost;
		$unitType["image"] = $r_image;
		$unitType["selected"] = $r_selected;
		$stmt->close();
	} else {
	} // else

	return $unitType;
} // getUnitTypeInfo

function setUnitTypeInfo($s_connection,$s_id,$s_name,$s_attack,$s_defense,$s_movement,$s_health,$s_water,$s_foodRequired,$s_goodsRequired,$s_baseCost,$s_image,$s_selected) {
	if (count($s_goodsRequired) > 1) {
		$new_goodsRequired = implode(",",$s_goodsRequired);
	} else {
		$new_goodsRequired = $s_goodsRequired[0];
	} // else
	if ($stmt = $s_connection->prepare("UPDATE units SET name=?,attack=?,defense=?,movement=?,health=?,water=?,foodRequired=?,goodsRequired=?,baseCost=?,image=?,selected=? WHERE id=?")) {
		$stmt->bind_param("siiiiidsdssi", $s_name, $s_attack, $s_defense, $s_movement, $s_health, $s_water, $s_foodRequired, $new_goodsRequired, $s_baseCost, $s_image, $s_selected, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setUnitTypeInfo

// unitsmap
function getUnitInfo($s_connection,$s_continent_id,$s_xpos,$s_ypos) {
	$unit = array("id"=>0,"continent"=>0,"xpos"=>0,"ypos"=>0,"health"=>0,"used"=>0,"name"=>"","type"=>0,"owner"=>0,"level">=0,"transport"=>0,"created"=>0,"exp"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,continent,xpos,ypos,health,used,name,type,owner,level,transport,created,exp FROM unitsmap WHERE continent=? AND xpos=? AND ypos=? LIMIT 1")) {
		$stmt->bind_param("iii", $s_continent_id, $s_xpos, $s_ypos);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_continent,$r_xpos,$r_ypos,$r_health,$r_used,$r_name,$r_type,$r_owner,$r_level,$r_transport,$r_created,$r_exp);
		$stmt->fetch();
		$unit["continent"] = $r_continent;
		$unit["xpos"] = $r_xpos;
		$unit["ypos"] = $r_ypos;
		$unit["health"] = $r_health;
		$unit["used"] = $r_used;
		$unit["id"] = $r_id;
		$unit["name"] = $r_name;
		$unit["type"] = $r_type;
		$unit["owner"] = $r_owner;
		$unit["level"] = $r_level;
		$unit["transport"] = $r_transport;
		$unit["created"] = $r_created;
		$unit["exp"] = $r_exp;
		$stmt->close();
	} else {
	} // else

	return $unit;
} // getUnitInfo

// unitsmap: by id
function getUnitInfoByID($s_connection,$s_id) {
	$unit = array("id"=>0,"continent"=>0,"xpos"=>0,"ypos"=>0,"health"=>0,"used"=>0,"name"=>"","type"=>0,"owner"=>0,"level">=0,"transport"=>0,"created"=>0,"exp"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,continent,xpos,ypos,health,used,name,type,owner,level,transport,created,exp FROM unitsmap WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_continent,$r_xpos,$r_ypos,$r_health,$r_used,$r_name,$r_type,$r_owner,$r_level,$r_transport,$r_created,$r_exp);
		$stmt->fetch();
		$unit["continent"] = $r_continent;
		$unit["xpos"] = $r_xpos;
		$unit["ypos"] = $r_ypos;
		$unit["health"] = $r_health;
		$unit["used"] = $r_used;
		$unit["id"] = $r_id;
		$unit["name"] = $r_name;
		$unit["type"] = $r_type;
		$unit["owner"] = $r_owner;
		$unit["level"] = $r_level;
		$unit["transport"] = $r_transport;
		$unit["created"] = $r_created;
		$unit["exp"] = $r_exp;
		$stmt->close();
	} else {
	} // else

	return $unit;
} // getUnitInfoByID

function setUnitInfo($s_connection,$s_id,$s_continent,$s_xpos,$s_ypos,$s_health,$s_used,$s_name,$s_type,$s_owner,$s_level,$s_transport,$s_created,$s_exp) {
	if ($stmt = $s_connection->prepare("UPDATE unitsmap SET continent=?,xpos=?,ypos=?,health=?,used=?,name=?,type=?,owner=?,level=?,transport=?,created=?,exp=? WHERE id=?")) {
		$stmt->bind_param("iiidisiiiiidi", $s_continent, $s_xpos, $s_ypos, $s_health, $s_used, $s_name, $s_type, $s_owner, $s_level, $s_transport, $s_created, $s_exp, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setUnitInfo

function deleteUnitInfo($s_connection,$s_id) {
	if ($stmt = $s_connection->prepare("DELETE FROM unitsmap WHERE id=?")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // deleteUnitInfo

function addUnitInfo($s_connection,$s_continent,$s_xpos,$s_ypos,$s_health,$s_used,$s_name,$s_type,$s_owner,$s_level,$s_transport,$s_created,$s_exp) {
	if ($stmt = $s_connection->prepare("INSERT INTO unitsmap (continent,xpos,ypos,health,used,name,type,owner,level,transport,created,exp) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)")) {
		$stmt->bind_param("iiidisiiiiii", $s_continent, $s_xpos, $s_ypos, $s_health, $s_used, $s_name, $s_type, $s_owner, $s_level, $s_transport, $s_created, $s_exp);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // addUnitInfo

// users
function getUserInfo($s_connection,$s_id) {
	$user = array("id"=>0,"name"=>"","avatar"=>"","joined"=>"","lastplayed"=>"","password"=>"","salt"=>"","token"=>0,"thread"=>0,"admin"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,name,avatar,joined,lastplayed,password,salt,token,thread,admin FROM users WHERE id=? LIMIT 1")) {
		$stmt->bind_param("i", $s_id);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_name,$r_avatar,$r_joined,$r_lastplayed,$r_password,$r_salt,$r_token,$r_thread,$r_admin);
		$stmt->fetch();
		$user["id"] = $r_id;
		$user["name"] = $r_name;
		$user["avatar"] = $r_avatar;
		$user["joined"] = $r_joined;
		$user["lastplayed"] = $r_lastplayed;
		$user["password"] = $r_password;
		$user["salt"] = $r_salt;
		$user["token"] = $r_token;
		$user["thread"] = $r_thread;
		$user["admin"] = $r_admin;
		$stmt->close();
	} else {
	} // else

	return $user;
} // getUserInfo

function getUserInfoByName($s_connection,$s_name) {
	$user = array("id"=>0,"name"=>"","avatar"=>"","joined"=>"","lastplayed"=>"","password"=>"","salt"=>"","token"=>0,"thread"=>0,"admin"=>0);
	if ($stmt = $s_connection->prepare("SELECT id,name,avatar,joined,lastplayed,password,salt,token,thread,admin FROM users WHERE name LIKE ? LIMIT 1")) {
		$stmt->bind_param("s", $s_name);
		$stmt->execute();
		$stmt->bind_result($r_id,$r_name,$r_avatar,$r_joined,$r_lastplayed,$r_password,$r_salt,$r_token,$r_thread,$r_admin);
		$stmt->fetch();
		$user["id"] = $r_id;
		$user["name"] = $r_name;
		$user["avatar"] = $r_avatar;
		$user["joined"] = $r_joined;
		$user["lastplayed"] = $r_lastplayed;
		$user["password"] = $r_password;
		$user["salt"] = $r_salt;
		$user["token"] = $r_token;
		$user["thread"] = $r_thread;
		$user["admin"] = $r_admin;
		$stmt->close();
	} else {
	} // else

	return $user;
} // getUserInfoByName

function setUserInfo($s_connection,$s_id,$s_name,$s_avatar,$s_joined,$s_lastplayed,$s_password,$s_salt,$s_token,$s_thread,$s_admin) {
	if ($stmt = $s_connection->prepare("UPDATE users SET name=?,avatar=?,joined=?,lastplayed=?,password=?,salt=?,token=?,thread=?,admin=? WHERE id=?")) {
		$stmt->bind_param("ssssssiiii", $s_name, $s_avatar, $s_joined, $s_lastplayed, $s_password, $s_salt, $s_token, $s_thread, $s_admin, $s_id);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // setUserInfo

function addUserInfo($s_connection,$s_name,$s_avatar,$s_joined,$s_lastplayed,$s_password,$s_salt,$s_token,$s_thread,$s_admin) {
	if ($stmt = $s_connection->prepare("INSERT INTO users (name,avatar,joined,lastplayed,password,salt,token,thread,admin) VALUES (?,?,?,?,?,?,?,?,?)")) {
		$stmt->bind_param("ssssssiii", $s_name, $s_avatar, $s_joined, $s_lastplayed, $s_password, $s_salt, $s_token, $s_thread, $s_admin);
		$stmt->execute();
		$stmt->close();
	} else {
	} // else
} // addUserInfo
?>