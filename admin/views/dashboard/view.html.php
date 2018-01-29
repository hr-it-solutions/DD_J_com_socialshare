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
 * Class D_SocialShareViewDashboard
 *
 * @since  Version  1.0.0.0
 */
class DD_SocialShareViewDashboard extends JViewLegacy
{
	protected $items;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since  Version  1.0.0.0
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		$this->getModel()->facebookOAuth();
		$this->getModel()->facebookTestPost();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();
		$this->addSidebar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   Version  1.0.0.0
	 */
	protected function addToolbar()
	{
		$canDo = JHelperContent::getActions('com_dd_socialshare');

		JToolBarHelper::title(JText::_('COM_DD_SOCIALSHARE_TOOLBARTITLE_DASHBOARD'), 'grid');

		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_dd_socialshare');
		}

	}

	/**
	 * Add the sidebar
	 *
	 * @return  void
	 *
	 * @since   Version  1.1.0.0
	 */
	protected function addSidebar()
	{
		DD_SocialShareHelper::addSubmenu('dashboard');
		$this->sidebar = JHtml::_('sidebar.render');
	}
}
