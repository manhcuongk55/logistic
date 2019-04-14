<?php
$chatBaseUrl = "http://orderhip.com:9090/static";

return array(
	"son" => array(
		"__autoloadExtensions" => array(
			"bootstrap", "font-awesome-3.2.1", "font-awesome", "bootstrap-datepicker", "select2", "icheck", "bootstrap-tagsinput",
		),
		"core" => array(
			"js" => array(
				"jquery-1.11.3.js", "core.js", "util.js", "formajax.js"
			),
			"css" => array(
				"style.css",
				"main.css"
			),
		),
		"extensions" => array(
			"bootstrap" => array(
				"css" => array(
					"bootstrap.min.css",
					"bootstrap-theme.min.css"
				),
				"js" => "bootstrap.min.js"
			),
			"font-awesome-3.2.1" => array(
				"css" => "font-awesome.css",
			),
			"font-awesome" => array(
				"css" => "font-awesome.css",
			),
			"bootstrap-datepicker" => array(
				"js" => array(
					"bootstrap-datepicker.js",
					'$__$.js'
				),
				"css" => array(
					"datepicker.css"
				)
			),
			"bootstrap-datetimepicker" => array(
				"js" => array(
					"bootstrap-datetimepicker.js",
					'$__$.js'
				),
				"css" => array(
					"bootstrap-datetimepicker.css"
				),
				"dependency" => array(
					"moment"
				)
			),
			"bootstrap-tagsinput" => array(
				"js" => array(
					"typeahead.js", "bootstrap-tagsinput.js", '$__$.js'
				),
				"css" => array(
					"bootstrap-tagsinput.css", "typeahead.css"
				)
			),
			"bootstrap3-typeahead" => array(
				"js" => array(
					"bootstrap3-typeahead.js"
				),
			),
			"moment" => array(
				"js" => array(
					"moment.js"
				)
			),
			"bootstrap-fileinput" => array(
				"css" => array(
					"fileinput.css"
				),
				"js" => array(
					"fileinput.js", '$__$.js'
				)
			),
			"upload-preview" => array(
				"css" => "upload-preview.css",
				"js" => "upload-preview.js"
			),
			"select2" => array(
				"css" => array(
					"select2.css" ,"select2-metronic.css"
				), 
				"js" => array(
					"select2.js", '$__$.js'
				)
			),
			"angular" => array(
				"js" => array(
					"angular.min.js",
					"angular-animate.min.js",
					"angular-route.min.js",
					"angular-touch.min.js",
					'$__$.js'
				),
				"angular-ui" => array(
					"js" => "angular-ui.js"
				),
				"position" => "top"
			),
			"summernote" => array(
				"js" => array(
					"summernote.js", '$__$.js'
				),
				"css" => array(
					"summernote.css", "summernote-bs3.css"
				)
			),
			"jquery-ui" => array(
				"js" => array(
					"jquery-ui.js"
				),
				"css" => array(
					"jquery-ui.css",
					"jquery-ui.theme.css"
				)
			),
			"tinymce" => array(
				"js" => array(
					"tinymce.min.js"
				)
			),
			"flot" => array(
				"js" => array(
					"jquery.flot.min.js", 
			        "jquery.flot.resize.min.js", 
			        "jquery.flot.pie.min.js", 
			        "jquery.flot.stack.min.js", 
			        "jquery.flot.crosshair.min.js", 
			        "jquery.flot.categories.min.js", 
			        "jquery.flot.time.min.js",
			        '$__$.js' 
				)
			),
			"jquery.rateit" => array(
				"js" => array(
					"jquery.rateit.js",
					'$__$.js'
				),
				"css" => true
			),
			"colorpicker" => array(
				"js" => array(
					"colorpicker.js",
					'$__$.js'
				),
				"css" => array(
					"colorpicker.css"
				)
			),
			"jquery.jsonview" => array(
				"js" => array(
					"jquery.jsonview.js"
				),
				"css" => array(
					"jquery.jsonview.css"
				)
			),
			"a-list" => array(
				"js" => array(
					"list.js"
				),
				"css" => array(
					"list.css"
				),
				"dependency" => array(
					
				),
				"position" => "top"
			),
			"admin-table" => array(
				"css" => array(
					"admin-table.css"
				)
			),
			"icheck" => array(
				"js" => array(
					"icheck.js", '$__$.js'
				),
				"css" => array(
					"square/blue.css"
				)
			),
			"slug" => array(
				"js" => array(
					"slug.js"
				)
			),
			"bxslider" => array(
				"js" => array(
					"plugins/jquery.easing.1.3.js", "plugins/jquery.fitvids.js", "jquery.bxslider.js"
				),
				"css" => array(
					"jquery.bxslider.css"
				)
			),
			"maskmoney" => array(
				"js" => array(
					"jquery.maskMoney.js", '$__$.js'
				)
			),
			"latlng-picker" => array(
				"js" => array(
					"https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places" => 1,
					"latlng-picker.js"
				)
			),
			"dropzone" => array(
				"js" => array(
					"dropzone.4.3.js"
				),
				"css" => array(
					"dropzone.css", "basic.css"
				)
			),
			"fancybox" => array(
				"js" => array(
					"jquery.fancybox.pack.js"
				),
				"css" => array(
					"jquery.fancybox.css"
				)
			),
			"featherlight" => array(
				"js" => array(
					"featherlight.min.js", "featherlight.gallery.min.js"
				),
				"css" => array(
					"featherlight.min.css", "featherlight.gallery.min.css"
				)
			),
			"js-cookie" => array(
				"js" => array(
					"js-cookie.js"
				)
			)
		)
	),
	"__new_theme__" => array(
		"__autoloadBase" => true,
		"__autoloadExtensions" => array(),
		"__autoloadCustom" => array(
			"js" => array(),
			"css" => array()
		),
		"extensions" => array()
 	),
	"simple" => array(
		"__autoloadBase" => true,
		"__autoloadExtensions" => array(),
		"__autoloadCustom" => array(
			"js" => array(),
			"css" => array()
		),
		"extensions" => array()
 	),
	"metronic" => array(
		"__autoloadBase" => true,
		"__autoloadExtensions" => array(
			"metronic"
		),
		"__autoloadCustom" => array(
			"js" => array(),
			"css" => array()
		),
		"extensions" => array(
			"metronic" => array(
				"css" => array(
					"style-metronic.css", "style.css", "style-responsive.css", "themes/default.css", "custom.css"
				),
				"js" => array(
					"app.js"
				)
			),
			"bootstrap" => array(
				"css" => array(
					"bootstrap.min.css",
				),
				"js" => array(
					"bootstrap.js"
				)
			),
			"data-tables" => array(
				"css" => array(
					"DT_bootstrap.css"
				)
			)
		)
 	),
 	"metronic2" => array(
		"__autoloadBase" => true,
		"__autoloadExtensions" => array(
			"metronic2"
		),
		"__autoloadCustom" => array(
			"js" => array(),
			"css" => array()
		),
		"extensions" => array(
			"metronic2" => array(
				"css" => array(
					"http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" => 1,
					"components-rounded.css", "plugins.css", "layout.css", "themes/light.css", "custom.css", "dataTables.bootstrap.css"
				),
				"js" => array(
					"metronic.js", "layout.js"
				)
			),
			"bootstrap" => array(
				"css" => array(
					"bootstrap.min.css",
				),
				"js" => array(
					"bootstrap.min.js"
				)
			)
		)
 	),
	"giaodichtrungquoc" => array(
		"__autoloadBase" => false,
		"__autoloadExtensions" => array(
			"a-list", "mozar", "giaodichtrungquoc", "bootstrap-datepicker", "select2", "icheck", "moment", "featherlight", "slick", "angular", "maskmoney", "nivo-slider", "js-cookie", "chat", "slick", "bootstrap3-typeahead"
		),
		"__autoloadCustom" => array(
			"js" => array(
			),
			"css" => array()
		),
		"extensions" => array(
			"giaodichtrungquoc" => array(
				"css" => array(
					//"https://fonts.googleapis.com/css?family=Lato:400,400italic,900,700,700italic,300' rel='stylesheet' type='text/css" => 1,
					"main.css", "all.css"
				),
				"js" => array(
					"main.js"
				)
			),
			"chat" => array(
				"css" => array(
					"$chatBaseUrl/css/style.css" => 1
				),
				"js" => array(
					"$chatBaseUrl/js/scrollglue.js" => 1,
					"$chatBaseUrl/js/common.js" => 1,
					"$chatBaseUrl/js/socket.io.js" => 1,
					"$chatBaseUrl/js/chat_client.js" => 1,
					"$chatBaseUrl/js/chat_client_data_container.js" => 1,
					"$chatBaseUrl/js/app.js" => 1,
				),
			),
			"mozar" => array(
				"css" => array(
					"bootstrap.min.css", "owl.carousel.css", "font-awesome.min.css", "jquery-ui.css", "meanmenu.min.css", "animate.css", "style.css", "responsive.css"
				),
				"js" => array(
					"vendor/modernizr-2.8.3.min.js", "bootstrap.min.js", "wow.min.js", "jquery.meanmenu.js", "owl.carousel.min.js", "jquery-price-slider.js", "jquery.scrollUp.min.js", "jquery.countdown.min.js", "jquery.sticky.js", "jquery.elevateZoom-3.0.8.min.js", "plugins.js", "main.js"
				)
			),
			"nivo-slider" => array(
				"css" => array(
					"nivo-slider.css", "preview.css"
				),
				"js" => array(
					"jquery.nivo.slider.js", "home.js"
				)
			),
			"slick" => array(
				"css" => array(
					"slick.css", "slick-theme.css"
				),
				"js" => array(
					"slick.min.js"
				)
			)
		)
	),
);
