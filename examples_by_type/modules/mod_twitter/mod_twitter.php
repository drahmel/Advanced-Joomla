<?php
/**
* @version $Id: mod_twitter.php 5203 2010-07-27 01:45:14Z DanR $
* This module will displays a Twitter entries based on search keywords
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('SimpleCache');

// Get the module parameters set in the Module Manager
$cacheExpire = $params->get('cache_expire', 4);
// Not used now, but can be used for more advanced Twitter operations
$apiKey = $params->get('twitter_api_key');
$numItems = $params->get('num_items',3);
$shuffle = $params->get('shuffle',0);

// Make sure caching is turned on to prevent site from hitting Bing excessively
if(!$cacheExpire) {
	echo 'No entries available<br/>';
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
if(empty($searchStr)) {
	echo 'No entries available<br/>';
	exit;
}

// In case multiple modules used on the same page, avoid redefining
if(!function_exists('getTwitter')) {
	function getTwitter($searchStr,$forceUpdate=false) {
		$searchStr = urlencode($searchStr);
		$keyName = 'twitter_key_'.md5($searchStr);
		$tweetData = false;
		if(!$forceUpdate) {
			$tweetData = SimpleCache::getCache($keyName,$expire);
		}
		if($tweetData===false) {
			$url = 'http://search.twitter.com/search.json?q='.$searchStr;	//.'&nothing='.rand(0,2000);
			$tweets = file_get_contents($url);
			$tweetData = json_decode($tweets,true);
			SimpleCache::setCache($keyName,$cacheExpire);
		}
		return $tweetData;
	}
}

// Output all tweets but hide beyond a certain point
$tweetData = getTwitter($searchStr);

$i=0;
//shuffle($tweetData['results']);
foreach($tweetData['results'] as $tweet) {
	$extraStyle = '';
	if($i>=$numItems) {
		$extraStyle = 'display:none;';
	}
?>
<div class="tweet" style='margin-bottom:10px;<?php echo $extraStyle; ?>'>
<img src="<?php echo $tweet['profile_image_url']; ?>" align="left" width="48" height="48"
	style="margin:5px;"
	alt="<?php echo $tweet['from_user']; ?>" />
<?php echo $tweet['text']; ?><br/>by <?php echo $tweet['from_user']; ?>. Link: 
<?php echo html_entity_decode($tweet['source']); ?>
</div>
<div style="clear:both;"></div>
<?php
	$i++;
}
?>
