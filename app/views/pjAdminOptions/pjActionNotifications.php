<?php
$titles = __('error_titles', true);
$bodies = __('error_bodies', true);

include PJ_VIEWS_PATH . 'pjLayouts/elements/update-calendar-title.php';
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="tabs-container">
    	<div class="tabs-left">
    		<?php include PJ_VIEWS_PATH . 'pjLayouts/elements/tab-options.php'; ?>
    		<div class="tab-content">
    			<div class="tab-pane active">
    				<div class="panel-body">
    					
    					<div id="boxNotificationsWrapper" class="panel-body-inner">
    						<input type="hidden" name="calendar_id" value="<?php echo $controller->_get->toInt('id');?>"/>
							<div class="ibox-content ibox-heading">
                                <h3><?php __('infoNotificationsTitle');?></h3>

                                <small><?php __('infoNotificationsDesc');?></small>
                            </div>
                            <div class="ibox-content">
                            	<div class="row">
                            		<div class="col-lg-3 col-sm-5">
                            			<div class="m-b-sm">
                    						<div class="row">
                    							<div class="col-sm-12">
													<h3><?php __('notifications_recipient'); ?></h3>
                    							</div>
                    						</div><!-- /.row -->
                    					</div><!-- /.m-b-sm -->
                    					<div class="form-group">
                    						<div class="radio radio-primary">
                    							<input type="radio" id="recipient_client" name="recipient" value="client"<?php echo !isset($tpl['query']['recipient']) || $tpl['query']['recipient'] == 'client' ? ' checked' : NULL; ?>>
                    							<label for="recipient_client"><?php __('recipients_ARRAY_client'); ?></label>
                    						</div>
                    					</div>
                    
                    					<div class="form-group">
                    						<div class="radio radio-primary">
                    							<input type="radio" id="recipient_admin" name="recipient" value="admin"<?php echo isset($tpl['query']['recipient']) && $tpl['query']['recipient'] == 'admin' ? ' checked' : NULL; ?>>
                    							<label for="recipient_admin"><?php __('recipients_ARRAY_admin'); ?></label>
                    						</div>
                    					</div>
                            		</div><!-- /.col-lg-3 col-sm-5 -->
                            		<div class="col-lg-9 col-sm-7" id="boxNotificationsMetaData">
				
									</div><!-- /.col-lg-9 col-sm-7 -->
                            	</div><!-- /.row -->
                            </div><!-- /.ibox-content -->
                            <div class="row">
                            	<div class="col-lg-9" id="boxNotificationsContent"></div>
                            	<div class="col-lg-3">
                        			<div class="ibox float-e-margins settings-box">
                        				<div class="ibox-content ibox-heading">
                        					<h3><?php __('notifications_tokens'); ?></h3>
                        	
                        					<small><?php __('notifications_tokens_note'); ?></small>
                        				</div>
                        				<?php
                        				$isAccountTokens = isset($tpl['query']['variant']) && in_array($tpl['query']['variant'], array('account','forgot'));
                        				?>
                        				<div class="ibox-content">
                        					<div class="notifyTokens bookingTokens" style="display:<?php echo $isAccountTokens ? 'none' : '';?>">
                        						<div><small><strong>{CalendarName}</strong> - The calendar name</small></div>
                        						<div><small><strong>{Name}</strong> - The customer's name</small></div>
                        						<div><small><strong>{Email}</strong> - The customer's email</small></div>
                        						<div><small><strong>{Phone}</strong> - The customer's phone number</small></div>
                        						<div><small><strong>{Notes}</strong> - Additional notes</small></div>
                        						<div><small><strong>{Country}</strong> - The provided country</small></div>
                        						<div><small><strong>{State}</strong> - The provided state</small></div>
                        						<div><small><strong>{City}</strong> - The provided city</small></div>
                        						<div><small><strong>{Zip}</strong> - The provided zip</small></div>
                        						<div><small><strong>{Address1}</strong> - The provided address 1</small></div>
                        						<div><small><strong>{Address2}</strong> - The provided address 2</small></div>
                        						<div><small><strong>{PaymentMethod}</strong> - The payment method</small></div>
                        						<div><small><strong>{VoucherCode}</strong> - The voucher code</small></div>
                        						<div><small><strong>{Discount}</strong> - The discount amount</small></div>
                        						<div><small><strong>{Deposit}</strong> - The deposit amount</small></div>
                        						<div><small><strong>{Tax}</strong> - The tax amount</small></div>
                        						<div><small><strong>{Total}</strong> - The total amount</small></div>
                        						<div><small><strong>{Price}</strong> - The price</small></div>
                        						<div><small><strong>{BookingID}</strong> - The booking ID</small></div>
                        						<div><small><strong>{Timeslots}</strong> - The booking's timeslots</small></div>
                        						<div><small><strong>{CancelURL}</strong> - The cancel URL</small></div>
                        					</div>
                        					<div class="notifyTokens accountTokens" style="display:<?php echo $isAccountTokens ? '' : 'none';?>">
                        						<div><small><strong>{Name}</strong> - The client's name</small></div>
                        						<div><small><strong>{Email}</strong> - The client's email</small></div>
                        						<div><small><strong>{Password}</strong> - The client's password</small></div>
                        						<div><small><strong>{Phone}</strong> - The client's phone number</small></div>
                        						<div><small><strong>{Notes}</strong> - Additional notes</small></div>
                        						<div><small><strong>{Country}</strong> - The provided country</small></div>
                        						<div><small><strong>{State}</strong> - The provided state</small></div>
                        						<div><small><strong>{City}</strong> - The provided city</small></div>
                        						<div><small><strong>{Zip}</strong> - The provided zip</small></div>
                        						<div><small><strong>{Address1}</strong> - The provided address 1</small></div>
                        						<div><small><strong>{Address2}</strong> - The provided address 2</small></div>
                        					</div>
                        				</div>
                        			</div><!-- /.ibox float-e-margins settings-box -->
                        		</div><!-- /.col-lg-3 -->
                            </div><!-- /.row -->
    					</div><!-- /.panel-body-inner -->
    				</div><!-- /.panel-body -->
    			</div><!-- /.tab-pane active -->
    		</div><!-- /.tab-content -->
    	</div><!-- /.tabs-left -->
    </div><!-- /.tabs-container -->
</div><!-- /.wrapper wrapper-content animated fadeInRight -->

<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.isFlagReady = "<?php echo $tpl['is_flag_ready'] ? 1 : 0;?>";
<?php if ($tpl['is_flag_ready']) : ?>
	var pjLocale = pjLocale || {};
	pjLocale.langs = <?php echo $tpl['locale_str']; ?>;
	pjLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
<?php endif; ?>
</script>