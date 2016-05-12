<?php

// Gestion des images
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

//error_reporting(E_ERROR | E_WARNING);
//set_time_limit(3600);
include("includes/fonctions.inc");
include("includes/fonctions_biblio.php");
//print_r($_POST);
if (isset($_POST['isbn'])) {
	$file1 = '';
	$text1 = '';
	$file2 = '';
	$text2 = '';
	$OCRtodo = false;
	$theTime = microtime(true);

	$foldername = str_replace('.','',microtime(true));
	$repertoiredestination = "uploads/".$foldername."/";
	$repertoiredestination_thumb = "uploads/".$foldername."/thumb/";

	if (array_key_exists('contentSnapshot',$_FILES) && $_FILES['contentSnapshot']['error'] === UPLOAD_ERR_OK) {
		$fileinfo = pathinfo($_FILES['contentSnapshot']['name']);
		if($_FILES['contentSnapshot']['name'] == "imagebiblio.jpg") {
			$file1 = $foldername . "extrait." . $fileinfo['extension'];

			$uploadfile = $repertoiredestination . $file1;
			$uploadfile_thumb = $repertoiredestination_thumb . $file1;

			createFolders($foldername);

			if (move_uploaded_file($_FILES['contentSnapshot']['tmp_name'], $uploadfile)) {

				//Traitement de l'image
				$src = $uploadfile;

				$arrayImage = getimagesize($src);
				$image_x = $arrayImage[0];

				// Traitement
				$img = new imaging;
				$img->set_img($src);
				$img->set_quality(90);

				// image 1 > thumb
				$img->set_size(80);
				$img->save_img($uploadfile_thumb);

				if ($_FILES['contentSnapshot']['name'] == "imagebiblio.jpg") {
					if (substr($_SERVER['REMOTE_ADDR'],0,7) != '134.21.') {
						$text1 = NO_OCR;
					} else {
						$OCRtodo = true;
						$text1 = EXTRACT_OCR_PENDING;
					}
				}
				$img = imagecreatefromjpeg($uploadfile);


				//$rot = imagerotate($img, -90, 0);
				imagejpeg($img, $uploadfile, 100);

				$img = imagecreatefromjpeg($uploadfile_thumb);
				//$rot = imagerotate($img, -90, 0);
				imagejpeg($img, $uploadfile_thumb, 100);
			}
		}
	}

	if (array_key_exists('titleSnapshot',$_FILES) && $_FILES['titleSnapshot']['error'] === UPLOAD_ERR_OK) {
		$fileinfo2 = pathinfo($_FILES['titleSnapshot']['name']);
		if ($_FILES['titleSnapshot']['name'] == "imagebiblio1.jpg") {
			$file2 = $foldername."title." . $fileinfo2['extension'];

			$uploadfile2 = $repertoiredestination . $file2;
			$uploadfile_thumb2 = $repertoiredestination_thumb . $file2;

			createFolders($foldername);

			move_uploaded_file($_FILES['titleSnapshot']['tmp_name'], $uploadfile2);

			//Traitement de l'image
			$src = $uploadfile2;

			$arrayImage = getimagesize($src);
			$image_x = $arrayImage[0];

			// Traitement
			$img = new imaging;
			$img->set_img($src);
			$img->set_quality(90);

			// image 1 > thumb
			$img->set_size(80);
			$img->save_img($uploadfile_thumb2);

			if ($_FILES['titleSnapshot']['name'] == "imagebiblio1.jpg") {
				if (substr($_SERVER['REMOTE_ADDR'],0,7) != '134.21.') {
					$text2 = NO_OCR;
				} else {
					$OCRtodo = true;
					$text2 = TITLE_OCR_PENDING;
				}
			}

			$img = imagecreatefromjpeg($uploadfile2);
			//$rot = imagerotate($img, -90, 0);
			imagejpeg($img, $uploadfile2, 100);

			$img = imagecreatefromjpeg($uploadfile_thumb2);
			//$rot = imagerotate($img, -90, 0);
			imagejpeg($img, $uploadfile_thumb2, 100);
		}

	}

    $isbn = ($_POST['isbn']);
    if ($_POST['uid'] == NULL || !isset($_POST['uid'])) {
        if ((substr($isbn,0,3) == "978") || (strlen($isbn) == 10)) {
    		$data = json_data_from_isbn($isbn);
    		if ($data->stat == 'ok') {
    			$fiche['ip'] = $_SERVER['REMOTE_ADDR'];
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
                $fiche['rdf'] = mysql_real_escape_string(rdf_from_json_data($data, 'isbn'));
    			$fiche['contentSnapshot'] = $file1;
    			$fiche['textOCR'] = addslashes($text1);
    			$fiche['titleSnapshot'] = $file2;
    			$fiche['titleOCR'] = addslashes($text2);
    			$fiche['OCRtodo'] = $OCRtodo;
    			if (!empty($_POST['tag'])) {
    				$fiche['tag'] = mysql_real_escape_string($_POST['tag']);
    			};
    			$query = sql_insert('fiches',$fiche);
    //			echo $query;
    			$result = mysql_query($query);
    			$insert_id = mysql_insert_id();
    			if ($result) {
    				echo trim("##ok##".$insert_id);
    			} else {
    				echo "Problème lors de l'ajout de la fiche";
    			}
    		} else {
    			echo 'isbn not found';
    		}
    	} else {
    		$data = json_data_from_issn($isbn);

    		if ($data->stat == 'ok') {
    			$fiche['ip'] = $_SERVER['REMOTE_ADDR'];
    			$fiche['title'] = mysql_real_escape_string($data->group[0]->list[0]->title);
    			$fiche['isbn'] = $isbn;
    //			$fiche['auteur'] = '';
    			$data->group[0]->file1 = $file1;
    			$data->group[0]->file2 = $file2;
    			$data->group[0]->text1 = $text1;
    			$data->group[0]->text2 = $text2;
    			$fiche['mods'] = mysql_real_escape_string(mods_from_json_data_issn($data));
                $fiche['rdf'] = mysql_real_escape_string(rdf_from_json_data($data, 'issn'));
    			$fiche['contentSnapshot'] = $file1;
    			$fiche['textOCR'] = addslashes($text1);
    			$fiche['titleSnapshot'] = $file2;
    			$fiche['titleOCR'] = addslashes($text2);
    			$fiche['OCRtodo'] = $OCRtodo;
    			if (!empty($_POST['tag'])) {
    				$fiche['tag'] = mysql_real_escape_string($_POST['tag']);
    			};
    			$query = sql_insert('fiches',$fiche);
    //			echo $query;
    			$result = mysql_query($query);
    			$insert_id = mysql_insert_id();
    			if ($result) {
    				echo trim("##ok##".$insert_id);
    			} else {
    				echo "Problème lors de l'ajout de la fiche";
    			}
    		} else {
    			echo 'isbn not found';
    		}
    	}
    } else { //update existing entry
        $up_id = $_POST['uid'];
        if ((substr($isbn,0,3) == "978") || (strlen($isbn) == 10)) {
    		$data = json_data_from_isbn($isbn);
    		if ($data->stat == 'ok') {
                $res = mysql_query("SELECT contentSnapshot FROM fiches WHERE id = " . $up_id);
    			$data->list[0]->file1 = mysql_result($res, 0);//$file1; //get from db
    			$data->list[0]->file2 = $file2;
                $res = mysql_query("SELECT textOCR FROM fiches WHERE id = " . $up_id);
    			$data->list[0]->text1 = mysql_result($res, 0);//$text1; //get from db
    			$data->list[0]->text2 = $text2;
    			$fiche['mods'] = mysql_real_escape_string(mods_from_json_data_isbn($data));
                $fiche['rdf'] = mysql_real_escape_string(rdf_from_json_data($data, 'isbn'));
    			$fiche['titleSnapshot'] = $file2;
    			$fiche['titleOCR'] = addslashes($text2);
                if ($OCRtodo == true) {
                    $fiche['OCRtodo'] = $OCRtodo;
                }
    			$query = sql_update('fiches',$fiche,true, $up_id);
    			$result = mysql_query($query);
    			if ($result) {
    				echo trim("##okup##".$up_id);
    			} else {
    				echo "Problème lors de modification de la fiche";
    			}
    		} else {
    			echo 'isbn not found';
    		}
    	} else {
    		$data = json_data_from_issn($isbn);

    		if ($data->stat == 'ok') {
                $res = mysql_query("SELECT contentSnapshot FROM fiches WHERE id = " . $up_id);
    			$data->group[0]->file1 = mysql_result($res, 0);//$file1; //get from db
    			$data->group[0]->file2 = $file2;
                $res = mysql_query("SELECT textOCR FROM fiches WHERE id = " . $up_id);
    			$data->group[0]->text1 = mysql_result($res, 0);//$text1; //get from db
    			$data->group[0]->text2 = $text2;
    			$fiche['mods'] = mysql_real_escape_string(mods_from_json_data_issn($data));
                $fiche['rdf'] = mysql_real_escape_string(rdf_from_json_data($data, 'issn'));
    			$fiche['titleSnapshot'] = $file2;
    			$fiche['titleOCR'] = addslashes($text2);
                if ($OCRtodo == true) {
                    $fiche['OCRtodo'] = $OCRtodo;
                }
    			$query = sql_update('fiches',$fiche, true, $up_id);
                $result = mysql_query($query);
    			if ($result) {
    				echo trim("##okup##".$up_id);
    			} else {
    				echo "Problème lors de modification de la fiche";
    			}
    		} else {
    			echo 'isbn not found';
    		}
    	}
    }

} else {
   echo "no isbn";
}
?>
