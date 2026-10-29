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
            if(in_array($error_code, array('AO06')))
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
    		
    		$show_form = false;
    		$selected_day = 'monday';
    		if($controller->_get->check('day'))
    		{
    		    if(in_array($controller->_get->toString('day'), array('monday','tuesday','wednesday','thursday','friday','saturday','sunday')))
    		    {
    		        $selected_day = $controller->_get->toString('day');
    		        $show_form = true;
    		    }
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
    		?>
    		<div class="tab-content">
    			<div class="tab-pane active">
    				<div class="panel-body">
    					<div class="panel-body-inner">
    						<?php
    						if (isset($tpl['wt_arr']) && !empty($tpl['wt_arr']))
    						{
    						    $time_slot_length = __('time_slot_length', true);
    						    ksort($time_slot_length);
    						    
    						    $from = date($tpl['option_arr']['o_time_format'], strtotime($tpl['wt_arr'][$selected_day.'_from']));
    						    $to = date($tpl['option_arr']['o_time_format'], strtotime($tpl['wt_arr'][$selected_day.'_to']));
    						    $lunch_from = date($tpl['option_arr']['o_time_format'], strtotime($tpl['wt_arr'][$selected_day.'_lunch_from']));
    						    $lunch_to = date($tpl['option_arr']['o_time_format'], strtotime($tpl['wt_arr'][$selected_day.'_lunch_to']));
    						    $lunch_length = (strtotime($tpl['wt_arr'][$selected_day.'_lunch_to']) - strtotime($tpl['wt_arr'][$selected_day.'_lunch_from'])) / 60;
    						    $price = $tpl['wt_arr'][$selected_day.'_price'];
    						    $limit = $tpl['wt_arr'][$selected_day.'_limit'];
    						    $length = $tpl['wt_arr'][$selected_day.'_length'];
    						    $slots = $tpl['wt_arr'][$selected_day.'_slots'];
    						    $people = $tpl['wt_arr'][$selected_day.'_people'];
    						    
    						    $checked = NULL;
    						    $dayoff_class = NULL;
    						    $lunch_break = NULL;
    						    $slot_arr = array();
    						    $after_midnight = false;
    						    if($lunch_length > 0)
    						    {
    						        $lunch_break = ' checked="checked"';
    						    }
    						    if ($tpl['wt_arr'][$selected_day.'_dayoff'] == 'T')
    						    {
    						        $checked = ' checked="checked"';
    						        $dayoff_class = ' pjTsbDayOff';
    						    }
    						    
    						    $step = $tpl['wt_arr'][$selected_day . '_length'] * 60;
    						    $start_ts = strtotime($tpl['wt_arr'][$selected_day . '_from']);
    						    $lunch_from_ts = strtotime($tpl['wt_arr'][$selected_day.'_lunch_from']);
    						    if(date('H:i', $lunch_from_ts) == '00:00')
    						    {
    						        $lunch_from_ts = $lunch_from_ts + (24 * 60 * 60);
    						    }
    						    $i = $start_ts;
    						    for($n = 1; $n <= $slots; $n++)
    						    {
    						        if($i+$step > strtotime(date('Y-m-d 00:00:00', $start_ts + (24 * 60 * 60))))
    						        {
    						            $after_midnight = true;
    						        }
    						        $slot_arr[$i + $step] = date($tpl['option_arr']['o_time_format'], $i) . ' - ' . date($tpl['option_arr']['o_time_format'], $i + $step);
    						        $to = date($tpl['option_arr']['o_time_format'], $i + $step);
    						        if($i + $step == $lunch_from_ts)
    						        {
    						            $i = strtotime($tpl['wt_arr'][$selected_day.'_lunch_to']);
    						        }else{
    						            $i = $i + $step;
    						        }
    						    }
    						    
    						    $i = $start_ts;
    						    for($n = 1; $n <= $slots; $n++)
    						    {
    						        $lunch_from_arr[date('H:i', ($i + $step))] = date($tpl['option_arr']['o_time_format'], $i + $step);
    						        $i = $i + $step;
    						    }
    						    if($show_form == false)
    						    {
            						?>
            						<div class="ibox-content ibox-heading">
                                        <h3><?php echo @$titles['AT04'];?></h3>
        
                                        <small><?php echo @$bodies['AT04'];?></small>
                                    </div>
            						<div class="table-responsive table-responsive-secondary">
                                		<table class="table table-striped table-hover">
                                			<thead>
                                				<tr>
                                					<th><?php __('time_day');?></th>
                                					<th><?php __('time_working_hours');?></th>
                                					<th><?php __('time_tbl_break');?></th>
                                					<th>&nbsp;</th>
                                				</tr>
                                			</thead>
                                			<tbody>
                                				<?php
                                				foreach($w_days as $k => $v)
                                				{
                                				    $_from = date($tpl['option_arr']['o_time_format'], strtotime($tpl['wt_arr'][$k.'_from']));
                                				    $_to = date($tpl['option_arr']['o_time_format'], strtotime($tpl['wt_arr'][$k.'_to']));
                                				    $show_lunch_break = true;
                                				    if($tpl['wt_arr'][$k.'_lunch_from'] == $tpl['wt_arr'][$k.'_lunch_to'])
                                				    {
                                				        $show_lunch_break = false;
                                				    }
                                				    $is_dayoff = false;
                                				    if($tpl['wt_arr'][$k.'_dayoff'] == 'T')
                                				    {
                                				        $is_dayoff = true;
                                				    }
                                				    ?>
                                				    <tr>
                                				    	<td><?php echo $v;?></td>
                                				    	<?php
                                				    	if($is_dayoff == false)
                                				    	{
                                    				    	?>
                                    				    	<td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex&amp;id=<?php echo $foreign_id?>&amp;day=<?php echo $k;?>"><span class="label label-primary"><?php echo $_from;?> - <?php echo $_to;?></span></a></td>
                                    				    	<?php
                                				    	}else{
                                				    	    ?>
                                    				    	<td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex&amp;id=<?php echo $foreign_id?>&amp;day=<?php echo $k;?>"><span class="label label-secondary"><?php __('time_tbl_dayoff');?></span></a></td>
                                    				    	<?php
                                				    	}
                                				    	if($show_lunch_break == true)
                                				    	{
                                				    	    $_lunch_from = date($tpl['option_arr']['o_time_format'], strtotime($tpl['wt_arr'][$k.'_lunch_from']));
                                				    	    $_lunch_to = date($tpl['option_arr']['o_time_format'], strtotime($tpl['wt_arr'][$k.'_lunch_to']));
                                    				    	?>
                                    				    	<td><span class="label label-secondary"><?php echo $_lunch_from;?> - <?php echo $_lunch_to;?></span></td>
                                    				    	<?php
                                				    	}else{
                                				    	    ?>
                                    				    	<td><span class="label label-secondary"><?php __('lunch_off')?></span></td>
                                    				    	<?php
                                				    	}
                                				    	?>
                            				    	    <td><a class="btn btn-primary btn-outline btn-sm m-l-xs" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex&amp;id=<?php echo $foreign_id?>&amp;day=<?php echo $k;?>"><i class="fa fa-clock-o"></i></a></td>
                                				    </tr>
                                				    <?php
                                				}
                                				?>
                                			</tbody>
                                		</table>
                                	</div><!-- /.table-responsive table-responsive-secondary -->
                                	<?php
    						    }else{
                                	?>
            						<form id="frmPriceDefault" action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex&amp;id=<?php echo $foreign_id?>&amp;day=<?php echo $selected_day?>" method="post">
        								<input type="hidden" name="working_time" value="1" />
        								<input type="hidden" name="csrf_token" value="<?php echo pjAppController::getCsrfToken(); ?>" />
        								<input type="hidden" name="id" value="<?php echo (int) $tpl['wt_arr']['id']; ?>" />
        								<input type="hidden" name="foreign_id" value="<?php echo $foreign_id; ?>" />
        								<input type="hidden" name="day_of_week" value="<?php echo $selected_day; ?>" />
                                        
                                        <h2><?php __('lblSetWTimeFor')?> <?php echo $w_days[$selected_day];?></h2>
                                        
                                    	<div class="hr-line-dashed"></div>
                                    	
                                    	<div class="row">
                                    		<div class="col-md-3 col-sm-6 col-xs-12">
                                    			<div class="form-group">
                                					<label class="control-label"><?php __('lblSetAsDayOff');?></label>
                                					
                                					<div class="switch m-t-xs">
                            							<div class="onoffswitch onoffswitch-yn">
                            								<input class="onoffswitch-checkbox tsIsDayOff" id="<?php echo $selected_day?>_dayoff" name="<?php echo $selected_day?>_dayoff" type="checkbox" value="T"<?php echo $checked;?>>
                            								<label class="onoffswitch-label" for="<?php echo $selected_day?>_dayoff">
                            									<span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T'); ?>" data-off="<?php __('_yesno_ARRAY_F'); ?>"></span>
                            									<span class="onoffswitch-switch"></span>
                            								</label>
                            							</div>
                            						</div>
                                				</div><!-- /.form-group -->
                                    		</div><!-- /.col-md-3 col-sm-6 col-xs-12 -->
                                    		
                                    		<div class="col-md-3 col-sm-6 col-xs-12 pjTsWTimeWrapper<?php echo $dayoff_class;?>">
                                				<div class="form-group">
                                                    <label class="control-label"><?php __('lblStartTime');?></label>
                    
                                                    <div class="input-group clockpicker">
                                                        <input type="text" name="<?php echo $selected_day?>_from" value="<?php echo $from;?>" class="form-control pjTsStartTime required" readonly>
                    
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                </div><!-- /.form-group -->
                                			</div><!-- /.col-md-3 col-sm-6 col-xs-12 -->
                                			<div class="col-md-3 col-sm-6 col-xs-12 pjTsWTimeWrapper<?php echo $dayoff_class;?>">
                                                <div class="form-group">
                                                    <label class="control-label"><?php __('time_tbl_length');?></label>
                                                    
                                                    <select name="<?php echo $selected_day; ?>_length" id="slot_length" class="form-control">
                                    				<?php
                                    				foreach ($time_slot_length as $sk => $sv)
                                    				{
                                    					?><option value="<?php echo $sk; ?>"<?php echo $length == $sk ? ' selected="selected"' : NULL; ?>><?php echo $sv; ?></option><?php
                                    				}
                                    				?>
                                    				</select>
                                                </div><!-- /.form-group -->
                                            </div><!-- /.col-md-3 col-sm-6 col-xs-12 -->
                                            <div class="col-md-3 col-sm-6 col-xs-12 pjTsWTimeWrapper<?php echo $dayoff_class;?>">
                                                <div class="form-group">
                                                    <label class="control-label"><?php __('lblNumberOfSlots');?></label>
                                                    
                                                    <select name="<?php echo $selected_day; ?>_slots" id="number_of_slots" class="form-control">
                                    				<?php
                                    				foreach (range(1, 144) as $number) 
                                    				{
                                        
                                    					?><option value="<?php echo $number; ?>"<?php echo $slots == $number ? ' selected="selected"' : NULL; ?>><?php echo $number; ?></option><?php
                                    				}
                                    				?>
                                    				</select>
                                                </div><!-- /.form-group -->
                                            </div><!-- /.col-md-3 col-sm-6 col-xs-12 -->
                                    	</div><!-- /.row -->
                                    	
                                    	<div class="pjTsWTimeWrapper<?php echo $dayoff_class;?>">
                                    		<div id="tsSlotBox">
                                    			<div class="hr-line-dashed"></div>
                                    			<div class="row">
                                    				<div class="col-md-3 col-sm-6 col-xs-12">
                                        				<div class="form-group">
                                        					<label class="control-label"><?php __('time_tbl_break');?></label>
                                        					
                                        					<div class="switch m-t-xs">
                                    							<div class="onoffswitch onoffswitch-yn">
                                    								<input class="onoffswitch-checkbox pjTsLunchBreak" id="lunch_break" name="lunch_break" type="checkbox" value="T"<?php echo $lunch_break;?>>
                                    								<label class="onoffswitch-label" for="lunch_break">
                                    									<span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T'); ?>" data-off="<?php __('_yesno_ARRAY_F'); ?>"></span>
                                    									<span class="onoffswitch-switch"></span>
                                    								</label>
                                    							</div>
                                    						</div>
                                        				</div><!-- /.form-group -->
                                    				</div><!-- /.col-md-3 col-sm-6 col-xs-12 -->
                                    				
                                    				<div id="tsLunchBreakContainer" style="display:<?php echo $lunch_length == 0 ? 'none' : '';?>;">
                                        				<div class="col-md-3 col-sm-6 col-xs-12">
                                							<div class="form-group">
                                                                <label class="control-label"><?php __('lblLunchStart');?></label>
                                                                
                                                                <select name="<?php echo $selected_day; ?>_lunch_from" id="lunch_from" class="form-control">
                                            					<?php
                                            					foreach($lunch_from_arr as $k => $v)
                                            					{
                                            						?><option value="<?php echo $v; ?>"<?php echo $lunch_from == $v ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
                                            					}
                                            					?>
                                            					</select>
                                                            </div><!-- /.form-group -->
                                						</div>
                                						<div class="col-md-3 col-sm-6 col-xs-12">
                                							<div class="form-group">
                                                                <label class="control-label"><?php __('lblLunchDuration');?></label>
                                                                
                                                                <input type="text" name="lunch_length" value="<?php echo $lunch_length;?>" class="form-control field-int"/>
                                                                <small><?php __('lblMinutes');?></small>
                                                            </div><!-- /.form-group -->
                                						</div>
                                        			</div>
                                        			<div class="col-md-3 col-sm-6 col-xs-12">
                                        				<div class="form-group">
                                                            <label class="control-label"><?php __('lblEndTime');?></label>
                            
                                                            <input type="text" name="<?php echo $selected_day?>_to" value="<?php echo $to;?>" class="form-control" readonly>
                                                        </div><!-- /.form-group -->
                                        			</div><!-- /.col-md-3 col-sm-6 col-xs-12 -->
                                    			</div><!-- /.row -->
                                    			<div class="hr-line-dashed"></div>
                                    			<div id="tsSlotsContainer" class="row">
                                    				<div class="col-md-12 col-sm-12 col-xs-12">
                                    					<h3><?php __('lblTimeSlotsNote');?></h3><br/>
                                    					<?php
                                    					$to = null;
                                    					foreach($slot_arr as $k => $v)
                                    					{
                                    					    ?><span class="label label-primary"><?php echo $v;?></span><?php
                                    					    $to = date($tpl['option_arr']['o_time_format'], $k);
                                    					    if($to == $lunch_from && $lunch_from != $lunch_to)
                                    					    {
                                    					        ?><span class="label label-seconday"><?php echo $lunch_from;?> - <?php echo $lunch_to;?></span><?php
                                    					    }
                                    					}
                                    					if($after_midnight == true)
                                    					{
                                    					    $message =  __('lblSlotLimitation', true);
                                    					    $message = str_replace("{AFTER}", date($tpl['option_arr']['o_time_format'], strtotime(date('Y-m-d') . ' 00:00:00')), $message);
                                    					    ?><div id="after_midnight" style="display:none;"><?php echo $message;?></div><?php
    						                            }
                                    					?>
                                    				</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
                                    				<div id="pjTsEndTimeValue" style="display:none;" data-wday="<?php echo $selected_day;?>"><?php echo $to;?></div>
                                    			</div><!-- /.row -->
                                    		</div><!-- /#tsSlotBox -->
                                    		
                                    		<div class="hr-line-dashed"></div>
                                    	
                                        	<div class="row">
                                    			<div class="col-md-3 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php __('time_limit');?></label>
        
                                                        <input type="text" name="<?php echo $selected_day; ?>_limit" id="<?php echo $selected_day; ?>_limit" maxlength="5" class="form-control field-int tsWorkingDay_<?php echo $selected_day;?>" value="<?php echo $limit; ?>"/>
                                                        <small><?php __('time_limit_note');?></small>
                                                    </div><!-- /.form-group -->
                                                </div><!-- /.col-md-3 col-sm-6 col-xs-12 -->
                                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    				<div class="form-group">
                                                        <label class="control-label"><?php __('time_people_per_slot');?></label>
                        
                                                        <input type="text" name="<?php echo $selected_day; ?>_people" id="<?php echo $selected_day; ?>_people" maxlength="5" class="form-control field-int tsWorkingDay_<?php echo $selected_day;?>" value="<?php echo $people;?>"/>
                                                        <small><?php __('time_people_per_slot_note');?></small>
                                                    </div><!-- /.form-group -->
                                    			</div><!-- /.col-md-3 col-sm-6 col-xs-12 -->
                                    			<div class="col-md-3 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label"><?php __('time_tbl_price');?></label>
        
                                                        <div class="input-group">
                                                            <input type="text" name="<?php echo $selected_day; ?>_price" id="<?php echo $selected_day; ?>_price" class="form-control number tsPriceField" value="<?php echo $price; ?>"/>
        
                                                            <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span>
                                                        </div>
        
                                                        <label class="normal-label m-t-xs">
                                                        	<a href="#" data-day="<?php echo $selected_day; ?>" data-fid="<?php echo $foreign_id;?>" class="day-price"><?php __('time_set_custom_price'); ?></a>
                                                        </label>
                                                    </div><!-- /.form-group -->
                                                </div><!-- /.col-md-3 col-sm-6 col-xs-12 -->
                                                
                                    		</div><!-- /.row -->
                                    	</div><!-- /#pjTsWTimeWrapper -->
                                    	
                                    	<div class="hr-line-dashed"></div>
                                        <div class="clearfix">
                        					<button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in">
                        						<span class="ladda-label"><?php __('btnSave', false, true); ?></span>
                        						<?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                        					</button>
                        					
                        					<button type="button" class="btn btn-white btn-lg pull-right" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminTime&action=pjActionIndex';"><?php __('btnCancel'); ?></button>
                        				</div>
                                   	</form>
                                   	<?php
    						    }
    						}
                            ?>
    					</div><!-- /.panel-body-inner -->
    				</div><!-- /.panel-body -->
    			</div><!-- /.tab-pane active -->
    		</div><!-- /.tab-content -->
    	</div><!-- /.tabs-left -->
    </div><!-- /.tabs-container -->
</div><!-- /.wrapper wrapper-content animated fadeInRight -->

<div class="modal fade in" id="setCustomPriceModal" tabindex="-1" role="dialog" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                <h3 class="modal-title" id="myModalLabel2"><?php __('time_dp_title'); ?></h3>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
            	<button type="button" id="pjTsSaveCustomPrices" class="btn btn-primary" data-fid="<?php echo $foreign_id;?>" data-wday="<?php echo $selected_day;?>"><?php __('button_ARRAY_save');?></button>
            	<button type="button" id="pjTsDeleteCustomPrices" class="btn btn-danger" data-fid="<?php echo $foreign_id;?>" data-wday="<?php echo $selected_day;?>"><?php __('button_ARRAY_erase_all');?></button>
        		<button type="button" class="btn btn-default" data-dismiss="modal"><?php __('btnCancel');?></button>
      		</div>
        </div>
    </div>
</div>

<?php
$show_period = 'false';
if((strpos($tpl['option_arr']['o_time_format'], 'a') > -1 || strpos($tpl['option_arr']['o_time_format'], 'A') > -1))
{
    $show_period = 'true';
}
?>
<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.showperiod = <?php echo $show_period; ?>;
myLabel.btnOK = <?php x__encode('gridBtnOk'); ?>;
myLabel.restriction_per_slot_msg = <?php x__encode('restriction_per_slot_msg'); ?>;
myLabel.price_limit_msg = <?php x__encode('lblPriceLimitMsg'); ?>;
</script>