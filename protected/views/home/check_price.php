<div class="breadcrumb-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumbs">
                    <ul>
                        <li class="home"><a href="<?php echo $this->createUrl("/home") ?>"><?php l_("home","Trang chủ") ?></a><span>/ </span></li>
                        <li class="home"><a href="">Check giá</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    #main-table input, #main-table [ng-bind], #main-table *  {
        font-weight: bold !important;
    }
    .borderless td, .borderless th {
        border: none !important;
    }
</style>
<div class="container mg-t10 mg-b10" ng-app="app" ng-controller="CheckPriceController">
    <h1 class="text-center">Check giá sản phẩm về Việt Nam</h1>
    <hr/>
    <table id="main-table" class="table borderless">
        <tbody>
            <tr>
                <td>Nhập thông tin tính cước</td>
                <td colspan="3"></td></tr>
            <tr class="text-info">
                <td>Tiền hàng (NDT)</td>
                <td>Tiền ship nội địa tq (NDT)</td>
                <td>Cân nặng (kg)</td>
                <td>Kho</td>
            </tr>
            <tr class="text-danger">
                <td><input type="number" class="form-control" ng-model="data.productsPrice" /></td>
                <td><input type="number" class="form-control" ng-model="data.internalTransportPrice" /></td>
                <td><input type="number" class="form-control" ng-model="data.weight" /></td>
                <td>
                    <select ng-model="data.store" class="form-control">
                        <option value="HN">Hà Nội</option>
                        <option value="HCM">Hồ Chí Minh</option>
                    </select>
                </td>
            </tr>
            <tr class="text-info">
                <td>Cước phí áp dụng</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td>Tỉ giá</td>
                <td>Phí dịch vụ</td>
                <td>Phí vận chuyển</td>
                <td></td>
            </tr>
            <tr class="text-danger">
                <td><span ng-bind="displayMoney(data.exchangePrice)"></span> VNĐ / NDT</td>
                <td><span ng-bind="displayMoney(getServicePrice())"></span> VNĐ</td>
                <td><span ng-bind="displayMoney(getTransportPrice())"></span> VNĐ</td>
                <td></td>
            </tr>
            <tr>
                <td>Tổng tiền về kho VN</td>
                <td colspan="3"></td>
            </tr>
            <tr class="text-danger">
                <td colspan="4"><span ng-bind="displayMoney(getTotalPrice())"></span> VNĐ</td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    (function(){
        var app = $__$.angular.init("app");
        app.controller("CheckPriceController",function($scope){
            var data = {
                productsPrice: 50.00,
                internalTransportPrice: 10.00,
                weight: 10.00,
                store: "HN",
            }
            data.exchangePrice = <?php echo Util::param2("setting","vnd_ndt_rate") ?>

            $scope.data = data

            $scope.getServicePrice = function(){
                var productsPriceVND = data.productsPrice * data.exchangePrice
                if(productsPriceVND > 30000000){
                    return "THỎA THUẬN"
                } else if(productsPriceVND > 15000000){
                    return productsPriceVND * 0.03
                } else if(productsPriceVND > 5000000){
                    return productsPriceVND * 0.04
                } else {
                    return productsPriceVND * 0.05
                }
            }

            $scope.getTransportPrice = function(){
                if(data.weight > 100){
                    return "THỎA THUẬN"
                } else if(data.weight > 50){
                    var rate = data.store == "HN" ? 15000 : 18000
                    return data.weight * rate
                } else if(data.weight > 20){
                    var rate = data.store == "HN" ? 20000 : 23000
                    return data.weight * rate
                } else {
                    var rate = data.store == "HN" ? 25000 : 28000
                    return data.weight * rate
                }
            }

            $scope.getTotalPrice = function(){
                var servicePrice = $scope.getServicePrice()
                var transportPrice = $scope.getTransportPrice()
                var productsPriceVND = data.productsPrice * data.exchangePrice
                var totalPrice = (data.productsPrice + data.internalTransportPrice) * data.exchangePrice
                if(!isNaN(servicePrice) && !isNaN(transportPrice)){
                    return totalPrice + servicePrice + transportPrice
                } else if(!isNaN(servicePrice) && isNaN(transportPrice)){
                    return $scope.displayMoney((totalPrice + servicePrice)) + " + [Phí cân nặng thỏa thuận]"
                } else if(isNaN(servicePrice) && !isNaN(transportPrice)){
                    return $scope.displayMoney((totalPrice + transportPrice)) + " + [Phí DV thỏa thuận]"
                } else {
                    return $scope.displayMoney(totalPrice) + " + [Phí DV thỏa thuận] + [Phí cân nặng thỏa thuận]"
                }
            }

            $scope.displayMoney = function(money){
                if(isNaN(money)){
                    return money
                }
                var money = parseFloat(money);
                var moneyStr = formatMoney(money, 0, ".", ",");
                return moneyStr
            }

            function formatMoney (money, c, d, t) {
                var n = money,
                    c = isNaN(c = Math.abs(c)) ? 0 : c,
                    d = d == undefined ? "." : d,
                    t = t == undefined ? "," : t,
                    s = n < 0 ? "-" : "",
                    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
                    j = (j = i.length) > 3 ? j % 3 : 0;
                return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
            }      
        })
    })()
</script>