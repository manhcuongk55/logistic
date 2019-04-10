<?php
Yii::import("ext.Son.super-modules.admin-table.*");
class OrderProductList extends AdminTableList {
    var $orderId;
    protected function getConfig(){
        $arr = array(
            "onRun" => function($list){
            },
            "fields" => array(
                "__item" => array(
                    "order" => true
                ),
                "id" => array(
                ),
                "created_time" => array(
                    "displayType" => "time_format",
                    "displayConfig" => array(
                        "format" => "d-m-Y h:i:s"
                    ),
                    "inputType" => "timestamp_datetimepicker",
                    "advancedSearchInputType" => "timestamp_range_datetimepicker",
                    "exportType" => "timestamp"
                ),
                "updated_time" => array(
                    "displayType" => "time_format",
                    "displayConfig" => array(
                        "format" => "d-m-Y h:i:s"
                    ),
                    "inputType" => "timestamp_datetimepicker",
                    "advancedSearchInputType" => "timestamp_range_datetimepicker",
                    "exportType" => "timestamp"
                ),
                "active" => array(
                    "inputType" => "checkbox_button",
                    "displayType" => "checkbox_label",
                    "advancedSearchInputType" => true,
                    "advancedSearchConfig" => array(
                        "triggerType" => "changed",
                    ),
                    "exportType" => "boolean"
                ),
                "order_id" => array(
                    "inputType" => "hidden",
                    "default" => Input::get("order_id"),
                    "displayInput" => false
                ),
                "shop_delivery_order_id" => array(),
                "url" => array(),
                "website_type" => array(
                    "inputType" => "dropdown_model",
                    "inputConfig" => array(
                        "modelClass" => "OrderProduct",
                        "attr" => "website_type",
                        "inputDropdown" => false
                    ),
                    "displayType" => "label_model",
                    "displayConfig" => array(
                        "modelClass" => "OrderProduct",
                        "attr" => "website_type"
                    ),
                    "advancedSearchInputType" => true
                ),
                "name" => array(),
                "count" => array(),
                "vietnamese_name" => array(),
                "image" => array(
                    "displayType" => "image",
                ),
                "ordered_count" => array(
                    "inputType" => "number"
                ),
                "price" => array(
                    //"inputType" => "money_input",
                    "displayType" => "money_display"
                ),
                "web_price" => array(
                    "inputType" => "money_input",
                    "displayType" => "money_display"
                ),
                "real_price" => array(
                    "inputType" => "money_input",
                    "displayType" => "money_display"
                ),
                "color" => array(),
                "description" => array(
                    "inputType" => "textarea"
                ),
                "collaborator_note" => array(
                )
            ),
            "actions" => array(
                "action" => array(
                    "update" => array(
                        "vietnamese_name", "url", "count", "description"
                    ),
                    "delete" => true,
                    "insert" => array(
                        "vietnamese_name", "url", "count", "description", "order_id"
                    ),
                    "data" => array(
                        "search" => array(
                            "id", "name", "vietnameseorder_id_name"
                        ),
                        "advancedSearch" => true,
                        "order" => true,
                        "limit" => true,
                        "offset" => true,
                        "page" => true,
                        "export" => false,
                    ),
                ),
                "actionTogether" => array(
                    "deleteTogether" => false	
                ),
                "extendedAction" => array(
                ),
                "extendedActionTogether" => array(
                ),
            ),
            "model" => array(
                "class" => "OrderProduct",
                "primaryField" => "id",
                "conditions" => array(),
                "with" => array(
                    
                ),
                "addedCondition" => array(),
                "defaultQuery" => array(
                    "orderBy" => "id",
                    "orderType" => "desc",
                    "limit" => 20,
                    "offset" => 0,
                    "search" => "",
                    "advancedSearch" => array(
                        "active" => 1
                    ),
                    "page" => 1
                ),
                "dynamicInputs" => array(
                    "order_id" => function($criteria,$value){
                        $criteria->compare("t.order_id",$value);
                    },
                ),
                "preloadData" => false,
                "forms" => array(),
                "insertScenario" => "insert_from_user",
                "updateScenario" => "update_from_user",
            ),
            "view" => array(
                "itemSelectable" => array(
                    "type" => "checkbox",
                    "selectedClass" => "active"
                ),
                "limitSelectList" => array(10,20,30,40),
                "trackUrl" => true,
                "viewPath" => array(
                    "list" => "webroot.themes.giaodichtrungquoc.views.admin.full_page_list",
                    "item" => "webroot.themes.giaodichtrungquoc.views.admin.item",
                )
            ),
            "pagination" => array(
                "first" => true,
                "back" => true,
                "next" => true,
                "last" => true,
                "count" => 5
            ),
            "admin" => array(
                "title" => "Danh sách sản phẩm đơn hàng",
                "columns" => array(
                    "order_id", "price", "count", "image"
                ),
                "detail" => array(
                    "id", "created_time", "updated_time", "active", "order_id", "name", "url", "website_type", "price", "count", "ordered_count", "vietnamese_name", "image", "web_price", "color", "description"
                ),
                "action" => true,
                "actionButtons" => array(
                    array("link",function($item){
                        $url = $item->url;
                        if(!$url){
                            return array(
                                "disabled" => true
                            );
                        }
                        return array(
                            "content" => '<i class="fa fa-external-link"></i>',
                            "newTab" => true,
                            "href" => $url,
                            "title" => "Đường dẫn sản phẩm"
                        );
                    })
                )
            ),
            "mode" => "jquery",
            "baseUrl" => Util::controller()->createUrl("/user/order_product_list") . "?"
        );

        return $arr;
    }

    public function __construct($orderId=null){
        $this->orderId = $orderId;
        parent::__construct();
        if($this->orderId){
            $this->setDynamicInput("order_id",$orderId);
        }
        Son::loadFile("ext.Son.super-modules.admin-table.code.action_button_register");
    }
}