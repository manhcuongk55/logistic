<hr/>
<h3 class="bold">Thông tin đơn hàng</h3>
<hr/>
<div class="cart-table table-responsive mg-t10">
    <table class="table">
        <thead>
            <tr>
                <th>
                    ID
                </th>
                <th>
                    Trạng thái
                </th>
                <th>
                    Tổng giá trị
                </th>
                <th>
                    Tiền đặc cọc
                </th>
                <th>
                    Tổng cân
                </th>
                <th>
                    Mã vận đơn
                </th>
                <th>
                    Ngày khởi tạo
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    #<?php echo $deliveryOrder->id ?>
                </td>
                <td>
                    <span class="label label-success"><?php echo $deliveryOrder->listGetLabel("status") ?></span>
                </td>
                <td>
                    <span money-display data-money="<?php echo $deliveryOrder->total_price ?>"></span>
                </td>
                <td>
                    <span money-display data-money="<?php echo $deliveryOrder->deposit_amount ?>"></span>
                </td>
                <td>
                    <?php echo $deliveryOrder->total_weight ?> kg
                </td>
                <td>
                    <?php echo $deliveryOrder->delivery_order_code ?>
                </td>
                <td>
                    <?php echo date("d/m/Y",$deliveryOrder->created_time) ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div>
    <p>
        <?php echo $deliveryOrder->description ?>
    </p>
</div>
<hr/>
<h3 class="bold">Liên hệ với CTV để biết thêm chi tiết</h3>
<hr/>
<?php $this->renderPartial('subviews/sale_contact_info') ?>