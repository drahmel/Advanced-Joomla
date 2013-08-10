<?php
function mysqlConnect($username,$password,$dbName='',$host='localhost') {
	$success = false;
	$link = mysql_connect($host, $username, $password);
	if (!$link) {
		$success = mysql_error();
	} else {
		if(!empty($dbName)) {
			$result = mysql_select_db($dbName,$link);
			if($result) {
				$success = $link;	
			} else {
				$success = mysql_error();
			}
		} else {
			$success = true;	
		}
	}
	return $success;
}
define('NL','<br/>');
mysqlConnect('USERNAME','PASSWORD','joomla16');
// Turn off unique checks during conversion to lower disk i/o
$sql = "SET unique_checks=0;";
$result = mysql_query($sql);

$sqlGetTables = "show tables;";

// Get list of all Joomla tables
$result = mysql_query($sqlGetTables);
while($row = mysql_fetch_row($result)) {
	$sql = "ALTER TABLE `{$row[0]}` ENGINE = InnoDB;";
	$resultAlter = mysql_query($sql);
	echo $sql.NL;
	if(!$result) {
		echo mysql_error().NL;
	}
}
$sql = "SET unique_checks=1;";
$result = mysql_query($sql);

?>
