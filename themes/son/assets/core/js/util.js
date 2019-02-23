$__$.loadScript = function (src, document, callback) {
	if (!document)
		document = window.document;
	var s, r, t;
	r = false;
	s = document.createElement('script');
	s.type = 'text/javascript';
	s.src = src;
	s.onload = s.onreadystatechange = function () {
		//console.log( this.readyState ); //uncomment this line to see which ready states are called.
		if (!r && (!this.readyState || this.readyState == 'complete')) {
			r = true;
			callback();
		}
	};
	//t = document.getElementsByTagName('meta')[0];
	//t.parentNode.insertBefore(s, t);
	t = document.getElementsByTagName("head")[0];
	t.appendChild(s);
};

$__$.generateRandomString = function (length) {
	var d = new Date().getTime();
	function generateUUID() {
		var uuid = 'xxxxx'.replace(/[xy]/g, function (c) {
			var r = (d + Math.random() * 16) % 16 | 0;
			d = Math.floor(d / 16);
			return (c == 'x' ? r : (r & 0x7 | 0x8)).toString(16);
		});
		return uuid;
	};
	return generateUUID() + new Date().getTime();
};

$__$.prevent = function (e) {
	e = e ? e : window.event;
	if (e) {
		e.stopPropagation();
		e.preventDefault();
	}
	return false;
}

$__$.on("html-appended", function ($html) {
	$html.find("[data-toggle='tooltip']:not([tooltip-done]),[rel='tooltip']:not([tooltip-done])").each(function () {
		$(this).attr("tooltip-done", "").tooltip();
	});
	$html.find("[data-toggle='popover']:not([popover-done]),[rel='popover']:not([popover-done])").each(function () {
		$(this).attr("popover-done", "").popover();
	});
});

$__$.toUnderscore = function (str) {
	return str.replace(/([A-Z])/g, function ($1) { return "_" + $1.toLowerCase(); });
};

$.fn.reset = function () {
	$(this).get(0).reset();
	$(this).find("input,select").trigger("change");
}

/*(function(){
	var originalSerializeArray = $.fn.serializeArray;
	$.fn.extend({
	    serializeArray: function () {
	        var brokenSerialization = originalSerializeArray.apply(this);
	        var checkboxValues = $(this).find('input[type=checkbox]:not([checkbox-origin])').map(function () {
	            return { 'name': this.name, 'value': this.checked ? this.value : 0 };
	        }).get();
	        var checkboxKeys = $.map(checkboxValues, function (element) { return element.name; });
	        var withoutCheckboxes = $.grep(brokenSerialization, function (element) {
	            return $.inArray(element.name, checkboxKeys) == -1;
	        });

	        return $.merge(withoutCheckboxes, checkboxValues);
	    }
	});
})();*/

$.fn.bindFirst = function (name, fn) {
	// bind as you normally would
	// don't want to miss out on any jQuery magic
	this.on(name, fn);

	// Thanks to a comment by @Martin, adding support for
	// namespaced events too.
	this.each(function () {
		var handlers = $._data(this, 'events')[name.split('.')[0]];
		console.log(handlers);
		// take out the handler we just inserted from the end
		var handler = handlers.pop();
		// move it at the beginning
		handlers.splice(0, 0, handler);
	});
};

$.fn.removeAllData = function () {
	var data = $(this).data();
	for (var k in data) {
		$.removeData($(this), k);
	}
}

$.fn.scrollToMe = function () {
	$('html, body').scrollTop($(this).offset().top);
	return $(this);
};

jQuery.fn.textFocusEnd = function () {
	var el = $(this).get(0);
	if (typeof el.selectionStart == "number") {
		el.selectionStart = el.selectionEnd = el.value.length;
	} else if (typeof el.createTextRange != "undefined") {
		el.focus();
		var range = el.createTextRange();
		range.collapse(false);
		range.select();
	}
	return $(this);
};

jQuery.fn.textFocusAll = function () {
	var el = $(this).get(0);
	if (typeof el.selectionStart == "number") {
		el.selectionStart = 0;
		el.selectionEnd = el.value.length;
	} else if (typeof el.createTextRange != "undefined") {
		el.focus();
		var range = el.createTextRange();
		range.collapse(false);
		range.select();
	}
	return $(this);
};

$__$.registerJQueryPlugin("selectDropdown", function () {
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;
	var $options;
	var $html;
	var searchCache = {};

	this.onInit = function () {
		if ($__$.mobile && !options.ajax) {
			$elem.show();
			return;
		}
		$self.initHtml();
		$self.updateOptions();
		$self.updateValue();
	}

	this.onUpdate = function () {
		if ($__$.mobile && !options.ajax) {
			$elem.show();
			return;
		}
		$self.updateValue();
	}

	this.initHtml = function () {
		var html = '\
			<div class="btn-group">\
				<button type="button" class="' + options.btnClass + ' dropdown-toggle" data-toggle="dropdown">\
					<span class="select-text"></span>\
					<span class="caret"></span>\
				</button>\
				<ul class="dropdown-menu option-list" role="menu" style="left:auto">\
		';
		if (options.ajax) {
			html += '\
				<li class="item-search"> \
					<div class="search-container empty"> \
						<input type="text" class="search form-control" placeholder="Search" /> \
						<div class="loading"></div> \
						<i class="icon-remove close"></i> \
					</div> \
				</li> \
			';
		}

		html += '\
				</ul>\
			</div>\
		';

		$self.$html && $self.$html.remove();
		$self.$html = $html = $(html).hide().insertAfter($elem);

		// events


		$html.children(".dropdown-toggle").click(function (e) {
			//$html.dropdown("toggle");
			$html.toggleClass("open");
			if ($html.hasClass("open")) {
				$html.find(".search").focus();
			}
			return $__$.prevent(e);
		});

		// search
		$self.setupAjax();




		$elem.on("change", function () {
			$self.updateValue();
		});


		// start

		$elem.hide();
		$html.show();
	}

	this.setupAjax = function () {
		if (options.ajax) {
			$html.find("ul li div.search-container").on("click", function (e) {
				return $__$.prevent(e);
			});
			$html.find(".search").listenToEnterKey(null, true).on("enter", function (e) {
				$self.search($(this).val());
				return $__$.prevent(e);
			}).on("change keyup", function () {
				if ($(this).val() == "") {
					$html.find(".search-container").addClass("empty");
					$(this).focus();
				}
				else {
					$html.find(".search-container").removeClass("empty");
				}
			});

			$html.on('shown.bs.dropdown', function () {
				$html.find("ul li.item-search input").focus();
			});

			$html.find(".close").click(function () {
				$html.find(".search").val("").trigger("change");
			});

			$elem.on("change", function () {
				var val = $(this).data("value");
				if (val === null) {
					val = $(this).val();
				}
				var $selectOption = $elem.find("option[value='" + val + "']");
				if (!$selectOption.length) {
					// no selected options => refresh
					$elem.children().remove();
					$elem.append('<option value="' + val + '" selected="selected">' + $elem.data("display") + '</option>');
				}
				$self.updateOptions();
				$self.updateValue();
			});

			$self.search("", true);

		}
	}

	this.updateOptions = function () {
		$options = $elem.children("option");
		var optionHtml = "";
		$options.each(function () {
			var optionVal = $(this).val();
			var optionText = $(this).text();
			optionHtml += '<li><a href="javascript:;" data-value="' + optionVal + '" data-text="' + optionText + '">' + optionText + '</a></li>';
		});
		var $optionHtml = $(optionHtml);

		if (options.ajax) {
			var $searchItem = $html.find(".item-search");
			$searchItem.nextAll().remove();
			$optionHtml.insertAfter($searchItem);
		} else {
			$html.find(".option-list").html($optionHtml);
		}

		$optionHtml.find("a").on("click", function (e) {
			var optionVal = $(this).data("value");
			$elem.val(optionVal).trigger("change");
			$html.removeClass("open");
			return $__$.prevent(e);
		});
	}

	this.updateValue = function (val) {
		var val = $elem.val();
		var text = $.trim($options.filter(":selected").text());
		$elem.data("value", val);
		$elem.data("display", text);
		$html.find(".select-text").text(text);
	}

	this.search = function (keyword, allowEmpty) {
		keyword = $.trim(keyword);
		$self.$elem.val(keyword);
		if (!allowEmpty && keyword.length == 0)
			return;
		var resultCallback = function (results) {
			if (options.searchcache) {
				searchCache[keyword] = results;
			}
			$elem.children().remove();
			var selectedValue = $self.$elem.data("value");
			var selectedText = $self.$elem.data("display");
			if (selectedValue != undefined && selectedText != undefined) {
				$elem.prepend('<option value="' + selectedValue + '" selected="selected">' + selectedText + '</option>');
			}
			$.each(results, function (k, v) {
				if (v[0] == selectedValue)
					return;
				$elem.append('<option value="' + v[0] + '">' + v[1] + '</option>');
			});
			$self.updateOptions();
			$self.updateValue();
			$html.find(".search-container").removeClass("searching");
		};
		if ($self.options.searchcache && searchCache[keyword] != undefined) {
			resultCallback(searchCache[keyword]);
			return;
		}
		$html.find(".search-container").addClass("searching");
		$.ajax({
			type: "get",
			url: options.url,
			data: {
				model: options.model,
				attr: options.attr,
				term: keyword,
			},
			success: function (json) {
				$__$.handleJSON(json, function (results) {
					resultCallback(results);
				});
			}
		});
	}

}, {
		btnClass: "btn btn-default btn-sm",
		ajax: "",
		model: "",
		url: "",
		searchcache: 1,
		defaultDisplayText: "No change",
	}, "[input-dropdown]");

// fix top

$__$.registerJQueryPlugin("elemFixedTop", function () {
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;

	this.onInit = function () {
		var isOverflow = false;
		var originTop;
		var originWidth;
		var originHeight = $elem.get(0).offsetHeight;
		var minHeight = parseFloat($("body").css("min-height"));

		if (originHeight > minHeight)
			$("body").css("min-height", originHeight);
		$elem.parent().css("position", "relative");
		$(window).scroll(function () {
			if (!$elem.is(":visible"))
				return;
			var top = originTop ? originTop : $elem.offset().top - parseFloat($elem.css('margin-top').replace(/auto/, 0)) - $elem.get(0).offsetHeight;
			//console.log(top);
			var overflow = $(window).scrollTop() > top;
			if ((isOverflow && overflow) || (!isOverflow && !overflow))
				return;
			console.log("elem-fixed-top change");
			isOverflow = overflow;
			if (overflow) {
				if (!originTop) {
					originTop = top;
				}
				if (!originWidth) {
					originWidth = $elem.get(0).offsetWidth;
					$elem.css("width", originWidth);
				}
				$elem.addClass("elemFixedTopClass").css("top", options.top);
			}
			else {
				$elem.removeClass("elemFixedTopClass").css("top", "auto");
			}
		});
	}
}, {
		top: 0
	}, "[position-fixed-top]");


$__$.registerJQueryPlugin("heightUntilBottom", function () {
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;

	this.onInit = function () {
		$(window).on("resize", function () {
			$self.resize();
		})
		$self.resize();
	}

	this.resize = function () {
		var offset = $elem.offset();
		if (!offset || offset.top == undefined)
			return;
		var height = window.innerHeight - offset.top;
		//console.log($(this).offset().top);
		//console.log(height);
		if (isNaN(height))
			return;
		$elem.css("height", height);
		if (options.overflowAuto)
			$elem.css("overflow", "auto");
	}
}, {
		overflowAuto: true
	}, "[height-until-bottom]");

(function () {
	var arr = [];
	var humanize = function (timestamp, lang) {
		return new Date(timestamp * 1000).toLocaleString();
		//
		var sPerMinute = 60;
		var sPerHour = sPerMinute * 60;
		var sPerDay = sPerHour * 24;
		var sPerMonth = sPerDay * 30;
		var sPerYear = sPerDay * 365;
		var current = (new Date()).getTime() / 1000;
		var d = window.d_timestamp ? window.d_timestamp : 0;
		var elapsed = current + d - timestamp; // num second

		switch (lang) {
			case "vi":
				if (elapsed < sPerMinute) {
					return "vừa xong";
				}
				else if (elapsed < sPerHour) {
					return Math.round(elapsed / sPerMinute) + ' phút trước';
				}
				else if (elapsed < sPerDay) {
					return Math.round(elapsed / sPerHour) + ' giờ trước';
				}
				/*else if (elapsed < sPerMonth) {
					return 'khoảng ' + Math.round(elapsed/sPerDay) + ' ngày trước';   
				}
				else if (elapsed < sPerYear) {
					return 'khoảng ' + Math.round(elapsed/sPerMonth) + ' tháng trước';   
				}
				else {
					return 'khoảng ' + Math.round(elapsed/sPerYear ) + ' năm trước';   
				}*/
				break;
			default: // en
				if (elapsed < sPerMinute) {
					return "just now";
				}
				else if (elapsed < sPerHour) {
					var val = Math.round(elapsed / sPerMinute);
					return val + ' minute' + (val > 1 ? "s" : "") + " ago";
				}
				else if (elapsed < sPerDay) {
					var val = Math.round(elapsed / sPerHour);
					return val + ' hour' + (val > 1 ? "s" : "") + " ago";
				}
				/*else if (elapsed < sPerMonth) {
					var val = Math.round(elapsed/sPerDay);
					return val + ' day' + (val > 1 ? "s" : "") + " ago";
				}
				else if (elapsed < sPerYear) {
					var val = Math.round(elapsed/sPerMonth);
					return val + ' month' + (val > 1 ? "s" : "") + " ago";
				}
				else {
					var val = Math.round(elapsed/sPerYear );
					return val + ' year' + (val > 1 ? "s" : "") + " ago"; 
				}*/
				break;
		}

		return new Date(timestamp * 1000).toLocaleString();


	};

	$__$.registerJQueryPlugin("prettyTime", function () {
		var $self = this;
		var $elem = $self.$elem;
		var options = $self.options;

		this.onInit = function () {
			$self.update();
		}

		this.onUpdate = function () {
			$self.update();
		}

		this.update = function () {
			var timestamp = $elem.attr("timestamp") ? $elem.attr("timestamp") : $elem.text();
			timestamp = parseInt(timestamp);
			if (!timestamp)
				return;
			setInterval(function () {
				$elem.text(humanize(timestamp, options.lang));
			}, options.interval);
			$elem.text(humanize(timestamp, options.lang));
		}
	}, {
			interval: 30000,
			lang: "en"
		}, "[timestamp]");
})();

$.fn.realVal = function (setVal) {
	var returnValue = $(this);
	$(this).each(function () {
		if ($(this).is('[type="checkbox"]')) {
			if (setVal == undefined) {
				returnValue = $(this).is(":checked") ? 1 : 0;
			} else {
				$(this).prop('checked', parseInt(setVal));
			}
		} else if ($(this).is('[type="file"]')) {
			if (setVal == undefined) {
				returnValue = $(this).attr("data-url");
			} else {
				$(this).attr("data-url", setVal);
			}
		} else {
			if (setVal == undefined) {
				returnValue = $(this).val();
			} else {
				if ($(this).is("select")) {
					$(this).data("value", setVal);
				}
				$(this).val(setVal);
			}
		}
	});
	return returnValue;
};

$__$.registerJQueryPlugin("inputChangeTracker", function () {
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;
	var triggerEnabled = true;
	var originValue;
	var currentValue;

	this.onInit = function () {
		originValue = $elem.realVal();
		currentValue = originValue;
		$elem.attr("origin-value", originValue);
		$elem.on("change keyup", function (e) {
			var hasChanged = $self.detectChange();
			if (triggerEnabled && hasChanged)
				$elem.trigger("changed");
		});
		$elem.on("change-without-trigger-changed", function () {
			triggerEnabled = false;
			$elem.trigger("change");
			triggerEnabled = true;
		});

		$elem.on("changed-without-check", function () {
			$elem.attr("changed", "");
		});
	}

	this.recover = function () {
		$elem.val(originValue).trigger("change-without-trigger-changed").removeAttr("changed");
	}

	this.detectChange = function () {
		var newValue = $elem.realVal();
		var valueChanged = newValue != currentValue;
		var valueHasChangedBackToOrigin = newValue == originValue;
		if (valueHasChangedBackToOrigin) {
			if (options.recoverEnabled)
				$elem.removeAttr("changed");
		} else if (valueChanged) {
			$elem.attr("changed", "");
		}
		currentValue = newValue;
		return valueChanged;
	}
}, {
		recoverEnabled: 1
	}, "[track-change]");

$__$.registerJQueryPlugin("listenToEnterKey", function () {
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;

	this.onInit = function () {
		$elem.keydown(function (e) {
			if (e.keyCode == 13) {
				$elem.trigger("enter");
				var enterCallback = $elem.attr("onenter");
				if (enterCallback)
					eval(enterCallback);
				return $__$.prevent(e);
			}
		});
	}
}, {}, "[onenter]");

$__$.registerJQueryPlugin("inlineEdit", function () {
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;
	var type;

	this.onInit = function () {
		if (options.inlineEditType == "double_click") {
			var id = $elem.attr("inline-edit-id");
			var $input = $("[inline-edit-display-id='" + id + "']");
			function showInput() {
				$elem.hide();
				$input.show().removeAttr("hidden");
			}

			function hideInput() {
				$elem.show();
				$input.hide();
			}

			$self.recover = function () {
				hideInput();
				$input.getPlugin("inputChangeTracker").recover();
				$elem.trigger("inline-edit-canceled");
			}

			$elem.on("click", function () {
				showInput();
			});

			$input.on("blur", function () {
				$self.recover();
			});
			$input.on("keyup", function (e) {
				if (e.keyCode == 27)
					$self.recover();
			})

			$self.listenInputChanged($input, function () {

			});
		} else if (options.inlineEditType == "show_input_directly") {
			$self.listenInputChanged($elem, function () {

			});
		}
	}

	// TODO
	this.updateValue = function () {
		if (options.inlineEditType == "double_click") {

		} else {

		}
	}

	this.listenInputChanged = function ($input, callback) {
		function triggerChange() {
			$elem.trigger("inline-edit-changed", {
				value: $input.realVal()
			});
			callback && callback();
		}

		$input.inputChangeTracker(null, true);

		if (options.inlineEditType == "double_click") {
			$input.listenToEnterKey(null, true).on("enter", function () {
				if ($input.is("[changed]")) {
					triggerChange();
				}
			});
		} else {
			$input.on("changed", function () {
				triggerChange();
			});
		}
	}
}, {
		inlineEditType: "show_input_directly"
	}, "[inline-edit]");

$__$.registerJQueryPlugin("iframeModal", function () {
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;
	var $iframe;
	var iframeLoaded = false;

	this.onInit = function () {
		$iframe = $elem.find("iframe");
		$iframe.each(function () {
			if (options.heightPerScreenHeight) {
				var callback = function () {
					var height = $(window).height() * options.heightPerScreenHeight;
					$iframe.height(height);
				}
				$(window).resize(callback);
				callback();
			}
		});

		$elem.on('shown.bs.modal', function () {
			if (iframeLoaded && options.loadOnce) {
				return;
			}
			iframeLoaded = true;
			$iframe.each(function () {
				$(this).attr("src", $(this).attr("data-src"));
			});
		});
	}

	this.onUpdate = function () {

	}
}, {
		loadOnce: true,
		heightPerScreenHeight: 0.8
	}, "[modal-iframe]");

$__$.registerJQueryPlugin("boxContainer", function () {
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;

	this.onInit = function () {
		$elem.find(".btn-minimize").click(function (e) {
			$elem.addClass("minimized");
			return $__$.prevent(e);
		});
		$elem.find(".btn-restore").click(function (e) {
			$elem.removeClass("minimized");
			return $__$.prevent(e);
		});
		$elem.find(".btn-toggle").click(function (e) {
			$elem.toggleClass("minimized");
			return $__$.prevent(e);
		});
	}
}, {}, ".box-container");

$__$.registerJQueryPlugin("moneyDisplay", function () {
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;

	this.onInit = function () {
		this.updateDisplay();
	}

	this.onUpdate = function () {
		this.updateDisplay();
	}

	this.updateDisplay = function () {
		var moneyValue = $elem.attr("data-money");
		if (moneyValue === undefined) {
			moneyValue = $.trim($elem.text());
			if (isNaN(parseInt(moneyValue))) {
				return;
			}
			$elem.attr("data-money", moneyValue);
		}
		var money = parseFloat(moneyValue);
		var moneyStr = this.formatMoney(money, 0, ",", ".");
		$elem.text(moneyStr);
	}

	this.formatMoney = function (money, c, d, t) {
		var n = money,
			c = isNaN(c = Math.abs(c)) ? 0 : c,
			d = d == undefined ? "." : d,
			t = t == undefined ? "," : t,
			s = n < 0 ? "-" : "",
			i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
			j = (j = i.length) > 3 ? j % 3 : 0;
		return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	}
}, {}, "[money-display],[data-money]");

$__$.linkifyRaw = function (inputText) {
	var replacedText, replacePattern1, replacePattern2, replacePattern3;

	//URLs starting with http://, https://, or ftp://
	replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
	replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank">$1</a>');

	//URLs starting with "www." (without // before it, or it'd re-link the ones done above).
	replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
	replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>');

	//Change email addresses to mailto:: links.
	replacePattern3 = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim;
	replacedText = replacedText.replace(replacePattern3, '<a href="mailto:$1">$1</a>');

	return replacedText;
}

$__$.linkify = function (str) {
	return $__$.linkifyRaw($__$.nl2br(str));
}

$__$.nl2br = function (str) {
	if (!str)
		return "";
	var is_xhtml = true;
	var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
	str = (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
	return str;
}

$__$.registerJQueryPlugin("linkify", function () {
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;

	this.onInit = function () {
		this.updateDisplay();
	}

	this.onUpdate = function () {
		this.updateDisplay();
	}

	this.updateDisplay = function () {
		var str = $elem.text();
		str = $__$.linkify(str);
		$elem.html(str);
	}
}, {}, "[data-linkify]");

$__$.registerJQueryPlugin("permissionInput", function () {
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;
	var $itemsContainer;
	var isUpdatingDisplay = false;

	this.onInit = function () {
		$itemsContainer = $('<div class="permission-list"/>');
		var permissionItems = options.permissions;
		$.each(permissionItems, function (index, label) {
			var $item = $(
				'<div class="permission-item" data-index="' + index + '" style="margin-bottom: 5px;">\
					<span class="" style="margin-right: 30px;">\
						<input type="checkbox" input-checkbox-button data-value="1" data-index="' + index + '" />\
					</span>\
					<span class="permission-label">' + label + '</span>\
				</div>'
			);
			$itemsContainer.append($item);
		});
		$itemsContainer.insertAfterPn($elem);

		$itemsContainer.find("input[type='checkbox']").on("change", function () {
			$self.updateValue();
		});

		$elem.on("change", function () {
			$self.updateDisplay();
		});

		this.updateDisplay();
	}

	this.onUpdate = function () {
		$self.updateDisplay();
	}

	this.updateValue = function () {
		var value = 0;
		$itemsContainer.find("input[type='checkbox']").each(function () {
			var index = parseInt($(this).attr("data-index"));
			var checked = $(this).realVal();
			if (!checked)
				return;
			value += Math.pow(2, index);
		});
		$elem.val(value).trigger("change");
	}

	this.updateDisplay = function () {
		if (isUpdatingDisplay)
			return;
		isUpdatingDisplay = true;
		var value = parseInt($elem.val());
		$itemsContainer.find("input[type='checkbox']").each(function () {
			var index = parseInt($(this).attr("data-index"));
			var checked = value & Math.pow(2, index);
			$(this).realVal(checked).trigger("change");
		});
		isUpdatingDisplay = false;
	}


}, {}, "[input-permission]");


$__$.registerJQueryPlugin("imagePreview", function () {
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;

	this.onInit = function () {
		$(options.imagePreview).change(function () {
			readURL(this);
		});
		var originUrl = $(options.imagePreview).attr("value");
		if (originUrl)
			$elem.attr("src", originUrl);
	}

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$elem.attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}


}, {}, "[data-image-preview]");

$__$.registerJQueryPlugin("imagePreviewAdvanced", function () {
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;

	// [file-preview] [file-preview-input] [file-preview-trigger-input] [file-preview-image] [file-preview-hide-when-image] [file-preview-show-when-image]

	this.onInit = function () {
		var value = $elem.find("[file-preview-input]").attr("value");
		if (value) {
			displayImage(value);
		} else {
			onImageHidden();
		}
		doWithElemForAttr("file-preview-input", function () {
			$(this).change(function () {
				readURL(this);
			});
		});
		doWithElemForAttr("file-preview-trigger-input", function () {
			$(this).off("click");
			$(this).click(function () {
				$elem.find("[file-preview-input]").eq(0).click();
			});
		});
		doWithElemForAttr("file-preview-input-data", function () {
			$(this).on("change", function () {
				var src = $(this).val();
				displayImage(src);
				$(this).attr("disabled", "disabled");
			});
		})
	}

	this.onUpdate = function () {

	}

	function displayImage(src) {
		doWithElemForAttr("file-preview-image", function () {
			if ($(this).is("img")) {
				$(this).attr("src", src);
			} else {
				$(this).css("background-image", "url(" + src + ")");
			}
		});
		onImageShown();
	}

	function onImageShown() {
		doWithElemForAttr("file-preview-show-when-image", function () {
			$(this).show();
		});
		doWithElemForAttr("file-preview-hide-when-image", function () {
			$(this).hide();
		});
	}

	function onImageHidden() {
		doWithElemForAttr("file-preview-show-when-image", function () {
			$(this).hide();
		});
		doWithElemForAttr("file-preview-hide-when-image", function () {
			$(this).show();
		});
	}

	function doWithElemForAttr(attrName, callback) {
		var selector = "[" + attrName + "]";
		return $elem.find(selector).andSelf().filter(selector).each(callback);;
	}

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				var base64Data = e.target.result;
				displayImage(e.target.result);
				doWithElemForAttr("file-preview-input-data", function () {
					$(this).removeAttr("disabled").val(base64Data);
				})
			}
			reader.readAsDataURL(input.files[0]);
		}
	}


}, {}, "[file-preview]");

$__$.registerJQueryPlugin("ajaxModal", function () {
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;

	this.onInit = function () {
		$elem.on("click", function (e) {
			var id = options.modalId;
			var $modal = $("#" + id);
			var $loadingModal = $("#loading-modal");
			if (!$modal.length) {
				$modal = $('<div class="modal" id="ajaxModal"><div class="modal-body modal-ajax-content"></div></div>');
				$('body').append($modal);
			}
			var url = options.url;
			if ($loadingModal.length)
				$loadingModal.show().modal("show");
			$.get(url, function (html) {
				$modal.find(".modal-ajax-content").htmlPn(html);
				if ($loadingModal.length) {
					$loadingModal.on("hidden.bs.modal", f1 = function () {
						$modal.modal("show");
						$loadingModal.off("hidden.bs.modal", f1);
					});
					$loadingModal.modal("hide");
				}
				$modal.modal("show");

			});
			return $__$.prevent(e);
		})
	}

}, {
		modalId: "ajax-modal"
	}, "[data-remote-modal]");

$__$.registerJQueryPlugin("imageLoadPlaceholder", function () {
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;

	this.onInit = function () {
		var $clone = $elem.clone(true);
		$elem.on("load", function () {
			$clone.remove();
			$elem.show();
		});
		$elem.hide();
		$clone.insertAfter($elem);
		$clone.attr("src", options.placeholder);
	}
}, {}, "[data-placeholder]");