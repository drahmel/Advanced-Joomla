<?php
/**
* @version $Id: mod_articleajax.php 5203 2007-07-27 01:45:14Z DanR $
* This module will display links to 5 current articles.
* Each link has an onMouseOver event to activate an
* Ajax routine to retrieve article information from
* com_articleinfo.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<script type="text/javascript" src="/media/system/js/jquery-13.pack.js"></script>
<script type="text/javascript">
     // Create XML request variable
     var myRequest = false;

function displayAjax(tempArticleID) {
     // Setup component query URL
     var myURL = 
      '/index.php?option=com_articleinfo&format=raw&articlenum=' 
      + tempArticleID;
	jQuery.get(myURL, function(data) { displayReturn(data); } );
}

function displayReturn(data) {
     // Get title and body elements
     myTitle = $('title',data)[0].text;
     myBody = $('body',data)[0].text;
     // Display in popup window
     //overlib(myBody,CAPTION,myTitle,BELOW,RIGHT);
}
</script>

<small>Ajax enabled module</small><br />

<?php

     // Define local variables
     $db =& JFactory::getDBO();
     $user =& JFactory::getUser();
     $userId = (int) $user->get('id');

     // Define date parameters to ensure articles are available
     $nullDate     = $db->getNullDate();
     $now          = date('Y-m-d H:i:s', time());

     // Create search string to display only published articles
     $where = ' a.state = 1 '
          . " AND (a.publish_up = '" . $nullDate . "' " 
          . " OR a.publish_up <= '" . $now . "')"
          . " AND ( a.publish_down = '" . $nullDate . "' " 
          . " OR a.publish_down >= '" . $now . "')";
     
     // Create query
     $query = "SELECT a.id, a.title  \n" .
          " FROM #__content AS a \n" .
          " WHERE " . $where . "\n" ;
     // Execute query to return a maximum of 5 records
     $db->setQuery( $query, 0,5);
     if ($rows = $db->loadObjectList()) {
          // Process each article row
          foreach ( $rows as $row )
          {
               // Process article title
               $artTitle = JText::_($row->title);
               // Create mouseover event to call displayAjax function onmouseout=nd(); 
               echo "<span onmouseover=displayAjax(" . $row->id . "); >";
               // Create link for the current article
               echo "<a href=index.php?option=com_content&view=article&id=" .
                    $row->id . "&Itemid=44>" . $artTitle . "</a><br /></span>\n";
          }
     }
     // Display error message if db retrieval failed.
     echo $db->getErrorMsg();
?>
