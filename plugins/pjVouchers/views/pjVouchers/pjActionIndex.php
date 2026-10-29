<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <h2><?php __('plugin_vouchers_infobox_list_title') ?></h2>
            </div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i><?php __('plugin_vouchers_infobox_list_desc') ?></p>
    </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
    <?php
    $error_code = $controller->_get->toString('err');
    if (!empty($error_code))
    {
        $titles = __('plugin_vouchers_error_titles', true);
        $bodies = __('plugin_vouchers_error_bodies', true);
        switch (true)
        {
            case in_array($error_code, array('AV01', 'AV03')):
                ?>
                <div class="alert alert-success">
                    <i class="fa fa-check m-r-xs"></i>
                    <strong><?php echo @$titles[$error_code]; ?></strong>
                    <?php echo @$bodies[$error_code]?>
                </div>
                <?php
                break;
            case in_array($error_code, array('AV08', 'AV04')):
                ?>
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle m-r-xs"></i>
                    <strong><?php echo @$titles[$error_code]; ?></strong>
                    <?php echo @$bodies[$error_code]?>
                </div>
                <?php
                break;
        }
    }
    ?>

    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="row m-b-md">
                    <div class="col-md-4">
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjVouchers&amp;action=pjActionCreate" class="btn btn-primary"><i class="fa fa-plus"></i> <?php __('plugin_vouchers_btn_add_voucher') ?></a>
                    </div><!-- /.col-md-6 -->

                    <div class="col-md-4 col-sm-8">
                        <form action="" method="get" class="form-horizontal frm-filter">
                            <div class="input-group">
                                <input type="text" name="q" placeholder="<?php __('plugin_base_btn_search', false, true); ?>" class="form-control">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /.col-md-3 -->
                </div><!-- /.row -->

                <div id="grid"></div>
            </div>
        </div>
    </div><!-- /.col-lg-12 -->
</div>

<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.code = "<?php __('plugin_vouchers_voucher_code', false, true); ?>";
myLabel.discount = "<?php __('plugin_vouchers_discount', false, true); ?>";
myLabel.type = "<?php __('plugin_vouchers_discount_type', false, true); ?>";
myLabel.valid = "<?php __('plugin_vouchers_valid', false, true); ?>";
myLabel.exported = "<?php __('plugin_base_export', false, true); ?>";
myLabel.delete_selected = "<?php __('plugin_base_delete_selected', false, true); ?>";
myLabel.delete_confirmation = "<?php __('plugin_base_delete_confirmation', false, true); ?>";
var pjGrid = pjGrid || {};
pjGrid.has_export = <?php echo (int) $tpl['has_export']; ?>;
</script>