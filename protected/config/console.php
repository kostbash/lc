<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// application components
	'components'=>array(
                // Соединение с СУБД
                'db'=>array(
                    'connectionString' => 'mysql:host=u409930.mysql.masterhost.ru;dbname=u409930_cur_tst',
                    'emulatePrepare' => true,
                    'username' => 'u409930_v',
                    'password' => 'M--4o2ICAl',
                    'charset' => 'utf8',
                    'enableProfiling'=>true,
                ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);