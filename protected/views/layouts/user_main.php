<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
	<?php $this->renderPartial("application.views.layouts.subviews.head"); ?>
</head>
<body>
	<?php $this->renderPartial("application.views.layouts.subviews.header"); ?>
	<div class="container mg-t10 mg-b10">
        <div class="text-right">
            Tỷ giá hôm nay: <span class="bold" money-display data-money="<?php echo Util::param2("setting","vnd_ndt_rate") ?>"></span> VNĐ / 1 NDT
        </div>
        <div class="mg-b10 mg-t10">
            <?php echo Util::param2("front_page_content","user_page_content1") ?>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="sidebar-content">
                    <?php $menu = new UserMenu(); ?>
                    <?php $menu->render(); ?>
                </div>
            </div>
            <div class="col-sm-9">
		        <?php echo $content; ?>
            </div>
        </div>
	</div>
	<?php $this->renderPartial("application.views.layouts.subviews.footer"); ?>
</body>
</html>