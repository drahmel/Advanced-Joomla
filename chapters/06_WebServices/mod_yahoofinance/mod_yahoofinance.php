<?php
/**
* @version $Id: mod_yahoofinance.php 5203 2012-08-22 02:31:14Z DanR $
* This module will displays data from the Yahoo Finance API
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('SimpleCache');

// Get the module parameters set in the Module Manager
$cacheExpire = $params->get('cache_expire', 4);
$ticker = $params->get('ticker_symbol','DMD');

// Make sure caching is On to prevent site from hitting Yahoo excessively
if(!$cacheExpire) {
    echo 'No entries available<br/>';
    return;
}

$document = JFactory::getDocument();

// In case multiple modules used on the same page, avoid redefining
if(!function_exists('getYahooFinance')) {
    function getYahooFinance($ticker,$expire,$forceUpdate=true) {
    	$params = 'l1c1va2x98asd9jm4rr5p5p6s7';
	$url = "http://finance.yahoo.com/d/quotes.csv?s={$ticker}&f={$params}";

        $keyName = 'yahoofinance_key_'.md5($url);
        $data = false;
        if(!$forceUpdate) {
            $data = SimpleCache::getCache($keyName,$expire);
        }
        if($data===false) {
	$csv = file_get_contents($url);
	$csv = trim($csv);
	$values = explode(',',$csv);
		$data['price'] = $values[0];
		$data['change'] = $values[1];
		$data['volume'] = $values[2];
		$data['avg_daily_volume'] = $values[3];
		$data['stock_exchange'] = $values[4];
		$data['market_cap'] = $values[5];
		$data['book_value'] = $values[6];
		$data['ebitda'] = $values[7];
		$data['dividend_per_share'] = $values[8];
		$data['dividend_yield'] = $values[9];
		$data['earnings_per_share'] = $values[10];
		$data['52_week_high'] = $values[11];
		$data['52_week_low'] = $values[12];
		$data['50day_moving_avg'] = $values[13];
		$data['200day_moving_avg'] = $values[14];
		$data['price_earnings_ratio'] = $values[15];
		$data['price_earnings_growth_ratio'] = $values[16];
		$data['price_sales_ratio'] = $values[17];
		$data['price_book_ratio'] = $values[18];
		$data['short_ratio'] = $values[19];
		$json = json_encode($data);
            SimpleCache::setCache($keyName,$json);
        } else {
        	$data = json_decode($data,true);
        }
        return $data;
    }
}

$data = getYahooFinance($ticker,$cacheExpire);

$display_fields = array('price', 'book_value', 'price_earnings_ratio',
    'earnings_per_share');

?>
<table class="table table-striped table-bordered table-hover 
    table-condensed">
	<tr>
		<th>Ticker</th><th><?php echo $ticker; ?></th>
	</tr>
	<?php foreach($display_fields as $display_field): ?>
	<tr>
		<td><?php echo ucwords(str_replace('_',' ',$display_field)); ?></td>
		<td><?php echo $data[$display_field]; ?></td>
	</tr>
	<?php endforeach; ?>
</table>

