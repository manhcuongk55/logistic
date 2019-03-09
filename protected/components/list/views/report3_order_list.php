<?php $html->begin(); ?>
    <div class="table-account-data table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td><b>Mã Đơn Hàng</b></td>
                    <td><b>Ngày tạo</b></td>
                    <td><b>Tiền hàng</b></td>
                    <td><b>Tình trạng</b></td>
                    <td></td>
                </tr>
            </thead>
            <tbody list-items>
                <?php $html->resetLoop(); while($html->loop()): ?>
                    <?php $html->renderCurrentItem() ?>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="text-center" style="margin-top: 0px; padding: 50px 0px; background-color: #e0e0e0" list-no-items>
            Chưa có đơn hàng nào
        </div>
    </div>
	<?php $list->getPagination()->render(); ?>
<?php $html->end(); ?>