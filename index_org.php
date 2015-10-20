<?php
session_name('prio2010_Beta');
session_start();
include("includes/fonctions.inc");
include("header.php");

?>
	<!-- start:colonneRight -->
	<div id="content">
 		<table width=100% border=0>
			<tr>
				<td class="contenu">
					<br>
					<span align="left"><h1><img src="images/books.png" width="64" alt="references">Stored references</h1></span><br /><br />

					<br>
<?php

					$aWeek = 7 * 24 * 60 * 60;
					$aDay = 24 * 60 * 60;
					$currentTime = time();
					$limitDate =$currentTime - $aWeek;
					$formatedLimitDate = date('Y-m-d', $limitDate);
					$requete = "select * from fiches where datecreated > '$formatedLimitDate' order by datecreated desc";

					$result = mysql_query($requete) or die("<br />couldn't execute query");
					echo "<table class=\"sortable\">
							<tr>
					 			<th width=\"80\">Date<span id=\"sorttable_sortfwdind\">&nbsp;<img src=\"includes/images/arrowUp.jpg\" width=\"12\"></span></th>
					 			<th>Title</th>
					 			<th class=\"sorttable_nosort\">Author</th>
					 			<th class=\"sorttable_nosort\">Extract</th>
					 			<th class=\"sorttable_nosort\">Title</th>
							</tr>";
					while($row = mysql_fetch_array($result))
					{
//afficher les fiches
//						echo('<span class="Z3988" title="' . $row['coin'] . '">' . $row['title'] . ', ' . $row['auteur'] . ', créé le ' . date('j M Y',strtotime($row['datecreated'])) . '</span>');
						echo "<tr>";
						echo"<td sorttable_customkey=\"".strtotime($row['datecreated'])."\">";
						echo(date('j M Y',strtotime($row['datecreated'])) .
							' ' .
							date('H:i',strtotime($row['datecreated'])) .
							' <abbr class="unapi-id" title="' .
							$row['id'] .
							'"></abbr>');
						echo "</td><td>";
						echo "<strong>". $row['title']. "</strong>";
						echo "</td><td>";
						echo $row['auteur'];
						echo "</td>";
//						echo(date('j M Y',strtotime($row['datecreated'])) . ', ' . date('H:i',strtotime($row['datecreated'])) . ' : <abbr class="unapi-id" title="' . $row['id'] . '"></abbr><strong>' . $row['title'] . '</strong>, ' . $row['auteur']);
						if (!empty($row['contentSnapshot']) && (substr_count($row['contentSnapshot'],"NULLLLL") == 0)) {
							echo "<td>";
							echo("<a class=\"tt\" href=\"" . dirname($_SERVER['SCRIPT_NAME']) . "/uploads/".substr($row['contentSnapshot'],0,-11)."/" . $row['contentSnapshot'] . "\"><img src=\"". dirname($_SERVER['SCRIPT_NAME']) . "/uploads/".substr($row['contentSnapshot'],0,-11)."/thumb/" . $row['contentSnapshot']. "\" >");
							if (strlen($row['textOCR']) > 0) {
								echo "<span class=\"tooltip\"><span class=\"top\"></span><span class=\"middle\">" . truncate_string($row['textOCR'],200) . "</span><span class=\"bottom\"></span></span>";
							}
							echo "</a></td>";
						} else {
							echo "<td></td>";
						}

						if (!empty($row['titleSnapshot']) && (substr_count($row['titleSnapshot'],"NULLL") == 0)) {
							echo "<td>";
							echo("<a href=\"" . dirname($_SERVER['SCRIPT_NAME']) 				. "/uploads/".substr($row['titleSnapshot'],0,-9)."/" . $row['titleSnapshot'] . "\"><img src=\"". dirname($_SERVER['SCRIPT_NAME']) 				. "/uploads/".substr($row['titleSnapshot'],0,-9)."/thumb/" . $row['titleSnapshot']. "\" ></a>");
							echo "</td>";
						}else{
							echo "<td></td>";
						}
						/*if(isset($_SESSION['password']))
						{
							echo " <a href=\"fiche.php?id=".$row['id']."\">Edit</a><br />";
						}*/
						echo "</tr>";
					}

					echo "</table>";

					?>
				</td>
			</tr>
		</table>
	</div>
	<!-- end:colonneRight -->

<?php
include("footer.php");
?>
