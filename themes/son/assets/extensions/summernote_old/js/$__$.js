(function(){
	$__$.registerJQueryPlugin("inputHTMLEditor",function(){
		var $self = this;
		var $elem = $self.$elem;
		var options = $self.options;
		var preventUpdateHTMLEditor = false;

		$self.onInit = function(){
			$self.options.onChange = function(contents, $editable) {
				preventUpdateHTMLEditor = true;
				$self.$elem.val(contents).trigger("change");
			};
			$self.options.onCreateLink = function (url) {
				if($self.options.urlAbsolute){
				    if (url.indexOf('http://') !== 0 && url.indexOf('#') !== 0) {
				        url = 'http://' + url;
				    }
				}
			    return url;
			};

			$self.$elem.on("change",function(){
				if(preventUpdateHTMLEditor){
					preventUpdateHTMLEditor = false;
					return;
				}
				$self.update();
			});
			
			$self.$elem.summernote($self.options);
			$self.update();
		};

		$self.onUpdate = function(){
			$self.update();
		};

		$self.update = function(){
			$self.$elem.code($self.$elem.val());
		}
	},{
		height : 200,
		onImageUpload: function(files, editor, welEditable) {
			var file = files[0];
			var data = new FormData();
			data.append("file", file);
			$.ajax({
			    data: data,
			    type: "POST",
			    url: "/site/summernote_upload",
			    cache: false,
			    contentType: false,
			    processData: false,
			    success: function(json) {
			    	$__$.handleJSON(json,function(data){
			    		editor.insertImage(welEditable, data.url);
			    	});
			    }
			});
		}
	},"[input-html]");
})();

