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

			$self.options.toolbar = [
		        ['style', ['style']],
		        ['font', ['bold', 'italic', 'underline', 'clear']],
		        // ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
		        ['fontname', ['fontname']],
		        ['fontsize', ['fontsize']],
		        ['color', ['color']],
		        ['para', ['ul', 'ol', 'paragraph']],
		        ['height', ['height']],
		        ['table', ['table']],
		        ['insert', ['link', 'picture', 'hr']],
		        ['view', ['fullscreen', 'codeview']],
		        ['help', ['help']],
		    ];

		    $self.options.onImageUpload = function(files, editor, welEditable) {
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
				    		$elem.summernote("insertImage", data.url);
				    	});
				    }
				});
			}
			
			$elem.on('summernote.paste', function (customEvent, nativeEvent) {
				var thisNote = $elem;
				function CleanPastedHTML(input) {
					// 1. remove line breaks / Mso classes
					var stringStripper = /(\n|\r| class=(")?Mso[a-zA-Z]+(")?)/g;
					var output = input.replace(stringStripper, ' ');
					// 2. strip Word generated HTML comments
					var commentSripper = new RegExp('<!--(.*?)-->','g');
					var output = output.replace(commentSripper, '');
					var tagStripper = new RegExp('<(/)*(meta|link|span|\\?xml:|st1:|o:)(.*?)>','gi');
					// 3. remove tags leave content if any
					output = output.replace(tagStripper, '');
					// 4. Remove everything in between and including tags '<style(.)style(.)>'
					var badTags = ['style', 'script','applet','embed','noframes','noscript'];

					for (var i=0; i< badTags.length; i++) {
					tagStripper = new RegExp('<'+badTags[i]+'.*?'+badTags[i]+'(.*?)>', 'gi');
					output = output.replace(tagStripper, '');
					}
					// 5. remove attributes ' style="..."'
					var badAttributes = ['style','start'];
					for (var i=0; i< badAttributes.length; i++) {
						var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"','gi');
						output = output.replace(attributeStripper, '');
					}
					return output;
				}
				var updatePastedText = function(someNote){
					var original = someNote.code();
					var cleaned = CleanPastedHTML(original); //this is where to call whatever clean function you want. I have mine in a different file, called CleanPastedHTML.
					someNote.code('').code(cleaned); //this sets the displayed content editor to the cleaned pasted code.
					$self.$elem.val(cleaned).trigger("change");
				};
				setTimeout(function () {
					//this kinda sucks, but if you don't do a setTimeout, 
					//the function is called before the text is really pasted.
					updatePastedText(thisNote);
				}, 10);
			});

		    $__$.trigger("summernote-options",$self.options);
			
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
	},"[input-html]");

	$__$.trigger("summernote-ready");
})();