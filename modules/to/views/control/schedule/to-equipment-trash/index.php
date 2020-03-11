<?php

use yii\helpers\Html;

$about = "Панель управления оборудованием, удаленным из графика проведения ТО.";
$del_hint = 'Удалить обертку';
$refresh_hint = 'Перезапустить форму';
$serial_hint = 'Серийный номер, который был указан при добавлении данного оборудования в график ТО';
$ref_hint = 'К оборудованию в основном перечне';

?>

<div class="">
  <div class="">
    <div class="container-fluid" style="margin-bottom: 10px">
      <button class="btn btn-success btn-sm refresh" title="<?= $refresh_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_equipment">
        <i class="fa fa-refresh" aria-hidden="true"></i>
      </button>
      <button class="btn btn-danger btn-sm del-node" title="<?= $del_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_equipment"
              data-delete="/to/control/schedule/to-equipment-trash/delete" style="display: none">
        <i class="fa fa-trash" aria-hidden="true"></i>
      </button>
      <button id="tool-ref" class="btn btn-info btn-sm" title="<?= $ref_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_equipment"
              data-delete="/to/control/schedule/to-equipment-trash/delete" style="display: none">
        <i class="fa fa-level-up" aria-hidden="true"></i>
      </button>
    </div>

    <div class="col-lg-7 col-md-7" style="padding-bottom: 10px">
      <div style="position: relative">
        <div class="container-fuid" style="float:left; width: 100%">
          <input class="form-control form-control-sm" autocomplete="off" name="search" placeholder="Поиск...">
        </div>
        <div style="padding-top: 8px; right: 10px; position: absolute">
          <a href="" class="btnResetSearch" data-tree="fancytree_to_equipment">
            <i class="fa fa-times-circle" aria-hidden="true" style="font-size:20px; color: #9d9d9d"></i>
          </a>
        </div>
      </div>

      <div class="row" style="padding: 0 15px">
        <div style="border-radius:2px;padding-top:40px">
          <div id="fancytree_to_equipment" class="ui-draggable-handle"></div>
        </div>
      </div>
    </div>


    <div class="col-lg-5 col-md-5">
      <div class="alert alert-warning" style="margin-bottom: 10px">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Внимание!</strong> Удаленное из графика ТО оборудование можно перепривязать к новому оборудованию.
      </div>

      <div id="result-info" style="margin-bottom: 10px"></div>
      <form action="create" method="post" class="input-add">
        <div class="about-main">
          <label>Серийный номер:
            <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                 data-toggle="tooltip" data-placement="top" title="<?= $serial_hint ?>"></sup>
          </label>
          <input id="serial-number" class="form-control c-input" readonly>
        </div>
      </form>
    </div>

  </div>
</div>


<script>

  var nodeId;
  var node$;

  // сохрание оборудования, сереийный номер которого будет использоваться в графике ТО
  function saveClick(e) {
    e.preventDefault();
    var csrf = $('meta[name=csrf-token]').attr("content");
    var nodeId = window.nodeId;
    var serial = $('#serial-number').val();
    $.ajax({
      url: "/to/control/schedule/to-equipment-trash/tool-serial-save",
      type: "post",
      data: {
        serial: serial,
        _csrf: csrf,
        id: nodeId
      },
      success: function (result) {
        if (result) {
          $('#result-info').hide().html(goodAlert('Запись добавлена в БД.')).fadeIn('slow');
          node$.data.eq_serial = serial;
          $("#save-btn").prop("disabled", true);
        } else {
          $('#result-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
            'снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
        }
      },
      error: function () {
        $('#result-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
          'снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
      }
    });
  }

  function serialControl(el) {
    var serial = $(el).find(':selected').data('serial');
    if (serial == '' || serial == null) {
      $("#save-btn").prop("disabled", true);
      $('#serial-number').val('');
    } else {
      $('#serial-number').val(serial);
      $("#save-btn").prop("disabled", false);
    }
    return;
  }

  var serialVal;

  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $("#serial-number").on('keyup mouseclick', function () {
      $("#save-btn").prop("disabled", this.value.length == "" ? true : false);
    });

    $('#tool-ref').click(function (e) {
      e.preventDefault();
      var treeIdAttr = $(e.currentTarget).data('tree');
      var node = $("#" + treeIdAttr).fancytree("getActiveNode");
      var toolId = node.data.eq_id;
      var windowSize = 'larges';
      var title = 'Оборудование';
      var url = '/equipment/default/index-ex';
      c = $.confirm({
        content: function () {
          var self = this;
          return $.ajax({
            url: url,
            method: 'get',
            data: {
              'id': toolId
            }
          }).fail(function () {
            self.setContentAppend('<div>Что-то пошло не так!</div>');
          });
        },
        contentLoaded: function (data, status, xhr) {
          this.setContentAppend('<div>' + data + '</div>');
        },
        columnClass: windowSize,
        title: title,
        buttons: {
          cancel: {
            text: 'НАЗАД'
          }
        }
      });
    });

    // отображение и логика работа дерева
    var main_url = '/to/control/schedule/to-equipment-trash/all-tools';
    var move_url = '/to/control/schedule/to-equipment-trash/move-node';
    var create_url = '/to/control/schedule/to-equipment-trash/create-node';
    var update_url = '/to/control/schedule/to-equipment-trash/update-node';

    $("#fancytree_to_equipment").fancytree({
      source: {
        url: main_url
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
          parent = data.node.parent;
          parent.folder = true;
          var node = data.node;
          if (node.data.lvl === '0' || node.key == '-999') {
            return false;
          }
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
                node.data.eq_id = 0;
                parent.renderTitle();
                $('#result-info').hide().html(goodAlert('Запись успешно сохранена в БД.')).fadeIn('slow');
              } else {
                node.setTitle(data.orgTitle);
                $('#result-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
                  ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
              }
            }).fail(function (result) {
              node.setTitle(data.orgTitle);
              $('#result-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
                ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
            }).always(function () {
              // data.input.removeClass("pending")
            });
          } else {
            $.ajax({
              url: update_url,
              data: {
                id: nodeId,
                title: data.input.val()
              }
            }).done(function (result) {
              if (result) {
                result = JSON.parse(result);
                node.setTitle(result.acceptedTitle);
                $('#result-info').hide().html(goodAlert('Запись успешно изменена в БД.')).fadeIn('slow');
              } else {
                node.setTitle(data.orgTitle);
                $('#result-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
                  ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
              }
            }).fail(function (result) {
              $('#result-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
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
            $(data.node.span).addClass("pending")
          }
        }
      },
      activate: function (node, data) {
        $('#result-info').html('');
        $("#save-btn").prop("disabled", true);
        var node = data.node;
        var lvl = node.data.lvl;
        window.node$ = node;
        window.nodeId = node.data.id;
        serialVal = node.data.eq_serial;
        if (node.data.lvl == 0 || node.key == '-999') {
          $(".del-node").hide();
        } else {
          $(".del-node").show();
        }
        if (node.data.eq_id != 0 || node.key == '-999') {
          $('#serial-number').prop("disabled", false);
          if (serialVal) {
            $('#serial-number').val(serialVal);
          } else {
            $('#serial-number').val('');
          }
          $.ajax({
            url: '/to/control/schedule/to-equipment-trash/tools-serials',
            data: {
              id: node.data.eq_id
            }
          }).done(function (result) {

          }).fail(function (result) {
            $('#result-info').hide().html(badAlert('Что-то пошло не так. Попробуйте перезагрузить страницу и попробовать' +
              ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
          });
        } else {
          $('#tool-ref').hide();
        }
      },
      click: function (event, data) {

      },
      renderNode: function (node, data) {

      }
    });
  });


</script>