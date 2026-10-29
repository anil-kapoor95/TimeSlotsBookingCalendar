<?php include PJ_VIEWS_PATH . 'pjFrontPublic/elements/menu.php';?>
<div class="pj-calendar">
	<?php
	include PJ_VIEWS_PATH . 'pjFrontPublic/elements/header.php';
	
	list($year, $month,) = explode("-", $controller->_get->toString('date'));
	echo $tpl['calendar']->getMonthHTML((int) $month, $year);
	?>
	<div class="pj-calendar-legends">
		<div class="legend">
			<span class="past"></span> <?php __('front_legend_past');?>
		</div>
		<div class="legend">
			<span class="today"></span> <?php __('front_legend_today');?>
		</div>
		<div class="legend">
			<span class="partly"></span> <?php __('front_legend_partly');?>
		</div>
		<div class="legend">
			<span class="avail"></span> <?php __('front_legend_avail');?>
		</div>
		<div class="legend">
			<span class="booked"></span> <?php __('front_legend_booked');?>
		</div>
	</div>
	<div class="pj-calendar-tooltip-view">
		<div class="pj-calendar-footer active">
			<?php
			$cid = $controller->_get->toInt('cid');
			$slots = 0;
			if(isset($tpl['calendar_cart'][$cid]))
			{
				foreach($tpl['calendar_cart'][$cid] as $date => $items)
				{
					$slots += count($items);
				}
			}
			if ($slots > 0)
			{
				?><p class="pull-left"><?php $slots != 1 ? printf(__('front_slots_selected', true, true), $slots) : printf(__('front_slot_selected', true, true), $slots); ?></p><!-- /.pull-left --><?php
			} 
			?>
	
			<a href="#" class="pull-right btn btn-primary pjTsSelectorCart"><?php __('front_goto_cart');?> <span class="glyphicon glyphicon-chevron-right"></span></a>
		</div><!-- /.pj-calendar-footer -->
	</div>
</div><!-- /.pj-calendar -->