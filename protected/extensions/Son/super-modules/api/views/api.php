<?php if(!$api): ?>
	API Module<br/>
	<?php echo $this->config["description"] ?><br/>
	Base URL: <a href="<?php echo $this->getBaseUrl() ?>"><?php echo $this->getBaseUrl() ?></a>
<?php else: ?>
	<?php
		Son::load("SAsset")->addExtension("jquery.jsonview");
		$formUpload = false;
		//print_r($api["inputs"]); die();
	?>
	<div style="border:1px solid #ddd; padding:10px; border-radius:4px">
		<?php if($api["description"]): ?>
			<div style="background:#eee; margin-bottom:20px">
				<div style="padding:10px 20px;">
					<?php echo $api["description"] ?>
				</div>
			</div>
		<?php endif; ?>
		<form action="javascript:void(0);" method="<?php echo $api["method"] ?>"  id="form" class="form-horizontal" >
			<h4><?php echo $api["label"] ?></h4>
			<?php foreach($api["inputs"] as $inputName => $inputConfig): ?>
				<div class="form-group">
					<label class="control-label col-lg-3" style="text-align:left">
						<?php if($inputConfig["description"]): ?>
							<abbr data-toggle="tooltip" data-placement="top" title="<?php echo $inputConfig["description"] ?>"><?php echo $inputName ?></abbr>
						<?php else: ?>
							<?php echo $inputName ?>
						<?php endif; ?>
					</label>
					<div class="col-lg-6">
						<?php if($inputConfig["type"]=="file"): $formUpload = true; ?>
							<input type="file" name="<?php echo $inputName ?>" class="form-control input-sm" />
						<?php else: ?>
							<input type="text" name="<?php echo $inputName ?>" class="form-control input-sm" />
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
			<div class="form-group">
				<div class="col-lg-3"></div>
				<div class="col-lg-6">
					<button type="submit" class="btn btn-info btn-sm" name="Submit">Submit</button>
				</div>
			</div>
		</form>
		<hr/>
		<div class="box">
		    <div class="row">
		    	<div class="col-lg-6">
			        <h3>
			            <i class="icon-file"></i>
			            Result
			        </h3>
			    </div>
			    <div class="col-lg-6 text-right">
		            <h3 id="status-code-container" style="display:none">
		            	<span id="status-code"></span>
		            </h3>
			    </div>
		    </div>
		    <pre><div id="response"></div></pre>
		</div>
		<hr/>
		<div class="box">
		    <div class="box">
		        <h3>
		            <i class="icon-file"></i>
		            EXAMPLE
		        </h3>
		    </div>
		    <pre><p id="example"></p></pre>
		</div>
		<script>
			$(function(){
				// tooltip
				$(function () {
					$('[data-toggle="tooltip"]').tooltip()
				})
				// form
				var $form = $('#'+'form');
				<?php if($formUpload): ?>
					$form.attr("enctype","multipart/form-data");
				<?php endif; ?>
			    var example = JSON.stringify(<?php echo json_encode($api["outputExample"]) ?>);
			    $('#example').JSONView(example);
			    function handleJSON(json,xhr)
			    {
			    	try {
			        	$('#response').JSONView(json);
			    	}
			    	catch(e){
				        $('#response').html(json);
			    	}
		            $("#status-code-container").show();
		            $("#status-code").text(xhr.status);
			    }
			    $form.submit(function(e){
			        $('#response').html('');
			        <?php if($formUpload): ?>
			        	var data = new FormData($form[0]);
			        <?php else: ?>
			        	var data = $form.serialize();
			        <?php endif; ?>
			        var obj = {
		                type: "POST",
		                url: '<?php echo $this->getBaseUrl($api["name"]);?>',
		                data: data,
		                success: function(data,textStatus,xhr){
		                    handleJSON(data,xhr);
		                },
		                error : function(xhr,textStatus,data){
		                	handleJSON(xhr.responseText,xhr);
		                },
		                cache: false,
		            };
		            <?php if($formUpload): ?>
			            obj.contentType = false;
					    obj.processData = false;
		            <?php endif; ?>
		            $.ajax(obj);

		            e.preventDefault();
		            e.stopPropagation();
		            return false;
			    });
			});
		</script>
	</div>
<?php endif; ?>