<?php
// No direct access
defined('_JEXEC') or die;

class ArticleStencilModelStencil extends JModelItem {
	protected $_context = 'com_articlestencil.stencil';
	protected $_cleanVars = array();
	protected $_previewStr = '';

	public function _populateState() {
		$app = JFactory::getApplication();
		$params	= $app->getParams();
		return;
		// Load the object state.
		$id	= JRequest::getInt('id');
		$this->setState('weblink.id', $id);

		// Load the parameters.
		$this->setState('params', $params);
	}

	public function &getItems($id = null) {
		return 'hello';
	}
	public function storeFormVars() {
		$previewStr = '';
		$cleanVars = array();
		// These are the fields that may come through the form POST
		$fields = array('title','htmlintrotext','htmlbodytext','keyword1','keyword2','keyword3',
			'resource1_title','resource1_url',
			'resource2_title','resource2_url',
			'resource3_title','resource3_url'
			);
		// For the intro and body text, we'll allow these tags to be used
		$allowableTags = '<b><i><p><a><br><br/><hr><hr/><ul><li><img>';
		// Loop through the fields and see if any of the post variables match the expected fields
		foreach($fields as $field) {
			$temp = '';
			if(isset($_POST[$field])) {
				if(substr($field,0,4)=='html') {
					$temp = trim(strip_tags($_POST[$field],$allowableTags));
				} else {
					$temp = trim(strip_tags($_POST[$field]));
				}
			}
			$cleanVars[$field] = $temp;
		}
		
		// Create a preview of the article that will be posted
		if(isset($_POST['stencil_submit']) && $_POST['stencil_submit']=='1') {
			foreach($cleanVars as $key => $data) {
				$tempStyle = empty($data) ? ' style="display:none" ' : '';
				$previewStr .= "<div class='$key' $tempStyle>";
				$previewStr .= $data;
				$previewStr .= "</div>\n";	
			}
		}
		$this->_previewStr = $previewStr;	
		$this->_cleanVars = $cleanVars;	
	}
	public function getCleanVars() {
		return $this->_cleanVars;
	}
	public function getPreviewStr() {
		return $this->_previewStr;
	}

}
