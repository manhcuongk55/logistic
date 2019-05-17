<?php 
    $items = ProductCartHelper::getItems();
    if(isset($_GET["debug"])){
        var_dump($items);
        var_dump(json_encode($items,JSON_UNESCAPED_UNICODE));
        die();
    }
    $url = Yii::app()->request->requestUri;
    $cartUrl = $this->createUrl("/home/cart").'#step-4';
    $vendorLogos = array(
        OrderProduct::WEBSITE_TYPE_TAOBAO => "/img/vendors/taobao.png",
        OrderProduct::WEBSITE_TYPE_TMALL => "/img/vendors/tmall.png",
        OrderProduct::WEBSITE_TYPE_1688 => "/img/vendors/1688.jpg",
        OrderProduct::WEBSITE_TYPE_OTHER => "/img/vendors/other.png",
    );
?>
<?php 
Son::load("SAsset")->addExtension("bootstrap");
?>


<style>

    .p-url {
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 200px;
        overflow: hidden;
        
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

.menu_step_order li{
    width: 25%;
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
         <div class="container">




        <!-- External toolbar sample -->
        <div class="row d-flex align-items-center p-3 my-3 text-white-50" >
            <div class="col-12 col-lg-6 col-sm-12">
       
              <select id="theme_selector" class="hidden" class="custom-select col-lg-6 col-sm-12">
                    
                    <option value="arrows">arrows</option>
                   
              </select>
            </div>
            <div class="hidden" class="col-12 col-lg-6 col-sm-12">
              <div class="btn-group col-lg-6 col-sm-12"  role="group">
                  <button  class="btn btn-secondary" id="prev-btn" type="button">Trở Về</button>
                  <button  class="btn btn-secondary" id="next-btn" type="button">Tiếp Tục</button>
              </div>
            </div>
        </div>

        <!-- SmartWizard html -->
        <div id="smartwizard" ng-controller="InsertFormController">
            <ul class="menu_step_order"> 
                <li><a href="#step-1">Bước 1<br /><small>Chọn Loại Đơn Hàng Và Nhập thông tin nhà cung cấp</small></a></li>
                <li><a href="#step-2">Bước 2<br /><small>Chọn kho gửi hàng</small></a></li>
                <li><a href="#step-3">Bước 3<br /><small>Chọn hình thức báo giá vận chuyển</small></a></li>
                <li><a href="#step-4">Bước 4<br /><small>Nhập thông tin sản phẩm</small></a></li>
            </ul>

            <div>
                <div id="step-1" class="">
                  <!--  <div class="container">
                        <button type="button" class="btn btn-primary btn-lg">Đơn hàng uỷ thác trọn gói</button>
                        <button type="button" class="btn btn-primary btn-lg">Đơn hàng Thanh toán và Vận Chuyển</button>
                        <button type="button" class="btn btn-primary btn-lg">Đơn hàng vận chuyển</button>        
                   </div> -->

                   Chọn Loại Đơn Hàng :
                    <select ng-model="myVar">
                        <option value="order_1">Đơn hàng uỷ thác trọn gói
                        <option value="order_2">Đơn hàng Thanh toán và Vận Chuyển
                        <option value="order_3">Đơn hàng vận chuyển
                    </select>

                    <div ng-switch="myVar">

                        <div  ng-switch-when="order_1"> 
                                <label for="idLinkNhaCungCap">Link Nhà Cung Cấp</label>
                                <input type="text" id="idLinkNhaCungCap" name="LinkNhaCungCap" ng-model="addedInfo.LinkNhaCungCap" placeholder="Link Hoặc Tên Nhà Cung Cấp">

                                <label for="idNhaCungCap">Tên Nhà Cung Cấp</label>
                                <input type="text" id="idNhaCungCap" name="NhaCungCap" ng-model="addedInfo.NhaCungCap" placeholder="Link Hoặc Tên Nhà Cung Cấp">

                                <label for="idsdt">Số Điện Thoại</label>
                                <input type="text" id="idsdt" name="sdt" ng-model="addedInfo.sdt" placeholder=" Số Điện Thoại">
                        </div>

                        <div ng-switch-when="order_2">
                                <label for="idLinkNhaCungCap">Link Nhà Cung Cấp</label>
                                <input type="text" id="idLinkNhaCungCap" name="items[{{$index}}][LinkNhaCungCap]" ng-model="addedInfo.LinkNhaCungCap" placeholder="Link Hoặc Tên Nhà Cung Cấp">

                                <!-- Tao 1 textbox set copy du lieu tu oject addedItem tro den value={{addedItem.LinkNhaCungCap}}  -->
                                <!-- <input type="text" id="idNhaCungCap1" name="NhaCungCap1" placeholder="Link Hoặc Tên Nhà Cung Cấp" value={{addedItem.LinkNhaCungCap}}> -->
                                {{addedItem}}
                                <label for="idNhaCungCap">Tên Nhà Cung Cấp</label>
                                <input type="text" id="idNhaCungCap" name="NhaCungCap" ng-model="addedInfo.NhaCungCap" placeholder="Link Hoặc Tên Nhà Cung Cấp">

                                <label for="idsdt">Số Điện Thoại</label>
                                <input type="text" id="idsdt" name="sdt" ng-model="addedInfo.sdt" placeholder=" Số Điện Thoại">
                        </div>
                        <div ng-switch-when="order_3">
                            <label for="fname">Mã Vận Đơn </label>
                            <input type="text"  placeholder="Mã Vận Đơn">
                            <label for="fname">Số Kiện</label>
                            <input type="text"  placeholder="Số Kiện">
                            <label for="fname">Link Nhà Cung Cấp</label>
                            <input type="text"  placeholder="Link Hoặc Tên Nhà Cung Cấp">
                            <label for="fname">Tên Nhà Cung Cấp</label>
                            <input type="text" placeholder="Link Hoặc Tên Nhà Cung Cấp">

                            <label for="lname">Số Điện Thoại</label>
                            <input type="text"  placeholder="Số Điện Thoại"> 
                        </div>

                    </div>
                </div>
                     
                <div id="step-2" class="">
                    <label for="warehouse">Chọn Kho Gửi Hàng</label>

                    <select name="Tenkho" ng-model="addedInfo.Tenkho">
                        <option value="" disabled selected>---Qúy khách vui lòng chọn kho gửi hàng ---</option>
                        <!-- not selected / blank option -->
                        <option value="Bằng Tường1"> Bằng Tường1 </option>
                        <!-- interpolation -->
                        <option value="Bằng Tường2"> Bằng Tường2 </option>
                    </select>

                    <!-- <select ng-options="size as size for size in sizes " ng-model="default_khohang" > </select> -->
                 
                    {{addedInfo }}    
                    {{addedItems}}
                </div>
                <div id="step-3" class="">
                   
                    <label for="country">Chọn Hình Thức Báo Giá Sản Phẩm</label>
                        
                        <!-- <select ng-options="size1 as size1 for size1 in size1s " ng-model="default_baogiathue" > </select> -->
                        <select name="HinhThucBaoGia" ng-model="addedInfo.HinhThucBaoGia">
                            <option value="" disabled selected>---Qúy khách vui lòng chọn hình thức báo giá ---</option>
                            <!-- not selected / blank option -->
                            <option value="Giá bao gồm thuế sản phẩm ">Giá bao gồm thuế sản phẩm </option>
                            <!-- interpolation -->
                            <option value="Giá không bao gồm thuế sản phẩm">Giá không bao gồm thuế sản phẩm</option>
                        </select>
                     {{addedItem}}

                </div>
               
                <div id="step-4" class="">
                   <div class="row">
                   
                        <div class="col-md-8" >
                            <form action="<?php echo $cartUrl ?>" method="POST">
                                <input type="hidden" name="action" value="add" />
                                <input type="hidden" name="redirect_url" value="<?php echo $url ?>" />
                                <div class="row mg-t5" ng-repeat="addedItem in addedItems">
                                    <div class="col-md-8">
                                        <input type="text" class="form-control input-sm" name="items[{{$index}}][url]" ng-model="addedItem.url" placeholder="Đường dẫn sản phẩm {{$index+1}}" />
                                                
                                        <!-- Tao the input an de luu du lieu TenKho -->
                                        <input type="text" id="idNhaCungCap" name="items[{{$index}}][Tenkho]"  
                                        ng-model="addedItem.Tenkho" style="display: none;" >

                                        <!-- Tao the input an de luu du lieu LinkNhaCungCap -->
                                        <input type="text"  id="idLinkNhaCungCap" name="items[{{$index}}][LinkNhaCungCap]" ng-model="addedItem.LinkNhaCungCap" placeholder="Link Hoặc Tên Nhà Cung Cấp" style="display: none;">
                                      
                                        <textarea class="form-control input-sm mg-t5" rows="2" name="items[{{$index}}][description]" ng-model="addedItem.description" placeholder="Ghi chú"></textarea>
                                        {{addedItem}} 
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
    
                  
                </div>
            </div>
        </div>


    </div>



                
            </div>
        </div>
       
	</div>


{{addedItem}} 

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
                                    
                                        <input type="hidden" ng-value="item.LinkNhaCungCap" ng-attr-name="items[{{$index}}][LinkNhaCungCap]" />
                                        {{item}}
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
                                </div>HinhThucBaoGia
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
                        tất việc thêm sản phẩm, bạn chỉ cần click vào nút Gửi đơn hàng và đợi CTV báo giá.<br>
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

                        <!--Them vao-->
                        <input type="hidden" value="<?php echo $item->LinkNhaCungCap ?>" name="items[<?php echo $i ?>][LinkNhaCungCap]"/>
                    <?php endforeach; ?>
                </form>
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
                $scope.addedInfo = {
                    LinkNhaCungCap: "",
                    NhaCungCap: "",
                    Tenkho: "",
                    HinhThucBaoGia: "",
                    sdt: ""
                }
                $scope.add();
            }   

            $scope.$watch("addedInfo", function(newValue, oldValue){        //Watch changes on addedInfo to update everyobject in addedItems array
                if (newValue != oldValue) {
                    for (var i = 0; i < $scope.addedItems.length; i++) {
                        $scope.addedItems[i].LinkNhaCungCap = $scope.LinkNhaCungCap;
                        $scope.addedItems[i].NhaCungCap = $scope.addedInfo.NhaCungCap;
                        $scope.addedItems[i].Tenkho = $scope.addedInfo.Tenkho;
                        $scope.addedItems[i].HinhThucBaoGia = $scope.addedInfo.HinhThucBaoGia;
                        $scope.addedItems[i].sdt = $scope.addedInfo.sdt;
                        
                    }
                }
            }, true);

            $scope.add = function(){
                $scope.addedItems.push({
                    LinkNhaCungCap: $scope.addedInfo.LinkNhaCungCap,
                    idNhaCungCap: $scope.addedInfo.idNhaCungCap,
                    Tenkho: $scope.addedInfo.Tenkho,
                    HinhThucBaoGia: $scope.addedInfo.HinhThucBaoGia,
                    sdt: $scope.addedInfo.sdt,
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

            

            //tao combobox chon kho hang

            $scope.sizes = [ "Bằng Tường1", "Bằng Tường2"];
            $scope.default_khohang = $scope.sizes[0];//tao gia tri mac dinh cho combobox kho hang
           // Tao thuoc tich trong combobox Hinh Thuc Bao Gia van chuyen ( co thue hay khong?)
            $scope.size1s = [ "Giá bao gồm thuế sản phẩm ", "Giá không bao gồm thuế sản phẩm"];
            $scope.default_baogiathue = $scope.size1s[0]
            

        init();// goi ham init
});

    })();
     $(document).ready(function(){

            // Step show event
            $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
               //alert("You are on step "+stepNumber+" now");
               if(stepPosition === 'first'){
                   $("#prev-btn").addClass('disabled');
               }else if(stepPosition === 'final'){
                   $("#next-btn").addClass('disabled');
               }else{
                   $("#prev-btn").removeClass('disabled');
                   $("#next-btn").removeClass('disabled');
               }
            });

            // Toolbar extra buttons
            var btnFinish = $('<button></button>').text('Finish')
                                             .addClass('btn btn-info')
                                             .on('click', function(){ alert('Finish Clicked'); });
            var btnCancel = $('<button></button>').text('Cancel')
                                             .addClass('btn btn-danger')
                                             .on('click', function(){ $('#smartwizard').smartWizard("reset"); });


            // Smart Wizard
            $('#smartwizard').smartWizard({
                    selected: 0,
                    theme: 'default',
                    transitionEffect:'fade',
                    showStepURLhash: true,
                    toolbarSettings: {//toolbarPosition: 'both',
                                      toolbarButtonPosition: 'end',
                                      //toolbarExtraButtons: [btnFinish, btnCancel]
                                    }
            });


            // External Button Events
            $("#reset-btn").on("click", function() {
                // Reset wizard
                $('#smartwizard').smartWizard("reset");
                return true;
            });

            $("#prev-btn").on("click", function() {
                // Navigate previous
                $('#smartwizard').smartWizard("prev");
                return true;
            });

            $("#next-btn").on("click", function() {
                // Navigate next
                $('#smartwizard').smartWizard("next");
                return true;
            });

            $("#theme_selector").on("change", function() {
                // Change theme
                $('#smartwizard').smartWizard("theme", $(this).val());
                return true;
            });

            // Set selected theme on page refresh
            $("#theme_selector").change();
        });

     
</script>


