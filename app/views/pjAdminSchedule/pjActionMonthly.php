<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-6">
                <h2><?php __('infoScheduleTitle')?></h2>
            </div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoScheduleDesc')?></p>
    </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="m-b-sm">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-8">
                            <div class="form-group">
                                <label class="control-label"><?php __('schedule_jump_to')?></label>

                                <select id="jump_to" name="date" class="form-control" data-type="Monthly">
                                	<?php
                                	$date_ts = strtotime(date('Y-m-d'));
                                	if($controller->_get->check('date'))
                                	{
                                	    $date_ts = strtotime($controller->_get->toString('date'));
                                	}
                                	$months = __('months', true);
                                	$first_day_of_month = date('Y-m-01', $date_ts);
                                	$first_date_of_month_ts = strtotime($first_day_of_month);
                                	foreach(range(5, 1) as $i)
                                	{
                                	    $day_of_month = date('Y-m-01', strtotime('-' .$i.' month', $first_date_of_month_ts));
                                	    $day_of_month_ts = strtotime($day_of_month);
                                	    $n = date('n', $day_of_month_ts);
                                	    ?>
                                	    <option value="<?php echo date('Y-m-d', $day_of_month_ts);?>"><?php echo $months[$n];?>, <?php echo date('Y', $day_of_month_ts);?></option>
                                	    <?php
                                	}
                                	foreach(range(0, 6) as $i)
                                	{
                                	    $day_of_month = date('Y-m-01', strtotime('+' .$i.' month', $first_date_of_month_ts));
                                	    $day_of_month_ts = strtotime($day_of_month);
                                	    $n = date('n', $day_of_month_ts);
                                	    if($day_of_month == $first_day_of_month)
                                	    {
                                	        ?>
                                    	    <option value="<?php echo date('Y-m-d', $day_of_month_ts);?>" selected="selected"><?php echo $months[$n];?>, <?php echo date('Y', $day_of_month_ts);?></option>
                                    	    <?php
                                	    }else{
                                	        ?>
                                    	    <option value="<?php echo date('Y-m-d', $day_of_month_ts);?>"><?php echo $months[$n];?>, <?php echo date('Y', $day_of_month_ts);?></option>
                                    	    <?php
                                	    }
                                	}
                                	?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-xs-8">
                        	<label class="control-label"><?php __('schedule_choose_calendar'); ?></label>
                        	<div class="row">
                        		<div class="col-lg-8 col-md-8 col-xs-8">
                        			<select id="calendar_id" name="calendar_id" class="form-control" data-type="Monthly">
                                        <option value="">-- <?php __('lblAll');?> --</option>
                                        <?php
                                        foreach($tpl['calendar_arr'] as $k => $v)
                                        {
                                            ?><option value="<?php echo $v['id'];?>"<?php echo $controller->_get->toInt('calendar_id') == $v['id'] ? ' selected="selected"' : NULL;?>><?php echo pjSanitize::html($v['title']);?></option><?php
                                        }
                                        ?>
                                    </select>
                        		</div>
                        		<div class="col-lg-4 col-md-4 col-xs-4">
                        			<?php
                        			if (pjAuth::factory('pjAdminSchedule', 'pjActionPrintMonthly')->hasAccess())
                        			{
                            			?>
                                    	<div class="form-group">
                                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionPrintMonthly&date=<?php echo date('Y-m-d', $date_ts); ?><?php echo $controller->_get->check('calendar_id') && $controller->_get->toInt('calendar_id') > 0 ? '&calendar_id=' . $controller->_get->toInt('calendar_id') : NULL;?>" class="btn btn-primary btn-outline" target="_blank"><i class="fa fa-print m-r-xs"></i> <?php __('booking_schedule_print'); ?></a>
                                        </div>
                                        <?php
                        			}
                                    ?>
                                </div>
                        	</div>
                        
                        </div>

                        
                        <div class="col-lg-5 col-md-5 col-xs-4">
                            <label class="control-label">&nbsp;</label>

                            <div class="clearfix">
								<?php
								if (pjAuth::factory('pjAdminSchedule', 'pjActionWeekly')->hasAccess())
								{
    								?>
                                    <div class="form-group pull-right">
                                        <div class="switch onoffswitch-data pull-right">
                                            <div class="onoffswitch">
                                                <input type="checkbox" class="onoffswitch-checkbox" id="mode" name="mode" checked="checked" data-href="<?php echo $_SERVER['PHP_SELF'];?>?controller=pjAdminSchedule&action=pjActionWeekly">
                                                <label class="onoffswitch-label" for="mode">
                                                    <span class="onoffswitch-inner" data-on="<?php __('schedule_weekly_view_lowercase');?>" data-off="<?php __('schedule_monthly_view_lowercase');?>"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div><!-- /.form-group -->
                                    <?php
								}
                                ?>
                            </div>
                        </div>
                    </div><!-- /.row -->
                    
                    <div class="row">
						<?php
						$next_month = date('Y-m-01', strtotime('+1 month', $date_ts));
						$prev_month = date('Y-m-01', strtotime('-1 month', $date_ts));
						?>
						<div class="col-md-2 col-sm-3 col-xs-6">
							<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionMonthly&date=<?php echo $prev_month; ?><?php echo $controller->_get->check('calendar_id') && $controller->_get->toInt('calendar_id') > 0 ? '&calendar_id=' . $controller->_get->toInt('calendar_id') : NULL;?>" class="btn btn-primary btn-outline"><i class="fa fa-angle-left"></i></a>
						</div>

						<div class="col-md-2 col-sm-3 col-xs-6 text-right pull-right">
							<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionMonthly&date=<?php echo $next_month; ?><?php echo $controller->_get->check('calendar_id') && $controller->_get->toInt('calendar_id') > 0 ? '&calendar_id=' . $controller->_get->toInt('calendar_id') : NULL;?>" class="btn btn-primary btn-outline"><i class="fa fa-angle-right"></i></a>
						</div>
								
						<div class="col-md-8 col-sm-6 col-xs-12 text-center">
							<h2 class="calendar-title"><?php echo $months[date('n', $date_ts)];?>, <?php echo date('Y', $date_ts);?></h2>
						</div> 
                    </div>
                </div>
				<?php
				$days = __('days', true);
				$week_start = $tpl['option_arr']['o_week_start'];
				$row_arr = array_chunk($tpl['dates_arr'], 7, true);
				?>
				<div class="table-responsive table-car-availability table-appointment-management">
					<table class="table table-bordered">
                        <thead>
                            <tr>
                            	<?php
                            	if($tpl['option_arr']['o_show_week_numbers'] == 1)
                            	{
                            	    ?>
                                    <th>#</th>
                                    <?php
                            	}
                            	?>
                                <th><strong><?php echo $days[$week_start%7];?></strong></th>
                                <th><strong><?php echo $days[($week_start + 1)%7];?></strong></th>
                                <th><strong><?php echo $days[($week_start + 2)%7];?></strong></th>
                                <th><strong><?php echo $days[($week_start + 3)%7];?></strong></th>
                                <th><strong><?php echo $days[($week_start + 4)%7];?></strong></th>
                                <th><strong><?php echo $days[($week_start + 5)%7];?></strong></th>
                                <th><strong><?php echo $days[($week_start + 6)%7];?></strong></th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php
                        	$now = time();
                            foreach($row_arr as $k => $row)
                            {       
                                reset($row);
                                $first_iso_date = key($row); 
                                ?>
                                <tr>
                                	<?php
                                	if($tpl['option_arr']['o_show_week_numbers'] == 1)
                                	{
                                    	?>
                                    	<td><?php echo date('W', strtotime($first_iso_date));?></td>
                                    	<?php
                                	}
                                	foreach($row as $iso_date => $item)
                                	{
                                	    include dirname(__FILE__) . '/elements/slots.php';
                                	}
                                	?>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
				</div><!-- /.table-responsive table-car-availability table-appointment-management -->
                
                <div class="donut-chart-legend no-padding">
                    <strong class="donut-color-1"></strong>
                    <span><?php __('schedule_legend_available')?></span>

                    <strong class="donut-color-2"></strong>
                    <span><?php __('schedule_legend_pending')?></span>
                    
                    <strong class="bg-danger"></strong>
                    <span><?php __('schedule_legend_confirmed')?></span>

                    <strong class="bg-muted"></strong>
                    <span><?php __('schedule_legend_passed');?></span>
                </div>
                
                <!-- remove this -->                   
                <br>
                <br>
                <br>
                <?php
                if (pjAuth::factory('pjAdminSchedule', 'pjActionWeekly')->hasAccess())
                {
                    ?>
                    <a href="<?php echo $_SERVER['PHP_SELF'];?>?controller=pjAdminSchedule&action=pjActionWeekly" class="btn btn-lg btn-primary"><?php __('schedule_weekly_view')?></a>
                    <?php
                }
                ?>
            </div>
        </div>
    </div><!-- /.col-lg-12 -->
</div><!-- /.wrapper wrapper-content -->

<div class="modal fade" id="reminderEmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
		      <div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        	<h4 class="modal-title"><?php __('schedule_send_reminder_email'); ?></h4>
		      </div>
		      <div id="emailContentWrapper" class="modal-body"></div>
		      <div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php __('btnCancel');?></button>
		        	<button id="btnSendEmailConfirm" type="button" class="btn btn-primary"><?php __('btnSend');?></button>
		      </div>
	    </div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.are_you_sure = <?php x__encode('schedule_are_you_sure');?>;
myLabel.cancel_text = <?php x__encode('schedule_cancel_booking_text');?>;
myLabel.btn_confirm_cancel = <?php x__encode('btn_confirm_cancel');?>;
</script>