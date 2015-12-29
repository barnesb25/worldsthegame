<?php
/****************************************************************************
 * Name:        functions_trade.php
 * Author:      Ben Barnes
 * Date:        2015-12-28
 * Purpose:     Trade functions page
 *****************************************************************************/

/********************************
 getGlobals_trade
 get and set global variables for organizations page
 ********************************/
function getGlobals_trade($getPage_connection2) {
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

	if (!(isset($_SESSION["offers"]))) {
		$_SESSION["offers"] = array(0=>0);
	} // if
	if (!(isset($_SESSION["offersItems"]))) {
		$_SESSION["offersItems"] = array(0=>0);
	} // if
	if (!(isset($_SESSION["offersTypes"]))) {
		$_SESSION["offersTypes"] = array(0=>0);
	} // if
	if (!(isset($_SESSION["offersQuantities"]))) {
		$_SESSION["offersQuantities"] = array(0=>0);
	} // if
	if (!(isset($_SESSION["demands"]))) {
		$_SESSION["demands"] = array(0=>0);
	} // if
	if (!(isset($_SESSION["demandsItems"]))) {
		$_SESSION["demandsItems"] = array(0=>0);
	} // if
	if (!(isset($_SESSION["demandsTypes"]))) {
		$_SESSION["demandsTypes"] = array(0=>0);
	} // if
	if (!(isset($_SESSION["demandsQuantities"]))) {
		$_SESSION["demandsQuantities"] = array(0=>0);
	} // if
		
	if (count($_POST)) {
		// post: current action
		if (isset($_POST["action"])) {
			$_SESSION["action"] = cleanString($_POST["action"],true);
		} else {
			$_SESSION["action"] = "";
		} // else
		
		// post: current ID for action
		if (isset($_POST["actionid"])) {
			$_SESSION["action_id"] = cleanString($_POST["actionid"],true);
		} else {
			$_SESSION["action_id"] = 0;
		} // else
		
		// post: nation sending
		if (isset($_POST["send_nation"])) {
			$_SESSION["send_nation"] = cleanString($_POST["send_nation"],false);
		} else {
			$_SESSION["send_nation"] = "";
		} // else
		
		// post: turns for settlement
		if (isset($_POST["turns"])) {
			$_SESSION["turns"] = cleanString($_POST["turns"],true);
		} else {
			$_SESSION["turns"] = 0;
		} // else
		
		// post: agreement policy
		if (isset($_POST["agreement"])) {
			$_SESSION["agreement"] = cleanString($_POST["agreement"],true);
		} else {
			$_SESSION["agreement"] = 0;
		} // else

		if (!(isset($_SESSION["offers"]))) {
			$_SESSION["offers"] = array(0=>0);
		} // if
		if (!(isset($_SESSION["offersItems"]))) {
			$_SESSION["offersItems"] = array(0=>0);
		} // if
		if (!(isset($_SESSION["offersTypes"]))) {
			$_SESSION["offersTypes"] = array(0=>0);
		} // if
		if (!(isset($_SESSION["offersQuantities"]))) {
			$_SESSION["offersQuantities"] = array(0=>0);
		} // if
		if (!(isset($_SESSION["demands"]))) {
			$_SESSION["demands"] = array(0=>0);
		} // if
		if (!(isset($_SESSION["demandsItems"]))) {
			$_SESSION["demandsItems"] = array(0=>0);
		} // if
		if (!(isset($_SESSION["demandsTypes"]))) {
			$_SESSION["demandsTypes"] = array(0=>0);
		} // if
		if (!(isset($_SESSION["demandsQuantities"]))) {
			$_SESSION["demandsQuantities"] = array(0=>0);
		} // if		
		
		// post: offers list
		if (isset($_POST["offers"][0])) {
			if ($_POST["offersQuantities"][0] > 0) {
				for ($gg=0; $gg < count($_POST["offers"]); $gg++) {
					if (isset($_POST["offersQuantities"][$gg])) {
						if (isset($_POST["offersItems"][$gg])) {
							$tempQuantities = cleanString($_POST["offersQuantities"][$gg],true);
							$tempItems = strtolower(cleanString($_POST["offersItems"][$gg],false));
							if ($tempQuantities > 0 && strlen($tempItems) > 0) {
								$_SESSION["offers"][$gg] = 1;
								$_SESSION["offersQuantities"][$gg] = cleanString($_POST["offersQuantities"][$gg],true);
								if (stristr($tempItems,"resources.") === false) {
								} else {
									$_SESSION["offersTypes"][$gg] = "resources";
									$item = getResourceTypeInfoByName($getPage_connection2,substr($tempItems,10));
									$_SESSION["offersItems"][$gg] = $item["id"];
								} // else
								if (stristr($tempItems,"goods.") === false) {
								} else {
									$_SESSION["offersTypes"][$gg] = "goods";
									$item = getGoodsInfoByName($getPage_connection2,substr($tempItems,6));
									$_SESSION["offersItems"][$gg] = $item["id"];
								} // else
								if (stristr($tempItems,"money.") === false) {
								} else {
									$_SESSION["offersTypes"][$gg] = "money";
									$_SESSION["offersItems"][$gg] = 1;
								} // else
								if (stristr($tempItems,"food.") === false) {
								} else {
									$_SESSION["offersTypes"][$gg] = "food";
									$_SESSION["offersItems"][$gg] = 1;
								} // else
							} // if
						} // if
					} // if
				} // for
			} else {

			} // else
		} else {

		} // else
		
		// post: demands list
		if (isset($_POST["demands"][0])) {
			if ($_POST["demandsQuantities"][0] > 0) {
				for ($gg=0; $gg < count($_POST["demands"]); $gg++) {
					if (isset($_POST["demandsQuantities"][$gg])) {
						if (isset($_POST["demandsItems"][$gg])) {
							$tempQuantities = cleanString($_POST["demandsQuantities"][$gg],true);
							$tempItems = strtolower(cleanString($_POST["demandsItems"][$gg],false));
							if ($tempQuantities > 0 && strlen($tempItems) > 0) {
								$_SESSION["demands"][$gg] = 1;
								$_SESSION["demandsQuantities"][$gg] = cleanString($_POST["demandsQuantities"][$gg],true);
								if (stristr($tempItems,"resources.") === false) {
								} else {
									$_SESSION["demandsTypes"][$gg] = "resources";
									$item = getResourceTypeInfoByName($getPage_connection2,substr($tempItems,10));
									$_SESSION["demandsItems"][$gg] = $item["id"];
								} // else
								if (stristr($tempItems,"goods.") === false) {
								} else {
									$_SESSION["demandsTypes"][$gg] = "goods";
									$item = getGoodsInfoByName($getPage_connection2,substr($tempItems,6));
									$_SESSION["demandsItems"][$gg] = $item["id"];
								} // else
								if (stristr($tempItems,"money.") === false) {
								} else {
									$_SESSION["demandsTypes"][$gg] = "money";
									$_SESSION["demandsItems"][$gg] = 1;
								} // else
								if (stristr($tempItems,"food.") === false) {
								} else {
									$_SESSION["demandsTypes"][$gg] = "food";
									$_SESSION["demandsItems"][$gg] = 1;
								} // else
							} // if
						} // if
					} // if
				} // for
			} else {

			} // else
		} else {

		} // else
			
	} else if (count($_GET)) {			
	} // else if
	
	// get info
	$_SESSION["userInfo"] = getUserInfo($getPage_connection2,$_SESSION["user_id"]);
	$_SESSION["nationInfo"] = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
} // getGlobals_trade

/********************************
 performAction_trade
 calls action for trade if requested and valid
 ********************************/
function performAction_trade($getPage_connection2) {
	if ($_SESSION["action"] == "accept_offer") {
		acceptOffer($getPage_connection2);
	} else if ($_SESSION["action"] == "decline_offer") {
		declineOffer($getPage_connection2);
	} else if ($_SESSION["action"] == "accept_agreement") {
		acceptAgreement($getPage_connection2);
	} else if ($_SESSION["action"] == "decline_agreement") {
		declineAgreement($getPage_connection2);		
	} else if ($_SESSION["action"] == "reset_offer") {
		resetOffer($getPage_connection2);
	} else if ($_SESSION["action"] == "send_offer") {
		sendOffer($getPage_connection2);
	} else if ($_SESSION["action"] == "reset_agreement") {
		resetAgreement($getPage_connection2);
	} else if ($_SESSION["action"] == "send_agreement") {
		sendAgreement($getPage_connection2);
	} // else if
} // performAction_trade

/********************************
 showTradeInfo
 visualize trade information and input
 ********************************/
function showTradeInfo($getPage_connection2) {
	$nationInfo = getNationInfo($getPage_connection2,$_SESSION["nation_id"]);
	$tradeInfo = getTradeInfo($getPage_connection2,$_SESSION["nation_id"]);
	$authorityReport = getAuthorityReport($nationInfo["authority"]);
	$economyReport = getEconomyReport($nationInfo["economy"]);

	echo "        <div class=\"spacing-from-menu well well-lg standard-text\">\n";
	
	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View and reply to pending trade agreements.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Pending Agreements        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapsePendingAgreements\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapsePendingAgreements\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	
	$agreementsExist = false;
	if ($stmt = $getPage_connection2->prepare("SELECT id FROM agreements ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->store_result();

		while ($stmt->fetch()) {
			$next_agreements = $r_result;
			$agreementInfo1 = getAgreementInfo($getPage_connection2,$next_agreements);
		
			if (($agreementInfo1["toNation"] == $_SESSION["nation_id"] || $agreementInfo1["fromNation"] == $_SESSION["nation_id"]) && $agreementInfo1["status"] == 0) {
				$agreementsExist = true;
				$toNationInfo = getNationInfo($getPage_connection2,$agreementInfo1["toNation"]);
				$fromNationInfo = getNationInfo($getPage_connection2,$agreementInfo1["fromNation"]);
				echo "                To: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$toNationInfo["id"]."\">".$toNationInfo["name"]."</a>, From: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$fromNationInfo["id"]."\">".$fromNationInfo["name"]."</a>\n                <br />\n";
				echo "                Agreement Policy: ".$agreementInfo1["policy"]."\n                <br />\n";
				echo "                ".$agreementInfo1["turns"]." turns \n";
				echo "                <br />\n";
				echo "                <form action=\"index.php?page=trade#collapsePendingAgreements\" method=\"post\">\n";
				echo "                  <div class=\"form-group form-group-xs\">\n";
				echo "                    <input type=\"hidden\" name=\"page\" value=\"trade\" />\n";
				echo "                    <input type=\"hidden\" name=\"actionid\" value=\"".$agreementInfo1["id"]."\" />\n";
				echo "                    <button data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Accept trade agreement proposal.\" name=\"action\" value=\"accept_agreement\" id=\"accept_agreement-".$agreementInfo1["id"]."\" type=\"submit\" class=\"btn btn-sm btn-success\">Accept</button>\n";
				echo "                    <button data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Decline trade agreement proposal.\" name=\"action\" value=\"decline_agreement\" id=\"decline_agreement-".$agreementInfo1["id"]."\" type=\"submit\" class=\"btn btn-sm btn-danger\">Decline</button>\n";
				echo "                  </div>\n";
				echo "                </form\">\n";
				echo "                ----\n                <br />\n";
			} // if
		} // while
		$stmt->close();
	} else {
	} // else
	
	if ($agreementsExist === false) {
		echo "                 No agreements established.\n";
	} // if
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";
	
	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View and reply to pending offers.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Pending Offers        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapsePendingOffers\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapsePendingOffers\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	
	$offersExist = false;
	if ($stmt = $getPage_connection2->prepare("SELECT id FROM offers ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->store_result();

		while ($stmt->fetch()) {
			$next_offers = $r_result;
			$offerInfo1 = getOfferInfo($getPage_connection2,$next_offers);
		
			if ($offerInfo1["toNation"] == $_SESSION["nation_id"] && $offerInfo1["status"] == 0) {
				if ($offerInfo1["givingItems"][0] > 0 || $offerInfo1["receivingItems"][0] > 0) {
					$offersExist = true;
					$toNationInfo = getNationInfo($getPage_connection2,$offerInfo1["toNation"]);
					$fromNationInfo = getNationInfo($getPage_connection2,$offerInfo1["fromNation"]);
					echo "                To: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$toNationInfo["id"]."\">".$toNationInfo["name"]."</a>, From: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$fromNationInfo["id"]."\">".$fromNationInfo["name"]."</a>\n                <br />\n";
					if ($offerInfo1["givingItems"][0] > 0) {
						echo "                Giving:\n                <br />\n";
						for ($z=0; $z < count($offerInfo1["givingItems"]); $z++) {
							if ($offerInfo1["givingQuantities"][$z] > 0) {
								if ($offerInfo1["givingTypes"][$z] == "goods") {
									$itemInfo1 = getGoodsInfo($getPage_connection2,$offerInfo1["givingItems"][$z]);
									$name1 = $itemInfo1["name"];
								} else if ($offerInfo1["givingTypes"][$z] == "resources") {
									$itemInfo1 = getResourceTypeInfo($getPage_connection2,$offerInfo1["givingItems"][$z]);
									$name1 = $itemInfo1["name"];
								} else {
									$name1 = "money";
								} // else
								echo "                ".$offerInfo1["givingQuantities"][$z]." ".$name1."\n                <br />\n";
							} // if
						} // for
					} else {
						echo "                Giving:\n                <br />\n                Nothing.\n";
					} // else
					if ($offerInfo1["receivingItems"][0] > 0) {
						echo "                Receiving:\n                <br />\n";
						for ($z=0; $z < count($offerInfo1["receivingItems"]); $z++) {
							if ($offerInfo1["receivingQuantities"][$z] > 0) {
								if ($offerInfo1["receivingTypes"][$z] == "goods") {
									$itemInfo1 = getGoodsInfo($getPage_connection2,$offerInfo1["receivingItems"][$z]);
									$name1 = $itemInfo1["name"];
								} else if ($offerInfo1["receivingTypes"][$z] == "resources") {
									$itemInfo1 = getResourceTypeInfo($getPage_connection2,$offerInfo1["receivingItems"][$z]);
									$name1 = $itemInfo1["name"];
								} else {
									$name1 = "money";
								} // else
								echo "                ".$offerInfo1["receivingQuantities"][$z]." ".$name1."\n                <br />\n";
							} // if
						} // for
					} else {
						echo "                Receiving:\n                <br />\n                Nothing.\n                <br />\n";
					} // else					
					echo "                ".$offerInfo1["turns"]." turns \n";
					echo "                <br />\n";
					echo "                <form action=\"index.php?page=trade#collapsePendingOffers\" method=\"post\">\n";
					echo "                  <div class=\"form-group form-group-xs\">\n";
					echo "                    <input type=\"hidden\" name=\"page\" value=\"trade\" />\n";
					echo "                    <input type=\"hidden\" name=\"actionid\" value=\"".$offerInfo1["id"]."\" />\n";
					echo "                    <button data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Accept offer.\" name=\"action\" value=\"accept_offer\" id=\"accept_offer-".$offerInfo1["id"]."\" type=\"submit\" class=\"btn btn-sm btn-success\">Accept</button>\n";
					echo "                    <button data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Decline offer.\" name=\"action\" value=\"decline_offer\" id=\"decline_offer-".$offerInfo1["id"]."\" type=\"submit\" class=\"btn btn-sm btn-danger\">Decline</button>\n";
					echo "                  </div>\n";
					echo "                </form\">\n";
					echo "                ----\n                <br />\n";
				} // if
			} // if
		} // while
		$stmt->close();
	} else {
	} // else
	
	if ($offersExist === false) {
		echo "                No offers established.\n";
	} // if
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";
	
	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Create new trade agreement proposal.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Make Trade Agreement        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseMakeAgreement\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseMakeAgreement\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	if ($tradeInfo["limit"] > count($tradeInfo["routes"])) {
		echo "                <form action=\"index.php?page=trade#collapseMakeAgreement\" method=\"post\">\n";
		echo "                  <input type=\"hidden\" name=\"page\" value=\"trade\" />\n";
		echo "                  <div class=\"form-group form-group-sm\">\n";
		echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Specify target nation.\" name=\"send_nation\" type=\"text\" class=\"form-control input-md\" id=\"send_nation\" placeholder=\"Name of Target Nation\" />\n";
		echo "                    <br />\n";
		echo "                    <label class=\"control-label\" for=\"slider-agreement\">Trade Policy:</label>\n";
		echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Specify trade agreement free trade policy arrangement.  A lower number means less state intervention, and a higher number means more.\" name=\"agreement\" id=\"slider-agreement\" type=\"range\" min=\"1\" max=\"10\" step=\"1\" value=\"1\" />\n";
		echo "                    <br />\n";
		echo "                    <label class=\"control-label\" for=\"set_turns_agreement\">Turns:</label>\n";
		echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Specify the duration of the deal.\" type=\"text\" name=\"turns\" id=\"set_turns_agreement\" class=\"form-control input-sm\" value=\"1\" placeholder=\"e.g. 1\" />\n";
		echo "                    <br />\n";
		echo "                    <button data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Send deal to nation for consideration.\" value=\"send_agreement\" name=\"action\" id=\"send_agreement\" type=\"submit\" class=\"btn btn-md btn-success info_button\">Send</button>\n";
		echo "                    <button data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Reset deal's contents to none.\" value=\"reset_agreement\" name=\"action\" id=\"reset_agreement\" type=\"submit\" class=\"btn btn-md btn-danger info_button\">Reset</button>\n";
		echo "                  </div>\n";
		echo "                </form>\n";
	} else {
		echo "              You have no trade routes available.\n";
	} // else
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";
	
	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Create new offer.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Make Offer        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseMakeOffer\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseMakeOffer\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	echo "                <form action=\"index.php?page=trade#collapseMakeOffer\" method=\"post\">\n";
	echo "                  <input type=\"hidden\" name=\"page\" value=\"trade\" />\n";
	echo "                  <div class=\"form-group form-group-sm\">\n";
	echo "                    <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Specify target nation.\" name=\"send_nation\" type=\"text\" class=\"form-control input-md\" id=\"send_nation\" placeholder=\"Name of Target Nation\" />\n";
	
	echo "                    <div class=\"col-md-6\">\n";
	echo "                      <label for=\"add_offer\">Add Offer</label>\n";
	echo "                      <button data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Add another offer (something the player's nation contributes).\" value=\"add_offer\" name=\"action\" id=\"add_offer\" type=\"submit\" class=\"btn btn-md btn-success\">Add</button>\n";
	echo "                      <br />\n";
	
	echo "                      <div id=\"offers\">\n";
	
	for ($ff=0; $ff <= count($_SESSION["offers"]); $ff++) {
		if ($ff < count($_SESSION["offers"])) {
			echo "                        <input type=\"hidden\" name=\"offers[".$ff."]\" value=\"1\" />\n";
			echo "                        Offer <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Specify quantity of item.\" type=\"text\" name=\"offersQuantities[".$ff."]\" class=\"form-control input-sm\" placeholder=\"Quantity\" value=\"".$_SESSION["offersQuantities"][$ff]."\" />\n";
			echo "                        <select data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Specify item.\" name=\"offersItems[".$ff."]\" class=\"form-control input-sm\">\n";
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM resources ORDER BY id ASC")) {
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->store_result();

				while ($stmt->fetch()) {
					$next_resources = $r_result;
					$resourceTypeInfo1 = getResourceTypeInfo($getPage_connection2,$next_resources);
					if ($_SESSION["offersItems"][$ff] == $resourceTypeInfo1["id"] && $_SESSION["offersTypes"][$ff] == "resources") {
						echo "                          <option selected value=\"resources.".strtolower($resourceTypeInfo1["name"])."\">".$resourceTypeInfo1["name"]."</option>\n";
					} else {
						echo "                          <option value=\"resources.".strtolower($resourceTypeInfo1["name"])."\">".$resourceTypeInfo1["name"]."</option>\n";
					} // else
				} // while
				$stmt->close();
			} else {
			} // else
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM goods ORDER BY id ASC")) {
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->store_result();

				while ($stmt->fetch()) {
					$next_goods = $r_result;
					$goodsInfo1 = getGoodsInfo($getPage_connection2,$next_goods);
					if ($_SESSION["offersItems"][$ff] == $goodsInfo1["id"] && $_SESSION["offersTypes"][$ff] == "goods") {
						echo "                          <option selected value=\"goods.".strtolower($goodsInfo1["name"])."\">".$goodsInfo1["name"]."</option>\n";
					} else {
						echo "                          <option value=\"goods.".strtolower($goodsInfo1["name"])."\">".$goodsInfo1["name"]."</option>\n";
					} // else
				} // while
				$stmt->close();
			} else {
			} // else
			if ($_SESSION["offersTypes"][$ff] == "money") {
				echo "                          <option selected value=\"money.money\">Money</option>\n";
			} else {
				echo "                          <option value=\"money.money\">Money</option>\n";
			} // else
			if ($_SESSION["offersTypes"][$ff] == "food") {
				echo "                          <option selected value=\"food.food\">Food</option>\n";
			} else {
				echo "                          <option value=\"food.food\">Food</option>\n";
			} // else
			echo "                        </select>\n";
			echo "                        <br />\n";
		} else {
			echo "                        <input type=\"hidden\" name=\"offers[".$ff."]\" value=\"1\" />\n";
			echo "                        Offer <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Specify quantity of item.\" type=\"text\" name=\"offersQuantities[".$ff."]\" class=\"form-control input-sm\" placeholder=\"Quantity\" value=\"0\" />\n";
			echo "                        <select data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Specify item.\" name=\"offersItems[".$ff."]\" class=\"form-control input-sm\">\n";
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM resources ORDER BY id ASC")) {
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->store_result();
				while ($stmt->fetch()) {
					$next_resources = $r_result;
					$resourceTypeInfo1 = getResourceTypeInfo($getPage_connection2,$next_resources);
					echo "                          <option value=\"resources.".strtolower($resourceTypeInfo1["name"])."\">".$resourceTypeInfo1["name"]."</option>\n";
				} // while
				$stmt->close();
			} else {
			} // else
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM goods ORDER BY id ASC")) {
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->store_result();
				
				while ($stmt->fetch()) {
					$next_goods = $r_result;
					$goodsInfo1 = getGoodsInfo($getPage_connection2,$next_goods);
					echo "                          <option value=\"goods.".strtolower($goodsInfo1["name"])."\">".$goodsInfo1["name"]."</option>\n";
				} // while
				$stmt->close();
			} else {
			} // else
	
			echo "                          <option value=\"money.money\">Money</option>\n";
			echo "                          <option value=\"food.food\">Food</option>\n";
	
			echo "                        </select>\n";
			echo "                        <br />\n";
		} // else
	} // for
	echo "                      </div>\n";
	echo "                    </div>\n";
	
	echo "                    <div class=\"col-md-6\">\n";
	echo "                      <label for=\"add_demand\">Add Demand</label>\n";
	echo "                      <button data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Add another demand (something the target nation contributes).\" value=\"add_demand\" name=\"action\" id=\"add_demand\" type=\"submit\" class=\"btn btn-md btn-success\">Add</button>\n";
	echo "                      <br />\n";
	
	echo "                      <div id=\"demands\">\n";
	
	for ($ff=0; $ff <= count($_SESSION["demands"]); $ff++) {
		if ($ff < count($_SESSION["demands"])) {
			echo "                        <input type=\"hidden\" name=\"demands[".$ff."]\" value=\"1\" />\n";
			echo "                        Demand <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Specify quantity of item.\" type=\"text\" name=\"demandsQuantities[".$ff."]\" class=\"form-control input-sm\" placeholder=\"Quantity\" value=\"".$_SESSION["demandsQuantities"][$ff]."\" />\n";
			echo "                        <select data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Specify item.\" name=\"demandsItems[".$ff."]\" class=\"form-control input-sm\">\n";
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM resources ORDER BY id ASC")) {
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->store_result();
				
				while ($stmt->fetch()) {
					$next_resources = $r_result;
					$resourceTypeInfo1 = getResourceTypeInfo($getPage_connection2,$next_resources);
					if ($_SESSION["demandsItems"][$ff] == $resourceTypeInfo1["id"] && $_SESSION["demandsTypes"][$ff] == "resources") {
						echo "                          <option selected value=\"resources.".strtolower($resourceTypeInfo1["name"])."\">".$resourceTypeInfo1["name"]."</option>\n";
					} else {
						echo "                          <option value=\"resources.".strtolower($resourceTypeInfo1["name"])."\">".$resourceTypeInfo1["name"]."</option>\n";
					} // else
				} // while
				$stmt->close();
			} else {
			} // else
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM goods ORDER BY id ASC")) {
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->store_result();
				
				while ($stmt->fetch()) {
					$next_goods = $r_result;
					$goodsInfo1 = getGoodsInfo($getPage_connection2,$next_goods);
					if ($_SESSION["demandsItems"][$ff] == $goodsInfo1["id"] && $_SESSION["demandsTypes"][$ff] == "goods") {
						echo "                          <option selected value=\"goods.".strtolower($goodsInfo1["name"])."\">".$goodsInfo1["name"]."</option>\n";
					} else {
						echo "                          <option value=\"goods.".strtolower($goodsInfo1["name"])."\">".$goodsInfo1["name"]."</option>\n";
					} // else
				} // while
				$stmt->close();
			} else {
			} // else
			if ($_SESSION["demandsTypes"][$ff] == "money") {
				echo "                          <option selected value=\"money.money\">Money</option>\n";
			} else {
				echo "                          <option value=\"money.money\">Money</option>\n";
			} // else
			if ($_SESSION["demandsTypes"][$ff] == "food") {
				echo "                          <option selected value=\"food.food\">Food</option>\n";
			} else {
				echo "                          <option value=\"food.food\">Food</option>\n";
			} // else
			echo "                        </select>\n";
			echo "                        <br />\n";
		} else {
			echo "                        <input type=\"hidden\" name=\"demands[".$ff."]\" value=\"1\" />\n";
			echo "                        Demand <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Specify quantity of item.\" type=\"text\" name=\"demandsQuantities[".$ff."]\" class=\"form-control input-sm\" placeholder=\"Quantity\" value=\"0\" />\n";
			echo "                        <select data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Specify item.\" name=\"demandsItems[".$ff."]\" class=\"form-control input-sm\">\n";
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM resources ORDER BY id ASC")) {
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->store_result();

				while ($stmt->fetch()) {
					$next_resources = $r_result;
					$resourceTypeInfo1 = getResourceTypeInfo($getPage_connection2,$next_resources);
					echo "                          <option value=\"resources.".strtolower($resourceTypeInfo1["name"])."\">".$resourceTypeInfo1["name"]."</option>\n";
				} // while
				$stmt->close();
			} else {
			} // else
			if ($stmt = $getPage_connection2->prepare("SELECT id FROM goods ORDER BY id ASC")) {
				$stmt->execute();
				$stmt->bind_result($r_result);
				$stmt->store_result();
				
				while ($stmt->fetch()) {
					$next_goods = $r_result;
					$goodsInfo1 = getGoodsInfo($getPage_connection2,$next_goods);
					echo "                          <option value=\"goods.".strtolower($goodsInfo1["name"])."\">".$goodsInfo1["name"]."</option>\n";
				} // while
				$stmt->close();
			} else {
			} // else
	
			echo "                          <option value=\"money.money\">Money</option>\n";
			echo "                          <option value=\"food.food\">Food</option>\n";
	
			echo "                        </select>\n";
			echo "                        <br />\n";
		} // else
	} // for
	echo "                      </div>\n";
	echo "                    </div>\n";
	
	echo "                    <br />\n";
	echo "                    <div class=\"col-md-12\">\n";
	echo "                      <label class=\"control-label\" for=\"set_turns\">Turns:</label>\n";
	echo "                      <input data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Specify the duration of the deal.\" type=\"text\" name=\"turns\" id=\"set_turns\" class=\"form-control input-sm\" value=\"1\" placeholder=\"e.g. 1\" />\n";
	echo "                    </div>\n";
	echo "                    <br />\n";
	echo "                    <div class=\"col-md-12\">\n";
	echo "                      <button data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Send deal to nation for consideration.\" value=\"send_offer\" name=\"action\" id=\"send_offer\" type=\"submit\" class=\"btn btn-md btn-success info_button\">Send</button>\n";
	echo "                      <button data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Reset deal's contents to none.\" value=\"reset_offer\" name=\"action\" id=\"reset_offer\" type=\"submit\" class=\"btn btn-md btn-danger info_button\">Reset</button>\n";
	echo "                    </div>\n";
	echo "                  </div>\n";
	echo "                </form>\n";
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";
	
	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View current market rates.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Current Market        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseMarket\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseMarket\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	
	if ($stmt = $getPage_connection2->prepare("SELECT id FROM market ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->store_result();
		
		while ($stmt->fetch()) {
			$next_market = $r_result;
			$marketInfo1 = getMarketInfo($getPage_connection2,$next_market);
		
			echo "                ".$marketInfo1["name"].": ".$marketInfo1["rate"]."% value\n                <br />\n";
		} // while
		$stmt->close();
	} else {
	} // else
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";
	
	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View previously accepted trade agreements.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Accepted Agreements        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseAcceptedAgreements\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseAcceptedAgreements\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	
	$agreementsExist = false;
	if ($stmt = $getPage_connection2->prepare("SELECT id FROM agreements ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->store_result();
		
		while ($stmt->fetch()) {
			$next_agreements = $r_result;
			$agreementInfo1 = getAgreementInfo($getPage_connection2,$next_agreements);
		
			if (($agreementInfo1["toNation"] == $_SESSION["nation_id"] || $agreementInfo1["fromNation"] == $_SESSION["nation_id"]) && $agreementInfo1["status"] == 1) {
				$agreementsExist = true;
				$toNationInfo = getNationInfo($getPage_connection2,$agreementInfo1["toNation"]);
				$fromNationInfo = getNationInfo($getPage_connection2,$agreementInfo1["fromNation"]);
				echo "                To: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$toNationInfo["id"]."\">".$toNationInfo["name"]."</a>, From: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$fromNationInfo["id"]."\">".$fromNationInfo["name"]."</a>\n                <br />\n";
				echo "                Agreement Policy: ".$agreementInfo1["policy"]."\n                <br />\n";
				echo "                ".$agreementInfo1["counter"]." / ".$agreementInfo1["turns"]." turns \n";
				echo "                <br />\n";
				echo "                ----\n                <br />\n";
			} // if
		} // while
		$stmt->close();
	} else {
	} // else
	
	if ($agreementsExist === false) {
		echo "                No agreements established.\n";
	} // if
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";

	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View previously accepted offers.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Accepted Offers        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseAcceptedOffers\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseAcceptedOffers\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";

	$offersExist = false;
	if ($stmt = $getPage_connection2->prepare("SELECT id FROM offers ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->store_result();
		
		while ($stmt->fetch()) {
			$next_offers = $r_result;
			$offerInfo1 = getOfferInfo($getPage_connection2,$next_offers);
	
			if (($offerInfo1["toNation"] == $_SESSION["nation_id"] || $offerInfo1["fromNation"] == $_SESSION["nation_id"]) && $offerInfo1["status"] == 1) {
				if ($offerInfo1["givingItems"][0] > 0 || $offerInfo1["receivingItems"][0] > 0) {
					$offersExist = true;
					$toNationInfo = getNationInfo($getPage_connection2,$offerInfo1["toNation"]);
					$fromNationInfo = getNationInfo($getPage_connection2,$offerInfo1["fromNation"]);
					echo "                To: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$toNationInfo["id"]."\">".$toNationInfo["name"]."</a>, From: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$fromNationInfo["id"]."\">".$fromNationInfo["name"]."</a>\n                <br />\n";
					if ($offerInfo1["givingItems"][0] > 0) {
						echo "                Giving:\n                <br />\n";
						for ($z=0; $z < count($offerInfo1["givingItems"]); $z++) {
							if ($offerInfo1["givingQuantities"][$z] > 0) {
								if ($offerInfo1["givingTypes"][$z] == "goods") {
									$itemInfo1 = getGoodsInfo($getPage_connection2,$offerInfo1["givingItems"][$z]);
									$name1 = $itemInfo1["name"];
								} else if ($offerInfo1["givingTypes"][$z] == "resources") {
									$itemInfo1 = getResourceTypeInfo($getPage_connection2,$offerInfo1["givingItems"][$z]);
									$name1 = $itemInfo1["name"];
								} else {
									$name1 = "money";
								} // else
								echo "                ".$offerInfo1["givingQuantities"][$z]." ".$name1."\n                <br />\n";
							} // if
						} // for
					} else {
						echo "                Giving:\n                <br />\n                Nothing.\n";
					} // else
					if ($offerInfo1["receivingItems"][0] > 0) {
						echo "                Receiving:\n                <br />\n";
						for ($z=0; $z < count($offerInfo1["receivingItems"]); $z++) {
							if ($offerInfo1["receivingQuantities"][$z] > 0) {
								if ($offerInfo1["receivingTypes"][$z] == "goods") {
									$itemInfo1 = getGoodsInfo($getPage_connection2,$offerInfo1["receivingItems"][$z]);
									$name1 = $itemInfo1["name"];
								} else if ($offerInfo1["receivingTypes"][$z] == "resources") {
									$itemInfo1 = getResourceTypeInfo($getPage_connection2,$offerInfo1["receivingItems"][$z]);
									$name1 = $itemInfo1["name"];
								} else {
									$name1 = "money";
								} // else
								echo "                ".$offerInfo1["receivingQuantities"][$z]." ".$name1."\n                <br />\n";
							} // if
						} // for
					} else {
						echo "                Receiving:\n                <br />\n                Nothing.\n";
					} // else
					echo "                <br />\n";
					echo "                ".$offerInfo1["counter"]." / ".$offerInfo1["turns"]." turns \n";
					echo "                <br />\n";
					echo "                ----\n                <br />\n";
				} // if
			} // if
		} // while
		$stmt->close();
	} else {
	} // else

	if ($offersExist === false) {
		echo "                No offers established.\n";
	} // if
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";
	
	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View previously declined trade agreements.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Declined Agreements        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseDeclinedAgreements\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseDeclinedAgreements\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";
	
	$agreementsExist = false;
	if ($stmt = $getPage_connection2->prepare("SELECT id FROM agreements ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->store_result();
		
		while ($stmt->fetch()) {
			$next_agreements = $r_result;
			$agreementInfo1 = getAgreementInfo($getPage_connection2,$next_agreements);
		
			if (($agreementInfo1["toNation"] == $_SESSION["nation_id"] || $agreementInfo1["fromNation"] == $_SESSION["nation_id"]) && $agreementInfo1["status"] == 2) {
				$agreementsExist = true;
				$toNationInfo = getNationInfo($getPage_connection2,$agreementInfo1["toNation"]);
				$fromNationInfo = getNationInfo($getPage_connection2,$agreementInfo1["fromNation"]);
				echo "                To: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$toNationInfo["id"]."\">".$toNationInfo["name"]."</a>, From: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$fromNationInfo["id"]."\">".$fromNationInfo["name"]."</a>\n                <br />\n";
				echo "                Agreement Policy: ".$agreementInfo1["policy"]."\n                <br />\n";
				echo "                <br />\n";
				echo "                ".$agreementInfo1["counter"]." / ".$agreementInfo1["turns"]." turns \n";
				echo "                <br />\n";
				echo "                ----\n                <br />\n";
			} // if
		} // while
		$stmt->close();
	} else {
	} // else
	
	if ($agreementsExist === false) {
		echo "                 No declined agreements exist so far.\n";
	} // if
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";

	echo "          <div class=\"panel panel-info\">\n";
	echo "            <div data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View previously declined offers.\" class=\"panel-heading\">\n";
	echo "              <h3 class=\"panel-title\">Declined Offers        <button type=\"button\" class=\"btn btn-default btn-md collapsed\" data-toggle=\"collapse\" data-target=\"#collapseDeclinedOffers\"><span class=\"glyphicon glyphicon-plus\"></span>/<span class=\"glyphicon glyphicon-minus\"></span></button></h3>\n";
	echo "            </div>\n";
	echo "            <div id=\"collapseDeclinedOffers\" class=\"panel-body collapse in\">\n";
	echo "              <div class=\"col-md-8 col-center\">\n";

	$offersExist = false;
	if ($stmt = $getPage_connection2->prepare("SELECT id FROM offers ORDER BY id ASC")) {
		$stmt->execute();
		$stmt->bind_result($r_result);
		$stmt->store_result();

		while ($stmt->fetch()) {
			$next_offers = $r_result;
			$offerInfo1 = getOfferInfo($getPage_connection2,$next_offers);
	
			if (($offerInfo1["toNation"] == $_SESSION["nation_id"] || $offerInfo1["fromNation"] == $_SESSION["nation_id"]) && $offerInfo1["status"] == 2) {
				if ($offerInfo1["givingItems"][0] > 0 || $offerInfo1["receivingItems"][0] > 0) {
					$offersExist = true;
					$toNationInfo = getNationInfo($getPage_connection2,$offerInfo1["toNation"]);
					$fromNationInfo = getNationInfo($getPage_connection2,$offerInfo1["fromNation"]);
					echo "                To: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$toNationInfo["id"]."\">".$toNationInfo["name"]."</a>, From: <a href=\"index.php?page=info&amp;section=nations&amp;info_id=".$fromNationInfo["id"]."\">".$fromNationInfo["name"]."</a>\n                <br />\n";
					if ($offerInfo1["givingItems"][0] > 0) {
						echo "                Giving:\n                <br />\n";
						for ($z=0; $z < count($offerInfo1["givingItems"]); $z++) {
							if ($offerInfo1["givingQuantities"][$z] > 0) {
								if ($offerInfo1["givingTypes"][$z] == "goods") {
									$itemInfo1 = getGoodsInfo($getPage_connection2,$offerInfo1["givingItems"][$z]);
									$name1 = $itemInfo1["name"];
								} else if ($offerInfo1["givingTypes"][$z] == "resources") {
									$itemInfo1 = getResourceTypeInfo($getPage_connection2,$offerInfo1["givingItems"][$z]);
									$name1 = $itemInfo1["name"];
								} else {
									$name1 = "money";
								} // else
								echo "                ".$offerInfo1["givingQuantities"][$z]." ".$name1."\n                <br />\n";
							} // if
						} // for
					} else {
						echo "                Giving:\n                <br />\n                Nothing.\n";
					} // else
					if ($offerInfo1["receivingItems"][0] > 0) {
						echo "                Receiving:\n                <br />\n";
						for ($z=0; $z < count($offerInfo1["receivingItems"]); $z++) {
							if ($offerInfo1["receivingQuantities"][$z] > 0) {
								if ($offerInfo1["receivingTypes"][$z] == "goods") {
									$itemInfo1 = getGoodsInfo($getPage_connection2,$offerInfo1["receivingItems"][$z]);
									$name1 = $itemInfo1["name"];
								} else if ($offerInfo1["receivingTypes"][$z] == "resources") {
									$itemInfo1 = getResourceTypeInfo($getPage_connection2,$offerInfo1["receivingItems"][$z]);
									$name1 = $itemInfo1["name"];
								} else {
									$name1 = "money";
								} // else
								echo "                ".$offerInfo1["receivingQuantities"][$z]." ".$name1."\n                <br />\n";
							} // if
						} // for
					} else {
						echo "                Receiving:\n                <br />\n                Nothing.\n";
					} // else
					echo "                <br />\n";
					echo "                ".$offerInfo1["counter"]." / ".$offerInfo1["turns"]." turns \n";
					echo "                <br />\n";
					echo "                ----\n                <br />\n";
				} // if
			} // if
		} // while
		$stmt->close();
	} else {
	} // else

	if ($offersExist === false) {
		echo "                No declined offers exist so far.\n";
	} // if
	echo "              </div>\n";
	echo "            </div>\n";
	echo "          </div>\n";

	echo "        </div>\n";
} // showTradeInfo

/*-----------------------------------------------*/
/********************************
 Trade Action Functions
 ********************************/
/*-----------------------------------------------*/

/********************************
 sendOffer
 sends new offer to target nation
 ********************************/
function sendOffer($getPage_connection2) {
	if ($_SESSION["action"] == "send_offer") {
		if (strlen($_SESSION["send_nation"]) > 0) {
			if ($_SESSION["turns"] > 0) {
				if (isset($_SESSION["offers"]) && isset($_SESSION["offersItems"]) && isset($_SESSION["offersQuantities"]) && isset($_SESSION["demands"]) && isset($_SESSION["demandsItems"]) && isset($_SESSION["demandsQuantities"])) {
					if (($_SESSION["offers"][0] > 0 && $_SESSION["offersItems"][0] > 0 && $_SESSION["offersQuantities"][0] > 0) ||  ($_SESSION["demands"][0] > 0 && $_SESSION["demandsItems"][0] > 0 && $_SESSION["demandsQuantities"][0] > 0)) {
						$nationInfoG = getNationInfoByName($getPage_connection2,$_SESSION["send_nation"]);
						addOfferInfo($getPage_connection2,$_SESSION["nation_id"],$nationInfoG["id"],$_SESSION["offersItems"],$_SESSION["demandsItems"],$_SESSION["offersQuantities"],$_SESSION["demandsQuantities"],$_SESSION["offersTypes"],$_SESSION["demandsTypes"],$_SESSION["turns"],0,0);
						$_SESSION["success_message"] = "Offer has been sent successfully!.";
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: deal is invalid. Check that you have filled out all required info.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: deal is invalid.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: turns not specified or not valid.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: no target nation defined.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: invalid action.";
	} // else
} // sendOffer

/********************************
 resetOffer
 resets all deal variables
 ********************************/
function resetOffer($getPage_connection2) {
	if ($_SESSION["action"] == "reset_offer") {
		$_SESSION["offers"] = array(0=>0);
		$_SESSION["offersItems"] = array(0=>1);
		$_SESSION["offersQuantities"] = array(0=>0);
		$_SESSION["demands"] = array(0=>0);
		$_SESSION["demandsItems"] = array(0=>1);
		$_SESSION["demandsQuantities"] = array(0=>0);
		$_POST["offers"] = array(0=>0);
		$_POST["offersItems"] = array(0=>1);
		$_POST["offersQuantities"] = array(0=>0);
		$_POST["demands"] = array(0=>0);
		$_POST["demandsItems"] = array(0=>1);
		$_POST["demandsQuantities"] = array(0=>0);

		$_SESSION["success_message"] = "Offer reset successfully!";
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: invalid action.";
	} // else
} // resetOffer

/********************************
 acceptOffer
 accepts incoming deal offer
 ********************************/
function acceptOffer($getPage_connection2) {
	if ($_SESSION["action"] == "accept_offer") {
		if ($_SESSION["action_id"] > 0) {
			$offerInfo1 = getOfferInfo($getPage_connection2,$_SESSION["action_id"]);
			if (($offerInfo1["toNation"] == $_SESSION["nation_id"] || $offerInfo1["fromNation"] == $_SESSION["nation_id"]) && $offerInfo1["status"] == 0) {
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

						$_SESSION["success_message"] = "Offer has been accepted successfully!";
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: a requested ".$notEnough[1]." is not possible due to insufficent supply.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: offer is empty.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: current nation is not involved in selected offer.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: invalid offer selected.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: invalid action.";
	} // else
} // acceptOffer

/********************************
 declineOffer
 rejects incoming deal offer
 ********************************/
function declineOffer($getPage_connection2) {
	if ($_SESSION["action"] == "decline_offer") {
		if ($_SESSION["action_id"] > 0) {
			$offerInfo1 = getOfferInfo($getPage_connection2,$_SESSION["action_id"]);
			if (($offerInfo1["toNation"] == $_SESSION["nation_id"] || $offerInfo1["fromNation"] == $_SESSION["nation_id"]) && $offerInfo1["status"] == 0) {
				if ($offerInfo1["givingItems"][0] > 0 || $offerInfo1["receivingItems"][0] > 0) {
					setOfferInfo($getPage_connection2,$_SESSION["action_id"],$offerInfo1["fromNation"],$offerInfo1["toNation"],$offerInfo1["givingItems"],$offerInfo1["receivingItems"],$offerInfo1["givingQuantities"],$offerInfo1["receivingQuantities"],$offerInfo1["givingTypes"],$offerInfo1["receivingTypes"],$offerInfo1["turns"],$offerInfo1["counter"],2);
					$_SESSION["success_message"] = "Offer has been declined successfully!";
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: offer is empty.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: current nation is not involved in selected offer.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: invalid offer selected.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: invalid action.";
	} // else
} // declineOffer

/********************************
 sendAgreement
 sends new agreement to target nation
 ********************************/
function sendAgreement($getPage_connection2) {
	if ($_SESSION["action"] == "send_agreement") {
		if (strlen($_SESSION["send_nation"]) > 0) {
			if ($_SESSION["turns"] > 0) {
				if (isset($_SESSION["agreement"])) {
					if (($_SESSION["agreement"] >= 0 && $_SESSION["agreement"][0] <= 10)) {
						$nationInfoG = getNationInfoByName($getPage_connection2,$_SESSION["send_nation"]);
						addAgreementInfo($getPage_connection2, $_SESSION["agreement"], $_SESSION["turns"], 0, $nationInfoG["id"], $_SESSION["nation_id"], 0);
						$_SESSION["success_message"] = "Agreement has been sent successfully!.";
					} else {
						$_SESSION["warning_message"] = "Cannot complete action: deal is invalid. Check that you have filled out all required info.";
					} // else
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: deal is invalid.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: turns not specified or not valid.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: no target nation defined.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: invalid action.";
	} // else
} // sendAgreement

/********************************
 resetAgreement
 resets all deal variables
 ********************************/
function resetAgreement($getPage_connection2) {
	if ($_SESSION["action"] == "reset_agreement") {
		$_SESSION["agreement"] = 0;
		$_SESSION["success_message"] = "Agreement reset successfully!";
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: invalid action.";
	} // else
} // resetAgreement

/********************************
 acceptAgreement
 accepts incoming deal agreement
 ********************************/
function acceptAgreement($getPage_connection2) {
	if ($_SESSION["action"] == "accept_agreement") {
		if ($_SESSION["action_id"] > 0) {
			$agreementInfo1 = getAgreementInfo($getPage_connection2,$_SESSION["action_id"]);
			if (($agreementInfo1["toNation"] == $_SESSION["nation_id"] || $agreementInfo1["fromNation"] == $_SESSION["nation_id"]) && $agreementInfo1["status"] == 0) {
				if ($agreementInfo1["policy"] >= 0 && $agreementInfo1["policy"] <= 10) {
					$tradeInfoToNation = getTradeInfo($getPage_connection2, $agreementInfo1["toNation"]);
					$tradeInfoFromNation = getTradeInfo($getPage_connection2, $agreementInfo1["fromNation"]);

					if ( ($tradeInfoFromNation["limit"] > count($tradeInfoFromNation["routes"])) || ($tradeInfoToNation["limit"] > count($tradeInfoToNation["routes"])) ) {
						$_SESSION["warning_message"] = "Cannot complete action: no trade route available.";
					} else {
						setAgreementInfo($getPage_connection2, $agreementInfo1["id"], $agreementInfo1["policy"], $agreementInfo1["turns"], $agreementInfo1["counter"], $agreementInfo1["toNation"], $agreementInfo1["fromNation"], 1);
						
						$new_index = count($tradeInfoFromNation["routes"]);
						$new_routes = $tradeInfoFromNation["routes"];
						$new_routes[$new_index] = $agreementInfo1["id"];
						setTradeInfo($getPage_connection2, $tradeInfoFromNation["id"], $tradeInfoFromNation["nation"], $new_routes, $tradeInfoFromNation["limit"]);
						
						$new_index = count($tradeInfoToNation["routes"]);
						$new_routes = $tradeInfoToNation["routes"];
						$new_routes[$new_index] = $agreementInfo1["id"];
						setTradeInfo($getPage_connection2, $tradeInfoToNation["id"], $tradeInfoToNation["nation"], $new_routes, $tradeInfoToNation["limit"]);
						
						$_SESSION["success_message"] = "Agreement has been accepted successfully!";
					}					
				} else {
					$_SESSION["warning_message"] = "Cannot complete action: agreement is invalid.";
				} // else
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: current nation is not involved in selected agreement.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: invalid agreement selected.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: invalid action.";
	} // else
} // acceptAgreement

/********************************
 declineAgreement
 rejects incoming deal agreement
 ********************************/
function declineAgreement($getPage_connection2) {
	if ($_SESSION["action"] == "decline_agreement") {
		if ($_SESSION["action_id"] > 0) {
			$agreementInfo1 = getAgreementInfo($getPage_connection2,$_SESSION["action_id"]);
			if (($agreementInfo1["toNation"] == $_SESSION["nation_id"] || $agreementInfo1["fromNation"] == $_SESSION["nation_id"]) && $agreementInfo1["status"] == 0) {
				$tradeInfoToNation = getTradeInfo($getPage_connection2, $agreementInfo1["toNation"]);
				$tradeInfoFromNation = getTradeInfo($getPage_connection2, $agreementInfo1["fromNation"]);
				setAgreementInfo($getPage_connection2, $agreementInfo1["id"], $agreementInfo1["policy"], $agreementInfo1["turns"], $agreementInfo1["counter"], $agreementInfo1["toNation"], $agreementInfo1["fromNation"], 2);
				$_SESSION["success_message"] = "Agreement has been declined successfully!";				
			} else {
				$_SESSION["warning_message"] = "Cannot complete action: current nation is not involved in selected agreement.";
			} // else
		} else {
			$_SESSION["warning_message"] = "Cannot complete action: invalid agreement selected.";
		} // else
	} else {
		$_SESSION["warning_message"] = "Cannot complete action: invalid action.";
	} // else
} // declineAgreement
?>