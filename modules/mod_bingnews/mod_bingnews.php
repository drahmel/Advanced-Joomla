<?php
/**
* @version $Id: mod_bingnews.php 5203 2010-07-27 01:45:14Z DanR $
* This module will displays a FB Like widget
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('SimpleCache');
SimpleCache::getCache('mytest');

$conf = &JFactory::getConfig();
$caching = $conf->get('caching', 1);
// Make sure caching is turned on to prevent site from hitting Twitter excessively
if(!$caching) {
	//echo 'No entries available<br/>';
	//exit;
}

$document = &JFactory::getDocument();


// Add Facebook metatags
$fbTags = array('og:title'=>$document->title,'og:type'=>'article','og:url'=>JURI::current(),
	'og:description'=>'The latest in Joomla technology', 'og:site_name'=>'Joomla Jumpstart',
	"fb:admins"=>"100000337386305,100000227392052","fb:app_id"=>"130425443647406", "fb:page_id"=>"117743991584876");

$app = JFactory::getApplication();
$menu = $app->getMenu();
$item = $menu->getActive(); //$menu->getItems('link', 'index.php?option=com_content&view=archive', true);
$sitename = "Joomla Jumpstart";
$id = JRequest::getInt('id');
$vName	= JRequest::getWord('view', 'categories');

// Check if this is the home page
if($item && intval($item->home)=='1') {
	$fbTags['og:type'] = 'website';
	$fbTags['og:title'] = $sitename." home page";
// Check if this is a category page
} elseif ($vName=='category') {
	$fbTags['og:type'] = 'website';
	// If this category has an associated Joomla menu, use the title of the menu
	if($item) {
		$fbTags['og:title'] = $item->title." articles";
	} else {
		$fbTags['og:title'] = $sitename." category page";
	}
}

foreach($fbTags as $fbTag => $fbContent) {
	$document->addCustomTag("<meta property='$fbTag' content='$fbContent' />");
}

$metaTags = trim($document->_metaTags['standard']['keywords']);
$keywords = explode(',',$metaTags);
$keywordArray = array();
// Filter for any empty keywords and eliminate duplicates
for($i=0;$i<count($keywords);$i++) {
	$keyword = strtolower(trim($keywords[$i]));
	if(!empty($keyword)) {
		$keywordArray[$keyword] = true;
	}
}
$searchStr = implode('%20',array_keys($keywordArray));
$searchStr = !empty($searchStr)	?	$searchStr	:	'joomla';
if(empty($searchStr)) {
	echo 'No entries available<br/>';
	exit;
}

// In case multiple modules used on the same page, avoid redefining
if(!function_exists('getBingNews')) {
	function getBingNews($searchStr,$forceUpdate=false) {
		$searchStr = urlencode($searchStr);
		// Make cache last for 1 hour -- 60s * 60m
		$expire = 60*60;
		$keyName = 'news_key_'.md5($searchStr);
		$data = false;
		if(!$forceUpdate) {
			$data = SimpleCache::getCache($keyName,$expire);
		}
		if($data===false) {
			$googleApiKey = "AIzaSyDoe_5k4xSYXftSMxoTzpXy3s-0XNtGhkg";
			$bingKey = "783284E45C03DFA1EFC82ADE7CD8BCBE7A179DBB";
			
			$url = "http://api.bing.net/json.aspx?AppId=$bingKey&sources=news&query=$searchStr";
			$json = file_get_contents($url);
			$data = json_decode($json,true);
			$data = $data['SearchResponse']['News']['Results'];
			SimpleCache::setCache($keyName,$data);
		}
		return $data;
	}
}

// Output all tweets but hide beyond a certain point
$newsData = getBingNews($searchStr);
//$news = $newsData['SearchResponse']['News']['Results'];
//shuffle($newsData);
for($i=0;$i<3;$i++) {
	$newsItem = $newsData[$i];
	//print_r($newsItem);
	?>
	<div>
		<div class="title"><a href="<?php echo $newsItem['Url']; ?>"><?php echo $newsItem['Title']; ?></a> from <?php echo $newsItem['Source']; ?></div>
		<div><?php echo $newsItem['Snippet']; ?></div>
	</div>
	<?php
}

?>
