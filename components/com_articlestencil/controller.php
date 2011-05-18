<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class ArticleStencilController extends JController {
	protected $_modelSet = false;
	
	function display() { 
		$cachable = true;
		
		// Get the view name from the request or use the default "form"
		$viewType = JRequest::getWord('view', 'form');
		JRequest::setVar('view', $viewType);
		
		if(!$this->_modelSet) {
			$model = $this->getModel('Stencil');
			$model->storeFormVars();
		}
		$document = &JFactory::getDocument();
		$vFormat	= $document->getType();
		$view 		= $this->getView($viewType, $vFormat);
		$modelCat	= $this->getModel('Stencil');
		$model->storeFormVars();
		$view->setModel($modelCat);	
		
		$user = &JFactory::getUser();
		
		if ($user->get('id') || ($_SERVER['REQUEST_METHOD'] == 'POST' && $viewType = 'category' )) {
			$cachable = false;
		}
		
		$safeurlparams = array('id'=>'INT','limit'=>'INT','limitstart'=>'INT','filter_order'=>'CMD','filter_order_Dir'=>'CMD');
		
		parent::display($cachable,$safeurlparams);
	}
	function insertArticle() { 
		$viewType = JRequest::getWord('view', 'form');
		JRequest::setVar('view', $viewType);
		$model = $this->getModel('Stencil');
		//$view = $this->getView('form');
		//$view->assignRef('model', $model);
		$model->storeFormVars();
		$this->_modelSet = true;
		$db = & JFactory::getDbo();
		$user =& JFactory::getUser();
		$app = &JFactory::getApplication();
		//$model	= &$document->getModel();
		//$controller = JController::getInstance('Content');
		//print_r($controller);
		// Create a user access object for the user
		$access					= new stdClass();
		$access->canEdit		= $user->authorize('com_content', 'edit', 'content', 'all');
		$access->canEditOwn		= $user->authorize('com_content', 'edit', 'content', 'own');
		$access->canPublish		= $user->authorize('com_content', 'publish', 'content', 'all');

		// load the category
		$catID = 1;
		$cat =& JTable::getInstance('category');
		$cat->load($catID);

		// Include the content plugins for the onSave events.
		JPluginHelper::importPlugin('content');

		$title = JRequest::getVar('title');
		$text = JRequest::getVar('htmlintrotext');
		$category = JRequest::getVar('category');

		// Get an empty article object
		$article = &JTable::getInstance('content');
		$value = 0;
		if ($value) {
			$article->load($value);
		}
		//print_r($article);
		//$article->id	 	= 0;
		$article->title	 	= $title;
		$article->alias	 	= strtolower(str_replace(' ','-',$title));
		$article->introtext	= $text;
		$article->fulltext		= '';

		$article->catid	 	= $catID;
		//$article->sectionid 	= $cat->section;
		// At the time of this writing, these had no default values, so needed to be sent through
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
		$article->state		= ($publish && $access->canPublish) ? 1 : 0;
		$article->state		= 1;

		//if (!$article->check()) {
		//	echo JText::_('Post check failed');
		//}

		$article->version++;
		//print_r($article);
		$result = $article->store();
		
		// At the time of this writing, JRoute isn't working properly
		//$articleURL = JRoute::_('index.php?option=com_content&id='.$article->id);
		$articleURL = '/joomla16/index.php?option=com_content&view=article&Itemid=1&id='.$article->id;
		// create a new content item
		//echo json_encode(array('id'=>$article->id,'title'=>$title,'text'=>$text,'category'=>$category,'url'=>$articleURL));
		$this->display();
	}
	function insertCat() { 
		$cat = &JTable::getInstance('category');
		// These are the minimum parameters you need
		$catID = JRequest::getVar('id','');
		if(!empty($catID)) {
			$cat->load($catID);
		}
		$cat->title = JRequest::getVar('title','');
		if(JRequest::getVar('alias')) {
			$cat->alias = JRequest::getVar('alias');
		} else {
			$cat->alias = str_replace(' ','-',strtolower($cat->title));
		}
		$cat->metadesc = JRequest::getVar('metadesc','');
		$cat->metakey = JRequest::getVar('metakey','');
		$cat->metadata = JRequest::getVar('metadata','');
		$cat->language = JRequest::getVar('language','en_GB');
		$cat->extension = JRequest::getVar('extension','com_content');
		$cat->created_time = JRequest::getVar('created_time','0000-00-00');
		$cat->published = JRequest::getVar('published',0);
		$cat->parent_id = JRequest::getVar('parent_id',1);
		
		$result = $cat->store();
	}	
	function insertMenu() { 
		// Get an empty menu object
		$menu = &JTable::getInstance('menu');
		$menuID = JRequest::getVar('id','');
		if(!empty($menuID)) {
			$cat->load($menuID);
		}
		$menu->title = JRequest::getVar('title','');
		if(JRequest::getVar('alias')) {
			$menu->alias = JRequest::getVar('alias');
		} else {
			$menu->alias = str_replace(' ','-',strtolower($cat->title));
		}
		$menu->menutype = JRequest::getVar('title','');
		$menu->path = JRequest::getVar('path','');
		$menu->link = JRequest::getVar('link','index.php?option=com_content&view=article&id=82');
		$menu->type = JRequest::getVar('type','component');

		$menu->published = JRequest::getVar('published',0);
		$menu->parent_id = JRequest::getVar('parent_id',1);
		$menu->level = JRequest::getVar('level',1);
		$menu->checked_out = 0;
		$menu->checked_out_time = '0000-00-00';
		$menu->params = JRequest::getVar('params','');
		$menu->img = JRequest::getVar('img','');
		$menu->component_id = JRequest::getVar('component_id',99);
		$result = $menu->store();
	}

}
