/* List */
var SList = {};


SList.ListManager = function(){
	var $self = this;
	this.lists = {};
	this.config = {};

	this.init = function(){
		
	}

	this.addList = function(list){
		$self.lists[list.config.alias] = list;
		$self.trigger("list-added",list);
		$self.trigger("list-" + list.config.alias + "-added",list);
	}

	this.onList = function(listAlias,callback){
		var eventName = "list-" + listAlias + "-added";
		if(this.lists[listAlias]!=undefined){
			callback(this.lists[listAlias]);
			return;
		}
		$self.on(eventName,callback);
	}

	this.listen = function(listAlias,eventName,callback){
		if($self.lists[listAlias]!=undefined){
			$self.lists[listAlias].on(eventName,callback);
		} else {
			$self.on("list-added",function(list){
				if(list.config.alias!=listAlias)
					return;
				list.listOn(eventName,callback);
			})
		}
	}
	
	this.getList = function(alias){
		return this.lists[alias];
	}
}
$__$.applyEventHandler(SList.ListManager);
(function(){
	var instance = null;
	SList.ListManager.getInstance = function(){
		if(instance==null){
			instance = new SList.ListManager();
			instance.init();
		}
		return instance;
	}
})();

SList.List = function(){
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;
	$self.id = $elem.attr("list-id");
	$self.config = SList.ListManager.getInstance().config[$self.id];
	$self.currentQuery = $self.config.query;
	$self.currentUpdateItem = null;

	$self.core = new SList.Core($self);
	$self.base = new SList.Base($self);
	$self.php = new SList.Php($self);
	$self.jquery = new SList.Jquery($self);

	var findV1 = $self.$elem.find;
	$self.$elem.find = function(selector){
		var $items = findV1.call($self.$elem,selector);
		return $items.filter(function(){
			return $(this).parents("[list-id]:first").attr("list-id")==$self.id;
		});
	}

	$__$.applyEventHandlerToObject($self);

	$self.listTrigger = function(eventName,data){
		var triggerData = $.extend(data,{
			list : $self
		});
		$self.trigger(eventName,triggerData);
	}

	$self.listOn = function(eventName,callback){
		$self.on(eventName,callback);
	}

	$self.onInit = function(){
		// action data
		SList.ListManager.getInstance().addList($self);
		$self.core.init();
		$self.listTrigger("list-inited");
	};

	$self.getSelectedItems = function(){
		var arr = $self.$elem.find("input[list-selected-items]:checkbox:checked").map(function(){
	      return $(this).val();
	    }).get();
	    return arr;
	}

	$self.getSelectedObjects = function(){
		var activeClass = $self.config.itemSelectable.selectedClass;
		var arr = $self.$elem.find("[list-item]." + activeClass).map(function(){
	      return $(this).data();
	    }).get();
	    return arr;
	}

	$self.doSelect = function(itemId,preventTrigger,preventUpdateUI){
		var $item = $self.getItem(itemId);
		var activeClass = $self.config.itemSelectable.selectedClass;
		if(!$self.config.itemSelectable.multiple){
			$self.$elem.find("[list-item]."+activeClass).each(function(){
				var id = $(this).data("id");
				$self.doDeselect(id,true);
			});
		}
		$item.addClass(activeClass);
		if(!preventTrigger){
			$self.listTrigger("list-item-selected",{
				id : itemId
			});
			$self.listTrigger("list-selected-items-changed");
		}

		if(!preventUpdateUI){
			$item.find("[list-selected-items]").realVal(1).trigger("change",true);
		}
	}

	$self.doDeselect = function(itemId,preventTrigger,preventUpdateUI){
		var $item = $self.getItem(itemId);
		var activeClass = $self.config.itemSelectable.selectedClass;
		$item.removeClass(activeClass);
		if(!preventTrigger){
			$self.listTrigger("list-item-deselected",{
				id : itemId
			});
			$self.listTrigger("list-selected-items-changed");
		}
		if(!preventUpdateUI){
			$item.find("[list-selected-items]").realVal(0).trigger("change",true);
		}
	}

	// query

	$self.setDynamicInput = function(name,value){
		var obj = $self.config.dynamicInputs;
		if(typeof name == "object"){
			for(var i in name){
				obj[i] = name[i];
			}
		} else {
			obj[name] = value;
		}
		var queryString = $self.buildQueryString(obj);
		$self.config.url = $self.config.baseUrlWithoutDynamicInputs + "&" + queryString + "&";
	}

	$self.getQueryFromString = function(queryString){
		var vars = queryString.split('&');
		var queryFromString = {};
		for (var i = 0; i < vars.length; i++) {
			var pair = vars[i].split('=');
			var name = decodeURIComponent(pair[0]);
			var value = decodeURIComponent(pair[1]);
			queryFromString[name] = value;
		}
		return queryFromString;
	}

	/*$self.buildQueryString = function(obj){
		function getStrArr(obj,strArr){
			for(var p in obj){
				if(obj[p]==undefined)
					continue;
				if (obj.hasOwnProperty(p)) {
					if(typeof obj[p] == "object"){
						getStrArr(obj[p],strArr);
						continue;
					}
					var p2 = $__$.toUnderscore(p);
				 	strArr.push(encodeURIComponent(p2) + "=" + encodeURIComponent(obj[p]));
				}
			}
		}
		var strArr = [];
		getStrArr(obj,strArr);
		return strArr.join("&");
	}*/

	$self.buildQueryString = function(obj, prefix) {
		var str = [];
		for(var p in obj) {
			if (obj.hasOwnProperty(p)) {
			  var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
			  str.push(typeof v == "object" ?
			    $self.buildQueryString(v, k) :
			    encodeURIComponent(k) + "=" + encodeURIComponent(v));
			}
		}
		return str.join("&");
	}

	$self.buildQueryFromInputForm = function(){
		var query = {};
		if($self.config.actions.action.data.search){
			$self.$elem.find("[list-input='search'][name]").each(function(){
				var searchTerm = $.trim($(this).val());
				if(searchTerm)
					query.search = searchTerm;
			});
		}
		if($self.config.actions.action.data.advancedSearch){
			$self.$elem.find("[list-input='advanced-search'][name]").each(function(){
				var attr = $(this).attr("list-input-advanced-search");
				var hasAdvancedSearchOfTheAttr = $self.config.query.advancedSearch && $self.config.query.advancedSearch[attr]!=undefined && $self.config.query.advancedSearch[attr]!=null;
				if((hasAdvancedSearchOfTheAttr || $(this).is("[changed]")))
					query[$(this).attr("name")] = $(this).realVal();
			});
		}
		if($self.config.actions.action.data.order){
			$self.$elem.find("[list-input='order_by'][name][changed]").each(function(){
				query.order_by = $(this).val();
			});
			$self.$elem.find("[list-input='order_type'][name][changed]").each(function(){
				query.order_type = $(this).val();
			});
		}
		if($self.config.actions.action.data.limit){
			$self.$elem.find("[list-input='limit'][name][changed]").each(function(){
				query.limit = $(this).val();
			});
		}
		if($self.config.actions.action.data.page){
			$self.$elem.find("[list-input='page'][name][changed]").each(function(){
				query.page = $(this).val();
			});
		}
		return query;
	}

	$self.getItem = function(id){
		return $self.$elem.find("[list-item='"+id+"']");
	}

}

SList.Core = function($self){
	var $core = this;
	var mode = $self.config.mode;

	$core.init = function(){
		$self[mode].init();
	}

	$core.refresh = function(){
		$self[mode].refresh();
	}

	$core.search = function(withPage){
		$self[mode].search(withPage);
	}

	$core.actionDeleteTogether = function(){
		$self[mode].actionDeleteTogether();
	}

	$core.extendedActionTogether = function(actionName){
		$self[mode].extendedActionTogether(actionName);
	}

	$core.actionDelete = function(id){
		$self[mode].actionDelete(id);
	}

	$core.actionUpdate = function(id){
		$self[mode].actionUpdate(id);
	}

	$core.extendedAction = function(actionName,id){
		$self[mode].extendedAction(actionName,id);
	}

	$core.actionInlineEdit = function(id,attr,value){
		$self[mode].actionInlineEdit(id,attr,value);
	}
}

SList.Base = function($self){
	var $base = this;

	function getActionUrl(actionName,queryString){
		if(queryString==undefined)
			queryString = "";
		queryString += "&action=" + actionName;
		var url = $self.config.url;
		if(!url)
			url = $self.config.baseUrl;
		return url + queryString;
	}

	$base.init = function(){
		// data

		$self.$elem.find("[list-do-refresh]").on("click",function(){
			$self.core.refresh();
		});
		if($self.config.actions.action.data.search){
			$self.$elem.find("[list-do-search]").on("click",function(){
				$self.core.search();
			});
			$self.$elem.find("[list-input='search']").listenToEnterKey(null,true).each(function(){
				$(this).on("enter",function(){
					$self.core.search();
				});
			});
		}
		if($self.config.actions.action.data.advancedSearch){	
			$self.$elem.find("[list-input-advanced-search-onchange]").each(function(){
				var type = $(this).attr("list-input-advanced-search-onchange");
				if(type=="enter"){
					$(this).listenToEnterKey(null,true).on("enter",function(){
						$self.core.search();
					});
				} else {
					$(this).on(type,function(){
						$self.core.search();
					});
				}
				
			});
		}
		if($self.config.actions.action.data.order){
			$self.$elem.find("[list-do-order]").each(function(){
				var $elemDoOrder = $(this);
				$(this).parent().on("click",function(){
					var orderBy = $elemDoOrder.attr("list-order-by");
					var orderType = $elemDoOrder.attr("list-order-type");
					if(orderType=="asc"){
						orderType = "desc";
					} else {
						orderType = "asc";
					}
					$self.$elem.find("[list-input='order_by']").val(orderBy).trigger("change-without-trigger-changed").trigger("changed-without-check");
					$self.$elem.find("[list-input='order_type']").val(orderType).trigger("change-without-trigger-changed").trigger("changed");
				});
			});
			$self.$elem.find("[list-input='order_type']").on("changed",function(){
				$self.core.search();
			});
		}
		if($self.config.actions.action.data.limit){
			$self.$elem.find("[list-input='limit']").on("changed",function(){
				$self.core.search();
			});
		}
		if($self.config.actions.action.data.page){
			$self.$elem.find("[list-input='page']").on("changed",function(){
				$self.core.search(true);
			});
		}

		// action insert

		// select

		if($self.config.itemSelectable){
			$self.$elem.find("[list-do-selected-all-items]").on("change",function(){
				var value = $(this).realVal();
				$self.$elem.find("[list-selected-items]").realVal(value).trigger("change");
			});
		}

		// action deleteMultiple

		if($self.config.actions.actionTogether.deleteTogether){
			$self.$elem.find("[list-action-delete-together]").click(function(e){
				$self.core.actionDeleteTogether();
				//return $__$.prevent(e);
			});
		}

		// action together

		$self.$elem.find("[list-extended-action-together]").click(function(e){
			var actionTogether = $(this).attr("list-extended-action-together");
			$self.core.extendedActionTogether(actionTogether);
			//return $__$.prevent(e);
		});

		// action export
		if($self.config.actions.action.data.export){
			$self.$elem.find("[list-export]").click(function(){
				var type = $(this).attr("list-export");
				var query = $self.buildQueryFromInputForm();
				delete query.limit;
				query["export_type"] = type;
				var queryString = $self.buildQueryString(query);
				var url = getActionUrl("export_download",queryString);
				location.href = url;
			});
		}
		
		// update item

		$self.listOn("list-items-updated",function(data){
			data.$items.each(function(){
				var $item = $(this);
				var id = $item.data($self.config.primaryField);
				if($self.config.actions.action.delete){
					$item.find("[item-action-delete]").click(function(){
						$self.core.actionDelete(id);
					});
				}

				if($self.config.actions.action.update){
					$item.find("[item-action-update]").click(function(){
						$self.core.actionUpdate(id);
					});
				}

				$item.find("[item-extended-action]").each(function(){
					var triggerType = "click";
					var actionName = $(this).attr("item-extended-action");
					$(this).on(triggerType,function(){
						$self.core.extendedAction(actionName,id);
					});
				});

				$.each($self.config.fields,function(fieldName,fieldConfig){
					if(fieldConfig.inlineEditEnabled){
						$item.find("[inline-edit][inline-edit-attr='"+fieldName+"']").on("inline-edit-changed",function(e,data){
							$self.core.actionInlineEdit(id,fieldName,data.value);
						});
					}
				});

				if($self.config.itemSelectable){
					var $checkbox = $item.find("[list-selected-items]");
					if($self.config.itemSelectable.type=="click"){
						$item.click(function(){
							var selected = $checkbox.realVal();
							$checkbox.realVal(selected ? 0 : 1).trigger("change");
						});
					}
					$checkbox.on("change",function(e,preventUpdate){
						if(preventUpdate)
							return;
						var value = $(this).realVal();
						if(value){
							$self.doSelect(id,false,true);
						} else {
							$self.doDeselect(id,false,true);
						}
					});
				}
			});
		});

		// update

		if($self.config.actions.action.update){
			$self.listOn("item-action-update-done",function(){
				var message;
				if(message = $self.config.updateSuccessMessage){
					alert(message);
				}
				$self.$elem.find("[list-form-update]").reset();
				$self.$elem.find("[list-form-update-submit]").attr("disabled","");
			});
		}

		if($self.config.actions.action.insert){
			$self.listOn("item-action-insert-done",function(){
				var message;
				if(message = $self.config.insertSuccessMessage){
					alert(message);
				}
				$self.$elem.find("[list-form-insert]").reset();
			});
		}
	}

	$base.search = function(withPage){
		var query = $self.buildQueryFromInputForm();
		if(!withPage)
			delete query.page;
		return query;
	}

	$base.loadData = function(query,callback){
		if(!query){
			query = $self.currentQuery;
		}
		$self.currentQuery = query;
		var queryString = $self.buildQueryString(query);
		var url = getActionUrl("data",queryString);
		$self.$elem.find("[list-loading]").show();
		$.ajax({
			type : "get",
			url : url,
			success : function(json){
				$self.$elem.find("[list-loading]").hide();
				$__$.handleJSON(json,function(data){
					callback(data);
				});
			}
		});
		return queryString;
	}

	$base.actionDeleteTogether = function(callback){
		var selectedIds = $self.getSelectedItems();
		if(!selectedIds)
			return;
		var message = $self.$elem.find("[list-action-delete-together]").attr("list-message");
		if(!message){
			message = "Are you sure to delete these items. This cannot be undone!";
		}
		$__$.confirm(message,function(result){
			if(!result)
				return;
			$self.listTrigger("list-action-delete-together-request",{
				ids : selectedIds
			});
			$.ajax({
				type : "post",
				url : getActionUrl("delete_together"),
				data : {
					ids : selectedIds
				},
				success : function(json){
					$__$.handleJSON(json,function(data){
						$self.listTrigger("list-action-delete-together-done",{
							ids : selectedIds
						});
						callback && callback(data);
					})
				}
			});
		});
	}

	$base.extendedActionTogether = function(actionName,callback){
		var selectedIds = $self.getSelectedItems();
		if(!selectedIds)
			return;
		var $button = $self.$elem.find("[list-extended-action-together='"+actionName+"']");
		var message = $button.attr("list-message");
		var sendRequest = function(){
			$self.listTrigger("list-extended-action-together-request",{
				actionName : actionName,
				ids : selectedIds
			});
			var modalSelector;
			if(modalSelector = $button.attr("list-form-modal")){
				var $formModal = $(modalSelector);
				$formModal.find('[name="ids"]').val(selectedIds.join(","));
				$formModal.modal("show");
			} else {
				$.ajax({
					type : "post",
					url : getActionUrl("extended_action_together","action_name=" + actionName),
					data : {
						ids : selectedIds
					},
					success : function(json){
						$__$.handleJSON(json,function(data){
							$self.listTrigger("list-extended-action-together-done",{
								actionName : actionName,
								ids : selectedIds
							});
							callback && callback(data);
						})
					}
				});
			}
		}
		if(message){
			$__$.confirm(message,function(result){
				if(!result)
					return;
				sendRequest();
			});	
		} else {
			sendRequest();
		}
	}

	$base.actionDelete = function(id,callback){
		var $item = $self.getItem();
		var message = $item.find("[item-action-delete]").attr("item-message");
		if(!message)
			message = "Are you sure to delete this item?";
		$__$.confirm(message,function(result){
			if(!result)
				return;
			$self.listTrigger("item-action-delete-request",{
				id : id
			});
			$.ajax({
				type : "post",
				url : getActionUrl("delete"),
				data : {
					id : id
				},
				success : function(json){
					$__$.handleJSON(json,function(data){
						$self.listTrigger("item-action-delete-done",{
							id : id
						});
						callback && callback(data);
					})
				}
			});
		});
	}

	$base.actionUpdate = function(id,callback){
		var $item = $self.getItem(id);
		$self.currentUpdateItem = $item.data();
		var $form = $self.$elem.find("[list-form-update]");
		$form.find("[item-attr]").each(function(){
			var attr = $(this).attr("item-attr");
			var value = $self.currentUpdateItem[attr];
			if(value instanceof Array || value instanceof Object){
				value = JSON.stringify(value);
			}
			$(this).realVal(value);
			var displayAttr;
			if(displayAttr = $(this).attr("item-display-attr")){
				$(this).data("display",$self.currentUpdateItem[displayAttr])
			}
			$(this).trigger("change");
		});
		$self.$elem.find("[list-form-update-submit]").removeAttr("disabled");
		$self.listTrigger("item-action-update-click",{
			id : id
		});
	}

	$base.extendedAction = function(actionName,id,callback){
		var $item = $self.getItem(id);
		var $button = $item.find("[item-extended-action='"+actionName+"']");
		var message = $button.attr("item-message");
		var sendRequest = function(){
			$self.listTrigger("item-extended-action-action-request",{
				actionName : actionName,
				id : id
			});
			var modalSelector;
			if(modalSelector = $button.attr("item-form-modal")){
				var $formModal = $(modalSelector);
				$formModal.find('[name="id"]').val(id);
				$formModal.modal("show");
				var $form = $formModal.find("form");
				if($form.data("handle-form-success"))
					return;
				$form.data("handle-form-success",true);
				$form.on("form-success",function(e,obj){
					$formModal.on('hidden.bs.modal', function (e) {
						callback && callback();
					});
					$formModal.modal("hide");
				})
			} else {
				$.ajax({
					type : "post",
					url : getActionUrl("extended_action","action_name=" + actionName),
					data : {
						id : id
					},
					success : function(json){
						$__$.handleJSON(json,function(data){
							$self.listTrigger("item-extended-action-action-done",{
								actionName : actionName,
								id : id
							});
							callback && callback(data);
						})
					}
				});
			}
		}
		if(message){
			$__$.confirm(message,function(result){
				if(!result)
					return;
				sendRequest();
			});
		} else {
			sendRequest();
		}
		
	}

	$base.actionInlineEdit = function(id,attr,value,callback){
		$self.listTrigger("item-action-inline-edit-request",{
			id : id,
			attr : attr,
			value : value
		});
		$.ajax({
			type : "post",
			url : getActionUrl("update_inline"),
			data : {
				id : id,
				prop : attr,
				value : value
			},
			success : function(json){
				$__$.handleJSON(json,function(data){
					$self.listTrigger("item-action-inline-edit-done",{
						id : id,
						attr : attr,
						value : value
					});
					callback && callback(data);
				})
			}
		});
	}
}

SList.Php = function($self){
	var $php = this;

	$php.init = function(){
		$self.base.init();

		if(location.search.length > 1){
			var query = $self.getQueryFromString(location.search);
			$.each(query,function(name,value){
				$self.$elem.find("[list-input][name='"+name+"']").trigger("changed-without-check");
			});
		}

		var $items = $self.$elem.find("[list-items]").children();
		$self.listTrigger("list-items-updated",{
			"$items" : $items
		});

		if($self.config.actions.action.insert){
			$self.$elem.find("[list-form-insert]").getPlugin("formAjax").option("onResultSuccess",function(data){
				$self.core.refresh();
				$self.listTrigger("item-action-insert-done");
			});
		}

		if($self.config.actions.action.update){
			$self.$elem.find("[list-form-update]").getPlugin("formAjax").option("onResultSuccess",function(data){
				$self.core.refresh();
				$self.listTrigger("item-action-update-done");
			});
		}

		$self.listTrigger("list-load-data-done");
	}

	$php.refresh = function(){
		$self.listTrigger("list-load-data-request");
		location.reload();
	}

	$php.search = function(withPage){
		$self.listTrigger("list-load-data-request");
		var query = $self.base.search(withPage);
		var baseUrl = $self.config.baseUrl;
		/*if(baseUrl[baseUrl.length-1]!="?"){
			baseUrl = "?" + baseUrl;
		}*/
		location.href = baseUrl + $self.buildQueryString(query);
	}

	// action together

	$php.actionDeleteTogether = function(){
		$self.base.actionDeleteTogether(function(){
			$self.core.refresh();
		});
	}

	$php.extendedActionTogether = function(actionName){
		$self.base.extendedActionTogether(actionName, function(){
			$self.core.refresh();
		});
	}

	$php.actionDelete = function(id){
		$self.base.actionDelete(id,function(){
			$self.core.refresh();
		});
	}

	$php.actionUpdate = function(id){
		$self.base.actionUpdate(id,function(){
			$self.core.refresh();
		});
	}

	$php.extendedAction = function(actionname,id){
		$self.base.extendedAction(actionName,id,function(){
			$self.core.refresh();
		});
	}

	$php.actionInlineEdit = function(id,attr,value){
		$self.base.actionInlineEdit(id,attr,value,function(){
			$self.core.refresh();
		})
	}
}

SList.Jquery = function($self){
	var $jquery = this;
	var hashForceChanged = false;
	var itemHasNotBeenReplaced = true;

	$jquery.init = function(){
		$self.base.init();

		if($self.config.actions.action.data.order){
			$self.$elem.find("[list-input='order_type']").on("change",function(){
				var orderBy = $self.$elem.find("[list-input='order_by']").val();
				var orderType = $self.$elem.find("[list-input='order_type']").val();
				$self.$elem.find("[list-order-display][list-order-by][list-order-type]").attr("hidden","");
				$self.$elem.find("[list-order-display][list-order-by='"+orderBy+"'][list-order-type='"+orderType+"'],[list-order-display][list-order-by!='"+orderBy+"'][list-order-type='nothing']").removeAttr("hidden");
				$self.$elem.find("[list-do-order][list-order-by='"+orderBy+"']").attr("list-order-type",orderType);
			});
		}
		if($self.config.trackUrl){
			$(window).on("hashchange",function(){
				$jquery.searchWithHashParams();
			});
		}

		if(!$self.config.preloadData){
			if($self.config.trackUrl && location.hash){
				$jquery.searchWithHashParams();
			} else if($self.config.refreshOnInit) {
				$self.core.refresh();
			}
		} else {
			$self.$elem.find("[list-loading]").hide();
		}

		if($self.config.itemSelectable){
			$self.listOn("list-refresh list-search",function(){
				$self.$elem.find("[list-do-selected-all-items]").realVal(0).trigger("change");
			})
		}

		if($self.config.actions.action.insert){
			$self.$elem.find("[list-form-insert]").getPlugin("formAjax").option("onResultSuccess",function(data){
				$self.core.refresh();
				$self.listTrigger("item-action-insert-done");
			});
		}

		if($self.config.actions.action.update){
			$self.$elem.find("[list-form-update]").getPlugin("formAjax").option("onResultSuccess",function(data){
				$self.core.refresh();
				$self.listTrigger("item-action-update-done");
			});
		}

		$self.$elem.find("[list-load-more]").click(function(){
			$jquery.loadMore();
		});

		if($self.config.infiniteScroll){
			var callback =  function(){
			    if( $(window).scrollTop() >= $(document).height() - window.innerHeight - 100) {
			     	$jquery.loadMore();
			    }
			};
			$(function(){
				$(window).on('scroll',callback);
				callback();
			});
			$self.$elem.find("[list-input='page']").val(1);
		}
	}

	// data

	$jquery.refresh = function(fromInitConfig){
		$self.listTrigger("list-refresh-request");
		var query = $self.currentQuery;
		if(fromInitConfig){
			query = $self.config.query;
		}
		$jquery.loadItemsHtml(query,false,function(){
			$self.listTrigger("list-refresh-done");
		});
	}

	$jquery.search = function(withPage){
		var query = $self.base.search(withPage);
		$self.listTrigger("list-search-request");
		$jquery.loadItemsHtml(query,true,function(){
			$self.listTrigger("list-search-done");
		});
	}

	$jquery.forceChangeHash = function(queryString){
		hashForceChanged = true;
		if(queryString)
			location.hash = "!" + queryString;
		else
			location.hash = "";
	}

	$jquery.searchWithHashParams = function(applyQueryToHtml){
		if(hashForceChanged){
			hashForceChanged = false;
			return;
		}
		var queryString;
		if(location.hash.length < 2){
			queryString = "";
		} else {
			var queryString = location.hash.substring(2);
		}
		$jquery.applyQueryStringToHtml(queryString);
		var query = $self.buildQueryFromInputForm();
		$jquery.loadItemsHtml(query,false);
	}

	$jquery.loadItemsHtml = function(query,forceChangeHash,callback){
		if($jquery.noMoreItems && $self.loadMoreFlag){
			return;
		}
		if($jquery.loading){
			return;
		}
		$jquery.loading = true;
		$self.listTrigger("list-load-data-request");
		var queryString = $self.base.loadData(query,function(data){
			var $items = $(data.data_html);
			if($self.loadMoreFlag){
				$self.loadMoreFlag = false;
				$self.$elem.find("[list-items]").appendPn($items);
				if($items.children().length==0){
					$self.$elem.find("[list-no-more-items]").show();
					$self.$elem.find("[list-load-more]").hide();
					$jquery.noMoreItems = true;
				} else {
					$self.$elem.find("[list-no-more-items]").hide();
					$self.$elem.find("[list-load-more]").show();
				}

			} else {
				$jquery.noMoreItems = false;
				$self.$elem.find("[list-items]").htmlPn($items);
				if($items.children().length==0){
					$self.$elem.find("[list-no-items]").show();
					$self.$elem.find("[list-has-items]").hide();
				} else {
					$self.$elem.find("[list-no-items]").hide();
					$self.$elem.find("[list-has-items]").show();
				}
				var $pageInput = $self.$elem.find("[list-input='page']");
				var currentPage = parseInt($pageInput.val());
				if(currentPage > 1){
					$("body").scrollTop($self.$elem.offset().top);
				}

			}
			$self.$elem.find("[list-pagination]").replaceWithPn(data.pagination_html)
			$self.$elem.find("[list-pagination] a[list-page]").click(function(e){
				var page = $(this).attr("list-page");
				$jquery.goToPage(page);
				return $__$.prevent(e);
			});
			$self.$elem.find("[list-total-count]").text(data.num_row_total);
			$self.listTrigger("list-items-updated",{
				"$items" : $items
			});
			callback && callback();
			$self.listTrigger("list-load-data-done");
			$jquery.loading = false;
		});
	
		if($self.loadMoreFlag)
			return;

		if(forceChangeHash && $self.config.trackUrl)
			$jquery.forceChangeHash(queryString);
	}

	$jquery.loadMore = function(){
		if($jquery.loading){
			return;
		}
		var $pageInput = $self.$elem.find("[list-input='page']");
		var currentPage = parseInt($pageInput.val());
		if(isNaN(currentPage))
			currentPage = 1;
		$self.loadMoreFlag = true;
		$pageInput.val(currentPage+1).trigger("change");
		window.addEventListener('unload',function(){});
	}

	$jquery.goToPage = function(page){
		$self.$elem.find("[list-input='page']").val(page).trigger("change");
	}

	$jquery.applyQueryStringToHtml = function(queryString){
		var queryFromString = $self.getQueryFromString(queryString);
		var query = $.extend({},$self.config.defaultQuery,queryFromString);
		$self.$elem.find("[list-input='advanced-search']").each(function(){
			$(this).realVal("").trigger("change-without-trigger-changed");
		});
		$.each(query,function(name,value){
			var name2 = $__$.toUnderscore(name);
			var $listInput = $self.$elem.find("[list-input][name='"+name2+"']");
	        if($listInput.realVal()==value)
	        	return;
	        $listInput.realVal(value).trigger("change-without-trigger-changed");
		});
		if($self.config.actions.action.data.advancedSearch){
			var advancedSearchName = "advanced-search";
			var advancedSearchArray = {};
			function processArray(name,arr){
				$.each(arr,function(key,value){
					var newName = name + "[" + key + "]";
					if(typeof value == "object"){
						processArray(newName,value)
					} else {
						advancedSearchArray[newName] = value;
					}
				});
			}
			processArray(advancedSearchName,$self.config.defaultQuery.advancedSearch);
			$.each(advancedSearchArray,function(key,value){
				if(queryFromString[key]!=undefined) // has been set
					return;
				$self.$elem.find("[list-input='advanced-search'][name='"+key+"']").realVal(value).trigger("change-without-trigger-changed");
			});
		}
	}

	// action together

	$jquery.actionDeleteTogether = function(){
		$self.base.actionDeleteTogether(function(){
			$self.core.refresh();
		});
	}

	$jquery.extendedActionTogether = function(actionName){
		$self.base.extendedActionTogether(actionName, function(){
			$self.core.refresh();
		});
	}

	$jquery.actionDelete = function(id){
		$self.base.actionDelete(id,function(){
			$self.getItem(id).remove();
		});
	}

	$jquery.actionUpdate = function(id){
		$self.base.actionUpdate(id);
	}

	$jquery.extendedAction = function(actionName,id){
		$self.base.extendedAction(actionName,id,function(){
			$self.core.refresh();
		});
	}

	$jquery.actionInlineEdit = function(id,attr,value){
		$self.base.actionInlineEdit(id,attr,value,function(){
			$self.core.refresh();
		})
	}
}

$__$.registerJQueryPlugin("sList",SList.List,{},"[list-id]");