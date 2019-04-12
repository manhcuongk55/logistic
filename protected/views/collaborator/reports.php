<?php 
    $reportIds = array(
        1 => "Thống kê tỷ giá",
        2 => "Thống kê lợi nhuận mặc cả",
        3 => "Thống kê NVKD",
        4 => "Thống kê doanh số khách hàng",
        5 => "Thống kế lượt truy cập",
        6 => "Thống kế mã vận đơn thừa",
    );
?>
<?php if(isset($reportIds[$id])): ?>
    <?php $this->pageTitle = $reportIds[$id]; ?>
    <div class="z-depth-1 pd-a10">
        <div class="row account-section-title" style="border-top: none">
			<div class="col-md-6">
				<h2 class="">
					<b><?php echo $reportIds[$id] ?></b>
				</h2>
			</div>
			<div class="col-md-6 text-right">
			</div>
        </div>
        <div>
            <?php $this->renderPartial("reports/report$id") ?>
        </div>
	</div>
<?php else: ?>
        <?php foreach($reportIds as $reportId => $title): ?>
            <div class="mg-b10">
                <a href="<?php echo $this->createUrl("/collaborator/reports",array(
                    "id" => $reportId
                )) ?>" class="btn btn-primary btn-block"><?php echo $title ?></a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>