<?php
/****************************************************************************
* Name:        index.php
* Author:      Ben Barnes
* Date:        2016-01-18
* Purpose:     Server-side execution of validation, html organization and display, mysql interaction.
*****************************************************************************/

require "functions.php";

$connection_host = "localhost";
$connection_user = "1_sec";
$connection_password = "ACCGZUeNQPThjFxb";
$connection_user2 = "1_fil";
$connection_password2 = "EHZdmaUuuFyjcqvc";
$connection_user3 = "1_adm";
$connection_password3 = "jcSjmDZn4ZDnx9L9";
$connection_database = "worldsdb";
$connection_port = "";
$mysqli = getConnection($connection_host,$connection_user,$connection_password,$connection_database,$connection_port); // file user
$mysqli2 = getConnection($connection_host,$connection_user2,$connection_password2,$connection_database,$connection_port); // standard user
$mysqli3 = getConnection($connection_host,$connection_user3,$connection_password3,$connection_database,$connection_port); // admin user

session_start();

error_reporting(E_ALL);

set_time_limit(14400);

getPage($mysqli,$mysqli2,$mysqli3);

// close mySQL connection
$mysqli->close();
$mysqli2->close();
$mysqli3->close();
?>