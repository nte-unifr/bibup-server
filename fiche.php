<?php
session_name('prio2010_Beta');
session_start();
include("includes/fonctions.inc");
include("header.php");

$message = "";
$idFiche = (int)($_GET['id']);
$action = mysql_real_escape_string($_GET['action']);

if(isset($_SESSION['password']) && $action == "modify_fiche")
{
//efface la valeur du bouton
	unset($_POST['submit']);
//log file
	$date = date("d.m.Y h:i:s A");
	$logMessage = $_SESSION['username']." MODIFY THE DOCUMENT --> fiche no ".(int)($_GET['id']).", ".$date;
	append_file("./log/log.txt",$logMessage."\r\n");
//creation de la requete
	$sql = sql_update("fiches",$_POST,true,$idFiche);
	updateFiche($sql);
	$message= "La fiche a été modifiée <br \><br \>Retour à la <a href=\"index.php\">la liste des fiches</a>";
	unset($_SESSION['validate']);
}//if(isset($_SESSION['password']) && $action == "modify_fiche")

$requeteFiche = "SELECT * FROM fiches where id = $idFiche";
$resultFiche = mysql_query($requeteFiche);
@$rowFiche = mysql_fetch_array($resultFiche);//evite le WARNING si vide
?>

	<!-- start:colonneRight -->
	<div id="content">
	<div align="center"><h1><?=$rowFiche['id']?></h1></div><br />
	<form action="fiche.php?action=modify_fiche&id=<?=$idFiche?>" method="POST">
	<?php
//chercher l'image
//	$pathDisplay=getPathDisplayImage($rowFiche['image_id']);
	?>
	<img src="<?=$pathDisplay?>" align="left" alt="image du document" width=\"111\" height=\"204\" />
	<table cellpadding="0" border="0" width="60%" align="center">
	<?php
	if ($message != "") {
	?>
	<tr>
		<td>
			<div><h1><?=$message?></h1></div><br />
		</td>
	</tr>
	<?php
	}//	if ($message != "") {
	?>
	<tr>
		<td>
			<textarea name="textOCR" cols="60" rows="2" class="form"><?=$rowFiche['textOCR']?></textarea>
		</td>
	</tr>
	<tr>
		<td>
			<strong>Date</strong><br />
			<input type="text" name="date" size="63" class="form" value="<?=$rowFiche['date']?>">
		</td>
	 </tr>
	<tr>
		<td>
			<strong>Auteur</strong><br />
			<input type="text" name="auteur" size="63" class="form" value="<?=$rowFiche['auteur']?>">
		</td>
	 </tr>
	<?php
	if(isset($_SESSION['password']))
	{
	?>
	<tr>
		<td align="left">
			<input type="submit" name="submit" class="button" value="Valider" /><br /><br />
		</td>
	</tr>
	<?php
	}
	?>
	</table>
	<br />
	<br />
	</form>
	</div>
	<!-- end:colonneRight -->
<?php
include("footer.php");
?>