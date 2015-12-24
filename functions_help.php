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

	if ($stmt = $getPage_connection2->prepare("SELECT id,title,text FROM helpcategories ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_id,$r_title,$r_text);
		
		while ($stmt->fetch()) {
			$helpCategoriesInfo1 = array("id"=>$r_id,"title"=>$r_title,"text"=>$r_text);
			
			echo "                <a class=\"cat\" href=\"#collapseHelp".$helpCategoriesInfo1["id"]."\">".$helpCategoriesInfo1["id"]." - ".$helpCategoriesInfo1["title"]."</a>\n";
			echo "                <br />\n";
			
			$subcategoryCounter = 0;
			if ($stmt2 = $getPage_connection2->prepare("SELECT id,category,title,text FROM helpsubcategories ORDER BY id ASC")) {
				$stmt2->execute();
				$stmt2->bind_result($r_id1,$r_category1,$r_title1,$r_text1);
				
				while ($stmt2->fetch()) {
					$helpSubcategoriesInfo1 = array("id"=>$r_id1,"category"=>$r_category1,"title"=>$r_title1,"text"=>$r_text1);
						
					if ($helpSubcategoriesInfo1["category"] == $helpCategoriesInfo1["id"]) {
						$subcategoryCounter++;
						echo "                <a class=\"subcat\" href=\"#".$helpCategoriesInfo1["id"]."-".$subcategoryCounter."\">".$helpCategoriesInfo1["id"].".".$subcategoryCounter." - ".$helpSubcategoriesInfo1["title"]."</a>\n";
						echo "                <br />\n";
					} // if
				} // while
				
				$stmt2->close();
			} else {
			} // else	
						
			echo "                <br />\n";			
		} // while		
		
		$stmt->close();
	} else {
	} // else

	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";

	if ($stmt = $getPage_connection2->prepare("SELECT id,title,text FROM helpcategories ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_id,$r_title,$r_text);
		
		while ($stmt->fetch()) {
			$helpCategoriesInfo1 = array("id"=>$r_id,"title"=>$r_title,"text"=>$r_text);	
			echo "          <div class=\"panel panel-info\">\n";
			echo "            <div class=\"panel-heading\">\n";
			echo "              <h3 class=\"panel-title\">".$helpCategoriesInfo1["title"]."        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseHelp1\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
			echo "            </div>\n";
			echo "            <div id=\"collapseHelp".$helpCategoriesInfo1["id"]."\" class=\"panel-body collapse in\">\n";
			echo "              <div class=\"col-md-8 col-center\">\n";
			echo "                <br />\n";
			echo "                <p class=\"paragraph\">\n";
			echo "                  ".$helpCategoriesInfo1["text"]." \n";
			echo "                </p>\n";
			
			$subcategoryCounter = 0;
			if ($stmt2 = $getPage_connection2->prepare("SELECT id,category,title,text FROM helpsubcategories ORDER BY id ASC")) {
				$stmt2->execute();
				$stmt2->bind_result($r_id1,$r_category1,$r_title1,$r_text1);
				
				while ($stmt2->fetch()) {
					$helpSubcategoriesInfo1 = array("id"=>$r_id1,"category"=>$r_category1,"title"=>$r_title1,"text"=>$r_text1);
					
					if ($helpSubcategoriesInfo1["category"] == $helpCategoriesInfo1["id"]) {
						$subcategoryCounter++;
						echo "                <a class=\"chapter-title\" id=\"".$helpCategoriesInfo1["id"]."-".$subcategoryCounter."\" href=\"#\">".$helpSubcategoriesInfo1["title"]."</a>\n";
						echo "                <br />\n";
						echo "                <p class=\"paragraph\">\n";
						echo "                  ".$helpSubcategoriesInfo1["text"]." \n";
						echo "                </p>\n";
					} // if
				} // while
				
				$stmt2->close();
			} else {
			} // else
				
			echo "              </div>\n";
			echo "            </div>\n";
			echo "          </div>\n";
			
		} // while
		
		$stmt->close();
	} else {
	} // else

	echo "        </div>\n";
} // showHelpInfo

/*-----------------------------------------------*/
/********************************
 Help Action Functions
 ********************************/
/*-----------------------------------------------*/
?>