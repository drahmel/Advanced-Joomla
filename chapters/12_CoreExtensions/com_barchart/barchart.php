<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

$outFormat = JRequest::getVar('format');
if($outFormat!='raw') {
	echo "<h1>Only output in Raw format is available.</h1>";
	exit;	
}

require_once('cChart.php');

// Get request parameters if they're available
$chartType = JRequest::getVar('type');	
$xStr = JRequest::getVar('xdata');	
$yStr = JRequest::getVar('ydata');
$titleStr = JRequest::getVar('title');
$imageType = JRequest::getVar('imgtype');
$chartWidth = JRequest::getVar('width');
$chartHeight = JRequest::getVar('height');

// If chart is a DB query, then query the database for the data
if($chartType=='db') {
	// Get a copy of the Joomla database object
	$db = &JFactory::getDBO();
	$datenow =& JFactory::getDate();
	
	// Setup query to return the 5 most popular articles --
	//     ordered from most popular to least popular
	$titleStr = 'Most Popular Articles -- '.$datenow->toFormat( '%m/%d/%Y' );
	$sql = "SELECT title as x,hits as y FROM #__content "
		." ORDER BY y DESC LIMIT 5;";
	$db->setQuery($sql,0);
	if(!$db->query()) {
		JError::raiseError( 500, $db->stderror());
		exit;
	} else {
		$rows = $db->loadObjectList();
		$xData = array();
		$yData = array();
		for($i=0;$i<count($rows);$i++) {
			//print_r($rows[$i]);
			$xData[] = $rows[$i]->x;
			$yData[] = $rows[$i]->y;
		}
		$xStr = implode(',',$xData);
		$yStr = implode(',',$yData);
	}
} else {
	// If not a database query, use the REQUEST string data
}
// Render the chart with the current parameters
cChart::renderBarChart($titleStr,$xStr,$yStr,$imageType,$chartWidth,$chartHeight); 
// Get a reference to the current JDocument object
$document =& JFactory::getDocument();
// Set the Mime type to match the chart header
$document->_mime = 'image/png';
?>
