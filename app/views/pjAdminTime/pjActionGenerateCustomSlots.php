<?php
$post = $controller->_post->raw();
$date = pjDateTime::formatDate($post['date'], $tpl['option_arr']['o_date_format']);
$step = $post['slot_length'] * 60;

$slots = $post['slots'];
$start_ts = strtotime($date . ' ' . $post['start']);
$i = $start_ts;
$lunch_from_ts = strtotime($date . ' ' . $post['lunch_from']);
$lunch_from = date($tpl['option_arr']['o_time_format'], $lunch_from_ts);
$lunch_length = $post['lunch_length'] != '' ? intval($post['lunch_length']) : 0;
if($lunch_from_ts < $i)
{
	$lunch_from_ts = $lunch_from_ts + (24 * 60 * 60);
}
$lunch_to_ts = $lunch_from_ts + ($lunch_length * 60);
$lunch_to = date($tpl['option_arr']['o_time_format'], $lunch_to_ts);

$slot_arr = array();
$after_midnight = false;
for($n = 1; $n <= $slots; $n++)
{
	if($i+$step > strtotime(date('Y-m-d 00:00:00', $start_ts + (24 * 60 * 60))))
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