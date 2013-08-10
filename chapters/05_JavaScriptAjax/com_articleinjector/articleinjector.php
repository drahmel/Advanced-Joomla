<?php
/**
 * @version		$Id: content.php 14276 2010-01-18 14:20:28Z louis $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

$task = JRequest::getVar('task');
$document = JFactory::getDocument();

switch($task) {
	case 'insert':
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		//print_r($user);
		$app = JFactory::getApplication();
		//$model	= &$document->getModel();
		//$controller = JController::getInstance('Content');
		//print_r($controller);
		// Create a user access object for the user
		$access					= new stdClass();
		//$access->canEdit		= $user->authorize('com_content', 'edit', 'content', 'all');
		//$access->canEditOwn		= $user->authorize('com_content', 'edit', 'content', 'own');
		$access->canPublish		= 1; //$user->authorize('com_content', 'publish', 'content', 'all');

		// load the category
		$catID = 1;
		$cat = JTable::getInstance('category');
		$cat->load($catID);

		// Include the content plugins for the onSave events.
		JPluginHelper::importPlugin('content');

		$title = JRequest::getVar('title');
		$text = JRequest::getVar('text');
		$category = JRequest::getVar('category');
		$category = is_numeric($category) && intval($category)>1	
		?	intval($category)	:	2;
		$category_name = $category==2	
			?	'Uncategorized'	:	$category;
		$publish = JRequest::getVar('publish')=='false'	?	0	:	1;

		// Get an empty article object
		$article = JTable::getInstance('content');
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

		
		$date = JFactory::getDate();

		$article->created		= $date->toSQL();
		$article->created_by	= $user->get('id');

		$article->publish_up	= $date->toSQL();
		$article->publish_down	= $db->getNullDate();
		//$publish = 1;
		$published = ($publish && $access->canPublish) ? 1 : 0;
		$article->state		= $published;
		$article->version++;

		$success=1;
		$msg = '';
		if (!$article->check()) {
			$msg = JText::_('Bad data');
			$success=0;
		} else {
			$result = $article->store();
			if (!$article->id) {
				$msg = JText::_('Duplicate article alias');
				$success=0;
			}
		}
		// At the time of this writing, JRoute isn't working properly
		//$articleURL = JRoute::_('index.php?option=com_content&id='.$article->id);
		$articleURL = '/index.php?option=com_content&view=article&Itemid=1&id='.$article->id;
		// create a new content item
		header('Content-Type: application/json');
		echo json_encode(array(
			'success'=>$success,
			'msg'=>$msg,
			'id'=>$article->id,
			'title'=>$title,
			'text'=>$text,
			'category'=>$category_name,
			'published'=>$published,
			'url'=>$articleURL)
		);
		exit;
	default:
		$a=1;
}

$title = "Advanced Joomla Article Injector";
echo '<h1>Article Injector</h1>';
$app = JFactory::getApplication();
$document = JFactory::getDocument();

$document->setTitle($title);
$link = '';
$attribs = array();
// At the time of this writing, this doesn't work properly in 1.6
//$document->addHeadLink(JRoute::_($link.'&type=rss'), 'stylesheet', 'rel', $attribs);
include("articleinjector_view.php");
?>
