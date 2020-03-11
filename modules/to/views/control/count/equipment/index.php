<?php

use yii\helpers\Html;

$add_hint = 'Добавить обертку';
$del_hint = 'Удалить обертку';
$refresh_hint = 'Перезапустить форму';
$ref_hint = 'К оборудованию в основном перечне';

?>

<div class="">
  <div class="">
    <div class="container-fluid" style="margin-bottom: 10px">
      <button class="btn btn-success btn-sm add-subcategory" title="<?= $add_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_worktime_equipment"
              data-root="Оборудование">
        <i class="fa fa-plus" aria-hidden="true"></i>
      </button>
      <button class="btn btn-success btn-sm refresh" title="<?= $refresh_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_worktime_equipment">
        <i class="fa fa-refresh" aria-hidden="true"></i>
      </button>
      <button class="btn btn-danger btn-sm del-node" title="<?= $del_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_worktime_equipment"
              data-delete="/to/control/count/equipment/delete" style="display: none">
        <i class="fa fa-trash" aria-hidden="true"></i>
      </button>
      <button id="tool-ref" class="btn btn-info btn-sm" title="<?= $ref_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_worktime_equipment"
              data-delete="/to/control/to-equipment/delete" style="display: none">
        <i class="fa fa-level-up" aria-hidden="true"></i>
      </button>
    </div>

    <div class="col-lg-7 col-md-7" style="padding-bottom: 10px">
      <div style="position: relative">
        <div class="container-fuid" style="float:left; width: 100%">
          <input class="form-control form-control-sm" autocomplete="off" name="search" placeholder="Поиск...">
        </div>
        <div style="padding-top: 8px; right: 10px; position: absolute">
          <a href="" class="btnResetSearch" data-tree="fancytree_to_worktime_equipment">
            <i class="fa fa-times-circle" aria-hidden="true" style="font-size:20px; color: #9d9d9d"></i>
          </a>
        </div>
      </div>

      <div class="row" style="padding: 0 15px">
        <div style="border-radius:2px;padding-top:40px">
          <div id="fancytree_to_worktime_equipment" class="ui-draggable-handle"></div>
        </div>
      </div>
    </div>


    <div class="col-lg-5 col-md-5">
      <div class="alert alert-warning" style="margin-bottom: 10px">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Внимание!</strong> В данном разделе представлено оборудование, которое настроено на подсчет
        наработанного
        времени.<br>Выберите шаблон и необходимые настройки подсчета.
      </div>

      <div id="result-info" style="margin-bottom: 10px"></div>
      <form action="create" method="post" class="input-add">
        <div class="about-main">
          <label>Шаблон подсчета:</label>
          <select type="text" id="template-control" class="c-input form-control" name="sn" disabled></select>
          <label style="font-weight:400;font-size: 10px">Выберите шаблон.</label>
          <br>
          <br>
          <label>
            <input class="check-it" type="checkbox" id="special_works_feature"
                   data-check='special-check'
                   data-url='special-works'>
            Не учитывать ТО</label>
          <span class="status-indicator" id="special-check"></span>
          <p class="note">При подсчете наработанного времени не учитывать время на проведение ТО</p>

        </div>
        <div class="about-footer" style="padding-bottom: 10px">

        </div>
        <button type="submit" id="save-btn" onclick="saveTemplate(event)" class="btn btn-primary" disabled>Сохранить
        </button>
      </form>
    </div>
  </div>
</div>


<script>

  var nodeId;
  var node$;

  toCountTemplates = '<select class="form-control to-list m-select">' +
    '<option value="none" selected="true" disabled="true">Выберите</option>';

  allDayWorkEx = '<span>' +
    '<div><h4>Дополнительные особенности</h4></div>' +
    '<label>Количество часов:</label>\n' +
    '<input class="form-control">' +
    '</span>';


  $.ajax({
    url: '/to/control/settings/count-templates',
    method: 'get',
    dataType: "JSON"
  }).done(function (response) {
    var templates = response.templates;
    templates.forEach(function (value, index, array) {
      toCountTemplates += '<option value="' + value.id + '">' + value.name + '</option>';
    });
    toCountTemplates += '</select>';
    $('#template-control').html(toCountTemplates);
  }).fail(function () {
    console.log('Не удалось загрузить шаблоны подсчета наработки');
    toCountTemplates += '</select>';
  });

  // сохрание оборудования, сереийный номер которого будет использоваться в графике ТО
  function saveTemplate(e) {
    e.preventDefault();
    var csrf = $('meta[name=csrf-token]').attr("content");
    var nodeId = window.nodeId;
    var template = $('#template-control').val();
    $.ajax({
      url: "/to/control/count/equipment/save-template",
      type: "post",
      data: {
        _csrf: csrf,
        id: nodeId,
        template: template
      },
      success: function (result) {
        if (result) {
          $('#result-info').hide().html(goodAlert('Запись добавлена в БД.')).fadeIn('slow');
          node$.data.count_template = template;
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

  var template;

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
    var main_url = '/to/control/count/equipment/all-tools';
    var move_url = '/to/control/count/equipment/move-node';
    var create_url = '/to/control/count/equipment/create-node';
    var update_url = '/to/control/count/equipment/update-node';

    $("#fancytree_to_worktime_equipment").fancytree({
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
          if (data.node.data.lvl == '0') return false;
          parent = data.node.parent;
          parent.folder = true;
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
        $('#template-control').val('none');
        var node = data.node;
        var lvl = node.data.lvl;
        nodeId = node.data.id;
        console.log(node.data);
        countTmpt = node.data.count_template;
        if (countTmpt) {
          $('#template-control').val(countTmpt);
        } else {
          $('#template-control').val('');
        }
        node$ = node;
        $('#result-info').html('');
        if (lvl == 0) {
          $('.del-node').hide();
        } else {
          $('.del-node').show();
        }
        if (node.data.eq_id != 0) {
          $('#tool-ref').show();
          $('.del-node').hide();
          $('#template-control').prop('disabled', false);
        } else {
          $('#tool-ref').hide();
          $('#template-control').prop('disabled', true);
        }
        if (node.data.template) {
          $('#template-control').val(node.data.count_template);
        }
      },
      click: function (event, data) {

      },
      renderNode: function (node, data) {
        if (data.node.key == -999) {
          $('.add-subcategory').hide();
        }
      }
    })
    ;
  });

  $(document).on('change', '#template-control', function (e) {
    $('.about-footer').html(allDayWorkEx);
    $('#save-btn').prop('disabled', false);
  })

</script>