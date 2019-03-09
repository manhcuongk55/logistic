<div class="page404 text-center">
    <img src="/img/bomman.gif" class="img-404">
    <h4 class="text-uppercase" style="margin-top: 40px;">Oh! Xin lỗi bạn</h4>
    <h5 class="text-uppercase">Trang bạn yêu cầu không tồn tại</h5>
    <form class="form-inline mg-t20" action="<?php echo $this->createUrl("/home/search") ?>">
    	<input type="hidden" name="type" value="document" />
    	<div class="form-group">
      		<input type="text" class="form-control" placeholder="Tìm kiếm" name="search">
    	</div>
    	<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
  	</form>
    <div class="mg-t15">
        <a href="<?php echo $this->createUrl("/home/index") ?>" class="btn btn-info">Quay lại trang chủ</a>
    </div>
</div>