<?php
/**
* @version $Id: mod_facebook_like.php 5203 2010-07-27 01:45:14Z DanR $
* This module will displays a FB Like widget
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

if(isset($app)) {
	$pathway = &$app->getPathway();
} else {
	// For Joomla 1.5 compatibility
	global $mainframe;
	$pathway = &$mainframe->getPathway();
}
$items = $pathway->getPathWay();
$pathStr = substr(JURI::base(),0,-1).JRoute::_($items[0]->link);
//$pathStr = 'http://www.joomlajumpstart.com';
$page_url = urlencode($pathStr);
?>
<iframe src="http://www.facebook.com/plugins/like.php?href=<?php 
	echo $page_url; 
?>&amp;layout=standard&amp;show-faces=true&amp;width=350&amp;action=like&amp;font=arial&amp;colorscheme=light"
scrolling="no" frameborder="0" allowTransparency="true" 
style="border:none; overflow:hidden; width:400px; margin-top: 0; height: 25px; float: right">
</iframe>
<div style="clear:both;"></div>
