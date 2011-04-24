<?php
/**
* @version $Id: mod_twitter.php 5203 2010-07-27 01:45:14Z DanR $
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
if(!function_exists('getTwitter')) {
	function getTwitter($searchStr,$forceUpdate=false) {
		$searchStr = urlencode($searchStr);
		// Make cache last for 1 hour -- 60s * 60m
		$expire = 60*60;
		$keyName = 'twitter_key_'.md5($searchStr);
		$tweetData = false;
		if(!$forceUpdate) {
			$tweetData = SimpleCache::getCache($keyName,$expire);
		}
		if($tweetData===false) {
			$url = 'http://search.twitter.com/search.json?q='.$searchStr;	//.'&nothing='.rand(0,2000);
			$tweets = file_get_contents($url);
			$tweetData = json_decode($tweets,true);
			SimpleCache::setCache($keyName,$tweetData);
		}
		return $tweetData;
	}
}
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
			$apiKey = 'fe138523e17c0258b0b837dc6de66154';
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
$tweetData = getTwitter($searchStr);
$flickrData = getFlickr($searchStr);
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
shuffle($flickrData['photos']['photo']);
$photo = $flickrData['photos']['photo'][0];
$baseURL = $photo['url'];
?>
<div class="image">
	<div style='width:400px;height:200px;background-position:center; background-image:url(<?php echo $baseURL; ?>)'></div>
	<div><?php echo $photo['title'].' from '.$photo['ownername']; ?></div>
</div>
<?php
//echo "<img src='$baseURL' />";
//print_r($flickrData);
$i=0;
//shuffle($tweetData['results']);
foreach($tweetData['results'] as $tweet) {
	$extraStyle = '';
	if($i>=4) {
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
