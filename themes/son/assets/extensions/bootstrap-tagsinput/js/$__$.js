$__$.registerJQueryPlugin("bootstrapTagsInputIntegration",function(){
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;

	this.onInit = function(){
		this.initElement();
	}

	this.onUpdate = function(){
		this.initElement();
	}

	this.initElement = function(){
		var config = {};
		if($self.options.data){
			var items = $self.options.data;
			/*var itemsBloodhound = new Bloodhound({
				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
				queryTokenizer: Bloodhound.tokenizers.whitespace,
			});
			itemsBloodhound.initialize();*/

			options = {
				itemValue: 'value',
  				itemText: 'text',
			};
			config.itemValue = "value";
			config.itemText = "text";

			var itemsBloodhound = new Bloodhound({
				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				local: items
			});

			itemsBloodhound.initialize();

			config.typeaheadjs = {
				displayKey: "text",
				source: itemsBloodhound.ttAdapter()

			};
		}
		$elem.tagsinput(config);
	}
},{
	suffix : " ",
	thousands : "", 
	decimal : ",",
	precision : 3
},"[input-tags]")