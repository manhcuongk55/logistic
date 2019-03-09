<?php $html->begin(); ?>
<div id="content">
	<div class="row">
		<div class="col-lg-10 col-lg-offset-1">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-4">
							<?php if($list->config["actions"]["action"]["data"]["limit"]): ?>
								<?php $html->renderLimitInput(array(
									"input-dropdown" => ""
								)) ?> entries per page
							<?php endif; ?>
						</div>
						<div class="col-lg-4 text-center">
							<?php if($list->config["actions"]["action"]["data"]["search"]): ?>
								<?php $html->renderSearchInput(); ?>
							<?php endif; ?>
							<button type="button" list-do-refresh class="btn btn-sm btn-default"><i class="icon-refresh"></i></button>
						</div>
						<div class="col-lg-4 text-right">
							<?php if($html->hasActionTogether()): ?>
								<div class="actions">
									<div class="btn-group">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
								</div>
							<?php endif; ?>
						</div>
					</div>
					<table class="table table-hover table-striped table-bordered table-advanced tablesorter">
						<thead>
							<tr>
								<?php if($html->willUseCheckbox()): ?>
									<td class="col-checkbox">
										<?php $html->renderCheckboxSelectAll(array(
											"input-checkbox-button" => ""
										)); ?>
									</td>
								<?php endif; ?>
								<?php foreach($list->config["admin"]["columns"] as $attr): ?>
									<td class="col-attr-<?php echo $attr ?>">
										<?php $html->renderOrderFor($attr); ?>
									</td>
								<?php endforeach; ?>
								<?php if($list->config["admin"]["action"]): ?>
									<td class="col-actions">Actions</td>
								<?php endif; ?>
							</tr>
							<?php if($list->config["actions"]["action"]["data"]["advancedSearch"]): ?>
								<tr>
									<?php if($html->willUseCheckbox()): ?>
										<td></td>
									<?php endif; ?>
									<?php foreach($list->config["admin"]["columns"] as $attr): ?>
										<td>
											<?php if($html->hasAdvancedSearchFor($attr)): ?>
												<?php $html->renderAdvancedSearchInputFor($attr,array(
													"class" => "form-control"
												)); ?>
											<?php endif; ?>
										</td>
									<?php endforeach; ?>
									<?php if($list->config["admin"]["action"]): ?>
										<td></td>
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
					<div class="row">
						<div class="col-lg-6">
							<?php $html->renderTotalCount(); ?> items found
						</div>
						<div class="col-lg-6 text-right">
							<?php if($list->config["pagination"]): ?>
								<?php $list->getPagination()->render(); ?>
							<?php endif; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<ul class="nav nav-tabs" role="tablist" style="margin-bottom:15px">
								<li class="active">
									<a href="#tab-insert-content" id="tab-insert" aria-controls="insert" role="tab" data-toggle="tab">Insert</a>
								</li>
								<li >
									<a href="#tab-update-content" id="tab-update" aria-controls="update" role="tab" data-toggle="tab">Update</a>
								</li>
							</ul>
							<!-- Tab panes -->
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="tab-insert-content">
									<?php $html->renderInsertForm() ?>
								</div>
								<div role="tabpanel" class="tab-pane" id="tab-update-content">
									<?php $html->renderUpdateForm() ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $html->end(); ?>
<script>
	$(function(){
		var listManager = SList.ListManager.getInstance();
		var listAlias = "<?php echo $list->alias ?>";
		listManager.listen(listAlias,"item-action-update",function(){
			$("#tab-update").tab("show");
			$("#tab-update-content").scrollToMe().find("input:not([type='hidden']):first").focus();;
		});

	});
</script>