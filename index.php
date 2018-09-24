<?php
session_name('prio2010_Beta');
session_start();
include("includes/fonctions.inc");
include("header_bootstrap.php");

$aMonth = 30 * 24 * 60 * 60;
$aWeek = 7 * 24 * 60 * 60;
$aDay = 24 * 60 * 60;
$currentTime = time();
$limitDate = $currentTime - $aMonth;
$formatedLimitDate = date('Y-m-d', $limitDate);

if (!isset($_GET['filter'])) {
    $theTag = "";
    $sqlTag = "tag = 'nte'";
} else if (isset($_GET['filter']) && ($_GET['tag']!= '')) {
    $theTag = $connexion1->real_escape_string($_GET['tag']);
    $sqlTag = "tag = '" . $theTag . "' and datecreated > '$formatedLimitDate' and OCRtodo = false order by datecreated desc";
    //$sqlTag = "tag = '" . $theTag . "' and datecreated > '$formatedLimitDate' order by datecreated desc";
} else {
    $theTag = 'Enter your tag here';
    $sqlTag = 'false';
}
$tagOnFocus = '';
?>

<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-warning" role="alert">The Bibup <strong>iOS and Android apps</strong> are temporarily <strong>unavailable</strong>. You can still check your existing references.</div>
		<div class="page-header">
			<h1>References</h1>
		</div>
		<div class="row">
			<div class="col-md-9">
				<p class="lead">References are stored for at least one month.</p>
				<form class="form-inline" action="index.php" method="get">
					<div class="form-group">
						<?php
					        if ($theTag == 'Enter your tag here' || $theTag == 'nte' ) {
					            $tagOnFocus = " onfocus=\"this.value='';\"";
					        }
						?>
						<label for="tag">Your tag </label>
						<input type="text" class="form-control" id="tag" name="tag" value="<?php echo $theTag ?>" <?php echo $tagOnFocus ?> />
					</div>
					<button type="submit" name="filter" value="Filter" class="btn btn-primary">Filter</button>
				</form>

                <div class="spacer"></div>

                <?php
        			$requete = "select * from fiches where " . $sqlTag;
        			$result = $connexion1->query($requete) or die("<br />couldn't execute query");
                    if (mysqli_num_rows($result) > 0) {
                        echo '<table class="table table-responsive tablesorter">
        						<thead>
        							<tr>
        								<th class="cur-pointer">Date </th>
        								<th class="cur-pointer">Title </th>
        								<th>Author</th>
        								<th>Extract</th>
        								<th>Title</th>
        						</thead>
        						<tbody>';

        				while($row = $result->fetch_array())
                        {
                        	echo "<tr>";
                            echo"<td>";
                            echo (date('j M Y',strtotime($row['datecreated'])) . ' ' . date('H:i',strtotime($row['datecreated'])) . ' <abbr class="unapi-id" title="' . $row['id'] . '"></abbr>');
                            echo "</td><td>";
                            echo "<strong>". $row['title']. "</strong>";
                            echo "</td><td>";
                            echo $row['auteur'];
                            echo "</td>";

                            if (!empty($row['contentSnapshot']) && (substr_count($row['contentSnapshot'],"NULLLLL") == 0))
        					{
                            	echo "<td>";
                                echo("<a href=\"" . dirname($_SERVER['SCRIPT_NAME']) . "/uploads/".substr($row['contentSnapshot'],0,-11)."/" . $row['contentSnapshot'] . "\"");

        						if (strlen($row['textOCR']) > 0) {
        							echo " title=\"" . htmlentities(truncate_string($row['textOCR'],200)) . "\"";
                                };

        						echo "><img src=\"". dirname($_SERVER['SCRIPT_NAME']) . "/uploads/".substr($row['contentSnapshot'],0,-11)."/thumb/" . $row['contentSnapshot']. "\"";
                                echo "</a></td>";
                            } else
        					{
                            	echo "<td></td>";
                            }

                            if (!empty($row['titleSnapshot']) && (substr_count($row['titleSnapshot'],"NULLL") == 0))
        					{
                            	echo "<td>";
                                echo("<a href=\"" . dirname($_SERVER['SCRIPT_NAME'])                            . "/uploads/".substr($row['titleSnapshot'],0,-9)."/" . $row['titleSnapshot'] . "\"");

        						if (strlen($row['titleOCR']) > 0) {
                                	echo " title=\"" . htmlentities($row['titleOCR']) . "\"";
                                };

        						echo "><img src=\"". dirname($_SERVER['SCRIPT_NAME']) . "/uploads/".substr($row['titleSnapshot'],0,-9)."/thumb/" . $row['titleSnapshot']. "\"";
                                echo "</a></td>";
                            } else
        					{
                                echo "<td></td>";
                            }

                            echo "</tr>";
                        }

                        echo "</tbody></table>";

                    } else
        			{
                        if ($sqlTag == 'false') {
                            echo '<p class="lead text-danger">Please enter a tag!</p>';
                        } else {
                            echo '<p class="lead text-warning">No references found for this tag!</p>';
                        }
                    }
                ?>
			</div>
			<div class="col-md-3">
                <div class="text-center">
                    <img class="app-icon" src="dist/images/bibupAppIcon.png" />
                    <!-- <a href="https://itunes.apple.com/app/bibup-universite-de-fribourg/id418304170?mt=8#"><img class="store-brand" src="dist/images/appstore.svg" /></a> -->
                    <!-- <a href="https://play.google.com/store/apps/details?id=ch.unifr.nte.bibup&hl=en"><img class="store-brand" src="dist/images/googleplay.png" /></a> -->
		</div>
                <div class="spacer"></div>
				<div class="well">
					<strong>Hint 1 :</strong> use the <a href="http://www.zotero.org/download/">Zotero</a> icon <img src="dist/images/zotero2.jpg" alt="zotero"> or <img src="dist/images/zotero.jpg" alt="zotero"> on the right on the Firefox adress bar to select the references you want to import in your Zotero. (Note: depending on the number of references, it might take some time (10 sec. or more) before the dialog appears)<br />
					<strong>Hint 2 :</strong> complete your article reference with <a href="http://scholar.google.com/">Google scholar</a>.
				</div>
			</div>
		</div>
	</div>
</div>

<?php
include("footer1_bootstrap.php");
?>
