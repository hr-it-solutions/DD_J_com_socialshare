<?php
/**
 * @package    DD_SocialShare
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user		= JFactory::getUser();
$userId		= $user->id;

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$trashed	= $this->state->get('filter.published') == -2;
$saveOrder	= $listOrder == 'f.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_dd_socialshare&task=articles.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'fieldList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

?>
<script type="text/javascript">
    Joomla.orderTable = function()
    {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.options[table.selectedIndex].value;
        if (order !== '<?php echo $listOrder; ?>')
        {
            dirn = 'asc';
        }
        else
        {
            dirn = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }
</script>
<div id="dd_socialshare-articles" class="row-fluid dd_socialshare">
	<form action="<?php echo JRoute::_('index.php?option=com_dd_socialshare&view=articles'); ?>" method="post" name="adminForm" id="adminForm">
		<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
			<?php else : ?>
			<div id="j-main-container">
				<?php endif; ?>

				<!-- Filter Bar -->
				<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

				<!-- Table -->
				<div class="clearfix"></div>

				<?php if (empty($this->items)) : ?>
					<div class="alert alert-no-items">
						<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
					</div>
				<?php else : ?>
					<table class="table table-striped" id="articleList">
						<thead>
						<th width="4%" class="center hidden-phone">
							<input type="checkbox" name="check-toggle" value=""
							       title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
							       onclick="Joomla.checkAll(this)" />
						</th>
						<th width="4%" style="min-width: 55px;" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
						</th>
                        <th width="4%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'COM_DD_SOCIALSHARE_FACEBOOK', 's.facebook', $listDirn, $listOrder); ?>
                        </th>
                        <th width="4%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'COM_DD_SOCIALSHARE_TWITTER', 's.twitter', $listDirn, $listOrder); ?>
                        </th>
						<th width="52%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
                        <th width="20%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'COM_DD_SOCIALSHARE_DATE_CREATED', 'a.created', $listDirn, $listOrder); ?>
                        </th>
						<th width="10%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'COM_DD_SOCIALSHARE_FACEBOOK', 's.facebook', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'COM_DD_SOCIALSHARE_TWITTER', 's.twitter', $listDirn, $listOrder); ?>
						</th>
                        <th width="1%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'COM_DD_SOCIALSHARE_FIELD_CONTENT_ID_LABEL', 's.socialsahre_id', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                        </th>
						</thead>
						<tfoot>
						<tr>
							<td colspan="10">
								<?php echo $this->pagination->getListFooter(); ?>
							</td>
						</tr>
						</tfoot>
						<tbody>
						<?php foreach ($this->items as $i => $item):
							$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
							$canChange  = false;

							$url = 'index.php?option=com_dd_socialshare&task=article.' . (($item->socialsahre_id === null) ? 'add' : 'edit');
							$url .= '&id=' . (int) $item->socialsahre_id . '&content_id=' . $item->id;

							?>
							<tr class="row<?php echo $i % 2; ?>">
								<td class="center hidden-phone">
									<?php echo JHtml::_('grid.id', $i, $item->id); ?>
								</td>
								<td class="center">
									<?php echo JHtml::_('jgrid.published', $item->state, $i, 'locations.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
								</td>
                                <td class="nowrap">
									<?php if($item->facebook === null): ?>
                                        <a class="btn btn-micro jgrid" href="<?php echo JRoute::_($url); ?>">
                                            <span class="icon-unpublish"></span> <span class="icon-facebook"></span>
                                        </a>
									<?php else: ?>
                                        <a class="btn btn-micro jgrid" href="<?php echo JRoute::_($url); ?>">
                                            <span class="icon-publish"></span> <span class="icon-facebook"></span>
                                        </a>
                                       <?php endif; ?>
                                </td>
                                <td class="nowrap">
									<?php if($item->twitter === null): ?>
                                        <a class="btn btn-micro jgrid" href="<?php echo JRoute::_($url); ?>">
                                            <span class="icon-unpublish"></span> <span class="icon-twitter"></span>
                                        </a>
									<?php else: ?>
                                        <a class="btn btn-micro jgrid" href="<?php echo JRoute::_($url); ?>">
                                            <span class="icon-publish"></span> <span class="icon-twitter"></span>
                                        </a>
                                    <?php endif; ?>
                                </td>
								<td class="nowrap">
									<a href="<?php echo JRoute::_($url); ?>">
										<?php echo $this->escape($item->title);?>
									</a>
                                    <span class="small break-word">
									    <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
								    </span>
                                    <div class="small">
										<?php echo JText::_('JCATEGORY') . ': ' . $this->escape($item->category_title); ?>
                                    </div>
								</td>
                                <td class="nowrap">
                                    <span class="small"><?php echo JHtml::_('date', $this->escape($item->created), 'Y-m-d H:m:s'); ?></span>
                                </td>
								<td class="nowrap">
                                    <?php if($item->facebook !== null || $item->facebook !== '0000-00-00 00:00:00'): ?>
                                        <span class="small"><?php echo JHtml::_('date', $this->escape($item->facebook), 'Y-m-d H:m:s'); ?></span>
                                    <?php endif; ?>
								</td>
								<td class="nowrap">
									<?php if($item->twitter !== null || $item->twitter !== '0000-00-00 00:00:00'): ?>
                                        <span class="small"><?php echo JHtml::_('date', $this->escape($item->twitter), 'Y-m-d H:m:s'); ?></span>
									<?php endif; ?>
								</td>
                                <td class="hidden-phone">
									<?php echo (int) $item->socialsahre_id; ?>
                                </td>
                                <td class="hidden-phone">
									<?php echo (int) $item->id; ?>
                                </td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>

				<input type="hidden" name="task" value="" />
				<input type="hidden" name="boxchecked" value="0" />
				<?php echo JHtml::_('form.token'); ?>

				<!-- Component Credits -->
				<div class="row-fluid">
					<hr>
					<div class="text-center">
						<p><small><?php echo nl2br(JText::sprintf('COM_DD_SOCIALSHARE_CREDITS', DD_SocialShareHelper::getComponentCoyright())); ?></small></p>
					</div>
				</div>
			</div>
	</form>
</div>
