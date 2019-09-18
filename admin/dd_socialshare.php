<?php
/**
 * @package    DD_SocialShare
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2019 HR-IT-Solutions GmbH
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

if (!JFactory::getUser()->authorise('core.manage', 'com_dd_socialshare'))
{
	throw new JAccessExceptionNotallowed(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

JLoader::import('helpers.dd_socialshare', JPATH_COMPONENT_ADMINISTRATOR);

JHtml::_('jQuery.Framework');

JHTML::_('stylesheet', 'com_dd_socialshare/admin.dd_socialshare.min.css', array(), true);

$controller	= JControllerLegacy::getInstance('DD_SocialShare');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
