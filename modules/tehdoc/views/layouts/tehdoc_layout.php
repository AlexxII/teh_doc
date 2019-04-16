<?php

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

use app\assets\AppAsset;
use app\assets\JConfirmAsset;
use app\modules\tehdoc\asset\TehdocAsset;

AppAsset::register($this);    // регистрация ресурсов всего приложения
TehdocAsset::register($this);       // регистрация ресурсов модуля
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
        'label' => 'Оборудование и ТО',
        'items' => [
          '<li class="dropdown-header" style="font-size: 10px">Оборудование</li>',
          ['label' => 'Перечень оборудования', 'url' => ['/tehdoc/equipment/tools']],
          ['label' => 'Сводная таблица', 'url' => ['/tehdoc/equipment/tools/index']],
          '<li class="divider"></li>',
          '<li class="dropdown-header" style="font-size: 10px">Тех.обслуживание</li>',
          ['label' => 'График ТО', 'url' => ['/tehdoc/']],
          ['label' => 'Учет наработки', 'url' => ['/tehdoc/']],
        ],
      ],
      [
        'label' => 'Настройки',
        'items' => [
          '<li class="dropdown-header" style="font-size: 10px">Представления</li>',
          ['label' => 'Категории', 'url' => ['/tehdoc/control/category/index']],
          ['label' => 'Места размещения', 'url' => ['/tehdoc/control/placement/index']],
          '<li class="divider"></li>',
          '<li class="dropdown-header" style="font-size: 10px">Инфтерфейса</li>',
          ['label' => 'Производитель/модель', 'url' => ['/tehdoc/control/interface/index']],
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

  <div class="container"s>
    <?= Breadcrumbs::widget([
      'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
      'options' => [
        'class' => 'breadcrumb'
      ],
      'tag' => 'ol',
    ]) ?>
    <?= Alert::widget() ?>
    <div class="modal fade freeztime" id="Modal" tabindex="-1" role="dialog"
         data-backdrop="static" data-keyboard="false" aria-labelledby="ModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalLabel">Ожидание ответа от сервера.</h5>
          </div>
          <div class="modal-body">
            Подождите пожалуйста. Ваш запрос обрабатывается.
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="classifier-modal" tabindex="-1" role="dialog"
         data-backdrop="static" data-keyboard="false" aria-labelledby="ModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 class="modal-title" id="ModalLabel">Присвоение классификатора.</h5>
            <div class="modal-header-select-place">

              <hr>
            </div>
          </div>
          <div id="modal-form">
            <form action="assign-classifier" id="form-classifier" method="POST"
                  enctype="multipart/form-data">
              <div class="modal-body" id="classifier-body">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="submit" class="btn btn-primary" id="assign-classifier-btn" disabled>
                  Просвоить
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?= $content ?>
  </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
