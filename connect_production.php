<?php

//VARIABLES DE CONNEXION1

$db_host = "127.0.0.1";
$db_login = "bibup_user";
$db_password = "***REMOVED***";
$db_database = "bibup";

$bibup_faq = "faq";

$titre_projet = "BibUp";

//CONNEXION
$connexion1 = mysql_connect($db_host, $db_login, $db_password) or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ($db_database) or die("Sélection de la base de données impossible");
//mysql_set_charset('utf8');

?>
