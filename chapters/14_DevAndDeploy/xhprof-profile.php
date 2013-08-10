<?php
define('XHPROF_ENABLED',true);
if(XHPROF_ENABLED) {
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
}

if(XHPROF_ENABLED) {
    $id='general';
    $type='none';
	$xhprof_data = xhprof_disable();
	
	$XHPROF_ROOT = realpath(dirname(__FILE__) .'/../');
	include_once $XHPROF_ROOT . "/admin/xhprof_html/xhprof_lib/utils/xhprof_lib.php";
	include_once $XHPROF_ROOT . "/admin/xhprof_html/xhprof_lib/utils/xhprof_runs.php";
	
	// save raw data for this profiler run using default
	// implementation of iXHProfRuns.
	$xhprof_runs = new XHProfRuns_Default();
	
	$ns = "xhprof_$type"; 
	// save the run under a namespace "xhprof_foo"
	$run_id = $xhprof_runs->save_run($xhprof_data,$ns);
	
	$url = "/administrator/xhprof_html/index.php?run=$run_id&source=$ns";
	echo "<a href='$url' target='_xhprof_$id'>Profile available here: $url</a><br/>\n";
	echo "$run_id<br/>\n";
}

?>
