<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-4 col-sm-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right"><?php __('dash_next_7_days')?></span>

                    <h5><?php __('dash_new_bookings')?></h5>
                </div>

                <div class="ibox-content">
                    <div class="row m-t-md m-b-sm">
                        <div class="col-xs-6">
                            <p class="h1 no-margins"><?php echo (int) $tpl['next_7_days_confirmed'];?></p>
                            <small class="text-info"><?php __('dash_confirmed');?></small>        
                        </div><!-- /.col-xs-6 -->

                        <div class="col-xs-6">
                            <p class="h1 no-margins"><?php echo (int) $tpl['next_7_days_pending'];?></p>
                            <small class="text-info"><?php __('dash_pending');?></small>        
                        </div><!-- /.col-xs-6 -->
                    </div><!-- /.row -->
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right"><?php __('dash_this_month')?></span>

                    <h5><?php __('dash_all_bookings')?></h5>
                </div>

                <div class="ibox-content">
                    <div class="row m-t-md m-b-sm">
                        <div class="col-xs-6">
                            <p class="h1 no-margins"><?php echo (int) $tpl['bookings_this_month'];?></p>
                            <small class="text-info"><?php __('dash_bookings')?></small>        
                        </div><!-- /.col-xs-6 -->

                        <div class="col-xs-6">
                            <p class="h1 no-margins"><?php echo pjCurrency::formatPrice($tpl['total_amount_this_month']);?></p>
                            <small class="text-info"><?php __('dash_total_amount')?></small>        
                        </div><!-- /.col-xs-6 -->
                    </div><!-- /.row -->
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right"><?php __('dash_this_month')?></span>

                    <h5><?php __('dash_clients')?></h5>
                </div>

                <div class="ibox-content">
                    <div class="row m-t-md m-b-sm">
                        <div class="col-xs-6">
                            <p class="h1 no-margins"><?php echo (int) $tpl['new_clients_this_month'];?></p>
                            <small class="text-info"><?php __('dash_new_clients')?></small>        
                        </div><!-- /.col-xs-6 -->

                        <div class="col-xs-6">
                            <p class="h1 no-margins"><?php echo (int) $tpl['total_clients'];?></p>
                            <small class="text-info"><?php __('dash_total_clients')?></small>        
                        </div><!-- /.col-xs-6 -->
                    </div><!-- /.row -->
                </div>
            </div>
        </div>
    </div><!-- /.row -->
	<?php
	$booking_statuses = __('booking_statuses', true);
	?>
    <div class="row">
        <div class="col-lg-4 dashboard-stats2">
            <div class="ibox float-e-margins">
                <div class="ibox-content ibox-heading clearfix">
                    <div class="pull-left">
                        <h3><?php __('dash_upcoming_bookings')?></h3>
                        <small><?php echo (int) $tpl['bookings_today'] != 1 ? sprintf(__('dash_you_have_bookings_today', true), '<strong>'.$tpl['bookings_today'].'</strong>') : sprintf(__('dash_you_have_booking_today', true), '<strong>'.$tpl['bookings_today'].'</strong>');?></small>
                    </div><!-- /.pull-left -->

                    <div class="pull-right m-t-md">
                    	<?php
                    	if(pjAuth::factory('pjAdminBookings', 'pjActionIndex')->hasAccess())
                    	{
                        	?>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex" class="btn btn-primary btn-sm btn-outline m-n"><?php __('dash_view_all_bookings')?></a>
                            <?php
                    	}
                        ?>
                    </div><!-- /.pull-right -->
                </div>

                <div class="ibox-content inspinia-timeline">
                	<?php
                	$has_update = pjAuth::factory('pjAdminBookings', 'pjActionIndex')->hasAccess();
                	foreach($tpl['upcoming_bookings'] as $k => $v)
                	{
                	    ?>
                	    <div class="timeline-item">
                            <div class="row">
                                <div class="col-xs-3 date">
                                    <i class="fa fa-clock-o"></i>
                                    <?php echo date($tpl['option_arr']['o_date_format'] . ', ' . $tpl['option_arr']['o_time_format'], strtotime($v['created']));?>
                                </div>
    
                                <div class="col-xs-7 content">
                                	<?php
                                	if($has_update)
                                	{
                                    	?>
                                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $v['id'];?>">
                                            <p class="m-b-xs"><strong><?php echo pjSanitize::html($v['customer_name']);?></strong></p>
                                            <?php
                                            if(isset($tpl['upcoming_items'][$v['id']]))
                                            {
                                                foreach($tpl['upcoming_items'][$v['id']] as $item)
                                                {
                                                    $date = $item[0]; $start = @$item[1]; $end = @$item[2]; $cnt = @$item[3]; 
                                                    if($cnt > 1)
                                                    {
                                                        ?>
                                                        <p class="m-n"><?php echo $date . ', ' . $start . ' - ' . $end . ' x ' . $cnt;?></p>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <p class="m-n"><?php echo $date . ', ' . $start . ' - ' . $end;?></p>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                            <p class="m-b-sm"><?php __('booking_total')?>: <em><?php echo pjCurrency::formatPrice($v['booking_total']);?></em></p>
                                        </a>
                                        <?php
                                	}else{
                                	    ?>
                                	    <p class="m-b-xs"><strong><?php echo pjSanitize::html($v['customer_name']);?></strong></p>
                                        <?php
                                        if(isset($tpl['upcoming_items'][$v['id']]))
                                        {
                                            foreach($tpl['upcoming_items'][$v['id']] as $item)
                                            {
                                                $date = $item[0]; $start = @$item[1]; $end = @$item[2]; $cnt = @$item[3];
                                                if($cnt > 1)
                                                {
                                                    ?>
                                                    <p class="m-n"><?php echo $date . ', ' . $start . ' - ' . $end . ' x ' . $cnt;?></p>
                                                    <?php
                                                }else{
                                                    ?>
                                                    <p class="m-n"><?php echo $date . ', ' . $start . ' - ' . $end;?></p>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                        <p class="m-b-sm"><?php __('booking_total')?>: <em><?php echo pjCurrency::formatPrice($v['booking_total']);?></em></p>
                                	    <?php
                                	}
                                    ?>
                                </div>
                                
                                <div class="badge bg-<?php echo $v['booking_status'];?> b-r-sm pull-right m-t-md m-r-sm"><?php echo $booking_statuses[$v['booking_status']];?></div>
                            </div>
                        </div>
                	    <?php
                	}
                	?>
                    
                </div>
            </div>
        </div><!-- /.col-lg-4 -->

        <div class="col-lg-8 dashboard-stats3">
            <div class="ibox float-e-margins">
                <div class="ibox-content ibox-heading clearfix">
                    <div class="pull-left">
                        <h3><?php __('dashboard_latest_bookings')?></h3>
                        <small><?php __('dash_total');?> <strong><?php echo (int) $tpl['bookings_made']?></strong> <?php __('dash_bookings_made');?></small>
                    </div><!-- /.pull-left -->

                    <div class="pull-right m-t-md">
                    	<?php
                    	if(pjAuth::factory('pjAdminBookings', 'pjActionCreate')->hasAccess())
                    	{
                        	?>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate" class="btn btn-primary btn-sm btn-outline m-n pjTsCheckAssignCalendar"><?php __('dash_add_booking')?></a>
                            <?php
                    	}
                        ?>
                    </div><!-- /.pull-right -->
                </div>

                <div class="ibox-content">
                	
                    <div class="table-responsive table-responsive-secondary">
                        <table class="table table-striped table-hover no-margins">
                            <thead>
                                <tr>
                                    <th><?php __('booking_created');?></th>
                                    <th><?php __('lblClient');?></th>
                                    <th><?php __('booking_slots')?></th>
                                    <th><?php __('lblCalendar');?></th>
                                    <th><?php __('booking_status')?></th>
                                    <th><?php __('booking_total')?></th>
                                </tr>
                            </thead>

                            <tbody>
                            	<?php
                            	if(!empty($tpl['latest_bookings']))
                            	{
                                	foreach($tpl['latest_bookings'] as $k => $v)
                                	{
                                	    $items = array();
                                	    if(isset($tpl['latest_items'][$v['id']]))
                                	    {
                                	        foreach($tpl['latest_items'][$v['id']] as $item)
                                	        {
                                	            $date = $item[0]; $start = @$item[1]; $end = @$item[2]; $cnt = @$item[3];
                                	            if($cnt > 1)
                                	            {
                                	                $items[] = $date . ', ' . $start . ' - ' . $end . ' x ' . $cnt;
                                	            }else{
                                	                $items[] = $date . ', ' . $start . ' - ' . $end;
                                	            }
                                	        }
                                	    }
                                	    $class = $v['booking_status'];
                                	    $fa_class = '';
                                	    if($v['booking_status'] == 'cancelled')
                                	    {
                                	        $class = 'canceled';
                                	        $fa_class = ' fa-times';
                                	    }else if($v['booking_status'] == 'confirmed'){
                                	        $fa_class = ' fa-check';
                                	    }else if($v['booking_status'] == 'pending'){
                                	        $fa_class = ' fa-exclamation-triangle';
                                	    }
                                    	?>
                                        <tr class="clickable" data-href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $v['id'];?>">
                                            <td><?php echo date($tpl['option_arr']['o_date_format'] . ', ' . $tpl['option_arr']['o_time_format'], strtotime($v['created']));?></td>
                                            <td><?php echo pjSanitize::html($v['customer_name']);?></td>
                                            <td><?php echo join("<br/>", $items);?> </td>
                                            <td><?php echo pjSanitize::html($v['calendar_title']);?></td>
                                            <td><div class="btn bg-<?php echo $class;?> btn-xs no-margin"><i class="fa<?php echo $fa_class;?>"></i> <?php echo $booking_statuses[$v['booking_status']];?></div></td>
                                            <td><?php echo pjCurrency::formatPrice($v['booking_total']);?></td>
                                        </tr>
                                        <?php
                                	}
                            	}else{
                            	    ?>
                            	    <tr><td colspan="6"><?php __('dashboard_latest_bookings_empty');?></td></tr>
                            	    <?php
                            	}
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- /.col-lg-8 -->
    </div><!-- /.row -->
</div><!-- /.wrapper wrapper-content -->