<?php 
    $items = ProductCartHelper::getItems();
    if(isset($_GET["debug"])){
        var_dump($items);
        var_dump(json_encode($items,JSON_UNESCAPED_UNICODE));
        die();
    }
    $url = Yii::app()->request->requestUri;
    $cartUrl = $this->createUrl("/home/cart");
    $vendorLogos = array(
        OrderProduct::WEBSITE_TYPE_TAOBAO => "/img/vendors/taobao.png",
        OrderProduct::WEBSITE_TYPE_TMALL => "/img/vendors/tmall.png",
        OrderProduct::WEBSITE_TYPE_1688 => "/img/vendors/1688.jpg",
        OrderProduct::WEBSITE_TYPE_OTHER => "/img/vendors/other.png",
    );
?>

<style>

    .p-url {
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 200px;
        overflow: hidden;
        
    }
    /* --- VARS --- */
/* --- MIXINS --- */
/* --- DEMO CONTENT--- */
.navbar-menu {
  border: 0;
  border-radius: 0;
}
.navbar-menu .navbar-collapse {
  padding: 0;
  border-top: 0;
}
.navbar-main {
  margin: 0;
}
.navbar-menu {
  background-color: #7352a4;
}
.navbar-menu .navbar-header {
  padding: 7px;
}
.navbar-main > li > a {
  font-size: 15px;
  font-weight: 500;
  color: #ffffff;
  text-transform: uppercase;
  -webkit-transition: 0.2s color ease-in-out;
  transition: 0.2s color ease-in-out;
  -webkit-transition: 0.2s background ease-in-out;
  transition: 0.2s background ease-in-out;
}
.navbar-main > li > a small {
  color: #fff;
  font-size: 15px;
  font-weight: 300;
  text-transform: none;
}
.navbar-main > li > a:hover,
.navbar-main > li > a:focus,
.navbar-main > li > a.active {
  background-color: #ffffff;
  color: #583e7e;
}
.navbar-main > li > a:hover small,
.navbar-main > li > a:focus small,
.navbar-main > li > a.active small {
  color: #000;
}
@media (min-width: 768px) {
  .navbar-main-container {
    background-color: #7352a4;
    background-repeat: repeat-x;
    background-image: -webkit-linear-gradient(left, color-stop(#7352a4 50%), color-stop(#583e7e 50%));
    background-image: -moz-linear-gradient(left, #7352a4 50%, #583e7e 50%);
    background-image: linear-gradient(to right, #7352a4 50%, #583e7e 50%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#7352a4', endColorstr='#583e7e', GradientType=1);
    /* IE6-9 */
  }
  .navbar-main-container .navbar-nav {
    float: none;
  }
  .navbar-main {
    margin: 0 auto;
    padding-right: 40px;
    max-width: 730px;
  }
  .navbar-main li {
    display: inline-block;
    height: 80px;
    position: relative;
    width: 25%;
    background-color: #583e7e;
    background-repeat: repeat-x;
    background-image: -webkit-gradient(linear, 0% top, 100% top, from(#583e7e), to(#7352a4));
    background-image: -webkit-linear-gradient(left, color-stop(#583e7e 0%), color-stop(#7352a4 100%));
    background-image: -moz-linear-gradient(left, #583e7e 0%, #7352a4 100%);
    background-image: linear-gradient(to right, #583e7e 0%, #7352a4 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#583e7e', endColorstr='#7352a4', GradientType=1);
    /* IE6-9 */
  }
  .navbar-main li:first-child:before {
    border: 40px solid transparent;
    border-left-color: #7352a4;
    border-right: 0;
    content: " ";
    display: block;
    position: absolute;
    left: 0;
    top: 0px;
    width: 0px;
    z-index: 10;
  }
  .navbar-main li a {
    display: table;
    height: 100%;
    padding: 0 0 0 66.66666667px;
    position: relative;
    width: 100%;
  }
  .navbar-main li a:after {
    border: 40px solid transparent;
    border-left-color: #7352a4;
    border-right: 0;
    content: " ";
    display: block;
    position: absolute;
    right: -40px;
    top: 0px;
    width: 0px;
    z-index: 10;
    -webkit-transition: 0.2s border-color ease-in-out;
    transition: 0.2s border-color ease-in-out;
  }
  .navbar-main li a:hover:after,
  .navbar-main li a.active:after {
    
    border-left-color: #ffffff;
  }
  
  
  .navbar-main li a span {
    display: table-cell;
    vertical-align: middle;
  }
}
@media (min-width: 992px) {
  .navbar-main {
    max-width: 980px;
  }
  .navbar-main li a {
    padding: 0 0 0 80px;
  }
}
input[type=text], select {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 20%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;

}

input[type=submit]:hover {
  background-color: #45a049;
  width: 20%;

}



</style>

<form id="delete-all-form" class="hidden" method="POST" action="<?php echo $cartUrl ?>" data-type="validate" data-confirm="Xóa tất cả sản phẩm trong giỏ hàng">
    <input type="hidden" name="action" value="delete_all" />
    <input type="hidden" name="redirect_url" value="<?php echo $url ?>" />
</form>
<?php if($errorMessage = Input::get("error")): ?>
    <div>
        <div class="alert alert-danger" role="alert">
            <?php echo $errorMessage ?>
        </div>
    </div>
<?php endif; ?>

<div class="cart-main-area area-padding mg-t20" ng-app="app" ng-controller="CartController" ng-rendered>
	<div class="container-1">
        <div class="row">
			<div class="col-md-12">
                <nav class="navbar navbar-menu" role="navigation">

    <!-- Collect the nav links, forms, and other content for toggling -->
    
      <div class="navbar-main-container">
        <ul class="nav navbar-nav navbar-main" id="main-nav">
            <li id="firt-item" class="navbar-item" data-toggle="collapse" data-target="#b1"><a href="#"><span class="icon-arrow-right">B1.<br/><small>Nhập thông tin nhà cung cấp</small></span></a></li>
            <li class="navbar-item" class="navbar-item" data-toggle="collapse" data-target="#b2"><a href="#"><span class="icon-arrow-right">B2<br/><small>Nhập thông tin sp</small></span></a></li>
            <li class="navbar-item" class="navbar-item" data-toggle="collapse" data-target="#b3"><a href="#"><span class="icon-arrow-right">B3.<br/><small>Chọn hình thức báo giá v/c</small></span></a></li>
            <li class="navbar-item" class="navbar-item" data-toggle="collapse" data-target="#b4"><a href="#"><span class="icon-arrow-right">B4.<br/><small>Chọn kho gửi hàng</small></span></a></li></ul>
    </div>
  </nav>

<div id="b1" class="collapse">

    <div class="page-title bold">
                    <h1>Thêm sản phẩm vào đơn hàng hiện tại</h1>
                </div>
                <div>
  <form action="/action_page.php">
    <label for="fname">Link Hoặc Tên Nhà Cung Cấp</label>
    <input type="text" id="fname" name="firstname" placeholder="Link Hoặc Tên Nhà Cung Cấp">

    <label for="lname">Số Điện Thoại</label>
    <input type="text" id="lname" name="lastname" placeholder="Số Điện Thoại">
  
    <input type="submit" value="Xác Nhận">
  </form>
</div>

                  
  </div>
  <div id="b2" class="collapse">
      <div class="row">
            <div class="col-md-8" ng-controller="InsertFormController">
                <form action="<?php echo $cartUrl ?>" method="POST">
                    <input type="hidden" name="action" value="add" />
                    <input type="hidden" name="redirect_url" value="<?php echo $url ?>" />
                    <div class="row mg-t5" ng-repeat="addedItem in addedItems">
                        <div class="col-md-8">
                            <input type="text" class="form-control input-sm" name="items[{{$index}}][url]" ng-model="addedItem.url" placeholder="Đường dẫn sản phẩm {{$index+1}}" />
                            
                            <textarea class="form-control input-sm mg-t5" rows="2" name="items[{{$index}}][description]" ng-model="addedItem.description" placeholder="Ghi chú"></textarea>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control input-sm" name="items[{{$index}}][count]" ng-model="addedItem.count" placeholder="Số lượng" />
                        </div>
                        <div class="col-md-1 text-right">
                            <button type="button" class="btn btn-sm btn-danger" ng-click="remove($index)" ng-disabled="addedItems.length==1"><i class="fa fa-close"></i></button>
                        </div>
                        <div class="col-md-1 text-right">
                            <button type="button" class="btn btn-sm btn-success" ng-click="add()" ng-if="$index==addedItems.length-1"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="row mg-t10">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-sm btn-primary" ng-disabled="!getSubmitable()">Xác nhận</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <hr/>

        <div class="row">
            <div class="col-md-12">
                <div class="page-title bold">
                    <h1>Upload file</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form action="<?php echo $cartUrl ?>" method="POST" enctype="multipart/form-data"  novalidate>
                    <input type="hidden" name="action" value="upload_file" />
                    <input type="hidden" name="redirect_url" value="<?php echo $url ?>" />
                    <div class="row">
                        <div class="col-md-8">
                            <input type="file" name="file" class="form-control" />
                        </div>
                    </div>
                    <div class="row mg-t10">
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <hr/>

        <div class="row mg-t20">
            <div class="col-md-12">
                <div class="page-title bold">
                    <h1>Danh sách sản phẩm</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <form action="<?php echo $cartUrl ?>" method="POST" novalidate>
                    <input type="hidden" name="action" value="update_all" />
                    <input type="hidden" name="redirect_url" value="<?php echo $url ?>" />
                    <div class="cart-table table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="p-url">
                                        Đường dẫn
                                    </th>
                                    <!--<th class="p-name">
                                        Tên sản phẩm
                                    </th>-->
                                    <th class="p-shop">
                                        Shop
                                    </th>
                                    <th class="p-image">
                                        Hình ảnh
                                    </th>
                                    <th class="p-quantity">
                                        Số lượng
                                    </th>
                                    <th>
                                        Ghi chú
                                    </th>
                                    <th class="p-action">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-controller="ItemController" ng-repeat="item in data.items" ng-style="item.is_updating && {'background-color': '#eee'}">
                                    <td>
                                        <input type="hidden" ng-value="item.url" ng-attr-name="items[{{$index}}][url]" />
                                        <input type="hidden" ng-value="item.name" ng-attr-name="items[{{$index}}][name]" />
                                        <input type="hidden" ng-value="item.description" ng-attr-name="items[{{$index}}][description]" />
                                        <input type="hidden" ng-value="item.image" ng-attr-name="items[{{$index}}][image]" />
                                        <input type="hidden" ng-value="item.type" ng-attr-name="items[{{$index}}][type]" />
                                        <input type="hidden" ng-value="item.original_name" ng-attr-name="items[{{$index}}][original_name]" />
                                        <input type="hidden" ng-value="item.web_price" ng-attr-name="items[{{$index}}][web_price]" />

                                        <a ng-attr-href="{{item.url}}" target="_blank" ng-attr-title="{{item.url}}">
                                            <img ng-attr-src="{{ getVendorLogo() }}" />
                                        </a>
                                    </td>
                                    <!--<td class="p-name">
                                        {{item.name ? item.name : "[Đang cập nhật]"}}
                                         <a ng-attr-href="{{item.url}}" target="_blank" ng-attr-title="{{item.original_name}}">
                                            <i class="fa fa-external-link"></i>
                                        </a>
                                    </td>-->

                                    <td class="p-shop">
                                        <textarea placeholder="Shop" class="form-control" ng-attr-name="items[{{$index}}][shop_id]" ng-model="item.shop_id" ng-value="(item.shop_id == item.url) ? '' : item.shop_id "></textarea>
                                    </td>
                                    <td class="p-image">
                                        <img ng-attr-src="{{ item.image ? item.image : '/img/product_placeholder.png' }}" style="width: 75px; height: 75px" />
                                    </td>
                                    <td class="p-quantity">
                                        <input ng-integer maxlength="20" type="number" ng-attr-name="items[{{$index}}][count]" ng-model="item.count" />
                                    </td>
                                    <td>
                                        {{item.description}}
                                    </td>
                                    <td class="p-action">
                                        <button class="button" class="btn btn-sm btn-danger" ng-click="removeItem($index)"><i class="fa fa-close"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php if(!count($items)): ?>
                            <div class="mg-t10">
                                <div class="alert alert-info" role="alert">
                                    Chưa có sản phẩm nào. Bắt đầu thêm sản phẩm dưới đây!
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="all-cart-buttons clearfix" style="padding: 10px 0px;">
                        <div class="floatright">
                            <button class="btn btn-default clear-cart" type="button" onclick="$__$.__ajax('#delete-all-form')">
                                <span>Xóa tất cả sản phẩm</span>
                            </button>
                            <button class="btn btn-primary" type="submit">
                                <span>Cập nhật giỏ hàng</span>
                            </button>
                        </div>
                    </div>
                </form>
                <div class="row mg-t15">
                    <div class="col-md-9 italic">
                        Sau khi hoàn tất việc thêm sản phẩm, bạn chỉ cần click vào nút Gửi đơn hàng và đợi CTV báo giá.<br>
                        Mọi thông tin cập nhật về đơn hàng này sẽ được thông báo qua email cũng như trên trang web này
                    </div>
                    <div class="col-md-3 text-right">
                        <button type="button" class="btn btn-danger" onclick="$__$.__ajax('#make-order-form')" ng-disabled="!data.items.length">Gửi đơn hàng <i class="fa fa-paper-plane"></i></button>
                    </div>
                </div>
                 <form id="make-order-form" class="hidden" action="<?php echo $cartUrl ?>" method="POST" data-type="ajax" data-confirm="Xác nhận gửi đơn hàng này?">
                    <input type="hidden" name="action" value="make_order" />
                    <?php foreach($items as $i => $item): ?>
                        <input type="hidden" value="<?php echo $item->count ?>" name="items[<?php echo $i ?>][count]" ng-value="data.items[<?php echo $i ?>].count" />
                        <input type="hidden" value="<?php echo $item->url ?>" name="items[<?php echo $i ?>][url]" />
                        <input type="hidden" value="<?php echo $item->name ?>" name="items[<?php echo $i ?>][name]"/>
                        <input type="hidden" value="<?php echo $item->description ?>" name="items[<?php echo $i ?>][description]"/>
                        <input type="hidden" value="<?php echo $item->image ?>" name="items[<?php echo $i ?>][image]"/>
                        <input type="hidden" value="<?php echo $item->type ?>" name="items[<?php echo $i ?>][type]"/>
                        <input type="hidden" value="<?php echo $item->original_name ?>" name="items[<?php echo $i ?>][original_name]"/>
                        <input type="hidden" value="<?php echo $item->web_price ?>" name="items[<?php echo $i ?>][web_price]"/>
                        <input type="hidden" value="<?php echo $item->shop_id ?>" name="items[<?php echo $i ?>][shop_id]"/>
                    <?php endforeach; ?>
                </form>
            </div>
        </div>

    
  </div>
  <div id="b3" class="collapse">
        
    <label for="country">Chọn Hình Thức Báo Giá Sản Phẩm</label>
    <select id="country" name="country">
      <option value="australia">Giá bao gồm thuế sản phẩm </option>
      <option value="canada">Không bao gồm thuế sản phẩm</option>
       </select>
       <input type="submit" value="Xác Nhận">
  
  </div>
  <div id="b4" class="collapse">
      <label for="warehouse">Chọn Kho Gửi Hàng</label> 
      <select id="warehouse" name="warehouse">
      <option value="Bằng Tường">Bằng Tường </option>
      <option value=".....">.....</option>
      
    </select>
    <input type="submit" value="Xác Nhận">
    
  </div>
            </div>
        </div>
       
	</div>
</div>
<!--End of Cart Main Area-->
<script>
    (function(){
        $("#make-order-form").on("form-success",function(e,data){
            location.href = "<?php echo $this->createUrl("/user/active_orders") ?>" + "?order_id=" + data.order_id;
        })
    })();
    (function(){
        var app = $__$.angular.init("app");
        app.controller("CartController",function($scope){
            t = $scope;
            $scope.data = {
                items : <?php echo json_encode($items) ?>
            };

            $scope.get_total_count = function(){
                var totalCount = 0;
                for(var i in $scope.data.items){
                    var count = $scope.data.items.count;
                    totalCount += count;
                }
                return totalCount;
            }

            $scope.removeItem = function(index){
                $scope.data.items.splice(index,1);
            }

        }).controller("ItemController",function($scope){
            $scope.getVendorLogo = function(){
                if(!$scope.item || !$scope.item.type)
                    return "";
                var vendorLogos = <?php echo json_encode($vendorLogos) ?>;
                return vendorLogos[$scope.item.type];
            }
        });

        app.controller("InsertFormController",function($scope){
            function init(){
                $scope.addedItems = [];
                $scope.add();
            }

            $scope.add = function(){
                $scope.addedItems.push({
                    count : 1
                });
            }

            $scope.remove = function(index){
                $scope.addedItems.splice(index,1);
            }

            $scope.getSubmitable = function(){
                for(var i in $scope.addedItems){
                    var addedItem = $scope.addedItems[i];
                    if(addedItem.count > 0 && addedItem.url){
                        return true;
                    }
                }
                return false;
            }

            init();
        });
    })();
</script>
