<?php $html->begin(); ?>
    <div class="table-account-data table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td><b>Mã Đơn Hàng</b></td>
                    <td><b>Ngày tạo</b></td>
                    <td><b>Tổng tệ (cả ship TQ)</b></td>
                    <td><b>Tỷ giá mua</b></td>
                    <td><b>Thành tiền (VNĐ)</b></td>
                    <td><b>Tỷ giá bán</b></td>
                    <td><b>Lợi nhuận</b></td>
                </tr>
            </thead>
            <tbody list-items>
                <?php 
                    Util::controller()->data["totalPrice"] = 0;
                    Util::controller()->data["totalProfit"] = 0;
                ?>
                <?php $html->resetLoop(); while($html->loop()): ?>
                    <?php $html->renderCurrentItem() ?>
                <?php endwhile; ?>
                <tr>
                    <td colspan="4">
                    </td>
                    <td class="bold">
                        <span money-display data-money="<?php echo Util::controller()->data["totalPrice"] ?>"></span>
                    </td>
                    <td>
                    </td>
                    <td class="bold">
                        <span money-display data-money="<?php echo Util::controller()->data["totalProfit"] ?>"></span>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="text-center" style="margin-top: 0px; padding: 50px 0px; background-color: #e0e0e0" list-no-items>
            Chưa có đơn hàng nào
        </div>
    </div>
	<?php $list->getPagination()->render(); ?>
<?php $html->end(); ?>