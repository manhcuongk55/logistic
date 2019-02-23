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
						<?php $html->renderHtmlAt("table_actions") ?>
						<?php if($html->hasInsertForm()): ?>
							<button type="button" id="btn-trigger-form-insert" class="btn red"><i class="fa fa-plus"></i> Insert</button>
						<?php endif; ?>
						<?php if($html->hasActionExport()): ?>
							<div class="btn-group">
								<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fa fa-download"></i>
									Export
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
							<table class="admin-table table table-striped table-bordered table-hover dataTable">
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
								<tbody list-no-items>
									<tr>
										<td colspan="<?php echo ($html->willUseCheckbox() ? 1 : 0) + count($list->config["admin"]["columns"]) +  ($list->config["admin"]["action"] ? 1 : 0); ?>" style="height: 120px; vertical-align: middle; text-align: center;">
											No items
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
			<?php if($html->hasInsertForm() || $html->hasUpdateForm()): ?>
				<!-- BEGIN TAB PORTLET-->
				<div class="portlet box blue tabbable">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-keyboard-o"></i>
						</div>
					</div>
					<div class="portlet-body">
						<div class=" portlet-tabs">
							<ul class="nav nav-tabs" role="tablist" style="margin-bottom:15px">
								<?php if($html->hasInsertForm()): ?>
									<li class="active">
										<a href="#tab-insert-content" id="tab-insert" aria-controls="insert" role="tab" data-toggle="tab">Insert</a>
									</li>
								<?php endif; ?>
								<?php if($html->hasUpdateForm()): ?>
									<li >
										<a href="#tab-update-content" id="tab-update" aria-controls="update" role="tab" data-toggle="tab">Update</a>
									</li>
								<?php endif; ?>
							</ul>
							<div class="tab-content">
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
						</div>
					</div>
				</div>
				<!-- END TAB PORTLET-->
			<?php endif; ?>
			<?php $html->renderHtmlAt("end_page"); ?>
		</div>
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