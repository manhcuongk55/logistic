Don hang #<?php echo $order->id ?> da ve VN, Quy khach vui long thanh ly don hang voi so tien con lai la: <?php echo $this->renderPartial("application.views.messages.money", array(
    "value" => $order->total_price - $order->deposit_amount
)) ?>. Lien he nhan hang sdt: 0936.237.226.
Cam on quy khach da su dung dich vu