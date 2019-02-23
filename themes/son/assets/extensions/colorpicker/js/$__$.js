/*$__$.on("html-appended",function($html){
	var selector = ".input-color-picker";
	var doneClass = "input-color-picker-done";
	$html.find(selector).andSelf().filter(selector).each(function(){
		var $self = $(this);
		$self.addClass(doneClass);
		$self.hide();
		var $div = $('<div class="colorSelector"><div></div></div>');
		$div.insertAfter($self);
		var value = $self.val() ? $self.val() :'#0000ff';
		$self.val(value);
		$div.ColorPicker({
			color: value,
			onShow: function (colpkr) {
				$(colpkr).show();
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).hide();
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$div.children('div').css('backgroundColor', '#' + hex);
				$self.val("#" + hex);
			}
		});
		$div.children('div').css('backgroundColor', value);
	});
});*/

$__$.registerJQueryPlugin("inputColorPicker",function(){
	var $self = this;
	var $div;
	var value;
	$self.onInit = function(){
		$div = $('<div class="colorSelector"><div></div></div>');
		$self.$elem.hide();
		$div.insertAfter($self.$elem);
		value = $self.$elem.val() ? $self.$elem.val() :'#0000ff';
		$self.$elem.val(value);
		$div.ColorPicker({
			color: value,
			onShow: function (colpkr) {
				$(colpkr).show();
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).hide();
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$self.$elem.val("#" + hex);
				value = "#" + hex;
				$self.onUpdateValue(true);
			}
		});
		$self.$elem.on("change",function(){
			$self.onUpdate();
		});
		$self.onUpdateValue();
		
	};
	$self.onUpdate = function(){
		value = $self.$elem.val();
		$self.onUpdateValue();
	};

	$self.onUpdateValue = function(preventSetColor){
		if(!preventSetColor)
			$(this).ColorPickerSetColor(value);
		$div.children('div').css('backgroundColor', value);
	};

},{},"[input-color-picker]");