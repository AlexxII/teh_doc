<?php

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

use app\assets\AppAsset;
use app\assets\FancytreeAsset;
use app\modules\tehdoc\asset\TehdocAsset;
use app\modules\tehdoc\modules\to\assets\ToAsset;

AppAsset::register($this);    // регистрация ресурсов всего приложения
FancytreeAsset::register($this);
TehdocAsset::register($this);       // регистрация ресурсов модуля
ToAsset::register($this);

$about = "Панель управления оборудованием";
$add_hint = 'Добавить новый узел';
$refresh_hint = 'Перезапустить форму';
$del_hint = 'Удалить БЕЗ вложений';
$del_root_hint = 'Удалить ветку полностью';
$del_multi_nodes = 'Удвлить С вложениями';

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?= Html::csrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>

</head>

<style>
  .fa {
    font-size: 18px;
  }
  .navbar-inverse .navbar-nav > .active > a {
    background-color: #0000aa;
  }
  .navbar-inverse .navbar-nav > .open > a, .navbar-inverse .navbar-nav > .open > a:hover, .navbar-inverse .navbar-nav > .open > a:focus {
    background-color: #0000aa;
    color: white;
  }
  .navbar-inverse .navbar-nav > .active > a, .navbar-inverse .navbar-nav > .active > a:hover, .navbar-inverse .navbar-nav > .active > a:focus {
    background-color: #0000aa;
    color: white;
  }
  .navbar-inverse .btn-link:hover, .navbar-inverse .btn-link:focus {
    text-decoration: none;
  }
  .navbar-nav > li > .dropdown-menu {
    background-color: #014993;
    color: white;
  }
  .dropdown-menu > li > a {
    color: white;
  }
  .dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus {
    background-color: #05226f;
    color: white;
  }
  .dropdown-header {
    color: white;
  }
  a:hover {
    text-decoration: none;
  }
</style>


<body>

<?php $this->beginBody() ?>

<div class="wrap">
  <?php
  NavBar::begin([
    'brandLabel' => '<img src="/images/logo.jpg" style="display:inline">',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
      'class' => 'navbar-inverse',
    ],
  ]);
  echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'encodeLabels' => false,
    'items' => [
      [
        'label' => 'Графики',
        'items' => [
          '<li class="dropdown-header" style="font-size: 10px">Ежемесячные</li>',
          ['label' => 'Графики ТО', 'url' => ['/tehdoc/to/schedule']],
          ['label' => 'Составить график', 'url' => ['/tehdoc/to/schedule/create']],
          '<li class="divider"></li>',
          '<li class="dropdown-header" style="font-size: 10px">Годовые</li>',
          ['label' => 'Графики ТО', 'url' => ['/tehdoc/to/schedule']],
          ['label' => 'Составить график', 'url' => ['/tehdoc/to/schedule']],
        ],
      ],
      [
        'label' => 'Настройки',
        'items' => [
          '<li class="dropdown-header" style="font-size: 10px">Системные</li>',
          ['label' => 'Оборудование в графике', 'url' => ['/tehdoc/to/control/to-equipment']],
          ['label' => 'Виды Тех.обслуживания', 'url' => ['/tehdoc/to/control/to-type']],
          '<li class="divider"></li>',
          '<li class="dropdown-header" style="font-size: 10px">Пользовательские</li>',
          ['label' => 'Какие-то настройки', 'url' => ['/tehdoc/to/control/to-type']],
        ],
      ],
      Yii::$app->user->isGuest ? (
      ['label' => 'Войти', 'url' => ['/site/login']]
      ) : ([
        'label' => '<i class="fa fa-user" aria-hidden="true" style="font-size: 18px"></i>',
        'items' => [
          '<li class="dropdown-header" style="font-size: 10px">' . Yii::$app->user->identity->username . '</li>',
          ['label' => '<i class="fa fa-cogs" aria-hidden="true" style="font-size: 16px"></i> Профиль',
            'url' => ['/admin/user/profile']
          ],
          ['label' => ''
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
              '<span style="cursor: default"><i class="fa fa-sign-out" aria-hidden="true"></i> Выход</span>',
              [
                'class' => 'btn btn-link logout',
                'data-toggle' => "tooltip",
                'data-placement' => "bottom",
                'style' => [
                  'padding' => '0px',
                ]
              ]
            )
            . Html::endForm()
          ]
        ]
      ])
    ],
  ]);
  NavBar::end();
  ?>

  <div class="container">
    <?= Breadcrumbs::widget([
      'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
      'options' => [
        'class' => 'breadcrumb'
      ],
      'tag' => 'ol',
    ]) ?>
    <?= Alert::widget() ?>

    <div><?= $content ?></div>

  </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
