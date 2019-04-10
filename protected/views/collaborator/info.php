<div class="z-depth-1 pd-a20">
    <div class="row">
        <div class="col-md-12">
            <h3 class="bold">Thông tin</h3>
            <hr/>
            <h4>Chia sẻ link</h4>
            <code>
                <?php 
                    $user = $this->getUser();
                    $url = $this->createAbsoluteUrl("/site/login?collaborator_id=" . $user->id . "&redirect_url=/home/index");
                    echo $url;
                ?>
            </code>
        </div>
    </div>
</div>