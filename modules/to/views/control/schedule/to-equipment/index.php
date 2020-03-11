<?php

use yii\helpers\Html;

$about = "Панель управления оборудованием, добавленным в графики проведения ТО.";
$add_hint = "Добавить обертку";
$del_hint = "Удалить обертку";
$refresh_hint = "Перезапустить форму";
$serial_hint = "Внимание! Серийный номер, присвоенный в данной форме отображается только в пределах раздела ТО.";
$duration_hint = "Данный параметр необходим для автоматизации формирования графика ТО.";
$on_hint = "Длительность включения оборудования на ТО. Параметр необходим для точного подсчета наработанного времени. Может отсутствовать.";
$off_hint = "Длительность отключения оборудования на ТО. Параметр необходим для точного подсчета наработанного времени. Может отсутствовать.";
$ref_hint = "К оборудованию в основном перечне";

?>

<div class="">
  <div class="">
    <div class="container-fluid" style="margin-bottom: 10px">
      <button class="btn btn-success btn-sm add-subcategory" title="<?= $add_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_equipment" data-root="Оборудование">
        <i class="fa fa-plus" aria-hidden="true"></i>
      </button>
      <button class="btn btn-success btn-sm refresh" title="<?= $refresh_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_equipment">
        <i class="fa fa-refresh" aria-hidden="true"></i>
      </button>
      <button class="btn btn-danger btn-sm del-node" title="<?= $del_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_equipment"
              data-delete="/to/control/schedule/to-equipment/delete" style="display: none">
        <i class="fa fa-trash" aria-hidden="true"></i>
      </button>
      <button id="tool-ref" class="btn btn-info btn-sm" title="<?= $ref_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_equipment"
              data-delete="/to/control/schedule/to-equipment/delete" style="display: none">
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
        <strong>Внимание!</strong> Выберите оборудование, серийный номер которого будет использоваться в графике ТО.
        Если
        выпадающий список не активен, значит у объекта отсутствуют дочерние элементы.
      </div>

      <div id="result-info" style="margin-bottom: 10px"></div>
      <form action="create" method="post" class="input-add" id="to-data">
        <div class="about-main tool-to-data">
          <label>Серийный номер:
            <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                 data-toggle="tooltip" data-placement="top" title="<?= $serial_hint ?>"></sup>
          </label>
          <input id="serial-number" class="form-control c-input" disabled>
          <br>
          <label>Оборудование:</label>
          <select type="text" id="serial-control" class="c-input form-control" name="sn" disabled></select>
          <label style="font-weight:400;font-size: 10px">Выберите оборудование.</label>
          <br>
          <label>t обслуживания:
            <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                 data-toggle="tooltip" data-placement="top" title="<?= $duration_hint ?>"></sup>
          </label>
          <input type="text" id="to-duration" class="c-input form-control" name="toDuration" disabled>
          <label style="font-weight:400;font-size: 10px">В минутах.</label>
          <br>
          <div class="row">
            <div class="col-md-6 col-lg-6 col-xs-6">
              <label>t включения:
                <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                     data-toggle="tooltip" data-placement="top" title="<?= $on_hint ?>"></sup>
              </label>
              <input type="text" id="to-duration-on" class="c-input form-control" name="onTime" disabled>
              <label style="font-weight:400;font-size: 10px">В минутах.</label>
            </div>
            <div class="col-md-6 col-lg-6 col-xs-6">
              <label>t отключения:
                <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                     data-toggle="tooltip" data-placement="top" title="<?= $off_hint ?>"></sup>
              </label>
              <input type="text" id="to-duration-off" class="c-input form-control" name="blackOut" disabled>
              <label style="font-weight:400;font-size: 10px">В минутах.</label>
            </div>
          </div>
        </div>
        <div class="about-footer"></div>
        <button type="submit" id="save-btn" onclick="saveToData(event)" class="btn btn-primary" disabled>Сохранить
        </button>
      </form>
    </div>

  </div>
</div>


<script>

  var nodeId;
  var node$;

  // сохрание оборудования, сереийный номер которого будет использоваться в графике ТО
  function saveToData(e) {
    e.preventDefault();
    var csrf = $('meta[name=csrf-token]').attr("content");

    var serial = $('#serial-number').val();
    var toDuration = $('#to-duration').val();
    var onTime = $('#to-duration-on').val();
    var blackOut = $('#to-duration-off').val();

    var $form = $("#to-data");
    var data = $form.serialize();
    $.ajax({
      url: "/to/control/schedule/to-equipment/to-data-save",
      type: "post",
      data: {
        _csrf: csrf,
        data: {
          'serial': serial,
          'toDuration': toDuration,
          'onTime': onTime,
          'blackOut': blackOut
        },
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

  var serialVal;

  $(document).on('change', '#serial-control', function () {
    var serial = $(this).find(':selected').data('serial');
    if (serial == '' || serial == null) {
      $('#serial-number').val('');
      $("#save-btn").prop("disabled", true);
    } else {
      $('#serial-number').val(serial);
      $("#save-btn").prop("disabled", false);
    }
  });

  // $('.tool-to-data input').prop('disabled', true).val('');

  $(document).on('keyup', '.tool-to-data input', function () {
    $('.tool-to-data input').each(function () {
      console.log($(this).val());
    });
/*
    if ($('.tool-to-data input').val()){
      // $("#save-btn").prop("disabled", this.value.length == "" ? true : false);
      $("#save-btn").prop("disabled", false);
    }
*/
  });


  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

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
    var main_url = '/to/control/schedule/to-equipment/all-tools';
    var move_url = '/to/control/schedule/to-equipment/move-node';
    var create_url = '/to/control/schedule/to-equipment/create-node';
    var update_url = '/to/control/schedule/to-equipment/update-node';

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
          if (node.data.lvl === '0' || node.data.key == -999) {
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
        // выбор ноды -> опустошение всех полей для дальнейшего заполнения (опустошение, а не замена - если что-то пойдет не так)
        $('.tool-to-data input').prop('disabled', true).val('');
        $('#serial-control').prop('disabled', true).val('none');
        $('#serial-control').children().remove();
        $('#result-info').html('');
        $("#save-btn").prop("disabled", true);
        // заполнение глобальныхх переменных, на них операются некоторые функции
        var node = data.node;
        var lvl = node.data.lvl;
        node$ = node;
        nodeId = node.data.id;
        // значки управления деревом
        if (node.data.lvl == 0) {
          $(".del-node").hide();
        } else {
          $(".del-node").show();
        }
        if (node.data.eq_id != 0) {
          $('#tool-ref').show();
          $(".del-node").hide();
          loadToData(node.data.eq_id, function (result) {
            var data = JSON.parse(result);
            var selectData = data.selectData;
            if (selectData != -1) {
              $('#serial-control').append('<option value="null" disabled selected>Выберите</option>');
              selectData.forEach(function (val, index, array) {
                $('#serial-control').append('<option value=' + val.eq_serial + ' data-serial="' + val.eq_serial + '">' +
                  val.name + ' - s/n: ' + val.eq_serial + '</option>');
              });
              $('#serial-control').prop('disabled', false);
            } else {
              if (!node.data.eq_serial) {
                $('#result-info').hide().html(warningAlert('У выбранного оборудования не был указан серийный номер и ' +
                  'отсутствуют вложенные элементы. ' +
                  'Заполните их в модуле "Техника" или заполните поле "Серийный номер" вручную.')).fadeIn('slow');
              }
            }
            $('#serial-number').prop('disabled', false).val(node.data.eq_serial);
            $('#serial-control option[value="' + node.data.eq_serial + '"]').prop('selected', true);
            fillToData(data.toData);
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

  function loadToData(toolId, callback) {
    var toDataUrl = '/to/control/schedule/to-equipment/to-data';
    $.ajax({
      url: toDataUrl,
      data: {
        id: toolId
      }
    }).done(function (result) {
      callback(result);
    }).fail(function (result) {
      $('#result-info').hide().html(badAlert('Что-то пошло не так. Попробуйте перезагрузить страницу и попробовать' +
        ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
    });
  }

  function fillToData(toData) {
    $('#to-duration').prop('disabled', false).val(toData.toDuration);
    $('#to-duration-on').prop('disabled', false).val(toData.onDuration);
    $('#to-duration-off').prop('disabled', false).val(toData.offDuration);
  }

</script>