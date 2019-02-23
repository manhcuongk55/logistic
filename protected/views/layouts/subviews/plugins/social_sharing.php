<?php if(false): ?>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-57826a43c39f67d3"></script>
<?php endif; ?>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<div id="social-box" style="position: fixed; top: 35%; left: 0px; padding: 2px; border-top-radius: 5px; border-right-radius: 5px; background-color: #fff; z-index: 10; display: none;">
	<div>
		<div class="fb-like" data-layout="box_count" data-action="like" data-show-faces="true"></div>
	</div>
	<div style="margin-top: 5px;">
		<!-- Place this tag where you want the +1 button to render. -->
		<div class="g-plusone" data-size="tall"></div>
	</div>
	<div style="margin-top: 5px; display: none">
		<a href="https://twitter.com/share" class="twitter-share-button" data-show-count="false">Tweet</a><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
	</div>
	<div style="margin-top: 5px">
		<script type="text/javascript" async defer src="//assets.pinterest.com/js/pinit.js"></script>
		<a href="https://www.pinterest.com/pin/create/button/" data-pin-count="above">
		    <img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" />
		</a>
	</div>
</div>
<script>
	$(function(){
		var $socialBox = $("#social-box .fb-like");
		var timer = setInterval(function(){
			var $iframe = $socialBox.find("iframe");
			if(!$iframe.length)
				return;
			$iframe.load(function(){
				$("#social-box").show();
				clearInterval(timer);
			});
		},100);
		
	});
</script>