<?php
	session_name('prio2010_Beta');
	session_start();
	include("includes/fonctions.inc");
	include("header.php");
	$query = "SELECT * FROM $bibup_faq";
	$result = mysql_query($query);

	if(isset($_GET['action']))
		$action = mysql_real_escape_string($_GET['action']);
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
	<div id="content" align="center">
		<table border="0" cellpadding="0" cellspacing="15" width="550">
			<tbody>
				<tr>
					<td align="left" valign="top">
						<!-- PARAGRAPH -->
						<p align="justify"><br>
							<table border="0" cellpadding="2" cellspacing="0" width="100%">
								<tbody>
									<tr>
										<td valign="top">
											<h5>Private access</h5>
											<p>Le contenu de cette zone est "privé" et ne peut être accédé que par les personnes ayant droits exceptés.</p>
											<p>Les noms d'utilisateurs et mots de passe ne doivent en aucun cas être redistribués à autrui. En cas de problèmes, n'hésitez pas à contacter l'<a href="mailto:francois.jimenez@unifr.ch">administrateur</a>.</p>
											<br><br><br><br><br><br>
										</td>
									</tr>
								</tbody>
							</table>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
<?php
	}else{
?>
	<!-- start:colonneRight -->
	<div id="content">
		<script type="text/javascript">
			function deleteTopic(id){
				var topic = document.getElementById(id);
				topic.parentNode.removeChild(topic);
				var elements = document.getElementById('topicList').getElementsByTagName('tr');
				if (elements.length == 0)
					createTopic();
			}

			function createTopic(){
				var elements = document.getElementById('topicList').getElementsByTagName('tr');
				var id = 1;
				if (elements.length > 0)
					id = parseInt(elements[elements.length - 1].id.substring(5)) + 1;
				var root = document.createElement('tr');
				root.id = "topic" + id;
				var topic = document.createElement('td');
				topic.style.verticalAlign="top";
				root.appendChild(topic);
				//title
				var header = document.createElement('div');
				header.innerHTML = 'Edit topic';
				header.className = 'ficheTitle';
				topic.appendChild(header);
				topic.appendChild(document.createElement('br'));
				var title = document.createElement('div');
				topic.appendChild(title);
				var titleText = document.createElement('div');
				titleText.innerHTML = 'Title';
				titleText.className = 'ficheSubtitle';
				title.appendChild(titleText);
				var titleInput = document.createElement('input');
				titleInput.className = "form editForm";
				titleInput.name = "title" + id;
				titleInput.style.width="98%";
				titleInput.type = "text";
				title.appendChild(titleInput);
				topic.appendChild(document.createElement('br'));
				//description
				var description = document.createElement('div');
				topic.appendChild(description);
				var descriptionText = document.createElement('div');
				descriptionText.innerHTML = 'Description';
				descriptionText.className = 'ficheSubtitle';
				description.appendChild(descriptionText);
				var input = document.createElement('textarea');
				input.className = "form editForm";
				input.name = "description" + id;
				input.style.width="98%";
				input.rows = 8;
				description.appendChild(input);
				topic.appendChild(document.createElement('br'));
				//remove
				var remove = document.createElement('div');
				topic.appendChild(remove);
				var image = document.createElement('img');
				image.id = "image" + id;
				remove.align = "left";
				remove.appendChild(image);
				image.src = "images/micro_delete.png";
				image.className = "buttonImage";
				image.onclick = function(){
					deleteTopic(root.id);
				};
				//append new entry
				topic.appendChild(document.createElement('br'));
				document.getElementById('topicList').appendChild(root);
			}
		</script>
		<div align="center">
			<h1>FAQ</h1>
		</div>
		<br />
		<form action="modifier_faq.php?action=modify_faq" method=POST>
			<table cellpadding="3" align="center" class="ficheTable" id="topicList">
				<?php
					$i = 1;
					while($row = mysql_fetch_array($result)){
						echo '<tr style="width:100%;" id="topic' . $i . '">
								<td style="vertical-align: top;">
									<div class="ficheTitle">Edit topic</div><br/>
									<div>
										<div class="ficheSubtitle">Title</div>
										<input class="form editForm" name="title' . $i . '" type="text" value="'. spec($row['title']) .'" style="width:98%"/>
									</div>
									<br/>
									<div>
										<div class="ficheSubtitle">Description</div>
										<textarea class="form editForm" name="description' . $i . '" rows="8" style="width:98%">' . spec($row['description']) .'</textarea>
									<div>
									<div align="left">
										<img class="buttonImage" src="images/micro_delete.png" id="image'. $i . '" onclick="deleteTopic(\'topic' . $i . '\');">
									</div>
								</td>
							</tr>';
						$i++;
					}
					if ($i == 1){
						echo '<script type="text/javascript">
							createTopic();
						</script>';
					}
				?>
			</table>
			<div class="buttonImage" style="width:150px;margin:10px 10px;"onclick="createTopic();"><img src="images/mini_add.png"> Add 1 more topic</div>
			<?php
				if(isset($_SESSION['password'])){
			?>
				<br /><br />
				<table width="100%">
					<tr>
						<td width="50%">
							<input type="submit" name="submit" class="button" value="Save" />
						</td>
						<td width="50%">
							<input type="submit" name="submit" class="button" value="Cancel" />
						</td>
					</tr>
				</table>
			<?php
				}
			?>
		</form>
	</div>
<?php
	}
	include("footer.php");
?>