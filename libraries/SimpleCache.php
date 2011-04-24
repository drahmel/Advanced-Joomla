<?php
class SimpleCache {
	static public $basePath = 'cache/';
	
	static function getCache($key,$expireHours=24,$forceUpdate=false) {
		$key = strtolower($key);
		$cacheName = self::$basePath.$key.'.dat';
		$outInfo = false;
		if(is_file($cacheName)) {
			$dateDiff = time() - filemtime($cacheName);
			$deltaHours = $dateDiff/(60*60);
			if($deltaHours<$expireHours) {
				if(!$forceUpdate) {
					$data = file_get_contents($cacheName);
					$outInfo = unserialize($data);
				}
			}
		}
		return $outInfo;
	}	
	static function setCache($key,$data) {
		$key = strtolower($key);
		$cacheName = self::$basePath.$key.'.dat';
		//echo $cacheName;
		$result = file_put_contents($cacheName,serialize($data));
		if($result===false) {
			error_log("SimpleCache write fail:".$cacheName);
		}
	}
}

//SimpleCache::$basePath = "/Users/drahmel/Sites/local.joomla16.com/docs/".SimpleCache::$basePath;
/*
$key = 'FBStuff2';
$data = SimpleCache::getCache($key,1);
if($data===false) {
	$tempdata = array('text'=>"This is my test");
	SimpleCache::setCache($key,$tempdata);
}
echo "Result:".print_r($data,true);
*/
?>
