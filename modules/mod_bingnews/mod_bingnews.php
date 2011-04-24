<?php
/**
* @version $Id: mod_bingnews.php 5203 2010-07-27 01:45:14Z DanR $
* This module will displays a news entries from the Bing search API
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('SimpleCache');

// Get the module parameters set in the Module Manager
$cacheExpire = $params->get('cache_expire', 4);
$apiKey = $params->get('bing_api_key');

// Make sure caching is turned on to prevent site from hitting Bing excessively
if(!$cacheExpire) {
	echo 'No entries available<br/>';
	return;
}
if(empty($apiKey)) {
	echo "Empty API key parameter. Use the Module Manager to set API key<br/>";
	return;
}

$document = &JFactory::getDocument();

$metaTags = trim($document->_metaTags['standard']['keywords']);
$keywords = explode(',',$metaTags);

// Filter for any empty keywords and eliminate duplicates
$keywordArray = array();
for($i=0;$i<count($keywords);$i++) {
	$keyword = strtolower(trim($keywords[$i]));
	if(!empty($keyword)) {
		$keywordArray[$keyword] = true;
	}
}
$searchStr = implode('%20',array_keys($keywordArray));
$searchStr = !empty($searchStr)	?	$searchStr	:	'joomla';

// In case multiple modules used on the same page, avoid redefining
if(!function_exists('getBingNews')) {
	function getBingNews($searchStr,$apiKey,$forceUpdate=true) {
		$searchStr = urlencode($searchStr);
		// Make cache last for 1 hour -- 60s * 60m
		$keyName = 'news_key_'.md5($searchStr);
		$data = false;
		if(!$forceUpdate) {
			$data = SimpleCache::getCache($keyName,$cacheExpire);
		}
		if($data===false) {			
			$url = "http://api.bing.net/json.aspx?AppId={$apiKey}&sources=news&query={$searchStr}";
			$json = file_get_contents($url);
			$data = json_decode($json,true);
			if(!isset($data['SearchResponse']['News'])) {
				$msg = "Error:".print_r($data['SearchResponse'],true);
				error_log($msg);
			} else {
				$data = $data['SearchResponse']['News']['Results'];
				SimpleCache::setCache($keyName,$data);
			}
		}
		return $data;
	}
}

// Output all tweets but hide beyond a certain point
$newsData = getBingNews($searchStr,$apiKey);
//$news = $newsData['SearchResponse']['News']['Results'];
//shuffle($newsData);
for($i=0;$i<3;$i++) {
	$newsItem = $newsData[$i];
	?>
	<div>
		<div class="title"><a href="<?php echo $newsItem['Url']; ?>"><?php echo $newsItem['Title']; ?></a> from <?php echo $newsItem['Source']; ?></div>
		<div><?php echo $newsItem['Snippet']; ?></div>
	</div>
	<?php
}

?>
