<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgContentFormBuilder extends JPlugin {
	var $dbConnect;
	var $myTinkerForm;
	
	// PHP4 compatible constructor
	function plgContentTinkerforms( & $subject ) {
		$this->dbConnect = false;
		parent::__construct( $subject );
	}

	// Name function same as event so it will be called.
	function onPrepareContent(&$article, &$params, $limitstart=0) {
		global $mainframe;

		// Get the parameters in case we need them in a later plug-in
		$plugin =& JPluginHelper::getPlugin('content', 'tinkerforms');
	 	$pluginParams = new JParameter( $plugin->params );
		$startTag = "{tinkerforms";
		$i = 0;
		$startPos = 0;
		$newArticle = "";
		// Set maximum number of forms on a page
		while($i<10) {
			$i++;
			// TODO: Add a character so if tag is in position zero, IF statement won't get a false negative
			$curPos = JString::strpos($article->text, $startTag,$startPos);
			if ($curPos) {
				$endPos = JString::strpos($article->text, "}",$curPos);
				// If they don't close the bracket, use the tag length to designate the end
				if($endPos==0) {
					$endPos = $curPos + strlen($startTag);
				}
				$tfRequest = substr($article->text,$curPos+1,$endPos-$curPos-1);
				
				//echo count($tfRequest);
				$newArticle .=substr($article->text,$startPos,$curPos-$startPos) . $this->renderTinkerForms($tfRequest) ."\n\n"; // . substr($article->text,$startPos+($endPos-$startPos+1));
				//echo "\n<hr />\n<h1>#".$i." with starttf:$startPos and endtf:$endPos</h1>\n<pre>".$newArticle."</pre>\n<hr />\n";
				//$article->text =substr($article->text,0,$tempPos) . $this->renderTinkerForms($article->text,"single") . substr($article->text,$tempPos+strlen($strSingle));
				$startPos = $endPos+1;
			} else {
				$i=100;
			}
		}
		if($this->dbConnect) {
			$this->myTinkerForm->dbClose();
		}
		if(strlen($newArticle)>0) {
			$newArticle .=substr($article->text,$startPos);
			$article->text = $newArticle;
		}
		
		return true;
	}

	function renderTinkerForms($inReqStr) {
		if(!$this->dbConnect) {
			require_once("cTinkerForms.php");
			$this->myTinkerForm = new cTinkerForms();
			$this->myTinkerForm->dbConnect();
			$this->dbConnect = true;
		}
		$output = $this->myTinkerForm->renderForm($inReqStr);
		return $output;
	}
}

//$myPlugin =& new plgContentTinkerforms( JEventDispatcher::getInstance() );

?>

