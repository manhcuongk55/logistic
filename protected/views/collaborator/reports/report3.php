<?php
    $collaborator = $this->getUser();
    $collaborators = array();
    if($collaborator->collaborator_group->is_admin_group && $collaborator->is_manager){
        $collaborators = Collaborator::model()->findAllByAttributes(array(
            "type" => Collaborator::TYPE_SALE,
        ));
    } else if($collaborator->is_manager) {
        $collaborators = Collaborator::model()->findAllByAttributes(array(
            "type" => Collaborator::TYPE_SALE,
            "collaborator_group_id" => $collaborator->collaborator_group_id,
        ));
    } else {
        $collaborators = array($collaborator);
    }
   
    $defaultYear = date("Y");
    $defaultMonth = date("m");
    $year = intval(Input::get("year",$defaultYear));
    $month = intval(Input::get("month",$defaultMonth));
    $numDayOfMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $from = DateTime::createFromFormat("H:i:s d/m/Y", "00:00:00 01/$month/$year")->getTimestamp();
    $to = DateTime::createFromFormat("H:i:s d/m/Y", "23:59:59 $numDayOfMonth/$month/$year")->getTimestamp();
?>
<div class="mg-b10" data-from="<?php echo date("H:i:s d/m/Y",$from) ?>" data-to="<?php echo date("H:i:s d/m/Y",$to) ?>">
    <form method="GET">
        Tháng
        <input type="number" class="input-sm" name="month" value="<?php echo $month ?>" />
        Năm
        <input type="number" class="input-sm" name="year" value="<?php echo $year ?>" />
        <button type="submit" class="btn btn-sm btn-primary">Tra cứu</button>
    </form>
</div>
<hr/>
<?php 
if(CacheHelper::beginFragment("collaborator-report3-$collaborator->id-$year-$month",array(
    "collaborator-report3"
),array(
    "differentByUser" => false
))):
?>
    <table class="table">
        <thead>
            <tr>
                <th><b>STT</b></th>
                <th><b>NVKD</b></th>
                <th><b>DS</b></th>
                <th><b>DS thanh lý</b></th>
                <th><b>PDV</b></th>
                <th><b>PDV thanh lý</b></th>
                <th><b>Ngày</b></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $allTotalPrice = 0;
                $allTotalDonePrice = 0;
            ?>
            <?php foreach($collaborators as $index => $collaborator): ?>
                <?php
                    // $condition = "";
                    // if($collaborator->is_manager){
                    //     $condition = "t.created_time > $from AND t.created_time < $to AND t.user_id IN (SELECT id FROM {{user}} WHERE collaborator_group_id = " . $collaborator->collaborator_group_id . " )";
                    // } else {
                    //     $condition = "t.created_time > $from AND t.created_time < $to AND t.user_id IN (SELECT id FROM {{user}} WHERE collaborator_id = " . $collaborator->id . " )";
                    // }
                    $condition = "t.created_time > $from AND t.created_time < $to AND t.user_id IN (SELECT id FROM {{user}} WHERE (collaborator_id IS NOT NULL AND collaborator_id = $collaborator->id))";
                    if($collaborator->is_manager){
                        $condition = "t.created_time > $from AND t.created_time < $to AND t.user_id IN (SELECT id FROM {{user}} WHERE (collaborator_id IS NOT NULL AND collaborator_id = $collaborator->id) OR (collaborator_id IS NULL AND collaborator_group_id = $collaborator->collaborator_group_id))";
                    }
                    $orders = Order::model()->findAll(array(
                        "condition" => $condition
                    ));
                    $totalPrice = 0;
                    $totalServicePrice = 0;
                    $totalDonePrice = 0;
                    $totalServiceDonePrice = 0;
                    foreach($orders as $order){
                        if($order->status<Order::STATUS_DEPOSIT_DONE || $order->status==Order::STATUS_CANCELED)
                            continue;
                        $totalPrice += $order->total_price;
                        $totalServicePrice += $order->service_price;
                        if($order->status!=Order::STATUS_PAID)
                            continue;
                        $totalDonePrice += $order->total_price;
                        $totalServiceDonePrice += $order->service_price;
                    }
                    $allTotalPrice += $totalPrice;
                    $allTotalDonePrice += $totalDonePrice;
                ?>
                <tr>
                    <td><?php echo $index + 1 ?></td>
                    <td><?php echo $collaborator->name ?></td>
                    <td><span money-display data-money="<?php echo $totalPrice ?>"></span></td>
                    <td><span money-display data-money="<?php echo $totalDonePrice ?>"></span></td>
                    <td><span money-display data-money="<?php echo $totalServicePrice ?>"></span></td>
                    <td><span money-display data-money="<?php echo $totalServiceDonePrice ?>"></span></td>
                    <td><?php echo date("m/d/Y") ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2" class="text-right bold">
                    Tổng
                </td>
                <td><span money-display data-money="<?php echo $allTotalPrice ?>"></span></td>
                <td><span money-display data-money="<?php echo $allTotalDonePrice ?>"></span></td>
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>
    <?php CacheHelper::endFragment() ?>
<?php endif; ?>