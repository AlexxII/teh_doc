<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\assets\TableBaseAsset;
use app\assets\JConfirmAsset;

AppAsset::register($this);    // регистрация ресурсов всего приложения
TableBaseAsset::register($this);
JConfirmAsset::register($this);

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

<noscript><strong>Откючен JavaScript</strong> . Корректаная работа приложения невозможна.</noscript>

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
        'label' => 'Журнал ВКС',
        'items' => [
          '<li class="dropdown-header" style="font-size: 10px">Сеансы ВКС</li>',
          ['label' => 'Предстоящие сеансы', 'url' => ['/vks/sessions']],
          ['label' => 'Добавить сеанс', 'url' => ['/vks/sessions/create-up-session']],
          ['label' => 'Добавить прошедший сеанс', 'url' => ['/vks/sessions/create-session']],
          ['label' => 'Предстоящие сеансы (админ)',
            'url' => ['/vks/admin/sessions'],
            'visible' => Yii::$app->user->identity->isAdmin],
          '<li class="divider"></li>',
          '<li class="dropdown-header" style="font-size: 10px">Статистика</li>',
          ['label' => 'Архив сеансов ВКС', 'url' => ['/vks/sessions/archive']],
          ['label' => 'Анализ сеансов ВКС', 'url' => ['/vks/analytics/index']],
          ['label' => 'Архив сеансов ВКС (админ)',
            'url' => ['/vks/admin/sessions/archive'],
            'visible' => Yii::$app->user->identity->isAdmin]
        ],
      ],
      [
        'label' => 'Настройки',
        'items' => [
          '<li class="dropdown-header" style="font-size: 10px">Системные</li>',
          ['label' => 'Тип ВКС', 'url' => ['/vks/control/vks-type']],
          ['label' => 'Студии проведения ВКС', 'url' => ['/vks/control/vks-place']],
          ['label' => 'Абоненты', 'url' => ['/vks/control/vks-subscribes']],
          ['label' => 'Распоряжения', 'url' => ['/vks/control/vks-order']],
          ['label' => 'Сотрудники', 'url' => ['/vks/control/vks-employee']],
          ['label' => 'Оборудование', 'url' => ['/vks/control/vks-tools']],
        ],
      ],
      Yii::$app->user->isGuest ? (
      ['label' => 'Войти', 'url' => ['/site/login']]
      ) : ([
        'label' => '<i class="fa fa-user" aria-hidden="true" style="font-size: 18px; color: #fff"></i>',
        'items' => [
          '<li class="dropdown-header" style="font-size: 10px">' . Yii::$app->user->identity->username . '</li>',
          ['label' => '<i class="fa fa-cogs" aria-hidden="true" style="font-size: 16px; color: #fff"></i> Профиль',
            'url' => ['/admin/user/profile']
          ],
          ['label' => ''
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
              '<span style="cursor: default"><i class="fa fa-sign-out" aria-hidden="true" 
                                        style="font-size: 16px; color: #fff"></i> Выход</span>',
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
    <?= $content ?>
  </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<script>
  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
