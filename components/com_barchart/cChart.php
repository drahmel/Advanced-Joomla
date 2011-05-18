<?php
class cChart {
    var $black,$gray,$yellow,$red,$blue;
    static function getColorVals($colorName) {
    ...
}
    static function getColor($image,$colorName) {
    ...
    }
    static function renderText($image,$inStr='',$tx=10,$ty=10,
        $fontNum = 3,$fontColor='black') {
    ...
    }
    static function renderGrid($image,$maxval,$l,$t,
        $chartWidth,$chartHeight,$base,
        $labelFontNum=2,$numLines=4) {        
    ...
    }
    static function renderBars($image,$data,$l,$t,
        $chartWidth,$chartHeight,$base,$labelFontNum=2,$numLines=4) {
    ...
    }
    static function drawGradRect($image, $l, $t, $r, $b, 
        $startColorName, $endColorName) {
    ...
    }
    static function renderBarChart($titleText,$inData,
        $imageType='png',$width=480,$height=250,
        $ox=38,$oy=20,$titleFontNum = 3,$titleColor = 'darkblue') {
    ...
    }
    static function sampleData($maxVal=400) {
    ...
    }
}

//cChart::renderBarChart("Widget A - Units",cChart::sampleData());
?>
