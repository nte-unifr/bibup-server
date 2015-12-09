<?php
session_name('prio2010_Beta');
session_start();
include("includes/fonctions.inc");
include("header_bootstrap.php");

$username=mysql_real_escape_string($_POST['username']);
$password=md5(mysql_real_escape_string($_POST['password']));
?>

<div class="row">
	<div class="col-xs-12">
		<div class="page-header">
			<h1>Administration</h1>
		</div>
		<div class="row">
			<div class="col-xs-4">

				<?php
				if($username=='' || $password=='')
				{
					echo "<div class=\"alert alert-danger\" role=\"alert\">You forgot a field.</div>";
				}
				else
				{
					$query = "select * from users where userName='$username' and password='$password'";
					//exécution de la requête
					$result = mysql_query($query);
					$data = mysql_fetch_array($result);
					if($data['password'] != $password)
					{
						echo "<div class=\"alert alert-danger\" role=\"alert\">Wrong login / password.</div>";
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
		</div>
	</div>
</div>
<?php
include("footer1_bootstrap.php");
?>
