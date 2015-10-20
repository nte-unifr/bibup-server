<?php
session_name('prio2010_Beta');
session_start();
include("includes/fonctions.inc");
include("header.php");

$username=mysql_real_escape_string($_POST['username']);
$password=md5(mysql_real_escape_string($_POST['password']));
?>
	<!-- start:colonneRight -->
	<div id="content">
	<?php
	if($username=='' || $password=='')
	{
		echo "<p><font color=red >You forgot a field.</font></p>";
	}
	else
	{
		$query = "select * from users where userName='$username' and password='$password'";
//exécution de la requête
		$result = mysql_query($query);
		$data = mysql_fetch_array($result);
		if($data['password'] != $password)
		{
			echo "<p><font color=red >Wrong login / password.</font></p>";
		}
		else
		{
// on demarre une session
//			session_name('_prio2010_Beta');
//			session_start();
// On enregistre les variables login et password dans la session en cours
// Attention, pas de signe $ dans le session_register
			$_SESSION["password"]=$password;
			$_SESSION["username"]=$username;
			echo "<META http-equiv=\"Refresh\" content=\"0;URL=index.php\">";
		}
	}
	?>
 	</div>
	<!-- end:colonneRight -->
<?php
include("footer.php");
?>
