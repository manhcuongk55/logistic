$__$.on("html-appended",function($html){
	var selector = ".input-select:not(.angular-render)";
	var doneClass = "input-select-done";
	$html.find(selector).andSelf().filter(selector).each(function(){
		var $self = $(this);
		if($self.hasClass(doneClass))
		{
			var selectPicker = $self.data("selectpicker");
			if(selectPicker)
			{
				selectPicker.refresh();
			}
			else
			{
				$self.selectpicker();
			}
			$self.trigger("change");
			//$self.selectpicker();
		}
		else
		{
			$self.addClass(doneClass);
			$self.selectpicker();
		}
	});
});