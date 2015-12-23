<?php
/****************************************************************************
 * Name:        functions_search.php
 * Author:      Ben Barnes
 * Date:        2015-12-21
 * Purpose:     Search functions page
 *****************************************************************************/

/*-----------------------------------------------*/
/********************************
 Search Page Functions
 ********************************/
/*-----------------------------------------------*/

/********************************
 getGlobals_search
 get and set global variables for search page
 ********************************/
function getGlobals_search($getPage_connection2) {
	// session: admin
	if (isset($_SESSION["admin"])) {
		$_SESSION["admin"] = cleanString($_SESSION["admin"],true);
	} else {
		$_SESSION["admin"] = 0;
	} // else
		
	
	// session: results output
	if (isset($_SESSION["results_output"])) {

	} else {
		$_SESSION["results_output"] = "Search length requirements are not met!  Search must be 5-75 characters long.";
	} // else
		
	if (count($_POST)) {	
		// post: search terms
		if (isset($_POST["search"])) {
			$_SESSION["search"] = cleanString($_POST["search"],false);
		} else {
			$_SESSION["search"] = "";
		} // else
		
		$_SESSION["results"] = array("");
		
		// parse results into separate strings
		if (stristr($_SESSION["search"], " ") === false) {
			$_SESSION["results"][0] = $_SESSION["search"];
		} else {
			$_SESSION["results"] = explode(" ",$_SESSION["search"]);
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
	$_SESSION["userInfo"] = getUserInfo($getPage_connection2,$_SESSION["user_id"]);
	$_SESSION["nationInfo"] = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
} // getGlobals_search

/********************************
 performAction_search
 calls action for search if requested and valid
 ********************************/
function performAction_search($getPage_connection2) {
	if (isset($_SESSION["results"])) {
		if (strlen($_SESSION["results"][0]) > 0) {
			$_SESSION["results_output"] = processSearch($getPage_connection2,$_SESSION["results"]);
		} else {
			$_SESSION["results_output"] = "Search length requirements are not met!  Search must be 5-75 characters long.";
		} // else
	} else {
		$_SESSION["results_output"] = "Search length requirements are not met!  Search must be 5-75 characters long.";
	} // else
} // performAction_search

/********************************
 showSearchInfo
 visualize search information and input
 ********************************/
function showSearchInfo($getPage_connection2) {
	$nationInfo = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
	$continentInfo = getContinentInfo($getPage_connection2, $nationInfo["home"]);

	echo "        <div class=\"spacing-from-menu well well-lg standard-text\">\n";

	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Search Results        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseSearchResults\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseSearchResults\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	echo $_SESSION["results_output"];
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";

	echo "        </div>\n";
} // showSearchInfo

/*-----------------------------------------------*/
/********************************
 Search Action Functions
 ********************************/
/*-----------------------------------------------*/

/********************************
 processSearch
 processes all search terms and looks for results, outputs
 ********************************/
function processSearch($getPage_connection2,$searchTerms) {
	$validSearch = false;
	$foundSomething = false;
	
	$output =  "Results for ";
	for ($a = 0; $a < count($searchTerms); $a++) {
		$output .= " \"$searchTerms[$a]\" \n";
	} // for
	$output .= "<br /><br />\n";	
	
	$stringSearchTerms = implode(" ", $searchTerms);
	
	if (strlen($stringSearchTerms) > 5 && strlen($stringSearchTerms) < 75) {
		$validSearch = true;
	} else {
		$validSearch = false;
	} // else
		
	if ($validSearch === true) {	
		$stringSearchTerms = strtolower($stringSearchTerms);
		$stringSearchTerms1 = "%".$stringSearchTerms."%";
			
		// nation search
		
		// go through 4 possibilities
		for ($qa=0; $qa < 4; $qa++) {
			$offset = $qa;
			$nationSearchInfo1 = array("id"=>0,"name"=>"");
			
			// first, search for exact match
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM nations WHERE LOWER(name) LIKE ? OR LOWER(formal) LIKE ? LIMIT 1 OFFSET ?")) {
				$stmt->bind_param("ssi", $stringSearchTerms, $stringSearchTerms, $offset);
				$stmt->execute();
				$stmt->bind_result($r_id);
				$stmt->fetch();
				$nationSearchInfo1["id"] = $r_id;
				$stmt->close();
			} else {
			} // else
				
			// if exact match found,
			if ($nationSearchInfo1["id"] > 0) {
				$foundSomething = true;
				$resultsInfo1 = getNationInfo($getPage_connection2, $nationSearchInfo1["id"]);
				$output .= "Nation: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$resultsInfo1["id"]."\">".$resultsInfo1["name"]."</a> <br />\n";
				
				break;
			
			// otherwise look for rough match,
			} else {
				// second, search for inclusive match
				if ($stmt = $getPage_connection2->prepare("SELECT id FROM nations WHERE LOWER(name) LIKE ? OR LOWER(formal) LIKE ? LIMIT 1 OFFSET ?")) {
					$stmt->bind_param("ssi", $stringSearchTerms1, $stringSearchTerms1, $offset);
					$stmt->execute();
					$stmt->bind_result($r_id);
					$stmt->fetch();
					$nationSearchInfo1["id"] = $r_id;
					$stmt->close();
				} else {
				} // else	

				// if match found,
				if ($nationSearchInfo1["id"] > 0) {
					$foundSomething = true;
					$resultsInfo1 = getNationInfo($getPage_connection2, $nationSearchInfo1["id"]);
					$output .= "Nation: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$resultsInfo1["id"]."\">".$resultsInfo1["name"]."</a> <br />\n";		
				} else {
				} // else
			} // else			
		} // for
			
		// organizations search
		
		// go through 4 possibilities
		for ($qb=0; $qb < 4; $qb++) {
			$offset = $qb;
			$orgSearchInfo1 = array("id"=>0,"name"=>"");
				
			// first, search for exact match
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM organizations WHERE LOWER(name) LIKE ? LIMIT 1 OFFSET ?")) {
				$stmt->bind_param("si", $stringSearchTerms, $offset);
				$stmt->execute();
				$stmt->bind_result($r_id);
				$stmt->fetch();
				$orgSearchInfo1["id"] = $r_id;
				$stmt->close();
			} else {
			} // else
		
			// if exact match found,
			if ($orgSearchInfo1["id"] > 0) {
				$foundSomething = true;
				$resultsInfo2 = getOrganizationInfo($getPage_connection2, $orgSearchInfo1["id"]);
				$output .= "Organization: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$resultsInfo2["id"]."\">".$resultsInfo2["name"]."</a> <br />\n";
				
				break;
					
				// otherwise look for rough match,
			} else {
				// second, search for inclusive match
				if ($stmt = $getPage_connection2->prepare("SELECT id FROM organizations WHERE LOWER(name) LIKE ? LIMIT 1 OFFSET ?")) {
					$stmt->bind_param("si", $stringSearchTerms1, $offset);
					$stmt->execute();
					$stmt->bind_result($r_id);
					$stmt->fetch();
					$orgSearchInfo1["id"] = $r_id;
					$stmt->close();
				} else {
				} // else
		
				// if match found,
				if ($orgSearchInfo1["id"] > 0) {
					$foundSomething = true;
					$resultsInfo2 = getOrganizationInfo($getPage_connection2, $orgSearchInfo1["id"]);
					$output .= "Organization: <a href=\"index.php?page=info&amp;section=orgs&amp;info_id=".$resultsInfo2["id"]."\">".$resultsInfo2["name"]."</a> <br />\n";
				} else {
				} // else
			} // else
		} // for
		
		if ($foundSomething === false) {
			$output .= "No results found.";
		} // if
	} else {
		$output .= "Search length requirements are not met!  Search must be 5-75 characters long.\n";
	} // else
		
	
	return $output;
} // processSearch

?>