<td>
	<strong class="appointment-management-date"><?php echo date('d', strtotime($iso_date));?></strong>
	<?php
	if(!empty($item))
	{
	    if(!empty($item['blocks']))
	    {
	        foreach($item['blocks'] as $block)
	        {
	            $start_ts = strtotime($iso_date . ' ' . $block['start_time']);
	            $end_ts = strtotime($iso_date . ' ' . $block['end_time']);
	            if($block['status'] == 'avail')
	            {
	                $style = "background-color:#5ac5b6; border-color:#5ac5b6; color: #fff;";
	                if($start_ts <= time())
	                {
	                    $style = "background-color:#f3f3f4; border-color:#f3f3f4; color: #888888;";
	                }
    	            ?>
    	            <div style="<?php echo $style;?>"><?php echo date($tpl['option_arr']['o_time_format'], $start_ts);?> - <?php echo date($tpl['option_arr']['o_time_format'], $end_ts);?></div>
    	            <?php
	            }else if($block['status'] == 'pending'){
	                $style = "background-color:#fbc994; border-color:#fbc994; color: #fff;";
	                if($start_ts <= time())
	                {
	                    $style = "background-color:#f3f3f4; border-color:#f3f3f4; color: #888888;";
	                }
	                ?>
	                <div style="<?php echo $style;?>">
	                	<?php echo date($tpl['option_arr']['o_time_format'], $start_ts);?> - <?php echo date($tpl['option_arr']['o_time_format'], $end_ts);?>
	                	<br/>
	                	<?php echo pjSanitize::html($block['data']['customer_name']);?>
	                </div>
    	            <?php
	            }else if($block['status'] == 'confirmed'){
	                $style = "background-color:#ed5565; border-color:#ed5565; color: #fff;";
	                if($start_ts <= time())
	                {
	                    $style = "background-color:#f3f3f4; border-color:#f3f3f4; color: #888888;";
	                }
	                ?>
	                <div style="<?php echo $style;?>">
	                	<?php echo date($tpl['option_arr']['o_time_format'], $start_ts);?> - <?php echo date($tpl['option_arr']['o_time_format'], $end_ts);?>
	                	<br/>
	                	<?php echo pjSanitize::html($block['data']['customer_name']);?>
	                </div>
    	            <?php
	            }else if($block['status'] == 'break'){
	                $style = "background-color:#f3f3f4; border-color:#f3f3f4; color: #888888;";
	                ?>
	                <div style="<?php echo $style;?>">
	                	<?php echo date($tpl['option_arr']['o_time_format'], $start_ts);?> - <?php echo date($tpl['option_arr']['o_time_format'], $end_ts);?>
	                	<br/>
	                	<?php __('time_tbl_break');?>
	                </div>
    	            <?php
	            }
	        }
	    }else{
	        $style = "background-color:#5ac5b6; border-color:#5ac5b6; color: #fff;";
	        if($item['start_ts'] <= time())
	        {
	            $style = "background-color:#f3f3f4; border-color:#f3f3f4; color: #888888;";
	        }
	        ?>
	        <div style="<?php echo $style;?>"><?php echo date($tpl['option_arr']['o_time_format'], $item['start_ts'])?> - <?php echo date($tpl['option_arr']['o_time_format'], $item['end_ts'])?></div>
            <?php
	        
	    }
	}
	?>
</td>