<?php

// Imaging
class imaging
{

    // Variables
    private $img_input;
    private $img_output;
    private $img_src;
    private $format;
    private $quality = 80;
    private $x_input;
    private $y_input;
    private $x_output;
    private $y_output;
    private $resize;

    // Set image
    public function set_img($img)
    {

        // Find format
        $ext = strtoupper(pathinfo($img, PATHINFO_EXTENSION));

        // JPEG image
        if(is_file($img) && ($ext == "JPG" OR $ext == "JPEG"))
        {

            $this->format = $ext;
            $this->img_input = ImageCreateFromJPEG($img);
            $this->img_src = $img;


        }

        // PNG image
        elseif(is_file($img) && $ext == "PNG")
        {

            $this->format = $ext;
            $this->img_input = ImageCreateFromPNG($img);
            $this->img_src = $img;

        }

        // GIF image
        elseif(is_file($img) && $ext == "GIF")
        {

            $this->format = $ext;
            $this->img_input = ImageCreateFromGIF($img);
            $this->img_src = $img;

        }

        // Get dimensions
        $this->x_input = imagesx($this->img_input);
        $this->y_input = imagesy($this->img_input);

    }

    // Set maximum image size (pixels)
    public function set_size($size = 100)
    {

        // Resize
        if($this->x_input > $size && $this->y_input > $size)
        {

            // Wide
            if($this->x_input >= $this->y_input)
            {

                $this->x_output = $size;
                $this->y_output = ($this->x_output / $this->x_input) * $this->y_input;

            }

            // Tall
            else
            {

                $this->y_output = $size;
                $this->x_output = ($this->y_output / $this->y_input) * $this->x_input;

            }

            // Ready
            $this->resize = TRUE;

        }

        // Don't resize
        else { $this->resize = FALSE; }

    }

    // Set image quality (JPEG only)
    public function set_quality($quality)
    {

        if(is_int($quality))
        {

            $this->quality = $quality;

        }

    }


    // Save image
    public function save_img($path)
    {

        // Resize
        if($this->resize)
        {

            $this->img_output = ImageCreateTrueColor($this->x_output, $this->y_output);
            ImageCopyResampled($this->img_output, $this->img_input, 0, 0, 0, 0, $this->x_output, $this->y_output, $this->x_input, $this->y_input);


        }

        // Save JPEG
        if($this->format == "JPG" OR $this->format == "JPEG")
        {

            if($this->resize) { imageJPEG($this->img_output, $path, $this->quality); }
            else { copy($this->img_src, $path); }

        }

        // Save PNG
        elseif($this->format == "PNG")
        {

            if($this->resize) { imagePNG($this->img_output, $path); }
            else { copy($this->img_src, $path); }

        }

        // Save GIF
        elseif($this->format == "GIF")
        {

            if($this->resize) { imageGIF($this->img_output, $path); }
            else { copy($this->img_src, $path); }

        }

    }

    // Get width
    public function get_width()
    {

        return $this->x_input;

    }

    // Get height
    public function get_height()
    {

        return $this->y_input;

    }

    // Clear image cache
    public function clear_cache()
    {

        @ImageDestroy($this->img_input);
        @ImageDestroy($this->img_output);

    }


}


error_reporting(E_ERROR | E_WARNING);
set_time_limit(3600);
include("includes/fonctions.inc");
include("includes/fonctions_biblio.php");
//print_r($_POST);
if (isset($_POST['isbn'])) {
	$file1 = '';
	$text1 = '';
	$file2 = '';
	$text2 = '';
	$userfile_error = $_FILES['contentSnapshot']['error'];
	if ($userfile_error > 0) {
		echo 'Problème: ';
		switch ($userfile_error) {
		  case 1:  echo 'Le fichier dépasse upload_max_filesize';  break;
		  case 2:  echo 'Le fichier dépasse max_file_size';  break;
		  case 3:  echo 'Fichier partiellement chargé';  break;
		  case 4:  echo 'Pas de fichier chargé';  break;
		}
	} else {
		//$uploaddir = './uploads/';
		$fileinfo = pathinfo($_FILES['contentSnapshot']['name']);
		$file1 = str_replace('.','',microtime(true)) . "extrait." . $fileinfo['extension'];
		$uploadfile = $uploaddir . $file1;
		if (move_uploaded_file($_FILES['contentSnapshot']['tmp_name'], $uploadfile)) {

			//$text1 = getOCRText($uploadfile);
			$img = imagecreatefromjpeg($uploadfile);
			$rot = imagerotate($img, -90, 0);
			imagejpeg($rot, $uploadfile, 100);
			if (array_key_exists('titleSnapshot',$_FILES) && $_FILES['titleSnapshot']['error'] === UPLOAD_ERR_OK) {
				$fileinfo = pathinfo($_FILES['titleSnapshot']['name']);
				$file2 = str_replace('.','',microtime(true)) . "title." . $fileinfo['extension'];
				$uploadfile = $uploaddir . $file2;
				move_uploaded_file($_FILES['titleSnapshot']['tmp_name'], $uploadfile);

				//$text2 = getOCRText($uploadfile);
				$img = imagecreatefromjpeg($uploadfile);
				$rot = imagerotate($img, -90, 0);
				imagejpeg($rot, $uploadfile, 100);

			}

		}
		
		
	
$amount=0;
$message ="";
$isbn = $_POST['isbn'];

//$MAX_FILE_SIZE = $_POST['MAX_FILE_SIZE'];
$userfile_error = $_FILES['contentSnapshot']['error'];

createFolders($isbn);

$repertoiredestination = "uploads/".$isbn."/";
$repertoiredestination_thumb = "uploads/".$isbn."/thumb/";


$nomdestination = $_FILES["contentSnapshot"]["name"];

// une image uniquement
if($amount < 1) {

	$target_path = $repertoiredestination;
	$target_path = $target_path . basename( $_FILES['contentSnapshot']['name']);

	$ext = substr($_FILES['contentSnapshot']['name'], -3);
	$newfilename = $isbn.".".$ext;

			if(move_uploaded_file($_FILES['contentSnapshot']['tmp_name'], $target_path) && ($ext == 'jpg' || $ext == 'png')) {
				//Traitement de l'image
				$src = $repertoiredestination.$nomdestination;
				
				$arrayImage = getimagesize($src);
				$image_x = $arrayImage[0];
				
				
				// Traitement
				$img = new imaging;
				$img->set_img($src);
				$img->set_quality(90);

				// image 1 > thumb
				$img->set_size(80);
				$img->save_img($repertoiredestination_thumb.$nomdestination);
				
				
				
				/*if($image_x <= 205){
				
				// image 2 > fiche
				$img->set_size($image_x);
				$img->save_img($repertoiredestination_fiche.$nomdestination);

				// image 3 > ppt
				$img->set_size($image_x);
				$img->save_img($repertoiredestination_ppt.$nomdestination);
				}else{
				
				//cherche le bon facteur pour diminuer la taille de l image
				$i=1;
				while($image_x/$i > 205){
					$i = $i + 1;
				}*/
				
				// image 2 > fiche
				//$img->set_size($image_x/$i);
				//$img->save_img($repertoiredestination_fiche.$nomdestination);
				
				// image 3 > ppt
				/*if($i <=4){
				
				$img->set_size($image_x/($i/2));
				$img->save_img($repertoiredestination_ppt.$nomdestination);				
				}else{
					$img->set_size($image_x/($i/4));
					$img->save_img($repertoiredestination_ppt.$nomdestination);			
				}
				// On vide le cache et on continue
				$img->clear_cache();
				}*/
				
				//}
				
				//rename de l'image dans les répertoires
				/*rename($target_path,$repertoiredestination.$newfilename);
				rename($repertoiredestination_thumb.$nomdestination,$repertoiredestination_thumb.$newfilename);
				rename($repertoiredestination_fiche.$nomdestination,$repertoiredestination_fiche.$newfilename);
				rename($repertoiredestination_ppt.$nomdestination,$repertoiredestination_ppt.$newfilename);
				*/

				//ajoute l'image a la table images
				/*$image_id = insertImageIntoDB($nomdestination);
				updateImageFiche($id,$image_id);*/

				//change le nom de l image dans la table
				//setImageName(getImageID($id),$newfilename);
				$message = "L'image a été téléchargée avec succès.<br /><br />";

				//redirect("fiche.php?id=$id",1);
				
				}//fin du if error
				
			} else{
				$message = "Le fichier ne peut pas être téléchargé. Veuillez vérifier sa taille (plus grande que 6Mo ?)  ou son format (uniquement .jpg oder .png). Le nom du fichier existe peut-être déjà ou est incorrect.<br /><br />";
			}

		} else {
			$message = "Le fichier ne peut pas être téléchargé. Veuillez vérifier , si le répertoir ".$repertoiredestination." existe vraiment.<br />Le nom du fichier existe peut-être déjà ou est incorrect.<br /><br />";
		}

	 
	
	/*if ($message != "") {
	?>
	<h2><a href="search.php"><?=$titre_projet?></a>&nbsp;>&nbsp;<a href="fiche.php?id=<?=$id?>">fiche no <?=$id?></a>&nbsp;>&nbsp;Ajouter une image</h2><br />
	<div id="content">
	<table id="mytable" class="tablesorter" cellpadding="1" cellspacing="1" >
		<thead>
			<tr>
				<th colspan="2" style="color:navy;"><strong>AJOUTER UNE IMAGE</strong><br /></th>
			</tr>
			<tbody>
			<tr>
				<td align="center"><h2><?=$message?></h2></td>
 		    </tr>
 		    </tbody>
 		</thead>
	</table>

	<br /><br />
	<?php
	
	}//	if ($message != "") {*/

		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}

	$isbn = ($_POST['isbn']);
	if ((substr($isbn,0,3) == "978") || (strlen($isbn) == 10)) {
		$data = json_data_from_isbn($isbn);
		if ($data->stat == 'ok') {
			$fiche['title'] = mysql_real_escape_string($data->list[0]->title);
			$fiche['isbn'] = $data->list[0]->isbn[0];
			$fiche['auteur'] = mysql_real_escape_string($data->list[0]->author);
	//		$fiche['coin'] = coin_from_data($data);
			$data->list[0]->file1 = $file1;
			$data->list[0]->file2 = $file2;
			$data->list[0]->text1 = $text1;
			$data->list[0]->text2 = $text2;
//			$data->list[0]->file1 = $file1;
			$fiche['mods'] = mysql_real_escape_string(mods_from_json_data_isbn($data));
			$fiche['contentSnapshot'] = $file1;
			$fiche['titleSnapshot'] = $file2;
	//		print_r($fiche);
			$query = sql_insert('fiches',$fiche);
	//		echo $query;
			$result = mysql_query($query);
			if ($result) {
				echo "##ok##";
			} else {
				echo "Problème lors de l'ajout de la fiche";
			}
		} else {
			echo 'isbn not found';
		}
	} else {
		$data = json_data_from_issn($isbn);
//		print_r($data);
		if ($data->stat == 'ok') {
			$fiche['title'] = mysql_real_escape_string($data->group[0]->list[0]->title);
			$fiche['isbn'] = $isbn;
//			$fiche['auteur'] = '';
			$data->group[0]->file1 = $file1;
			$data->group[0]->file2 = $file2;
			$data->group[0]->text1 = $text1;
			$data->group[0]->text2 = $text2;
			$fiche['mods'] = mysql_real_escape_string(mods_from_json_data_issn($data));
			$fiche['contentSnapshot'] = $file1;
			$fiche['titleSnapshot'] = $file2;
//			print_r($fiche);
			$query = sql_insert('fiches',$fiche);
//			echo $query;
			$result = mysql_query($query);
			if ($result) {
				echo "##ok##";
			} else {
				echo "Problème lors de l'ajout de la fiche";
			}
		} else {
			echo 'isbn not found';
		}
	}
} else {
   echo "no isbn";
}
?>
