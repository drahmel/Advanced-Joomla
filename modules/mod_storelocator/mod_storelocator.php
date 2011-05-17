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
$markers = array(
	array('name'=>'Main St. store','lat'=>-34.397,'long'=>150.644,'url'=>'/main-st'),
	array('name'=>'2nd St. store','lat'=>-34.387,'long'=>150.634,'url'=>'/2nd-st'),
	array('name'=>'Plain Ave. store','lat'=>-34.357,'long'=>150.604,'url'=>'/plain-ave'),
	array('name'=>'Downtown store','lat'=>-34.307,'long'=>150.604,'url'=>'/downtown')
);

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
	var latlng = new google.maps.LatLng(-34.357, 150.604);
	var myOptions = {
		zoom: 11,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(
		document.getElementById("map_canvas"),
		myOptions
	);
<?php
foreach ($markers as $i => $marker) { 
?>
	var storeMarker = new google.maps.Marker({
		position: new google.maps.LatLng(
			<?php echo $marker['lat']; ?>, 
			<?php echo $marker['long']; ?>
		),
		title:"<?php echo $marker['name']; ?> (click here)",
		animation: google.maps.Animation.DROP,
		map: map
	});
	var contentString = '<center><a href="<?php echo $marker['url']; ?>">'+
		'<?php echo $marker['name']; ?></a></center>';
	storeMarker.iw = new google.maps.InfoWindow({
		content: contentString
	}); 
	google.maps.event.addListener(storeMarker, 'click', toggleBounce);
<?php } ?>
}

function toggleBounce() {
	console.log(this.iw);
	if (this.getAnimation() != null) {
		this.setAnimation(null);
		this.iw.close();
	} else {
		this.setAnimation(google.maps.Animation.BOUNCE);
		this.iw.open(map,this);
	}
}
</script>

<div id="map_canvas" style="width: 100%; height: 300px;"></div>

<script type="text/javascript">
function junk() {
<?php
foreach ($markers as $i => $marker) { 
	$enc_name = htmlspecialchars($marker['name'], ENT_QUOTES, 'UTF-8');
}
?>
}

window.onload = function() {
	initStoreLocator();
};
</script>

