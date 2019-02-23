<?php 
	Son::load("SAsset")->addExtension("data-tables");
?>
<style>
	body {
		background: #eee;
	}

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
					<button type="button" id="btn-trigger-form-insert-<?php echo $list->alias ?>" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> Thêm mới</button>
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
							Hành động
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
				<?php $actionRendered = false; ?>
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
								<?php if($attr=="__action__"): $actionRendered = true; ?>
									<th class="list-th-actions">Actions</th>
								<?php else: ?>
									<th <?php if($html->hasOrderFor($attr)): ?> template-order="<?php echo $attr ?>" <?php endif; ?> class="list-th-attr-<?php echo $attr ?>">
										<?php $html->renderOrderFor($attr); ?>
									</th>
								<?php endif; ?>
							<?php endforeach; ?>
							<?php if(!$actionRendered && $list->config["admin"]["action"]): ?>
								<th class="list-th-actions">Actions</th>
							<?php endif; ?>
						</tr>
						<?php if($list->config["actions"]["action"]["data"]["advancedSearch"]): ?>
							<tr>
								<?php if($html->willUseCheckbox()): ?>
									<th></th>
								<?php endif; ?>
								<?php foreach($list->config["admin"]["columns"] as $attr): ?>
									<?php if($attr=="__action__"): ?>
										<th></th>
									<?php else: ?>
										<th>
											<?php if($html->hasAdvancedSearchFor($attr)): ?>
												<?php $html->renderAdvancedSearchInputFor($attr,array(
													"class" => "form-control input-sm"
												)); ?>
											<?php endif; ?>
										</th>
									<?php endif; ?>
								<?php endforeach; ?>
								<?php if(!$actionRendered && $list->config["admin"]["action"]): ?>
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
	<?php if($html->hasInsertForm()): ?>
		<!-- INSERT MODAL -->
		<?php $html->renderInsertForm($this->getThemePath("admin.subviews.form_modal"),array(
			"modalId" => "modal-form-insert-" . $list->alias,
			"formId" => "form-insert-" . $list->alias
		)); ?>
		<!-- END INSERT MODAL -->
	<?php endif; ?>

	<?php if($html->hasUpdateForm()): ?>
		<!-- UPDATE MODAL -->
		<?php $html->renderUpdateForm($this->getThemePath("admin.subviews.form_modal"),array(
			"modalId" => "modal-form-update-" . $list->alias,
			"formId" => "form-update-" . $list->alias
		)); ?>
		<!-- END UPDATE MODAL -->
	<?php endif; ?>
	<!-- END FORMS -->

	<?php $html->renderHtmlAt("end_page"); ?>
</div>
<?php $html->end(); ?>

<script>
	$(function(){
		var listManager = SList.ListManager.getInstance();
		var listAlias = "<?php echo $list->alias ?>";

		listManager.listen(listAlias,"list-load-data-done",function(obj){
			var list = obj.list;
			if(!list.config.actions.action.data.order)
				return;
			var orderBy = list.currentQuery.order_by;
			var orderType = list.currentQuery.order_type;
			if(!orderBy){
				orderBy = list.config.defaultQuery.orderBy;
			}
			if(!orderType){
				orderType = list.config.defaultQuery.orderType;
			}
			
			list.$elem.find('[template-order][template-order!="'+orderBy+'"]').removeClass("sorting_asc sorting_desc").addClass("sorting");
			list.$elem.find('[template-order="'+orderBy+'"]').removeClass("sorting sorting_asc sorting_desc").addClass("sorting_"+orderType);

		});

		<?php if($html->hasUpdateForm()): ?>
			listManager.listen(listAlias,"item-action-update-click",function(obj){
				$("#modal-form-update-<?php echo $list->alias ?>").modal("show");
			});

			listManager.listen(listAlias,"item-action-update-done",function(){
				$("#modal-form-update-<?php echo $list->alias ?>").modal("hide");
			});
		<?php endif; ?>

		<?php if($html->hasInsertForm()): ?>
			listManager.listen(listAlias,"item-action-insert-done",function(){
				$("#modal-form-insert-<?php echo $list->alias ?>").modal("hide");
			});

			$("#btn-trigger-form-insert-<?php echo $list->alias ?>").click(function(){
				$("#modal-form-insert-<?php echo $list->alias ?>").modal("show");
			});
		<?php endif; ?>
	});
</script>