<?php
    $collaborator = $this->getUser();
    if($collaborator->type!=Collaborator::TYPE_ACCOUNTANT && $collaborator->type!=Collaborator::TYPE_SHIP && !$collaborator->is_manager){
        echo "Bạn không có quyền truy nhập vào trang này!";
        return;
    }

    $defaultYear = date("Y");
    $defaultMonth = date("m");
    $year = intval(Input::get("year",$defaultYear));
    $month = intval(Input::get("month",$defaultMonth));
    $numDayOfMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $from = DateTime::createFromFormat("d/m/Y", "01/$month/$year")->getTimestamp();
    $to = DateTime::createFromFormat("d/m/Y", "$numDayOfMonth/$month/$year")->getTimestamp();

    $orderList = new OrderList("report2");
    $orderList->setDynamicInput("from_date",$from);
    $orderList->setDynamicInput("to_date",$to);
    $orderList->run();
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
<?php $orderList->render() ?>