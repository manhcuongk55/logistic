<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>

<?php $form->open(array(
    "id" => "unknown-shop-delivery-order-form",
)) ?>
    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label>Mã vận đơn</label>
                <?php $form->inputField("delivery_order_code",array(
                    "class" => "form-control input-sm"
                )) ?>
            </div>
            <div class="form-group">
                <label>Cân nặng</label>
                <?php $form->inputField("total_weight",array(
                    "class" => "form-control input-sm"
                )) ?>
            </div>
            <div class="form-group">
                <label>Số khối</label>
                <?php $form->inputField("total_volume",array(
                    "class" => "form-control input-sm"
                )) ?>
            </div>
            <div class="form-group">
                <label>Ghi chú</label>
                <?php $form->inputField("description",array(
                    "class" => "form-control input-sm"
                )) ?>
            </div>
            <div class="form-group">
                <label>Hình ảnh</label>
                <?php $form->inputField("image",array(
                    "class" => "form-control input-sm"
                )) ?>
            </div>
            <div class="text-right">
                <?php $form->submitButton("Lưu lại <i class='fa fa-paper-plane'></i>",array(
                    "class" => "btn btn-primary"
                )) ?>
            </div>
        </div>
    </div>
<?php $form->close(); ?>
<script>
    $(function(){
         $("#unknown-shop-delivery-order-form").on("form-success",function(){
            $.alert({
                title:"Thông báo",
                content: "<?php $form->l_("Lưu thành công!") ?>",
                onClose: function(){
                location.href = "<?php echo $this->createUrl("/collaborator/china_store") ?>";
                }
            });
        })
    });
</script>