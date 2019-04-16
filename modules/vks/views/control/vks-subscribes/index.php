<?php

use yii\helpers\Html;
use app\assets\FancytreeAsset;

FancytreeAsset::register($this);

$this->title = 'Абоненты ВКС';
$this->params['breadcrumbs'][] = ['label' => 'ВКС', 'url' => ['/vks']];
$this->params['breadcrumbs'][] = ['label' => 'Журнал', 'url' => ['/vks/sessions']];
$this->params['breadcrumbs'][] = $this->title;

$about = "Панель управления абонента ВКС. При сбое, перезапустите форму, воспользовавшись соответствующей клавишей.";
$add_hint = 'Добавить новый узел';
$add_tree_hint = 'Добавить дерево';
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
    font-size: 14px;
  }
  input {
    color: black;
  }
</style>

<div class="admin-category-pannel">

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

  <div class="col-lg-7 col-md-7" style="padding-bottom: 10px">
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

  <div class="col-lg-5 col-md-5" style="margin-bottom: 10px">
    <div class="alert alert-warning">
      <a href="#" class="close" data-dismiss="alert">&times;</a>
      <strong>Внимание!</strong> Выберите ведомство в каталоге и укажите фамилии. Будьте внимательны!
    </div>
  </div>

  <div class="col-lg-5 col-md-5 about">
    <div class="about-info"></div>
    <form action="create" method="post" class="input-add">
      <div class="about-main">
        <input id="node-id" style="display: none" readonly>
        <label>Фамилии и инициалы сотрудников:</label>
        <textarea type="text" id="surnames-control" class="form-control" name="surnames"
                  style="resize: vertical" rows=5 disabled></textarea>
        <label style="font-weight:400;font-size: 10px">Фамилии перечиляются через ";". Например: Шойгу С.К.;
          Герасимов В.В.</label>
      </div>
      <div class="about-footer"></div>
      <button type="submit" onclick="saveClick(event)" class="btn btn-primary save-btn" disabled>Сохранить</button>
    </form>
  </div>
</div>


<script>
  var nodeId;

  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  $(document).ready(function () {
    $('.add-subcategory').click(function (event) {
      event.preventDefault();
      var node = $(".ui-draggable-handle").fancytree("getActiveNode");
      if (!node) {
        alert("Выберите родительский элемент");
        return;
      }
      if (node.data.lvl <= 1) {
        node.editCreateNode("child", " ");
      } else {
        alert("Нельзя создавать вложенность более 3х");
        return;
      }
    })
  });

  $(document).ready(function () {
    $('.refresh').click(function (event) {
      event.preventDefault();
      var tree = $(".ui-draggable-handle").fancytree("getTree");
      tree.reload();
      $(".del-node").hide();
      $(".del-multi-nodes").hide();
      $(".save-btn").prop("disabled", true);
      $("#surnames-control").prop("disabled", true);
      $('.about-info').html('')
    })
  });

  $(document).ready(function () {
    $('.del-node').click(function (event) {
      var url = "/vks/control/vks-subscribes/delete";
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
      var url = "/vks/control/vks-subscribes/delete-root";
      event.preventDefault();
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
        data: {id: node.data.id, _csrf: csrf}
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
                  $('.about-info').html('');
                  $('.del-node').hide();
                  $(".del-multi-nodes").hide();
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
          content: 'Запрос не вы!!!полнен. Что-то пошло не так.',
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
    $("input[name=search]").keyup(function (e) {
      if ($(this).val() == '') {
        var tree = $(".ui-draggable-handle").fancytree("getTree");
        tree.clearFilter();
      }
    })
  });

  $(document).ready(function () {
    $("textarea").on('keyup mouseclick', function () {
      $(".save-btn").prop("disabled", this.value.length == "" ? true : false);
    });
  });

  function saveClick(e) {
    e.preventDefault();
    var csrf = $('meta[name=csrf-token]').attr("content");
    var nodeId = window.nodeId;
    var surnames = $("textarea").val();
    $.ajax({
      url: "/vks/control/vks-subscribes/surnames-save",
      type: "post",
      data: {Data: surnames, _csrf: csrf, id: nodeId},
      success: function (result) {
        if (result) {
          $('.about-info').hide().html(goodAlert('Записи добавлены в БД.')).fadeIn('slow');
        } else {
          $('.about-info').hide().html(badAlert('Записи не сохранены в БД. Попробуйте перезагрузить страницу и попробовать' +
            'снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
        }
      },
      error: function () {
        $('.about-info').hide().html(badAlert('Записи не сохранены в БД. Попробуйте перезагрузить страницу и попробовать' +
          'снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
      }
    });
  }

  // отображение и логика работа дерева
  jQuery(function ($) {
    var main_url = '/vks/control/vks-subscribes/subscribes';
    var move_url = '/vks/control/vks-subscribes/move';
    var create_url = '/vks/control/vks-subscribes/vks-subscribes-create';
    var update_url = '/vks/control/vks-subscribes/update';
    var surnames_url = '/vks/control/vks-subscribes/surnames';

    $("#fancyree_w0").fancytree({
      source: {
        url: main_url,
      },
      extensions: ['dnd', 'edit', 'filter'],
      quicksearch: true,
      minExpandLevel: 2,
      dnd: {
        preventVoidMoves: true,
        preventRecursiveMoves: true,
        autoCollapse: true,
        dragStart: function (node, data) {
          if (data.node.data.lvl == 0) {
            return false;
          }
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
                node.setTitle(result.acceptedTitle);
                node.data.lvl = result.lvl;
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
                id: node.data.id,
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
        if (lvl > 1) {                                         // ограничения по вложенности
          $(".add-subcategory").hide();
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
        var id = node.data.id;
        if (lvl == 2) {
          $("#surnames-control").prop("disabled", false);
        } else {
          $("#surnames-control").prop("disabled", true);

        }
        $(".save-btn").prop("disabled", true);
        var url = surnames_url;
        $.get(url, {
          id: id
        }, function (data) {
          if (data) {
            var surnames = JSON.parse(data);
            $("#surnames-control").val(surnames);
            window.nodeId = id;
          }
        })
      },
      renderNode: function (node, data) {
        if (data.node.key == -999) {
          $(".add-category").show();
          $(".add-subcategory").hide();
        }
      }
    });
  })

</script>