<?php
session_name('prio2010_Beta');
session_start();
include("includes/fonctions.inc");
include("header_bootstrap.php");

?>

<div class="row">
	<div class="col-xs-12">
		<div class="page-header">
			<h1>Administration</h1>
		</div>

		<div class="row">
			<div class="col-xs-4">
				<form class="form" action="login_admin.php?idid=<?php echo($_GET['idid']);?>" method="post" name="loginForm">
					<div class="form-group">
						<label for="username">Username </label>
						<input type="text" class="form-control" id="username" name="username" size="25" />
						<label for="password">Password </label>
						<input type="password" class="form-control" id="password" name="password" size="25" />
					</div>
					<button type="submit" name="submit" value="Login" class="btn btn-primary">Login</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	document.loginForm.username.focus();
</script>
<?php
include("footer1_bootstrap.php");
?>
