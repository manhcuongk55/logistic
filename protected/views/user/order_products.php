<?php 
    $orderProductList = new OrderProductList($order->id);
?>
<?php
    $orderProductList->render();
?>