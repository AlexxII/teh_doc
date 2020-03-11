<?php

use yii\helpers\Html;

$about = "Панель управления оборудованием, добавленным в графики проведения ТО.";
$add_hint = 'Добавить обертку';
$del_hint = 'Удалить обертку';
$refresh_hint = 'Перезапустить форму';
$serial_hint = 'Внимание! Серийный номер, присвоенный в данной форме отображается только в пределах раздела ТО';
$ref_hint = 'К оборудованию в основном перечне';

?>

<div class="">
  <div class="col-lg-5 col-md-5" style="padding-bottom: 10px">
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


  <div class="col-lg-7 col-md-7">
    <div class="alert alert-warning" style="margin-bottom: 10px">
      <a href="#" class="close" data-dismiss="alert">&times;</a>
      <strong>Внимание!</strong> Данное оборудование
    </div>

  </div>


</div>


<script>

  var nodeId;
  var node$;

  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('#tool-ref').click(function (event) {
      event.preventDefault();
      var node = $(".ui-draggable-handle").fancytree("getActiveNode");
      var toolId = node.data.eq_id;
      var prefix = '/equipment/tool/';
      var href = prefix + toolId + '/info/index';
      if (event.ctrlKey) {
        window.open(href);
      } else {
        location.href = href;
      }
    });

    // отображение и логика работа дерева
    var main_url = '/to/control/count/equipment/all-tools';

    $("#fancytree_to_equipment").fancytree({
      source: {
        url: main_url
      },
      extensions: ['filter'],
      quicksearch: true,
      minExpandLevel: 2,
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
      activate: function (node, data) {
        $('#serial-number').val('');
        $('#serial-control').val('none');
        var node = data.node;
        var lvl = node.data.lvl;
        var eqId = node.data.eq_id;
        window.node$ = node;
        window.nodeId = node.data.id;
        serialVal = node.data.eq_serial;
        if (eqId != 0) {
          $('#serial-number').prop("disabled", false);
          if (serialVal) {
            $('#serial-number').val(serialVal);
          } else {
            $('#serial-number').val('');
          }
          $.ajax({
            url: '/to/control/to-equipment/tools-serials',
            data: {
              id: node.data.eq_id
            }
          }).done(function (result) {
            if (result != -1) {
              var serial = 0;
              var result = JSON.parse(result, function (key, value) {
                if (key == 'single') serial = 1;
                return value;
              });
              if (serial) {
                if (result.single != '' && result.single != null) {
                  $('#serial-number').val(result.single);
                  if (node.data.eq_serial == null) {
                    $("#save-btn").prop("disabled", false);
                  }
                } else {
                  $('#serial-number').val('');
                  $("#save-btn").prop("disabled", true);
                }
              } else {
                var optionsValues = '<select class="form-control input-sm c-input" id="serial-control" ' +
                  'onchange=serialControl(this) style="margin-top: 5px">';
                optionsValues += '<option selected disabled>Выберите</option>';
                $.each(result, function (index, obj) {
                  if (obj.eq_serial != '' && obj.eq_serial != null) {
                    var serVal = 's/n: ' + obj.eq_serial;
                  } else {
                    serVal = 's/n: -';
                  }
                  optionsValues += '<option value="' + obj.id + '" ' +
                    'data-serial="' + obj.eq_serial + '">' + obj.name + ' ' + serVal + '</option>';
                });
                optionsValues += '</select>';
                var options = $('#serial-control');
                options.replaceWith(optionsValues);
                if (serialVal) {
                  $("#serial-control option[data-serial='" + serialVal + "']").attr("selected", "selected");
                }
              }
            } else if (result == -1) {
              if ($('#serial-number').val() == '') {
                $('#result-info').hide().html(warningAlert('У объекта нет серийного номера, введите его самостоятельно' +
                  ' в поле ввода.')).fadeIn('slow');
              }
            } else {
              $('#result-info').hide().html(badAlert('Что-то пошло не так. Попробуйте перезагрузить страницу и попробовать' +
                ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
            }
          }).fail(function (result) {
            $('#result-info').hide().html(badAlert('Что-то пошло не так. Попробуйте перезагрузить страницу и попробовать' +
              ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
          });
        } else {
          $("#serial-control").prop("disabled", true);
          $('#serial-number').prop("disabled", true);
          $('#serial-number').val('');
        }
      },
      click: function (event, data) {
        $('#result-info').html('');
        $("#serial-control").children().remove();
        $("#serial-control").prop("disabled", true);
        var node = data.node;
        var lvl = node.data.lvl;
        $("#save-btn").prop("disabled", true);
        if (node.key == -999) {
          $("#add-subcategory").hide();
          return;
        }
        if (lvl == 0) {
          $(".del-node").hide();
        } else {
          $(".del-node").show();
        }
        if (node.data.eq_id != 0) {
          $('#tool-ref').show();
          $(".del-node").hide();
        } else {
          $('#tool-ref').hide();
        }
      },
      renderNode: function (node, data) {
        if (data.node.key == -999) {
          $("#add-subcategory").hide();
        }
      }
    })
    ;
  });


</script>