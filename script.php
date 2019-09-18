<?php
/**
 * @package    DD_SocialShare
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2019 HR-IT-Solutions GmbH
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
**/

defined('_JEXEC') or die();

class com_dd_gmaps_locationsInstallerScript
{

	private $extensionName;

	public function __construct()
	{
		$this->extensionName = JText::_('COM_DD_SOCIALSHARE');
	}

	function install($parent)
	{
		$parent->getParent()->setRedirectURL('index.php?option=com_dd_socialshare');
	}

	function uninstall($parent)
	{
		echo '<p>' . JText::sprintf('COM_DD_SOCIALSHARE_UNINSTALL_TEXT', $this->extensionName) . '</p>';
	}

	function update($parent)
	{
		echo '<p>' . JText::sprintf('COM_DD_SOCIALSHARE_UPDATE_TEXT', $this->extensionName) . '</p>';
	}

	function preflight($type, $parent)
	{
		echo '<p>' . JText::sprintf('COM_DD_SOCIALSHARE_PREFLIGHT_' . strtoupper($type) . '_TEXT', $this->extensionName) . '</p>';
	}

	function postflight($type, $parent)
	{
		echo '<p>' . JText::sprintf('COM_DD_SOCIALSHARE_POSTFLIGHT_' . strtoupper($type) . '_TEXT', $this->extensionName) . '</p>';
	}
}
