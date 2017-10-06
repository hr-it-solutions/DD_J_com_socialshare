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
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   3.5
	 */
	public function display($tpl = null)
	{
		// Access check.
		if (!JFactory::getUser()->authorise('core.admin'))
		{
			throw new JAccessExceptionNotallowed(JText::_('JERROR_ALERTNOAUTHOR'), 403);
		}

		// Set the MIME type for JSON output.
		JFactory::getDocument()->setMimeEncoding('application/json');

		// Change the suggested filename.
		JFactory::getApplication()->setHeader('Content-Disposition', 'attachment;filename="result.json"');

		// Get data on success of shareSave
		$data = $this->getModel()->shareSave();

		if ($data)
		{
			echo json_encode($data);
		}
		else
		{
			echo json_encode(array('success' => 'false'));
		}

		JFactory::getApplication()->close();
	}
}