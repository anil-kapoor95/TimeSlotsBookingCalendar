<td class="<?php echo $iso_date == date('Y-m-d') ? 'day-today' : NULL;?>">
	<strong class="appointment-management-date"><?php echo date('d', strtotime($iso_date));?></strong>
	<?php
	if(!empty($item))
	{
	    if(!empty($item['blocks']))
	    {
	        foreach($item['blocks'] as $block)
	        {
	            $start_ts = strtotime($iso_date . ' ' . $block['start_time']);
	            $end_ts = strtotime($iso_date . ' ' . $block['end_time']);
	            if($block['status'] == 'avail')
	            {
	                $href = "#";
	                if(pjAuth::factory('pjAdminBookings', 'pjActionCreate')->hasAccess() && $start_ts > time())
	                {
	                    $href = $_SERVER['PHP_SELF'] . '?controller=pjAdminBookings&amp;action=pjActionCreate';
	                }
    	            ?>
    	            <div class="car-reservation-outer tooltip-demo">
                        <a href="<?php echo $href;?>" class="car-reservation">
                            <div class="car-reservation-inner bg-<?php echo $start_ts > time() ? 'confirmed' : 'muted';?>">
                            	<div class="<?php echo $start_ts <= time() ? 'text-muted' : NULL;?>">
                                	<strong><?php echo date($tpl['option_arr']['o_time_format'], $start_ts);?> - <?php echo date($tpl['option_arr']['o_time_format'], $end_ts);?></strong>
                                </div>
                            </div>
                        </a>
                    </div>
    	            <?php
	            }else if($block['status'] == 'pending'){
	                $tooltip_arr = array();
	                $tooltip_arr[] = __('lblCalendar', true) . ': ' . pjSanitize::clean($block['data']['calendar_title']);
	                $tooltip_arr[] = __('booking_total', true) . ': ' . pjCurrency::formatPrice($block['data']['booking_total']);
	                
	                $href = "#";
	                if(pjAuth::factory('pjAdminBookings', 'pjActionUpdate')->hasAccess())
	                {
	                    $href = $_SERVER['PHP_SELF'] . '?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=' . $block['data']['booking_id'];
	                }
	                ?>
    	            <div class="car-reservation-outer tooltip-demo">
                        <a href="<?php echo $href;?>" class="car-reservation" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo join("<br/>", $tooltip_arr);?>">
                            <div class="car-reservation-inner bg-<?php echo $start_ts > time() ? 'pending' : 'muted';?>">
                            	<div class="<?php echo $start_ts <= time() ? 'text-muted' : NULL;?>">
                                    <p><strong><?php echo date($tpl['option_arr']['o_time_format'], $start_ts);?> - <?php echo date($tpl['option_arr']['o_time_format'], $end_ts);?></strong></p>
                                    <?php echo pjSanitize::html($block['data']['customer_name']);?>
                                </div>
                            </div>
                        </a>
                        <?php
                        if ($start_ts >= time()) {
							?>
	                        <div class="clearfix">
	                        	<?php
	                        	if(pjAuth::factory('pjAdminSchedule', 'pjActionSendReminder')->hasAccess())
	                        	{
    	                        	?>
    	                            <a href="#" class="text-pending pull-left btn-reminder" data-booking_id="<?php echo $block['data']['booking_id'];?>"><i class="fa fa-bell"></i> <small><?php __('schedule_send_reminder', false, true); ?></small></a>
    	                            <?php
	                        	}
	                        	if(pjAuth::factory('pjAdminSchedule', 'pjActionCancelBooking')->hasAccess())
	                        	{
        	                        ?>
    	                            <a href="#" class="text-pending pull-right btn-delete" data-id="<?php echo $block['data']['id'];?>" data-booking_id="<?php echo $block['data']['booking_id'];?>"><i class="fa fa-trash"></i> <small><?php __('btnCancel', false, true); ?></small></a>
    	                            <?php
	                        	}
    	                        ?>
	                        </div><!-- /.clearfix -->
	                        <?php 
						}
						?>
                    </div>
    	            <?php
	            }else if($block['status'] == 'confirmed'){
	                $tooltip_arr = array();
	                $tooltip_arr[] = __('lblCalendar', true) . ': ' . pjSanitize::clean($block['data']['calendar_title']);
	                $tooltip_arr[] = __('booking_total', true) . ': ' . pjCurrency::formatPrice($block['data']['booking_total']);
	                
	                $href = "#";
	                if(pjAuth::factory('pjAdminBookings', 'pjActionUpdate')->hasAccess())
	                {
	                    $href = $_SERVER['PHP_SELF'] . '?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=' . $block['data']['booking_id'];
	                }
	                ?>
    	            <div class="car-reservation-outer tooltip-demo">
                        <a href="<?php echo $href;?>" class="car-reservation" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo join("<br/>", $tooltip_arr);?>">
                            <div class="car-reservation-inner bg-<?php echo $start_ts > time() ? 'danger' : 'muted';?>">
                            	<div class="<?php echo $start_ts <= time() ? 'text-muted' : NULL;?>">
                                    <p><strong><?php echo date($tpl['option_arr']['o_time_format'], $start_ts);?> - <?php echo date($tpl['option_arr']['o_time_format'], $end_ts);?></strong></p>
                                    <?php echo pjSanitize::html($block['data']['customer_name']);?>
                                </div>
                            </div>
                        </a>
                        <?php
                        if ($start_ts >= time()) 
                        {
							?>
	                        <div class="clearfix">
	                        	<?php
	                        	if(pjAuth::factory('pjAdminSchedule', 'pjActionSendReminder')->hasAccess())
	                        	{
    	                        	?>
    	                            <a href="#" class="text-danger pull-left btn-reminder" data-booking_id="<?php echo $block['data']['booking_id'];?>"><i class="fa fa-bell"></i> <small><?php __('schedule_send_reminder', false, true); ?></small></a>
    	                            <?php
	                        	}
	                        	if(pjAuth::factory('pjAdminSchedule', 'pjActionCancelBooking')->hasAccess())
	                        	{
        	                        ?>
    	                            <a href="#" class="text-danger pull-right btn-delete" data-id="<?php echo $block['data']['id'];?>" data-booking_id="<?php echo $block['data']['booking_id'];?>"><i class="fa fa-trash"></i> <small><?php __('btnCancel', false, true); ?></small></a>
    	                            <?php
	                        	}
    	                        ?>
	                        </div><!-- /.clearfix -->
	                        <?php 
						}
						?>
                    </div>
    	            <?php
	            }else if($block['status'] == 'break'){
	                $href = "#";
	                ?>
    	            <div class="car-reservation-outer tooltip-demo">
                        <a href="<?php echo $href;?>" class="car-reservation">
                            <div class="car-reservation-inner bg-muted">
                            	<div class="text-muted">
                                	<p><strong><?php echo date($tpl['option_arr']['o_time_format'], $start_ts);?> - <?php echo date($tpl['option_arr']['o_time_format'], $end_ts);?></strong></p>
                                	<?php __('time_tbl_break');?>
                                </div>
                            </div>
                        </a>
                    </div>
    	            <?php
	            }
	        }
	    }else{
	        $href = "#";
	        if(pjAuth::factory('pjAdminBookings', 'pjActionCreate')->hasAccess() && $item['start_ts'] > time())
	        {
	            $href = $_SERVER['PHP_SELF'] . '?controller=pjAdminBookings&amp;action=pjActionCreate';
	        }
	        ?>
	        <div class="car-reservation-outer tooltip-demo">
                <a href="<?php echo $href;?>" class="car-reservation">
                    <div class="car-reservation-inner bg-<?php echo $item['start_ts'] > time() ? 'confirmed' : 'muted';?>">
                        <p class="<?php echo $item['start_ts'] <= time() ? 'text-muted' : NULL;?>"><strong><?php echo date($tpl['option_arr']['o_time_format'], $item['start_ts'])?> - <?php echo date($tpl['option_arr']['o_time_format'], $item['end_ts'])?></strong></p>
                    </div>
                </a>
            </div>
	        <?php
	    }
	}
	?>
</td>