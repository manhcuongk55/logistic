<?php 
    $url = $this->createUrl("/home/post",array(
        "id" => $item->model->id,
        "slug" => $item->model->slug
    ))
?>
<?php $item->renderBegin("div",array(
    "class" => "single-blog fix"
)); ?>
    <?php if($item->model->image): ?>
        <div class="post-thumbnail">
            <a href="<?php echo $url ?>">
                <img src="<?php echo $item->model->url("image") ?>" alt="<?php echo $item->model->title ?>" />
            </a>
        </div>
    <?php endif; ?>
    <div class="postinfo-wrapper">
        <div class="post-date">
            <span class="day"><?php echo date("d",$item->model->created_time) ?></span><span class="month"><?php echo date("M",$item->model->created_time) ?></span>
        </div>
        <div class="post-info">
            <h1 class="blog-post-title">
                <a href="<?php echo $url ?>"><?php echo $item->model->title ?></a>
            </h1>
            <div class="entry-meta">
                Posted by <span class="author vcard"><a class="url fn n" href="javascript:;" rel="author"><?php echo $item->model->admin->name ?></a></span>
            </div>
            <div class="entry-summary">
                <p><?php echo $item->model->short_description ?></p>
            </div>
            <a class="read-button" href="<?php echo $url ?>"><span><?php l_("home/post","Xem thêm"); ?></span></a>
        </div>
    </div>
<?php $item->renderEnd(); ?>