<?php
	session_name('prio2010_Beta');
	session_start();
	include("includes/fonctions.inc");
	include("header.php");
	
	$query = "SELECT * FROM $bibup_faq";
	$result = mysql_query($query);
	

?>
	<!-- start:colonneRight -->
	<div id="content">
	<span align="left"><h1><img src="images/faq.png" alt="FAQ"> FAQ - Frequently asked questions</h1></span><br />
	
		<form action="modifier_faq.php" method=POST>
					
				<?php
					while($row = mysql_fetch_array($result)){
						echo '<table d="mytable" class="tablesorter" cellpadding="1" cellspacing="1">	<thead><tr  style="color:navy;"><th>' . spec($row['title']) .'</th></tr></thead>
							<tbody><tr><td>' . utf8_encode(nl2br(spec($row['description']))) .'</td></tr></tbody><t/able>';
					}
				?>
			</table>
			<?php
				if(isset($_SESSION['password'])){
			?>
				<br /><br /><input type="submit" name="submit" class="button" value="Modify" /><br /><br />
			<?php
				}
			?>	
		</form>
	</div>
<?php
	include("footer.php");
?>