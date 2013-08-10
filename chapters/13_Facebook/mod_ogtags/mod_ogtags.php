<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Get a reference to the current document object
$document = &JFactory::getDocument();
// Get meta information set through the Joomla admin interface
$desc = $document->getMetaData('description');
$title = $document->getMetaData('title');
$ogType = $document->getMetaData('keywords');

// Get sitename and environment (dev or prod) from config
$sitename = JFactory::getApplication()->getCfg('sitename');
$environment = JFactory::getApplication()->getCfg('env');


// Get the OG information from the module parameters
$appID = trim($params->get('app_id'));
$adminIDs = trim($params->get('admin_ids'));
$pageID = trim($params->get('page_id'));

// Add Facebook metatags
$fbTags = array(
	'og:title'=>$title,
	'og:type'=>'article',
	'og:url'=>JURI::current(),
	'og:description'=>$desc, 
	'og:site_name'=>$sitename,
	"fb:admins"=>$adminIDs,
	"fb:app_id"=>$appID,
	"fb:page_id"=>$pageID
);

// Test if we're showing the home page or category menu
// -- then set the OG type to website
$app = JFactory::getApplication();
$menu = $app->getMenu();
$item = $menu->getActive();

$id = JRequest::getInt('id');
$vName = JRequest::getWord('view', 'categories');

// Check if this is the home page
if($item && intval($item->home)=='1') {
	$fbTags['og:type'] = 'website';
	$fbTags['og:title'] = $sitename." home page";
// Check if this is a category page
} elseif ($vName=='category') {
	$fbTags['og:type'] = 'website';
	// If this category has an associated Joomla menu, 
// use the title of the menu
	if($item) {
		$fbTags['og:title'] = $item->title." articles";
	} else {
		$fbTags['og:title'] = $sitename." category page";
	}
}

// Setup the standard OG types
$basicOGTypes = array(
	'article', 'blog', 'website',
	'activity', 'sport',
	'bar', 'company', 'cafe', 'hotel', 'restaurant',
	'cause', 'sports_league', 'sports_team',
	'band', 'government', 'non_profit', 'school', 
	'university',
	'actor', 'athlete', 'author', 'director', 'musician', 
	'politician', 'public_figure',
	'city', 'country', 'landmark', 'state_province',
	'album', 'book', 'drink', 'food', 'game', 'movie', 
	'product', 'song', 'tv_show'
);
// If an OG type has been specified using the Meta keywords field, 
// use it
if(in_array($ogType,$basicOGTypes)!==false) {
	$fbTags['og:type'] = $ogType;
}

// Add the tags to the header
foreach($fbTags as $fbTag => $fbContent) {
	$document->addCustomTag('<meta property="'.$fbTag.'" content="'.$fbContent.'" />');
}

return;
