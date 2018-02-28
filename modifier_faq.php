<?php
	session_name('prio2010_Beta');
	session_start();
	include("includes/fonctions.inc");
	include("header_bootstrap.php");
	$query = "SELECT * FROM $bibup_faq";
	$result = $connexion1->query($query);

	if(isset($_GET['action']))
		$action = $connexion1->real_escape_string($_GET['action']);
	else
		$action = '';

	if(isset($_SESSION['password']) && $action == "modify_faq"){
		if ($_POST['submit'] == 'Save'){
			//efface la valeur du bouton
			unset($_POST['submit']);
			//$date = date("d.m.Y");
			//$message = $_SESSION['username']." MODIFY THE FAQ --> FAQ".", ".$date;
			//append_file("./log/log.txt",$message."\r\n");
			addTopics($_POST);
		}
		redirect("faq.php");
	}

	if(!isset($_SESSION['password'])){
?>
<div class="row">
	<div class="col-xs-12">
		<div class="page-header">
			<h1>FAQ - Edition</h1>
		</div>
		<div class="row">
			<div class="col-xs-5">
				<div class="alert alert-warning" role="alert">
					<h3>Private access</h3>
					<p>Le contenu de cette zone est "privé" et ne peut être accédé que par les personnes ayant droits exceptés.</p>
					<p>Les noms d'utilisateurs et mots de passe ne doivent en aucun cas être redistribués à autrui. En cas de problèmes, n'hésitez pas à contacter l'<a href="mailto:nte@unifr.ch">administrateur</a>.</p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	}else{
?>

<div class="row">
	<div class="col-xs-12">
		<div class="page-header">
			<h1>FAQ - Edition</h1>
		</div>
		<script type="text/javascript">
			function deleteTopic(id){
				var topic = document.getElementById(id);
				// topic.parentNode.removeChild(topic);
				topic.remove();
				var elements = document.getElementById('topicList').getElementsByClassName('entry');
				if (elements.length == 0)
					createTopic();
			}

			function createTopic(){
				var elements = document.getElementById('topicList').getElementsByClassName('entry');
				var id = 1;
				if (elements.length > 0)
					id = parseInt(elements[elements.length - 1].id.substring(5)) + 1;
				var root = document.createElement('div');
				root.id = "topic" + id;
				root.className = "entry";
				//title
				var title = document.createElement('div');
				title.className = "form-group";
				root.appendChild(title);
				var titleText = document.createElement('label');
				titleText.htmlFor = 'title' + id;
				titleText.innerHTML = '<img class="buttonImage" src="images/micro_delete.png" id="image' +id+ '" onclick="deleteTopic(\'topic' +id+ '\');"> Title';
				title.appendChild(titleText);
				var titleInput = document.createElement('input');
				titleInput.className = "form-control";
				titleInput.name = "title" + id;
				titleInput.type = "text";
				title.appendChild(titleInput);
				//description
				var description = document.createElement('div');
				description.className = "form-group";
				root.appendChild(description);
				var descriptionText = document.createElement('label');
				descriptionText.htmlFor = 'description' + id;
				descriptionText.innerHTML = 'Description';
				description.appendChild(descriptionText);
				var input = document.createElement('textarea');
				input.className = "form-control";
				input.name = "description" + id;
				input.rows = 4;
				description.appendChild(input);
				document.getElementById('topicList').appendChild(root);
			}
		</script>

		<form action="modifier_faq.php?action=modify_faq" method="POST">
			<div class="ficheTable" id="topicList">
				<?php
					$i = 1;
					while($row = $result->fetch_array()){
						echo '<div id="topic' . $i . '" class="entry">
							<div class="form-group">
								<label for="title' . $i . '"><img class="buttonImage" src="images/micro_delete.png" id="image'. $i . '" onclick="deleteTopic(\'topic' . $i . '\');"> Title </label>
								<input class="form-control" name="title' . $i . '" type="text" value="'. spec($row['title']) .'" />
							</div>
							<div class="form-group">
								<label for="description' . $i . '">Description </label>
								<textarea rows="4" class="form-control" name="description' . $i . '">' . spec($row['description']) .'</textarea>
							</div>
						</div>';
						$i++;
					}
					if ($i == 1){
						echo '<script type="text/javascript">
							createTopic();
						</script>';
					}
				?>
			</div>
			<div class="buttonImage" style="width:150px;margin:10px 10px;" onclick="createTopic();"><img src="images/mini_add.png"> Add 1 more topic</div>
			<?php
				if(isset($_SESSION['password'])){
			?>
				<button type="submit" name="submit" class="button btn btn-primary" value="Save">Save</button>
				<button type="submit" name="submit" class="button btn btn-default" value="Cancel">Cancel</button>
			<?php
				}
			?>
		</form>
	</div>
</div>
<?php
	}
	include("footer1_bootstrap.php");
?>
