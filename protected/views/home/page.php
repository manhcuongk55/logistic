<?php 
    $url = $absoluteUrl;
?>
<div class="breadcrumb-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumbs">
                    <ul>
                        <li class="home"><a href="<?php echo $this->createUrl("/home") ?>"><?php l_("home","Trang chủ") ?></a><span>/ </span></li>
                        <li class="home"><a href="<?php echo $absoluteUrl ?>"><?php echo $page->title ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="blog-page-area details-page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="single-blog no-margin">
                    <?php if($page->image): ?>
                        <div class="post-thumbnail"><img src="<?php echo $page->url("image") ?>" alt=""></div>
                    <?php endif; ?>
                    <div class="postinfo-wrapper">
                        <div class="post-date">
                            <span class="day"><?php echo date("d",$page->created_time) ?></span><span class="month"><?php echo date("M",$page->created_time) ?></span>
                        </div>
                        <div class="post-info">
                            <h1 class="blog-post-title"><?php echo $page->title ?></h1>
                            <div class="entry-summary">
                                <?php echo $page->content ?>
                            </div>
                            <div class="share-icon">
                                <h3>Share this post</h3>
                                <ul>
                                    <li><a class="facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url ?>"><i class="fa fa-facebook"></i></a></li>
                                    <li><a class="twitter" target="_blank" href="https://twitter.com/home?status=<?php echo $url ?>"><i class="fa fa-twitter"></i></a></li>
                                    <li><a class="pinterest" target="_blank" href="https://pinterest.com/pin/create/button/?url=<?php echo $url ?>&media=&description="><i class="fa fa-pinterest"></i></a></li>
                                    <li><a class="google-plus" target="_blank" href="https://plus.google.com/share?url=<?php echo $url ?>"><i class="fa fa-google-plus"></i></a></li>
                                </ul>
                            </div>
                            <!-- <div class="reply-comment-area">
                                <h3><span class="fb-comments-count" data-href="<?php echo $url ?>"></span> comments</h3>
                                <div class="fb-comments" data-width="100%" data-href="<?php echo $url ?>"></div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>