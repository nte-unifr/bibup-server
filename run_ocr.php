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
	$result = $connexion1->query($query) or die("<br />couldn't execute query");
	while($row = $result->fetch_array())
	{
		$contentOCR = "";
		$titleOCR = "";
		echo($row["id"]);
		$mods = $row["mods"];
		$rdf = $row["rdf"];
		if (!empty($row['contentSnapshot'])) {
			$foldername = substr($row['contentSnapshot'],0,-11);
			$file = "uploads/" . $foldername . "/" . $row['contentSnapshot'];
			$contentOCR = getOCRText($file);
			$mods = str_replace(EXTRACT_OCR_PENDING, XMLClean($contentOCR), $mods);
			$rdf = str_replace(EXTRACT_OCR_PENDING, XMLClean($contentOCR), $rdf);
//			echo $contentOCR;
		}
		if (!empty($row['titleSnapshot'])) {
			$foldername = substr($row['titleSnapshot'],0,-9);
			$file = "uploads/" . $foldername . "/" . $row['titleSnapshot'];
			$titleOCR = getOCRText($file);
			$mods = str_replace(TITLE_OCR_PENDING, XMLClean($titleOCR), $mods);
			$rdf = str_replace(TITLE_OCR_PENDING, XMLClean($titleOCR), $rdf);
//			echo $titleOCR;
		}
		$query = "update fiches set OCRtodo = false";
		if (!empty($contentOCR)) {
			$query .= ", textOCR = '" . addslashes($contentOCR) . "'";
		}
		if (!empty($titleOCR)) {
			$query .= ", titleOCR = '" . addslashes($titleOCR) . "'";
		}
		$query .= ", rdf = '" . addslashes($rdf) . "'";
		$query .= ", mods = '" . addslashes($mods) . "' where id =" . $row["id"];
//		echo $query . "</br>";
		$result1 = $connexion1->query($query) or die("<br />couldn't execute query");
	}
	unlink($running_ocr);
} else {
	echo "OCR is already running";
}
?>
