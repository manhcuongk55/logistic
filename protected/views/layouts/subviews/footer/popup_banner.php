<?php
    Son::load("SAsset")->addExtension("featherlight");
    $config = Util::param2("popup_banner");
?>
<script>
(function(){
    function showPopup(){
        var content = '<a href="<?php echo $config["url"] ? $config["url"] : "javascript:;" ?>" title="<?php echo $config["title"] ?>"><img src="<?php echo $config["popup_banner"] ?>" /></a>';
        $.featherlight(content, {

        });
        Cookies.set("popup-banner-time",new Date().getTime(),{
            expires : 3 // 3 days
        });
    }
    $(function(){
        var lastShowTime = Cookies.get("popup-banner-time");
        if(!lastShowTime){
            showPopup();
        }
    });

})();
</script>
