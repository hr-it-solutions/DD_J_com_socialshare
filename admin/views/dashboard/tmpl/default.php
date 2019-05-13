<?php
/**
 * @package    DD_SocialShare
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2019 HR-IT-Solutions GmbH
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;
?>
<div class="row-fluid">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
	<div id="j-main-container" class="span12">
		<?php endif; ?>
		<div class="row-fluid">
			<!-- Module Positions -->
			<?php
			$modules = JModuleHelper::getModules('dd_socialshare');

			if (count($modules))
			{
				$modules = array_chunk($modules, 2);

				foreach ($modules as $modulegroup) :
					echo '<div class="row-fluid">';

					foreach ($modulegroup as $module) :
						echo '<div class="span12">';
						echo JModuleHelper::renderModule($module);
						echo '</div>';
					endforeach;

					echo '</div>';

				endforeach;
			}
			else
			{
				echo '<div class="alert alert-info">';
				echo JText::sprintf('COM_DD_SOCIALSHARE_POSITION_DESCRIPTION', 'dd_socialshare');
				echo '</div>';
			}
			?>
			<hr>
			<!-- Component Description -->
			<div class="text-center">
				<p><?php echo JText::_('COM_DD_SOCIALSHARE_XML_DESCRIPTION'); ?></p>
			</div>

			<!-- Component Version Info -->
			<div class="alert alert-success text-center">
				<?php echo JText::sprintf('COM_DD_SOCIALSHARE_VERSION', DD_SocialShareHelper::getComponentVersion()); ?>
			</div>

			<hr>
			<!-- Component Credits -->
			<div class="text-center">
				<p><small><?php echo nl2br(JText::sprintf('COM_DD_SOCIALSHARE_CREDITS', DD_SocialShareHelper::getComponentCoyright())); ?></small></p>
			</div>
		</div>
	</div>
</div>
