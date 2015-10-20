<?php
$userfile_error = $_FILES['userfile']['error'];
if ($userfile_error > 0) {
	echo 'Problème: ';
    switch ($userfile_error) {
      case 1:  echo 'Le fichier dépasse upload_max_filesize';  break;
      case 2:  echo 'Le fichier dépasse max_file_size';  break;
      case 3:  echo 'Fichier partiellement chargé';  break;
      case 4:  echo 'Pas de fichier chargé';  break;
    }
} else {

	$uploaddir = './uploads/'; 
	$file = basename($_FILES['userfile']['name']); 
	$uploadfile = $uploaddir . $file; 
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) { 
		echo $file . "<br>"; 
	}
}
echo "ISBN : " . $_POST['isbn'];
?>