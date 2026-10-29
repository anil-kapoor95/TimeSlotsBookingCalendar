<input type="hidden" name="item_add" value="1" />
<input type="hidden" name="csrf_token" value="<?php echo pjAppController::getCsrfToken(); ?>" />
<input type="hidden" name="booking_id" value="<?php echo $controller->_get->check('id') ? $controller->_get->toInt('id') : NULL; ?>" />
<input type="hidden" name="hash" value="<?php echo $controller->_get->check('hash') ? $controller->_get->toString('hash') : NULL; ?>" />
<input type="hidden" name="calendar_id" value="<?php echo $controller->_get->check('calendar_id') ? $controller->_get->toInt('calendar_id') : NULL; ?>" />

<div class="container-fluid">
    <div class="row m-t-sm">
    	<div class="col-sm-6">
            <div class="form-group">
                <label class="control-label"><?php __('booking_date'); ?></label>
            
                <div class="input-group date">
                    <input type="text" name="date" class="form-control required" readonly="readonly" value="<?php echo $controller->_get->check('booking_date') && !$controller->_get->isEmpty('booking_date') ? $controller->_get->toString('booking_date') : date($tpl['option_arr']['o_date_format']); ?>" />
            
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div><!-- /.form-group -->
        </div><!-- /.col-sm-4 -->
    </div>
</div>

<div class="item_details" style="display: none">
	
</div>