<?php
// No direct access
defined('_JEXEC') or die;


class ArticleStencilController extends JController {
	protected $_modelSet = false;
	
	function display() { 
		$app = xxx;
		$cachable = true;
		
		// Get the view name from the request or use the default "form"
		$viewType = $app->input->getWord('view', 'form');
		$app->input->set('view', $viewType);
		
		if(!$this->_modelSet) {
			$model = $this->getModel('Stencil');
			$model->storeFormVars();
		}
		$document = JFactory::getDocument();
		$vFormat	= $document->getType();
		$view 		= $this->getView($viewType, $vFormat);
		$modelCat	= $this->getModel('Stencil');
		$model->storeFormVars();
		$view->setModel($modelCat);	
		
		$user = &JFactory::getUser();
		
		if ($user->get('id') || ($app->input->getMethod() == 'POST' && $viewType = 'category' )) {
			$cachable = false;
		}
		
		$safeurlparams = array('id'=>'INT','limit'=>'INT','limitstart'=>'INT','filter_order'=>'CMD','filter_order_Dir'=>'CMD');
		
		parent::display($cachable,$safeurlparams);
	}
	function insert() { 
		$viewType = $app->input->getWord('view', 'form');
		$app->input->set('view', $viewType);
		$model = $this->getModel('Stencil');
		$model->storeFormVars();
		$this->_modelSet = true;
		$this->display();
		$db = JFactory::getDbo();
		$user =JFactory::getUser();
		$app = JFactory::getApplication();
		// Create a user access object for the user
		$access = new stdClass();
		$access->canEdit = $user->authorize('com_content', 'edit', 'content', 'all');
		$access->canEditOwn = $user->authorize('com_content', 'edit', 'content', 'own');
		$access->canPublish = $user->authorize('com_content', 'publish', 'content', 'all');

		// load the category
		$catID = 1;
		$cat =& JTable::getInstance('category');
		$cat->load($catID);

		// Include the content plugins for the onSave events.
		JPluginHelper::importPlugin('content');

		$title = $app->input->get('title');
		$text = $app->input->get('text');
		$category = $app->input->get('category');

		// Get an empty article object
		$article = JTable::getInstance('content');
		$value = 0;
		if ($value) {
			$article->load($value);
		}
		$article->title = $title;
		$article->alias = strtolower(str_replace(' ','-',$title));
		$article->introtext	= $text;
		$article->fulltext		= '';

		$article->catid	 	= $catID;
		// At the time of this writing, these had no default values, 
		// so needed to be sent through
		$article->images = '';
		$article->urls = '';
		$article->attribs = '';
		$article->metakey = '';
		$article->metadesc = '';
		$article->metadata = '';
		$article->language = '';
		$article->xreference = '';

		
		$date =& JFactory::getDate();

		$article->created		= $date->toMySQL();
		$article->created_by	= $user->get('id');

		$article->publish_up	= $date->toMySQL();
		$article->publish_down	= $db->getNullDate();
		$publish = 1;
		$article->state = ($publish && $access->canPublish) ? 1 : 0;
		//$article->state		= 1;

		//if (!$article->check()) {
		//	echo JText::_('Post check failed');
		//}

		$article->version++;
		//$result = $article->store();
		
		// At the time of this writing, JRoute isn't working properly
		//$articleURL = JRoute::_('index.php?option=com_content&id='.$article->id);
		$articleURL = '/joomla16/index.php?option=com_content&view=article&Itemid=1&id='.$article->id;
		// create a new content item
		echo json_encode(array('id'=>$article->id,'title'=>$title,'text'=>$text,'category'=>$category,'url'=>$articleURL));
	}
}
