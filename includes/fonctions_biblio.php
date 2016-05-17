<?php

define("TITLE_OCR_PENDING","##Title OCR pending##");
define("EXTRACT_OCR_PENDING","##Extract OCR pending##");
define("NO_OCR","No OCR is performed for users from outside the University of Fribourg. If you are registered at the University of Fribourg don't forget to use VPN connection.");

//include('dBug.php');
/*
OpenURL() constructs an NISO Z39.88 compliant ContextObject for use in OpenURL links and COinS.  It returns
the proper query string, which you must embed in a <span></span> thus:

<span class="Z3988" title="<?php print OpenURL($Document, $People) ?>">Content of your choice goes here</span>

This span will work with Zotero. You can also use the output of OpenURL() to link to your library's OpenURL resolver, thus:

<a href="http://www.lib.utexas.edu:9003/sfx_local?<?php print OpenURL($Document, $People); ?>" title="Search for a copy of this document in UT's libraries">Find it at UT!</a>

Replace "http://www.lib.utexas.edu:9003/sfx_local?" with the correct resolver for your library.

OpenURL() takes two arguments.

$Document - a document object, having an array (fields) with the following properties:
	$Document->fields["DocType"]
		1 = Article
		2 = Book Item (e.g. a chapter, section, etc)
		3 = Book
		4 = Unpublished MA thesis
		5 = Unpublished PhD thesis

	$Document->fields["DocTitle"] - Title of the document.
	$Document->fields["JournalTitle"] - Title of the journal/magazine the article was published in, or false if this is not an article.

	$Document->fields["BookTitle"] - Title of the book in which this item was published, or false if this is not a book item.

	$Document->fields["Volume"] - The volume of the journal this article was published in as an integer, or false if this is not an article.  Optional.
	$Document->fields["JournalIssue"] - The issue of the journal this article was published in as an integer, or false if this is not an article.  Optional.
	$Document->fields["JournalSeason"] Optional.
		The season of the journal this article was published in, as a string, where:
			Spring
			Summer
			Fall
			Winter
			false = not applicable
	$Document->fields["JournalQuarter"] - The quarter of the journal this article was published in as an integer between 1 and 4, or false. Optional.
	$Document->fields["ISSN"] - The volume of the journal this article was published in, or false.  Optional.


	$Document->fields["BookPublisher"] - The publisher of the book, or false. Optional.
	$Document->fields["PubPlace"] - The publication place, or false.  Optional.
	$Document->fields["ISBN"] - The ISBN of the book.  Optional but highly recommended.

	$Document->fields["StartPage"] - Start page for the article or item, or false if this is a complete book.
	$Document->fields["EndPage"] - End page for the article or item, or false if this is a complete book.

$Document->fields["DocYear"] - The year in which this document was published.

$People - An array of person objects, each having an array, fields, with these properties:
	$People->fields["DocRelationship"]
		An integer indicating what kind of relationship the person has to this document.
		0 = author
		1 = editor
		2 = translator
	$People->fields["FirstName"] - The person's first name.
	$People->fields["LastName"] - The person's last name.
*/
function OpenURL($Document, $People){
	$DocType = $Document["DocType"];
	if($DocType > 2){ return false; }

	// Base of the OpenURL specifying which version of the standard we're using.
	$URL = "ctx_ver=Z39.88-2004";

	// Metadata format - e.g. article or book.
	if($DocType == 0){ $URL .= "&amp;rft_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Ajournal"; }
	if($DocType > 0){ $URL .= "&amp;rft_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Abook"; }

	// An ID for your application.  Replace yoursite.com and specify a name for your application.
	$URL .= "&amp;rfr_id=info%3Asid%2Fyoursite.com%3AYour+Name+Here+Using+Plus+Signs+For+Spaces";

	// Document Genre
	if($DocType == 0){ $URL .= "&amp;rft.genre=article"; }
	if($DocType == 1){ $URL .= "&amp;rft.genre=bookitem"; }
	if($DocType == 2){
		$URL .= "&amp;rft.genre=book";
		$URL .= "&amp;rft.edition=".urlencode($Document["Edition"]);
	}

	// Document Title
	if($DocType < 2){ $URL .= "&amp;rft.atitle=".urlencode($Document["DocTitle"]); }
	if($DocType == 2){ $URL .= "&amp;rft.btitle=".urlencode($Document["DocTitle"]); }

	// Publication Title
	if($DocType == 0){ $URL .= "&amp;rft.jtitle=".urlencode($Document["JournalTitle"]); }
	if($DocType == 1){ $URL .= "&amp;rft.btitle=".urlencode($Document["BookTitle"]); }

	// Volume, Issue, Season, Quarter, and ISSN (for journals)
	if($DocType == 0){
		if($Document["Volume"]){ $URL .= "&amp;rft.volume=".urlencode($Document["Volume"]); }
		if($Document["JournalIssue"]){ $URL .= "&amp;rft.issue=".urlencode($Document["JournalIssue"]); }
		if($Document["JournalSeason"]){ $URL .= "&amp;rft.ssn=".urlencode($Document["JournalSeason"]); }
		if($Document["JournalQuarter"]){ $URL .= "&amp;rft.quarter=".urlencode($Document["JournalQuarter"]); }
		if($Document["JournalQuarter"]){ $URL .= "&amp;rft.quarter=".urlencode($Document["ISSN"]); }
	}

	// Publisher, Publication Place, and ISBN (for books)
	if($DocType > 0){
		$URL .= "&amp;rft.pub=".urlencode($Document["BookPublisher"]);
		$URL .= "&amp;rft.place=".urlencode($Document["PubPlace"]);
		$URL .= "&amp;rft.isbn=".urlencode($Document["ISBN"]);
	}

	// Start page and end page (for journals and book articles)
	if($DocType < 2){
		$URL .= "&amp;rft.spage=".urlencode($Document["StartPage"]);
		$URL .= "&amp;rft.epage=".urlencode($Document["EndPage"]);
	} else if($DocType = 2) {
		$URL .= "&amp;rft.pages=".urlencode($Document["StartPage"] . "-" . $Document["StartPage"]);
//		$URL .= "&amp;rft.epage=".urlencode($Document["EndPage"]);
//		$URL .= "&amp;rft.tpages=".urlencode($Document["NrPages"]);
	}
	$URL .= "&amp;rft.extra=test";
	// Publication year.
	$URL .= "&amp;rft.date=".$Document["DocYear"];

	// Authors
	$i = 0;
	while($People[$i]){
		if($People[$i]["DocRelationship"] == 0){
			$URL .= "&amp;rft.au=".urlencode($People[$i]["LastName"]).",+".urlencode($People[$i]["FirstName"]);
		}
		$i++;
	}

	return $URL;
}

function data_from_isbn($isbn) {

	$strResult = new HTTPRequest('http://xisbn.worldcat.org/webservices/xid/isbn/' . $isbn . '?method=getMetadata&fl=*&format=php');
	$strResult = $strResult->DownloadToString();
//	echo $strResult;
	/*$strResult = "array(
	 'stat'=>'ok',
	 'list'=>array(array(
		'url'=>array('http://www.worldcat.org/oclc/154289190?referer=xid'),
		'publisher'=>'O\'Reilly & Associates',
		'form'=>array('BA'),
		'lccn'=>array('00502159'),
		'lang'=>'eng',
		'city'=>'Cambridge',
		'author'=>'David Flanagan.',
		'ed'=>'3rd ed.',
		'year'=>'1997',
		'isbn'=>array('9781565923928'),
		'title'=>'JavaScript : the definitive guide',
		'oclcnum'=>array('154289190',
		 '245800024',
		 '300449276',
		 '39368294',
		 '441078428',
		 '474726259',
		 '491223411'))))";*/
	eval("\$newResult = $strResult;");
	return $newResult;
//$newResult = xml2array($strResult,true);
//$newResult = json_decode($strResult,true);
}

function isbn13to10($isbn) {
	$request = new HTTPRequest('http://xisbn.worldcat.org/webservices/xid/isbn/'. $isbn .'?method=to10&format=json');
	$data = json_decode($request->DownloadToString());
	if ($data->stat == 'ok') {
		$isbn = $data->list[0]->isbn[0];
	}
	return $isbn;
}

function json_data_from_isbn($isbn) {

	$strResult = new HTTPRequest('http://xisbn.worldcat.org/webservices/xid/isbn/' . $isbn . '?method=getMetadata&fl=*&format=json');
	$strResult = $strResult->DownloadToString();
	return json_decode($strResult);
}

function json_data_from_issn($issn) {

	$strResult = new HTTPRequest('http://xissn.worldcat.org/webservices/xid/issn/' . $issn . '?method=getMetadata&fl=*&format=json');
	$strResult = $strResult->DownloadToString();
	return json_decode($strResult);
}

function coin_from_data($data) {
	$theDoc['DocType'] = 2;
	$theDoc['Edition'] = $data['list'][0]['ed'];
	$theDoc['DocTitle'] = $data['list'][0]['title'];
	$theDoc['BookPublisher'] = $data['list'][0]['publisher'];
	$theDoc['PubPlace'] = $data['list'][0]['city'];
	$theDoc['ISBN'] = $data['list'][0]['isbn'][0];
	$theDoc['DocYear'] = $data['list'][0]['year'];
	$theDoc['StartPage'] = $_POST['page'];
	$theDoc['EndPage'] = $theDoc['StartPage'];
	$theDoc['NrPages'] = 1;
	$thePeople[0]['DocRelationship'] = 0;
	$thePeople[0]['LastName'] = $data['list'][0]['author'];
	return OpenURL($theDoc,$thePeople);
}

function mods_from_data($data) {
	$mods = '<mods xmlns:xlink="http://www.w3.org/1999/xlink" version="3.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.loc.gov/mods/v3" xsi:schemaLocation="http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-0.xsd">';
	$mod .= '<genre authority="marcgt">book</genre>';
	$mods .= '<typeOfResource>text</typeOfResource>';
	$mods .= '<titleInfo><title>' . XMLClean($data['list'][0]['title']) . '</title></titleInfo>';
	$mods .= '<name type="personal"><namePart>' . $data['list'][0]['author'] . '</namePart>	</name>';
	$mods .= '<identifier type="isbn">' . $data->list[0]->isbn[0] . '</identifier>';
	$mods .= '<originInfo><publisher>' . XMLClean($data['list'][0]['publisher']) . '</publisher><copyrightDate>' . $data['list'][0]['year'] . '</copyrightDate><edition>' . $data['list'][0]['ed'] . '</edition></originInfo>';
	if ($data['list'][0]['file'] <> '') {
		$mods .= '<location><url displayLabel="Image scannée" access="raw object">http://nte2.unifr.ch/tests/biblio_jm/uploads/'.$data['list'][0]['file'] . '</url></location>';
	}
	$mods .= '<note>Note : ' . $_POST['note'] . '</note>';
	$mods .= '</mods>';
	return $mods;
}

function mods_from_json_data_isbn($data) {
	$mods = '<mods xmlns:xlink="http://www.w3.org/1999/xlink" version="3.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.loc.gov/mods/v3" xsi:schemaLocation="http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-0.xsd">';
	$mods .= '<genre authority="marcgt">book</genre>';
	$mods .= '<typeOfResource>text</typeOfResource>';
	$mods .= '<titleInfo><title>' . XMLClean($data->list[0]->title) . '</title></titleInfo>';
	$mods .= '<name type="personal"><namePart>' . XMLClean($data->list[0]->author) . '</namePart>	</name>';
	$mods .= '<identifier type="isbn">' . isbn13to10($data->list[0]->isbn[0]) . '</identifier>';
	$mods .= '<originInfo><publisher>' . XMLClean($data->list[0]->publisher) . '</publisher><copyrightDate>' . XMLClean($data->list[0]->year) . '</copyrightDate><edition>' . XMLClean($data->list[0]->ed) . '</edition></originInfo>';
	if ($data->list[0]->file1 <> '') {
		$location = file_location(substr($data->list[0]->file1,0,-11));
		$mods .= '<location><url displayLabel="Extract" access="raw object">' . $location . $data->list[0]->file1 . '</url></location>';
	}
	if ($data->list[0]->file2 <> '') {
		$location = file_location(substr($data->list[0]->file2,0,-9));
		$mods .= '<location><url displayLabel="Title" access="raw object">' . $location . $data->list[0]->file2 . '</url></location>';
	}
	if (!empty($_POST['note'])) {
		$mods .= '<note>Note : ' . XMLClean($_POST['note']) . '</note>';
	}
	if ($data->list[0]->text1 <> '') {
		$mods .= '<note>OCRed Extract : ' . XMLClean($data->list[0]->text1) . '</note>';
	}
	if ($data->list[0]->text2 <> '') {
		$mods .= '<note>OCRed Title : ' . XMLClean($data->list[0]->text2) . '</note>';
	}
	$mods .= '</mods>';
	return $mods;
}

function mods_from_json_data_issn($data) {
	$mods = '<mods xmlns:xlink="http://www.w3.org/1999/xlink" version="3.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.loc.gov/mods/v3" xsi:schemaLocation="http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-0.xsd">';
	$mods .= '<genre authority="marcgt">periodical</genre>';
	$mods .= '<titleInfo><title>A remplir : '. XMLClean($data->group[0]->list[0]->title) . '</title></titleInfo>';
	$mods .= '<typeOfResource>text</typeOfResource>';
	$mods .= '<relatedItem type="host">';
	$mods .= '<titleInfo><title>' . XMLClean($data->group[0]->list[0]->title) . '</title></titleInfo>';
	$mods .= '<identifier type="issn">' . $_POST['isbn'] . '</identifier>';
	$mods .= '<originInfo><publisher>' . XMLClean($data->group[0]->list[0]->publisher) . '</publisher></originInfo>';
	$mods .= '</relatedItem>';
	if ($data->group[0]->file1 <> '') {
		$location = file_location(substr($data->group[0]->file1,0,-11));
		$mods .= '<location><url displayLabel="Extract" access="raw object">' . $location . $data->group[0]->file1 . '</url></location>';
	}
	if ($data->group[0]->file2 <> '') {
		$location = file_location(substr($data->group[0]->file2,0,-9));
		$mods .= '<location><url displayLabel="Title" access="raw object">' . $location . $data->group[0]->file2 . '</url></location>';
	}
	if (!empty($_POST['note'])) {
		$mods .= '<note>Note : ' . XMLClean($_POST['note']) . '</note>';
	}
	if ($data->group[0]->text1 <> '') {
		$mods .= '<note>OCRed Extract : ' . XMLClean($data->group[0]->text1) . '</note>';
	}
	if ($data->group[0]->text2 <> '') {
		$mods .= '<note>OCRed Title : ' . XMLClean($data->group[0]->text2) . '</note>';
	}

	$mods .= '</mods>';
	return $mods;
}

function rdf_from_json_data($data, $identifier) {
	if ($identifier === 'isbn') {
		$type = 'Book';
		$exType = 'book';
		$publisher = XMLClean($data->list[0]->publisher);
		$author = XMLClean($data->list[0]->author);
		$idnumber = XMLClean($data->list[0]->isbn[0]);
		$year = XMLClean($data->list[0]->year);
		$file1 = $data->list[0]->file1;
		$file2 = $data->list[0]->file2;
		$text1 = $data->list[0]->text1;
		$text2 = $data->list[0]->text2;
		$title = XMLClean($data->list[0]->title);
		$resource = '<dc:identifier>ISBN '.$idnumber.'</dc:identifier>';
		$bib = '<bib:Book rdf:about="urn:'.$identifier.':'.$idnumber.'">';
	}
	else {
		$type= 'Article';
		$exType = 'journalArticle';
		$publisher = XMLClean($data->group[0]->publisher);
		$author = XMLClean($data->group[0]->author);
		$idnumber = XMLClean($data->group[0]->list[0]->issn);
		$year = XMLClean($data->list[0]->year);
		$file1 = $data->group[0]->file1;
		$file2 = $data->group[0]->file2;
		$text1 = $data->group[0]->text1;
		$text2 = $data->group[0]->text2;
		$title = XMLClean($data->group[0]->list[0]->title);
		$resource = '<dc:identifier>ISSN '.$idnumber.'</dc:identifier>';
		$bib = '<bib:Article rdf:about="urn:'.$identifier.':'.$idnumber.'">';
	}
	$rdf = '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:z="http://www.zotero.org/namespaces/export#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:foaf="http://xmlns.com/foaf/0.1/" xmlns:bib="http://purl.org/net/biblio#" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:link="http://purl.org/rss/1.0/modules/link/">';
	$rdf .=     $bib;
	$rdf .=         '<z:itemType>'.$exType.'</z:itemType>';
	if ($publisher != '') {
		$rdf .=     '<dc:publisher>';
		$rdf .=         '<foaf:Organization>';
		$rdf .=             '<foaf:name>'.$publisher.'</foaf:name>';
		$rdf .=         '</foaf:Organization>';
		$rdf .=     '</dc:publisher>';
	}
	if ($author != '') {
		$rdf .=     '<bib:authors>';
		$rdf .=         '<rdf:Seq>';
		$rdf .=             '<rdf:li>';
		$rdf .=                 '<foaf:Person>';
		$rdf .=                     '<foaf:surname>'.$author.'</foaf:surname>';
		$rdf .=                 '</foaf:Person>';
		$rdf .=             '</rdf:li>';
		$rdf .=         '</rdf:Seq>';
		$rdf .=     '</bib:authors>';
	}
	if (!empty($_POST['note'])) {
		$rdf .= 	'<dcterms:isReferencedBy rdf:resource="#note"/>';
	}
	if ($text1 <> '') {
		$rdf .=		'<dcterms:isReferencedBy rdf:resource="#ocred_extract"/>';
	}
	if ($text2 <> '') {
		$rdf .= 	'<dcterms:isReferencedBy rdf:resource="#ocred_title"/>';
	}
	if ($file1 <> '') {
	    $rdf .=     '<link:link rdf:resource="#file1"/>';
	}
	if ($file2 <> '') {
	    $rdf .=     '<link:link rdf:resource="#file2"/>';
	}
	if ($identifier === 'isbn') {
		$rdf .=     '<link:link rdf:resource="#worldcat"/>';
	}
	$rdf .=         $resource;
	if ($year != '') {
		$rdf .=         '<dc:date>'.$year.'</dc:date>';
	}
	$rdf .=         '<z:libraryCatalog>elearning.unifr.ch</z:libraryCatalog>';
	$rdf .=         '<dc:title>'.$title.'</dc:title>';
	$rdf .=     '</bib:'.$type.'>';
	if (!empty($_POST['note'])) {
		$rdf .= '<bib:Memo rdf:about="#note">';
		$rdf .=     '<rdf:value>'.XMLClean($_POST['note']).'</rdf:value>';
		$rdf .= '</bib:Memo>';
	}
	if ($text1 <> '') {
		$rdf .= '<bib:Memo rdf:about="#ocred_extract">';
		$rdf .=     '<rdf:value>'.$text1.'</rdf:value>';
		$rdf .= '</bib:Memo>';
	}
	if ($text2 <> '') {
		$rdf .= '<bib:Memo rdf:about="#ocred_title">';
		$rdf .=     '<rdf:value>'.$text2.'</rdf:value>';
		$rdf .= '</bib:Memo>';
	}
	if ($file1 <> '') {
	    $location = file_location(substr($file1,0,-11));
	    $rdf .= '<z:Attachment rdf:about="#file1">';
	    $rdf .=     '<z:itemType>attachment</z:itemType>';
	    $rdf .=     '<dc:identifier>';
	    $rdf .=         '<dcterms:URI>';
	    $rdf .=             '<rdf:value>'.$location . $file1.'</rdf:value>';
	    $rdf .=         '</dcterms:URI>';
	    $rdf .=     '</dc:identifier>';
	    $rdf .=     '<dc:title>File 1</dc:title>';
	    $rdf .=     '<z:linkMode>3</z:linkMode>';
	    $rdf .= '</z:Attachment>';
	}
	if ($file2 <> '') {
	    $location = file_location(substr($file2,0,-9));
	    $rdf .= '<z:Attachment rdf:about="#file2">';
	    $rdf .=     '<z:itemType>attachment</z:itemType>';
	    $rdf .=     '<dc:identifier>';
	    $rdf .=         '<dcterms:URI>';
	    $rdf .=             '<rdf:value>'.$location . $file2.'</rdf:value>';
	    $rdf .=         '</dcterms:URI>';
	    $rdf .=     '</dc:identifier>';
	    $rdf .=     '<dc:title>File 2</dc:title>';
	    $rdf .=     '<z:linkMode>3</z:linkMode>';
	    $rdf .= '</z:Attachment>';
	}
	if ($identifier === 'isbn') {
		$rdf .= '<z:Attachment rdf:about="#worldcat">';
		$rdf .=     '<z:itemType>attachment</z:itemType>';
		$rdf .=     '<dc:identifier>';
		$rdf .=         '<dcterms:URI>';
		$rdf .=             '<rdf:value>'.XMLClean($data->list[0]->url[0]).'</rdf:value>';
		$rdf .=         '</dcterms:URI>';
		$rdf .=     '</dc:identifier>';
		$rdf .=     '<dc:title>Worldcat</dc:title>';
		$rdf .=     '<z:linkMode>3</z:linkMode>';
		$rdf .= '</z:Attachment>';
	}
	$rdf .= '</rdf:RDF>';
	return $rdf;
}

function getOCRText($uploadfile) {
//	echo $uploadfile;
//	return "Ce texte OCR contient des ' et des é à accents et des \"";
	$client = new SoapClient("http://www.ocrwebservice.com/services/OCRWebService.asmx?WSDL", array("trace"=>1, "exceptions"=>1));
	$params = new StdClass();
	$params->user_name = "monnardj";
	$params->license_code = "3B490A0C-3AAD-4A81-9D6C-C837035AB73C";
	$inimage = new StdClass();
	$handle = fopen($uploadfile, 'r');
	$card_image = fread($handle, filesize($uploadfile));
	fclose($handle);
	$inimage->fileName = "sample_image.jpg";
	$inimage->fileData = $card_image;
	$params->OCRWSInputImage = $inimage;
	$settings = new StdClass();
	$settings->ocrLanguages = array("FRENCH","ENGLISH","GERMAN","ITALIAN");
	$settings->outputDocumentFormat = "TXT";
	$settings->convertToBW = FALSE;
	$settings->getOCRText = TRUE;
	$settings->createOutputDocument = FALSE;
	$settings->multiPageDoc = FALSE;
	$settings->ocrWords = FALSE;
	$params->OCRWSSetting = $settings;
	try
	{
		$result = $client->OCRWebServiceRecognize($params);
	}
	catch (SoapFault $fault)
	{
		print($client->__getLastRequest());
		print($client->__getLastRequestHeaders());
	}
	//var_dump($result);

	//new dBug ($result);
	if ($result->OCRWSResponse->errorMessage<>'') {
		return "";
	} else {
		return $result->OCRWSResponse->ocrText->ArrayOfString->string;
	}
}

function file_location($name) {
	return "http://elearning.unifr.ch/bibup/uploads/". $name . "/";
}
?>
