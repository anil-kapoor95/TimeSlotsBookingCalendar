<?php
$post = $controller->_post->raw();
$selected_day = $post['day_of_week'];
$from = strtotime($post[$selected_day . '_from']);
$lunch_from_ts = isset($post[$selected_day . '_lunch_from']) ? strtotime($post[$selected_day . '_lunch_from']) : $from;
$lunch_length = $post['lunch_length'] != '' ? intval($post['lunch_length']) : 0;
$lunch_break = NULL;
if($lunch_length > 0)
{
    $lunch_break = ' checked="checked"';
}
if($lunch_from_ts < $from)
{
	$lunch_from_ts = $lunch_from_ts + (24 * 60 * 60);
}
$lunch_to_ts = $lunch_from_ts + ($lunch_length * 60);

$lunch_from = date($tpl['option_arr']['o_time_format'], $lunch_from_ts);
$lunch_to = date($tpl['option_arr']['o_time_format'], $lunch_to_ts);

$lunch_from_arr = array();
$step = $post[$selected_day . '_length'] * 60;
$slots = $post[$selected_day . '_slots'];
$i = $from;
for($n = 1; $n <= $slots; $n++)
{
	$lunch_from_arr[date('H:i', ($i + $step))] = date($tpl['option_arr']['o_time_format'], $i + $step);
	$i = $i + $step;
}

$after_midnight = false;
$slot_arr = array();
$i = $from;

for($n = 1; $n <= $slots; $n++)
{
	if($i+$step > strtotime(date('Y-m-d 00:00:00', $from + (24 * 60 * 60))))
	{
		$after_midnight = true;
	}
	$slot_arr[$i + $step] = date($tpl['option_arr']['o_time_format'], $i) . ' - ' . date($tpl['option_arr']['o_time_format'], $i + $step);
	if($i + $step == $lunch_from_ts)
	{
	    $i = $lunch_to_ts;
	}else{
		$i = $i + $step;
	}
}
?>
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