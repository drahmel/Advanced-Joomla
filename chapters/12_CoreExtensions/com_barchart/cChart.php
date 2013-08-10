<?php
class cChart {
	var $black,$gray,$yellow,$red,$blue;

	static function getColorVals($colorName) {
		static $colors;
		if(!is_array($colors)) {
			$colors = array('aliceblue'=>'#f0f8ff','antiquewhite'=>'#faebd7','yellow'=>'#FFF799');
		}
		if(!isset($colors[$colorName])) {
			$colorName = 'black';
		}
		return array(hexdec(substr($colors[$colorName],1,2)),hexdec(substr($colors[$colorName],3,2)),hexdec(substr($colors[$colorName],5,2)));
	}
	static function getColor($image,$colorName) {
		$colorArray = cChart::getColorVals($colorName);
		return imagecolorallocate($image, $colorArray[0],$colorArray[1],$colorArray[2]);
	}
    
	static function renderText($image,$inStr='',$tx=10,$ty=10,$fontNum = 3,$fontColor='black') {
		$fontColor = cChart::getColor($image,$fontColor);		
		imagestring($image, $fontNum, $tx, $ty, $inStr , $fontColor); 
	}    
    
	static function renderGrid($image, $maxval,$l,$t,$chartWidth, 
		$chartHeight, $base,$labelFontNum=2,$numLines=4) {		
		$colDistance = $maxval / $numLines; 
		$lineDistance = $chartHeight / ($numLines + 1);
	
		// Setup basic colors
		$black = cChart::getColor($image,'black');
		$gray = cChart::getColor($image,'gray');
	
		imagerectangle($image, $l, $t, $l + $chartWidth, $t + $chartHeight, $black);
	
		for($i=0;$i<=($numLines+1);$i++) {
			$ydat = intval(($i * $colDistance));
			$labelWidth = imagefontwidth($labelFontNum) * strlen($ydat);
			$labelHeight = imagefontheight($labelFontNum); 
			
			$xpos = intval((($l - $labelWidth) / 2));
			$xpos = max(1, $xpos);
			$ypos = $t + $chartHeight - intval(($i*$lineDistance));
			
			imagestring($image, $labelFontNum, $xpos, $ypos - intval(($labelHeight/2)), $ydat, $black);
			
			if (!($i == 0) && !($i > $numLines)) {	
				imageline($image, $l - 3, $ypos, $l + $chartWidth, $ypos, $gray);
			}
		}
	}    
    
	static function renderBars($image, $data,$l,$t, $chartWidth, $chartHeight, $base,$labelFontNum=2,$numLines=4) {
		// Setup basic colors
		$black = cChart::getColor($image,'black');
		$indigo = cChart::getColor($image,'indigo');
	
		$maxval = max($data);
		$colDistance = $maxval / $numLines;
		$padding = 3; 
		$yscale = $chartHeight / (($numLines+1) * $colDistance);
		for ($i = 0; list($xval, $yval) = each($data); $i++) {
			$ymax = $t + $chartHeight;
			$ymin = $ymax - intval(($yval*$yscale));
			$xmax = $l + ($i+1)*$base - $padding;
			$xmin = $l + $i*$base + $padding;
			
			cChart::drawGradRect($image,$xmin, $ymin, $xmax, $ymax, 'black','indigo');
			$labelWidth = imagefontwidth($labelFontNum) * strlen($xval);
			
			$xpos = $xmin + intval((($base - $labelWidth) / 2));
			$xpos = max($xmin, $xpos);
			$ypos = $ymax + 4; 
			
			imagestring($image, $labelFontNum, $xpos, $ypos, $xval, $black);
		} 
	}
    
	static function drawGradRect($image, $l, $t, $r, $b, $startColorName, $endColorName) {
		$startColorVals = cChart::getColorVals($startColorName);
		$endColorVals = cChart::getColorVals($endColorName);
		$colorStep = array();
		
		$inc = 5;
		$height = $b-$t;
		if($height==0) {
			$height=1;
		}
		for($i=0;$i<3;$i++) {
			if($startColorVals[$i]==$endColorVals[$i]) {
				$endColorVals[$i] -= 1;
			}
			$colorStep[$i] = ($endColorVals[$i]-$startColorVals[$i]) / $height;
		}
		for($i=$inc;$i<$height;$i+=$inc) {
			$clour1 = ($i*$colorStep[0])+$startColorVals[0];
			$clour2 = ($i*$colorStep[1])+$startColorVals[1];
			$clour3 = ($i*$colorStep[2])+$startColorVals[2];
			$col=imagecolorallocate($image,$clour1,$clour2,$clour3);
			imagefilledrectangle($image,$l,$b-$i,$r,$b-$i+$inc,$col);
		}
		
	}    
    
	static function renderBarChart($titleText, $inData, $imageType='png',
		$width=480, $height=250, $ox=38,$oy=20,$titleFontNum = 3,$titleColor = 'darkblue') {
		$numRows = count($inData);
		
		$base = floor(($width - $ox) / $numRows); 
		$chartHeight = $height - (2 * $oy);
		$chartWidth = $numRows * $base;
		$maxval = max($inData);
	
		$image = imagecreate($width, $height);
		// With GD, the first color allocated becomes the background color
		$white = cChart::getColor($image,'lightyellow');
		
		$textWidth = imagefontwidth($titleFontNum) * strlen($titleText);
		// Calculate x & y of title
		$tx = intval(($ox + ($chartWidth - $textWidth)/2));
		$ty = 5; 
		cChart::renderText($image,$titleText,$tx,$ty,$titleFontNum,$titleColor);
	
		cChart::renderGrid($image,$maxval,$ox,$oy,$chartWidth,$chartHeight,$base);
		cChart::renderBars($image,$inData,$ox,$oy,$chartWidth,$chartHeight,$base);
		// Send header with MIME-type and render image in proper format
		switch($imageType) {
			case 'jpg':
				header("Content-type: image/jpeg");
				imagejpeg($image);
				break;
			case 'png':
				header("Content-type: image/png");
				imagepng($image);
				break;
			case 'gif':
				header("Content-type: image/gif");
				imagegif($image);
				break;
		}
		imagedestroy($image);
	}    
    
	static function sampleData($maxVal=400) {
	    return array('Jan'=>rand(0,$maxVal), 'Feb'=>rand(0,$maxVal),
		'Mar'=>rand(0,$maxVal), 'Apr'=>rand(0,$maxVal),
		'May'=>rand(0,$maxVal), 'Jun'=>rand(0,$maxVal,
		'Jul'=>rand(0,$maxVal),'Aug'=>rand(0,$maxVal), 
		'Sept'=>rand(0,$maxVal), 'Oct'=>rand(0,$maxVal), 
		'Nov'=>rand(0,$maxVal), 'Dec'=>rand(0,$maxVal));
	}    
    
}

//cChart::renderBarChart("Widget A - Units",cChart::sampleData());
?>
