<?php
include ("connect.php");
//variables
$titre_projet = "BibUp";

/*
    Imagerotate replacement. ignore_transparent is work for png images
    Also, have some standard functions for 90, 180 and 270 degrees.
    Rotation is clockwise
*/
if(!function_exists("imagerotate")) {
    function imagerotate($src_img, $angle) {
        $src_x = imagesx($src_img);
        $src_y = imagesy($src_img);
        if ($angle == 180) {
            $dest_x = $src_x;
            $dest_y = $src_y; }
        elseif ($src_x <= $src_y) {
            $dest_x = $src_y;
            $dest_y = $src_x; }
        elseif ($src_x >= $src_y) {
            $dest_x = $src_y;
            $dest_y = $src_x; }

        $rotate=imagecreatetruecolor($dest_x,$dest_y);
        imagealphablending($rotate, false);

        switch ($angle) {
            case 270:
                for ($y = 0; $y < ($src_y); $y++) {
                    for ($x = 0; $x < ($src_x); $x++) {
                        $color = imagecolorat($src_img, $x, $y);
                        imagesetpixel($rotate, $dest_x - $y - 1, $x, $color); }}
                break;
            case 90:
                for ($y = 0; $y < ($src_y); $y++) {
                    for ($x = 0; $x < ($src_x); $x++) {
                        $color = imagecolorat($src_img, $x, $y);
                        imagesetpixel($rotate, $y, $dest_y - $x - 1, $color); }}
                break;
            case 180:
                for ($y = 0; $y < ($src_y); $y++) {
                    for ($x = 0; $x < ($src_x); $x++) {
                        $color = imagecolorat($src_img, $x, $y);
                        imagesetpixel($rotate, $dest_x - $x - 1, $dest_y - $y - 1, $color); }}
                break;
            default: $rotate = $src_img; }
        return $rotate; }}


function isMobileBrowser()
{
 return strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'android');
}


function sql_insert($table,$fields,$protect=false)
{
	$protector = ($protect) ? '`' : ''; // Accent grave : ALT 96
	$name_separator = $protector.', '.$protector;

	$delimiter = "'"; // Apostrophe
	$value_separator = $delimiter.', '.$delimiter;

	// Ajoute les slashs aux valeurs si nécessaire
	foreach($fields as $key => $value)
	{
		$fields[$key]=$value;
	}

	$sql  = 'INSERT INTO '.$protector.$table.$protector;
	$sql .= ' ('.$protector.implode($name_separator, array_keys($fields)).$protector.')';
	$sql .= ' VALUES ('.$delimiter.implode($value_separator, $fields).$delimiter.')';
	return $sql;
}

function sql_update($table,$fields,$protect=false,$id)
{
	$counter = 0;
	$protector = ($protect) ? '`' : ''; // Accent grave : ALT 96
	$name_separator = $protector.', '.$protector;

	$delimiter = "'"; // Apostrophe
	$value_separator = $delimiter.', '.$delimiter;

	// Ajoute les slashs aux valeurs si nécessaire
	foreach($fields as $key => $value)
	{
		$fields[$key]=$value;
	}

	$sql  = 'UPDATE '.$protector.$table.$protector.' SET ';
	foreach($fields as $key=> $val){
		$counter++;
		$sql .= $key.' = '.$delimiter.$val.$delimiter;
		if($counter < count($fields)){
			$sql .= ', ';
		}else{}

	}
	$sql .= " WHERE id = '".$id."'";
	return $sql;
}

function append_file($filename,$newdata) {
	$f=fopen($filename,"a");
	fwrite($f,$newdata);
	fclose($f);
}






/*************************************** FONCTIONS SYSTEME ***************************************************/

function despec($string){
	return addslashes($string);
}


function redirect($url){
	echo "<META http-equiv=\"Refresh\" content=\"0;URL=".$url."\">";
}

function addTopics($post){
	global $bibup_faq;
	//add references
	$sql = "";
	foreach ($post as $key => $value){
		$root = substr($key, 0, 5);
		if ($root == "title"){
			$refId = substr($key, 5);
			if ($post['title' . $refId] != '' || $post['description' . $refId] != ''){
				if ($sql != '')
					$sql .= ", ";
				$sql .= "(null, '" . despec($post['title' . $refId]) . "', '" . despec($post['description' . $refId]) . "')";
			}
		}
	}
	$connexion1->query("TRUNCATE " .  $bibup_faq) or die ($connexion1->error()."<br />couldn't execute query: $query");
	if ($sql != ''){
		$sql = "INSERT INTO ". $bibup_faq . " (`id`, `title`, `description`) VALUES " . $sql . ";";
		$result = $connexion1->query($sql) or die ($connexion1->error()."<br />couldn't execute query: $query");
	}
}


function spec($string){
	return stripslashes($string);
}

function createRequest($arrayvar,$table){

	$requete = "select * from $table ";
	$first_notion = true;
	$where = true;
	$and = true;

	foreach($arrayvar as $k => $v) {
		if ($k != "" && $v != ""){
			$keys[$k] = $v;
		}
	}


	foreach($keys as $cle => $valeur) {
		if($valeur != ""){
			$and = true;
		}

		if($valeur != "" && $where){
			$requete = $requete." where ";
			$where = false;
			//$and = true;
		}
		if (!$first_notion && $and) {
			$requete = $requete . " AND ";
			$and = false;
		} else {
			$first_notion = false;
		}
		if ($valeur != "") {
			$first_notion = false;
			$requete = $requete . "$cle LIKE '%" . reslash($valeur) . "%'";
		} else {
			$first_notion = true;
			//$requete = substr_replace($requete, "", -4, -1);
		}
	}

	$requete = $requete . " ORDER BY nom ASC";
	return $requete;
}

function createRequestUnique($arrayvar,$table){

	$requete = "select * from $table ";
	$first_notion = true;
	$where = true;
	$and = true;

	foreach($arrayvar as $k => $v) {
		if ($k != "" && $v != ""){
			$keys[$k] = $v;
		}
	}


	foreach($keys as $cle => $valeur) {
		if($valeur != ""){
			$and = true;
		}

		if($valeur != "" && $where){
			$requete = $requete." where ";
			$where = false;
			//$and = true;
		}
		if (!$first_notion && $and) {
			$requete = $requete . " AND ";
			$and = false;
		} else {
			$first_notion = false;
		}
		if ($valeur != "") {
			$first_notion = false;
			$requete = $requete . "$cle = '" . reslash($valeur) . "'";
		} else {
			$first_notion = true;
			//$requete = substr_replace($requete, "", -4, -1);
		}
	}

	$requete = $requete . " ORDER BY nom ASC";
	return $requete;
}



function getCounterPosition($array,$id){

	$key = array_search($id, $array);
	return $key;

}

function reslash($string)
{
	if (!get_magic_quotes_gpc())
	$string = addslashes($string);
	return $string;
}


// This function takes out slashes if if the magic quotes are on.
function deslash($string)
{
	if (get_magic_quotes_gpc())
	$string = stripslashes($string);
	return $string;
}

/**************************************************/


function write_file($filename,$newdata) {
	$f=fopen($filename,"w");
	fwrite($f,$newdata);
	fclose($f);
}

function read_file($filename) {
	$f=fopen($filename,"r");
	$data=fread($f,filesize($filename));
	fclose($f);
	return $data;
}


function getFields($table,$database="")
{
	if (!$database) $database=$GLOBALS['currentdb'];
	$fields = $connexion1->list_fields($database,$GLOBALS[tp].$table) or die ($connexion1->error());
	$columns = $connexion1->num_fields($fields);
	$arr=array();
	for ($i = 0; $i < $columns; $i++) {
		$fieldname=$connexion1->field_name($fields, $i);
		$arr[$fieldname]=1;
	}
	return $arr;
}

function getFieldList($DB, $Table) {

	$fldlist = $connexion1->list_fields($DB, $Table);
	$columns = $connexion1->num_fields($fldlist);

	for ($i = 0; $i < $columns; $i++) {
		$Listing[] = $connexion1->field_name($fldlist, $i);
	}

	return ($Listing);
}


//Simple class to fetch a HTTP URL. Supports "Location:"-redirections. Useful for servers with allow_url_fopen=false (c'est le cas chez Philippe). Works with SSL-secured hosts.
class HTTPRequest
{
   var $_fp;        // HTTP socket
   var $_url;        // full URL
   var $_host;        // HTTP host
   var $_protocol;    // protocol (HTTP/HTTPS)
   var $_uri;        // request URI
   var $_port;        // port

   // scan url
   function _scan_url()
   {
       $req = $this->_url;

       $pos = strpos($req, '://');
       $this->_protocol = strtolower(substr($req, 0, $pos));

       $req = substr($req, $pos+3);
       $pos = strpos($req, '/');
       if($pos === false)
           $pos = strlen($req);
       $host = substr($req, 0, $pos);

       if(strpos($host, ':') !== false)
       {
           list($this->_host, $this->_port) = explode(':', $host);
       }
       else
       {
           $this->_host = $host;
           $this->_port = ($this->_protocol == 'https') ? 443 : 80;
       }

       $this->_uri = substr($req, $pos);
       if($this->_uri == '')
           $this->_uri = '/';
   }

   // constructor
   function HTTPRequest($url)
   {
       $this->_url = $url;
       $this->_scan_url();
   }

   // download URL to string
   function DownloadToString()
   {
       $crlf = "\r\n";
       $response = "";

       // generate request
       $req = 'GET ' . $this->_uri . ' HTTP/1.0' . $crlf
           .    'Host: ' . $this->_host . $crlf
           .    $crlf;

       // fetch
       $this->_fp = fsockopen(($this->_protocol == 'https' ? 'ssl://' : '') . $this->_host, $this->_port);
       fwrite($this->_fp, $req);
       while(is_resource($this->_fp) && $this->_fp && !feof($this->_fp))
           $response .= fread($this->_fp, 1024);
       fclose($this->_fp);

       // split header and body
       $pos = strpos($response, $crlf . $crlf);
       if($pos === false)
           return($response);
       $header = substr($response, 0, $pos);
       $body = substr($response, $pos + 2 * strlen($crlf));

       // parse headers
       $headers = array();
       $lines = explode($crlf, $header);
       foreach($lines as $line)
           if(($pos = strpos($line, ':')) !== false)
               $headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1));

       // redirection?
       if(isset($headers['location']))
       {
           $http = new HTTPRequest($headers['location']);
           return($http->DownloadToString($http));
       }
       else
       {
           return($body);
       }
   }
}

function xml2array($originalXML, $attributes=true)
{
        $xmlArray = array();
        $search = $attributes ? '|<((\S+)(.*))\s*>(.*)</\2>|Ums' : '|<((\S+)()).*>(.*)</\2>|Ums';

        // normalize data
        $xml = preg_replace('|>\s*<|', ">\n<", $originalXML); // one tag per line
        $xml = preg_replace('|<\?.*\?>|', '', $xml);            // remove XML declarations
        $xml = preg_replace('|<(\S+?)(.*)/>|U', '<$1$2></$1>', $xml); //Expand singletons

        if (! preg_match_all($search, $xml, $xmlMatches))
                return trim($originalXML);      // bail out - no XML found

        foreach ($xmlMatches[1] as $index => $key)
        {
                if (! isset($xmlArray[$key])) $xmlArray[$key] = array();
                $xmlArray[$key][] = xml2array($xmlMatches[4][$index], $attributes);
        }
        return $xmlArray;
}

function XMLClean($strin) {
        $strout = null;

        for ($i = 0; $i < strlen($strin); $i++) {
                $ord = ord($strin[$i]);

                if (($ord > 0 && $ord < 32) || ($ord >= 127)) {
                        $strout .= "&amp;#{$ord};";
                }
                else {
                        switch ($strin[$i]) {
                                case '<':
                                        $strout .= '&lt;';
                                        break;
                                case '>':
                                        $strout .= '&gt;';
                                        break;
                                case '&':
                                        $strout .= '&amp;';
                                        break;
                                case '"':
                                        $strout .= '&quot;';
                                        break;
                                default:
                                        $strout .= $strin[$i];
                        }
                }
        }
		return htmlspecialchars($strin);
        return $strout;
}


function createFolders($foldername){

	$repertoiredestination = "uploads/".$foldername."/";
	$repertoiredestination_thumb = "uploads/".$foldername."/thumb/";


	$folderExists = is_dir($repertoiredestination);
	if($folderExists === false) {
	$old = umask(0);
	mkdir($repertoiredestination, 0777);
	umask($old);
	}

	$folderExists_thumb = is_dir($repertoiredestination_thumb);
	if($folderExists_thumb === false) {
	$old = umask(0);
	mkdir($repertoiredestination_thumb, 0777);
	umask($old);
	}


}

function truncate_string($details,$max)
{
    if(strlen($details)>$max)
    {
        $details = substr($details,0,$max);
        $i = strrpos($details," ");
        $details = substr($details,0,$i);
        $details = $details." ...";
    }
    return $details;
}

?>
