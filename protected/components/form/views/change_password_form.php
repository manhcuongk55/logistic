<?php $form->open(array(
    "id" => "change-password-form",
)) ?>
    <div class="row">
        <div class="col-md-6">
            <?php if($form->user->passwordBackup): ?>
                <div class="form-group">
                    <label><?php $form->l_("Mật khẩu hiện tại") ?></label>
                    <?php $form->inputField("current_password",array(
                        "class" => "form-control input-sm"
                    )) ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label><?php $form->l_("Mật khẩu mới") ?></label>
                <?php $form->inputField("new_password",array(
                    "class" => "form-control input-sm"
                )) ?>
            </div>
            <div class="form-group">
                <label><?php $form->l_("Nhập lại mật khẩu mới") ?></label>
                <?php $form->inputField("retype_new_password",array(
                    "class" => "form-control input-sm"
                )) ?>
            </div>
            <div class="text-right">
                <?php $form->submitButton("Lưu mật khẩu",array(
                    "class" => "btn btn-primary"
                )) ?>
            </div>
        </div>
    </div>
<?php $form->close(); ?>
<script>
    $(function(){
         $("#change-password-form").on("form-success",function(){
            $__$.alert("<?php $form->l_("Đổi mật khẩu thành công!") ?>",function(){
                location.reload();
            });
        })
    });
</script>