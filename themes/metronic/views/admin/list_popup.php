<?php 
	Son::load("SAsset")->addExtension("data-tables");
?>
<style>
	.list-th-checkbox {
		width:50px;
	}

	.admin-table thead th [list-order-display] {
		display:none;
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
	}

	.admin-table td, .admin-table th {
		overflow-x: hidden !important;
		text-overflow: ellipsis;
	}

	.admin-table thead > tr:not(:first-child) > th{
		overflow: visible !important;
	}
</style>
<?php $html->begin(); ?>
<div id="content-<?php echo $list->alias ?>">
	<div class="row">
		<div class="col-lg-12">
			<?php $html->renderHtmlAt("begin_page"); ?>
			<!-- BEGIN TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption">
						<?php echo $list->config["admin"]["title"] ?>
					</div>
					<div class="actions">
						<?php if($html->hasInsertForm()): ?>
							<button type="button" id="btn-trigger-form-insert-<?php echo $list->alias ?>" class="btn red"><i class="fa fa-plus"></i> Insert</button>
						<?php endif; ?>
						<?php if($html->hasActionTogether()): ?>
							<div class="btn-group">
								<button type="button" class="btn green dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fa fa-cog"></i>
									Actions
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
				<div class="portlet-body">
					<div class="dataTables_wrapper">
						<div class="row">
							<div class="col-md-9 col-sm-12">
								<?php if($list->config["actions"]["action"]["data"]["limit"]): ?>
									<?php $html->renderLimitInput(array(
										"input-dropdown" => ""
									)) ?> entries per page
								<?php endif; ?>
							</div>
							<div class="col-md-3 col-sm-12 text-right">
								<?php if($list->config["actions"]["action"]["data"]["search"]): ?>
									<?php $html->renderSearchInput(array(
										"class" => "form-control"
									)); ?>
								<?php endif; ?>
							</div>
						</div>
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover dataTable" style="table-layout:fixed">
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
															"class" => "form-control"
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
								<tbody list-items>
									<?php $html->resetLoop(); while($html->loop()): ?>
										<?php $html->renderCurrentItem() ?>
									<?php endwhile; ?>
								</tbody>
							</table>
						</div>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<?php $html->renderTotalCount(); ?> items found
							</div>
							<div class="col-md-7 col-sm-12 text-right">
								<?php if($list->config["pagination"]): ?>
									<?php $list->getPagination()->render(); ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php if($html->hasInsertForm()): ?>
				<!-- INSERT MODAL -->
				<?php $html->renderInsertForm("ext.Son.html.views.form.form-bootstrap-modal",array(
					"modalId" => "modal-form-insert-" . $list->alias,
					"formId" => "form-insert-" . $list->alias
				)); ?>
				<!-- END INSERT MODAL -->
			<?php endif; ?>

			<?php if($html->hasUpdateForm()): ?>
				<!-- UPDATE MODAL -->
				<?php $html->renderUpdateForm("ext.Son.html.views.form.form-bootstrap-modal",array(
					"modalId" => "modal-form-update-" . $list->alias,
					"formId" => "form-update-" . $list->alias
				)); ?>
				<!-- END UPDATE MODAL -->
			<?php endif; ?>

			<?php $html->renderHtmlAt("end_page"); ?>
		</div>
	</div>
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