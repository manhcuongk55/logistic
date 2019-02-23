$__$.registerJQueryPlugin("inputDateTime",function(){
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;
	var timestamp;
	var $elemClone;

	this.onInit = function(){
		$elemClone = $elem.clone(false);
		$elemClone.removeAllData();
		$elemClone.removeAttr("name");
		$elemClone.removeAttr("id");
		$elemClone.attr("is-simulate","");
		$elem.hide();
		$elemClone.insertAfter($elem);
		$elemClone.parent().css("position","relative");
		$elemClone.datetimepicker(options);
		$elemClone.on("dp.change",function(e){
			var date = e.date.toDate();
			$elem.val(parseInt(date.getTime() / 1000)).trigger("change");
		});
		$elem.on("change",function(){
			$self.onUpdate();
		});
		updateTimestamp();
		$self.update();
	};

	this.onUpdate = function(){
		updateTimestamp();
		$self.update();
	};

	function updateTimestamp(){
		timestamp = options.timestamp;
		if(!timestamp)
			timestamp = $elem.val();
		if(!options["init-with-current-date"])
			return;
		if(!timestamp)
			timestamp =  parseInt((new Date()).getTime() / 1000);
	}

	this.update = function(){
		if(!timestamp)
			return;
		$elemClone.data("DateTimePicker").date(new Date(timestamp*1000));
	};
},{
	"init-with-current-date" : 0
},"[input-datetime]");