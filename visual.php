<?php
/****************************************************************************
 * Name:        visual.php
 * Author:      Ben Barnes
 * Date:        2015-12-29
 * Purpose:     Visualization functions page
 *****************************************************************************/

/********************************
 showTitle
 html title
 ********************************/
function showTitle($title) {
	require "section1.txt";
	echo "\n    <title>Worlds: The Game - ".$title."</title>\n  </head>\n";
	require "section2.txt";
} // showTitle

/********************************
 compileMenu
 setup menu nav bar
 ********************************/
function compileMenu($getPage_connection2,$pageType) {
	echo "    <div id=\"real_content\">\n";
	echo "      <!-- Fixed navbar -->\n";
	echo "      <nav class=\"navbar navbar-default navbar-fixed-top\">\n";
	echo "        <div class=\"container\">\n";
	echo "          <div class=\"navbar-header\">\n";
	echo "            <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#navbar\" aria-expanded=\"false\" aria-controls=\"navbar\">\n";
	echo "              <span class=\"sr-only\">Toggle navigation</span>\n";
	echo "              <span class=\"icon-bar\"></span>\n";
	echo "              <span class=\"icon-bar\"></span>\n";
	echo "              <span class=\"icon-bar\"></span>\n";
	echo "            </button>\n";
	echo "          </div>\n";
	echo "          <div id=\"navbar\" class=\"navbar-collapse collapse\">\n";
	echo "            <ul class=\"nav navbar-nav\">\n";
	// default login bar
	if (isset($_SESSION["login"])) {
		if ($_SESSION["login"] == 1) {
			if ($pageType == "profile" || $pageType == "settings" || $pageType == "logout") {
				echo "              <li class=\"dropdown active\">\n";
				echo "                <a href=\"#\" class=\"dropdown-toggle menu_text\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\"><span class=\"glyphicon glyphicon-user menu_glyph\"></span><br />User <span class=\"caret\"></span></a>\n";
				echo "                <ul class=\"dropdown-menu\" role=\"menu\">\n";
				if ($pageType == "settings") {
					echo "                  <li class=\"active\"><a class=\"menu_text\" href=\"index.php?page=settings\"><span class=\"glyphicon glyphicon-wrench menu_glyph\"></span>  Settings</a></li>\n";
				} else {
					echo "                  <li><a class=\"menu_text\" href=\"index.php?page=settings\"><span class=\"glyphicon glyphicon-wrench menu_glyph\"></span>  Settings</a></li>\n";
				} // else
				if ($pageType == "logout") {
					echo "                  <li class=\"active\"><a class=\"menu_text\" href=\"index.php?page=home&amp;action=logout\"><span class=\"glyphicon glyphicon-log-out menu_glyph\"></span>  Logout</a></li>\n";
				} else {
					echo "                  <li><a class=\"menu_text\" href=\"index.php?page=home&amp;action=logout\"><span class=\"glyphicon glyphicon-log-out menu_glyph\"></span>  Logout</a></li>\n";
				} // else
				echo "                </ul>\n";
				echo "              </li>\n";
			} else {
				echo "              <li class=\"dropdown\">\n";
				echo "                <a href=\"#\" class=\"dropdown-toggle menu_text\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\"><span class=\"glyphicon glyphicon-user menu_glyph\"></span><br />User <span class=\"caret\"></span></a>\n";
				echo "                <ul class=\"dropdown-menu\" role=\"menu\">\n";
				echo "                  <li><a class=\"menu_text\" href=\"index.php?page=settings\"><span class=\"glyphicon glyphicon-wrench menu_glyph\"></span>  Settings</a></li>\n";
				echo "                  <li><a class=\"menu_text\" href=\"index.php?page=home&amp;action=logout\"><span class=\"glyphicon glyphicon-log-out menu_glyph\"></span>  Logout</a></li>\n";
				echo "                </ul>\n";
				echo "              </li>\n";
			} // else
			if ($pageType == "map") {
				echo "              <li class=\"dropdown active\">\n";
				echo "                <a href=\"#\" class=\"dropdown-toggle menu_text\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\"><span class=\"glyphicon glyphicon-th menu_glyph\"></span><br />Map <span class=\"caret\"></span></a>\n";
				echo "                <ul class=\"dropdown-menu\" role=\"menu\">\n";
				echo "                  <li>\n";
				echo "                    <div class=\"well well-sm\">\n";
				echo "                      <form action=\"index.php?page=map\" method=\"post\">\n";
				echo "                        <div class=\"form-group form-group-sm\">\n";
				if (isset($_SESSION["overlay"])) {
					echo "                          <input type=\"hidden\" name=\"page\" value=\"map\"><input type=\"hidden\" name=\"overlay\" value=\"".$_SESSION["overlay"]."\" />\n";
				} else {
					echo "                          <input type=\"hidden\" name=\"page\" value=\"map\"><input type=\"hidden\" name=\"overlay\" value=\"terrain\" />\n";
				} // else
				if (isset($_SESSION["continent_id"])) {
					echo "                          <label for=\"menu_continentInput\">Continent</label>\n                          <input name=\"continent\" type=\"text\" class=\"form-control\" id=\"menu_continentInput\" placeholder=\"e.g. 1\" value=\"".$_SESSION["continent_id"]."\"/>\n";
				} else {
					echo "                          <label for=\"menu_continentInput\">Continent</label>\n                          <input name=\"continent\" type=\"text\" class=\"form-control\" id=\"menu_continentInput\" placeholder=\"e.g. 1\" />\n";
				} // else
				echo "                        </div>\n";
				echo "                        <div class=\"form-group form-group-sm\">\n";
				if (isset($_SESSION["xpos"])) {
					echo "                          <label for=\"menu_xPosInput\">X Position</label>\n                          <input name=\"xpos\" type=\"text\" class=\"form-control\" id=\"menu_xPosInput\" placeholder=\"e.g. 1\" value=\"".$_SESSION["xpos"]."\" />\n";
				} else {
					echo "                          <label for=\"menu_xPosInput\">X Position</label>\n                          <input name=\"xpos\" type=\"text\" class=\"form-control\" id=\"menu_xPosInput\" placeholder=\"e.g. 1\" />\n";
				} // else
				echo "                        </div>\n";
				echo "                        <div class=\"form-group form-group-sm\">\n";
				if (isset($_SESSION["ypos"])) {
					echo "                          <label for=\"menu_yPosInput\">Y Position</label>\n                          <input name=\"ypos\" type=\"text\" class=\"form-control\" id=\"menu_yPosInput\" placeholder=\"e.g. 1\" value=\"".$_SESSION["ypos"]."\" />\n";
				} else {
					echo "                          <label for=\"menu_yPosInput\">Y Position</label>\n                          <input name=\"ypos\" type=\"text\" class=\"form-control\" id=\"menu_yPosInput\" placeholder=\"e.g. 1\" />\n";
				} // else
				echo "                        </div>\n";
				echo "                        <div class=\"form-group form-group-sm\">\n";
				if (isset($_SESSION["overlay"])) {
					echo "                          <label for=\"menu_overlayInput\">Overlay</label>\n \n";
					showOverlayOptions($getPage_connection2);
				} else {
					echo "                          <label for=\"menu_overlayInput\">Overlay</label>\n \n";
					showOverlayOptions($getPage_connection2);
				} // else
				echo "                        </div>\n";
				echo "                        <div class=\"form-group form-group-sm\">\n";
				echo "                          <button onclick=\"loadButton(this)\" id=\"menu_submit\" type=\"submit\" class=\"btn btn-lg btn-primary\">Go</button>\n";
				echo "                        </div>\n";
				echo "                      </form>\n";
				echo "                    </div>\n";
				echo "                  </li>\n";
				echo "                </ul>\n";
				echo "              </li>\n";
			} else {
				echo "              <li class=\"dropdown\">\n";
				echo "                <a href=\"#\" class=\"dropdown-toggle menu_text\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\"><span class=\"glyphicon glyphicon-th menu_glyph\"></span><br />Map <span class=\"caret\"></span></a>\n";
				echo "                <ul class=\"dropdown-menu\" role=\"menu\">\n";
				echo "                  <li>\n";
				echo "                    <div class=\"well well-sm\">\n";
				echo "                      <form action=\"index.php?page=map\" method=\"post\">\n";
				echo "                        <div class=\"form-group form-group-sm\">\n";
				if (isset($_SESSION["overlay"])) {
					echo "                          <input type=\"hidden\" name=\"page\" value=\"map\"><input type=\"hidden\" name=\"overlay\" value=\"".$_SESSION["overlay"]."\" />\n";
				} else {
					echo "                          <input type=\"hidden\" name=\"page\" value=\"map\"><input type=\"hidden\" name=\"overlay\" value=\"terrain\" />\n";
				} // else
				if (isset($_SESSION["continent_id"])) {
					echo "                          <label for=\"menu_continentInput\">Continent</label>\n                          <input name=\"continent\" type=\"text\" class=\"form-control\" id=\"menu_continentInput\" placeholder=\"e.g. 1\" value=\"".$_SESSION["continent_id"]."\"/>\n";
				} else {
					echo "                          <label for=\"menu_continentInput\">Continent</label>\n                          <input name=\"continent\" type=\"text\" class=\"form-control\" id=\"menu_continentInput\" placeholder=\"e.g. 1\" />\n";
				} // else
				echo "                        </div>\n";
				echo "                        <div class=\"form-group form-group-sm\">\n";
				if (isset($_SESSION["xpos"])) {
					echo "                          <label for=\"menu_xPosInput\">X Position</label>\n                          <input name=\"xpos\" type=\"text\" class=\"form-control\" id=\"menu_xPosInput\" placeholder=\"e.g. 1\" value=\"".$_SESSION["xpos"]."\" />\n";
				} else {
					echo "                          <label for=\"menu_xPosInput\">X Position</label>\n                          <input name=\"xpos\" type=\"text\" class=\"form-control\" id=\"menu_xPosInput\" placeholder=\"e.g. 1\" />\n";
				} // else
				echo "                        </div>\n";
				echo "                        <div class=\"form-group form-group-sm\">\n";
				if (isset($_SESSION["ypos"])) {
					echo "                          <label for=\"menu_yPosInput\">Y Position</label>\n                          <input name=\"ypos\" type=\"text\" class=\"form-control\" id=\"menu_yPosInput\" placeholder=\"e.g. 1\" value=\"".$_SESSION["ypos"]."\" />\n";
				} else {
					echo "                          <label for=\"menu_yPosInput\">Y Position</label>\n                          <input name=\"ypos\" type=\"text\" class=\"form-control\" id=\"menu_yPosInput\" placeholder=\"e.g. 1\" />\n";
				} // else
				echo "                        </div>\n";
				echo "                        <div class=\"form-group form-group-sm\">\n";
				if (isset($_SESSION["overlay"])) {
					echo "                          <label for=\"menu_overlayInput\">Overlay</label>\n \n";
					showOverlayOptions($getPage_connection2);
				} else {
					echo "                          <label for=\"menu_overlayInput\">Overlay</label>\n \n";
					showOverlayOptions($getPage_connection2);
				} // else
				echo "                        </div>\n";
				echo "                        <div class=\"form-group form-group-sm\">\n";
				echo "                          <button onclick=\"loadButton(this)\" id=\"menu_submit\" type=\"submit\" class=\"btn btn-lg btn-primary\">Go</button>\n";
				echo "                        </div>\n";
				echo "                      </form>\n";
				echo "                    </div>\n";
				echo "                  </li>\n";
				echo "                </ul>\n";
				echo "              </li>\n";
			} // else				
				
			if ($pageType == "policies") {
				echo "              <li class=\"more_buttons_main active\"><a class=\"menu_text\" href=\"index.php?page=policies\"><span class=\"glyphicon glyphicon-tasks menu_glyph\"></span><br />Policies</a></li>\n";
			} else {
				echo "              <li class=\"more_buttons_main\"><a class=\"menu_text\" href=\"index.php?page=policies\"><span class=\"glyphicon glyphicon-tasks menu_glyph\"></span><br />Policies</a></li>\n";
			} // else
			if ($pageType == "trade") {
				echo "              <li class=\"more_buttons_main active\"><a class=\"menu_text\" href=\"index.php?page=trade\"><span class=\"glyphicon glyphicon-transfer menu_glyph\"></span><br />Trade</a></li>\n";
			} else {
				echo "              <li class=\"more_buttons_main\"><a class=\"menu_text\" href=\"index.php?page=trade\"><span class=\"glyphicon glyphicon-transfer menu_glyph\"></span><br />Trade</a></li>\n";
			} // else
			if ($pageType == "organizations") {
				echo "              <li class=\"more_buttons_main active\"><a class=\"menu_text\" href=\"index.php?page=organizations\"><span class=\"glyphicon glyphicon-globe menu_glyph\"></span><br />Orgs</a></li>\n";
			} else {
				echo "              <li class=\"more_buttons_main\"><a class=\"menu_text\" href=\"index.php?page=organizations\"><span class=\"glyphicon glyphicon-globe menu_glyph\"></span><br />Orgs</a></li>\n";
			} // else
				
			if ($pageType == "forum") {
				echo "              <li class=\"more_buttons_mobileVisible active\"><a class=\"menu_text\" href=\"index.php?page=forum\"><span class=\"glyphicon glyphicon-comment menu_glyph\"></span><br />Forum</a></li>\n";
			} else {
				echo "              <li class=\"more_buttons_mobileVisible\"><a class=\"menu_text\" href=\"index.php?page=forum\"><span class=\"glyphicon glyphicon-comment menu_glyph\"></span><br />Forums</a></li>\n";
			} // else
				
			if ($pageType == "help") {
				echo "              <li class=\"more_buttons_mobileVisible active\"><a class=\"menu_text\" href=\"index.php?page=help\"><span class=\"glyphicon glyphicon-question-sign menu_glyph\"></span><br />Help</a></li>\n";
			} else {
				echo "              <li class=\"more_buttons_mobileVisible\"><a class=\"menu_text\" href=\"index.php?page=help\"><span class=\"glyphicon glyphicon-question-sign menu_glyph\"></span><br />Help</a></li>\n";
			} // else
				
			if ($_SESSION["admin"] == 1) {
				echo "              <li class=\"more_buttons_mobileVisible admin_button\"><a class=\"menu_text\" href=\"index.php?page=admin\"><span class=\"glyphicon glyphicon-dashboard menu_glyph\"></span><br />Admin</a></li>\n";
			} // if 
				
			echo "              <li class=\"more_buttons_mobileHidden dropdown\">\n";
			echo "                <a href=\"#\" class=\"dropdown-toggle menu_text\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\"><span class=\"glyphicon glyphicon-plus menu_glyph\"></span><br /> More <span class=\"caret\"></span></a>\n";
			echo "                <ul class=\"dropdown-menu\" role=\"menu\">\n";
			
			if ($pageType == "policies") {
				echo "                  <li class=\"more_buttons_menu active\"><a class=\"menu_text\" href=\"index.php?page=policies\"><span class=\"glyphicon glyphicon-tasks menu_glyph\"></span>  Policies</a></li>\n";
			} else {
				echo "                  <li class=\"more_buttons_menu\"><a class=\"menu_text\" href=\"index.php?page=policies\"><span class=\"glyphicon glyphicon-tasks menu_glyph\"></span>  Policies</a></li>\n";
			} // else
			if ($pageType == "trade") {
				echo "                  <li class=\"more_buttons_menu active\"><a class=\"menu_text\" href=\"index.php?page=trade\"><span class=\"glyphicon glyphicon-transfer menu_glyph\"></span>  Trade</a></li>\n";
			} else {
				echo "                  <li class=\"more_buttons_menu\"><a class=\"menu_text\" href=\"index.php?page=trade\"><span class=\"glyphicon glyphicon-transfer menu_glyph\"></span>  Trade</a></li>\n";
			} // else
			if ($pageType == "organizations") {
				echo "                  <li class=\"more_buttons_menu active\"><a class=\"menu_text\" href=\"index.php?page=organizations\"><span class=\"glyphicon glyphicon-globe menu_glyph\"></span>  Orgs</a></li>\n";
			} else {
				echo "                  <li class=\"more_buttons_menu\"><a class=\"menu_text\" href=\"index.php?page=organizations\"><span class=\"glyphicon glyphicon-globe menu_glyph\"></span>  Orgs</a></li>\n";
			} // else				
			if ($pageType == "forum") {
				echo "                  <li class=\"active\"><a class=\"menu_text\" href=\"index.php?page=forum\"><span class=\"glyphicon glyphicon-comment menu_glyph\"></span>  Forum</a></li>\n";
			} else {
				echo "                  <li><a class=\"menu_text\" href=\"index.php?page=forum\"><span class=\"glyphicon glyphicon-comment menu_glyph\"></span>  Forum</a></li>\n";
			} // else
			if ($pageType == "help") {
				echo "                  <li class=\"active\"><a class=\"menu_text\" href=\"index.php?page=help\"><span class=\"glyphicon glyphicon-question-sign menu_glyph\"></span>  Help</a></li>\n";
			} else {
				echo "                  <li><a class=\"menu_text\" href=\"index.php?page=help\"><span class=\"glyphicon glyphicon-question-sign menu_glyph\"></span>  Help</a></li>\n";
			} // else
				
			// Admin Controls
			if ($_SESSION["admin"] == 1) {
				echo "              <li class=\"admin_button\"><a class=\"menu_text\" target=\"_blank\" href=\"index.php?page=admin\"><span class=\"glyphicon glyphicon-dashboard menu_glyph\"></span>  Admin</a></li>\n";
			} // if
			
			echo "                </ul>\n";
			echo "              </li>\n";
			
			echo "                    <li>\n";
			echo "                      <div class=\"row menu_login\">\n";
			echo "                        <form action=\"index.php?page=search\" method=\"post\">\n";
			echo "                          <div class=\"col-xs-1 col-sm-1 col-md-1\">\n";
			echo "                          </div>\n";
			echo "                          <div class=\"col-xs-8 col-sm-9 col-md-10\">\n";
			echo "                            <div class=\"form-group form-group-lg\">\n";
			echo "                              <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Search for any nation or organization here.\" name=\"search\" id=\"search_terms\" type=\"text\" class=\"form-control\" placeholder=\"Enter Search here...\" />\n";
			echo "                            </div>\n";
			echo "                          </div>\n";
			echo "                          <div class=\"col-xs-1 col-sm-1 col-md-1\">\n";
			echo "                            <div class=\"form-group form-group-lg\">\n";
			echo "                              <button onclick=\"loadButton(this)\" id=\"search_submit\" type=\"submit\" class=\"btn btn-lg btn-success\"><span class=\"glyphicon glyphicon-search\"></span></button>\n";
			echo "                            </div>\n";
			echo "                          </div>\n";
			echo "                        </form>\n";
			echo "                      </div>\n";
			echo "                    </li>\n";
			
		} else {
			echo "                <li>\n";
			echo "                  <form action=\"index.php?page=map\" method=\"post\">\n";
			echo "                    <div class=\"container\">\n";
			echo "                      <div class=\"row\">\n";
			echo "                        <div class=\"col-md-6\">\n";
			echo "                        </div>\n";
			echo "                        <div class=\"col-md-6\">\n";
			echo "                          <div class=\"row\">\n";
			echo "                            <div class=\"col-xs-6 col-sm-3 col-md-4\">\n";
			echo "                              <span class=\"form-group form-group-xs\">\n";
			echo "                                <label for=\"menu_username\">Username</label>\n";
			echo "                                <input name=\"username\" type=\"text\" class=\"form-control\" id=\"menu_username\" placeholder=\"Username\" />\n";
			echo "                              </span>\n";
			echo "                            </div>\n";
			echo "                            <div class=\"col-xs-6 col-sm-3 col-md-4\">\n";
			echo "                              <span class=\"form-group form-group-xs\">\n";
			echo "                                <label for=\"menu_password\">Password</label>\n";
			echo "                                <input name=\"password\" type=\"password\" class=\"form-control\" id=\"menu_password\" placeholder=\"Password\" />\n";
			echo "                              </span>\n";
			echo "                            </div>\n";
			echo "                            <div class=\"col-xs-4 col-sm-1 col-md-2\">\n";
			echo "                              <span class=\"form-group form-group-xs\">\n";
			echo "                                <button onclick=\"loadButton(this)\" id=\"menu_submit\" type=\"submit\" class=\"form-control btn btn-sm btn-success menu_login\"><span class=\"glyphicon glyphicon-log-in\"></span></button>\n";
			echo "                              </span>\n";
			echo "                            </div>\n";
			echo "                            <span class=\"clear\"></span>\n";
			echo "                          </div>\n";
			echo "                        </div>\n";
			echo "                        <span class=\"clear\"></span>\n";
			echo "                      </div>\n";
			echo "                    </div>\n";
			echo "                  </form>\n";
			echo "                </li>\n";
		} // else
	} else {
		echo "                <li>\n";
		echo "                  <form action=\"index.php?page=map\" method=\"post\">\n";
		echo "                    <div class=\"container\">\n";
		echo "                      <div class=\"row\">\n";
		echo "                        <div class=\"col-md-6\">\n";
		echo "                        </div>\n";
		echo "                        <div class=\"col-md-6\">\n";
		echo "                          <div class=\"row\">\n";
		echo "                            <div class=\"col-xs-6 col-sm-3 col-md-4\">\n";
		echo "                              <span class=\"form-group form-group-xs\">\n";
		echo "                                <label for=\"menu_username\">Username</label>\n";
		echo "                                <input name=\"username\" type=\"text\" class=\"form-control\" id=\"menu_username\" placeholder=\"Username\" />\n";
		echo "                              </span>\n";
		echo "                            </div>\n";
		echo "                            <div class=\"col-xs-6 col-sm-3 col-md-4\">\n";
		echo "                              <span class=\"form-group form-group-xs\">\n";
		echo "                                <label for=\"menu_password\">Password</label>\n";
		echo "                                <input name=\"password\" type=\"password\" class=\"form-control\" id=\"menu_password\" placeholder=\"Password\" />\n";
		echo "                              </span>\n";
		echo "                            </div>\n";
		echo "                            <div class=\"col-xs-4 col-sm-1 col-md-2\">\n";
		echo "                              <span class=\"form-group form-group-xs\">\n";
		echo "                                <button onclick=\"loadButton(this)\" id=\"menu_submit\" type=\"submit\" class=\"form-control btn btn-sm btn-success menu_login\"><span class=\"glyphicon glyphicon-log-in\"></span></button>\n";
		echo "                              </span>\n";
		echo "                            </div>\n";
		echo "                            <span class=\"clear\"></span>\n";
		echo "                          </div>\n";
		echo "                        </div>\n";
		echo "                        <span class=\"clear\"></span>\n";
		echo "                      </div>\n";
		echo "                    </div>\n";
		echo "                  </form>\n";
		echo "                </li>\n";
	} // else
	echo "            </ul>\n";
	echo "          </div><!--/.nav-collapse -->\n\n";
	echo "        </div>\n";
	echo "      </nav>\n";
} // compileMenu

/********************************
 showOverlayOptions
 visualize overlay menu
 ********************************/
function showOverlayOptions($getPage_connection2) {
	echo "          <select id=\"menu_overlayInput\" name=\"overlay\" class=\"dropdown1 btn btn-lg btn-default\">\n";

	if ($stmt = $getPage_connection2->prepare("SELECT id,name FROM overlays ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_id,$r_name);
		$stmt->store_result();
		
		while ($stmt->fetch()) {		
			$overlayInfo1 = array("id"=>$r_id,"name"=>$r_name);	
			
			if (isset($_SESSION["overlay"])) {
				if ($_SESSION["overlay"] == strtolower($overlayInfo1["name"])) {
					echo "            <option class=\"option1\" selected=\"selected\" value=\"".strtolower($overlayInfo1["name"])."\">".$overlayInfo1["name"]."</option>\n";
				} else {
					echo "            <option class=\"option1\" value=\"".strtolower($overlayInfo1["name"])."\">".$overlayInfo1["name"]."</option>\n";
				} // else
			} else {
				echo "            <option class=\"option1\" value=\"".strtolower($overlayInfo1["name"])."\">".$overlayInfo1["name"]."</option>\n";
			} // else
		} // while

		$stmt->close();
	} else {
	} // else
			
	echo "          </select>\n";
} // showOverlayOptions

/********************************
 showWarning
 visualize warning
 ********************************/
function showWarning($getPage_connection2) {
	require "section3.txt";
	$warning = "";
	$success = "";
	
	if (isset($_SESSION["warning_message"])) {
		if (strlen($_SESSION["warning_message"]) >= 1) {
			//unset($_POST);
			$warning = cleanString($_SESSION["warning_message"],false);
			$_SESSION["warning_message"] = "";
			unset($_SESSION["warning_message"]);
		} else {
			$warning = "";
		} // else
	} else {
		$warning = "";
	} // else
		
	if (isset($_SESSION["success_message"])) {
		if (strlen($_SESSION["success_message"]) >= 1) {
			//unset($_POST);
			$success = cleanString($_SESSION["success_message"],false);
			$_SESSION["success_message"] = "";
			unset($_SESSION["success_message"]);
		} else {
			$success = "";
		} // else
	} else {
		$success = "";
	} // else
		
	if (strlen($warning) >= 1) {
		echo "        <div class=\"alert alert-danger alert-spacer\" role=\"alert\">\n";
		echo "          <strong>Something went wrong with your request...</strong> <br /> ".$warning."\n";
		echo "        </div>\n";
	} else if (strlen($success) >= 1) {
		echo "        <div class=\"alert alert-success alert-spacer\" role=\"alert\">\n";
		echo "          <strong>Your request has been processed...</strong> <br /> ".$success."\n";
		echo "        </div>\n";
	} // else if
	
	unset($warning);
	unset($success);
} // showWarning

/********************************
 showPageTitle
 visualize page title
 ********************************/
function showPageTitle($getPage_connection2,$pageTitle,$showNation) {
	if ($showNation === true) {
		$nationInfo = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
		echo "        <div class=\"page-header spacing-from-menu\">\n";
		echo "          <h1>".$pageTitle." - ".$nationInfo["name"]."</h1>\n";
		echo "        </div>\n\n";		
	} else {
		echo "        <div class=\"page-header spacing-from-menu\">\n";
		echo "          <h1>".$pageTitle."</h1>\n";
		echo "        </div>\n\n";		
	}
} // showPageTitle
?>