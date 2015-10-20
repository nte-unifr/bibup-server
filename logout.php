<?
//On démarre la session
session_name('prio2010_Beta');
session_start();

//Maintenant on détruit la session en cours
session_unset(); // on efface toutes les variables de session
session_destroy(); // on detruit la session en cours.

//On renvoi sur la page principale
echo "<META http-equiv=\"Refresh\" content=\"0;URL=index.php\">";
?>