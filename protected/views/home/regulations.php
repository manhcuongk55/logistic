<?php 
    $pageList = array(
        "quy-dinh-order",
        "quy-dinh-hang-ki-gui",
        "quy-dinh-giao-nhan-hang",
        "quy-dinh-ve-thanh-toan"
    );
    $criteria = new CDbCriteria();
    $criteria->addInCondition("page_id",$pageList,"OR");
    $pages = Page::model()->findAll($criteria);
?>
<div class="breadcrumb-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumbs">
                    <ul>
                        <li class="home"><a href="<?php echo $this->createUrl("/home") ?>"><?php l_("home","Trang chủ") ?></a><span>/ </span></li>
                        <li class="home"><a href="">Quy định</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="blog-page-area details-page mg-b20">
    <div class="container">
        <div class="row">
            <div>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach($pages as $i => $page): ?>
                    <li role="presentation" <?php if($i==0): ?>class="active"<?php endif; ?>><a href="#<?php echo $page->page_id ?>" aria-controls="<?php echo $page->page_id ?>" role="tab" data-toggle="tab" class="bold"><?php echo $page->title ?></a></li>
                <?php endforeach; ?>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <?php foreach($pages as $i => $page): ?>
                    <div role="tabpanel" class="tab-pane <?php if($i==0): ?>active<?php endif; ?>" id="<?php echo $page->page_id ?>">
                        <?php echo $page->content ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>