<?php
/**
* @version $Id: com_articleinfo.php 5203 2013-08-10 01:45:14Z DanR $
* This component will process a request parameter articlenum
* to query the article database and return the title and
* article text in XML format.
* 
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Get request ID from query string variable
$articleNum = intval(JRequest::getVar( 'articlenum' ));
// Define default values if query fails
$articleTitle = "Error";
$articleBody = "Error";

// Get instance of database object
$db =& JFactory::getDBO();
// Create query to return id, title, and text of article
$query = "SELECT a.id, a.title,a.introtext  \n" .
	" FROM #__content AS a \n" .
	" WHERE a.id =" . $articleNum . "\n";
$db->setQuery( $query, 0);
// Execute query
if ($rows = $db->loadObjectList()) {
	// Get first row returned
	$row = $rows[0];
	// Load article title and text into variables
	$articleTitle = $row->title;
	$articleBody = $row->introtext;
	// Strip all the HTML from the article text
	$articleBody = strip_tags($articleBody);
	// Strip all non-alpha, numeric, or punctuation
	$articleBody = preg_replace(
	"/[^a-zA-Z0-9 .?!$()\'\"]/", "", $articleBody);
	// If length is > 200, truncate length
	if(strlen($articleBody) > 200) {
		$articleBody = substr($articleBody, 0, 200);
	}
}
echo json_encode(array('title'=>$articleTitle,'body'=>$articleBody));
?>
