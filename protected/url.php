<?php
return array(
	'urlFormat'=>'path',
	'showScriptName'=>false,
	'rules'=>array(
		"page_<id:\d+>_<slug:.+>" => array(
			"home/post",
			'urlSuffix'=> '.html', 
			'caseSensitive'=> false,
		),
		"content_<page:.+>" => array(
			"home/page",
			'urlSuffix'=> '.html', 
			'caseSensitive'=> false,
		),
		"<controller:\w+>/<action:\w+>/<id:\d+>/<slug:.+>" => array(
			"<controller>/<action>",
			'urlSuffix'=> '.html', 
			'caseSensitive'=> false,
		),
		"<controller:\w+>/<action:\w+>_<id:\d+>" => array(
			"<controller>/<action>",
			'urlSuffix'=> '.html', 
			'caseSensitive'=> false,
		),
		"<controller:\w+>/<action:\w+>'=>'<controller>/<action>",
	),
);