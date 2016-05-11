<?php
include("includes/fonctions.inc");

// get parameters
$id = ( isset($_GET['id']) ) ? urldecode($_GET['id']) : null;
$format = ( isset($_GET['format']) ) ? urldecode($_GET['format']) : null;

$formatsList = array(
	'mods'
);

// validate format
if ( $format )
	if ( !in_array($format, $formatsList) )
		unapi_error(406);

// validate id
if ( $id ) {
	if (!is_numeric($id)) {
		unapi_error(404);
	}
	$requete = "select * from fiches where id = " . ($id);
	$result = mysql_query($requete) or die("<br />couldn't execute query");
	$row = mysql_fetch_assoc($result);
	if (!$row) {
		unapi_error(404);
	}
}

// create XML for responses
$xmlHeader = '<?xml version="1.0" encoding="utf-8" ?>' . "\n";
$formats  = '<format name="mods" type="application/xml" docs="http://www.loc.gov/standards/mods/" />' . "\n";
$formats  = '<format name="rdf" type="application/xml" docs="http://www.w3.org/1999/02/22-rdf-syntax-ns#" />' . "\n";

// main brancher: select response depending on presence/absence of identifier and format
if ( $format )
	( $id ) ? unapi_type3url() : unapi_error(400);
else
	( $id ) ? unapi_type2url() : unapi_type1url();


/*
 * type1url (no identifier, no format): return list of formats
 *
 *
 */
function unapi_type1url() {
	global $xmlHeader, $formats;
	header('Content-type: application/xml; charset=utf-8', true);
	echo $xmlHeader .
		"<formats>\n" .
		$formats .
		'</formats>';
} // type1url()

/*
 * type2url: identifier, no format - return list of formats for this identifier
 *
 *
 */
function unapi_type2url() {
	global $xmlHeader, $formats, $id;
	header('Content-type: application/xml; charset=utf-8', true);
	header('HTTP/1.0 300 Multiple Choices');
	echo $xmlHeader .
		'<formats id="'. $id . '">' . "\n" .
		$formats .
		'</formats>';
} // type2url()

/*
 * type3url: identifier and format - return status 300 and multiple links
 *
 * Gathers necessary information such as author and blog name, and calls
 * the appropriate function to build the metadata record in the requested
 * format.
 */
function unapi_type3url() {
	global $xmlHeader, $formats, $id, $format, $result;
	$contentType = ( 'rss' == $format ) ? 'application/rss+xml' : 'application/xml';
	header('Content-type: ' . $contentType . '; charset=utf-8', true);

	echo $xmlHeader;
	eval('unapi_show_' . $format . '();');
} // type3url()

/*
 * error - return error in status code
 *
 *
 */
function unapi_error($statusCode) {
	global $statusString;

	$statusString[400] = 'Bad Request';
	$statusString[404] = 'Not Found';
	$statusString[406] = 'Not Acceptable';

	header('HTTP/1.0 ' . $statusCode . ' ' . $statusString[$statusCode]);
	echo $statusCode . ' ' . $statusString[$statusCode];
	die();
} // error()

/*
 * output an oai_dc record from a post
 *
 *
 *
 */
function unapi_show_oai_dc() {
	global $postId, $blogName;
	foreach(array_merge(get_posts('include=' . $postId), get_pages('include=' . $postId)) as $post) : setup_postdata($post);
?>
	<oai_dc:dc xmlns:oai_dc="http://www.openarchives.org/OAI/2.0/oai_dc/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/oai_dc/ http://www.openarchives.org/OAI/2.0/oai_dc.xsd">
		<dc:identifier><?php the_permalink_rss(); ?></dc:identifier>
		<dc:title><?php the_title_rss(); ?></dc:title>
		<dc:type>text</dc:type>
		<dc:creator><?php the_author(); ?></dc:creator>
		<dc:publisher><?php echo htmlspecialchars($blogName); ?></dc:publisher>
		<dc:date><?php the_modified_date('r'); ?></dc:date>
		<dc:format>application/xml</dc:format>
		<dc:language><?php echo get_option('rss_language'); ?></dc:language>
<?php
	foreach ( array_merge((array) get_the_category(), (array) get_the_tags()) as $cat ) {
		if ( $cat->name == "" ) continue;
?>
		<dc:subject scheme="local"><?php echo $cat->name; ?></dc:subject>
<?php
	}
?>
		<dc:description>'<?php the_excerpt_rss();?>'</dc:description>
	</oai_dc:dc>
<?php
	endforeach;
}

/*
 * output an RSS record from a post
 *
 *
 */
function unapi_show_rss() {
	global $postId, $blogName;
	foreach(array_merge(get_posts('include=' . $postId), get_pages('include=' . $postId)) as $post) : setup_postdata($post);
?>
  <rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
      <title><?php echo htmlspecialchars($blogName); ?></title>
      <link><?php echo get_bloginfo('url'); ?></link>
      <description><?php echo htmlspecialchars(get_bloginfo('description')); ?></description>
      <pubDate><?php the_modified_date('r'); ?></pubDate>
      <language><?php echo get_option('rss_language'); ?></language>
      <item>
	 <title><?php the_title_rss(); ?></title>
         <link><?php the_permalink_rss(); ?></link>
	 <comments><?php echo get_permalink() . "#comments"; ?></comments>
	 <pubDate><?php the_modified_date('r'); ?></pubDate>
	 <dc:creator><?php the_author(); ?></dc:creator>
	<?php
	foreach ( array_merge((array) get_the_category(), (array) get_the_tags()) as $cat ) {
		if ( $cat->name == "" ) continue;
		echo "\t\t<category>" . $cat->name . "</category>\n";
	}
?>
	 <guid isPermaLink="true"><?php the_permalink_rss(); ?></guid>
	 <description><![CDATA['<?php the_excerpt_rss();?>']]></description>
	 <wfw:commentRSS><?php echo get_permalink() . "feed/"; ?></wfw:commentRSS>
       </item>
     </channel>
   </rss>
<?php
	endforeach;
}

/*
 * output a mods record from a post
 *
 *
 *
 */
function unapi_show_mods() {
	global $row;
	echo $row['mods'];
}

/*
 * output a mods record from a post
 *
 *
 *
 */
function unapi_show_rdf() {
	global $row;
	echo $row['rdf'];
}

/*
 * output a marcxml record from a post
 *
 *
 *
 */
function unapi_show_marcxml() {
	global $postId, $blogName;
	foreach(array_merge(get_posts('include=' . $postId), get_pages('include=' . $postId)) as $post) : setup_postdata($post);
?>
  <marc:record xmlns:marc="http://www.loc.gov/MARC21/slim">
	<marc:leader>nm 22 uu 4500</marc:leader>
	<marc:controlfield tag="008">s ||||||||||||||||||||||</marc:controlfield>
        <marc:datafield tag="041" ind1="0" ind2="7">
		<marc:subfield code="a"><?php echo get_option('rss_language'); ?></marc:subfield>
	        <marc:subfield code="2">rfc3066</marc:subfield>
	</marc:datafield>
	<marc:datafield tag="245" ind1="1" ind2="0">
        	<marc:subfield code="a"><?php the_title_rss(); ?></marc:subfield>
	</marc:datafield>
	<marc:datafield tag="260" ind1="" ind2="">
		<marc:subfield code="b"><?php echo htmlspecialchars($blogName); ?></marc:subfield>
		<marc:subfield code="c"><?php the_modified_date('r'); ?></marc:subfield>
	</marc:datafield>
	<marc:datafield tag="520" ind1="" ind2="">
                <marc:subfield code="a">'<?php the_excerpt_rss(); ?>'</marc:subfield>
	</marc:datafield>
	<marc:datafield tag="650" ind1="1" ind2="">
        <?php
	$i = 0;
	foreach ( array_merge((array) get_the_category(), (array) get_the_tags()) as $cat ) {
		if ( $cat->name == "" ) continue;
		$j = ( 0 == $i ) ? "a" : "x";
		echo '<marc:subfield code="' . $j . '">' . $cat->name . "</marc:subfield>\n";
		$i++;
	}
?>
        </marc:datafield>
        <marc:datafield tag="700" ind1="1" ind2="">
        	<marc:subfield code="a"><?php the_author(); ?></marc:subfield>
	</marc:datafield>
	<marc:datafield tag="856" ind1="" ind2="">
		<marc:subfield code="u"><?php the_permalink_rss(); ?></marc:subfield>
	</marc:datafield>
  </marc:record>
<?php
	endforeach;
}

/*
 * output an SRW_DC record from a post
 *
 *
 *
 */
function unapi_show_srw_dc() {
	global $postId, $blogName;
	foreach(array_merge(get_posts('include=' . $postId), get_pages('include=' . $postId)) as $post) : setup_postdata($post);
?>
  <srw_dc:dc xmlns:srw_dc="info:srw/schema/1/dc-schema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://purl.org/dc/elements/1.1/" xsi:schemaLocation="info:srw/schema/1/dc-schema http://www.loc.gov/standards/sru/dc-schema.xsd">
	<title><?php the_title_rss(); ?></title>
        <creator><?php the_author(); ?></creator>
	<type>text</type>
	<format>application/xml</format>
	<publisher><?php echo htmlspecialchars($blogName); ?></publisher>
	<date><?php the_modified_date('r'); ?></date>
        <description>'<?php the_excerpt_rss(); ?>'</description>
        <?php
	foreach ( array_merge((array) get_the_category(), (array) get_the_tags()) as $cat ) {
		if ( $cat->name == "" ) continue;
		echo '<subject>' . $cat->name . "</subject>\n";
	}
?>
	<identifier><?php the_permalink_rss(); ?></identifier>
	<language><?php echo get_option('rss_language'); ?></language>
  </srw_dc:dc>
<?php
	endforeach;
}
?>
