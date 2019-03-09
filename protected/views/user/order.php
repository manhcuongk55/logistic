<?php 
    $vendorLogos = array(
        OrderProduct::WEBSITE_TYPE_TAOBAO => "/img/vendors/taobao.png",
        OrderProduct::WEBSITE_TYPE_TMALL => "/img/vendors/tmall.png",
        OrderProduct::WEBSITE_TYPE_1688 => "/img/vendors/1688.jpg",
        OrderProduct::WEBSITE_TYPE_OTHER => "/img/vendors/other.png",
    );
?>
<style>
    .td-desc {
        background-color: blue;
        color: white;
        font-weight: bold;
        font-size: 18px;
    }
    
    .td-desc a {
        color: white;
    }
</style>
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
                    <span class="label label-danger"><?php echo $order->listGetLabel("status") ?></span>
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
<h3 class="bold">Chi phí đơn hàng</h3>
<div class="row">
    <div class="col-md-6">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td rowspan="4" class="td-desc text-center v-mid bold">Đơn hàng</td>
                    <td>Tổng tiền hàng (NDT)</td>
                    <td class="bold"><span money-display data-money="<?php echo $order->total_price_ndt ?>"></span></td>
                </tr>
                <tr>
                    <td>Tổng Ship TQ (NDT)</td>
                    <td><span money-display data-money="<?php echo $order->total_delivery_price_ndt ?>"></span></td>
                </tr>
                <tr>
                    <td>Tổng cân nặng (kg)</td>
                    <td class="bold text-danger"><?php echo $order->total_weight ?></span></td>
                </tr>
                <tr>
                    <td>Tổng số khối (m3)</td>
                    <td class="bold text-danger"><?php echo $order->total_volume ?></span></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td rowspan="4" class="td-desc text-center v-mid bold">Cước phí áp dụng</td>
                    <td>Tỷ giá</td>
                    <td class="bold text-danger"><span money-display data-money="<?php echo $order->exchange_rate ?>"></span></td>
                </tr>
                <tr>
                    <td>Phí dịch vụ</td>
                    <td class="bold text-info"><span money-display data-money="<?php echo $order->service_price_percentage ?>"></span> %</td>
                </tr>
                <tr>
                    <td>Cước cân nặng (VNĐ/kg)</td>
                    <td class="bold text-info"><span money-display data-money="<?php echo $order->weight_price ?>"></span></td>
                </tr>
                <tr>
                    <td>Cước số khối (VNĐ/m3)</td>
                    <td class="bold text-info"><span money-display data-money="<?php echo $order->volume_price ?>"></span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row mg-t20">
    <div class="col-md-6">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td rowspan="4" class="td-desc text-center v-mid bold">Chi phí mua hàng (VNĐ)</td>
                    <td>Tổng tiền hàng (VNĐ)</td>
                    <td class="bold"><span money-display data-money="<?php echo $order->total_price ?>"></span></td>
                </tr>
                <tr>
                    <td>Tổng Ship TQ (VNĐ)</td>
                    <td class="bold"><span money-display data-money="<?php echo $order->total_delivery_price ?>"></span></td>
                </tr>
                <tr>
                    <td>Phí dịch vụ (VNĐ)</td>
                    <td class="bold"><span money-display data-money="<?php echo $order->service_price ?>"></span></td>
                </tr>
                <tr>
                    <td>Tổng tiền 1 (VNĐ)</td>
                    <td class="bold"><span money-display data-money="<?php echo $order->final_price ?>"></span></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td rowspan="4" class="td-desc text-center v-mid bold">Chi phí vận chuyển</td>
                    <td>Tổng tiền cân nặng & số khối (VNĐ)</td>
                    <td class="bold"><span money-display data-money="<?php echo $order->total_weight_price ?>"></span></td>
                </tr>
                <tr>
                    <td>Ship tận tay hoặc gửi hàng (VNĐ)</td>
                    <td class="bold text-danger"><span money-display data-money="<?php echo $order->shipping_home_price ?>"></span></td>
                </tr>
                <tr>
                    <td>Chi phí phát sinh (VNĐ)</td>
                    <td class="bold"><span money-display data-money="<?php echo $order->extra_price ?>"></span></td>
                </tr>
                <tr>
                    <td>Tổng tiền 2 (VNĐ)</td>
                    <td class="bold"><span money-display data-money="<?php echo $order->total_weight_price + $order->shipping_home_price + $order->extra_price ?>"></span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

    <div class="row mg-t20">
        <div class="col-md-12">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td class="td-desc">
                            Đặt cọc: <span class="bold" money-display data-money="<?php echo $order->deposit_amount ?>"></span>
                        </td>
                        <td class="td-desc">
                            Hoàn thành đơn: 
                           <span class="bold" money-display data-money="<?php echo $order->remaining_amount ?>"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<hr/>
<div class="mg-b20">
    Chú ý: <span class="text-danger">Tổng cân nặng và cước phí cân nặng sẽ được cập nhật sau khi hàng về kho Việt Nam</span>
</div>
<h3 class="bold mg-t20">
    Thông tin sản phẩm
    <?php if($order->status==Order::STATUS_WAIT_FOR_PRICE): ?>
        <div>
            <span class="text-danger">Khách hàng chú ý kiểm tra và chỉnh sửa kỹ lại đơn hàng trước khi được báo giá.</span>
            <a href="<?php echo $this->createUrl("/user/order_products",array(
                "order_id" => $order->id
            )) ?>"><i class="fa fa-pencil"></i> Chỉnh sửa</a>
        </div>
    <?php endif; ?>
</h3>
<hr/>
<div class="cart-table table-responsive mg-t20">
    <table class="table">
        <thead>
            <tr>
                <th>
                    Đường dẫn
                </th>
                <th class="p-name">
                    Tên sản phẩm
                </th>
                <th class="p-image">
                    Hình ảnh
                </th>
                <th class="p-quantity">
                    SL khách đặt
                </th>
                <th class="p-quantity">
                    SL đặt được
                </th>
                <th class="p-price">
                    Đơn giá
                </th>
                <th class="p-quantity">
                    Thành tiền
                </th>
                <th>
                    Mô tả
                </th>
                <th>
                    Ghi chú (CTV)
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($order->order_products as $i => $orderProduct): ?>
                <tr>
                    <td>
                        <a href="<?php echo $orderProduct->url ?>" target="_blank">
                            <img src="<?php echo @$vendorLogos[$orderProduct->website_type] ?>" />
                        </a>
                    </td>
                    <td>
                        <?php echo $orderProduct->vietnamese_name ?>
                    </td>
                    <td>
                        <img src="<?php echo $orderProduct->image ?>" />
                    </td>
                    <td>
                        <?php echo $orderProduct->count ?>
                    </td>
                    <td>
                        <?php echo $orderProduct->ordered_count ?>
                    </td>
                    <td>
                        <span money-display data-money="<?php echo $orderProduct->price ?>" />
                    </td>
                    <td>
                        <span money-display data-money="<?php echo $orderProduct->price * $orderProduct->count ?>" />
                    </td>
                    <td>
                        <?php echo $orderProduct->description ?>
                    </td>
                    <td>
                        <?php echo $orderProduct->collaborator_note ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<hr/>
<h3 class="bold mg-t20">
    Thông tin vận đơn
</h3>
<hr/>
<div class="cart-table table-responsive mg-t20">
    <table class="table">
        <thead>
            <tr>
                <th>
                    Mã vận đơn
                </th>
                <th class="p-url">
                    Trạng thái
                </th>
                <th class="p-name">
                    Cân nặng
                </th>
                <th class="p-image">
                    Số kiện hàng
                </th>
                <th class="p-quantity">
                    Mã kiện hàng
                </th>
                <th class="p-quantity">
                    Ngày về kho TQ
                </th>
                <th class="p-price">
                    Ngày về kho VN
                </th>
                <th>
                    Ghi chú (CTV)
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($order->shop_delivery_orders as $i => $shopDeliveryOrder): ?>
                <tr>
                    <td>
                        <?php echo $shopDeliveryOrder->delivery_order_code ?>
                    </td>
                    <td>
                        <span class="label label-danger"><?php echo $shopDeliveryOrder->listGetLabel("status") ?></span>
                    </td>
                    <td>
                        <?php echo $shopDeliveryOrder->total_weight ?>
                    </td>
                    <td>
                        <?php echo $shopDeliveryOrder->num_block ?>
                    </td>
                    <td>
                        <?php echo $shopDeliveryOrder->block_id ?>
                    </td>
                    <td>
                        <?php echo date("d/m/Y",$shopDeliveryOrder->china_delivery_time) ?>
                    </td>
                    <td>
                        <?php echo date("d/m/Y",$shopDeliveryOrder->vietnam_delivery_time) ?>
                    </td>
                    <td>
                        <?php echo $shopDeliveryOrder->note ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<hr/>
<?php if($order->extra_description): ?>
<h3 class="bold mg-t20">
    Ghi chú lỗi phát sinh
</h3>
<div class="bold text-danger"><?php echo $order->extra_description ?></div>
<hr/>
<?php endif; ?>]
<h3 class="bold">Liên hệ với CTV để biết thêm chi tiết</h3>
<hr/>
<?php $this->renderPartial('subviews/sale_contact_info') ?>