<?php
include ("../connect.php");
//variables
$titre_projet = "BibUp";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$titre_projet?>  - University of Fribourg</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
body {
	font-size:300%;
}
.title{
	font-size:150%;
}
.auteur{
	font-size:120%;
	font-style:italic;
	margin-bottom:20px;
}
.reference{
	border: 5px blue solid;
	width: 96%;
	margin: 0 auto;
	text-align:justify;
}
.reference .label{
	position:absolute;
	top:10px; /* in conjunction with left property, decides the text position */
	left:200px;
	font-size:120%;
	font-weight: bold;
	text-align:center;
	color:coral;
/*	width:300px; /* optional, though better have one */
}
a.tt{
	text-decoration: none;
	color: black;
}
div.allReferences {
	margin: 0 auto;
	text-align:center;
	width: 90%;
	border-top: 3px coral solid;
	font-weight: bold;
}
</style>
</head>
<body>
<?
$idToShow = $_GET['id'];
//echo "ID = $idToShow";
//dbug
//$idToShow = 7;
//echo "dbug ID = $idToShow";

$requete = "select * from fiches where id = $idToShow";
//echo $requete;
$result = mysql_query($requete) or die(mysql_error());
echo "<br><br>";
echo "<div class=\"reference\">";
echo "<div class=\"label\">Your current reference :</div>";

while($row = mysql_fetch_array($result))
{
	echo "<a class=\"tt\" href=\"" . dirname($_SERVER['SCRIPT_NAME']) . "/../uploads/".substr($row['contentSnapshot'],0,-11)."/" . $row['contentSnapshot'] . "\">";
	echo "<div class=\"title\">" . $row['title'] . "</div>";
	echo "<div class=\"auteur\">" . $row['auteur'] . "</div>";
	if (!empty($row['contentSnapshot']) && (substr_count($row['contentSnapshot'],"NULLLLL") == 0))
	{
		echo "<div class=\"text\">";
		echo("<img src=\"". dirname($_SERVER['SCRIPT_NAME']) . "/../uploads/".substr($row['contentSnapshot'],0,-11)."/thumb/" . $row['contentSnapshot']. "\" align=\"left\">");
		if (strlen($row['textOCR']) > 0)
		{
			echo substr($row['textOCR'],0,400)."...";
		}
		echo "</a>";
		echo "</div>";
	}
}
echo "</div>";
?>
<br>
<div class="allReferences">
<table class="allReferences" >
<tr>
<td rowspan="2">
<a href="http://www.unifr.ch/go/bibup"><img src="http://nte.unifr.ch/misc/bibup/images/logo_bibup_512x512.png" width="128"></a>
</td>
<td>
To use your refs with Zotero go to&nbsp;: <a href="http://www.unifr.ch/go/bibup">http://www.unifr.ch/go/bibup</a>
</td>
</tr>
</table>
<br>
</div>
</body>
</html>