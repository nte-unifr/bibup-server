<?php
    session_name('prio2010_Beta');
    session_start();
    include("includes/fonctions.inc");
    include("header_bootstrap.php");

    $query = "SELECT * FROM $bibup_faq";
    $result = mysql_query($query);
?>

<div class="row">
	<div class="col-xs-12">
		<div class="page-header">
			<h1>FAQ - Frequently asked questions</h1>
		</div>

        <?php
            while($row = mysql_fetch_array($result))
            {
                echo '<div class="panel panel-default">
                        <div class="panel-heading"><strong>';
                echo spec($row['title']);
                echo '</strong></div>
                        <div class="panel-body">';
                echo utf8_encode(nl2br(spec($row['description'])));
                echo '</div>
                    </div>';

            }
        ?>
	</div>
</div>

<?php
    include("footer1_bootstrap.php");
?>
