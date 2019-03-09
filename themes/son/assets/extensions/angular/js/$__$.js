// angular

(function(){
	$__$.on("angular-init",function(app){
		app.directive("ngCoreController",function($rootScope){
			return {
				link : function($scope,$element){
					$__$.angular.triggerScope({
						"$rootScope" : $rootScope,
						"$scope" : $scope,
						"$element" : $element
					});
				}
			}
		});
		app.directive('ngBindHtmlUnsafe', function($compile){
		    return function( $scope, $element, $attrs ) {
		        var compile = function( newHTML ) { // Create re-useable compile function
		        	newHTML = '<div>'+newHTML+'</div>';
		            newHTML = $compile(newHTML)($scope);
		            if($element.get(0).moreThanOneTimeLoaded)
		            	$element.htmlPn(newHTML);
		            else
		            {
		            	$element.get(0).moreThanOneTimeLoaded = 1;
		            	$element.html(newHTML);
		            }
		        };
		        var htmlName = $attrs.ngBindHtmlUnsafe;
		        $scope.$watch(htmlName, function( newHTML ){
		            if(!newHTML) return;
		            compile(newHTML);   // Compile it
		        });
		    };
		}).directive("ngRendered",["$timeout",function($timeout){
			return {
				restrict : "A",
				scope : {
					"ngRendered" : "@"
				},
				link : function($scope,$element){
					$timeout(function(){
						$element.css("visibility","visible");
						$element.find(".angular-render").removeClass("angular-render");
						$__$.updateHtml($element);
						if($scope.ngRendered)
						{
							$scope.$parent.$eval($scope.ngRendered);
						}
						$scope.$emit("rendered");
					});
				}
			};
		}]).directive("ngSuccess",function(){
			var _index = 0;
			return {
				restrict : "A",
				link : function($scope, $element, $attrs){
					var success = $attrs.ngSuccess;
					var index = _index++;
					var attrName = "ng-success-id";
					$element.attr("data-success-event",success);
					$element.attr(attrName,index);
					$__$.on("form-"+success,function(obj){
						if($(obj.elem).attr(attrName)==index)
							$scope.$emit(success,obj.data,$element);
					});
				}
			};
		}).directive("ngClickConfirm",function(){
			var _index = 0;
			return {
				restrict : "A",
				link : function($scope, $element, $attrs){
					var callback = $attrs.ngClickConfirm;
					var confirmMessage = $attrs.ngConfirm ? $attrs.ngConfirm : "Are you sure";
					$element.click(function(e){
						e.preventDefault();
						e.stopPropagation();

						$__$.confirm(confirmMessage,{
							callback : function(result){
								if(result)
									$scope.$parent.$eval(callback);
							}
						})

						return false;
					});
				}
			};
		}).directive("ngDisplayTime",["$timeout",function($timeout){
			return {
				restrict : "A",
				link : function($scope,$element,$attrs){
					$timeout(function(){
						var timestamp = parseInt($element.text());
						var date = new Date(timestamp*1000);
						$element.attr("data-timestamp",timestamp);
						$element.text(date.toDateString());
					});
				}
			}
		}]);

		app.directive('ngEnter', function () {
		    return function (scope, element, attrs) {
		        element.bind("keydown keypress", function (event) {
		            if(event.which === 13) {
		                scope.$apply(function (){
		                    scope.$eval(attrs.ngEnter);
		                });

		                event.preventDefault();
		            }
		        });
		    };
		});

		app.directive("fileread", [function () {
		    return {
		        scope: {
		            fileread: "="
		        },
		        link: function (scope, element, attributes) {
		            element.bind("change", function (changeEvent) {
		                var reader = new FileReader();
		                reader.onload = function (loadEvent) {
		                    scope.$apply(function () {
		                        scope.fileread = loadEvent.target.result;
		                    });
		                }
		                reader.readAsDataURL(changeEvent.target.files[0]);
		            });
		        }
		    }
		}]);

		app.directive("ngOnFilesPicked", [function () {
		    return {
		        link: function ($scope, element, attributes) {
		            element.bind("change", function (changeEvent) {
		            	var files = changeEvent.target.files;
		            	if(!files.length)
		            		return;
		            	var eventName = attributes.ngOnFilesPicked;
		            	var images = [];
		            	var readFile = function(i){
		            		var file = files[i];
		            		var reader = new FileReader();
			                reader.onload = function (loadEvent) {
			                    $scope.$apply(function () {
			                        var src = loadEvent.target.result;
			                        images.push(src);
			                        if(images.length==files.length){
			                        	$scope.$broadcast(eventName,{
			                        		data : images
			                        	});
			                        } else {
			                        	readFile(i+1);
			                        }
			                    });
			                }
			                reader.readAsDataURL(file);
		            	}
		            	readFile(0);
		            });
		        }
		    }
		}]);

		app.directive('ngInitial', function() {
			return {
				restrict: 'A',
				controller: ['$scope','$element','$attrs','$parse',function($scope,$element,$attrs,$parse){
					var getter, setter, val;
					val = $attrs.ngInitial || $($element).val();
					getter = $parse($attrs.ngModel);
					setter = getter.assign;
					if(!setter)
						return;
					setter($scope, val);
				}]
			};
		});

		app.directive('ngInitialCheckbox', function() {
			return {
				restrict: 'A',
				controller: ['$scope','$element','$attrs','$parse',function($scope,$element,$attrs,$parse){
					if(!$($element).is(":checked"))
						return;
					var getter, setter, val;
					val = $($element).attr("value");
					getter = $parse($attrs.ngModel);
					setter = getter.assign;
					if(!setter)
						return;
					setter($scope, val);
				}]
			};
		});

		app.directive('ngInteger', function(){
		    return {
		        require: 'ngModel',
		        controller: ["$scope","$element","$attrs","$parse",function($scope,$element,$attrs,$parse){
		            $scope.$watch($attrs.ngModel,function(){
						var getter = $parse($attrs.ngModel);
						var setter = getter.assign;
		            	setter($scope,parseInt(getter($scope)));
		            });
		        }]
		    };
		});

		app.directive("ngPlugin",["$timeout",function(){
			return {
				restrict : "A",
				link : function($scope,$element,$attrs){
					$scope.$watch($attrs.ngPlugin,function(){
						$__$.updateHtml($element.parent());
					});
				}
			}
		}]);
		
		app.directive('ngFormSuccess', function () {
		    return function ($scope, element, $attrs) {
		    	$(element).on("form-success",function(e,data){
		    		$scope.$eval($attrs.ngFormSuccess,{
		    			form_data : data
		    		});
		    		$scope.$apply();
		    	});
		    };
		});
		
		app.directive('ngOnclick', function () {
		    return function ($scope, element, $attrs) {
		    	$(element).on("click",function(e){
		    		var expression = $attrs.ngOnclick;
		    		eval(expression);
		    	});
		    };
		});
	});
	$__$.angular = {};
	$__$.angular.numApp = 0;
	$__$.angular.apply = function(objectOfScope,objectFromServer){
		$.each(objectFromServer,function(k,v){
			objectOfScope[k] = v;
		});
	};
	$__$.angular.hasInit = false;
	$__$.angular.init = function(appName,modules){
		if(modules==undefined){
			modules = [];
		}
		var app = angular.module(appName,modules);
		$__$.angular.hasInit = true;
		$__$.trigger("angular-init",app);
		//$__$.angular.app = app;
		$__$.angular.numApp++;
		if($__$.angular.numApp > 1){
			$(function(){
				$__$.angular.dynamic(appName);
			});
		}
		return app;
	};

	$__$.angular.module = $__$.angular.init;

	$__$.angular.dynamic = function(appName){
		angular.bootstrap($("[ng-app='"+appName+"']"),[appName]);
	};
	$__$.angular.data = {};
	$__$.angular.run = function(callback){
		if($__$.angular.hasInit)
			callback($__$.angular.app);
		else
			$__$.on("angular-init",function(app){
				callback(app);
			})
	};
	$__$.angular.onScope = function(callback){
		$__$.on("angular-scope",callback);
	};
	$__$.angular.triggerScope = function($scope){
		$__$.trigger("angular-scope",$scope);
	};
	$__$.angular.getScope = function(selector){
		return angular.element($(selector)).scope();
	}
})();

// animation

(function(){
	$__$.angular.run(function(app){
		app.animation(".main-view",function(){
			return {
				enter : function($element,done){
					var anim = null;
					var animSpeed = $__$.animationSpeed;
					var $firstChild = $element.children().length ? $element.children().eq(0) : null;
					if($firstChild!=null)
					{
						anim = $firstChild.attr("anim-enter");
						if(!anim)
							anim = $firstChild.attr("anim");
						if(!anim)
							anim = null;
						// speed

						var _animSpeed = $firstChild.attr("anim-speed-enter");
						if(!_animSpeed)
							_animSpeed = $firstChild.attr("anim-speed");
						if(_animSpeed)
							animSpeed = _animSpeed;
					}
					switch(anim)
					{
						case "slide":
							$firstChild.css("top",-$firstChild.height());
							$firstChild.animate({
								top : "0px"
							},animSpeed,function(){
								done();
							});
							break;
						case "fade":
							$element.hide();
							setTimeout(function(){
								$element.show();
								done();
							},animSpeed);
							break;
						default:
							done();
							break;
					}
				},
				leave : function($element,done){
					var anim = null;
					var animSpeed = 100;
					var $firstChild = $element.children().length ? $element.children().eq(0) : null;
					if($firstChild!=null)
					{
						anim = $firstChild.attr("anim-leave");
						if(!anim)
							anim = $firstChild.attr("anim");
						if(!anim)
							anim = null;// speed

						var _animSpeed = $firstChild.attr("anim-speed-leave");
						if(!_animSpeed)
							_animSpeed = $firstChild.attr("anim-speed");
						if(_animSpeed)
							animSpeed = _animSpeed;
					}
					switch(anim)
					{
						case "slide":
							$firstChild.animate({
								top : -$firstChild.height()
							},animSpeed,function(){
								done();
							});
							break;
						case "fade":
							$element.fadeOut(animSpeed,function(){
								done();
							});
							break;
						default:
							done();
							break;
					}
				}
			};
		});
	});
})();