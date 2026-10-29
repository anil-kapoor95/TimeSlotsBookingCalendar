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

                                <select id="jump_to" name="date" class="form-control" data-type="Weekly">
                                	<?php
                                	$months = __('months', true);
                                	$week_range = pjUtil::getWeekRange(date('Y-m-d'), $tpl['option_arr']['o_week_start']);
                                	$selected_first_date = $tpl['option_arr']['o_week_start'] == 0 ? $week_range[0] : date('Y-m-d');
                                	if($controller->_get->check('date'))
                                	{
                                	    $selected_first_date = $controller->_get->toString('date');
                                	    $week_range = pjUtil::getWeekRange($selected_first_date, $tpl['option_arr']['o_week_start']);
                                	}
                                	$week_text = '';
                                	for($i = -4; $i <= 4; $i++)
                                	{
                                	    $first_date_of_week = date('Y-m-d', strtotime($selected_first_date) + (86400*7*$i));
                                	    $week_number  = (int) date('W', strtotime($first_date_of_week));
                                	    $week_arr = pjUtil::getWeekRange($first_date_of_week, $tpl['option_arr']['o_week_start']);
                                	    $first_date_ts = strtotime($week_arr[0]);
                                	    $last_date_ts = strtotime($week_arr[1]);
                                	    $range_text = $tpl['option_arr']['o_show_week_numbers'] == 1 ? __('schedule_week', true) . ' ' . $week_number . ': ' : NULL;
                                	    $range_text .= $months[date('n', $first_date_ts)] . ' ' . date('j', $first_date_ts);
                                	    $range_text .= ' - ' . $months[date('n', $last_date_ts)] . ' ' . date('j', $last_date_ts);
                                	    if($first_date_of_week == $selected_first_date)
                                	    {
                                	        $week_text = $range_text;
                                	        ?>
                                            <option value="<?php echo $first_date_of_week;?>" selected="selected"><?php echo $range_text;?></option>
                                            <?php
                                        }else{
                                            ?>
                                            <option value="<?php echo $first_date_of_week;?>"><?php echo $range_text;?></option>
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
                        			<select id="calendar_id" name="calendar_id" class="form-control" data-type="Weekly">
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
                        			if (pjAuth::factory('pjAdminSchedule', 'pjActionPrintWeekly')->hasAccess())
                        			{
                            			?>
                                    	<div class="form-group">
                                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionPrintWeekly&date=<?php echo $selected_first_date; ?><?php echo $controller->_get->check('calendar_id') && $controller->_get->toInt('calendar_id') > 0 ? '&calendar_id=' . $controller->_get->toInt('calendar_id') : NULL;?>" class="btn btn-primary btn-outline" target="_blank"><i class="fa fa-print m-r-xs"></i> <?php __('booking_schedule_print'); ?></a>
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
								if (pjAuth::factory('pjAdminSchedule', 'pjActionMonthly')->hasAccess())
								{
    								?>
                                    <div class="form-group pull-right">
                                        <div class="switch onoffswitch-data pull-right">
                                            <div class="onoffswitch">
                                                <input type="checkbox" class="onoffswitch-checkbox" id="mode" name="mode" data-href="<?php echo $_SERVER['PHP_SELF'];?>?controller=pjAdminSchedule&action=pjActionMonthly">
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
						<div class="col-md-2 col-sm-3 col-xs-6">
							<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionWeekly&date=<?php echo date('Y-m-d', strtotime($selected_first_date) - 86400 * 7); ?><?php echo $controller->_get->check('employee_id') && $controller->_get->toInt('employee_id') > 0 ? '&employee_id=' . $controller->_get->toInt('employee_id') : NULL;?>" class="btn btn-primary btn-outline"><i class="fa fa-angle-left"></i></a>
						</div>

						<div class="col-md-2 col-sm-3 col-xs-6 text-right pull-right">
							<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionWeekly&date=<?php echo date('Y-m-d', strtotime($selected_first_date) + 86400 * 7); ?><?php echo $controller->_get->check('employee_id') && $controller->_get->toInt('employee_id') > 0 ? '&employee_id=' . $controller->_get->toInt('employee_id') : NULL;?>" class="btn btn-primary btn-outline"><i class="fa fa-angle-right"></i></a>
						</div>

						<div class="col-md-8 col-sm-6 col-xs-12 text-center">
							<h2 class="calendar-title"><?php echo $week_text;?></h2>
						</div> 
                    </div>
                </div>
				<?php
				$days = __('days', true);
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
                            	foreach($tpl['dates_arr'] as $iso_date => $item)
                            	{
                            	    ?><th><strong><?php echo $days[date('w', strtotime($iso_date))];?></strong></th><?php
                            	}
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                        	<tr>
                            	<?php
                            	if($tpl['option_arr']['o_show_week_numbers'] == 1)
                            	{
                                	?>
                                    <td>#<?php echo date('W', strtotime($selected_first_date));?></td>
                                    <?php
                            	}
                            	foreach($tpl['dates_arr'] as $iso_date => $item)
                            	{
                            	    include dirname(__FILE__) . '/elements/slots.php';
                            	}
                                ?>
                            </tr>
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
                if (pjAuth::factory('pjAdminSchedule', 'pjActionMonthly')->hasAccess())
                {
                    ?>
                    <a href="<?php echo $_SERVER['PHP_SELF'];?>?controller=pjAdminSchedule&action=pjActionMonthly" class="btn btn-lg btn-primary"><?php __('schedule_monthly_view')?></a>
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