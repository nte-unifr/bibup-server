<?php
echo date(DATE_RFC822) . "\n";
include("includes/fonctions.inc");
include("includes/fonctions_biblio.php");
$running_ocr = "uploads/ocr_running";
if (!file_exists($running_ocr)) {
/*	$dummy = fopen($running_ocr,"w");
	fclose($dummy);*/
	touch($running_ocr);
	$query = "select * from fiches where OCRtodo = TRUE";
//	echo $query;
	$result = mysql_query($query) or die("<br />couldn't execute query");
	while($row = mysql_fetch_array($result))
	{
		$contentOCR = "";
		$titleOCR = "";
		echo($row["id"]);
		$mods = $row["mods"];
		if (!empty($row['contentSnapshot'])) {
			$foldername = substr($row['contentSnapshot'],0,-11);
			$file = "uploads/" . $foldername . "/" . $row['contentSnapshot'];
			$contentOCR = getOCRText($file);
			$mods = str_replace(EXTRACT_OCR_PENDING, XMLClean($contentOCR), $mods);
//			echo $contentOCR;
		}
		if (!empty($row['titleSnapshot'])) {
			$foldername = substr($row['titleSnapshot'],0,-9);
			$file = "uploads/" . $foldername . "/" . $row['titleSnapshot'];
			$titleOCR = getOCRText($file);
			$mods = str_replace(TITLE_OCR_PENDING, XMLClean($titleOCR), $mods);
//			echo $titleOCR;
		}
		$query = "update fiches set OCRtodo = false";
		if (!empty($contentOCR)) {
			$query .= ", textOCR = '" . addslashes($contentOCR) . "'";
		}
		if (!empty($titleOCR)) {
			$query .= ", titleOCR = '" . addslashes($titleOCR) . "'";
		}
		$query .= ", mods = '" . addslashes($mods) . "' where id =" . $row["id"];
//		echo $query . "</br>";
		$result1 = mysql_query($query) or die("<br />couldn't execute query");
	}
	unlink($running_ocr);
} else {
	echo "OCR is already running";
}
?>
