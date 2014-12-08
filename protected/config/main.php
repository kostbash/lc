<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Курсис',
        'timeZone' => 'Europe/Moscow',
        'sourceLanguage'=>'ru_RU',
        'language' => 'ru', 
	// preloading 'log' component
	'preload'=>array('log', 'maintenanceMode'),
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		/**/
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
                'admin',
		
	),

	// application components
	'components'=>array(
		'user'=>array(
                    'class' => 'WebUser',
                    // enable cookie-based authentication
                    'allowAutoLogin'=>true,
                    'loginUrl'=>array('site/index'),
		),
            
                'authManager' => array(
                    // Будем использовать свой менеджер авторизации
                    'class' => 'PhpAuthManager',
                    // Роль по умолчанию.
                    'defaultRoles' => array('guest'),
                ),
            
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
                        'baseUrl' => 'http://education.loc',
                        'showScriptName' => false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                                'admin/relationskills/<action:\w+>'=>'admin/relationSkills/<action>',
                                'admin/relationskills'=>'admin/relationSkills',
                                'admin/exerciseslogs/<action:\w+>'=>'admin/exercisesLogs/<action>',
                                'admin/exerciseslogs'=>'admin/exercisesLogs',
                                'admin/studentsofteacher/<action:\w+>'=>'admin/studentsOfTeacher/<action>',
                                'admin/studentsofteacher'=>'admin/studentsOfTeacher',
			),
		),
                'format' => array(
                    'class' => 'application.components.ZFormatter'
                ),
            
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=education',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
                        'enableProfiling'=>true,
		),
            
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
            
                'messages' => array(
                    'class' => 'CDbMessageSource',
                    'sourceMessageTable'=> 'source_messages',
                    'translatedMessageTable'=>'translated_messages',
                ),
            
                'maintenanceMode' => array(
                    'class' => 'application.extensions.MaintenanceMode.MaintenanceMode',
                    'enabledMode' => false,
                    'urls' => array('site/login'),
                    'roles' => array('admin', ),
                ),
            
//		'log'=>array(
//			'class'=>'CLogRouter',
//			'routes'=>array(
//				array(
//					'class'=>'CFileLogRoute',
//					'levels'=>'error, warning',
//				),
//				// uncomment the following to show log messages on web pages
//				array(
//					'class'=>'CProfileLogRoute',
//				),
//			),
//		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
                'beginSalt' => '409jdf1',
                'endSalt' => 'bsl@swa',
                'WordsImagesPath' => 'images/lessons',
                'MapImagesPath' => 'images/mapImages',
                'pdfPath' => 'pdf',
	),
);