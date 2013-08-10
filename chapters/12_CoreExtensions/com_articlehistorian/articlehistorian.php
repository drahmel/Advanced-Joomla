<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Get request ID from query string variable
$task = JRequest::getVar('task','display');
switch($task) {
	case 'restore':
		JToolBarHelper::title(JText::_('Article Historian: Restoring Version'));
		restoreRevision();
		break;
	default:
	case 'display':
		JToolBarHelper::title(JText::_('Article Historian: Revisions'));
		showRevisions();
		break;	  
}

function showRevisions() {
	// Get instance of database object
	$db =& JFactory::getDBO();
	// Create query to return id, title, and text of article
	$query = "SELECT UNIX_TIMESTAMP(revision_date) 
		ts,id,title,introtext
		FROM jos_content_history 
		ORDER BY id, revision_date DESC
		LIMIT 100";
	$db->setQuery( $query, 0);
	// Execute query
	if ($rows = $db->loadAssocList()) {
		echo "<table class='adminlist'><tbody>";
		echo "<thead><tr>
			<th><a href=''>Rev date</a></th>
			<th>id</th>
			<th>Title</th>
			<th>Contents</th>
			<th></th>
			</tr></thead>";
		$lastID = $rows[0]['id'];
		foreach($rows as $row) {
			// Load article title and text into variables
			$articleTitle = $row['title'];
			$articleBody = $row['introtext'];
			// Strip all the HTML from the article text
			$articleBody = strip_tags($articleBody);
			// Strip all non-alpha, numeric, or punctuation
			$articleBody = preg_replace(
				"/[^a-zA-Z0-9 .?!$()\'\"]/", "", $articleBody);
			// If length is > 200, truncate length
			if(strlen($articleBody) > 200) {
				$articleBody = substr($articleBody, 0, 200);
			}
			echo "<tr>";
			//$ts = strtotime($row->revision_date);
			$ts = $row['ts'];
			$dateStr = date('D m/d/y h:i:s',$ts);
			echo "<td>{$dateStr}</td>";
			echo "<td>{$row['id']}</td>";
			echo "<td>$articleTitle</td>";
			echo "<td>$articleBody</td>";
			echo "<td><a href='/administrator/index.php?option=com_article_historian&task=restore&id={$row['id']}&ts={$ts}'>Restore</a></td>";
			echo "</tr>";
		}
		echo "</tbody></table>";
	}
}

function restoreRevision() {
	$id = intval(JRequest::getVar('id'));
	$ts = intval(JRequest::getVar('ts'));
	$verbose = intval(JRequest::getVar('verbose'),0);
	if($id>0 && $ts>0) {
		$db =& JFactory::getDBO();
		echo "Restoring ID#$id to revision dated " . 
			date(DATE_COOKIE,$ts). "<br/>";
		$sql = "SELECT * FROM jos_content_history 
			WHERE UNIX_TIMESTAMP(revision_date)='$ts' 
			AND id='$id' ";
		$db->setQuery( $sql, 0);
		// Execute query
		if ($row = $db->loadAssoc()) {
			$sqlCurrent = "SELECT * FROM jos_content 
				WHERE id='{$id}' ";
			$db->setQuery( $sqlCurrent, 0);
			$updateArray = array();
			$currentVersion = $db->loadAssoc();
			foreach($currentVersion as $key => &$val) {
				// Skip version key -- we'll increment it
				if($key!='version') {
					$shortCurrent = substr($val,0,20);
					$shortRevision = substr($row[$key],0,20);
					if(isset($row[$key]) && $row[$key]!=$val) {
						if($verbose) {
							echo "Reverting: $key from {$shortCurrent} 
								<b>to</b> {$shortRevision}<br/>";
						}
						$updateArray[$key] = $row[$key];
					} else {
						if($verbose) {
							echo "NO CHANGE: $key from {$shortCurrent}
								<b>to</b> {$shortRevision}<br/>";
						}
					}
					
				} else {
					$updateArray[$key] = $val+1;
					if($verbose) {
						echo "$key = {$updateArray[$key]}<br/>";
					}
				}
			}
			if(!empty($updateArray)) {
			 echo "Updating ".count($updateArray)." fields.<br/>";
				$sqlUpdate = "UPDATE jos_content SET ";
				$count = 0;
				foreach($updateArray as $field => $val) {
					if($count > 0) {
						$sqlUpdate .= " , ";
					}
					$data = $db->getEscaped($val);
					$sqlUpdate .= " $field = '{$data}' ";
					$count++;
				}
				$sqlUpdate .= " WHERE id = '$id' ";
				$db->setQuery( $sqlUpdate, 0);
				$db->query();
				$updatedRows = $db->getAffectedRows();
				echo "Updated $updatedRows row.<br/>";
			} else {
				echo "Nothing to update.<br/>";	   
			}
			if($verbose) {
				echo $sqlUpdate;
			}
			//print_r($row);
		}
		echo "<a href='/administrator/index.php?option=com_article_historian'>Return to revision list</a><br/>";
	}
}


