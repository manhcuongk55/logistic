$__$.registerJQueryPlugin("inputDateTime",function(){
	var $self = this;
	var $elem = $self.$elem;
	var timestamp;
	var $elemClone;

	this.onInit = function(){
		$elemClone = $elem.clone(false);
		$elemClone.removeAttr("name");
		$elemClone.removeAttr("id");
		$elemClone.attr("is-simulate","");
		$elem.hide();
		$elemClone.insertAfter($elem);
		$elemClone.datetimepicker($self.options);
		$elemClone.on("dp.change",function(){
			var date = $elemClone.data("datetimepicker").getDate();
			$elem.val(parseInt(date.getTime() / 1000)).trigger("change");
		});
		updateTimestamp();
		$self.update();
	};

	this.onUpdate = function(){
		updateTimestamp();
		$self.update();
	};

	function updateTimestamp(){
		timestamp = $self.options.timestamp;
		if(!timestamp)
			timestamp = $elem.val();
		if(!timestamp)
			timestamp =  parseInt((new Date()).getTime() / 1000);
	}

	this.update = function(){
		if(!timestamp)
			return;
		$elemClone.data("datetimepicker").setDate(new Date(timestamp*1000));
	};
},{},"[input-datetime]");