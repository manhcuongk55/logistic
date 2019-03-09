<?php 
    $day = date("d");
    $week = date('w');
    $month = date("m");
    $year = date("Y");

    $nowFrom = time() - 5 * 60;
    $nowTo = time() + 5 * 60;
    $dayFrom = DateTime::createFromFormat("d/m/Y H:i:s", "$day/$month/$year 00:00:00")->getTimestamp();
    $dayTo = time();
    $weekFrom = date('m-d-Y', strtotime('-'.$day.' days'));
    $weekTo = time();
    $monthFrom = DateTime::createFromFormat("d/m/Y H:i:s", "01/$month/$year 00:00:00")->getTimestamp();
    $monthTo = time();

    echo $dayFrom;
    echo " - ";
    echo $dayTo;

    $viewCounts = array(
        "now" => View::viewCount($nowFrom,$nowTo),
        "day" => View::viewCount($dayFrom,$dayTo),
        "week" => View::viewCount($weekFrom,$weekTo),
        "month" => View::viewCount($monthFrom,$monthTo),
    );

    $userLogins = array(
        "now" => View::userCount(View::TYPE_USER,$nowFrom,$nowTo),
        "day" => View::userCount(View::TYPE_USER,$dayFrom,$dayTo),
        "week" => View::userCount(View::TYPE_USER,$weekFrom,$weekTo),
        "month" => View::userCount(View::TYPE_USER,$monthFrom,$monthTo),
    );

    $collaboratorLogins = array(
        "now" => View::userCount(View::TYPE_COLLABORATOR,$nowFrom,$nowTo),
        "day" => View::userCount(View::TYPE_COLLABORATOR,$dayFrom,$dayTo),
        "week" => View::userCount(View::TYPE_COLLABORATOR,$weekFrom,$weekTo),
        "month" => View::userCount(View::TYPE_COLLABORATOR,$monthFrom,$monthTo),
    );
?>

<div>
    <b>Lượt truy cập chung:</b>  <br/>
    Hiện tại: <?php echo $viewCounts["now"] ?><br/>
    Ngày: <?php echo $viewCounts["day"] ?><br/>
    Tuần:<?php echo $viewCounts["week"] ?> <br/>
    Tháng: <?php echo $viewCounts["month"] ?><br/>
</div>
<hr/>
<div>
    <b>Lượt đăng nhập khách hàng:</b> <br/>
    Hiện tại: <?php echo $userLogins["now"] ?><br/>
    Ngày: <?php echo $userLogins["day"] ?><br/>
    Tuần: <?php echo $userLogins["week"] ?><br/>
    Tháng: <?php echo $userLogins["month"] ?><br/>
</div>
<hr/>
<div>
    <b>Lượt đăng nhập CTV:</b> <br/>
    Hiện tại: <?php echo $collaboratorLogins["now"] ?><br/>
    Ngày: <?php echo $collaboratorLogins["day"] ?><br/>
    Tuần: <?php echo $collaboratorLogins["week"] ?><br/>
    Tháng: <?php echo $collaboratorLogins["month"] ?><br/>
</div>