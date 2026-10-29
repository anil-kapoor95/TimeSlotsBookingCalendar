<?php
$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
$jqDateFormat = pjUtil::momentJsDateFormat($tpl['option_arr']['o_date_format']);

$time_format = 'HH:mm';
$time_ampm = 0;
if(strpos($tpl['option_arr']['o_time_format'], 'a') > -1)
{
    $time_ampm = 1;
    $time_format = 'hh:mm a';
}
if(strpos($tpl['option_arr']['o_time_format'], 'A') > -1)
{
    $time_ampm = 2;
    $time_format = 'hh:mm A';
}
$months = __('months', true);
ksort($months);
$short_days = __('short_days', true);
?>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-10">
                <h2><?php __('plugin_vouchers_infobox_update_voucher_title') ?></h2>
            </div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('plugin_vouchers_infobox_update_voucher_desc') ?></p>
    </div><!-- /.col-md-12 -->
</div>
<div id="dateTimePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-dateformat="<?php echo pjUtil::toMomemtJS($tpl['option_arr']['o_date_format']); ?>" data-format="<?php echo pjUtil::toMomemtJS($tpl['option_arr']['o_date_format']); ?> <?php echo $time_format;?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjVouchers&amp;action=pjActionUpdate" method="post" id="frmUpdateVoucher" autocomplete="off">
                    <input type="hidden" name="voucher_update" value="1" />
		            <input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />

                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('plugin_vouchers_voucher_code') ?></label>

                                <input type="text" name="code" id="code" class="form-control required" value="<?php echo pjSanitize::clean($tpl['arr']['code'])?>" data-msg-remote="<?php __('plugin_vouchers_voucher_code_exist', false, true); ?>" />
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('plugin_vouchers_discount') ?></label>

                                <div class="input-group group-fa-change" data-currency-sign="<?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?>">
                                    <input type="text" name="discount" id="discount" class="form-control number decimal text-right required" value="<?php echo pjSanitize::html($tpl['arr']['discount']); ?>" data-msg-number="<?php __('plugin_vouchers_enter_valid_number', false, true);?>">

                                    <span class="input-group-addon"><strong><?php echo 'amount' == $tpl['arr']['type']? pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false): '%' ?></strong></span>
                                </div>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('plugin_vouchers_discount_type') ?></label>

                                <?php $voucher_types = __('plugin_vouchers_types', true); ?>
                                <div class="clearfix">
                                    <div class="switch onoffswitch-data onoffswitch-fa-change pull-left">
                                        <div class="onoffswitch">
                                            <input type="checkbox" class="onoffswitch-checkbox" id="switch_type"<?php echo 'amount' == $tpl['arr']['type'] ? ' checked' : NULL; ?>>
                                            <label class="onoffswitch-label" for="switch_type">
                                                <span class="onoffswitch-inner" data-on="<?php echo $voucher_types['amount'] ?>" data-off="<?php echo $voucher_types['percent'] ?>"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div><!-- /.clearfix -->
                                <input type="hidden" name="type" id="type" value="<?php echo $tpl['arr']['type'] ?>">
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->

                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('plugin_vouchers_valid') ?></label>

                                <select name="valid" id="valid" class="form-control select-voucher required">
                                    <option value="">-- <?php __('plugin_base_choose'); ?> --</option>
                                    <?php
                                    foreach (__('plugin_vouchers_valids', true, false) as $k => $v)
                                    {
                                        ?><option value="<?php echo $k; ?>"<?php echo $k == $tpl['arr']['valid'] ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
                                    }
                                    ?>
                                </select>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->

                    <?php
                    $date_from = $date_to = $hour_from = $hour_to = $minute_from = $minute_to = NULL;
                    if (!empty($tpl['arr']['date_from']))
                    {
                        $date_from = date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['date_from']));
                    }
                    if (!empty($tpl['arr']['date_to']))
                    {
                        $date_to = date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['date_to']));
                    }
                    if (!empty($tpl['arr']['time_from']) && strpos($tpl['arr']['time_from'], ":") !== false)
                    {
                        list($hour_from, $minute_from,) = explode(":", $tpl['arr']['time_from']);
                    }
                    if (!empty($tpl['arr']['time_to']) && strpos($tpl['arr']['time_to'], ":") !== false)
                    {
                        list($hour_to, $minute_to,) = explode(":", $tpl['arr']['time_to']);
                    }

                    $time_ampm = 0;
                    $ampm_from = 'am';
                    $ampm_to = 'am';
                    if(strpos($tpl['option_arr']['o_time_format'], 'a') > -1 || strpos($tpl['option_arr']['o_time_format'], 'A') > -1)
                    {
                        if(strpos($tpl['option_arr']['o_time_format'], 'a') > -1)
                        {
                            $time_ampm = 1;
                            $ampm_format = 'a';
                        }
                        else
                        {
                            $time_ampm = 2;
                            $ampm_format = 'A';
                        }

                        $_ts = strtotime($tpl['arr']['time_from']);
                        $hour_from = date('h', $_ts);
                        $minute_from = date('i', $_ts);
                        $ampm_from = date($ampm_format, $_ts);

                        $_ts = strtotime($tpl['arr']['time_to']);
                        $hour_to = date('h', $_ts);
                        $minute_to = date('i', $_ts);
                        $ampm_to = date($ampm_format, $_ts);
                    }
                    ?>
                    <div id="valid_fixed" class="area-fixed valid-box row" style="display: <?php echo $tpl['arr']['valid'] == 'fixed' ? 'block' : 'none'; ?>;">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('plugin_vouchers_date') ?></label>

                                <div class="input-group date"
                                         data-provide="datepicker"
                                         data-date-autoclose="true"
                                         data-date-format="<?php echo $jqDateFormat ?>"
                                         data-date-week-start="<?php echo (int) $tpl['option_arr']['o_week_start'] ?>"
                                    >
                                    <input type="text" name="f_date" id="f_date" class="form-control required" value="<?php echo $date_from; ?>">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('plugin_vouchers_time_from') ?></label>

                                <div class="row">
                                    <?php
                                    $dtHour = pjDateTime::factory()
                                        ->attr('name', 'f_hour_from')
                                        ->attr('id', 'f_hour_from')
                                        ->attr('class', 'form-control')
                                        ->prop('ampm', $time_ampm)
                                        ->prop('selected', $hour_from);
                                    $dtMinute = pjDateTime::factory()
                                        ->attr('name', 'f_minute_from')
                                        ->attr('id', 'f_minute_from')
                                        ->attr('class', 'form-control')
                                        ->prop('step', 5)
                                        ->prop('selected', $minute_from);
                                    $dtAmPm = pjDateTime::factory()
                                        ->attr('name', 'f_ampm_from')
                                        ->attr('id', 'f_ampm_from')
                                        ->attr('class', 'form-control')
                                        ->prop('ampm', $time_ampm)
                                        ->prop('selected', $ampm_from);
                                    ?>
                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtHour->hour(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->

                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtMinute->minute(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->

                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtAmPm->ampm(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->
                                </div>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('plugin_vouchers_time_to') ?></label>

                                <div class="row">
                                    <?php
                                    $dtHour
                                        ->attr('name', 'f_hour_to')
                                        ->attr('id', 'f_hour_to')
                                        ->prop('selected', $hour_to);
                                    $dtMinute
                                        ->attr('name', 'f_minute_to')
                                        ->attr('id', 'f_minute_to')
                                        ->prop('selected', $minute_to);
                                    $dtAmPm
                                        ->attr('name', 'f_ampm_to')
                                        ->attr('id', 'f_ampm_to')
                                        ->prop('selected', $ampm_to);
                                    ?>
                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtHour->hour(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->

                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtMinute->minute(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->

                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtAmPm->ampm(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->

                                    <div class="col-xs-12">
                                        <input type="hidden" id="validate_fixedtime" name="validate_fixedtime" value="" data-msg-validateFixedTime="<?php __('plugin_vouchers_validate_time', false, true);?>"/>
                                    </div>
                                </div>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->

                    <div id="valid_period" class="area-period valid-box row" style="display: <?php echo $tpl['arr']['valid'] == 'period' ? 'block' : 'none'; ?>;">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('plugin_vouchers_date_from') ?></label>

                                <div class="input-group date"
                                         data-provide="datepicker"
                                         data-date-autoclose="true"
                                         data-date-format="<?php echo $jqDateFormat ?>"
                                         data-date-week-start="<?php echo (int) $tpl['option_arr']['o_week_start'] ?>"
                                    >
                                    <input type="text" name="p_date_from" id="p_date_from" class="form-control required" value="<?php echo $date_from; ?>">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('plugin_vouchers_time_from') ?></label>

                                <div class="row">
                                    <?php
                                    $dtHour
                                        ->attr('name', 'p_hour_from')
                                        ->attr('id', 'p_hour_from')
                                        ->prop('selected', $hour_from);
                                    $dtMinute
                                        ->attr('name', 'p_minute_from')
                                        ->attr('id', 'p_minute_from')
                                        ->prop('selected', $minute_from);
                                    $dtAmPm
                                        ->attr('name', 'p_ampm_from')
                                        ->attr('id', 'p_ampm_from')
                                        ->prop('selected', $ampm_from);
                                    ?>
                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtHour->hour(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->

                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtMinute->minute(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->

                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtAmPm->ampm(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->
                                </div>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('plugin_vouchers_date_to') ?></label>

                                <div class="input-group date"
                                         data-provide="datepicker"
                                         data-date-autoclose="true"
                                         data-date-format="<?php echo $jqDateFormat ?>"
                                         data-date-week-start="<?php echo (int) $tpl['option_arr']['o_week_start'] ?>"
                                    >
                                    <input type="text" name="p_date_to" id="p_date_to" class="form-control required" value="<?php echo $date_to; ?>">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('plugin_vouchers_time_to') ?></label>

                                <div class="row">
                                    <?php
                                    $dtHour
                                        ->attr('name', 'p_hour_to')
                                        ->attr('id', 'p_hour_to')
                                        ->prop('selected', $hour_to);
                                    $dtMinute
                                        ->attr('name', 'p_minute_to')
                                        ->attr('id', 'p_minute_to')
                                        ->prop('selected', $minute_to);
                                    $dtAmPm
                                        ->attr('name', 'p_ampm_to')
                                        ->attr('id', 'p_ampm_to')
                                        ->prop('selected', $ampm_to);
                                    ?>
                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtHour->hour(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->

                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtMinute->minute(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->

                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtAmPm->ampm(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->

                                    <div class="col-xs-12">
                                        <input type="hidden" id="validate_datetime" name="validate_datetime" value="1" data-msg-remote="<?php __('plugin_vouchers_validate_datetime', false, true);?>"/>
                                    </div>
                                </div>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->

                    <div id="valid_recurring" class="area-recurring valid-box row" style="display: <?php echo $tpl['arr']['valid'] == 'recurring' ? 'block' : 'none'; ?>;">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('plugin_vouchers_every') ?></label>

                                <select name="r_every" id="r_every" class="form-control">
                                    <?php
                                    $days = __('plugin_vouchers_days', true, false);
                                    foreach (pjUtil::getWeekdays() as $v)
                                    {
                                        ?><option value="<?php echo $v; ?>"<?php echo $tpl['arr']['every'] == $v ? ' selected="selected"' : null;?>><?php echo $days[$v]; ?></option><?php
                                    }
                                    ?>
                                </select>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('plugin_vouchers_start_time') ?></label>

                                <div class="row">
                                    <?php
                                    $dtHour
                                        ->attr('name', 'r_hour_from')
                                        ->attr('id', 'r_hour_from')
                                        ->prop('selected', $hour_from);
                                    $dtMinute
                                        ->attr('name', 'r_minute_from')
                                        ->attr('id', 'r_minute_from')
                                        ->prop('selected', $minute_from);
                                    $dtAmPm
                                        ->attr('name', 'r_ampm_from')
                                        ->attr('id', 'r_ampm_from')
                                        ->prop('selected', $ampm_from);
                                    ?>
                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtHour->hour(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->

                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtMinute->minute(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->

                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtAmPm->ampm(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->
                                </div>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('plugin_vouchers_end_time') ?></label>

                                <div class="row">
                                    <?php
                                    $dtHour
                                        ->attr('name', 'r_hour_to')
                                        ->attr('id', 'r_hour_to')
                                        ->prop('selected', $hour_to);
                                    $dtMinute
                                        ->attr('name', 'r_minute_to')
                                        ->attr('id', 'r_minute_to')
                                        ->prop('selected', $minute_to);
                                    $dtAmPm
                                        ->attr('name', 'r_ampm_to')
                                        ->attr('id', 'r_ampm_to')
                                        ->prop('selected', $ampm_to);
                                    ?>
                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtHour->hour(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->

                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtMinute->minute(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->

                                    <div class="col-lg-4 col-xs-6">
                                        <?php echo $dtAmPm->ampm(); ?>
                                    </div><!-- /.col-lg-4 col-xs-6 -->

                                    <div class="col-xs-12">
                                        <input type="hidden" id="validate_time" name="validate_time" value="" data-msg-validateTime="<?php __('plugin_vouchers_validate_time', false, true);?>"/>
                                    </div>
                                </div>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->

                    <div class="hr-line-dashed"></div>

                    <div class="clearfix">
                        <button class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in">
                            <span class="ladda-label"><?php __('plugin_base_btn_save') ?></span>
                            <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                        </button>

                        <a class="btn btn-white btn-lg pull-right" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjVouchers&action=pjActionIndex"><?php __('plugin_base_btn_cancel') ?></a>
                    </div><!-- /.clearfix -->
                </form>
            </div>
        </div>
    </div><!-- /.col-lg-12 -->
</div>
<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.discount_invalid_msg = <?php x__encode('plugin_vouchers_discount_invalid'); ?>;
</script>