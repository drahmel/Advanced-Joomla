<?php
/**
* @version $Id: mod_flickr.php 5203 2010-07-27 01:45:14Z DanR $
* This module will displays a a Flickr image depending on the search
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('SimpleCache');

// Get the module parameters set in the Module Manager
$cacheExpire = $params->get('cache_expire', 24);
$apiKey = $params->get('flickr_api_key');
$numItems = $params->get('num_items',2);
$shuffle = $params->get('shuffle',0);

// Make sure caching is turned on to prevent site from hitting Flickr excessively
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


if(!function_exists('getFlickr')) {
	function getFlickr($searchStr,$apiKey,$forceUpdate=false) {
		$searchStr = urlencode($searchStr);
		$keyName = 'flickr_key_'.md5($searchStr);
		$data = false;
		if(!$forceUpdate) {
			$data = SimpleCache::getCache($keyName,$cacheExpire);
		}
		if($data===false) {
			$callMethod = 'flickr.photos.search';
			$url = "http://api.flickr.com/services/rest/?&api_key=$apiKey&method=$callMethod";
			$url .= "&extras=owner_name,license,path_alias";
			$url .= "&sort=relevance";
			$url .= "&text=$searchStr";				
			// Request no JavaScript wrapper function
			$url .= "&format=json&nojsoncallback=1";
			$json = file_get_contents($url);
			$data = json_decode($json,true);
			if(isset($data['photos']['photo'])) {
				foreach($data['photos']['photo'] as &$photo) {
					$id = $photo['id'];
					$farm = $photo['farm'];
					$server = $photo['server'];
					$secret = $photo['secret'];
					$photo['url'] = "http://farm{$farm}.static.flickr.com/{$server}/{$id}_{$secret}.jpg";
				}
			} else {
				$data = array();
			}
			SimpleCache::setCache($keyName,$data);
		}
		return $data;
	}
}

$flickrData = getFlickr($searchStr,$apiKey);
if($shuffle) {
	shuffle($flickrData['photos']['photo']);
}
for($i=0;$i<$numItems;$i++) {
	$photo = $flickrData['photos']['photo'][$i];
	$baseURL = $photo['url'];

?>
	<div class="image">
		<div style='width:400px;height:200px;background-position:center; background-image:url(<?php echo $baseURL; ?>)'></div>
		<div><?php echo $photo['title'].' from '.$photo['ownername']; ?></div>
	</div>
	<?php
}

?>
