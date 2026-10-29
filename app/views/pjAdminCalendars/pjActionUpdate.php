<?php
$titles = __('error_titles', true);
$bodies = __('error_bodies', true);
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-6">
                <h2><?php echo @$titles['AC12'];?></h2>
                
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
				<?php if ($tpl['is_flag_ready']) : ?>
				<div class="multilang"></div>
				<?php endif; ?>
			</div>
        </div>

        <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php echo @$bodies['AC12']; ?></p>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="tabs-container">
    	<div class="tabs-left">
    		<?php include PJ_VIEWS_PATH . 'pjLayouts/elements/tab-options.php'; ?>
    		<div class="tab-content">
    			<div class="tab-pane active">
    				<div class="panel-body">
    					<div class="panel-body-inner">
    						<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCalendars&amp;action=pjActionUpdate" method="post" id="frmUpdateCalendar" autocomplete="off" enctype="multipart/form-data">
								<input type="hidden" name="calendar_update" value="1" />
								<input type="hidden" name="csrf_token" value="<?php echo pjAppController::getCsrfToken(); ?>" />
								<input type="hidden" name="id" value="<?php echo $tpl['arr']['id'];?>" />
								<div class="row">
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php __('calendar_title');?></label>
            								<?php
            								foreach ($tpl['lp_arr'] as $v)
            								{
            									?>
            									<div class="<?php echo $tpl['is_flag_ready'] ? 'input-group ' : NULL;?>pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
            										<input type="text" class="form-control<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" name="i18n[<?php echo $v['id']; ?>][title]" value="<?php echo pjSanitize::html(@$tpl['arr']['i18n'][$v['id']]['title']); ?>" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>">	
            										<?php if ($tpl['is_flag_ready']) : ?>
            										<span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
            										<?php endif; ?>
            									</div>
            									<?php 
            								}
            								?>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6">
            							<div class="form-group">
            								<label class="control-label"><?php __('calendar_user'); ?></label>
            
            								<select name="user_id[]" class="select-item form-control required" multiple data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>">
            									<?php
            									foreach ($tpl['user_arr'] as $user)
            									{
            									    ?><option value="<?php echo $user['id']; ?>"<?php echo in_array($user['id'], $tpl['arr']['user_id_arr']) ? ' selected="selected"' : NULL;?>><?php echo pjSanitize::html($user['name']); ?></option><?php
            									}
            									?>
            								</select>
            								<small><?php __('lblSelectOwnerNote');?></small>
            							</div>
            						</div>
            						<div class="col-lg-4 col-sm-12 text-right">
                                        <label class="control-label">&nbsp;</label>
                                        <?php
                                        if(!empty($tpl['copy_calendar_arr']))
                                        {
                                            ?>
                                            <div>
                                                <a href="#" class="btn btn-primary btn-outline" data-toggle="modal" data-target="#myModalCopyOptions"><i class="fa fa-copy m-r-xs"></i> <?php __('lblCopyOptions');?></a>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div><!-- /.row -->
                                
                                <div class="hr-line-dashed"></div>
                                
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <h3><?php __('menuBookings');?></h3>

                                        <div class="m-b-sm">
                                        	<?php
                                        	if(pjAuth::factory('pjAdminBookings')->hasAccess())
                                        	{
                                            	?>
                                                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex&amp;calendar_id=<?php echo $controller->getCalendarId();?>" class="btn btn-primary btn-sm btn-outline"><i class="fa fa-check m-r-xs"></i> <?php __('lblViewBookings');?></a>
                                                <?php
                                        	}
                                            ?>

                                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionIndex&amp;calendar_id=<?php echo $controller->getCalendarId();?>" class="btn btn-primary btn-sm btn-outline"><i class="fa fa-table m-r-xs"></i> <?php __('lblViewSchedule');?></a>
                                        </div>

                                        <ul class="list-group clear-list">
                                            <li class="list-group-item fist-item clearfix">
                                                <span class="label label-primary pull-right"><?php __('dash_today');?></span>

                                                <strong><?php echo $tpl['new_cnt_today']?> <?php __('dash_new');?></strong>
                                            </li>
                                            <li class="list-group-item clearfix">
                                                <span class="label label-primary pull-right"><?php __('dash_today');?></span>
                                
                                                <strong><?php echo $tpl['confirmed_cnt_today']?> <?php __('dash_confirmed');?></strong>
                                            </li>
                                            <li class="list-group-item clearfix">
                                                <span class="label label-primary pull-right"><?php __('dash_total');?></span>

                                                <strong><?php echo $tpl['confirmed_cnt']?> <?php __('dash_confirmed');?></strong>
                                            </li>

                                            <li class="list-group-item clearfix">
                                                <span class="label label-primary pull-right"><?php __('dash_total');?></span>

                                                <strong><?php echo $tpl['cancelled_cnt']?> <?php __('dash_cancelled');?></strong>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-lg-8 col-md-12">
                                        <h3><?php __('lblUpcoming5Bookings')?></h3>

                                        <div class="table-responsive table-responsive-secondary">
                                            <table class="table table-striped table-hover no-margins">
                                                <thead>
                                                    <tr>
                                                        <th><?php __('booking_slots')?></th>
                                                        <th><?php __('booking_customer');?></th>
                                                        <th><?php __('booking_status')?></th>
                                                        <th><?php __('booking_total')?></th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                	<?php
                                                	if(!empty($tpl['upcoming_bookings']))
                                                	{
                                                    	$booking_statuses = __('booking_statuses', true);
                                                    	foreach($tpl['upcoming_bookings'] as $k => $v)
                                                    	{
                                                    	    $items = array();
                                                    	    $client_arr = array();
                                                    	    if(isset($tpl['upcoming_items'][$v['id']]))
                                                    	    {
                                                    	        foreach($tpl['upcoming_items'][$v['id']] as $item)
                                                    	        {
                                                    	            $date = $item[0]; $start = @$item[1]; $end = @$item[2]; $cnt = @$item[3];
                                                    	            if($cnt > 1)
                                                    	            {
                                                    	                $items[] = date($tpl['option_arr']['o_date_format'], strtotime($date)) . ', ' . date($tpl['option_arr']['o_time_format'], strtotime($start)) . ' - ' . date($tpl['option_arr']['o_time_format'], strtotime($end)) . ' x ' . $cnt;
                                                    	            }else{
                                                    	                $items[] = date($tpl['option_arr']['o_date_format'], strtotime($date)) . ', ' . date($tpl['option_arr']['o_time_format'], strtotime($start)) . ' - ' . date($tpl['option_arr']['o_time_format'], strtotime($end));
                                                    	            }
                                                    	        }
                                                    	    }
                                                    	    if(!empty($v['customer_name']))
                                                    	    {
                                                    	        $client_arr[] = pjSanitize::html($v['customer_name']);
                                                    	    }
                                                    	    if(!empty($v['customer_email']))
                                                    	    {
                                                    	        $client_arr[] = pjSanitize::html($v['customer_email']);
                                                    	    }
                                                    	    if(!empty($v['customer_phone']))
                                                    	    {
                                                    	        $client_arr[] = pjSanitize::html($v['customer_phone']);
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
                                                                <td><?php echo join("<br/>", $items)?></td>
                                                                <td><?php echo join("<br/>", $client_arr)?></td>
                                                                <td><div class="btn bg-<?php echo $class;?> btn-xs no-margin"><i class="fa<?php echo $fa_class;?>"></i> <?php echo $booking_statuses[$v['booking_status']]?></div></td>
                                                                <td><?php echo pjCurrency::formatPrice($v['booking_total']);?></td>
                                                            </tr>
                                                            <?php 
                                                    	}
                                                	}else{
                                                	    ?>
                                                	    <tr><td colspan="4"><?php __('dash_no_upcoming_bookings');?></td></tr>
                                                	    <?php
                                                	}
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="hr-line-dashed"></div>
                                <div class="clearfix">
                					<button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in">
                						<span class="ladda-label"><?php __('btnSave', false, true); ?></span>
                						<?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                					</button>
                					<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCalendars&amp;action=pjActionIndex" class="btn btn-white btn-lg pull-right"><?php __('btnCancel'); ?></a>
                				</div>
							</form>
    					</div><!-- /.panel-body-inner -->
    				</div><!-- /.panel-body -->
    			</div><!-- /.tab-pane active -->
    		</div><!-- /.tab-content -->
    	</div><!-- /.tabs-left -->
    </div><!-- /.tabs-container -->
</div><!-- /.wrapper wrapper-content animated fadeInRight -->

<div class="modal fade" id="myModalCopyOptions" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="?" method="post">
            	<input type="hidden" name="current_calendar_id" value="<?php echo $tpl['arr']['id'];?>" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                    <h3 class="modal-title" id="myModalLabel"><?php __('lblCopyOptions');?></h3>
                </div>

                <div class="panel-body">
                	<h3><?php __('lblCopyOptionsNote');?></h3><br/>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="radio radio-primary">
                                <input type="radio" id="radio-cal-1" name="radio-cal" value="from">
                                <label for="radio-cal-1"><?php __('lblFromAnotherCalendar');?></label>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="radio radio-primary">
                                <input type="radio" id="radio-cal-2" name="radio-cal" checked value="to">
                                <label for="radio-cal-2"><?php __('lblToAnotherCalendar');?></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="calendar_id" class="form-control pj-selector-row-count">
                            <?php
                            foreach($tpl['copy_calendar_arr'] as $k => $v)
                            {
                                ?><option value="<?php echo $v['id']?>"><?php echo pjSanitize::html($v['title']);?></option><?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="hr-line-dashed"></div>
                    
					<h3><?php __('lblSelectOptionsToCopy');?></h3><br/>
					
                    <div class="row">
                        <div class="col-sm-6">
                        	<?php
                        	if(pjAuth::factory('pjAdminTime', 'pjActionIndex')->hasAccess())
                        	{
                            	?>
                                <div class="form-group">
                                    <label class="control-label"><input type="checkbox" class="i-checks" name="tabs[]" value="working_time"> <span class="m-l-xs"><?php __('tabWorkingTime');?></span></label>
                                </div>
                                <?php
                        	}
                        	if(pjAuth::factory('pjAdminTime', 'pjActionCustom')->hasAccess())
                        	{
                                ?>
                                <div class="form-group">
                                    <label class="control-label"><input type="checkbox" class="i-checks" name="tabs[]" value="custom_working_time"> <span class="m-l-xs"><?php __('tabCustomWorkingTime');?> </span></label>
                                </div>
                                <?php
                        	}
                        	if(pjAuth::factory('pjAdminOptions', 'pjActionIndex')->hasAccess())
                        	{
                                ?>
                                <div class="form-group">
                                    <label class="control-label"><input type="checkbox" class="i-checks" name="tabs[]" value="booking_options"> <span class="m-l-xs"><?php __('tabBookingOptions');?> </span></label>
                                </div>
                                <?php
                        	}
                            ?>
                        </div>

                        <div class="col-sm-6">
                        	<?php
                        	if(pjAuth::factory('pjAdminOptions', 'pjActionPayments')->hasAccess())
                        	{
                            	?>
                                <div class="form-group">
                                    <label class="control-label"><input type="checkbox" class="i-checks" name="tabs[]" value="payments"> <span class="m-l-xs"><?php __('menuPayments');?></span></label>
                                </div>
                                <?php
                        	}
                        	if(pjAuth::factory('pjAdminOptions', 'pjActionTerms')->hasAccess())
                        	{
                                ?>
                                <div class="form-group">
                                    <label class="control-label"><input type="checkbox" class="i-checks" name="tabs[]" value="terms"> <span class="m-l-xs"><?php __('tabTerms');?> </span></label>
                                </div>
                                <?php
                        	}
                        	if(pjAuth::factory('pjAdminOptions', 'pjActionNotifications')->hasAccess())
                        	{
                                ?>
                                <div class="form-group">
                                    <label class="control-label"><input type="checkbox" class="i-checks" name="tabs[]" value="notifications"> <span class="m-l-xs"><?php __('menuNotifications');?></span></label>
                                </div>
                               	<?php
                        	}
                        	if(pjAuth::factory('pjAdminOptions', 'pjActionBookingForm')->hasAccess())
                        	{
                                ?>
                                <div class="form-group">
                                    <label class="control-label"><input type="checkbox" class="i-checks" name="tabs[]" value="booking_form"> <span class="m-l-xs"><?php __('menuBookingForm');?></span></label>
                                </div>
                                <?php
                        	}
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-copy m-r-xs"></i> <?php __('btnCopy')?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php __('btnCancel')?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.isFlagReady = "<?php echo $tpl['is_flag_ready'] ? 1 : 0;?>";
myLabel.choose = "-- <?php __('lblChoose', false, true); ?> --";
<?php if ($tpl['is_flag_ready']) : ?>
	var pjLocale = pjLocale || {};
	pjLocale.langs = <?php echo $tpl['locale_str']; ?>;
	pjLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
<?php endif; ?>
</script>