<div class="account-menu">
	<ul>
		<li>
			<a href="#" id="notifications-dropdown">
				Thông báo (<?php echo $this->getUser()->unread_notification_count ?>) <i class="fa fa-bell"></i>
			</a>
			<?php $html->begin(); ?>
			<ul class="account-dropdown" list-items style="min-width: 300px; text-align: left">
				<?php $html->resetLoop(); while($html->loop()): ?>
					<?php $html->renderCurrentItem() ?>
				<?php endwhile; ?>
			</ul>
			<?php $html->end(); ?>
		</li>
	</ul>
</div>

<script>
(function(){
	var read = false;
	var listManager = SList.ListManager.getInstance();
	var listAlias = "<?php echo $list->alias ?>";
	var ids = [];
	listManager.listen(listAlias,"list-items-updated",function(obj){
		var $items = obj.$items;
		ids = [];
		$items.each(function(){
			if(parseInt($(this).data("is_read"))==1){
				return;
			}
			ids.push($(this).data("id"))
		});
	});
	$(function(){
		$("#notifications-dropdown").click(function(e){
			//var $menu = $(this).parent().find(".account-dropdown");
			//$menu.toggle();
			doRead();
			return $__$.prevent(e);
		});
	});

	function doRead(){
		if(read)
			return;
		read = true;
		$.ajax({
			type : "post",
			url  : "/home/read_notifications",
			data : {
				ids : ids
			},
			success : function(){

			}
		});
	}
})();
</script>