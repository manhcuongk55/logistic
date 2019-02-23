<?php 
    // if(!function_exists("money_format_2")){
    //     function money_format_2($format, $number) 
    //     { 
    //         $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'. 
    //                 '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/'; 
    //         if (setlocale(LC_MONETARY, 0) == 'C') { 
    //             setlocale(LC_MONETARY, ''); 
    //         } 
    //         $locale = localeconv(); 
    //         preg_match_all($regex, $format, $matches, PREG_SET_ORDER); 
    //         foreach ($matches as $fmatch) { 
    //             $value = floatval($number); 
    //             $flags = array( 
    //                 'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ? 
    //                             $match[1] : ' ', 
    //                 'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0, 
    //                 'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ? 
    //                             $match[0] : '+', 
    //                 'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0, 
    //                 'isleft'    => preg_match('/\-/', $fmatch[1]) > 0 
    //             ); 
    //             $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0; 
    //             $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0; 
    //             $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits']; 
    //             $conversion = $fmatch[5]; 

    //             $positive = true; 
    //             if ($value < 0) { 
    //                 $positive = false; 
    //                 $value  *= -1; 
    //             } 
    //             $letter = $positive ? 'p' : 'n'; 

    //             $prefix = $suffix = $cprefix = $csuffix = $signal = ''; 

    //             $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign']; 
    //             switch (true) { 
    //                 case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+': 
    //                     $prefix = $signal; 
    //                     break; 
    //                 case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+': 
    //                     $suffix = $signal; 
    //                     break; 
    //                 case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+': 
    //                     $cprefix = $signal; 
    //                     break; 
    //                 case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+': 
    //                     $csuffix = $signal; 
    //                     break; 
    //                 case $flags['usesignal'] == '(': 
    //                 case $locale["{$letter}_sign_posn"] == 0: 
    //                     $prefix = '('; 
    //                     $suffix = ')'; 
    //                     break; 
    //             } 
    //             if (!$flags['nosimbol']) { 
    //                 $currency = $cprefix . 
    //                             ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) . 
    //                             $csuffix; 
    //             } else { 
    //                 $currency = ''; 
    //             } 
    //             $space  = $locale["{$letter}_sep_by_space"] ? ' ' : ''; 

    //             $value = number_format($value, $right, $locale['mon_decimal_point'], 
    //                     $flags['nogroup'] ? '' : $locale['mon_thousands_sep']); 
    //             $value = @explode($locale['mon_decimal_point'], $value); 

    //             $n = strlen($prefix) + strlen($currency) + strlen($value[0]); 
    //             if ($left > 0 && $left > $n) { 
    //                 $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0]; 
    //             } 
    //             $value = implode($locale['mon_decimal_point'], $value); 
    //             if ($locale["{$letter}_cs_precedes"]) { 
    //                 $value = $prefix . $currency . $space . $value . $suffix; 
    //             } else { 
    //                 $value = $prefix . $value . $space . $currency . $suffix; 
    //             } 
    //             if ($width > 0) { 
    //                 $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ? 
    //                         STR_PAD_RIGHT : STR_PAD_LEFT); 
    //             } 

    //             $format = str_replace($fmatch[0], $value, $format); 
    //         } 
    //         return $format; 
    //     } 
    // }
    // setlocale(LC_MONETARY, '');
    // function displayMoney($money){
    //     $moneyStr = money_format_2("%!n",$money);
    //     return $moneyStr;
    // }

    function displayMoney($money){
        return number_format($money, 0, '', ',');
    }
?>
<div>
    <a href="http://orderhip.com">Orderhip.com</a>
    <h1>Đơn hàng #<?php echo $order->id ?></h1>
    <table>
        <tbody>
            <tr>
                <td>Mã đơn</td>
                <td><?php echo $order->id ?></td>
            </tr>
            <tr>
                <td>Giá tổng NDT</td>
                <td><?php echo displayMoney($order->total_price_ndt) ?></td>
            </tr>
            <tr>
                <td>Giá tổng vận chuyển NDT</td>
                <td><?php echo displayMoney($order->total_delivery_price_ndt) ?></td>
            </tr>
            <tr>
                <td>Tỉ giá</td>
                <td><?php echo displayMoney($order->exchange_rate) ?> VND / NDT</td>
            </tr>
            <tr>
                <td>Thời gian khởi tạo</td>
                <td><?php echo date("d/m/Y h:i:s",$order->created_time) ?></td>
            </tr>
            <tr>
                <td>Khách hàng</td>
                <td><?php echo $order->user->name ?></td>
            </tr>
            <tr>
                <td>Trạng thái</td>
                <td><b><?php echo $order->listGetLabel("status") ?></b></td>
            </tr>
            <tr>
                <td>Giá tổng</td>
                <td><?php echo displayMoney($order->total_price) ?></td>
            </tr>
            <tr>
                <td>Giá dịch vụ</td>
                <td><?php echo displayMoney($order->service_price) ?></td>
            </tr>
            <tr>
                <td>Cân nặng tổng</td>
                <td><?php echo $order->total_weight ?> kg</td>
            </tr>
            <tr>
                <td>Tổng giá cân nặng</td>
                <td><?php echo displayMoney($order->total_weight_price) ?></td>
            </tr>
            <tr>
                <td>Giá tổng vận chuyển</td>
                <td><?php echo displayMoney($order->total_delivery_price) ?></td>
            </tr>
            <tr>
                <td>Giá ship tận tay</td>
                <td><?php echo displayMoney($order->shipping_home_price) ?></td>
            </tr>
            <tr>
                <td>Phí phát sinh</td>
                <td><?php echo displayMoney($order->extra_price) ?></td>
            </tr>
            <tr>
                <td>Tổng tiền</td>
                <td><?php echo displayMoney($order->final_price) ?></td>
            </tr>
            <tr>
                <td>Đã đặt cọc</td>
                <td><?php echo displayMoney($order->deposit_amount) ?></td>
            </tr>
            <tr>
                <td style="color: red">Tiền thanh lý</td>
                <td style="color: red; font-weight: bold"><?php echo displayMoney($order->remaining_amount) ?></td>
            </tr>
            <tr>
                <td>Mã người dùng</td>
                <td><?php echo $order->user_id ?></td>
            </tr>
            <tr>
                <td>Phí dịch vụ</td>
                <td><?php echo $order->service_price_percentage ?> %</td>
            </tr>
            <tr>
                <td>Phí cân nặng</td>
                <td><?php echo displayMoney($order->weight_price) ?> /kg</td>
            </tr>
        </tbody>
    </table>
</div>