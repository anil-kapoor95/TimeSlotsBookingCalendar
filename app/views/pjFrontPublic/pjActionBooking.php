<?php
if (isset($tpl['status']) && $tpl['status'] == "OK")
{
    $status = __('front_booking_status', true);
    $tsb_step = 3;
    include PJ_VIEWS_PATH . 'pjFrontPublic/elements/steps.php';
	?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<?php __('front_system_msg'); ?>
		</div><!-- /.panel-heading -->
		
		<?php
		if (isset($tpl['booking_arr']))
		{
		    
	        if(isset($tpl['params']['plugin']) && !empty($tpl['params']['plugin']))
	        {
	            $payment_messages = __('payment_plugin_messages');
	            ?>
	            <div class="panel-body">
	            <p class="text-center"><?php echo isset($payment_messages[$tpl['booking_arr']['payment_method']]) ? $payment_messages[$tpl['booking_arr']['payment_method']]: ''; ?></p>
	            <?php
                if (pjObject::getPlugin($tpl['params']['plugin']) !== NULL)
                {
                    $controller->requestAction(array('controller' => $tpl['params']['plugin'], 'action' => 'pjActionForm', 'params' => $tpl['params']));
                }
                ?>
                </div>
                <?php
	        }else{
	            ?>
	            <div class="panel-body">
	            	<p class="alert alert-success">
    	            <?php
    	            switch ($tpl['booking_arr']['payment_method'])
    	            {
    	                case 'bank':
    	                    echo $status[1] . '<br/>' .  pjSanitize::html($tpl['bank_account']);
                        	break;
                        case 'creditcard':
                        case 'cash':
                        default:
                            echo $status[1];
    						break;
                    }
                    ?>
                    </p>
                </div>
                <div class="panel-footer">
					<a href="#" class="btn btn-primary pull-left pjTsSelectorCalendar"><?php __('front_start_over', false, true); ?></a>
				</div><!-- /.panel-footer -->
                <?php
	        }
		    
		}else {
			?>
			<div class="panel-body">
				<?php 
				echo $status[4];
				?>
			</div>
			<?php
		}
		?>
	</div>
	<?php
} elseif (isset($tpl['status']) && $tpl['status'] == 'ERR') {
	?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<?php __('front_system_msg'); ?>
		</div><!-- /.panel-heading -->
		<div class="panel-body">
			<?php __('front_booking_na'); ?>
		</div>
		<div class="panel-footer">
			<a href="#" class="btn btn-primary pull-left pjTsSelectorPreview"><?php __('front_return_back', false, true); ?></a>
			<a href="#" class="btn btn-primary pull-right pjTsSelectorCalendar"><?php __('front_start_over', false, true); ?></a>
		</div><!-- /.panel-footer -->
	</div>
	<?php
}
?>