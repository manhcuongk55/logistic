<?php $form->open(array(
    "id" => "user-form"
)); ?>
    <div class="form-group">
        <label>Tên hiển thị</label>
        <?php $form->inputField("name",array(
            "class" => "form-control input-sm",
            "placeholder" => "Tên hiển thị"
        )) ?>
    </div>
    <hr>
    <div class="form-group">
        <label>Số điện thoại liên hệ</label>
        <?php $form->inputField("phone",array(
            "class" => "form-control input-sm",
            "placeholder" => "Số điện thoại"
        )) ?>
    </div>
    <div class="form-group">
        <label>Skype</label>
        <?php $form->inputField("skype",array(
            "class" => "form-control input-sm",
            "placeholder" => "Skype"
        )) ?>
    </div>
    <div class="form-group">
        <label>Facebook</label>
        <?php $form->inputField("facebook_id",array(
            "class" => "form-control input-sm",
            "placeholder" => "Facebook"
        )) ?>
    </div>
    <div class="form-group" file-preview>
        <label>Ảnh đại diện</label>
        <div>
            <?php $form->inputField("image",array(
                "class" => "form-control input-sm hidden",
                "file-preview-input" => ""
            )) ?>   
            <button type="button" class="btn btn-sm btn-info" file-preview-trigger-input>Chọn ảnh</button>
            <div class="mg-t10">
                <img src="<?php echo $this->user->url("image") ?>" style="width: 200px;" file-preview-image />
            </div>
        </div>
    </div>
    <hr>
    <?php $form->submitButton("Cập nhật",array(
        "class" => "btn btn-primary"
    )) ?>
</form>
<script>
    $(function(){
         $("#user-form").on("form-success",function(){
            $__$.alert("Cập nhật thông tin thành công!",function(){
                location.href = "/user/contact";
            });
        })
    });
</script>