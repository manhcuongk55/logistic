<?php
    $collaborator = $this->getUser();
    $criteria = new CDbCriteria();
    if($collaborator->collaborator_group->is_admin_group && $collaborator->is_manager){
        $criteria->addCondition("t.id IN (SELECT user_id FROM {{order}} GROUP BY user_id)");
    } else if($collaborator->is_manager) {
        $criteria->addCondition("t.id IN (SELECT user_id FROM {{order}} GROUP BY user_id HAVING collaborator_group_id = $collaborator->collaborator_group_id)");
    } else {
        $criteria->addCondition("t.id IN (SELECT user_id FROM {{order}} GROUP BY user_id HAVING collaborator_id = $collaborator->id)");
    }

    $users = User::model()->findAll($criteria);
    $defaultYear = date("Y");
    $defaultMonth = date("m");
    $year = intval(Input::get("year",$defaultYear));
    $month = intval(Input::get("month",$defaultMonth));
    $numDayOfMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $from = DateTime::createFromFormat("d/m/Y", "01/$month/$year")->getTimestamp();
    $to = DateTime::createFromFormat("d/m/Y", "$numDayOfMonth/$month/$year")->getTimestamp();
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
<?php if(CacheHelper::beginFragment("customer-report3-$collaborator->id-$year-$month",array(
    "collaborator-report3"
),array(
    "differentByUser" => false
))): ?>
    <table class="table">
        <thead>
            <tr>
                <th><b>STT</b></th>
                <th><b>NVKD</b></th>
                <th><b>DS</b></th>
                <th><b>DS thanh lý</b></th>
                <th><b>DS hoàn thành đơn</b></th>
                <th><b>DS đặt cọc</b></th>
                <th><b>Ngày</b></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $allTotalPrice = 0;
                $allTotalDonePrice = 0;
                $allTotalRemainingAmount = 0;
                $allTotalDepositAmount = 0;
            ?>
            <?php foreach($users as $index => $user): ?>
                <?php
                    $orders = Order::model()->findAll(array(
                        "condition" => "t.created_time > $from AND t.created_time < $to AND t.user_id = " . $user->id
                    ));
                    $totalPrice = 0;
                    $totalServicePrice = 0;
                    $totalDonePrice = 0;
                    $totalServiceDonePrice = 0;
                    $totalRemainingAmount = 0;
                    $totalDepositAmount = 0;
                    foreach($orders as $order){
                        if($order->status<Order::STATUS_DEPOSIT_DONE || $order->status==Order::STATUS_CANCELED)
                            continue;
                        $totalPrice += $order->total_price;
                        $totalServicePrice += $order->service_price;
                        if($order->status!=Order::STATUS_PAID)
                            continue;
                        $totalDonePrice += $order->total_price;
                        $totalServiceDonePrice += $order->service_price;
                        $totalRemainingAmount += $order->remaining_amount;
                        $totalDepositAmount += $order->deposit_amount;
                    }
                    $allTotalPrice += $totalPrice;
                    $allTotalDonePrice += $totalDonePrice;
                    $allTotalDepositAmount += $totalDepositAmount;

                    if($totalPrice==0 && $totalDonePrice==0){
                        continue;
                    }
                ?>
                <tr>
                    <td><?php echo $index + 1 ?></td>
                    <td><?php echo $user->name ?></td>
                    <td><span money-display data-money="<?php echo $totalPrice ?>"></span></td>
                    <td><span money-display data-money="<?php echo $totalDonePrice ?>"></span></td>
                    <td><span money-display data-money="<?php echo $totalRemainingAmount ?>"></span></td>
                    <td><span money-display data-money="<?php echo $totalDepositAmount ?>"></span></td>
                    <td><?php echo date("m/d/Y") ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2" class="text-right bold">
                    Tổng
                </td>
                <td><span money-display data-money="<?php echo $allTotalPrice ?>"></span></td>
                <td><span money-display data-money="<?php echo $allTotalDonePrice ?>"></span></td>
                <td><span money-display data-money="<?php echo $allTotalRemainingAmount ?>"></span></td>
                <td><span money-display data-money="<?php echo $allTotalDepositAmount ?>"></span></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <?php CacheHelper::endFragment() ?>
<?php endif; ?>