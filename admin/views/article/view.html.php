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
 * Class DD_SocialShareViewArticle
 *
 * @since  Version  1.0.0.0
 */
class DD_SocialShareViewArticle extends JViewLegacy
{
	/**
	 * The JForm object
	 *
	 * @var  JForm
	 */
	protected $form;

	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected $item;

	/**
	 * The model state
	 *
	 * @var  object
	 */
	protected $state;

	/**
	 * The actions the user is authorised to perform
	 *
	 * @var  JObject
	 */
	protected $canDo;

	protected $params;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   Version 1.0.0.0
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');
		$this->canDo = JHelperContent::getActions('com_dd_socialshare', 'article', $this->item->id);

		$this->params = JComponentHelper::getParams('com_dd_socialshare');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   Version 1.0.0.0
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		// Get the toolbar object instance
		$bar = JToolbar::getInstance('toolbar');

		$bar->appendButton('Standard', 'arrow-down-4 disabled', 'COM_DD_SOCIALSHARE_BUTTON_AUTOSAVE');

		JToolbarHelper::title(
			JText::_('COM_DD_SOCIALSHARE_TOOLBARTITLE_ARTICLE'),
			'pencil-2 article-add'
		);

		JToolbarHelper::cancel('article.cancel', 'JTOOLBAR_CLOSE');

		JToolbarHelper::divider();
	}
}
