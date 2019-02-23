(function(){
	var applyEventHandlerToObject = function(theObject){
		theObject.eventData = {};
		theObject.filterData = {};
		theObject.on = function(nameStr,callback){
			var eventNames = $.trim(nameStr).split(" ");
			var $self = this;
			$.each(eventNames,function(k,name){
				if($self.eventData[name]==undefined)
					$self.eventData[name] = [];
				$self.eventData[name].push(callback);
			});
		};

		theObject.trigger = function(nameStr,data){
			var eventNames = $.trim(nameStr).split(" ");
			var $self = this;
			$.each(eventNames,function(k,name){
				if($self.eventData[name]==undefined)
					return;
				$.each($self.eventData[name],function(k,callback){
					callback(data);
				});
			});
		};

		theObject.unTrigger = function(nameStr){
			var eventNames = $.trim(nameStr).split(" ");
			var $self = this;
			$.each(eventNames,function(k,name){
				if($self.eventData[name]==undefined)
					return;
				$.each($self.eventData[name],function(k,callback){
					$self.eventData[name] && delete($self.eventData[name]);
				});
			});
		};

		theObject.filter = function(name,callback){
			if(this.filterData[name]==undefined)
				this.filterData[name] = [];
			this.filterData[name].push(callback);	
		};

		theObject.getFilter = function(name,data){
			if(this.filterData[name]==undefined)
				return data;
			$.each(this.filterData[name],function(k,callback){
				data = callback(data);
			});
			return data;
		};

		theObject.unFilter = function(name){
			this.filterData[name] && delete(this.filterData[name]);
		};
	}
	var applyEventHandler = function(theClass){
		applyEventHandlerToObject(theClass.prototype);
	};

	var $__$__base = function(){

	};
	applyEventHandler($__$__base);
	window.$__$ = new $__$__base();
	$__$.applyEventHandler = applyEventHandler;
	$__$.applyEventHandlerToObject = applyEventHandlerToObject;

})();

(function(){

	$__$.emptyFunction = function(){
		return function(){};
	};

	$__$.removeFromArray = function(item,array){
		var index = array.indexOf(item);
		array.splice(index, 1);
	}

	$__$.inArray = function(item,arr){
		for(var j=0; j<arr.length; j++){
			if(item==arr[j]){
				return true;
			}
		};
		return false;
	};

	$__$.shuffleArray = function(o){
	    for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
	    return o;
	};

	$__$.checkParams = function(expectedParams,params){
		var types = [
			"string", "number", "function", "object", "undefined"
		];
		var match = [];
		for(var i = 0; i<expectedParams.length; i++){
			var expectedItems = expectedParams[i].split("|");
			var valid = false;
			var j;
			for(j=0; j<expectedItems.length; j++){
				var expected = expectedItems[j];
				var useTypeOf = false;
				var useCheckNull = false;
				if(expected=="skip"){
					continue;
				} else if(expected=="null"){
					useCheckNull = true;
				} else {
					useTypeOf = $__$.inArray(expected,types);
				}
				if(useCheckNull){
					valid = (params[i] == null);
				} else if(useTypeOf){
					valid = (typeof params[i] == expected);
				} else {
					valid = (params[i] instanceof window.eval(expected));
				}
				if(valid)
				{
					break;
				}
			}
			if(!valid){
				return false;
			} else {
				match.push(j);
			}
		};
		return match;
	};

	var defaultOptionsList = {};
	$__$.registerDefaultOptions = function(name,defaultOptions,forSelector){
		if(defaultOptionsList[name]==undefined)
			defaultOptionsList[name] = [];
		var item;
		if(forSelector){
			item = [defaultOptions,forSelector];
		}
		else {
			item = defaultOptions;
		}
		defaultOptionsList[name].push(item);
	};

	$__$.registerJQueryPlugin = function(_name,theClass,defaultOptions,_selector){
		var name = _name;
		var selector = _selector;
		theClass.defaultOptions = defaultOptions;
		$.fn[name] = function(options,callOnInit,returnObject){
			var $self = $(this);
			var obj = {};
			obj.$elem = $self;
			var defaultOptionsSetByUser = defaultOptionsList[name];
			var defaultOptionsToExtend = {};
			if(defaultOptionsSetByUser){
				$.each(defaultOptionsSetByUser,function(k,item){
					if(item instanceof Array){
						var forSelector = item[1];
						if($self.filter(forSelector).length){
							// this options is set for this input
							defaultOptionsToExtend = $.extend(defaultOptionsToExtend, item[0]);
						}
					} else {
						defaultOptionsToExtend = $.extend(defaultOptionsToExtend,item);
					}
				});
			}
			obj.options = $.extend({},theClass.defaultOptions,defaultOptionsToExtend,$self.data(),options);
			theClass.call(obj);
			$self.data(name,obj);
			if(obj.option==undefined){
				obj.option = function(key,value){
					if(value==undefined)
						return obj.options[key];
					obj.options[key] = value;
				}
			}
			if(callOnInit){
				$self.addClass();
				obj.onInit && obj.onInit();
				if(!returnObject)
					return $self;
			}
			return obj;
		};

		if(!selector)
			return;
		$__$.on("html-appended",function($html){
			var doneClass = name + "-done";
			$html.find(selector+":not(."+doneClass+")").each(function(){
				var $elem = $(this);
				$elem.addClass(doneClass);
				if(!$.fn[name]){
					console.log("invalid name",name);
				}
				obj = $elem[name]();
				obj.onInit && obj.onInit();
			});
			$html.find(selector+"."+doneClass+":not([is-simulate])").each(function(){
				var obj = $(this).data()[name];
				if(!obj){
					var a = 1;
				}
				obj.onUpdate && obj.onUpdate();
			});
		});
	};

	$.fn.getPlugin = function(name,options){
		var plugin = $(this).data(name);
		if(!plugin)
			plugin = $(this)[name](options);
		return plugin;
	};
})();

(function(){
	$__$.validate = new function(){
		var $self = this;
		this.availableRules = {
			"default":function(value,param,params,data,prop){
				data[prop] = param;
				return true;
			},
			"required":function(value,param,params){
				return value && value.length;
			},
			"match_input":function(value,param,params,data,prop){
				return value == data[param];
			},
			"unmatch_input":function(value,param,params){
				return value != data[param];
			},
			"match_value":function(value,param,params){
				return value == param;
			},
			"unmatch_value":function(value,param,params){
				return value != param;
			},
			"min_length":function(value,param,params){
				return value.length >= parseInt(param);
			},
			"max_length":function(value,param,params){
				return value.length <= parseInt(param);
			},
			"exact_length":function(value,param,params){
				return value.length == parseInt(param);
			},
			"greater_than":function(value,param,params){
				return !isNaN(valueNum = parseInt(value)) && valueNum > parseInt(param);
			},
			"less_than":function(value,param,params){
				return !isNaN(valueNum = parseInt(value)) && valueNum < parseInt(param);
			},
			"valid_email":function(value,param,params){
				return (/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/).test(value);
			},
			"valid_number":function(value,param,params){
				return !isNaN(value);
			},
			"valid_integer" : function(value,param,params){
				return /^\+?\d+$/.test(value);
			},
			"valid_illegalChar":function(value,param,params){
				return !value.match(/\W/g);
			},
			"valid_startLetter":function(value,param,params){
				return !value || (/[A-z]/g).test(value[0]);
			},
			"trim":function(value,param,params,data,prop){
				if(data[prop]==undefined)
					data[prop] = "";
				else
					data[prop] = $.trim(data[prop]);
				return true;
			}
		};

		this.ruleLangs = {
			"__default" : function(label){
				return label + " is invalid";
			},
			"en" : {
				"__default" : function(label){
					return label + " is invalid";
				},
				"required":function(label,param,params){
					return label + " is required";
				},
				"match_input":function(label,param,params,labels,name){
					return label + " must match with " + labels[param];
				},
				"unmatch_input":function(label,param,params,labels,name){
					return label + " must NOT match with " + labels[param];
				},
				"match_value":function(label,param,params){
					return label + " must have value '"+param+"'";
				},
				"unmatch_value":function(label,param,params){
					return label + " must NOT have value '" + param + "'";
				},
				"min_length":function(label,param,params){
					return label + " must contain minimum " + (param) + " " + (param < 2 ? "character" : "characters");
				},
				"max_length":function(label,param,params){
					return label + " must contain maximum " + (param) + " " + (param < 2 ? "character" : "characters");
				},
				"exact_length":function(label,param,params){
					return label + " must contain exactly " + (param) + " " + (param < 2 ? "character" : "characters");
				},
				"greater_than":function(label,param,params){
					return label + " must be greater than "+param;
				},
				"less_than":function(label,param,params){
					return label + " must be less than "+param;
				},
				"valid_email":function(label,param,params){
					return (label ? label : "email") + " is invalid";
				},
				"valid_number":function(label,param,params){
					return label + " must be a number";
				},
				"valid_interget":function(label,param,params){
					return label + " must be an integer";
				},
				"valid_illegalChar":function(label,param,params){
					return label + " must NOT cotain illegal characters";
				},
				"valid_startLetter":function(label,param,params){
					return label + " must start with a letter";
				}
			},
			"vi" : {
				"__default" : function(label){
					return label + " không hợp lệ";
				},
				"required":function(label,param,params){
					return label + " là bắt buộc";
				},
				"match_input":function(label,param,params){
					return label + " phải trùng với " + getLabelByName(error.param);
				},
				"unmatch_input":function(label,param,params){
					return label + " không được trùng với " + getLabelByName(param);
				},
				"match_value":function(label,param,params){
					return label + " phải có giá trị '"+param+"'";
				},
				"unmatch_value":function(label,param,params){
					return label + " không được có giá trị '"+param+"'";
				},
				"min_length":function(label,param,params){
					return label + " phải có độ dài tối thiểu là "+(param)+" ký tự";
				},
				"max_length":function(label,param,params){
					return label + " có độ dài không được vượt quá "+(param)+" ký tự";
				},
				"exact_length":function(label,param,params){
					return label + " phải có độ dài xác định là "+(param)+" ký tự";
				},
				"greater_than":function(label,param,params){
					return label + " phải lớn hơn "+param;
				},
				"less_than":function(label,param,params){
					return label + " không được vượt quá "+param;
				},
				"valid_email":function(label,param,params){
					return (label ? label : "email") + " không hợp lệ";
				},
				"valid_number":function(label,param,params){
					return label + " phải là một số";
				},
				"valid_integer":function(label,param,params){
					return label + " phải là một số nguyên";
				},
				"valid_illegalChar":function(label,param,params){
					return label + " bao gồm những ký tự không hợp lệ";
				},
				"valid_startLetter":function(label,param,params){
					return label + " phải bắt đầu bằng một chữ cái";
				}
			}
		};

		this.defaultLang = "en";

		this.registerRule = function(name,validateFunction,langs){
			this.availableRules[name] = validateFunction;
			if(langs==undefined){
				langs = {};
			}
			if(typeof langs == "function")
			{
				var _langs = langs;
				langs = {};
				langs[this.defaultLang] = _langs;
			}
			$.each(langs,function(lang,langFunction){
				if(!$self.ruleLangs[lang])
					$self.ruleLangs[lang] = {};
				$self.ruleLangs[lang][name] = langFunction;
			});
		};

		this.run = function(propRules,labels,data,callback,justOneError,lang){
			if(!callback)
				callback = $__$.emptyFunction();
			var errors = {};
			var totalErrors = [];
			var totalErrorsMap = [];
			var defaulLabels = {};
			$.each(propRules,function(prop){
				defaulLabels[prop] = prop;
				errors[prop] = [];
			});
			labels = $.extend(defaulLabels,labels);
			lang = lang ? lang : $self.defaultLang;

			var isRunning = true;
			for(var prop in propRules){
				var rules = propRules[prop];
				if(!(rules instanceof Array))
					rules = [rules];
				for(var k in rules){
					var value = data[prop];
					var rule = rules[k];
					var ruleFunction, params = [], langFunction = null, langFunctionString, message;
					if(typeof rule == "string"){
						var ruleFunctionString = getRuleFromExpression(rule);
						var langFunctionString = ruleFunctionString;
						ruleFunction = $self.availableRules[ruleFunctionString];
						//langFunction = $self.ruleLangs[lang][ruleFunctionString];
						params = getParamsFromExpression(rule);
						message = getMessageFromExpression(rule);
					} else {
						// rule is an array
						var match;
						if(match=$__$.checkParams(["string|function","Array|string|number|undefined","string|undefined"],rule)){
							// function
							if(match[0]==0){
								ruleFunction = $self.availableRules[rule[0]];
								//langFunction = $self.ruleLangs[lang][rule[0]];
								langFunctionString = rule[0];
							} else if(match[0]==1){
								ruleFunction = rule[0];
							}
							// params
							params = rule[1];
							if(!(params instanceof Array)){
								params = [params];
							}
							// message
							message = rule[2];
						} else if(match=$__$.checkParams(["function","function|string"],rule)){
							ruleFunction = rule[0];
							if(match[1]==0){
								langFunction = rule[1];
							} else if(match[1]==1){
								message = rule[1];
							};
						};

					}
					if(!ruleFunction)
						continue;

					//console.log(prop + ": " + langFunctionString);

					if(!langFunction){
						if(langFunctionString)
						{
							//console.log(langFunctionString);
							langFunction = $self.ruleLangs[lang][langFunctionString];
							if(!langFunction)
								langFunction = $self.ruleLangs[$self.defaultLang][langFunctionString];
						}
						if(!langFunction)
							langFunction = $self.ruleLangs[lang]["__default"];
						if(!langFunction)
							langFunction = $self.ruleLangs["__default"];
					}

					var param = params[0] ? params[0] : null;
					if(value==undefined || value==null)
						value = "";
					var valid = ruleFunction(value,param,params,data,prop);
					if(!valid)
					{
						if(!message)
							message = langFunction(labels[prop],param,params,labels,prop);
						errors[prop].push(message);
						totalErrors.push(message);
						totalErrorsMap.push(prop);
						if(justOneError)
						{
							callback(false,errors,totalErrors,totalErrorsMap);
							return [false,errors,totalErrors,totalErrorsMap];
						}
					}
				};
			};
			if(totalErrors.length){
				callback && callback(false,errors,totalErrors,totalErrorsMap);
				return [false,errors,totalErrors,totalErrorsMap];
			}
			callback && callback(true,errors,totalErrors,totalErrorsMap);
			return [true,errors,totalErrors,totalErrorsMap];
		};

		var getRuleFromExpression = function(expression){
			return expression.match(/((?!\[).)*/g)[0];
		};

		var getParamsFromExpression = function(expression){
			var paramPaths = expression.match(/\[[^\[\}]+\]/g);
			if(!paramPaths)
				return [];
			var params = [];
			$.each(paramPaths,function(key,expression){
				params.push(expression.substring(1,expression.length-1));
			});
			return params;
		};

		var getMessageFromExpression = function(expression){
			var arr = expression.match(/(\{.+\}){1,}/g);
			if(arr && arr.length)
			{
				var message = arr[0];
				message = message.substring(1,message.length-1);
				if(message)
					return message;
				return false;
			}
			else
			{
				return false;
			}
		};

	};

	$__$.applyValidator = function(theClass,__rules,__labels){
		theClass.prototype.__rules = __rules;
		theClass.prototype.__labels = __labels;
		theClass.prototype.validate = function(callback,attrs){
			var $self = this;
			var rules = this.__rules;
			if(attrs){
				rules = {};
				$.each(attrs,function(k,attr){
					rules[attr] = this.__rules[attr];
				});
			}
			return $__$.validate.run(rules,this.__labels,this,function(success,errors,totalErrors){
				callback && callback(success,errors,totalErrors);
				if(success)
					$self.trigger("onValidatedSuccess",$self);
				else
					$self.trigger("onValidatedError",$self);
			});
		};

		theClass.prototype.on("onInserting",function(_this){
			_this.validateBeforeSave();
		});

		theClass.prototype.on("onUpdating",function(_this){
			_this.validateBeforeSave();
		});

		theClass.prototype.validateBeforeSave = function(){
			var result = this.validate();
			//console.log(result);
			if(result[0]){
				// validated
				return;
			}
			this.preventSave(result[2]);
		};
	};

	$__$.registerJQueryPlugin("formSetData",function(){
		var $self = this;
		for(var key in $self.options){
			var value = $self.options[key];
			$self.$elem.find("[name='"+key+"']").val(value);
		}
	})

	$__$.registerJQueryPlugin("formValidate",function(){
		var $self = this;
		this.run = function(){
			var data = [];
			var rules = [];
			var labels = [];
			var elems = [];
			this.$elem.find("[data-valid]:not([is-simulate]):not([disabled])").each(function(){
				var name = $(this).attr("name");
				data.push($(this).val());
				rules.push($(this).attr("data-valid").split("|"));
				var label = $(this).attr("data-label");
				if(!label)
					label = $(this).attr("label");
				labels.push(label);
				elems.push($(this));
			});
			//console.log(data);
			var result = $__$.validate.run(rules,labels,data,null,$self.options.justOneError,$self.options.lang);
			//[success,errors,totalErrors,totalErrorMap]
			if(result[0]){
				// success
				return result;
			} else {
				// error
				var $elems = [];
				$.each(result[3],function(k,v){
					$elems.push(elems[v]);
				});
				$self.options.onError.call($self,result[1],result[2],$elems);
				return false;
			}
		};
	},{
		justOneError : true,
		lang : "en",
		onError : function(errors,totalErrors,$inputElems){
			var $inputElem = $inputElems[0];
			var message = totalErrors[0];
			var $self = this;
			$__$.alert(message,function(){
				$inputElem.add($inputElem.siblings("[is-simulate]")).addClass("input-error").focus();
			});
		}
	});

	$__$.__validate = function(form,e){
		var formValidate = $(form).getPlugin("formValidate");
		var result = formValidate.run();
		if(result){
			return true;
		}
		if(e){
			e.preventDefault();
			e.stopPropagation();
		}
		return false;
	};
})();

(function($){
	$__$.alert = function(message,options){
		if(typeof(options)=="function"){
			options = {
				callback : options
			};
		};
		var defaultOptions = {
			title: $__$.appName,
			callback:function(){}
		};
		options = $.extend({},defaultOptions,options);
		alert(message);
		options.callback();
	};
	$__$.confirm = function(message,options){
		if(typeof(options)=="function"){
			options = {
				callback : options
			};
		};
		var defaultOptions = {
			title:$__$.appName,
			callback:function(){}
		};
		options = $.extend({},defaultOptions,options);
		options.callback(confirm(message));
	};
	$__$.handleJSON = function(json,successCallback,errorCallback){
		//console.log(json);
		if(!errorCallback){
			errorCallback = function(message){
				$__$.alert(message);
			}
		}
		var obj = json;
		if(typeof json == "string")
			var obj = $.parseJSON(obj);
		if(obj.error){
			errorCallback && errorCallback(obj.message,obj.error);
		} else {
			successCallback && successCallback(obj.data);
		}
	};
})(jQuery);

(function($){
	$__$.on("html-appended",function($html){
		var attrName = "data-jquery-plugin";
		var className = "jquery-plugin-init";
		$html.find("["+attrName+"]").andSelf().filter("["+attrName+"]").not("."+className).each(function(){
			var $self = $(this);
			$self.addClass(className);
			var pluginName = $self.attr(attrName);
			$self[pluginName]($self.data());
		});
	});

	$.fn.htmlPn = function(html){
		var $html = $(html);
		$__$.trigger("html-parsing",$html);
		$(this).html($html);
		$__$.trigger("html-appended",$html);
		return $(this);
	};
	$.fn.replaceWithPn = function(html){
		var $html = $(html);
		$__$.trigger("html-parsing",$html);
		$(this).replaceWith($html);
		$__$.trigger("html-appended",$html);
		return $(this);
	};
	$.fn.appendPn = function(html){
		var $html = $(html);
		$__$.trigger("html-parsing",$html);
		$(this).append($html);
		$__$.trigger("html-appended",$html);
		return $(this);
	};
	$.fn.prependPn = function(html){
		var $html = $(html);
		$__$.trigger("html-parsing",$html);
		$(this).prepend($html);
		$__$.trigger("html-appended",$html);
		return $(this);
	};
	$.fn.insertBeforePn = function($elem){
		var $html = $(this);
		$__$.trigger("html-parsing",$html);
		$html.insertBefore($elem);
		$__$.trigger("html-appended",$html);
		return $elem;
	};
	$.fn.insertAfterPn = function($elem){
		var $html = $(this);
		$__$.trigger("html-parsing",$html);
		$html.insertAfter($elem);
		$__$.trigger("html-appended",$html);
		return $elem;
	};
	$__$.updateHtml = function($element)
	{
		//$__$.trigger("html-parsing",$element);
		$__$.trigger("html-appended",$element);
	}
})(jQuery);
(function(){
	window.mobilecheck = function() {
		var check = false;
		(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
		return check; 
	};
	$__$.mobile = mobilecheck();
})();


//

$(function(){
	$__$.updateHtml($("body"));
});