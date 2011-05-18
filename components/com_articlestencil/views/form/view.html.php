<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class ArticleStencilViewForm extends JView {
	protected $previewStr = null;
	protected $cleanTags = null;

	function display($tpl = null) {
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
		$model = $this->getModel('Stencil');
		$model->storeFormVars();
		$cleanVars = $model->getCleanVars();
		//print_r($cleanVars);
		$this->assignRef('cleanVars',$cleanVars);
		$this->assign('previewStr',$model->getPreviewStr());
		// Assign these variables to the view so they can be used in the template
		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= &JFactory::getApplication();
		$menus	= &JSite::getMenu();
		$title	= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			//$this->params->def('page_heading', JText::_('COM_NEWSFEEDS_DEFAULT_PAGE_TITLE'));
		}
		$title = ''; //$this->params->get('page_title');
		if (empty($title)) {
			$title	= htmlspecialchars_decode($app->getCfg('sitename'));
		}
		$this->document->setTitle($title);
	}
}
