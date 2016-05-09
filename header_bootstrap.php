<!DOCTYPE html>
<html lang="en">
<head>
<link rel="unapi-server" type="application/xml" title="unAPI" href="http://elearning.unifr.ch/bibup/unapi.php" />
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" lang="fr" content="universit&eacute;, formation, suisse, fribourg, recherche, &eacute;tudes" />
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title><?=$titre_projet?>  - University of Fribourg</title>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

	<!-- Open Sans from Google fonts -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,700,300,600,800,400' rel='stylesheet' type='text/css'>

	<!-- Bootstrap -->
	<link rel="stylesheet" href="dist/css/bootstrap.min.css" />

	<!-- Bibup -->
	<link rel="stylesheet" href="dist/css/bibup.css" />
	<link rel="stylesheet" href="dist/js/themes/blue/style.css">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <a href="http://www.unifr.ch" target="_blank"><img src="dist/images/unifr_universite_cut.png" alt="Unifr" /></a>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="application-title">
                    BIBUP
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
			    			<span class="navbar-brand">Bibup</span>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse" id="main-navbar-collapse">
                            <ul class="nav navbar-nav">
								<?php
									$page = basename($_SERVER['SCRIPT_NAME']);
									$m1 = '';
									$m2 = '';
									$m3 = '';
									$m4 = '';
									if ($page == 'index.php') {
										$m1 = 'class="active"';
										$m2 = '';
										$m3 = '';
										$m4 = '';
									} else if ($page == 'tutorial.php') {
										$m1 = '';
										$m2 = 'class="active"';
										$m3 = '';
										$m4 = '';
									} else if ($page == 'faq.php') {
										$m1 = '';
										$m2 = '';
										$m3 = 'class="active"';
										$m4 = '';
									} else if ($page == 'contact.php') {
										$m1 = '';
										$m2 = '';
										$m3 = '';
										$m4 = 'class="active"';
									} else {
										$m1 = '';
										$m2 = '';
										$m3 = '';
										$m4 = '';
									}
								?>
                                <li <?php echo $m1 ?>><a href="index.php">References</a></li>
                                <li <?php echo $m2 ?>><a href="tutorial.php">Tutorial</a></li>
                                <li <?php echo $m3 ?>><a href="faq.php">FAQ</a></li>
                                <li><a href="https://survey.unifr.ch/index.php/95831?lang=en" target="_blank">Feedback</a></li>
								<?php
								if (isset($_SESSION['password'])) {
									echo '<li><a href="logout.php">Logout</a></li>';
								}
								?>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
								<li <?php echo $m4 ?>><a href="contact.php">Contact</a></li>
                            </ul>
                        </div><!-- /.navbar-collapse -->
                    </div><!-- /.container-fluid -->
                </nav>
            </div>
        </div>
