$__$.registerJQueryPlugin("checkboxButton",function(){
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;

	this.onInit = function(){
		$elem.iCheck(options);

		$elem.on("change",function(){
			$self.updateValue();
		}).trigger("change");

		$elem.on('ifChanged', function(event){
			$elem.trigger("change");
		});
	}

	this.onUpdate = function(){
		$self.updateValue();
	}

	this.updateValue = function(){
		$elem.iCheck('update');
	}

},{
	checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' // optional
},"[input-checkbox-button]");