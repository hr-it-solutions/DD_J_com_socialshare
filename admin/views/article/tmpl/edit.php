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
                    <div class="span6 well">
                        <h3>Facebook Preview <span class="icon-facebook pull-right"></span></h3>
                        <hr>
                        <div class="control-group">
                            <div class="control-label">
			                    <?php echo $this->form->getLabel('facebook_post_image'); ?>
                            </div>
                            <div class="controls">
			                    <?php echo $this->form->getInput('facebook_post_image'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
			                    <?php echo $this->form->getLabel('facebook_post_text'); ?>
                            </div>
                            <div class="controls">
			                    <?php echo $this->form->getInput('facebook_post_text'); ?>
                            </div>
                        </div>

                        <div class="facebook-success">
                            <div class="alert alert-success">
                                <h4>This Article is post on Facebook, see postdate below!</h4>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
			                        <?php echo $this->form->getLabel('facebook'); ?>
                                </div>
                                <div class="controls">
			                        <?php echo $this->form->getInput('facebook'); ?>
                                </div>
                            </div>
                        </div>

                        <button class="btn-facebook btn btn-large btn-success"><span class="icon-facebook"></span> Share now</button>
                    </div>
                    <div class="span6 well">
                        <h3>Twitter Preview <span class="icon-twitter pull-right"></span></h3>
                        <hr>
                        <div class="control-group">
                            <div class="control-label">
			                    <?php echo $this->form->getLabel('twitter_post_text'); ?>
                            </div>
                            <div class="controls">
			                    <?php echo $this->form->getInput('twitter_post_text'); ?>
                            </div>
                        </div>

                        <div class="twitter-success">
                            <div class="alert alert-success">
                                <h4>This Article is post on Twitter, see postdate below!</h4>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
			                        <?php echo $this->form->getLabel('twitter'); ?>
                                </div>
                                <div class="controls">
			                        <?php echo $this->form->getInput('twitter'); ?>
                                </div>
                            </div>
                        </div>

                        <button class="btn-twitter btn btn-large btn-success"><span class="icon-twitter"></span> Share now</button>
                    </div>
                </div>

				<input type="hidden" name="task" value=""/>
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</div>
	</form>
</div>
