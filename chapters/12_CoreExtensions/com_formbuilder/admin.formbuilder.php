<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/*
// Make sure the user is authorized to view this page
$user = & JFactory::getUser();
if (!$user->authorize( 'com_form_builder', 'manage' )) {
	$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}
*/

// Set the table directory
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_form_builder'.DS.'tables');

$controllerName = JRequest::getCmd( 'c', 'form_builder' );
//echo "Controller:".$controllerName;
if($controllerName == 'client') {
	JSubMenuHelper::addEntry(JText::_('FormBuilder'), 'index.php?option=com_FormBuilder');
	JSubMenuHelper::addEntry(JText::_('Clients'), 'index.php?option=com_FormBuilder&c=client', true );
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_categories&section=com_banner');
} else {
	JSubMenuHelper::addEntry(JText::_('FormBuilder'), 'index.php?option=com_FormBuilder', true );
	JSubMenuHelper::addEntry(JText::_('Clients'), 'index.php?option=com_FormBuilder&c=client');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_categories&section=com_banner');
}

switch ($controllerName)
{
	default:
		$controllerName = 'form_builder';
		// allow fall through

	case 'banner' :
	case 'client':
		// Temporary interceptor
		$task = JRequest::getCmd('task');
		if ($task == 'listclients') {
			$controllerName = 'client';
		}

		require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php' );
		$controllerName = 'Form_builderController'.$controllerName;

		// Create the controller
		$controller = new $controllerName();

		// Perform the Request task
		$controller->execute( JRequest::getCmd('task') );

		// Redirect if set by the controller
		$controller->redirect();
		break;
}
