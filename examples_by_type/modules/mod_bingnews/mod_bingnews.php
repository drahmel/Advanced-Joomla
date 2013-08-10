<?php
/**
* @version $Id: mod_bingnews.php 5203 2011-07-27 01:45:14Z DanR $
* This module will displays a news entries from the Bing search API
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('SimpleCache');

// Get the module parameters set in the Module Manager
$cacheExpire = $params->get('cache_expire', 4);
$apiKey = $params->get('bing_api_key');
$numItems = $params->get('num_items',3);
$shuffle = $params->get('shuffle',0);

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
	function getBingNews($searchStr,$apiKey,$forceUpdate=false) {
		$searchStr = urlencode($searchStr);
		$keyName = 'news_key_'.md5($searchStr);
		// Set a default value of false in case of force update
		$data = false;
		if(!$forceUpdate) {
			$data = SimpleCache::getCache($keyName,$cacheExpire);
		}
		if($data===false) {			
			$url = "http://api.bing.net/json.aspx?AppId={$apiKey}"
				."&sources=news&query={$searchStr}";
			// Get JSON from API
			$json = file_get_contents($url);
			// Decode JSON to an array
			$data = json_decode($json,true);
			// If there is an error, log to PHP log
			if(!isset($data['SearchResponse']['News'])) {
				$msg = "Error:".print_r($data['SearchResponse'],true);
				error_log($msg.$url);
			} else {
				$data = $data['SearchResponse']['News']['Results'];
				SimpleCache::setCache($keyName,$data);
			}
		}
		return $data;
	}
}

// Output number of news items specified in parameters
$newsData = getBingNews($searchStr,$apiKey);
//$news = $newsData['SearchResponse']['News']['Results'];
if($shuffle) {
	shuffle($newsData);
}
$totalItems = count($newsData[$i]);
// If there are fewer available items then the # requested, only display available
$numItems = $totalItems < $numItems	?	$totalItems	:	$numItems; 

for($i=0;$i<$numItems;$i++) {
	$newsItem = $newsData[$i];
	?>
	<div>
		<div class="title">
		<a href="<?php echo $newsItem['Url']; ?>">
			<?php echo $newsItem['Title']; ?>
		</a> from <?php echo $newsItem['Source']; ?></div>
		<div><?php echo $newsItem['Snippet']; ?></div>
	</div>
	<?php
}

?>
