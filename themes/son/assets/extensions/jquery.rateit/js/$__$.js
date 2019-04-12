/*$__$.on("html-appended",function($html){
	var selector = ".rate-it";
	var doneClass = "rate-it-done";
	$html.find(selector).andSelf().filter(selector).each(function(){
		var $self = $(this);
		$self.addClass(doneClass);
		$self.rateit();
	});
});*/
(function(){
	var i = 0;
	$__$.registerJQueryPlugin("inputRateit",function(){
		var $self = this;
		var $div = $("<div/>");
		$self.onInit = function(){
			/*var inputId;
			if(!$self.$elem.attr("id")){
				inputId = new Date().getTime();
				newId = "input-rateit-" + newId + "-" + (i++);
				$self.$elem.attr("id",newId)
			} else {
				inputId = $self.$elem.attr("id");
			}*/
			$self.$elem.hide();
			$div.addClass("rateit").insertAfter($self.$elem);
			$div.rateit($self.options);
			$div.bind('rated', function (event, value) {
				$self.$elem.val(value).trigger("change");
			});
			$self.$elem.on("change",function(){
				$self.update();
			});
			$self.update();
		};
		$self.onUpdate = function(){
			$self.update();
		};
		$self.update = function(){
			$div.rateit("value",$self.$elem.val());
		};
	},{},"[input-rateit]");
})();