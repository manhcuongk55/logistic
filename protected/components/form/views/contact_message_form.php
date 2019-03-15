<?php $form->open(array(
    "id" => "contact-message-form",
)) ?>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label><?php $form->l_("Tiêu đề") ?></label>
                <?php $form->inputField("title",array(
                    "class" => "form-control input-sm"
                )) ?>
            </div>
            <div class="form-group">
                <label><?php $form->l_("Nội dung") ?></label>
                <?php $form->inputField("content",array(
                    "class" => "form-control input-sm"
                )) ?>
            </div>
            <div class="text-right">
                <?php $form->submitButton("Gửi",array(
                    "class" => "btn btn-primary"
                )) ?>
            </div>
        </div>
    </div>
<?php $form->close(); ?>
<script>
    $(function(){
         $("#contact-message-form").on("form-success",function(){
            $__$.alert("<?php $form->l_("Gửi tin nhắn thành công!") ?>",function(){
                location.reload();
            });
        })
    });
</script>