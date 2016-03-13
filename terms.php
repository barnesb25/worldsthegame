<?php
/****************************************************************************
* Name:        terms.php
* Author:      Ben Barnes
* Date:        2016-02-20
* Purpose:     Terms page
*****************************************************************************/
getGlobals_terms($getPage_connection2);
showTitle("Terms");
compileMenu($getPage_connection2,"terms");
showWarning($getPage_connection2);
showPageTitle($getPage_connection2,"Terms and Conditions and Privacy Policy",false);
showTermsInfo($getPage_connection2);
require "section4.txt";
resetSession(true);
?>