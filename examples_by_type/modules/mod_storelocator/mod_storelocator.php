<?php
/**
* @version $Id: mod_storelocator.php 5203 2011-07-27 01:45:14Z DanR $
* This module will displays a Google Map widget of store locations
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Get the module parameters set in the Module Manager
$markerParam = $params->get('markers');
$markerEntries = explode('|',$markerParam);
$markers = array();
foreach($markerEntries as $markerEntry) {
	if(!empty($markerEntry)) {
		$temp = explode(',',$markerEntry);
		$markers[] = array(
			'lat'=>$temp[0],
			'long'=>$temp[1],
			'title'=>$temp[2],
			'url'=>$temp[3]
		);	
	}
}
// Add some dummy data if no data supplied
if(empty($markers)) {
	$markers = array(
		array('title'=>'Main St. store',
			'lat'=>-34.397,'long'=>150.644,
			'url'=>'/main-st'),
		array('title'=>'2nd St. store',
			'lat'=>-34.387,'long'=>150.634,
			'url'=>'/2nd-st'),
		array('title'=>'Plain Ave. store',
			'lat'=>-34.357,'long'=>150.604,
			'url'=>'/plain-ave'),
		array('title'=>'Downtown store',
			'lat'=>-34.307,'long'=>150.604,
			'url'=>'/downtown')
	);
	
}
$document = &JFactory::getDocument();
$app                = JFactory::getApplication();

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
	$encodeTitle = htmlspecialchars($marker['title'], ENT_QUOTES, 'UTF-8')
?>
	var storeMarker = new google.maps.Marker({
		position: new google.maps.LatLng(
			<?php echo $marker['lat']; ?>, 
			<?php echo $marker['long']; ?>
		),
		title:"<?php echo $encodeTitle; ?> (click here)",
		animation: google.maps.Animation.DROP,
		map: map
	});
	var contentString = '<center><a href="<?php echo $marker['url']; ?>">'+
		'<?php echo $encodeTitle; ?></a></center>';
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
window.onload = function() {
	initStoreLocator();
};

</script>

<div id="map_canvas" style="width: 100%; height: 300px;"></div>

