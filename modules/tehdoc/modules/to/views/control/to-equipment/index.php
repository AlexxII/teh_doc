<?php

use yii\helpers\Html;
use app\assets\FancytreeAsset;

FancytreeAsset::register($this);

$this->title = 'Оборудование';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = ['label' => 'ТО', 'url' => ['/tehdoc/to/schedule']];
$this->params['breadcrumbs'][] = $this->title;

$about = "Панель управления оборудованием, добавленным в графики проведения ТО.";
$add_hint = 'Добавить группу';
$del_hint = 'Удалить БЕЗ вложений';
$del_root_hint = 'Удалить ветку полностью';
$del_multi_nodes = 'Удалить С вложениями';

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
  .fancytree-custom-icon {
    color: #1e6887;
    font-size: 18px;
  }
  .t {
    font-size: 14px;
  }

</style>

<div class="admin-category-pannel">

  <h3><?= Html::encode('Оборудование в графике ТО') ?>
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


  <div class="col-lg-5 col-md-5">
    <div class="alert alert-warning" style="margin-bottom: 10px">
      <a href="#" class="close" data-dismiss="alert">&times;</a>
      <strong>Внимание!</strong> Выберите оборудование, серийный номер которого будет использоваться в графике ТО. Если
      выпадающий список не активен, значит у объекта отсутствуют дочерные элементы.
    </div>

    <div class="about-info" style="margin-bottom: 10px"></div>
    <form action="create" method="post" class="input-add">
      <div class="about-main">
        <label>Серийный номер:</label>
        <input id="serial-number" class="form-control" disabled+>
        <label>Оборудование:</label>
        <select type="text" id="serial-control" class="form-control" name="sn" disabled></select>
        <label style="font-weight:400;font-size: 10px">Выберите оборудование.</label>
      </div>
      <div class="about-footer"></div>
      <button type="submit" onclick="saveClick(event)" class="btn btn-primary save-btn" disabled>Сохранить</button>
    </form>
  </div>


</div>


<script>

  var nodeId;
  var node$;

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

  function warningAlert(text) {
    var div = '' +
      '<div id="w3-success-0" class="alert-warning alert fade in">' +
      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
      text +
      '</div>';
    return div;
  }

  // сохрание оборудования, сереийный номер которого будет использоваться в графике ТО
  function saveClick(e) {
    e.preventDefault();
    var csrf = $('meta[name=csrf-token]').attr("content");
    var nodeId = window.nodeId;
    var serial = $('#serial-number').val();
    $.ajax({
      url: "/tehdoc/to/control/to-equipment/tool-serial-save",
      type: "post",
      data: {
        serial: serial,
        _csrf: csrf,
        id: nodeId
      },
      success: function (result) {
        if (result) {
          $('.about-info').hide().html(goodAlert('Запись добавлена в БД.')).fadeIn('slow');
          window.node$.data.eq_serial = serial;
        } else {
          $('.about-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
            'снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
        }
      },
      error: function () {
        $('.about-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
          'снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
      }
    });
  }

  function serialControl(el) {
    var serial = $(el).find(':selected').data('serial');
    if (serial == '' || serial == null){
      $(".save-btn").prop("disabled", true);
      $('#serial-number').val('');
    } else {
      $('#serial-number').val(serial);
      $(".save-btn").prop("disabled", false);
    }
    return;
  }

  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  $(document).ready(function () {
    $("#serial-number").on('keyup mouseclick', function () {
      $(".save-btn").prop("disabled", this.value.length == "" ? true : false);
    });
  });

  $(document).ready(function () {
    $('.add-subcategory').click(function (event) {
      event.preventDefault();
      var tree = $(".ui-draggable-handle").fancytree("getTree");
      $.ajax({
        url: "/tehdoc/to/control/to-equipment/create-root",
        data: {title: 'Новая группа'}
      })
        .done(function () {
          tree.reload();
        })
        .fail(function () {
          alert("Что-то пошло не так. Перезагрузите форму с помошью клавиши.");
        });
    });
  });


  $(document).ready(function () {
    $('.refresh').click(function (event) {
      event.preventDefault();
      var tree = $(".ui-draggable-handle").fancytree("getTree");
      tree.reload();
      $(".del-root").hide();
      $(".del-node").hide();
      $(".del-multi-nodes").hide();
      $('.about-info').html('')
    })
  });

  $(document).ready(function () {
    $('.del-node').click(function (event) {
      return;
      if (confirm('Вы уверены, что хотите удалить выбранный классификатор?')) {
        event.preventDefault();
        var csrf = $('meta[name=csrf-token]').attr("content");
        var node = $(".ui-draggable-handle").fancytree("getActiveNode");
        $.ajax({
          url: "/tehdoc/to/control/to-equipment/delete-node",
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
          url: "/tehdoc/to/control/to-equipment/delete-root",
          type: "post",
          data: {
            id: node.data.id,
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
    var main_url = '/tehdoc/to/control/to-equipment/all-tools';
    var move_url = '/tehdoc/to/control/to-equipment/move-node';
    var create_url = '/tehdoc/to/control/to-equipment/create-node';
    var update_url = '/tehdoc/to/control/to-equipment/update-node';

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
          return true;
        },
        dragEnter: function (node, data) {
          return true;
        },
        dragDrop: function (node, data) {
          if (data.hitMode == 'over') {
            if (data.node.data.eq_id != 0) {             // Ограничение на вложенность
              return false;
            } else if (data.otherNode.data.eq_id == 0) {
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
      icon: function (event, data) {
        if (data.node.key == 1122334455) {
          return "fa fa-sitemap";
        } else if (data.node.key == 5544332211) {
          return "fa fa-question-circle";
        } else if (data.node.data.eq_id == 0) {
          return "t fa fa-clone";
        } else {
          return "t fa fa-file-o";
        }
      },
      activate: function (node, data) {
        $('.about-info').html('');
        $('#serial-number').val('');
        $("#serial-control").children().remove();
        $("#serial-control").prop("disabled", true);
        var node = data.node;
        var lvl = node.data.lvl;
        window.node$ = data.node;
        window.nodeId = node.data.id;
        var serial = node.data.eq_serial;
        if (node.key == -999) {
          $(".add-subcategory").hide();
          return;
        } else {
          $(".add-subcategory").show();
        }
        if (lvl > 1) {
          $('#serial-number').prop("disabled", false);
          if (serial) {
            $('#serial-number').val(serial);
          } else {
            $('#serial-number').val('');
          }
          $.ajax({
            url: '/tehdoc/to/control/to-equipment/tools-serials',
            data: {
              id: node.data.ref,
            }
          }).done(function (result) {
            if (result) {
              var serial = 0;
              var result = JSON.parse(result, function (key, value) {
                if (key == 'single') serial = 1;
                  return value;
              });
              if (serial){
                if (result.single != '' && result.single != null){
                  $('#serial-number').val(result.single);
                } else {
                  $('#serial-number').val('');
                  $(".save-btn").prop("disabled", true);
                }
              } else {
                var optionsValues = '<select class="form-control input-sm" id="serial-control" onchange=serialControl(this) style="margin-top: 5px">';
                optionsValues += '<option selected disabled>Выберите</option>';
                $.each(result, function (index, obj) {
                  if (obj.eq_serial != '' && obj.eq_serial != null) {
                    var serVal = 's/n: ' + obj.eq_serial;
                  } else {
                    serVal = 's/n: -';
                  }
                  optionsValues += '<option value="' + obj.ref + '" ' +
                    'data-serial="' + obj.eq_serial + '">' + obj.name + ' ' + serVal + '</option>';
                });
                optionsValues += '</select>';
                var options = $('#serial-control');
                options.replaceWith(optionsValues);
              }
            } else if (result == -1) {
              $('.about-info').hide().html(warningAlert('У объекта нет серийного номера, введите его самостоятельно' +
                ' в поле ввода.')).fadeIn('slow');
            } else {
              $('.about-info').hide().html(badAlert('Что-то пошло не так. Попробуйте перезагрузить страницу и попробовать' +
                ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
            }
          }).fail(function (result) {
            $('.about-info').hide().html(badAlert('Что-то пошло не так. Попробуйте перезагрузить страницу и попробовать' +
              ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
          }).always(function () {
            return;
          });
        } else if (lvl == 1) {
          $('#serial-number').prop("disabled", true);
          $(".save-btn").prop("disabled", true);
        }
        if (lvl == 0) {
          $(".del-root").show();
          $(".del-node").hide();
          $(".del-multi-nodes").hide();
        } else {
          $(".add-subcategory").hide();
          if (node.hasChildren()) {
            $(".del-multi-nodes").show();
          } else {
            $(".del-multi-nodes").hide();
          }
          $(".del-root").hide();
          $(".del-node").show();
        }
      },
      renderNode: function (node, data) {
        if (data.node.key == -999) {
          $(".add-category").show();
          $(".add-subcategory").hide();
        }
      }
    });
  });


</script>