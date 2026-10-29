<?php
$titles = __('error_titles', true);
$bodies = __('error_bodies', true);
$calendar_title = NULL;
if(count($tpl['calendar_arr']) > 1)
{
    foreach ($tpl['calendar_arr'] as $calendar)
    {
        if($calendar['id'] == $controller->getCalendarId())
        {
            $calendar_title = pjSanitize::html($calendar['title']);
        }
    }
}
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-6">
                <h2><?php echo @$titles['AC12'];?> <?php echo $calendar_title;?></h2>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
				<?php if (@$tpl['is_flag_ready']) : ?>
				<div class="multilang"></div>
				<?php endif; ?>
			</div>
        </div>

        <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php echo @$bodies['AC12']; ?></p>
    </div>
</div>