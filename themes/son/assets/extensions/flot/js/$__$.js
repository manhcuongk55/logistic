$__$.on("html-appended",function($html){
	var selector = ".display-chart:not(.display-chart-done)";
	var doneClass = "display-chart-done";
	$html.find(selector).andSelf().filter(selector).each(function(){
		var $self = $(this);
		$self.addClass(doneClass);
		$self.displayChart();
	});
});

$.fn.displayChart = function(){
	var $self = $(this);
	var options = $self.data();
	options = $.extend({},$.fn.displayChart.defaultOptions,options);

	switch(options.type){
		case "flot":
			$self.displayChartFlot(options);
			break;
		case "bar":
			$self.displayChartBar(options);
			break;
		case "pie":
			$self.displayChartPie(options);
			break;
	}

};



$.fn.displayChartPie = function(options){
	var $self = $(this);
	var data = [];

	if(options.src=="[example]")
	{
	    var series = Math.floor(Math.random() * 10) + 1;
	    series = series < 5 ? 5 : series;
	    
	    for (var i = 0; i < series; i++) {
	        data[i] = {
	            label: "Series" + (i + 1),
	            data: Math.floor(Math.random() * 100) + 1
	        }
	    }
	    $self.addClass("example-chart");
	}
	else
	{
		var _data = eval(options.src);
		$.each(_data,function(k,item){
			data.push({
				label : item.label,
				data : item.value
			});
		});
	}

    // GRAPH 1
    $.plot($self, data, {
        series: {
            pie: {
                show: true
            }
        },
        legend: {
            show: false
        }
    });

};

$.fn.displayChartBar = function(options){
	var $self = $(this);	

	var data = [];

	var _options = {
            series:{
                bars:{show: true}
            },
            bars:{
                            barWidth: 0.5,
                            lineWidth: 0, // in pixels
                            shadowSize: 0,
                            align: 'center'
            },            

            grid:{
                tickColor: "#eee",
                borderColor: "#eee",
                borderWidth: 1
            }
    };

	if(options.src=="[example]"){
		var added = 0;
		var start = 0 + added;
        var end = 4 + added;
 
        for(i=1;i<=4;i++){        
            var d = Math.floor(Math.random() * (end - start + 1) + start);        
            data.push([i, d]);
            start++;
            end++;
        }
		$self.addClass("example-chart");
	} else {
		var _data = eval(options.src);
		_options.xaxis = {
			ticks : []
		};
		$.each(_data,function(k,item){
			data.push([k,item.value]);
			_options.xaxis.ticks.push([
				k, item.label
			]);
		});
	}

	//console.log(_options);

    $.plot($self,[
    {
        data: data,
        lines: {
            lineWidth: 1,
        },
        shadowSize: 0
     }
     ], _options);
};

$.fn.displayChartFlot = function(options){
	var $self = $(this);
	var $tooltip;

	var data = [];

	if(options.src=="[example]"){
		var sin = [], cos = [];
		for (var i = 0; i < 14; i += 0.5) {
			sin.push([i, Math.sin(i)]);
		};
		data = [
			{ 
				data: sin, 
				label: "sin(x)", 
				lines: {
                    lineWidth: 1,
                },
			}
		];
		$self.addClass("example-chart");
	} else {
		//var _data = JSON.parse(options.src);
		var _data = eval(options.src);
		//console.log(t=_data);
		$.each(_data,function(k,line){
			var lineData = [];
			$.each(line.data,function(k2,item){
				if(options.x=="date"){
					item.xaxis = parseInt(item.xaxis)*1000;
				}
				lineData.push([item.xaxis,item.yaxis]);
			});
			var lineObj = {
				data : lineData,
				lines : {
					lineWidth : 1
				}
			};
			lineObj.label = line.label ? line.label : options.label;
			data.push(lineObj);
		});
	}

	var min_y;
	var max_y;
	$.each(data,function(k,line){
		$.each(line.data,function(k2,point){
			var y = parseFloat(point[1]);
			if(max_y==undefined || y > max_y)
				max_y = y;
			if(min_y==undefined || y < min_y)
				min_y = y;
		});
	});

	//console.log(data);

	//console.log(min_y);
	//console.log(max_y);

	//min_y = min_y < 0 ? 0 : min_y;
	max_y = max_y + options.offset;

	var _options = {
		series: {
            lines: {
                show: true,
                lineWidth: 2,
                fill: true,
                fillColor: {
                    colors: [{
                            opacity: 0.05
                        }, {
                            opacity: 0.01
                        }
                    ]
                }
            },
            points: {
                show: true,
                radius: 3,
                lineWidth: 1
            },
            shadowSize: 2
        },
		grid: {
            hoverable: true,
            clickable: true,
            tickColor: "#eee",
            borderColor: "#eee",
            borderWidth: 1
        },
        colors: ["#d12610", "#37b7f3", "#52e136"],
		yaxis: {
			min: min_y,
			max: max_y
		}
	};

	if(options.x=="date"){
		_options.xaxis = {
			mode: "time",
        	timeformat: "%Y-%m-%d",
		};
	};

	var plot = $.plot($self, data, _options);

	$tooltip = $('<div class="plot-tooltip" />');
	$tooltip.css({
		position: "absolute",
		display: "none",
		border: "1px solid #fdd",
		padding: "2px",
		"background-color": "#fee",
		opacity: 0.80
	}).appendTo("body");

	$self.bind("plothover", function (event, pos, item) {
		if (item) {
			var x = item.datapoint[0].toFixed(2),
				y = item.datapoint[1].toFixed(2);

			$tooltip.html(y)
				.css({top: item.pageY+5, left: item.pageX+5})
				.fadeIn(200);
		} else {
			$tooltip.hide();
		}
	});
};

$.fn.displayChart.defaultOptions = {
	type : "flot",
	src : "[example]",
	label : "chart",
	offset : 0,
	x : false
};