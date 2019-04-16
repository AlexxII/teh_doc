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
use app\modules\tehdoc\modules\equipment\asset\EquipmentAsset;
use app\assets\JConfirmAsset;

FancytreeAsset::register($this);
AppAsset::register($this);    // регистрация ресурсов всего приложения
TehdocAsset::register($this);       // регистрация ресурсов модуля
EquipmentAsset::register($this);
MdeAsset::register($this);
JConfirmAsset::register($this);


$about = "Панель управления оборудованием. 
              Предназначена для добавления, изменения, управления и удаления оборудования.";
$add_hint = 'Добавить новый узел или оборудование';
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
    font-size: 15px;
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
  .ui-fancytree {
    overflow: auto;
  }
  input {
    color: black;
  }
  .fancytree-custom-icon {
    color: #1e6887;
    font-size: 18px;
    cursor: pointer;
  }
  .t {
    font-size: 14px;
  }
  span.fancytree-title {
    cursor: default;
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

    <div class="complex-pannel">
      <h3><?= Html::encode($this->title) ?>
        <sup class="h-title fa fa-question-circle-o" aria-hidden="true"
             data-toggle="tooltip" data-placement="right" title="<?php echo $about ?>"></sup>
      </h3>
    </div>

    <div class="row">
      <div class="">
        <div class="container-fluid" style="margin-bottom: 10px">
          <?= Html::a('<i class="fa fa-plus" aria-hidden="true"></i>', ['#'], ['class' => 'btn btn-success btn-sm add-subcategory',
            'style' => ['margin-top' => '5px'],
            'title' => $add_hint,
            'data-toggle' => 'tooltip',
            'data-placement' => 'top'
          ]) ?>
          <?= Html::a('<i class="fa fa-trash" aria-hidden="true"></i>', ['#'], ['class' => 'btn btn-danger btn-sm del-node',
            'style' => ['margin-top' => '5px', 'display' => 'none'],
            'title' => $del_hint,
            'data-toggle' => 'tooltip',
            'data-placement' => 'top'
          ]) ?>
          <?= Html::a('<i class="fa fa-object-group" aria-hidden="true"></i>', ['#'], ['class' => 'btn btn-danger btn-sm del-multi-nodes',
            'style' => ['margin-top' => '5px', 'display' => 'none'],
            'title' => $del_multi_nodes,
            'data-toggle' => 'tooltip',
            'data-placement' => 'top'
          ]) ?>
        </div>
      </div>

      <div id="complex-tree" class="col-lg-4 col-md-4" style="padding-bottom: 10px">
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
            <div id="fancyree_w0" class="ui-draggable-handle"></div>
          </div>
        </div>
      </div>
      <div id="complex-info" class="col-lg-8 col-md-8" style="height: 100%">
        <div id="status-info"></div>
        <div id="content-info"><?= $content ?></div>
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

  function goodAlert(text) {
    var div = '' +
      '<div id="w3-success-0" class="alert-success alert fade in" style="margin-bottom: 10px">' +
      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
      text +
      '</div>';
    return div;
  }

  function badAlert(text) {
    var div = '' +
      '<div id="w3-success-0" class="alert-danger alert fade in">' +
      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
      text +
      '</div>';
    return div;
  }

  $(document).ready(function () {
    $('.add-subcategory').click(function (event) {
      event.preventDefault();
      var node = $(".ui-draggable-handle").fancytree("getActiveNode");
      if (!node) {
        alert("Выберите родительскую категорию");
        return;
      }
      node.editCreateNode("child", " ");
    })
  });


  $(document).ready(function () {
    $('.del-node').click(function (event) {
      var url = '/tehdoc/equipment/control-panel/info/delete-node';
      event.preventDefault();
      jc = $.confirm({
        icon: 'fa fa-question',
        title: 'Вы уверены?',
        content: 'Вы действительно хотите удалить выделенное?',
        type: 'red',
        closeIcon: false,
        autoClose: 'cancel|9000',
        buttons: {
          ok: {
            btnClass: 'btn-danger',
            action: function () {
              var node = $(".ui-draggable-handle").fancytree("getActiveNode");
              jc.close();
              deleteProcess(url, node);
            }
          },
          cancel: {
            action: function () {
              return;
            }
          }
        }
      });
    });

    $('.del-multi-nodes').click(function (event) {
      event.preventDefault();
      var url = "/tehdoc/equipment/control-panel/info/delete-root";
      jc = $.confirm({
        icon: 'fa fa-question',
        title: 'Вы уверены?',
        content: 'Вы действительно хотите удалить выделенное С вложениями?',
        type: 'red',
        closeIcon: false,
        autoClose: 'cancel|9000',
        buttons: {
          ok: {
            btnClass: 'btn-danger',
            action: function () {
              var node = $(".ui-draggable-handle").fancytree("getActiveNode");
              jc.close();
              deleteProcess(url, node);
            }
          },
          cancel: {
            action: function () {
              return;
            }
          }
        }
      });
    });


    function deleteProcess(url, node) {
      var csrf = $('meta[name=csrf-token]').attr("content");
      jc = $.confirm({
        icon: 'fa fa-cog fa-spin',
        title: 'Подождите!',
        content: 'Ваш запрос выполняется!',
        buttons: false,
        closeIcon: false,
        confirmButtonClass: 'hide'
      });
      $.ajax({
        url: url,
        type: "post",
        data: {nodeId: getNodeId(), _csrf: csrf}
      }).done(function (response) {
        if (response != false) {
          jc.close();
          jc = $.confirm({
            icon: 'fa fa-thumbs-up',
            title: 'Успех!',
            content: 'Ваш запрос выполнен.',
            type: 'green',
            buttons: false,
            closeIcon: false,
            autoClose: 'ok|8000',
            confirmButtonClass: 'hide',
            buttons: {
              ok: {
                btnClass: 'btn-success',
                action: function () {
                  node.remove();
                  $('#status-info').html('');
                  $('.del-node').hide();
                  $(".del-multi-nodes").hide();
                  $('#content-info').html('');
                }
              }
            }
          });
        } else {
          jc.close();
          jc = $.confirm({
            icon: 'fa fa-exclamation-triangle',
            title: 'Неудача!',
            content: 'Запрос не выполнен. Что-то пошло не так.',
            type: 'red',
            buttons: false,
            closeIcon: false,
            autoClose: 'ok|8000',
            confirmButtonClass: 'hide',
            buttons: {
              ok: {
                btnClass: 'btn-danger',
                action: function () {
                }
              }
            }
          });
        }
      }).fail(function () {
        jc.close();
        jc = $.confirm({
          icon: 'fa fa-exclamation-triangle',
          title: 'Неудача!',
          content: 'Запрос не выполнен. Что-то пошло не так.',
          type: 'red',
          buttons: false,
          closeIcon: false,
          autoClose: 'ok|4000',
          confirmButtonClass: 'hide',
          buttons: {
            ok: {
              btnClass: 'btn-danger',
              action: function () {
              }
            }
          }
        });
      });
    }
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


  var uri, match;
  // отображение и логика работа дерева
  jQuery(function ($) {
    var main_url = '/tehdoc/equipment/tools/all-tools';
    var move_url = "/tehdoc/equipment/control-panel/info/move-node";
    var create_url = '/tehdoc/equipment/control-panel/info/create-node';
    var update_url = '/tehdoc/equipment/control-panel/info/update-node';

    $("#fancyree_w0").fancytree({
      source: {
        url: main_url,
      },
      expandParents: true,
      noAnimation: false,
      scrollIntoView: true,
      topNode: null,
      extensions: ['dnd', 'edit', 'filter'],
      quicksearch: true,
      minExpandLevel: 2,
      hotkeys: {},
      // wide: {
      //   iconWidth: "32px",     // Adjust this if @fancy-icon-width != "16px"
      //   iconSpacing: "6px", // Adjust this if @fancy-icon-spacing != "3px"
      //   labelSpacing: "6px",   // Adjust this if padding between icon and label !=  "3px"
      //   levelOfs: "32px"     // Adjust this if ul padding != "16px"
      // },
      dnd: {
        preventVoidMoves: true,
        preventRecursiveMoves: true,
        autoCollapse: true,
        dragStart: function (node, data) {
          return true;
        },
        dragEnter: function (node, data) {
          return true;
        },
        dragDrop: function (node, data) {
          if (data.hitMode == 'over') {
            var pId = data.node.data.id;
          } else {
            var pId = data.node.parent.data.id;
          }
          $.get(move_url, {
            item: data.otherNode.data.id,
            action: data.hitMode,
            second: node.data.id,
            parentId: pId
          }, function () {
            data.otherNode.moveTo(node, data.hitMode);
          })
        }
      },
      filter: {
        autoApply: true,                                    // Re-apply last filter if lazy data is loaded
        autoExpand: true,                                   // Expand all branches that contain matches while filtered
        counter: true,                                      // Show a badge with number of matching child nodes near parent icons
        fuzzy: false,                                       // Match single characters in order, e.g. 'fb' will match 'FooBar'
        hideExpandedCounter: true,                          // Hide counter badge if parent is expanded
        hideExpanders: false,                                // Hide expanders if all child nodes are hidden by filter
        highlight: true,                                    // Highlight matches by wrapping inside <mark> tags
        leavesOnly: true,                                   // Match end nodes only
        nodata: true,                                       // Display a 'no data' status node if result is empty
        mode: 'hide'                                        // Grayout unmatched nodes (pass "hide" to remove unmatched node instead)
      },
      edit: {
        inputCss: {
          minWidth: '10em'
        },
        triggerStart: ['clickActive', 'dbclick', 'f2', 'mac+enter', 'shift+click'],
        beforeEdit: function (event, data) {
          var node = data.node;
          if (node.data.lvl == 0) {
            return false;
          }
          node.icon = 't fa fa-file-o';
          return true;
        },
        edit: function (event, data) {
          return true;
        },
        beforeClose: function (event, data) {
          data.save
        },
        save: function (event, data) {
          var node = data.node;
          if (data.isNew) {
            $.ajax({
              url: create_url,
              data: {
                parentId: node.parent.data.id,
                title: data.input.val()
              }
            }).done(function (result) {
              if (result) {
                var parent = node.parent;
                parent.folder = true;
                result = JSON.parse(result);
                node.data.id = result.acceptedId;
                node.key = result.acceptedId;
                node.setTitle(result.acceptedTitle);
                $('#status-info').hide().html(goodAlert('Запись успешно сохранена в БД.')).fadeIn('slow');
              } else {
                node.setTitle(data.orgTitle);
                $('#status-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
                  ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
              }
            }).fail(function (result) {
              node.setTitle(data.orgTitle);
              $('#status-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
                ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
            }).always(function () {
              // data.input.removeClass("pending")
            });
          } else {
            $.ajax({
              url: update_url,
              data: {
                nodeId: getNodeId(),
                title: data.input.val()
              }
            }).done(function (result) {
              if (result) {
                result = JSON.parse(result);
                node.setTitle(result.acceptedTitle);
                $('#status-info').hide().html(goodAlert('Запись успешно изменена в БД.')).fadeIn('slow');
              } else {
                node.setTitle(data.orgTitle);
                $('#status-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
                  ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
              }
            }).fail(function (result) {
              $('#status-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
                ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
              node.setTitle(data.orgTitle);
            }).always(function () {
              // data.input.removeClass("pending")
            });
          }
          return true;
        },
        close: function (event, data) {
          if (data.save) {
            // Since we started an async request, mark the node as preliminary
            $(data.node.span).addClass("pending")
          }
        }
      },
      activate: function (node, data) {
        $('#status-info').html('');
        var node = data.node;
        var lvl = node.data.lvl;
        if (node.key == -999) {
          $(".add-subcategory").hide();
          return;
        } else {
          $(".add-subcategory").show();
        }
        if (lvl == 0) {
          $(".del-node").hide();
          $(".del-multi-nodes").hide();
        } else {
          if (node.hasChildren()) {
            $(".del-multi-nodes").show();
          } else {
            $(".del-multi-nodes").hide();
          }
          $(".del-node").show();
        }
      },
      click: function (event, data) {
        var target = $.ui.fancytree.getEventTargetType(event.originalEvent);
        if (target === 'title') {
          $('#content-info').html('');
        }
        if (target === 'icon') {
          var node = data.node;
          var prefix = '/tehdoc/equipment/control-panel/';
          if (!match) {
            var url = prefix + node.key + '/info/index';
          } else {
            var url = prefix + node.key + '/' + match[2] + '/index';
          }
          if (node.data.eq_wrap == 1) {
            var url = prefix + node.key + '/settings/wrap-config';
            window.location.href = url;
          } else if (node.data.lvl != 0) {
            window.location.href = url;
          }
        }
      },
      dblclick: function (event, data) {
        var target = $.ui.fancytree.getEventTargetType(event.originalEvent);
        if (target === 'title') {
          $('#content-info').html('');
        }
        if (target === 'icon') {
          var node = data.node;
          var prefix = '/tehdoc/equipment/control-panel/';
          if (!match) {
            var url = prefix + node.key + '/info/index';
          } else {
            var url = prefix + node.key + '/' + match[2] + '/index';
          }
          if (node.data.eq_wrap == 1) {
            var url = prefix + node.key + '/settings/wrap-config';
            window.location.href = url;
          } else if (node.data.lvl != 0) {
            window.location.href = url;
          }
        }
      },
      icon: function (event, data) {
        var icon = data.node.data.icon;
        if (icon) {
          return icon;
        }
      },
      renderNode: function (node, data) {
      },
      // Получение совпадений по регулярке сразу при инициализации дерева.
      // получение id и контроллера из адресной строки браузера
      init: function (event, data) {
        uri = window.location.href;
        match = uri.match('\\/control-panel\\/([0-9-]+)\\/(\\w+)\\/');
        if (!match) {
          return;
        }
        data.tree.activateKey(match[1]);
      }
    });
  });

  function getNodeId() {
    var node = $("#fancyree_w0").fancytree("getActiveNode");
    if (node) {
      return node.data.id;
    } else {
      return 1;
    }
  }

</script>
