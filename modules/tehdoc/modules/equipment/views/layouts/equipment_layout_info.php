<?php

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\modules\tehdoc\asset\TehdocAsset;
use app\modules\tehdoc\modules\equipment\asset\MdeAsset;
use app\assets\FancytreeAsset;
use app\assets\PhotoswipeAsset;
use app\modules\tehdoc\modules\equipment\asset\EquipmentAsset;
use app\assets\JConfirmAsset;

PhotoswipeAsset::register($this);
FancytreeAsset::register($this);
AppAsset::register($this);    // регистрация ресурсов всего приложения
TehdocAsset::register($this);       // регистрация ресурсов модуля
EquipmentAsset::register($this);
MdeAsset::register($this);
JConfirmAsset::register($this);

$about = "Панель управления оборудованием";
$add_hint = 'Добавить новый узел';
$refresh_hint = 'Перезапустить форму';
$del_hint = 'Удалить БЕЗ вложений';
$del_root_hint = 'Удалить ветку полностью';
$del_multi_nodes = 'Удалить С вложениями';

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
  .ui-fancytree {
    overflow: auto;
  }
  .fancytree-custom-icon {
    color: #1e6887;
    font-size: 18px;
  }
  .t {
    font-size: 14px;
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
        'label' => 'Оборудование',
        'items' => [
          '<li class="dropdown-header" style="font-size: 10px">Перечень</li>',
          ['label' => 'Перечень оборудования', 'url' => ['/tehdoc/equipment/tools']],
          ['label' => 'Сводная таблица', 'url' => ['/tehdoc/equipment/tools/index']],
          '<li class="divider"></li>',
          '<li class="dropdown-header" style="font-size: 10px">Управление оборудованием</li>',
          ['label' => 'Панель управления', 'url' => ['/tehdoc/equipment/control-panel']],
          ['label' => 'Добавить', 'url' => ['/tehdoc/equipment/tools/create']],
          ['label' => 'Задание на обновление', 'url' => ['/tehdoc/equipment/tools/task']],
        ],
      ],
      /*            // В разработке
                 [
                      'label' => 'Движение',
                      'items' => [
                          '<li class="dropdown-header" style="font-size: 10px">Движение оборудования</li>',
                          ['label' => 'Приемка', 'url' => ['/tehdoc/']],
                          ['label' => 'Ввод в экспл-цию', 'url' => ['/tehdoc/']],
                          ['label' => 'Списание', 'url' => ['/tehdoc/']],
                      ],
                  ],
      */
      [
        'label' => 'Представления',
        'items' => [
          '<li class="dropdown-header" style="font-size: 10px">Весь перечнь</li>',
          ['label' => 'По категориям', 'url' => ['/tehdoc/equipment/tools/categories']],
          ['label' => 'По месту размещения', 'url' => ['/tehdoc/equipment/tools/placement']],
          '<li class="divider"></li>',
          '<li class="dropdown-header" style="font-size: 10px">Таблицы</li>',
          ['label' => 'Таблица ОТХ', 'url' => ['/tehdoc/equipment/tools/oth']],
          ['label' => 'Таблица драг.металлов', 'url' => ['/tehdoc/equipment/tools/categories']],
          ['label' => 'Таблица инвентаризации', 'url' => ['/tehdoc/equipment/tools/categories']],

//                    '<li class="divider"></li>',
//                    '<li class="dropdown-header" style="font-size: 10px">Комплекты</li>',
//                    ['label' => 'По категориям', 'url' => ['/tehdoc/tools/categories']],
//                    ['label' => 'По месту размещения', 'url' => ['/tehdoc/tools/placement']],
//                    ['label' => 'Классификатор', 'url' => ['/tehdoc/tools/classifiers']],
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

    <div class="tools-pannel">
      <h3><?= Html::encode($this->title) ?>
        <sup class="h-title fa fa-question-circle-o" aria-hidden="true"
             data-toggle="tooltip" data-placement="right" title="<?php echo $about ?>"></sup>
      </h3>
    </div>

    <div class="row">
      <div id="tools-tree" class="col-lg-4 col-md-4" style="padding-bottom: 10px">
        <div style="position: relative">
          <div class="container-fuid" style="float:left; width: 100%">
            <input class="form-control form-control-sm" autocomplete="off" name="search" placeholder="Поиск...">
          </div>
          <div style="padding-top: 8px; right: 10px; position: absolute">
            <a href="" id="btnResetSearch">
              <i class="fa fa-times-circle" aria-hidden="true" style="font-size:20px; color: #9d9d9d"></i>
            </a>
          </div>
        </div>

        <div class="row" style="padding: 0 15px">
          <div style="border-radius:2px;padding-top:40px">
            <div id="fancyree_w0" class="ui-draggable-handle" style="overflow: auto"></div>
          </div>
        </div>
      </div>

      <div id="complex-info" class="col-lg-8 col-md-8" style="height: 100%">
        <?= $content ?>
      </div>

    </div>

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

  $("input[name=search]").keyup(function (e) {
    var n,
      tree = $.ui.fancytree.getTree(),
      args = "autoApply autoExpand fuzzy hideExpanders highlight leavesOnly nodata".split(" "),
      opts = {},
      filterFunc = $("#branchMode").is(":checked") ? tree.filterBranches : tree.filterNodes,
      match = $(this).val();

    $.each(args, function (i, o) {
      opts[o] = $("#" + o).is(":checked");
    });
    opts.mode = $("#hideMode").is(":checked") ? "hide" : "dimm";

    if (e && e.which === $.ui.keyCode.ESCAPE || $.trim(match) === "") {
      $("button#btnResetSearch").click();
      return;
    }
    if ($("#regex").is(":checked")) {
      // Pass function to perform match
      n = filterFunc.call(tree, function (node) {
        return new RegExp(match, "i").test(node.title);
      }, opts);
    } else {
      // Pass a string to perform case insensitive matching
      n = filterFunc.call(tree, match, opts);
    }
    $("#btnResetSearch").attr("disabled", false);
  }).focus();


  $("#btnResetSearch").click(function (e) {
    e.preventDefault();
    $("input[name=search]").val("");
    $("span#matches").text("");
    var tree = $(".ui-draggable-handle").fancytree("getTree");
    tree.clearFilter();
  }).attr("disabled", true);

  $(document).ready(function () {
    $("input[name=search]").keyup(function (e) {
      if ($(this).val() == '') {
        var tree = $(".ui-draggable-handle").fancytree("getTree");
        tree.clearFilter();
      }
    })
  });

  // отображение и логика работа дерева
  jQuery(function ($) {
    var main_url = '/tehdoc/equipment/tools/all-tools';
    $("#fancyree_w0").fancytree({
      source: {
        url: main_url
      },
      extensions: ['filter'],
      quicksearch: true,
      minExpandLevel: 2,
      wide: {
        iconWidth: "32px",     // Adjust this if @fancy-icon-width != "16px"
        iconSpacing: "6px", // Adjust this if @fancy-icon-spacing != "3px"
        labelSpacing: "6px",   // Adjust this if padding between icon and label !=  "3px"
        levelOfs: "32px"     // Adjust this if ul padding != "16px"
      },
      filter: {
        autoApply: true,                                    // Re-apply last filter if lazy data is loaded
        autoExpand: true,                                   // Expand all branches that contain matches while filtered
        counter: true,                                      // Show a badge with number of matching child nodes near parent icons
        fuzzy: false,                                       // Match single characters in order, e.g. 'fb' will match 'FooBar'
        hideExpandedCounter: true,                          // Hide counter badge if parent is expanded
        hideExpanders: true,                                // Hide expanders if all child nodes are hidden by filter
        highlight: true,                                    // Highlight matches by wrapping inside <mark> tags
        leavesOnly: true,                                   // Match end nodes only
        nodata: true,                                       // Display a 'no data' status node if result is empty
        mode: 'hide'                                        // Grayout unmatched nodes (pass "hide" to remove unmatched node instead)
      },
      click: function (event, data) {
        var target = $.ui.fancytree.getEventTargetType(event.originalEvent);
        if (target === 'title' || target === 'icon') {
          var node = data.node;
          var prefix = '/tehdoc/equipment/tool/';
          if (node.key != 1122334455 && node.key != 5544332211 && node.data.eq_wrap != 1) {
            var url = prefix + node.key + '/info/index';
            window.location.href = url;
          }
        }
      },
      icon: function (event, data) {
        if (data.node.key == 1122334455) {
          return "fa fa-sitemap";
        } else if (data.node.key == 5544332211) {
          return "fa fa-question-circle";
        } else if (data.node.data.eq_wrap == 1) {
          return "t fa fa-clone";
        } else {
          return "t fa fa-file-o";
        }
        // if (data.node.isFolder()) {
        //   return "fa fa-eye";
        // }
      },
      init: function (event, data) {
        var uri = window.location.href;
        var key = uri.match('\\/tool\\/(\\d+)\\/\\w+\\/');
        if (!key) {
          return;
        }
        data.tree.activateKey(key[1]);
      },
      keypress: function (event, data) {
        console.log(event);
      },
      dblclick: function (event, data) {
        var node = data.node;
        var prefix = '/tehdoc/equipment/tool/';
        if (node.key != 1122334455 && node.key != 5544332211 && node.data.eq_wrap != 1) {
          var url = prefix + node.key + '/info/index';
          window.location.href = url;
        }
      }
    });
  });

  function getNodeId() {
    var node = $("#fancyree_w0").fancytree("getActiveNode");
    if (node) {
      return node.data.ref;
    } else {
      return 1;
    }
  }

</script>
