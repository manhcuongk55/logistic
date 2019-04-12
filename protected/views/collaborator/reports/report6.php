<?php
    $defaultYear = date("Y");
    $defaultMonth = date("m");
    $year = intval(Input::get("year",$defaultYear));
    $month = intval(Input::get("month",$defaultMonth));
    $numDayOfMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $from = DateTime::createFromFormat("d/m/Y", "01/$month/$year")->getTimestamp();
    $to = DateTime::createFromFormat("d/m/Y", "$numDayOfMonth/$month/$year")->getTimestamp();

    $criteria = new CDbCriteria();
    $criteria->addCondition(":from <= created_time and created_time <= :to");
    $criteria->params = array(
        ":from" => $from,
        ":to" => $to
    );
    $unknownShopDeliveryOrders = UnknownShopDeliveryOrder::model()->findAll($criteria);
?>
<div class="mg-b10">
    <form method="GET">
        Tháng
        <input type="number" class="input-sm" name="month" value="<?php echo $month ?>" />
        Năm
        <input type="number" class="input-sm" name="year" value="<?php echo $year ?>" />
        <button type="submit" class="btn btn-sm btn-primary">Tra cứu</button>
    </form>
</div>
<hr/>
<?php if(CacheHelper::beginFragment("customer-report-$year-$month",array(
    "collaborator-report6"
),array(
    "differentByUser" => false
))): ?>
    <table class="table">
        <thead>
            <tr>
                <th><b>STT</b></th>
                <th><b>Mã vận đơn</b></th>
                <th><b>Ghi chú</b></th>
                <th><b>Cân nặng</b></th>
                <th><b>Số khối</b></th>
                <th><b>Hình ảnh</b></th>
                <th><b>Ngày</b></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($unknownShopDeliveryOrders as $index => $unknownShopDeliveryOrder): ?>
                <tr>
                    <td><?php echo $index + 1 ?></td>
                    <td><?php echo $unknownShopDeliveryOrder->delivery_order_code ?></td>
                    <td><?php echo $unknownShopDeliveryOrder->description ?></td>
                    <td><?php echo $unknownShopDeliveryOrder->total_weight ?></td>
                    <td><?php echo $unknownShopDeliveryOrder->total_volume ?></td>
                    <td>
                        <?php if($unknownShopDeliveryOrder->image): ?>
                            <a href="<?php echo $unknownShopDeliveryOrder->image ?>" target="_blank">
                                <img src="<?php echo $unknownShopDeliveryOrder->image ?>" style="width: 100px; height: 100px" />
                            </a>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date("m/d/Y",$unknownShopDeliveryOrder->created_time) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php CacheHelper::endFragment() ?>
<?php endif; ?>