(function(){
	$__$.registerJQueryPlugin("formCsrf",function(){
		var $self = this;
		var $elem = $self.$elem;
		var options = $self.options;

		$self.onInit = function(){
			$elem.on("form-success",function(e,data){
				$elem.find("[csrf]").val(data.csrf);
				$elem.trigger("form-csrf-updated");
			});
		}

	},{},"form[data-csrf]");
	
	$__$.registerJQueryPlugin("formAjax",function(){
		var $self = this;
		var $elem = $self.$elem;
		var options = $self.options;
		
		$self.onInit = function(){
		}

		var formValidate = $self.$elem.getPlugin("formValidate",{
			justOneError : $self.options.justOneError,
			lang : $self.options.lang,
			onError : $self.options.onError
		});
		//
		this.validate = function(){
			return formValidate.run();
		};

		this.submit = function(){
			if(!$self.validate())
				return;
			
			$self.$elem.on("form-loading",function(){
				$elem.find("[form-loading]").show();
			});
			$self.$elem.on("form-loaded",function(){
				$elem.find("[form-loading]").hide();
			});
			if(options.replaceAfterSuccess){
				$self.$elem.on("form-success",function(e,data){
					var id = $self.$elem.attr("id");
					$("[data-form-wrapper='"+id+"']").replaceWithPn(data.html);
				});
			}
			
			$elem.on("form-loading-ajax",function(e,data){
				$elem.find(":input,button").not("[disabled]").attr("disabled","disabled").attr("temp-disabled","");
			});
			
			$elem.on("form-loaded",function(e,data){
				$elem.find("[temp-disabled]").removeAttr("disabled").removeAttr("temp-disabled");
			});
			
			function doSubmit(){
				$elem.trigger("form-loading");
				switch($self.options.type){
					case "iframe":
						// must call from button
						$self.submitIframe();
						break;
					case "validate":
						// must call from button
						$self.submitValidate();
						break;
					default: // ajax
						$self.submitAjax();
						break;
				}
			}
			if($self.options.confirm){
				$__$.confirm($self.options.confirm,function(result){
					if(!result)
						return;
					doSubmit();
				});
			} else {
				doSubmit();
			}
		};

		this.submitAjax = function(){
			var url = $self.$elem.attr("action")==undefined ? "" : $self.$elem.attr("action");
			var type = $self.$elem.attr("method")==undefined ? "get" : $self.$elem.attr("method");
			convertCheckboxToBooleanInput();
			var data = $self.$elem.serializeArray();
			var ajaxObject = {
				url:url,
				type:type,
				data:data,
				success:function(data){
					$elem.trigger("form-loaded");
					$__$.handleJSON(data,function(data){
						$elem.trigger("form-success",data);
						$self.options.onResultSuccess && $self.options.onResultSuccess(data);
					},function(message,errorCode){
						$self.options.onResultError && $self.options.onResultError(message,errorCode);
					});
				},
				error : function(){
					$elem.trigger("form-loaded");
					$self.options.onConnectError && $self.options.onConnectError();
				},
			};
			$elem.trigger("form-loading-ajax");
			$.ajax(ajaxObject);
			undoConvertCheckboxToBooleanInput();
		};

		this.submitIframe = function(){
			var target = $self.$elem.attr("target");
			if(!target)
			{
				// create new
				target = "iframe"+(new Date()).getTime();
				$self.$elem.attr("target",target);
			}
			var $iframe = $("<iframe hidden></iframe>").attr("name",target);
			var $exist = $("iframe[name='"+target+"']");
			if($exist.length)
				$exist.replaceWith($iframe);
			else
				$("body").append($iframe);
			$iframe.load(function(){
				undoConvertCheckboxToBooleanInput();
				$elem.trigger("form-loaded");
				var json = $iframe.contents().find("body").text();
				$__$.handleJSON(json,function(data){
					$elem.trigger("form-success",data);
					$self.options.onResultSuccess && $self.options.onResultSuccess(data);
				},function(message,errorCode){
					$self.options.onResultError && $self.options.onResultError(message,errorCode);
				});
			});
			convertCheckboxToBooleanInput();
			$self.$elem.submit();
		};

		this.submitValidate = function(){
			convertCheckboxToBooleanInput();
			$self.$elem.submit();
		};

		function convertCheckboxToBooleanInput(){
			$elem.find("input[type=checkbox]:not([checkbox-origin]):not(:checked)").each(function(){
				$(this).attr("origin-value",$(this).val());
				$(this).val(0);
				$(this).prop("checked",1);
			});
		}

		function undoConvertCheckboxToBooleanInput(){
			$elem.find("input[type=checkbox][origin-value]:not([checkbox-origin])").each(function(){
				$(this).val($(this).attr("origin-value"));
				$(this).prop('checked',0);
			});
		}

	},{
		type : "ajax",
		confirm : false,
		onResultError : function(message,errorCode){
			$__$.alert(message);
		},
		onResultSuccess : function(data){

		},
		onConnectError : function(){
			$__$.alert("Oops! There's some errors happened on server!");
		},
		//
		replaceAfterSuccess : false,
		justOneError : true,
		lang : "en",
		onError : function(errors,totalErrors,$inputElems){
			var $inputElem = $inputElems[0];
			var message = totalErrors[0];
			var $self = this;
			$__$.alert(message,function(){
				$inputElem.add($inputElem.siblings("[is-simulate]")).addClass("input-error").focus();
			});
		}
	});

	$__$.__ajax = function(form,e){
		if(e){
			e.preventDefault();
			e.stopPropagation();
		}
		var formAjax = $(form).getPlugin("formAjax");
		formAjax.submit();
		return false;
	};
})();