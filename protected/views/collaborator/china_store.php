<div class="content" ng-app="app" ng-controller="AppController">
    <div class="z-depth-1 pd-a10">
        <div class="row account-section-title" style="border-top: none">
            <div class="col-md-6">
                <h2 class="">
                    <b>Về kho Trung Quốc</b>
                </h2>
            </div>
            <div class="col-md-4 col-md-offset-2 text-right">
                <input type="text" id="input-scanned-code" class="form-control" ng-model="scannedCode" ng-enter="onCodeScanned(scannedCode); scannedCode = '';" placeholder="Mã vận đơn" />
            </div>
        </div>
        <div class="row account-section-title" style="border-top: none">
            <div class="col-md-4 col-md-offset-8 text-right">
                <input type="text" id="input-block-id" class="form-control" ng-disabled="!hasItemSelected()" ng-model="blockID" ng-enter="onBlockID(blockID);" placeholder="Mã kiện hàng" />
            </div>
        </div>
        <div>
            <div ng-if="unknownCodes.length">
                <h4>Danh sách mã đơn hàng không tìm thấy:</h4>
                <div>
                    <span class="label label-warning" ng-repeat="code in unknownCodes" ng-bind="code" style="margin-right:5px;"></span>
                </div>
            </div>
            <div class="table-data-wrapper">
                <div class="table-account-data table-responsive table-scrollable">
                    <table class="table table-bordered admin-table">
                        <thead>
                            <tr>
                                <td>
                                </td>
                                <td>
                                    Mã vận đơn
                                </td>
                                <td>
                                    Loại
                                </td>
                                <td>
                                    Mã đơn hàng
                                </td>
                                <td>
                                    Mã khách hàng
                                </td>
                                <td>
                                    Mã kiện hàng
                                </td>
                                <td>
                                    Cân nặng<br/>
                                    (<span class="bold" ng-bind="totalWeight()"></span> kg)
                                </td>
                                <td>
                                    Số khối<br/>
                                    (<span class="bold" ng-bind="totalVolume()"></span> m3)
                                </td>
                                <td>
                                    Số kiện<br/>
                                    (<span class="bold" ng-bind="totalBlock()"></span>)
                                </td>
                                <td>
                                    Ghi chú
                                </td>
                                <td>
                                    Ngày về Trung Quốc
                                </td>
                                <td>

                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="item in sort(items)" ng-init="code = item.delivery_order_code">
                                <td>
                                    <input type="checkbox" ng-model="selectedCodes[code]" ng-disabled="codeStatuses[code]!='success' || codeUpdating[code]" />
                                </td>
                                <td class="">
                                    <div>
                                        <span class="text-info" ng-if="codeStatuses[code]=='pending'" ng-bind="code"></span>
                                        <span class="text-success" ng-if="codeStatuses[code]=='success'" ng-bind="code"></span>
                                        <a href="javascript:;" ng-click="onCodeScanned(code,true)" class="text-danger" ng-if="codeStatuses[code]=='error'" ng-bind="code"></a>
                                        <a href="javascript:;" ng-click="onCodeScanned(code,true)" style="color: #ddd" ng-if="!codeStatuses[code]" ng-bind="code"></a>
                                    </div>
                                    <div ng-bind="errors[code]"></div>
                                </td>
                                <td>
                                    <span class="label label-primary" ng-bind="item.type==1 ? 'Order' : 'Ký gửi'"></span>
                                </td>
                                <td>
                                    <span ng-bind="item.order_id"></span>
                                </td>
                                <td>
                                    <span ng-bind="item.user_id"></span>
                                </td>
                                <td>
                                    <input type="text" class="form-control" ng-disabled="codeStatuses[code]!='success'" ng-model="item.block_id" />
                                </td>
                                <td>
                                    <input type="number" class="form-control" ng-disabled="codeStatuses[code]!='success'" ng-model="item.total_weight" />
                                </td>
                                <td>
                                    <input type="number" class="form-control" ng-disabled="codeStatuses[code]!='success'" ng-model="item.total_volume" />
                                </td>
                                <td>
                                    <input type="number" class="form-control" ng-disabled="codeStatuses[code]!='success'" ng-model="item.num_block" />
                                </td>
                                <td>
                                    <textarea class="form-control" ng-disabled="codeStatuses[code]!='success'" ng-model="item.note"></textarea>
                                </td>
                                <td>
                                    <span></span>
                                </td>
                                <td>
                                    <span ng-bind="item.china_delivery_time | dateTime"></span>
                                </td>
                                <!-- <td>
                                    <span ng-bind="item.created_time | dateTime"></span>
                                </td> -->
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" ng-disabled="codeStatuses[code]!='success' || codeUpdating[code] || (!item.block_id && !item.total_weight && !item.num_block)" ng-click="updateItem(item.delivery_order_code)">Cập nhật</button>
                                    <a ng-attr-href="{{ item.image_url }}" target="_blank" title="Ảnh sản phẩm" class="btn btn-sm btn-primary" ng-if="item.image_url"><i class="fa fa-external-link"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function(){
        var app = $__$.angular.init("app");

        app.filter("dateTime", function () {
            return function (unixTime, dateFormat) {
                if (!unixTime) {
                    return "N/A"
                }
                if (!dateFormat) {
                    dateFormat = "YYYY-MM-DD hh:mm:ssa"
                }
                var time = moment(unixTime, "X")
                return time.format(dateFormat)
            }
        })

        app.controller("AppController",["$scope","$http","$timeout",function($scope,$http,$timeout){
            $scope.errors = {}
            $scope.unknownCodes = []
            $scope.codeStatuses = {}
            $scope.codeUpdating = {}
            $scope.selectedCodes = {}
            var items = {};
            $scope.items = []

            function init(){
                $timeout(function(){
                    $("#input-scanned-code").focus()
                })
            }

            function confirmShopDeliveryOrder(code,callback){
                $http.post("/collaborator/confirm_china_shop_delivery_order?delivery_order_code=" + code).then(function(response){
                    var data = response.data
                    callback(data)
                })
            }

            $scope.onCodeScanned = function(code,willConfirm){
                if(willConfirm){
                    if(!confirm("Bạn có muốn xác nhận mã vận đơn #" + code + "?")){
                        return
                    }
                }

                confirmShopDeliveryOrder(code,function(data){
                    var item = data.Data
                    if(item){
                        var oldItem = items[item.delivery_order_code]
                        if(oldItem){
                            item.index = oldItem.index
                            $scope.items[oldItem.index] = item
                        } else {
                            item.index = $scope.items.length
                            $scope.items.push(item)
                        }
                        items[item.delivery_order_code] = item
                    } else {
                        $scope.unknownCodes.push(code)
                        onUnknownCodeFound(code)
                    }

                    if(data.Error){
                        if(item){
                            $scope.errors[item.delivery_order_code] = data.Message
                            $scope.codeStatuses[item.delivery_order_code] = "error"
                        }
                    } else {
                        delete $scope.errors[item.delivery_order_code]
                        $scope.codeStatuses[item.delivery_order_code] = "success"
                    }
                })
            }

            $scope.updateItem = function(code){
                var item = items[code]
                $scope.codeUpdating[code] = true
                $http.post("/collaborator/update_item?id=" + item.id,{
                    block_id:item.block_id,
                    total_weight:item.total_weight,
                    total_volume:item.total_volume,
                    num_block:item.num_block,
                    note:item.note,
                }).then(function(response){
                    var data = response.data
                    if(data.Error){
                        $scope.errors[item.id] = data.Message;
                    } else {
                        delete($scope.errors[item.id])
                    }
                    $scope.codeUpdating[code] = false
                })
            }

            function getSelectedItems(){
                var selectedItems = []
                for(var code in $scope.selectedCodes){
                    if(!$scope.selectedCodes[code]){
                        continue
                    }
                    selectedItems.push(items[code])
                }
                return selectedItems
            }

            $scope.hasItemSelected = function(){
                for(var code in $scope.selectedCodes){
                    if($scope.selectedCodes[code]){
                        return true
                    }
                }
                return false
            }

            $scope.onBlockID = function(blockID){
                var selectedItems = getSelectedItems()
                for(var i in selectedItems){
                    var item = selectedItems[i]
                    item.block_id = blockID
                    $scope.updateItem(item.delivery_order_code)
                }
            }

            $scope.sort = function(items){
                if(!items){
                    return []
                }
                return items.slice(0).sort(function(item1, item2){
                    if($scope.codeStatuses[item1.delivery_order_code] && $scope.codeStatuses[item2.delivery_order_code]){
                        if($scope.selectedCodes[item1.delivery_order_code] && !$scope.selectedCodes[item2.delivery_order_code]){
                            return -1;
                        } else if(!$scope.selectedCodes[item1.delivery_order_code] && $scope.selectedCodes[item2.delivery_order_code]){
                            return 1;
                        }
                        return item2.china_delivery_time - item1.china_delivery_time
                    } else if($scope.codeStatuses[item1.delivery_order_code]){
                        return -1
                    } else if($scope.codeStatuses[item2.delivery_order_code]){
                        return 1
                    }
                    return 0
                })
            }

            $scope.totalWeight = function(){
                var total = 0
                for(var code in $scope.selectedCodes){
                    var item = items[code]
                    if(item.total_weight){
                        total += item.total_weight
                    }
                }
                return total
            }

            $scope.totalVolume = function(){
                var total = 0
                for(var code in $scope.selectedCodes){
                    var item = items[code]
                    if(item.total_volume){
                        total += item.total_volume
                    }
                }
                return total
            }

            $scope.totalBlock = function(){
                var total = 0
                for(var code in $scope.selectedCodes){
                    var item = items[code]
                    if(item.num_block){
                        total += item.num_block
                    }
                }
                return total
            }

            function onUnknownCodeFound(code){
                if(!confirm("Bạn có muốn thêm mã vận đơn " + code + " vào danh sách mã vận đơn thừa không?")){
                    return
                }
                location.href = "/collaborator/unknown_shop_delivery_order_form?delivery_order_code=" + code
            }

            init()
        }])
    })()
</script>