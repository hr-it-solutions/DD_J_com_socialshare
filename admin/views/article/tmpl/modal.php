<?php
/**
 * @package    DD_SocialShare
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', '#jform_catid', null, array('disable_search_threshold' => 0));
JHtml::_('formbehavior.chosen', 'select');

JHtml::_('script', 'com_dd_socialshare/admin.dd_socialshare.min.js', array('version' => 'auto', 'relative' => true));

JText::script('COM_DD_SOCIALSHARE_BUTTON_SHARE_AGAIN');

?>
<style>.modal-100 { width: 100% !important; margin-left: 0 !important; }</style>
<button id="closeBtn" type="button" class="hidden" onclick="Joomla.submitbutton('article.cancel');"></button>

<div class="container-popup">
	<?php $this->setLayout('edit'); ?>
	<?php echo $this->loadTemplate(); ?>
</div>
