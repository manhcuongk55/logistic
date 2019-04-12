$__$.registerJQueryPlugin("maskMoneyIntegrate",function(){
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;
	var $clone;

	this.onInit = function(){
		$clone = $elem.clone(true);
		$clone.attr("is-simulate","");
		$clone.insertAfter($elem.hide());
		$clone.maskMoney(options);
		$clone.attr("name","clone-" +  $elem.attr("name") + "-clone");

		$clone.on("change keyup",function(){
			var val = $clone.val();
			val = val.replace(options.decimal,"");
			val = parseInt(val);
			$elem.val(val).trigger("change");
		})
	}

	this.onUpdate = function(){
		$clone.val($elem.val());
	}
},{
	suffix : " ",
	thousands : "", 
	decimal : ",",
	precision : 3
},"[input-money]")