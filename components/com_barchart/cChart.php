<?php
class cChart {
	static function getColorVals($colorName) {
		static $colors;
		if(!is_array($colors)) {
			$colors = array('aliceblue'=>'#f0f8ff','antiquewhite'=>'#faebd7','aqua'=>'#00ffff','aquamarine'=>'#7fffd4','azure'=>'#f0ffff','beige'=>'#f5f5dc','bisque'=>'#ffe4c4',
				'black'=>'#000000','blanchedalmond'=>'#ffebcd','blue'=>'#0000ff','blueviolet'=>'#8a2be2','brown'=>'#a52a2a','burlywood'=>'#deb887','cadetblue'=>'#5f9ea0',
				'chartreuse'=>'#7fff00','chocolate'=>'#d2691e','coral'=>'#ff7f50','cornflowerblue'=>'#6495ed','cornsilk'=>'#fff8dc','crimson'=>'#dc143c','cyan'=>'#00ffff',
				'darkblue'=>'#00008b','darkcyan'=>'#008b8b','darkgoldenrod'=>'#b8860b','darkgray'=>'#a9a9a9','darkgrey'=>'#a9a9a9','darkgreen'=>'#006400',
				'darkkhaki'=>'#bdb76b','darkmagenta'=>'#8b008b','darkolivegreen'=>'#556b2f','darkorange'=>'#ff8c00','darkorchid'=>'#9932cc','darkred'=>'#8b0000',
				'darksalmon'=>'#e9967a','darkseagreen'=>'#8fbc8f','darkslateblue'=>'#483d8b','darkslategray'=>'#2f4f4f','darkslategrey'=>'#2f4f4f','darkturquoise'=>'#00ced1',
				'darkviolet'=>'#9400d3','deeppink'=>'#ff1493','deepskyblue'=>'#00bfff','dimgray'=>'#696969','dimgrey'=>'#696969','dodgerblue'=>'#1e90ff','firebrick'=>'#b22222',
				'floralwhite'=>'#fffaf0','forestgreen'=>'#228b22','fuchsia'=>'#ff00ff','gainsboro'=>'#dcdcdc','ghostwhite'=>'#f8f8ff','gold'=>'#ffd700','goldenrod'=>'#daa520',
				'gray'=>'#808080','grey'=>'#808080','green'=>'#008000','greenyellow'=>'#adff2f','honeydew'=>'#f0fff0','hotpink'=>'#ff69b4','indianred'=>'#cd5c5c',
				'indigo'=>'#4b0082','ivory'=>'#fffff0','khaki'=>'#f0e68c','lavender'=>'#e6e6fa','lavenderblush'=>'#fff0f5','lawngreen'=>'#7cfc00','lemonchiffon'=>'#fffacd',
				'lightblue'=>'#add8e6','lightcoral'=>'#f08080','lightcyan'=>'#e0ffff','lightgoldenrodyellow'=>'#fafad2','lightgray'=>'#d3d3d3','lightgrey'=>'#d3d3d3',
				'lightgreen'=>'#90ee90','lightpink'=>'#ffb6c1','lightsalmon'=>'#ffa07a','lightseagreen'=>'#20b2aa','lightskyblue'=>'#87cefa','lightslategray'=>'#778899',
				'lightslategrey'=>'#778899','lightsteelblue'=>'#b0c4de','lightyellow'=>'#ffffe0','lime'=>'#00ff00','limegreen'=>'#32cd32','linen'=>'#faf0e6',
				'magenta'=>'#ff00ff','maroon'=>'#800000','mediumaquamarine'=>'#66cdaa','mediumblue'=>'#0000cd','mediumorchid'=>'#ba55d3','mediumpurple'=>'#9370db',
				'mediumseagreen'=>'#3cb371','mediumslateblue'=>'#7b68ee','mediumspringgreen'=>'#00fa9a','mediumturquoise'=>'#48d1cc','mediumvioletred'=>'#c71585',
				'midnightblue'=>'#191970','red'=>'#ff0000','white'=>'#ffffff','yellow'=>'#FFF799');
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
	static function renderGrid($image,$maxval,$l,$t,$chartWidth,$chartHeight,$base,$labelFontNum=2,$numLines=4) {		
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
	static function renderBars($image,$data,$l,$t,$chartWidth,$chartHeight,$base,$labelFontNum=2,$numLines=4) {
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
			
			//echo "xmin:$xmin, ymin:$ymin, xmax:$xmax, ymax:$ymax,<br/>";
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
		// Populate endColorVals and the color step
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
			//if($col==-1) echo "failed!";
			//echo 'C:'.$col."\n";
			imagefilledrectangle($image,$l,$b-$i,$r,$b-$i+$inc,$col);
		}
		
	}
	static function renderBarChart($titleText='',$xData='',$yData='',$imageType='png',$width=640,$height=480,$ox=38,$oy=20,$titleFontNum = 3,$titleColor = 'yellow') {
		// If image type is empty, use PNG
		$imageType = empty($imageType) ? 'png' : $imageType;
		// If width and heights are empty, use defaults
		$width = empty($width) ? 640 : $width;
		$height = empty($height) ? 480 : $height;
		$inData = cChart::processData($xData,$yData);
		$numRows = count($inData);
		$base = floor(($width - $ox) / $numRows); 
		$chartHeight = $height - (2 * $oy);
		$chartWidth = $numRows * $base;
		$maxval = max($inData);

		// The imagecreate() function only allows 256 colors, so use imagecreatetruecolor()
		$image = @imagecreatetruecolor($width, $height);
		// With GD, the first color allocated becomes the background color
		$bgColor = cChart::getColor($image,'lightyellow');
		cChart::drawGradRect($image,0, 0, $width, $height, 'yellow','indigo');
		
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
	static function processData($inXData,$inYData) {
		if(empty($inXData) || empty($inYData)) { 
			return cChart::sampleData();
		}
		// Break out the data separated by commas
		$xArray = explode(',',$inXData);
		$yArray = explode(',',$inYData);
		//print_r(array_combine($yArray,$xArray));
		return array_combine($xArray,$yArray);
		
	}
		
	static function sampleData($maxVal=400) {
		return array('Jan'=>rand(0,$maxVal),'Feb'=>rand(0,$maxVal),'Mar'=>rand(0,$maxVal),'Apr'=>rand(0,$maxVal),
				'May'=>rand(0,$maxVal),'Jun'=>rand(0,$maxVal),'Jul'=>rand(0,$maxVal),'Aug'=>rand(0,$maxVal),
				'Sept'=>rand(0,$maxVal),'Oct'=>rand(0,$maxVal),'Nov'=>rand(0,$maxVal),'Dec'=>rand(0,$maxVal));
	}
}

?>