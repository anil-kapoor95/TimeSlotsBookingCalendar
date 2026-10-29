<?php
$titles = __('error_titles', true);
$bodies = __('error_bodies', true);

include PJ_VIEWS_PATH . 'pjLayouts/elements/update-calendar-title.php';
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="tabs-container">
    	<?php
    	$error_code = $controller->_get->toString('err');
    	if (!empty($error_code))
        {
            if(in_array($error_code, array('AT02')))
            {
                ?>
    			<div class="alert alert-success">
    				<i class="fa fa-check m-r-xs"></i>
    				<strong><?php echo @$titles[$error_code]; ?></strong>
    				<?php echo @$bodies[$error_code]; ?>
    			</div>
    			<?php
            }
        }
        ?>
    	<div class="tabs-left">
    		<?php 
    		include PJ_VIEWS_PATH . 'pjLayouts/elements/tab-options.php';
    		
    		$foreign_id = $controller->getCalendarId();
    		if($controller->_get->toInt('id') > 0)
    		{
    		    $foreign_id = $controller->_get->toInt('id');
    		}
    		$days = __('days', true);
    		$w_days = array(
    		    'monday' => $days[1],
    		    'tuesday' => $days[2],
    		    'wednesday' => $days[3],
    		    'thursday' => $days[4],
    		    'friday' => $days[5],
    		    'saturday' => $days[6],
    		    'sunday' => $days[0]
    		);
    		
    		$filter = __('custom_filter', true);
    		
    		$months = __('months', true);
    		ksort($months);
    		$short_days = __('short_days', true);
    		
    		$date = NULL;
    		$dayoff_class = NULL;
    		$checked = NULL;
    		$from = NULL;
    		$length = NULL;
    		$slots = NULL;
    		$limit = 1;
    		$people = 1;
    		$price = NULL;
    		if(isset($tpl['arr']))
    		{
    		    $date = date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['date']));
    		    if ($tpl['arr']['is_dayoff'] == 'T')
    		    {
    		        $checked = ' checked="checked"';
    		        $dayoff_class = ' pjTsbDayOff';
    		    }
    		    $from = date($tpl['option_arr']['o_time_format'], strtotime($tpl['arr']['start_time']));
    		    $length = $tpl['arr']['slot_length'];
    		    $slots = $tpl['arr']['slots'];
    		    $limit = $tpl['arr']['slot_limit'];
    		    $people = $tpl['arr']['people'];
    		    $price = $tpl['arr']['price'];
    		}
    		?>
    		<div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo $tpl['date_format']; ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
    		
    		<div class="tab-content">
    			<div class="tab-pane active">
    				<div class="panel-body">
    					<div class="panel-body-inner">
    						
    						<div class="ibox-content ibox-heading">
                                <h4><?php __('lblAddCustom');?></h4>

                                <small><?php __('lblAddCustomDesc');?></small>
                            </div>
                            <div class="ibox-content">
                            	<div class="m-b-lg">
                            		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionCustom&amp;id=<?php echo $foreign_id?>" method="post" id="frmTimeCustom">
                            			<input type="hidden" name="custom_time" value="1" />
                            			<input type="hidden" name="csrf_token" value="<?php echo pjAppController::getCsrfToken(); ?>" />
                            			<input type="hidden" name="date_id" value="<?php echo isset($tpl['arr']) ? $tpl['arr']['id'] : NULL;?>" />
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label"><?php __('time_date'); ?></label>
                                                
                                                    <div class="input-group date">
                                                        <input type="text" name="date" value="<?php echo $date;?>" class="form-control required" readonly="readonly" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>"/>
                                                
                                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div><!-- /.form-group -->
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-6">
                                                <div class="form-group">
                                					<label class="control-label"><?php __('time_is');?></label>
                                					
                                					<div class="switch m-t-xs">
                            							<div class="onoffswitch onoffswitch-yn">
                            								<input class="onoffswitch-checkbox tsIsDayOff" id="is_dayoff" name="is_dayoff" type="checkbox" value="T"<?php echo $checked;?>>
                            								<label class="onoffswitch-label" for="is_dayoff">
                            									<span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T'); ?>" data-off="<?php __('_yesno_ARRAY_F'); ?>"></span>
                            									<span class="onoffswitch-switch"></span>
                            								</label>
                            							</div>
                            						</div>
                                				</div><!-- /.form-group -->
                                            </div>
                                        </div><!-- /.row -->
                                        
                                        <div class="pjTsWTimeWrapper<?php echo $dayoff_class;?>">
                                        
                                            <div class="hr-line-dashed"></div>
                                            
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php __('time_from');?></label>
                        
                                                        <div class="input-group clockpicker">
                                                            <input type="text" name="start" value="<?php echo $from;?>" class="form-control required" readonly data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>"/>
                                                            
                                                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                        </div>
                                                    </div><!-- /.form-group -->
                                                </div>
    
                                                <div class="col-lg-3 col-md-3 col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php __('time_length');?></label>
                                                        
                                                        <select name="slot_length" id="slot_length" class="form-control">
                                        				<?php
                                        				$time_slot_length = __('time_slot_length', true);
                                        				ksort($time_slot_length);
                                        				foreach ($time_slot_length as $sk => $sv)
                                        				{
                                        					?><option value="<?php echo $sk; ?>"<?php echo $length == $sk ? ' selected="selected"' : NULL; ?>><?php echo $sv; ?></option><?php
                                        				}
                                        				?>
                                        				</select>
                                                    </div><!-- /.form-group -->
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php __('lblNumberOfSlots');?></label>
                                                        
                                                        <select name="slots" id="number_of_slots" class="form-control">
                                    					<?php
                                    					foreach (range(1, 144) as $number) 
                                    					{
                                    	    
                                    						?><option value="<?php echo $number; ?>"<?php echo $slots == $number ? ' selected="selected"' : NULL; ?>><?php echo $number; ?></option><?php
                                    					}
                                    					?>
                                    					</select>
                                                    </div><!-- /.form-group -->
                                                </div>
                                                <div class="col-md-3 col-sm-6 col-xs-12">
                                            		<div class="form-group">
                                                        <label class="control-label"><?php __('lblEndTime');?></label>
                                            
                                                        <input type="text" name="end" class="form-control" readonly>
                                                    </div><!-- /.form-group -->
                                            	</div><!-- /.col-md-3 col-sm-6 col-xs-12 -->
                                            	
                                            </div><!-- /.row -->
                                            
                                            <div id="tsCustomSlotBox"></div>
                                            
                                            <div class="hr-line-dashed"></div>
                                            
                                            <div class="row">
                                				<div class="col-md-3 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php __('time_limit');?></label>
        
                                                        <input type="text" name="slot_limit" id="slot_limit" class="form-control field-int" maxlength="5" value="<?php echo $limit;?>" />
                                                        <small><?php __('time_limit_note');?></small>
                                                    </div><!-- /.form-group -->
                                                </div><!-- /.col-md-3 col-sm-6 col-xs-12 -->
                                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    				<div class="form-group">
                                                        <label class="control-label"><?php __('time_people_per_slot');?></label>
                        
                                                        <input type="text" name="people" id="people" class="form-control field-int" maxlength="5" value="<?php echo $people;?>" />
                                                        <small><?php __('time_people_per_slot_note');?></small>
                                                    </div><!-- /.form-group -->
                                    			</div><!-- /.col-md-3 col-sm-6 col-xs-12 -->
                                    			
                                    			<div id="pjTsCustomDefaultPrice" class="col-md-3 col-sm-6 col-xs-12" style="display:<?php echo !empty($tpl['price_arr']) ? 'none' : ''; ?>;">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php __('time_price');?></label>
        
                                                        <div class="input-group">
                                                            <input type="text" name="default_price" id="default_price" value="<?php echo $price;?>" class="form-control tsPriceField number"/>
                                                            
                                                            <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span>
                                                        </div>
        
                                                    </div><!-- /.form-group -->
                                                </div><!-- /.col-md-3 col-sm-6 col-xs-12 -->
                                                <div class="col-md-3 col-sm-6 col-xs-12">
                                                	<div class="form-group">
                                                        <label class="control-label">&nbsp;</label>
        
                                                        <label class="normal-label m-t-xs">
                                                        	<input type="checkbox" name="single_price" id="single_price" value="1"<?php echo !empty($tpl['price_arr']) ? NULL : ' checked="checked"'; ?> class="m-r-xs i-checks pps"/> <span class="m-l-xs"><?php __('time_single_price');?></span>
                                                        </label>
                                                    </div><!-- /.form-group -->
                                                </div><!-- /.col-md-3 col-sm-6 col-xs-12 -->
                                    		</div><!-- /.row -->
                                                
                                        	<div id="boxPPS"></div>
                                        	
                                        </div><!-- /.pjTsWTimeWrapper -->
                                        
                                        <div class="hr-line-dashed"></div>
                                        
                                        <div class="clearfix">
                        					<button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in">
                        						<span class="ladda-label"><?php __('btnSave', false, true); ?></span>
                        						<?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                        					</button>
                        					<?php
                        					if(isset($tpl['arr'])){
                        					    ?>
                        					    <button class="btn btn-white btn-lg pull-right" type="button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminTime&action=pjActionCustom&id=<?php echo $foreign_id;?>';"><?php __('btnCancel'); ?></button>
                        					    <?php
                        					}
                        					?>
                        				</div>
                                    </form>
                            	</div><!-- /.m-b-lg -->
                            </div>
    						
    						<div class="ibox-content ibox-heading">
                                <h4><?php __('infoCustomDatesTitle');?></h4>
								<small><?php __('infoCustomDatesDesc');?></small>
                            </div>
                			<div class="ibox-content">
        						<div class="row m-b-md">
                                	<div class="col-md-4 col-sm-4 text-left">
                        				
                                	</div>
                                    <div class="col-md-8 text-right">
        	                            <div class="btn-group" role="group" aria-label="...">
        	                                <button type="button" class="btn btn-primary btn-all active"><?php echo $filter['A']; ?></button>
        	                                <button type="button" class="btn btn-default btn-filter" data-column="is_dayoff" data-value="F"><i class="fa fa-check"></i> <?php echo $filter['T']; ?></button>
        	                                <button type="button" class="btn btn-default btn-filter" data-column="is_dayoff" data-value="T"><i class="fa fa-times"></i> <?php echo $filter['F']; ?></button>
        	                            </div>
        	                        </div><!-- /.col-md-6 -->
                                </div><!-- /.row -->
                                
                                <div id="grid"></div>
                    		</div>   
    					</div><!-- /.panel-body-inner -->
    				</div><!-- /.panel-body -->
    			</div><!-- /.tab-pane active -->
    		</div><!-- /.tab-content -->
    	</div><!-- /.tabs-left -->
    </div><!-- /.tabs-container -->
</div><!-- /.wrapper wrapper-content animated fadeInRight -->

<?php
$show_period = 'false';
if((strpos($tpl['option_arr']['o_time_format'], 'a') > -1 || strpos($tpl['option_arr']['o_time_format'], 'A') > -1))
{
    $show_period = 'true';
}
?>
<script type="text/javascript">
var pjGrid = pjGrid || {};
pjGrid.queryString = "";
<?php
if ($controller->_get->check('id'))
{
    ?>pjGrid.queryString += "&calendar_id=<?php echo $controller->_get->toInt('id'); ?>";<?php
}
?>
pjGrid.has_delete = <?php echo pjAuth::factory('pjAdminTime', 'pjActionDeleteDate')->hasAccess() ? "true" : "false"; ?>;
pjGrid.has_delete_bulk = <?php echo pjAuth::factory('pjAdminTime', 'pjActionDeleteDateBulk')->hasAccess() ? "true" : "false"; ?>;
var myLabel = myLabel || {};
myLabel.showperiod = <?php echo $show_period; ?>;
myLabel.btnOK = <?php x__encode('gridBtnOk'); ?>;
myLabel.time_price = <?php x__encode('time_price', false, true); ?>;
myLabel.time_date = <?php x__encode('time_date', false, true); ?>;
myLabel.time_start = <?php x__encode('time_from', false, true); ?>;
myLabel.time_end = <?php x__encode('time_to', false, true); ?>;
myLabel.time_lunch_start = <?php x__encode('time_lunch_from', false, true); ?>;
myLabel.time_lunch_end = <?php x__encode('time_lunch_to', false, true); ?>;
myLabel.time_dayoff = <?php x__encode('time_is', false, true); ?>;
myLabel.time_yesno = <?php echo pjAppController::jsonEncode(__('_yesno', true)); ?>;
myLabel.time_yes = <?php x__encode('_yesno_ARRAY_T', false, true); ?>;
myLabel.time_no = <?php x__encode('_yesno_ARRAY_F', false, true); ?>;
myLabel.delete_selected = <?php x__encode('delete_selected', false, true); ?>;
myLabel.delete_confirmation = <?php x__encode('delete_confirmation', false, true); ?>;
myLabel.restriction_per_slot_msg = <?php x__encode('restriction_per_slot_msg'); ?>;
myLabel.price_limit_msg = <?php x__encode('lblPriceLimitMsg'); ?>;
</script>