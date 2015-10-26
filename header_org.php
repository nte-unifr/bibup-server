<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$titre_projet?>  - University of Fribourg</title>
<link rel="unapi-server" type="application/xml" title="unAPI" href="http://elearning.unifr.ch/bibup/unapi.php" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link href="http://unifr.ch/css/crpr/unifr.101.css" rel="stylesheet" type="text/css" />
<link href="includes/style.css" rel="stylesheet" type="text/css" />
<link href="includes/blue/style.css" rel="stylesheet" type="text/css" />
<link href="includes/css/infobulle.css" rel="stylesheet" type="text/css" />

<!-- TUTO -->

<link href="tuto/style.css" type="text/css" rel="stylesheet"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
	//slidetoggle
	$('.toggler').click(function(){
		$(this).parents(".teamMemberRight").children('.toggleMe').slideToggle('fast');
		return false; //ensures no anchor jump
	});
});
</script>
<style type="text/css">
#webpage {
	width: 900px;
	margin: auto;
	padding: auto;
	text-align:left;
	font-size:12px;
}

#main {
	position:absolute;
	width:898px;
	margin: 0;
	padding: 0;
	clear: both;
	background-image: url(http://unifr.ch/images/background_main.gif);
	background-repeat: repeat-y;
	border-top-width: 1px;
	border-top-style: solid;
	border-top-color: #FFFFFF;
	border-right-width: 1px;
	border-left-width: 1px;
	border-bottom-width: 1px;
	border-right-style: solid;
	border-left-style: solid;
	border-bottom-style: solid;
	border-right-color: #CCCCCC;
	border-left-color: #CCCCCC;
	border-bottom-color: #CCCCCC; 
	
}
</style>

<!-- END TUTO -->

<script type="text/javascript" src="javascript/swfobject.js"></script>
<!--<script type="text/javascript" src="javascript/videobox.js"></script>-->
<script src="javascript/prototype.js" type="text/javascript"></script>
<script src="javascript/lightbox.js" type="text/javascript"></script>

<script type="text/javascript">
function openWin(url)
{
var newwin = window.open(url,'fullflash','width='+screen.width+',height='+screen.height+',top=0,left0');
if(newwin) newwin.focus();
}
</script>
<link rel="stylesheet" href="css/videobox.css" type="text/html" media="screen" />
<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />




<meta name="keywords" lang="fr" content="universit&eacute;, formation, suisse, fribourg, recherche, &eacute;tudes" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="includes/sorttable.js"></script>

</head>

<body>
<div id="webpage">
<!-- start:identite -->
<div id="identity">
	<div id="address">
		<div class="txt">
			<?=$titre_projet?><br />
		</div>
	</div>
	<div id="bandeau">
	</div>
</div><!-- end:identite -->

<!-- start:navig -->
<div id="navigBarre">
	<div id="nbLeft">
		<ul>
	    	<li><a href="http://www.unifr.ch">Unifr</a>|</li>
	    	<li><a href="http://www.unifr.ch/nte">Centre NTE</a></li>
	    </ul>
	</div>
	<div id="nbRight">
		<ul>
		<?php
		if(isset($_SESSION['password']))
		{
			echo "<li><a href=\"index.php\">Home</a></li>";
			echo "<li><a href=\"faq.php\">FAQ</a></li>";
			echo "<li><a href=\"contact.php\">Contact</a></li>";		
			echo "<li><a href=\"logout.php\">Logout</a></li>";
		}
		else
		{
			echo "<li><a href=\"index.php\">Home</a></li>";
			echo "<li><a href=\"faq.php\">FAQ</a></li>";
			echo "<li><a href=\"contact.php\">Contact</a></li>";
		 	echo "<li><a href=\"login.php\">Admin</a></li>";
		 	
		}
		?>
		</ul>
	</div>
</div><!-- end:navig -->

<!-- start:colonneLeft -->
<div id="main">
	<div id="menu">
	<?php
	if(isset($_SESSION['password']))
	{
	?>
	<!-- box menu2 -->
		<div class="box001">
			<h2>Admin</h2>
			<div class="box001_content">
				<div align=center>
				<strong>Username</strong> : <?=$_SESSION['username']?>

				<br /><br /><a href="logout.php">Logout Admin</a><br /><br />
				</div>
   			</div>
		</div>
	<?php
	}
	?>
	<br />
	
	<img src="images/logo_bibup_512x512.png" width="100" border="0" ><br /><br />
	



<link rel="stylesheet" href="engine/css/videolightbox.css" type="text/css" />
<style type="text/css">#videogallery a#videolb{display:none}</style>


			<link rel="stylesheet" type="text/css" href="lightbox/engine/css/overlay-minimal.css"/>

			<script src="lightbox/engine/js/jquery.tools.min.js" type="text/javascript"></script>

			<script src="lightbox/engine/js/swfobject.js" type="text/javascript"></script>

			<!-- make all links with the 'rel' attribute open overlays -->

			<script src="lightbox/engine/js/videolightbox.js" type="text/javascript"></script>


</head><body>	 



<script type="text/javascript">
function onYouTubePlayerReady(playerId) { 
ytplayer = document.getElementById("video_overlay"); 
ytplayer.setVolume(200); 
} 
</script> 
<!-- box menu2 -->
		<div class="box001">
			<h2>Watch an example</h2>
			<div class="box001_content">
				<div align=center>
				<a rel="#voverlay" href="lightbox/data/video/video.swf" title="watch the video" ><img src="images/logo_video.png" alt="bibup&zotero" width="128" border="1" /><span></span></a><br /><br />
				</div>
   			</div>
		</div>
<!--div id="videogallery" align="left">
<a rel="#voverlay" href="lightbox/data/video/video.swf" title="watch the video"><img src="images/logo_video.png" alt="bibup&zotero" width="128" border="1" /><span></span></a>
</div-->

<!-- box menu2 -->
		<div class="box001">
			<h2>Tutorial</h2>
			<div class="box001_content">
				<div align=center>
				<a href="tuto/index.php" title="tuto" ><img src="images/logo_tuto.png" alt="tutorial" width="128" border="0" /><span></span></a><br /><br />
				</div>
   			</div>
		</div>
		<br /><br />
		
		<a href="http://itunes.apple.com/app/bibup-universite-de-fribourg/id418304170?mt=8#" target="_blank"><img src="images/AppStoreBadge.png" border="0" /></a>


	
	</div>
	
<!-- end:colonneLeft -->