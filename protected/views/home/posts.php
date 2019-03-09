<?php 
    $postList = new PostList();
    $postList->run();
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php $postList->render(); ?>
        </div>
    </div>
</div>