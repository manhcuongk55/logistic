<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

require_once(dirname(__FILE__) . '/../extensions/Son/helpers/global-functions/functions.php');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Huonglee',
	"defaultController" => "home",

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(

		'application.models.*',
		'application.components.*',
		'application.components.list.*',
		'application.components.menu.*',
		'application.components.form.*',
		'application.components.helper.*',
		'ext.giix-components.*', // giix components
		'ext.YiiMailer.YiiMailer',
		"ext.Son.Son",
		"ext.Son.components.*",
		"ext.Son.helpers.*",
		"ext.Son.html.*",
		"ext.Son.models.*",
		"ext.Son.super-modules.list.*"

	),


	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123456c@',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
                        'generatorPaths' => array(
				'ext.giix-core', // giix generators
			),

		),
		"admin" => array(
			"defaultController" => "home"
		),
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'class'=>'WebUser',
			'autoUpdateFlash'=>false
		),

		'collaboratorUser'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'class'=>'CollaboratorWebUser',
			'autoUpdateFlash'=>false
		),

		'adminUser'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'class'=>'AdminWebUser',
			'autoUpdateFlash'=>false
		),

		"cache" => array(
			//'class'=>'system.caching.CFileCache',
			

			//Uncomment this when go to production
			// "class" => "ext.Son.components.FileCache",
			// "cachePath" => "_cache_data"
			//Commet this
			"class" => "CDummyCache"
		),
		
		'urlManager' => include(dirname(__FILE__)) . '/url.php',
		
		'db'=> include(dirname(__FILE__).'/db.php'),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				
				array(
					'class'=>'CWebLogRoute',
					"enabled" => isset($_GET["log"])
				),
				array(
	                'class'=>'CFileLogRoute',
	                'categories'=>'system.db.*',
	                'logFile'=>'sql.log',
	            ),
			),
		)
	),
	'params'=>include(dirname(__FILE__).'/params.php'),
	'theme'=>'giaodichtrungquoc',
	"language" => "vi"
	
);