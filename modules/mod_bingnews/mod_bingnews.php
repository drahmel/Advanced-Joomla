<?php
/**
* @version $Id: mod_bingnews.php 5203 2010-07-27 01:45:14Z DanR $
* This module will displays a news entries from the Bing search API
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('SimpleCache');

$conf = &JFactory::getConfig();
$caching = $conf->get('caching', 1);
$bingApiKey = $conf->get('bing_api_key', 1);

// Make sure caching is turned on to prevent site from hitting Bing excessively
if(!$caching) {
	echo 'No entries available<br/>';
	exit;
}

$document = &JFactory::getDocument();

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
	?>
	<div>
		<div class="title"><a href="<?php echo $newsItem['Url']; ?>"><?php echo $newsItem['Title']; ?></a> from <?php echo $newsItem['Source']; ?></div>
		<div><?php echo $newsItem['Snippet']; ?></div>
	</div>
	<?php
}

?>
