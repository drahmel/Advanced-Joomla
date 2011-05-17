<?php
/**
* @version $Id: mod_storelocator.php 5203 2010-07-27 01:45:14Z DanR $
* This module will displays a Google Map widget of store locations
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
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
*/
$app                = JFactory::getApplication();
print_r($this);
?>

<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
  #map_canvas { height: 100% }
</style>
<script type="text/javascript"
    src="http://maps.google.com/maps/api/js?sensor=false">
</script>
<script type="text/javascript">
var storeMarker;
var map;

function initStoreLocator() {
	var latlng = new google.maps.LatLng(-34.397, 150.644);
	var myOptions = {
		zoom: 8,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(
		document.getElementById("map_canvas"),
		myOptions
	);
	storeMarker = new google.maps.Marker({
		position: latlng,
		title:"Main street (click here)",
		animation: google.maps.Animation.DROP,
		map: map
	});
	storeMarker.iw = new google.maps.InfoWindow({
		content: contentString
	}); 
	google.maps.event.addListener(storeMarker, 'click', toggleBounce);
}
var contentString = '<a href="/">Main street</a>';
var infowindow = new google.maps.InfoWindow({
    content: contentString
});

function toggleBounce(o,o2) {
	console.log(o);
	console.log(this);
	if (storeMarker.getAnimation() != null) {
		storeMarker.setAnimation(null);
		infowindow.close();
	} else {
		storeMarker.setAnimation(google.maps.Animation.BOUNCE);
		infowindow.open(map,storeMarker);
	}
}
</script>

<div id="map_canvas" style="width: 100%; height: 300px;"></div>

<script type="text/javascript">
function junk() {
<?php
$markers = array(array('name'=>'first','lat'=>62.281819,'long'=>-150.287132));

// add in reverse order so higher rated pins appear on top of map (zindex)
foreach ($markers as $i => $marker) { 
	$enc_name = htmlspecialchars($marker['name'], ENT_QUOTES, 'UTF-8');
}
?>
}

window.onload = function() {
	initStoreLocator();
};
</script>
<style>
	.storelocatorToolTip { 
		-moz-border-radius:4px;
		-webkit-border-radius:4px; 
	}
	.storelocatorToolTip { 
		background:-moz-linear-gradient(top,#ffffff,#eeeeee,#cccccc);
		background:-webkit-gradient(linear,left top,left bottom,from(#ffffff),color-stop(50%, #eeeeee),to(#cccccc)); 
	}
</style>

