<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>

<?php $form->open(array(
    "id" => "delivery-order-form",
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
                <label>Tên sản phẩm</label>
                <?php $form->inputField("product_name",array(
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
                <label>Số kiện hàng</label>
                <?php $form->inputField("num_block",array(
                    "class" => "form-control input-sm"
                )) ?>
            </div>
            <div class="form-group">
                <label>Link ảnh</label>
                <?php $form->inputField("image_url",array(
                    "class" => "form-control input-sm"
                )) ?>
            </div>
            <div class="form-group">
                <label>Chèn ảnh</label>
                <?php $form->inputField("image",array(
                    "class" => "form-control input-sm"
                )) ?>
            </div>
            <div class="text-right">
                <?php $form->submitButton("Gửi đơn hàng <i class='fa fa-paper-plane'></i>",array(
                    "class" => "btn btn-primary"
                )) ?>
            </div>
        </div>
    </div>
<?php $form->close(); ?>
<script>
    $(function(){
         $("#delivery-order-form").on("form-success",function(){
            $.alert({
                title:"Thông báo",
                content: "<?php $form->l_("Gửi đơn hàng ký gửi thành công!") ?>",
                onClose: function(){
                location.href = "<?php echo $this->createUrl("/user/active_delivery_orders") ?>";
                }
            });
        })
    });
</script>