<?php 
    $vendorLogos = array(
        OrderProduct::WEBSITE_TYPE_TAOBAO => "/img/vendors/taobao.png",
        OrderProduct::WEBSITE_TYPE_TMALL => "/img/vendors/tmall.png",
        OrderProduct::WEBSITE_TYPE_1688 => "/img/vendors/1688.jpg",
    );
?>
<hr/>
<h3 class="bold">Thông tin đơn hàng</h3>
<hr/>
<div class="cart-table table-responsive mg-t10">
    <table class="table">
        <tbody>
            <tr>
                <td>
                    Mã đơn
                </td>
                 <td>
                    #<?php echo $order->id ?>
                </td>
            </tr>
            <tr>
                <td>
                    Trạng thái
                </td>
                 <td>
                    <span class="label label-success"><?php echo $order->listGetLabel("status") ?></span>
                </td>
            </tr>
            <tr>
                <td>
                    Tỉ giá
                </td>
                <td>
                    <span money-display data-money="<?php echo $order->exchange_rate ?>"></span> VND / NDT
                </td>
            </tr>
            <tr>
                <td>
                    Tổng giá trị sản phẩm (NDT)
                </td>
                <td>
                    <span money-display data-money="<?php echo $order->total_price_ndt ?>"></span> NDT
                </td>
            </tr>
            <tr>
                <td>
                    Giá vận chuyển TQ (NDT)
                </td>
                <td>
                    <span money-display data-money="<?php echo $order->total_delivery_price_ndt ?>"></span> NDT
                </td>
            </tr>
            <tr>
                <td>
                    Phí dịch vụ
                </td>
                <td>
                    <span money-display data-money="<?php echo $order->service_price_percentage ?>"></span> %
                </td>
            </tr>
            <tr>
                <td>
                    Cước cân nặng
                </td>
                <td>
                    <span money-display data-money="<?php echo $order->weight_price ?>"></span> VNĐ / kg
                </td>
            </tr>
            <tr>
                <td>
                    Tổng cân nặng
                </td>
                <td>
                    <?php echo $order->total_weight ?></span>kg
                </td>
            </tr>
            <tr>
                <td>
                    Tổng tiền hàng (VNĐ)
                </td>
                <td>
                    <span money-display data-money="<?php echo $order->total_price ?>"></span> VNĐ
                </td>
            </tr>
            <tr>
                <td>
                    Giá vận chuyển TQ (VNĐ)
                </td>
                <td>
                    <span money-display data-money="<?php echo $order->total_delivery_price ?>"></span> VNĐ
                </td>
            </tr>
            <tr>
                <td>
                    Phí dịch vụ
                </td>
                <td>
                    <span money-display data-money="<?php echo $order->service_price ?>"></span> VNĐ
                </td>
            </tr>
            <tr>
                <td>
                    Giá vận chuyển tận tay
                </td>
                <td>
                    <span money-display data-money="<?php echo $order->shipping_home_price ?>"></span> VNĐ
                </td>
            </tr>
            <tr>
                <td>
                    Tổng tiền cân nặng
                </td>
                <td>
                    <span money-display data-money="<?php echo $order->total_weight_price ?>"></span> VNĐ
                </td>
            </tr>
            <tr>
                <td>
                    Tổng tiền
                </td>
                <td>
                    <span money-display data-money="<?php echo $order->final_price ?>"></span> VNĐ
                </td>
            </tr>
            <tr>
                <td>
                    Đặt cọc
                </td>
                <td>
                    <span money-display data-money="<?php echo $order->deposit_amount ?>"></span> VNĐ
                </td>
            </tr>
            <tr>
                <td>
                    Tiền thanh lý
                </td>
                <td>
                    <span money-display data-money="<?php echo $order->remaining_amount ?>"></span> VNĐ
                </td>
            </tr>
            <tr>
                <td>
                    Ngày tạo đơn
                </td>
                <td>
                     <?php echo date("d/m/Y",$order->created_time) ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div>
    <p>
        <?php echo $order->description ?>
    </p>
</div>

<hr/>

<h3 class="bold">Thông tin sản phẩm</h3>
<hr/>
<div class="cart-table table-responsive mg-t20">
    <table class="table">
        <thead>
            <tr>
                <th class="p-url">
                    Đường dẫn
                </th>
                <th class="p-name">
                    Tên sản phẩm
                </th>
                <th class="p-image">
                    Hình ảnh
                </th>
                <th class="p-price">
                    Giá
                </th>
                <th class="p-quantity">
                    Số lượng
                </th>
                <th class="p-quantity">
                    Số lượng đặt được
                </th>
                <th class="p-quantity">
                    Cân nặng
                </th>
                <th>
                    Ghi chú
                </th>
                <th class="p-action">

                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($order->order_products as $orderProduct): ?>
                <tr>
                    <td>
                        <a href="<?php echo $orderProduct->url ?>" target="_blank">
                            <img src="<?php echo $vendorLogos[$orderProduct->website_type] ?>" />
                        </a>
                    </td>
                    <td>
                        <?php echo $orderProduct->vietnamese_name ?>
                    </td>
                    <td>
                        <img src="<?php echo $orderProduct->image ?>" />
                    </td>
                    <td>
                        <span money-display data-money="<?php echo $orderProduct->price ?>" />
                    </td>
                    <td>
                        <?php echo $orderProduct->count ?>
                    </td>
                    <td>
                        <?php echo $orderProduct->ordered_count ?>
                    </td>
                    <td>
                        <?php echo $orderProduct->description ?>
                    </td>
                    <td>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<hr/>
<h3 class="bold">Liên hệ với CTV để biết thêm chi tiết</h3>
<hr/>
<?php $this->renderPartial('subviews/sale_contact_info') ?>