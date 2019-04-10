(function(){
    console.log("orderhip script");
    var $ = $__$.$;
    function getWebsiteType(){
        var arr = {
            "1688" : /1688/g,
            "taobao" : /taobao/g,
            "tmall" : /tmall/g
        };
        for(var type in arr){
            var item = arr[type];
            if(item.exec(location.host)){
                return type;
            }
        }
        return null;
    }
    
    var websiteType = getWebsiteType();
        

    function main(){
        var baseUrl = "http://orderhip.webapps.homily.vn";
        baseUrl = "http://giaodichtrungquoc.dev";
        var url = location.href;
        $("body").append('\
            <div class="orderhip-container">\
                <div class="orderhip-background"></div>\
                <div class="orderhip-content">\
                    <form id="orderhip-form" action="' + baseUrl + '/home/cart" method="post" target="_blank">\
                        <input type="hidden" name="action" value="add" />\
                        <input id="order-product-image" type="hidden" name="items[0][image]" value="" />\
                        <input type="hidden" name="redirect_url" value="' + baseUrl +'/user/create_order" />\
                        <input type="hidden" name="items[0][url]" value="' + url + '" />\
                        <div class="orderhip-inline">\
                            <img src="' + baseUrl + '/img/icon.png" style="width: 40px; height: 40px; float: left;" />\
                            <span class="orderhip-text">Chọn số lượng sản phẩm</span>\
                            <input type="number" name="items[0][count]" value="1" class="form-control" style="width: 50px; text-align: center" min="1" />\
                            <input type="text" name="items[0][description]" class="form-control" placeholder="Chú thích thêm" style="width: 300px;" />\
                            <button type="submit" class="btn btn-primary">Đặt hàng</button>\
                            <a href="' + baseUrl + '/user/create_order" target="_blank" class="btn btn-danger">Giỏ hàng</a>\
                        </div>\
                    </form>\
                </div>\
            </div>\
        ');
        $("#orderhip-container").css("width",$(window).width());
        $("#orderhip-form").submit(function(){
            var $imageElem = null;
            if(websiteType=="1688"){
                $imageElem = $("#mod-detail-bd > div.region-custom.region-detail-gallery.region-takla.ui-sortable.region-vertical > div > div > div > div > div.tab-pane > div > a > img");
            } else if(websiteType=="taobao"){
                $imageElem = $("#J_ThumbView");
            } else if(websiteType=="tmall"){
                $imageElem = $("#J_ImgBooth");
            }
            if(!$imageElem)
                return true;
            var image = $imageElem.attr("src");
            var $imageInput = $(this).find("#order-product-image");
            $imageInput.val(image);
            return true;
        });

        var suggestText = "[Orderhip] Click vào sản phẩm bạn muốn mua dưới đây &ddarr;";
        var suggestHtml = '<div style="padding: 10px 0px; font-weight: bold; font-size: 18px; color: blue;">'+  suggestText +'</div>';
        var $suggestHtml = $(suggestHtml);
        if(websiteType=="1688"){
            $suggestHtml.insertBefore("#mod-detail-bd > div.region-custom.region-detail-property.region-takla.ui-sortable.region-vertical > div.widget-custom.offerdetail_ditto_purchasing");
        } else if(websiteType=="taobao"){
            $suggestHtml.insertBefore("#J_SKU > dl:nth-child(2),#J_isSku");
        } else if(websiteType=="tmall"){
            $suggestHtml.insertBefore("#J_DetailMeta > div.tm-clear > div.tb-property > div > div.tb-key > div > div")
        }
        $suggestHtml.textillate({
            loop : true,
            in: { 
                effect: 'flash' 
            },
            out: false
        });
    }
    $(function(){
        if(websiteType=="1688"){
            if($("#mod-detail-bd > div.region-custom.region-detail-gallery.region-takla.ui-sortable.region-vertical > div > div > div > div > div.tab-pane > div > a > img").length==0)
                return;
        } else if(websiteType=="taobao"){
            if($("#J_ThumbView").length==0)
                return;
        } else if(websiteType=="tmall"){
            if($("#J_ImgBooth").length==0)
                return;
        }
        main();
    });
})();