<?php
/**
 * @package    DD_SocialShare
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2019 HR-IT-Solutions GmbH
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

/**
 * Class DD_SocialShareViewArticles
 *
 * @since  Version  1.0.0.0
 */
class DD_SocialShareViewArticles extends JViewLegacy
{
	/**
	 * An array of items
	 *
	 * @var  array
	 */
	protected $items;

	/**
	 * The model state
	 *
	 * @var  object
	 */
	protected $state;

	/**
	 * The Pagination
	 *
	 */
	protected $pagination;

	public $filterForm;

	public $activeFilters;

	protected $canDo;

	protected $sidebar;

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
		$this->canDo = DD_SocialShareHelper::getActions('com_dd_socialshare', 'articles');

		DD_SocialShareHelper::addSubmenu('articles');

		// Load the datas from the model
		$this->items			= $this->get('Items');
		$this->state			= $this->get('State');
		$this->pagination		= $this->get('Pagination');
		$this->filterForm		= $this->get('FilterForm');
		$this->activeFilters	= $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();

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
		$user  = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolbar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_DD_SOCIALSHARE_TOOLBARTITLE_ARTICLES'), 'grid');

		if ($user->authorise('core.admin', 'com_dd_socialshare') || $user->authorise('core.options', 'com_dd_socialshare'))
		{
			JToolbarHelper::preferences('com_dd_socialshare');
		}

		$bar->appendButton('Link', 'new-tab-2', 'COM_DD_SOCIALSHARE_EDIT_VIA_ARTICLES', 'index.php?option=com_content');
	}

	/**
	 * Add the sidebar
	 *
	 * @return  void
	 *
	 * @since   Version  1.0.0.0
	 */
	protected function addSidebar()
	{
		DD_SocialShareHelper::addSubmenu('articles');
		$this->sidebar = JHtml::_('sidebar.render');
	}

	/**
	 * Drop Down Filter
	 *
	 * @return array
	 *
	 * @since Version 1.0.0.0
	 */
	protected function getSortFields()
	{
		return array(
			'a.state' => JText::_('JSTATUS'),
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'b.facebook' => JText::_('COM_DD_SOCIALSHARE_HEADING_FACEBOOK'),
			'b.twitter' => JText::_('COM_DD_SOCIALSHARE_HEADING_TWITTER'),
		);
	}
}
