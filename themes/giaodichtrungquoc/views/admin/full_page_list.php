<?php 
	Son::load("SAsset")->addExtension("data-tables");
?>
<style>

	.table-account-data {
		margin-bottom: 5px;
	}

	.admin-table {
		margin-bottom: 0px;
	}

	.list-th-checkbox {
		width:50px;
	}

	.admin-table thead th {
		font-weight: bold;
	}

	.admin-table thead th [list-order-display] {
		/*display:none;*/
	}

	[template-order] {
		padding-right:30px !important;
	}

	.admin-table th .list-title {
		overflow:hidden;
		text-overflow:ellipsis;
	}
	
	.table-scrollable {
		overflow:visible !important;
		overflow-x: auto !important;
		background: rgb(253,253,253);
	}

	.admin-table td, .admin-table th {
		overflow-x: hidden !important;
		text-overflow: ellipsis;
	}

	.admin-table thead > tr:not(:first-child) > th{
		overflow: visible !important;
	}

	.admin-table td, .admin-table th {
		white-space: nowrap;
	}

	.admin-table .form-group > * {
		white-space: normal;
	}


</style>
<?php $html->begin(); ?>
<div class="content">
	<div class="z-depth-1 pd-a10">
		<!-- BEGIN TABLE TITLE -->
		<?php $html->renderHtmlAt("begin_page"); ?>
		<div class="row account-section-title" style="border-top: none">
			<div class="col-md-6">
				<h2 class="">
					<b>
						<?php echo $list->config["admin"]["title"] ?>
					</b>
				</h2>
			</div>
			<div class="col-md-6 text-right">
				<?php $html->renderHtmlAt("table_actions") ?>
				<?php if($html->hasInsertForm()): ?>
					<button type="button" id="btn-trigger-form-insert" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> Thêm mới</button>
				<?php endif; ?>
				<?php if($html->hasActionExport()): ?>
					<div class="btn-group">
						<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-download"></i>
							Truy xuất
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<?php $html->renderActionExport(); ?>
						</ul>
					</div> 
				<?php endif; ?>
				<?php if($html->hasActionTogether()): ?>
					<div class="btn-group">
						<button type="button" class="btn green dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-cog"></i>
							With selected
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<?php if($html->hasActionDeleteTogether()): ?>
								<?php $html->renderActionDeleteTogether(); ?>
							<?php endif; ?>
							<?php if($html->hasExtendedActionsTogether()): ?>
								<?php $html->renderExtendedActionsTogether(); ?>
							<?php endif; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-9 col-sm-12">
				<?php if($list->config["actions"]["action"]["data"]["limit"]): ?>
					<?php $html->renderLimitInput(array(
						"input-dropdown" => ""
					)) ?> mục trên trang
				<?php endif; ?>
			</div>
			<div class="col-md-3 col-sm-12 text-right">
				<?php if($list->config["actions"]["action"]["data"]["search"]): ?>
					<?php $html->renderSearchInput(array(
						"class" => "form-control",
						"placeholder" => "Tìm kiếm"
					)); ?>
				<?php endif; ?>
			</div>
		</div>
		<!-- END TABLE TITLE -->
		
		<!-- BEGIN TABLE DATA -->
		<div class="table-data-wrapper">
			<div class="table-account-data table-responsive table-scrollable">
				<table class="table table-bordered admin-table">
					<thead>
						<tr>
							<?php if($html->willUseCheckbox()): ?>
								<th class="list-th-checkbox">
									<?php $html->renderCheckboxSelectAll(array(
										"input-checkbox-button" => ""
									)); ?>
								</th>
							<?php endif; ?>
							<?php foreach($list->config["admin"]["columns"] as $attr): ?>
								<th <?php if($html->hasOrderFor($attr)): ?> template-order="<?php echo $attr ?>" <?php endif; ?> class="list-th-attr-<?php echo $attr ?>">
									<?php $html->renderOrderFor($attr); ?>
								</th>
							<?php endforeach; ?>
							<?php if($list->config["admin"]["action"]): ?>
								<th class="list-th-actions">Actions</th>
							<?php endif; ?>
						</tr>
						<?php if($list->config["actions"]["action"]["data"]["advancedSearch"]): ?>
							<tr>
								<?php if($html->willUseCheckbox()): ?>
									<th></th>
								<?php endif; ?>
								<?php foreach($list->config["admin"]["columns"] as $attr): ?>
									<th>
										<?php if($html->hasAdvancedSearchFor($attr)): ?>
											<?php $html->renderAdvancedSearchInputFor($attr,array(
												"class" => "form-control input-sm"
											)); ?>
										<?php endif; ?>
									</th>
								<?php endforeach; ?>
								<?php if($list->config["admin"]["action"]): ?>
									<th></th>
								<?php endif; ?>
							</tr>
						<?php endif; ?>
					</thead>
					<tbody list-no-items>
						<tr>
							<td colspan="<?php echo ($html->willUseCheckbox() ? 1 : 0) + count($list->config["admin"]["columns"]) +  ($list->config["admin"]["action"] ? 1 : 0); ?>" style="height: 120px; vertical-align: middle; text-align: center; background-color: #e0e0e0; font-weight: bold">
								Không có mục nào
							</td>
						</tr>
					</tbody>
					<tbody list-items list-has-items>
						<?php $html->resetLoop(); while($html->loop()): ?>
							<?php $html->renderCurrentItem() ?>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
			<!-- BEGIN TABLE DATA FOOTER -->
			<div class="row">
				<div class="col-md-5 col-sm-12">
					tìm thấy <b><?php $html->renderTotalCount(); ?></b> mục
				</div>
				<div class="col-md-7 col-sm-12 text-right">
					<?php if($list->config["pagination"]): ?>
						<?php $list->getPagination()->render(); ?>
					<?php endif; ?>
				</div>
			</div>
			<!-- END TABLE DATA FOOTER -->
		</div>
	</div>

	<!-- BEGIN FORMS -->
	<div class="mg-t20">
		<?php if($html->hasInsertForm() || $html->hasUpdateForm()): ?>
			<div class="z-depth-1 pd-a10">
				<ul class="nav nav-tabs" role="tablist" style="margin-bottom:15px">
					<?php if($html->hasInsertForm()): ?>
						<li class="active">
							<a href="#tab-insert-content" id="tab-insert" aria-controls="insert" role="tab" data-toggle="tab">Tạo mới</a>
						</li>
					<?php endif; ?>
					<?php if($html->hasUpdateForm()): ?>
						<li >
							<a href="#tab-update-content" id="tab-update" aria-controls="update" role="tab" data-toggle="tab">Cập nhật</a>
						</li>
					<?php endif; ?>
				</ul>
				<div class="tab-content">
					<?php if($html->hasInsertForm()): ?>
						<div role="tabpanel" class="tab-pane active" id="tab-insert-content">
							<?php $html->renderInsertForm() ?>
						</div>
					<?php endif; ?>
					<?php if($html->hasUpdateForm()): ?>
						<div role="tabpanel" class="tab-pane" id="tab-update-content">
							<?php $html->renderUpdateForm() ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<!-- END TAB PORTLET-->
		<?php endif; ?>
		<?php $html->renderHtmlAt("end_page"); ?>
	</div>
</div>
<?php $html->end(); ?>
<script>
	(function(){
		var listManager = SList.ListManager.getInstance();
		var listAlias = "<?php echo $list->alias ?>";
		<?php if($html->hasUpdateForm()): ?>
			listManager.listen(listAlias,"item-action-update-click",function(obj){
				$("#tab-update").tab("show");
				$("#tab-update-content").scrollToMe().find("input:not([type='hidden']):first").focus();
			});
		<?php endif; ?>

		<?php if($html->hasInsertForm()): ?>
			$("#btn-trigger-form-insert").click(function(){
				$("#tab-insert").tab("show");
				$("#tab-insert-content").scrollToMe().find("input:not([type='hidden']):first").focus();;
			});
		<?php endif; ?>

		listManager.listen(listAlias,"list-load-data-done",function(obj){
			var list = obj.list;
			if(!list.config.actions.action.data.order)
				return;
			var orderBy = list.currentQuery.orderBy;
			var orderType = list.currentQuery.orderType;
			if(!orderBy){
				orderBy = list.config.defaultQuery.orderBy;
			}
			if(!orderType){
				orderType = list.config.defaultQuery.orderType;
			}
			
			list.$elem.find('[template-order][template-order!="'+orderBy+'"]').removeClass("sorting_asc sorting_desc").addClass("sorting");
			list.$elem.find('[template-order="'+orderBy+'"]').removeClass("sorting sorting_asc sorting_desc").addClass("sorting_"+orderType);

		});

	})();
</script>