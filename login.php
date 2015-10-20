<?php
session_name('prio2010_Beta');
session_start();
include("includes/fonctions.inc");
include("header.php");

?>
	<!-- start:colonneRight -->
	<div id="content">
	<p><h1>Administration</h1></p>

	<form action="login_admin.php?idid=<?php echo($_GET['idid']);?>" method="post" name="loginForm">
	<table cellpadding="3" border="0" width="100%" align="left">
		<tr>
			<td>
				<table border="0" width="95%"cellpadding="3">

					<tr>
						<td  valign="top" width="25%"><img src="includes/images/security.png" alt="auteur" /></td>
						<td>Username <br />
							<input class="form" name="username" type="text" size="25" /><br />
							Password <br />
							<input class="form" name="password" type="password" size="25" /><br /><br />
							<input type="submit" name="submit" class="button" value="Login" /><br /><br />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	</form>
<script>
	document.loginForm.username.focus();
</script>
 	</div>
	<!-- end:colonneRight -->
<?php
include("footer.php");
?>
