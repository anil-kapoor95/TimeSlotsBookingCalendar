<?php
$titles = __('error_titles', true);
$bodies = __('error_bodies', true);
$months = __('months', true);
ksort($months);
$short_days = __('short_days', true);
$statuses = __('booking_statuses', true, true);
?>
<div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo $tpl['date_format']; ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-10">
                <h2><?php __('infoBookingsTitle');?></h2>
            </div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoBookingsDesc');?></p>
    </div><!-- /.col-md-12 -->
</div><!-- /.row wrapper border-bottom white-bg page-heading -->

<div class="row wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<?php
    	$error_code = $controller->_get->toString('err');
    	if (!empty($error_code))
    	{
    		switch (true)
    		{
    			case in_array($error_code, array('ABK01', 'ABK03')):
    				?>
    				<div class="alert alert-success">
    					<i class="fa fa-check m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo @$bodies[$error_code]?>
    				</div>
    				<?php 
    				break;
    			case in_array($error_code, array('ABK04', 'ABK05', 'ABK15')):	
    				?>
    				<div class="alert alert-danger">
    					<i class="fa fa-exclamation-triangle m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo @$bodies[$error_code]?>
    				</div>
    				<?php
    				break;
    		}
    	} 
    	?>
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<div class="row m-b-md">
					<form action="" method="get" class="frm-filter">
                        <div class="col-sm-3">
                        	<?php
                        	if(pjAuth::factory('pjAdminBookings', 'pjActionCreate')->hasAccess())
                        	{
                            	?>
                                <div class="form-group">
                                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate" class="btn btn-primary pjTsCheckAssignCalendar"><i class="fa fa-plus"></i> <?php __('btnAddBooking');?></a>
                                </div>
                                <?php
                        	}
                            ?>
                        </div><!-- /.col-md-6 -->
    
                        <div class="col-md-3 col-sm-5">
                            <div class="form-group">
                                <div class="input-group">
    								<input type="text" name="q" placeholder="<?php __('btnSearch', false, true); ?>" class="form-control">
    	
    								<div class="input-group-btn">
    									<button class="btn btn-primary" type="submit">
    										<i class="fa fa-search"></i>
    									</button>
    								</div>
    							</div>
                            </div>
                        </div><!-- /.col-md-3 -->
    
                        <div class="col-lg-2 col-md-3 col-sm-4">
                            <div class="form-group">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="btn btn-primary btn-outline btn-advance-search"><?php __('btnAdvanceSearch');?></a>
                            </div>
                        </div><!-- /.col-md-2 -->
    
                        <div class="col-lg-2 col-lg-offset-2 col-md-3 col-sm-12 text-right">
                            <div class="form-group">
                                <select class="form-control pj-filter-status" name="booking_status">
    								<option value="">-- <?php __('lblAll');?> --</option>
    								<?php 
    								foreach ($statuses as $k => $v) 
    								{ 
    								    ?>
    									<option value="<?php echo $k;?>"><?php echo $v;?></option>
    									<?php 
    								} 
    								?>
    							</select>
                            </div>
                        </div><!-- /.col-md-6 -->
                    </form>
                </div><!-- /.row -->
                
                <div id="collapseOne" class="collapse">
                    <div class="m-b-lg">
                        <ul class="agile-list no-padding">
                            <li class="success-element b-r-sm">
                                <div class="panel-body">
                                    <form action="" method="get" class="frm-filter-advanced">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php __('lblCalendar'); ?></label>

                                                    <select class="form-control" id="search_calendar_id" name="calendar_id">
                                						<option value="">-- <?php __('lblAllCalendars')?> --</option>
                                						<?php
                                						foreach ($tpl['calendar_arr'] as $calendar)
                                						{
                                							?><option value="<?php echo $calendar['id']; ?>"><?php echo pjSanitize::html($calendar['title']); ?></option><?php
                                						}
                                						?>
                                					</select>
                                                </div><!-- /.form-group -->
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php __('booking_client'); ?></label>

                                                    <input type="text" name="q" class="form-control" value="<?php echo $controller->_get->check('q') ? $controller->_get->toString('q') : NULL; ?>" />
                                                </div><!-- /.form-group -->
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <div class="form-group">
                                                     <label class="control-label"><?php __('booking_status'); ?></label>

                                                    <select name="booking_status" class="form-control">
                                						<option value="">-- <?php __('lblChoose'); ?> --</option>
                                						<?php
                                						foreach ($statuses as $k => $v)
                                						{
                                						    ?><option value="<?php echo $k; ?>"<?php echo $controller->_get->check('booking_status') && $controller->_get->toString('booking_status') == $k ? ' selected="selected"' : NULL; ?>><?php echo pjSanitize::html($v); ?></option><?php
                                						}
                                						?>
                                					</select>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <label><?php __('booking_dates'); ?></label>

                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 

                                                        <input type="text" name="date_from" class="form-control datepick" readonly="readonly"/>

                                                        <span class="input-group-addon"><?php __('lblTo'); ?></span>

                                                        <input type="text" name="date_to" class="form-control datepick" readonly="readonly"/>
                                                    </div>
                                                </div><!-- /.form-group -->
                                            </div>
                                        </div>

                                        <div class="m-t-sm">
                                            <button class="btn btn-primary" type="submit"><?php __('btnSearch');?></button>

											<button class="btn btn-primary btn-outline" type="reset"><?php __('btnCancel');?></button>
                                        </div>
                                    </form>
                                </div><!-- /.panel-body -->
                            </li><!-- /.panel panel-primary -->
                        </ul>
                    </div><!-- /.m-b-lg -->
                </div><!-- /.collapse -->
                
                <div id="grid"></div>
                
			</div><!-- /.ibox-content -->
		</div><!-- /.ibox float-e-margins -->
	</div><!-- /.col-lg-12 -->
</div><!-- /.row wrapper wrapper-content animated fadeInRight -->

<script type="text/javascript">
var pjGrid = pjGrid || {};
pjGrid.queryString = "";
<?php
if ($controller->_get->check('calendar_id'))
{
    ?>pjGrid.queryString += "&calendar_id=<?php echo $controller->_get->toInt('calendar_id'); ?>";<?php
}
if ($controller->_get->check('client_id'))
{
    ?>pjGrid.queryString += "&client_id=<?php echo $controller->_get->toInt('client_id'); ?>";<?php
}
?>
pjGrid.hasAccessUpdate = <?php echo pjAuth::factory('pjAdminBookings', 'pjActionUpdate')->hasAccess() ? 'true' : 'false';?>;
pjGrid.hasAccessDeleteSingle = <?php echo pjAuth::factory('pjAdminBookings', 'pjActionDeleteBooking')->hasAccess() ? 'true' : 'false';?>;
pjGrid.hasAccessDeleteMulti = <?php echo pjAuth::factory('pjAdminBookings', 'pjActionDeleteBookingBulk')->hasAccess() ? 'true' : 'false';?>;

var myLabel = myLabel || {};
myLabel.uuid = <?php x__encode('booking_uuid', false, true); ?>;
myLabel.slots = <?php x__encode('booking_slots', false, true); ?>;
myLabel.status = <?php x__encode('booking_status', false, true); ?>;
myLabel.customer = <?php x__encode('booking_customer', false, true); ?>;
myLabel.total = <?php x__encode('booking_total', false, true); ?>;
myLabel.confirmed = <?php x__encode('booking_statuses_ARRAY_confirmed', false, true);?>;
myLabel.pending = <?php x__encode('booking_statuses_ARRAY_pending', false, true) ?>;
myLabel.cancelled = <?php x__encode('booking_statuses_ARRAY_cancelled', false, true) ?>;
myLabel.export_selected = <?php x__encode('booking_export', false, true); ?>;
myLabel.delete_selected = <?php x__encode('delete_selected', false, true); ?>;
myLabel.delete_confirmation = <?php x__encode('delete_confirmation', false, true); ?>;
</script>