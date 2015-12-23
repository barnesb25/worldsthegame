<?php
/****************************************************************************
 * Name:        functions_help.php
 * Author:      Ben Barnes
 * Date:        2015-12-21
 * Purpose:     Help functions page
 *****************************************************************************/

/********************************
 getGlobals_help
 get and set global variables for help page
 ********************************/
function getGlobals_help($getPage_connection2) {
	// session: admin
	if (isset($_SESSION["admin"])) {
		$_SESSION["admin"] = cleanString($_SESSION["admin"],true);
	} else {
		$_SESSION["admin"] = 0;
	} // else

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
} // getGlobals_help

/********************************
 showHelpInfo
 visualize help information and input
 ********************************/
function showHelpInfo($getPage_connection2) {
	$nationInfo = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);

	echo "        <div class=\"spacing-from-menu well well-lg standard-text\">\n";

	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Table of Contents        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseHelpTC\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseHelpTC\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";

	$next_helpcategories = 1;
	if ($stmt = $getPage_connection2->prepare("SELECT id FROM helpcategories ORDER BY id ASC LIMIT 1")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->fetch();
		$next_helpcategories = $r_result;
		$stmt->close();
	} else {
		$next_helpcategories = 0;
	} // else
	while ($next_helpcategories > 0) {
		$helpCategoriesInfo1 = getHelpCategoriesInfo($getPage_connection2,$next_helpcategories);

		echo "                <a class=\"cat\" href=\"#collapseHelp".$next_helpcategories."\">".$next_helpcategories." - ".$helpCategoriesInfo1["title"]."</a>\n";
		echo "                <br />\n";

		$next_helpsubcategories = 1;
		$subcategoryCounter = 0;
		if ($stmt = $getPage_connection2->prepare("SELECT id FROM helpsubcategories ORDER BY id ASC LIMIT 1")) {
			$stmt->execute();
			$stmt->bind_result($r_result);
			$stmt->fetch();
			$next_helpsubcategories = $r_result;
			$stmt->close();
		} else {
			$next_helpsubcategories = 0;
		} // else
		while ($next_helpsubcategories > 0) {
			$helpSubcategoriesInfo1 = getHelpSubcategoriesInfo($getPage_connection2,$next_helpsubcategories);

			if ($helpSubcategoriesInfo1["category"] == $helpCategoriesInfo1["id"]) {
				$subcategoryCounter++;
				echo "                <a class=\"subcat\" href=\"#".$next_helpcategories."-".$subcategoryCounter."\">".$next_helpcategories.".".$subcategoryCounter." - ".$helpSubcategoriesInfo1["title"]."</a>\n";
				echo "                <br />\n";
			} // if

			if ($stmt = $getPage_connection2->prepare("SELECT id FROM helpsubcategories WHERE id = (SELECT MIN(id) FROM helpsubcategories WHERE id > ?) ORDER BY id LIMIT 1")) {
				$stmt->bind_param("i", $next_helpsubcategories);
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->fetch();
				$next_helpsubcategories = $r_result;
				$stmt->close();
			} else {
				$next_helpsubcategories = 0;
			} // else
		} // while

		echo "                <br />\n";

		if ($stmt = $getPage_connection2->prepare("SELECT id FROM helpcategories WHERE id = (SELECT MIN(id) FROM helpcategories WHERE id > ?) ORDER BY id LIMIT 1")) {
			$stmt->bind_param("i", $next_helpcategories);
			$stmt->execute();
			$stmt->bind_result($r_result);
			$stmt->fetch();
			$next_helpcategories = $r_result;
			$stmt->close();
		} else {
			$next_helpcategories = 0;
		} // else
	} // while

	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";

	$next_helpcategories = 1;
	if ($stmt = $getPage_connection2->prepare("SELECT id FROM helpcategories ORDER BY id ASC LIMIT 1")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->fetch();
		$next_helpcategories = $r_result;
		$stmt->close();
	} else {
		$next_helpcategories = 0;
	} // else
	while ($next_helpcategories > 0) {
		$helpCategoriesInfo1 = getHelpCategoriesInfo($getPage_connection2,$next_helpcategories);

		echo "          <div class=\"panel panel-info\">\n";
		echo "            <div class=\"panel-heading\">\n";
		echo "              <h3 class=\"panel-title\">".$helpCategoriesInfo1["title"]."        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseHelp1\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
		echo "            </div>\n";
		echo "            <div id=\"collapseHelp".$next_helpcategories."\" class=\"panel-body collapse in\">\n";
		echo "              <div class=\"col-md-8 col-center\">\n";
		echo "                <br />\n";
		echo "                <p class=\"paragraph\">\n";
		echo "                  ".$helpCategoriesInfo1["text"]." \n";
		echo "                </p>\n";

		$next_helpsubcategories = 1;
		$subcategoryCounter = 0;
		if ($stmt = $getPage_connection2->prepare("SELECT id FROM helpsubcategories ORDER BY id ASC LIMIT 1")) {
			$stmt->execute();
			$stmt->bind_result($r_result);
			$stmt->fetch();
			$next_helpsubcategories = $r_result;
			$stmt->close();
		} else {
			$next_helpsubcategories = 0;
		} // else
		while ($next_helpsubcategories > 0) {
			$helpSubcategoriesInfo1 = getHelpSubcategoriesInfo($getPage_connection2,$next_helpsubcategories);

			if ($helpSubcategoriesInfo1["category"] == $helpCategoriesInfo1["id"]) {
				$subcategoryCounter++;
				echo "                <a class=\"chapter-title\" id=\"".$next_helpcategories."-".$subcategoryCounter."\" href=\"#\">".$helpSubcategoriesInfo1["title"]."</a>\n";
				echo "                <br />\n";
				echo "                <p class=\"paragraph\">\n";
				echo "                  ".$helpSubcategoriesInfo1["text"]." \n";
				echo "                </p>\n";
			} // if

			if ($stmt = $getPage_connection2->prepare("SELECT id FROM helpsubcategories WHERE id = (SELECT MIN(id) FROM helpsubcategories WHERE id > ?) ORDER BY id LIMIT 1")) {
				$stmt->bind_param("i", $next_helpsubcategories);
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->fetch();
				$next_helpsubcategories = $r_result;
				$stmt->close();
			} else {
				$next_helpsubcategories = 0;
			} // else
		} // while

		echo "              </div>\n";
		echo "            </div>\n";
		echo "          </div>\n";


		if ($stmt = $getPage_connection2->prepare("SELECT id FROM helpcategories WHERE id = (SELECT MIN(id) FROM helpcategories WHERE id > ?) ORDER BY id LIMIT 1")) {
			$stmt->bind_param("i", $next_helpcategories);
			$stmt->execute();
			$stmt->bind_result($r_result);
			$stmt->fetch();
			$next_helpcategories = $r_result;
			$stmt->close();
		} else {
			$next_helpcategories = 0;
		} // else
	} // while

	echo "        </div>\n";
} // showHelpInfo

/*-----------------------------------------------*/
/********************************
 Help Action Functions
 ********************************/
/*-----------------------------------------------*/
?>