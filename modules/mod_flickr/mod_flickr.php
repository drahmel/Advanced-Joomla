<?php
/**
* @version $Id: mod_flickr.php 5203 2010-07-27 01:45:14Z DanR $
* This module will displays a a Flickr image depending on the search
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('SimpleCache');
SimpleCache::getCache('mytest');

$conf = &JFactory::getConfig();
$caching = $conf->get('caching', 1);
$apiKey = $conf->get('flickr_api_key');

// Make sure caching is turned on to prevent site from hitting Twitter excessively
if(!$caching) {
	echo 'No entries available<br/>';
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
	function getFlickr($searchStr,$forceUpdate=false) {
		$searchStr = urlencode($searchStr);
		// Make cache last for 1 day -- 60s * 60m * 24h
		$expire = 60*60*24;
		$keyName = 'flickr_key_'.md5($searchStr);
		$data = false;
		if(!$forceUpdate) {
			$data = SimpleCache::getCache($keyName,$expire);
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

// Output all tweets but hide beyond a certain point
$flickrData = getFlickr($searchStr);
shuffle($flickrData['photos']['photo']);
$photo = $flickrData['photos']['photo'][0];
$baseURL = $photo['url'];
?>
<div class="image">
	<div style='width:400px;height:200px;background-position:center; background-image:url(<?php echo $baseURL; ?>)'></div>
	<div><?php echo $photo['title'].' from '.$photo['ownername']; ?></div>
</div>
?>
