<?php
/**
 * @package    DD_SocialShare
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

/**
 * Class DD_SocialShareHelper
 *
 * @since  Version 1.1.0.0
 */
class  DD_SocialShareHelper extends JHelperContent
{

	public static $extension = 'com_dd_socialshare';

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   string   $component  The component name.
	 * @param   string   $section    The access section name.
	 * @param   integer  $id         The item ID.
	 *
	 * @return  JObject
	 */
	public static function getActions($component = '', $section = '', $id = 0)
	{
		if (!$section || $id)
		{
			return parent::getActions($component, $section, $id);
		}

		$assetName = $component . '.' . $section;

		$path = JPATH_ADMINISTRATOR . '/components/' . $component . '/access.xml';

		$actions = JAccess::getActionsFromFile($path, "/access/section[@name='component']/");

		$user	= JFactory::getUser();
		$result	= new JObject;

		foreach ($actions as $action)
		{
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}

		return $result;
	}


	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since    Version 1.1.0.1
	 */
	public static function addSubmenu($vName)
	{
		// Dashboard
		JHtmlSidebar::addEntry(
			JText::_('COM_DD_SOCIALSHARE_SIDEBARTITLE_DASHBOARD'),
			'index.php?option=com_dd_socialshare&view=dashboard',
			$vName == 'dashboard'
		);

		// Content
		JHtmlSidebar::addEntry(
			JText::_('COM_DD_SOCIALSHARE_SIDEBARTITLE_LOCATIONS'),
			'index.php?option=com_dd_socialshare&view=content',
			$vName == 'content'
		);
	}

	/**
	 * todo: replace with valid alias check in tables/location.php
	 * Checks plausibility of alias and prepare for URLSafe
	 * If alias ist not unique, a unique ID was prefixed (loaction ID)
	 *
	 * @param   array  $data  data
	 *
	 * @return  string  alias
	 *
	 * @since   Version 1.1.0.1
	 */
	public static function prepareAlias($data)
	{
		// Get alias
		if ($data['alias'] != '')
		{
			$alias = $data['alias'];
		}
		else
		{
			$alias = $data['title'];
		}

		// Prepare alias for URLSafe
		$alias = JFilterOutput::stringURLSafe($alias);

		// Plausibility check unique alias
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('alias')
			->from($db->quoteName('#__dd_socialshare'))
			->where($db->quoteName('alias') . " ='$alias' AND " . $db->quoteName('id') . " <> " . $data['id']);
		$db->setQuery($query);

		if ($db->loadResult())
		{
			JFactory::getApplication()->enqueueMessage('COM_DD_SOCIALSHARE_CHECKALIAS_ALIAS_UNIQUE', 'notice');
			$alias = $data['id'] . '-' . $alias;
		}

		return $alias;

	}


	/**
	 * Get Component Version
	 *
	 * @return string component version
	 *
	 * @since  Version  1.1.0.0
	 */
	public static function getComponentVersion()
	{
		$xml = JFactory::getXML(JPATH_ADMINISTRATOR . '/components/com_dd_socialshare/dd_socialshare.xml');
		$version = (string) $xml->version;

		return $version;
	}

	/**
	 * Get Component Coyright
	 *
	 * @return string component copyright
	 *
	 * @since  Version  1.1.0.0
	 */
	public static function getComponentCoyright()
	{
		$xml = JFactory::getXML(JPATH_ADMINISTRATOR . '/components/com_dd_socialshare/dd_socialshare.xml');
		$copyright = (string) $xml->copyright;

		return $copyright;
	}
}
