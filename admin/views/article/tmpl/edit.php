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

?>
<div id="dd_socialshare-article" class="row-fluid dd_socialshare">
	<form action="<?php echo JRoute::_('index.php?option=com_dd_socialshare&layout=edit&id=' . (int) $this->item->id); ?>"
	      method="post" name="adminForm" id="adminForm" class="form-validate">
		<div class="row-fluid">
			<div class="span12">

				<div class="form-inline form-inline-header">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('title'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('title'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('alias'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('alias'); ?>
						</div>
					</div>
                    <div class="control-group">
                        <div class="control-label">
							<?php echo $this->form->getLabel('content_id'); ?>
                        </div>
                        <div class="controls">
							<?php echo $this->form->getInput('content_id'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
							<?php echo $this->form->getLabel('id'); ?>
                        </div>
                        <div class="controls">
							<?php echo $this->form->getInput('id'); ?>
                        </div>
                    </div>
				</div>

                <hr>

				<div class="row-fluid form-horizontal-desktop">
                    <div class="span12">
                        <h2>SocialShare</h2>
                    </div>
				</div>
                <div class="row-fluid form-horizontal-desktop">
                    <div class="span6 well">
                        <h3>Facebook</h3>
                        <button class="btn-facebook btn btn-large btn-success"><span class="icon-facebook"></span> Share now</button>
                    </div>
                    <div class="span6 well">
                        <h3>Twitter</h3>
                        <button class="btn-twitter btn btn-large btn-success"><span class="icon-twitter"></span> Share now</button>
                    </div>
                </div>

				<input type="hidden" name="task" value=""/>
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</div>
	</form>
</div>
