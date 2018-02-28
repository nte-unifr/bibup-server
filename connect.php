<?php

//VARIABLES DE CONNEXION1

$db_host = "localhost";
$db_login = "root";
$db_password = "";
$db_database = "bibup";

$bibup_faq = "faq";

$titre_projet = "BibUp";

//CONNEXION
$connexion1 = mysqli_connect($db_host, $db_login, $db_password) or die ('I cannot connect to the database because: ' . mysqli_error($connexion1));
$connexion1->select_db ($db_database) or die("Sélection de la base de données impossible");
//$connexion1->set_charset('utf8');

?>
