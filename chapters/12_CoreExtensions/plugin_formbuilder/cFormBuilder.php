<?php

class cFormBuilder {
	var $dbHandle;
	var $tableFields;
	var $tableName;
	var $tableStyle;
	var $configArray;
	
	function cFormBuilder() {
		$this->tableStyle = "\n<style type='text/css'> table.tblMyForm { background-color: #FFFFFF; border: none; color: #495E49; font-family: arial, helvetica, sans-serif; font-size: 12px; text-align: left; }"
			. " table.tblMyForm a:link, table a:visited, table a:active { background-color: transparent; color: #0096BD; text-decoration: none; }"
			. " table.tblMyForm a:hover { background: transparent; color: #000000; text-decoration: underline; }"
			. " table.tblMyForm caption { background-color: transparent; color: #67BD2A; font-family: Georgia, serif; font-size: 24px; padding-bottom: 12px; padding-left: 8px; text-align: left; }"
			. " table.tblMyForm, table.tblMyForm td, table.tblMyForm th { margin: 0; padding: 0; }"
			. " table.tblMyForm tbody td, tbody th { background-color: #D6F2C3; border-bottom: 2px solid #B3DE94; border-top: 3px solid #FFFFFF; padding: 9px; }"
			. " table.tblMyForm.tbody tr.odd th, tbody tr.odd td { background-color: #C8EDAE; border-bottom: 2px solid #67BD2A; }"
			. " table.tblMyForm tbody table.tblMyForm tr:hover td, table.tblMyForm.tbody tr:hover th { background-color: #BAE899; }"
			. " table.tblMyForm td, table.tblMyForm th { vertical-align: middle; }"
			. " table.tblMyForm tfoot td, table.tblMyForm tfoot th { font-weight: bold; padding: 4px 8px 6px 9px; }"
			. " table.tblMyForm thead th { font-family: arial, helvetica, sans-serif; font-size: 14px; font-weight: bold; line-height: 19px; padding: 0 8px 2px 8px; white-space: nowrap; } </style>\n";
	}
	
	function dbConnect() {
		$hostURL = "localhost";
		$dbUsername = "root";
		$dbPassword = "orange88";
		$useDatabase = "joomlasvn";
		// connection to the database
		$this->dbHandle = mysql_connect($hostURL, $dbUsername, $dbPassword)  or die("Unable to connect to MySQL");
		
		// select a database to work with
		$selected = mysql_select_db($useDatabase, $this->dbHandle)  or die("Could not select examples");
	}
	function dbClose() {
		// close the connection
		mysql_close($this->dbHandle);
	}
	function renderSingleEntry($asTable=true,$readOnly=false,$cssClass='',$newFlag=0) {
		$outStr = $this->tableStyle;
		if($newFlag) {
			$destinationArticle = $this->configArray['destid'];
			$formAction = "index.php?option=com_content&view=article&id=$destinationArticle&Itemid=74";
		} else {
			$formAction = "";
		}
		$outStr .= "<form id='form1' class='frm$cssClass' name='$this->tableName' method='post' action='$formAction'>\n";

		if($asTable) {
			$outStr .= "<table class='tbl$cssClass' >\n";
		}
		if(!$newFlag) {
			$result = mysql_query( "SELECT * FROM ".$this->tableName." LIMIT 1;", $this->dbHandle );
			$rowVals = array();
			if($result) {
				$rowVals = @mysql_fetch_array($result);
			}
		}
		$i=0;
		foreach($this->tableFields as $fieldName => $fieldData) {
			$tempLen = $fieldData['len'];
			//$outStr .= $fieldName .":".$rowVals[$fieldName].":".."<br />";
			//$outStr .= ($row1[3] == "PRI") ? " primary_key=\"yes\" />" : " />";
			if(!$newFlag) {
				$myVal = $rowVals[$fieldName];
			} else {
				$myVal = '';
			}
			$myCaption = $fieldData['caption'];
			if($asTable) {
				if($readOnly) {
					$outStr .= "<tr id='tr$i' ><th id='th$i' >$myCaption</th><td id='td$i'>$myVal</td></tr>\n";
				} else {
					$outStr .= "<tr id='tr$i' ><th id='th$i' >$myCaption</th><td id='td$i'><input type='text' name='$fieldName' id='$fieldName' value='$myVal' size='$tempLen' /></td></tr>\n";
				}					
			} else {
				$outStr .= "<label id='lbl$i' >".$fieldName . " <input type='text'  id='fld$i' name='$fieldName' value='$myVal' />" . "</label><br />\n";
			}
			$i++;
		}
		if($asTable) {
			$outStr .= "<tr class='tr$cssClass' ><td class='td$cssClass' ></td><td>";
		}	
		if(!$newFlag) {
			$outStr .="<input  class='btnSubmit$cssClass' type='submit' value='&lt;'>";
			$outStr .=" Record #1 ";
			$outStr .="<input type='submit' value='&gt;'><br />";
			
			$outStr .="<input  class='btnAdd$cssClass' type='submit' value='New'>";
			$outStr .="<input class='btnEdit$cssClass'  type='submit' value='Edit'>";
			$outStr .="<input class='btnDelete$cssClass'  type='submit' value='Delete'>";
		} 			
		$outStr .="<input  class='btnSave$cssClass' type='submit' value='Save'>";
		if($asTable) {
			$outStr .= "</td></tr>\n";
			$outStr .= "</table>\n";
		}
			
		$outStr .= '</form>';
		return $outStr;
	}
	function renderGrid($asTable=true,$readOnly=false,$cssClass='') {
		$outStr = $this->tableStyle;
		$outStr .= "<table  class='tbl$cssClass' >\n";
			
		$result = mysql_query( "SELECT * FROM ".$this->tableName." LIMIT 20;", $this->dbHandle );
		$outStr .="<tr class='trh$cssClass'>\n";
		$j=0;
		foreach($this->tableFields as $fieldName => $fieldData) {
			$myFldCaption = $fieldData['caption'];
			$outStr .= "<th class='th$j'>$myFldCaption</th>\n";
			$j++;
		}
		$outStr .="</tr>\n";
		$i = 0;
		while($rowVals = @mysql_fetch_array($result)) {
			$outStr .="<tr id='tr$i'>\n";
			$j = 0;
			foreach($this->tableFields as $fieldName => $fieldData) {
				$tempLen = $fieldData['len'];
				//$outStr .= $fieldName .":".$rowVals[$fieldName].":".."<br />";
				//$outStr .= ($row1[3] == "PRI") ? " primary_key=\"yes\" />" : " />";
				$myVal = $rowVals[$fieldName];
				if($readOnly) {
					$outStr .= "<td id='td$j'>$myVal</td>\n";
				} else {
					$outStr .= "<td id='td$j'><input type='text' name='$fieldName' id='$fieldName' value='$myVal' size='$tempLen' /></td>\n";
				}	
				$j++;		
			}
			$outStr .="</tr>\n";
			$i++;
		}
		$outStr .= "</table>\n";
			
		$outStr .= '</form>';
		return $outStr;
		
	}
	function populateFields($tableName="jos_users",$ignoreAutoInc=true) {
		//$tableSchema = "{table:bwp_disconnection_forms,submitted_date:{caption:'Submit Date',ignore:0,type:'text',key:0},requested_disconn_date:{caption:'Requested Disconnect Date',ignore:0,type:'text',key:0},service_type:{caption:'Type',ignore:0,type:'text',key:0},social_security:{caption:'SSN',ignore:0,type:'text',key:0},drivers_license:{caption:'DL',ignore:0,type:'text',key:0},drivers_license_state:{caption:'DL State',ignore:0,type:'text',key:0},account_number:{caption:'Account',ignore:0,type:'text',key:0},personal_name:{caption:'Name',ignore:0,type:'text',key:0},personal_phone:{caption:'Phone',ignore:0,type:'text',key:0},personal_phone_type:{caption:'Phone Type',ignore:0,type:'text',key:0},10:{name:'personal_address1',caption:'personal_address1',ignore:0,type:'text',key:0},11:{name:'personal_address2',caption:'personal_address2',ignore:0,type:'text',key:0},12:{name:'personal_city',caption:'personal_city',ignore:0,type:'text',key:0},13:{name:'personal_state',caption:'personal_state',ignore:0,type:'text',key:0},14:{name:'personal_zip',caption:'personal_zip',ignore:0,type:'text',key:0},15:{name:'personal_email',caption:'personal_email',ignore:0,type:'text',key:0},16:{name:'personal_cell_phone',caption:'personal_cell_phone',ignore:0,type:'text',key:0},17:{name:'personal_pager',caption:'personal_pager',ignore:0,type:'text',key:0},18:{name:'personal_bill_address1',caption:'personal_bill_address1',ignore:0,type:'text',key:0},19:{name:'personal_bill_address2',caption:'personal_bill_address2',ignore:0,type:'text',key:0},20:{name:'personal_bill_city',caption:'personal_bill_city',ignore:0,type:'text',key:0},21:{name:'personal_bill_state',caption:'personal_bill_state',ignore:0,type:'text',key:0},22:{name:'personal_bill_zip',caption:'personal_bill_zip',ignore:0,type:'text',key:0},23:{name:'emergency_name',caption:'emergency_name',ignore:0,type:'text',key:0},24:{name:'emergency_phone',caption:'emergency_phone',ignore:0,type:'text',key:0},25:{name:'emergency_address1',caption:'emergency_address1',ignore:0,type:'text',key:0},26:{name:'emergency_address2',caption:'emergency_address2',ignore:0,type:'text',key:0},27:{name:'emergency_city',caption:'emergency_city',ignore:0,type:'text',key:0},28:{name:'emergency_state',caption:'emergency_state',ignore:0,type:'text',key:0},29:{name:'emergency_zip',caption:'emergency_zip',ignore:0,type:'text',key:0},30:{name:'dogs_yesorno',caption:'dogs_yesorno',ignore:0,type:'text',key:0},31:{name:'dogs_howmany',caption:'dogs_howmany',ignore:0,type:'text',key:0},32:{name:'adults_remaining',caption:'adults_remaining',ignore:0,type:'text',key:0},33:{name:'adults_name',caption:'adults_name',ignore:0,type:'text',key:0},34:{name:'donation',caption:'donation',ignore:0,type:'text',key:0},35:{name:'reason',caption:'reason',ignore:0,type:'text',key:0}}";
		$tableSchema = "{table:bwp_disconnection_forms,submitted_date:{caption:'Submit Date',ignore:0,type:'text',key:0},requested_disconn_date:{caption:'Requested Disconnect Date',ignore:0,type:'text',key:0},service_type:{caption:'Type',ignore:0,type:'text',key:0},drivers_license:{caption:'DL',ignore:0,type:'text',key:0},drivers_license_state:{caption:'DL State',ignore:0,type:'text',key:0},account_number:{caption:'Account',ignore:0,type:'text',key:0},personal_name:{caption:'Name',ignore:0,type:'text',key:0},personal_phone:{caption:'Phone',ignore:0,type:'text',key:0},personal_phone_type:{caption:'Phone Type',ignore:0,type:'text',key:0}}";
		$schemaArray = json_decode($tableSchema,true);
		$this->tableFields = array();
		//$outStr .= "<tr><td>" . $i . "</td><td>" . $table . "</td>";
		$result_fld = mysql_query( "SHOW FIELDS FROM ".$tableName, $this->dbHandle );
		while( $row1 = @mysql_fetch_array($result_fld) ) {
			if(!($ignoreAutoInc && $row1["Extra"]=='auto_increment') && !($row1["Field"]=='social_security')) {
				$tempLen = 20;
				if(substr($row1["Type"], 0,7)=='varchar') {
					$front = substr($row1["Type"], 8);
					$tempLen = intval(substr($front,0,strlen($front)-1));
					//echo strlen($front)."-".$tempLen.",";
				}
				$caption = $row1["Field"];
				if(isset($schemaArray[$caption])) {
					$caption = $schemaArray[$caption]['caption'];		
					$this->tableFields[$row1["Field"]] = array('caption'=>$caption,'type'=>$row1["Type"],'key'=>$row1["Key"],'null'=>$row1["Null"],'default'=>$row1["Default"],'extra'=>$row1["Extra"],'len'=>$tempLen);
				}
			}
		}
		$this->tableName = $tableName;
		//print_r($this->tableFields);
	}
	function populateFields2($inSQL,$inJSON,$ignoreAutoInc=true) {
		//$tableSchema = "{table:bwp_disconnection_forms,submitted_date:{caption:'Submit Date',ignore:0,type:'text',key:0},requested_disconn_date:{caption:'Requested Disconnect Date',ignore:0,type:'text',key:0},service_type:{caption:'Type',ignore:0,type:'text',key:0},social_security:{caption:'SSN',ignore:0,type:'text',key:0},drivers_license:{caption:'DL',ignore:0,type:'text',key:0},drivers_license_state:{caption:'DL State',ignore:0,type:'text',key:0},account_number:{caption:'Account',ignore:0,type:'text',key:0},personal_name:{caption:'Name',ignore:0,type:'text',key:0},personal_phone:{caption:'Phone',ignore:0,type:'text',key:0},personal_phone_type:{caption:'Phone Type',ignore:0,type:'text',key:0},10:{name:'personal_address1',caption:'personal_address1',ignore:0,type:'text',key:0},11:{name:'personal_address2',caption:'personal_address2',ignore:0,type:'text',key:0},12:{name:'personal_city',caption:'personal_city',ignore:0,type:'text',key:0},13:{name:'personal_state',caption:'personal_state',ignore:0,type:'text',key:0},14:{name:'personal_zip',caption:'personal_zip',ignore:0,type:'text',key:0},15:{name:'personal_email',caption:'personal_email',ignore:0,type:'text',key:0},16:{name:'personal_cell_phone',caption:'personal_cell_phone',ignore:0,type:'text',key:0},17:{name:'personal_pager',caption:'personal_pager',ignore:0,type:'text',key:0},18:{name:'personal_bill_address1',caption:'personal_bill_address1',ignore:0,type:'text',key:0},19:{name:'personal_bill_address2',caption:'personal_bill_address2',ignore:0,type:'text',key:0},20:{name:'personal_bill_city',caption:'personal_bill_city',ignore:0,type:'text',key:0},21:{name:'personal_bill_state',caption:'personal_bill_state',ignore:0,type:'text',key:0},22:{name:'personal_bill_zip',caption:'personal_bill_zip',ignore:0,type:'text',key:0},23:{name:'emergency_name',caption:'emergency_name',ignore:0,type:'text',key:0},24:{name:'emergency_phone',caption:'emergency_phone',ignore:0,type:'text',key:0},25:{name:'emergency_address1',caption:'emergency_address1',ignore:0,type:'text',key:0},26:{name:'emergency_address2',caption:'emergency_address2',ignore:0,type:'text',key:0},27:{name:'emergency_city',caption:'emergency_city',ignore:0,type:'text',key:0},28:{name:'emergency_state',caption:'emergency_state',ignore:0,type:'text',key:0},29:{name:'emergency_zip',caption:'emergency_zip',ignore:0,type:'text',key:0},30:{name:'dogs_yesorno',caption:'dogs_yesorno',ignore:0,type:'text',key:0},31:{name:'dogs_howmany',caption:'dogs_howmany',ignore:0,type:'text',key:0},32:{name:'adults_remaining',caption:'adults_remaining',ignore:0,type:'text',key:0},33:{name:'adults_name',caption:'adults_name',ignore:0,type:'text',key:0},34:{name:'donation',caption:'donation',ignore:0,type:'text',key:0},35:{name:'reason',caption:'reason',ignore:0,type:'text',key:0}}";
		$schemaArray = json_decode($inJSON,true);
		$this->tableFields = array();
		//print_r($schemaArray);
		$tableName = $schemaArray['table'];
		$result_fld = mysql_query( "SHOW FIELDS FROM ".$tableName, $this->dbHandle );
		while( $row1 = @mysql_fetch_array($result_fld) ) {
			if(!($ignoreAutoInc && $row1["Extra"]=='auto_increment') && !($row1["Field"]=='social_security')) {
				$tempLen = 20;
				if(substr($row1["Type"], 0,7)=='varchar') {
					$front = substr($row1["Type"], 8);
					$tempLen = intval(substr($front,0,strlen($front)-1));
					//echo strlen($front)."-".$tempLen.",";
				}
				$caption = $row1["Field"];
				if(isset($schemaArray[$caption])) {
					$caption = $schemaArray[$caption]['caption'];		
					$this->tableFields[$row1["Field"]] = array('caption'=>$caption,'type'=>$row1["Type"],'key'=>$row1["Key"],'null'=>$row1["Null"],'default'=>$row1["Default"],'extra'=>$row1["Extra"],'len'=>$tempLen);
				}
			}
		}
		$this->tableName = $tableName;
		//print_r($this->tableFields);
	}
	
	function renderButtons() {
		// TODO: Save, Delete, invisible New
		
	}
	function generateFormTemplate() {
		
	}
	function editFormTemplate() {
		
	}
	function updateRecords() {
		return "<h1>Update successful</h1>";	
	}
	function addRecord($inSQL) {
		$sql = str_ireplace("{personal_name}",$_POST['personal_name'],$inSQL);
		$outStr = "<h1>Add record successful</h1>"."<pre>".print_r($_POST,true)."</pre>";
		$outStr .= "<pre>".$sql."</pre>";
		$result_fld = mysql_query( $sql, $this->dbHandle );
		return $outStr;
		

	}
	function renderForm($inReqStr) {
		$output = "";
		parse_str(html_entity_decode($inReqStr),$this->configArray);
		//print_r($this->configArray);
		$db =& JFactory::getDBO();
		
		$formID = $this->configArray["id"];
		$query = "SELECT * FROM tinkerforms_forms WHERE id=$formID; ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		//echo "<h1>Find</h1>";
		$row = $rows[0];
		//print_r($rows);
		//echo "<br />SQL:".$row->sql;
		//echo "<br />JSON:".$row->json;
		switch($row->type) {
			case "G":
				$output = $this->populateFields2($row->sql, $row->json);
				$output .= $this->renderGrid(true,true,@$this->configArray["css"]);
				break;
			case "S":
				$output = $this->populateFields2($row->sql, $row->json);
				$output .= $this->renderSingleEntry(true,false,@$this->configArray["css"],@$this->configArray["new"]);
				break;
			case "A":
				$output = $this->addRecord($row->sql);
				break;
			case "U":
				$output = $this->updateRecords($row->sql);
				break;
		}
		return $output;
	}
	
}
 


?>
