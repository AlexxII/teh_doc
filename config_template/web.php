<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
  'id' => 'riac',
  'basePath' => dirname(__DIR__),
  'bootstrap' => ['log'],
  'language' => 'ru-Ru',
  'timeZone' => 'Europe/Moscow',
  'aliases' => [
    '@bower' => '@vendor/bower-asset',
    '@npm' => '@vendor/npm-asset',
  ],
  'modules' => [
    'admin' => [
      'class' => 'app\modules\admin\Module',
    ],
    'tehdoc' => [
      'class' => 'app\modules\tehdoc\TehdocModule',
    ],
    'to' => [
      'class' => 'app\modules\to\ToModule',
    ],
    'vks' => [
      'class' => 'app\modules\vks\VksModule',
      'as access' => [
        'class' => 'yii\filters\AccessControl',
        'rules' => [
          [
            'allow' => true,
            'roles' => ['@']
          ],
        ]
      ]
    ],
    'people' => [
      'class' => 'app\modules\people\PeopleModule',
    ],
    'doc' => [
      'class' => 'app\modules\doc\DocModule',
    ],
    'sysi' => [
      'class' => 'app\modules\sysi\SysiModule',
    ]
  ],
  'components' => [
    'request' => [
      // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
      'cookieValidationKey' => '12343984523io4j5hlk234h5jk',
    ],
    'cache' => [
      'class' => 'yii\caching\FileCache',
    ],
    'user' => [
      'identityClass' => 'app\modules\admin\models\User',
      'enableAutoLogin' => true,
      'loginUrl' => '/site/login'         // возможность переопределить страницу аутентификации
    ],
    'errorHandler' => [
      'errorAction' => 'site/error',
    ],
    // менеджер RBAC
    'authManager' => [
      'class' => 'yii\rbac\DbManager',
    ],
    // менеджер ресурсов - гибко управляет подключаемыми библиотеками
    'assetManager' => [
      'appendTimestamp' => true,
      'bundles' => [
//                'yii\web\JqueryAsset' => false,
//                'yii\web\YiiAsset' => false,
//                'linkAssets' => true,             // TODO Возможно операционная система сервера не разрешит ссылки!
      ]
    ],
    'mailer' => [
      'class' => 'yii\swiftmailer\Mailer',
      'useFileTransport' => true,
    ],
    'log' => [
      'traceLevel' => YII_DEBUG ? 3 : 0,
      'targets' => [
        [
          'class' => 'yii\log\FileTarget',
          'levels' => ['error', 'warning'],
        ],
      ],
    ],
    'db' => $db,
    'urlManager' => [
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'baseUrl' => '',
      'rules' => [
        [
          'pattern' => 'tehdoc/equipment/tool/<id:\d+>/<controller:(info)>/<action>',
          'route' => 'tehdoc/equipment/infoPanel/info/info',
          'defaults' => ['id' => 1122334455],
        ],
        [
          'pattern' => 'tehdoc/equipment/tools',
          'route' => 'tehdoc/equipment/infoPanel/info/index',
        ],
        [
          'pattern' => 'tehdoc/equipment/tool/<id:\d+>/<controller>/<action>',
          'route' => 'tehdoc/equipment/infoPanel/<controller>/<action>',
          'defaults' => ['id' => 1122334455],
        ],
        [
          'pattern' => 'tehdoc/equipment/control-panel',
          'route' => 'tehdoc/equipment/controlPanel/info/meeting',
        ],
        [
          'pattern' => 'tehdoc/equipment/control-panel/<id:\d+>/<controller>/<action>',
          'route' => 'tehdoc/equipment/controlPanel/<controller>/<action>',
          'defaults' => ['id' => 1122334455],
        ]
      ],
    ],
  ],
  'params' => $params,
];

if (YII_ENV_DEV) {
  // configuration adjustments for 'dev' environment
  $config['bootstrap'][] = 'debug';
  $config['modules']['debug'] = [
    'class' => 'yii\debug\Module',
    // uncomment the following to add your IP if you are not connecting from localhost.
    'allowedIPs' => ['192.168.56.1', '::1'],
  ];

  $config['bootstrap'][] = 'gii';
  $config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
    // uncomment the following to add your IP if you are not connecting from localhost.
    'allowedIPs' => ['192.168.56.1', '::1'],
  ];
}

return $config;
