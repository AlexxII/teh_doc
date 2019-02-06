<?php

use yii\helpers\Html;
use app\assets\FancytreeAsset;

FancytreeAsset::register($this);

$this->title = 'Перечень оборудования';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = $this->title;

$about = "Панель управления оборудованием. При сбое, перезапустите форму, воспользовавшись соответствующей клавишей.";
$add_hint = 'Добавить новый узел';
$refresh_hint = 'Перезапустить форму';
$del_hint = 'Удалить БЕЗ вложений';
$del_root_hint = 'Удалить ветку полностью';
$del_multi_nodes = 'Удвлить С вложениями';

?>

<style>
  .h-title {
    font-size: 18px;
    color: #1e6887;
  }
  .fa {
    font-size: 15px;
  }
  ul.fancytree-container {
    font-size: 15px;
  }
  input {
    color: black;
  }
  .nav-tabs > li > a {
    font-size: 10px;
  }
</style>

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
      <?= Html::a('<i class="fa fa-refresh" aria-hidden="true"></i>', ['#'], ['class' => 'btn btn-success btn-sm refresh',
        'style' => ['margin-top' => '5px'],
        'title' => $refresh_hint,
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
    <ul class="nav nav-tabs" id="main-teh-tab">
      <li class="active"><a href="#info" data-toggle="tab" data-url="complex/info">Info</a></li>
      <li><a href="#messages" data-toggle="tab" data-url="complex/files">Files</a></li>
      <li><a href="#profile" data-toggle="tab" data-url="wiki/index">Wiki</a></li>
      <li><a href="#messages" data-toggle="tab" data-url="complex/log">Log</a></li>
      <li><a href="#setting" data-toggle="tab" data-url="settings/index"><i class="fa fa-cogs" aria-hidden="true"></i></a></li>
    </ul>
    <div class="about-content" style="margin-top: 15px">

    </div>
  </div>
</div>


<script>
  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  function goodAlert(text) {
    var div = '' +
      '<div id="w3-success-0" class="alert-success alert fade in">' +
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
    $('.refresh').click(function (event) {
      event.preventDefault();
      reloadTree(true);
      // var tree = $(".ui-draggable-handle").fancytree("getTree");
      // tree.reload();
      // $(".del-node").hide();
      // $(".del-multi-nodes").hide();
      // $('.about-info').html('');
      // showMeeting();
      // unbindEvents();
    })
  });

  $(document).ready(function () {
    $('.del-node').click(function (event) {
      if (confirm('Вы уверены, что хотите удалить выбранный классификатор?')) {
        event.preventDefault();
        var csrf = $('meta[name=csrf-token]').attr("content");
        var node = $(".ui-draggable-handle").fancytree("getActiveNode");
        $.ajax({
          url: "/tehdoc/equipment/complex/delete",
          type: "post",
          data: {id: node.data.id, _csrf: csrf}
        })
          .done(function () {
            node.remove();
            $('.about-info').html('');
            $('.del-node').hide();
          })
          .fail(function () {
            alert("Что-то пошло не так. Перезагрузите форму с помошью клавиши.");
          });
      }
    });

    $('.del-multi-nodes').click(function (event) {
      if (confirm('Вы уверены, что хотите удалить выбранный классификатор вместе с вложениями?')) {
        event.preventDefault();
        var csrf = $('meta[name=csrf-token]').attr("content");
        var node = $(".ui-draggable-handle").fancytree("getActiveNode");
        if (!node) {
          alert('Выберите узел');
          return;
        }
        $.ajax({
          url: "/vks/control/vks-order/delete-root",
          type: "post",
          data: {
            id: getNodeId(),
            _csrf: csrf
          }
        })
          .done(function () {
            node.remove();
            $('.about-info').html('');
            $('.del-multi-nodes').hide();
            $('.del-node').hide();

          })
          .fail(function () {
            alert("Что-то пошло не так. Перезагрузите форму с помошью клавиши.");
          });
      }
    })
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
    var main_url = '/tehdoc/equipment/complex/complexes';
    var move_url = "/tehdoc/equipment/complex/move";
    var create_url = '/tehdoc/equipment/complex/create';
    var update_url = '/tehdoc/equipment/complex/update';

    $("#fancyree_w0").fancytree({
      source: {
        url: main_url,
      },
      extensions: ['dnd', 'edit', 'filter'],
      quicksearch: true,
      minExpandLevel: 2,
      hotkeys: {},
      wide: {
        iconWidth: "32px",     // Adjust this if @fancy-icon-width != "16px"
        iconSpacing: "6px", // Adjust this if @fancy-icon-spacing != "3px"
        labelSpacing: "6px",   // Adjust this if padding between icon and label !=  "3px"
        levelOfs: "32px"     // Adjust this if ul padding != "16px"
      },
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
            if (data.node.data.lvl == 2) {             // Ограничение на вложенность
              return false;
            }
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
        hideExpanders: true,                                // Hide expanders if all child nodes are hidden by filter
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
                result = JSON.parse(result);
                node.data.id = result.acceptedId;
                node.data.ref = result.acceptedRef;
                node.setTitle(result.acceptedTitle);
                $('.about-info').hide().html(goodAlert('Запись успешно сохранена в БД.')).fadeIn('slow');
              } else {
                node.setTitle(data.orgTitle);
                $('.about-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
                  ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
              }
            }).fail(function (result) {
              node.setTitle(data.orgTitle);
              $('.about-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
                ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
            }).always(function () {
              // data.input.removeClass("pending")
            });
          } else {
            $.ajax({
              url: update_url,
              data: {
                id: getNodeId(),
                title: data.input.val()
              }
            }).done(function (result) {
              if (result) {
                result = JSON.parse(result);
                node.setTitle(result.acceptedTitle);
                $('.about-info').hide().html(goodAlert('Запись успешно изменена в БД.')).fadeIn('slow');
              } else {
                node.setTitle(data.orgTitle);
                $('.about-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
                  ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
              }
            }).fail(function (result) {
              $('.about-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
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
          var node = data.node;
          var $span = $(node.span);
          $span.find("span.fancytree-title").text(node.title).css({
            "white-space": "normal",
            "word-break": "break-all",
            "margin": "0 30px 0 5px"
          });
        }
      },
      activate: function (node, data) {
        $('.about-info').html('');
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
        if (node.data.lvl == 0) {
          showMeeting();
        } else {
          showTab();
        }
      },
      renderNode: function (node, data) {
        var node = data.node;
        // if (node.data) {
        //   var $span = $(node.span);
        //   $span.find("span.fancytree-title").text(node.title).css({
        //     "white-space": "normal",
        //     "word-break": "break-all",
        //     "margin": "0 30px 0 5px"
        //   });
        // }
      },
      init: function (event, data, flag) {
        showMeeting();
        tabEvents();
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

  function showTab() {
    $('#main-teh-tab').css("display", "block");
    var csrf = $('meta[name=csrf-token]').attr("content");
    var url = "/tehdoc/equipment/complex/info";
    $.ajax({
      url: url,
      type: "post",
      data: {
        id: getNodeId(),
        _csrf: csrf
      }
    })
      .done(function (result) {
        $('.about-content').html(result);
        $('#main-teh-tab a:first').tab('show');
      })
      .fail(function () {
        alert("Что-то пошло не так. Перезагрузите форму с помошью клавиши.");
      });
  }

  function tabEvents() {
    $('a[data-toggle="tab"]').bind('shown.bs.tab', function (e) {
      var csrf = $('meta[name=csrf-token]').attr("content");
      var mainUrl = "/tehdoc/equipment/";
      var u = $(e.target).data('url');
      $.ajax({
        url: mainUrl + u,
        type: "post",
        data: {
          id: getNodeId(),
          _csrf: csrf
        }
      })
        .done(function (result) {
          $('.about-content').html(result);
          // $(this).tab('show');
        })
        .fail(function () {
          alert("Что-то пошло не так. Перезагрузите форму с помошью клавиши.");
        });
    });
  }

  function showMeeting() {
    var meetMsg = '';
    $('#main-teh-tab').css("display", "none");
    $('.about-content').html(meetMsg);
  }

  function unbindEvents() {
    $('a[data-toggle="tab"]').unbind('shown.bs.tab');
  }


  $(document).ready(function () {
    $('#complex-tree').mouseover(function () {
      $('#complex-tree').width('45%');
      $('#complex-info').width('45%');
    });
    $('#complex-info').mouseover(function () {
      $('#complex-info').width('65%');
      $('#complex-tree').width('25%');
      $('#fancyree_w0').css('overflow-x', 'auto');
    })
  });

  function reloadTree(activate) {
    var tree = $("#fancyree_w0").fancytree("getTree");
    var node = $("#fancyree_w0").fancytree("getActiveNode");
    var key = node.key;
    if (activate){
      tree.reload();
    }
  }


</script>
