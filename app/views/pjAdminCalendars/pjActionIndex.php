<?php
$titles = __('error_titles', true);
$bodies = __('error_bodies', true);
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-10">
                <h2><?php echo @$titles['AC10'];?></h2>
            </div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php echo @$bodies['AC10'];?></p>
    </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-12">
    	<?php
    	$error_code = $controller->_get->toString('err');
    	if (!empty($error_code))
        {
            if(in_array($error_code, array('AC01', 'AC03')))
            {
                ?>
    			<div class="alert alert-success">
    				<i class="fa fa-check m-r-xs"></i>
    				<strong><?php echo @$titles[$error_code]; ?></strong>
    				<?php echo @$bodies[$error_code]; ?>
    			</div>
    			<?php
            }else if(in_array($error_code, array('AC04', 'AC08', 'AC13'))){
                ?>
    			<div class="alert alert-danger">
    				<i class="fa fa-exclamation-triangle m-r-xs"></i>
    				<strong><?php echo @$titles[$error_code]; ?></strong>
    				<?php echo @$bodies[$error_code]; ?>
    			</div>
    			<?php
            }
        }
        ?>
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-4">
                        <?php 
                        if ($tpl['has_create'])
                        {
                        	?>
                        	<div class="form-group">
                            	<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCalendars&amp;action=pjActionCreate" class="btn btn-primary"><i class="fa fa-plus"></i> <?php __('btnAddCalendar');?></a>
                            </div>
                            <?php 
                        }
                        ?>
                    </div>

                    <div class="col-lg-4 col-md-9 col-sm-8">
                        <form action="" method="get" class="form-horizontal frm-filter">
							<div class="input-group">
								<input type="text" name="q" placeholder="<?php __('btnSearch', false, true); ?>" class="form-control">
								<div class="input-group-btn">
									<button class="btn btn-primary" type="submit">
										<i class="fa fa-search"></i>
									</button>
								</div>
							</div>
						</form>
                    </div><!-- /.col-lg-6 -->
                </div><!-- /.row -->
                <div id="grid"></div>
            </div><!-- /.ibox-content -->
        </div><!-- /.ibox float-e-margins -->
    </div><!-- /.col-lg-12 -->
</div><!-- /.row wrapper wrapper-content animated fadeInRight -->

<script type="text/javascript">
var pjGrid = pjGrid || {};
pjGrid.currentCalendarId = <?php echo (int) $controller->getCalendarId(); ?>;
pjGrid.queryString = "";
<?php
if ($controller->_get->toInt('user_id') > 0)
{
    ?>pjGrid.queryString += "&user_id=<?php echo $controller->_get->toInt('user_id'); ?>";<?php
}
?>
var myLabel = myLabel || {};
myLabel.title = <?php x__encode('calendar_title'); ?>;
myLabel.name = <?php x__encode('lblName'); ?>;
myLabel.email = <?php x__encode('email'); ?>;
myLabel.delete_selected = <?php x__encode('delete_selected'); ?>;
myLabel.delete_confirmation = <?php x__encode('delete_confirmation'); ?>;
myLabel.has_update = <?php echo (int) $tpl['has_update']; ?>;
myLabel.has_delete = <?php echo (int) $tpl['has_delete']; ?>;
myLabel.has_working_time = <?php echo (int) $tpl['has_working_time']; ?>;
myLabel.has_delete_bulk = <?php echo (int) $tpl['has_delete_bulk']; ?>;
myLabel.has_working_time = <?php echo (int) $tpl['has_working_time']; ?>;
myLabel.has_booking_options = <?php echo (int) $tpl['has_booking_options']; ?>;
</script>