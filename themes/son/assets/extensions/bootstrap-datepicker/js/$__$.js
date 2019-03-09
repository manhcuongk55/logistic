/*$__$.on("html-appended",function($html){
	var selector = ".input-date";
	var doneClass = "input-date-done";
	$html.find(selector).andSelf().filter(selector).each(function(){
		var $self = $(this);
		$self.addClass(doneClass);
		$self.datepicker($self.data());
		var timestamp;
		if(timestamp = $self.attr("data-timestamp")){
			$self.data("datepicker").setDate(new Date(timestamp*1000));
		}
	});
});*/

$__$.registerJQueryPlugin("inputDate",function(){
	var $self = this;
	var timestamp;

	function updateTimestamp(){
		timestamp = $self.$elem.attr("data-timestamp");
	}

	this.onInit = function(){
		updateTimestamp();
		$self.$elem.datepicker($self.options);
		$self.update();
	};

	this.onUpdate = function(){
		updateTimestamp();
		$self.update();
	};

	this.update = function(){
		if(!timestamp)
			return;
		$self.$elem.data("datepicker").setDate(new Date(timestamp*1000));
	};
},{},"[input-date]");