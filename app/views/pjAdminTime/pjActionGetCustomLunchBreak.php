<?php
$post = $controller->_post->raw();
$date = pjDateTime::formatDate($post['date'], $tpl['option_arr']['o_date_format']);
$step = $post['slot_length'] * 60;

$slots = $post['slots'];

$after_midnight = false;

$lunch_from_arr = array();
$slot_arr = array();
$from = strtotime($date . ' ' . $post['start']);
$i = $from;
for($n = 1; $n <= $slots; $n++)
{
	if($i+$step > strtotime(date('Y-m-d 00:00:00', $from + (24 * 60 * 60))))
	{
		$after_midnight = true;
	}
	$lunch_from_arr[date('H:i', ($i + $step))] = date($tpl['option_arr']['o_time_format'], $i + $step);
	$slot_arr[$i + $step] = date($tpl['option_arr']['o_time_format'], $i) . ' - ' . date($tpl['option_arr']['o_time_format'], $i + $step);
	$i = $i + $step;
}
$lunch_length = 0;

$lunch_break = NULL;
$lunch_from = NULL;
if(isset($tpl['arr']))
{
    $lunch_length = (strtotime($tpl['arr']['end_lunch']) - strtotime($tpl['arr']['start_lunch'])) / 60;
    if($lunch_length > 0)
    {
        $lunch_break = ' checked="checked"';
    }
    $lunch_from = date($tpl['option_arr']['o_time_format'], strtotime($tpl['arr']['start_lunch']));
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
                
                <select name="lunch_from" id="lunch_from" class="form-control">
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
	
</div><!-- /.row -->

<div class="hr-line-dashed"></div>

<div id="tsCustomSlotsContainer" class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<h3><?php __('lblTimeSlotsNote');?></h3><br/>
		<?php
		$to = null;
		foreach($slot_arr as $k => $v)
		{
		    ?><span class="label label-primary"><?php echo $v;?></span><?php
		    $to = date($tpl['option_arr']['o_time_format'], $k);
		}
		if($after_midnight == true)
		{
		    $message =  __('lblSlotLimitation', true);
		    $message = str_replace("{AFTER}", date($tpl['option_arr']['o_time_format'], strtotime(date('Y-m-d') . ' 00:00:00')), $message);
		    ?><div id="after_midnight" style="display:none;"><?php echo $message;?></div><?php
        }
		?>
	</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
	<div id="pjTsEndTimeValue" style="display:none;"><?php echo $to;?></div>
</div><!-- /.row -->