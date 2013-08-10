<?php
defined('_JEXEC') or die;

// Include dependancies
//jimport('joomla.application.component.controller'); xxx

// Execute the task.
$controller	= JController::getInstance('ArticleStencil');
// Set default controller task to "display"
$app = JFactory::getApplication();
$controller->execute($app->input->get('task','display'));
$controller->redirect();
