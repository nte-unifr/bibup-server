<?php
    session_name('prio2010_Beta');
    session_start();
    include("includes/fonctions.inc");
    include("header_bootstrap.php");

    $query = "SELECT * FROM $bibup_faq";
    $result = $connexion1->query($query);
?>

<div class="row">
	<div class="col-xs-12">
		<div class="page-header">
			<h1>FAQ - Frequently asked questions</h1>
            <form action="modifier_faq.php" method="POST">
    			<?php
    				if(isset($_SESSION['password'])){
    			?>
                    <button type="submit" class="btn btn-info">Modify</button>
    			<?php
    				}
    			?>
    		</form>
		</div>

        <?php
            while($row = $result->fetch_array())
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
