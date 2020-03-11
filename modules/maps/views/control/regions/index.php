<?php

use yii\helpers\Html;

$about = "Регионы России";
$add_hint = 'Добавить новый узел';
$refresh_hint = 'Перезапустить форму';
$del_hint = 'Удалить БЕЗ вложений';
$del_multi_nodes = 'Удвлить С вложениями';

$rNumberTitle = 'Номер субъекта согласно конституции';
$rTempTitle = 'Среднегодоваая температура';
?>
<style>
  .region-info {

  }
</style>

<div class="employee-control">
  <div class="fancytree-control-panel">
    <div class="container-fluid" style="margin-bottom: 10px">
      <?= Html::a('<i class="fa fa-plus" aria-hidden="true"></i>', ['#'], ['class' => 'btn btn-success btn-sm add-subcategory',
        'style' => ['margin-top' => '5px'],
        'title' => $add_hint,
        'data-toggle' => 'tooltip',
        'data-container' => 'body',
        'data-placement' => 'top',
        'data-tree' => 'fancytree_maps_regions'
      ]) ?>
      <?= Html::a('<i class="fa fa-refresh" aria-hidden="true"></i>', ['#'], ['class' => 'btn btn-success btn-sm refresh',
        'style' => ['margin-top' => '5px'],
        'title' => $refresh_hint,
        'data-toggle' => 'tooltip',
        'data-container' => 'body',
        'data-placement' => 'top',
        'data-tree' => 'fancytree_maps_regions'
      ]) ?>
      <?= Html::a('<i class="fa fa-trash" aria-hidden="true"></i>', ['#'], ['class' => 'btn btn-danger btn-sm del-node',
        'style' => ['margin-top' => '5px', 'display' => 'none'],
        'title' => $del_hint,
        'data-toggle' => 'tooltip',
        'data-container' => 'body',
        'data-placement' => 'top',
        'data-tree' => 'fancytree_maps_regions',
        'data-delete' => '/maps/control/regions/delete'
      ]) ?>
      <?= Html::a('<i class="fa fa-object-group" aria-hidden="true"></i>', ['#'], ['class' => 'btn btn-danger btn-sm del-multi-nodes',
        'style' => ['margin-top' => '5px', 'display' => 'none'],
        'title' => $del_multi_nodes,
        'data-toggle' => 'tooltip',
        'data-container' => 'body',
        'data-placement' => 'top',
        'data-tree' => 'fancytree_maps_regions',
        'data-delete' => '/maps/control/regions/delete-root'
      ]) ?>
    </div>

  </div>

  <div class="col-lg-7 col-md-7" style="padding-bottom: 10px">
    <div style="position: relative">
      <div class="container-fuid" style="float:left; width: 100%">
        <input class="form-control form-control-sm" autocomplete="off" name="search" placeholder="Поиск...">
      </div>
      <div style="padding-top: 8px; right: 10px; position: absolute">
        <a href="" class="btnResetSearch" data-tree="fancytree_maps_regions">
          <i class="fa fa-times-circle" aria-hidden="true" style="font-size:20px; color: #9d9d9d"></i>
        </a>
      </div>
    </div>

    <div class="row" style="padding: 0 15px">
      <div style="border-radius:2px;padding-top:40px">
        <div id="fancytree_maps_regions" class="ui-draggable-handle"></div>
      </div>
    </div>
  </div>
  <div class="col-lg-5 col-md-5 about">
    <div class="row form-group">
      <div class="col-md-12 col-lg-12">
        <label for="city">Административный центр:</label>
        <input class="form-control region-info" disabled id="city" data-input="region_center">
      </div>
    </div>
    <div class="row form-group">
      <div class="col-md-6 col-lg-6">
        <label for="area">Площадь:</label>
        <div class="input-group">
          <input type="text" class="form-control region-info" disabled id="area" data-input="region_area">
          <span class="input-group-addon">кв.км</span>
        </div>
      </div>
      <div class="col-md-6 col-lg-6">
        <label for="area-place">Место по стране:</label>
        <input class="form-control region-info" disabled id="area-place" data-input="region_area_place">
      </div>
    </div>
    <div class="row form-group">
      <div class="col-md-6 col-lg-6">
        <label for="population">Население:</label>
        <div class="input-group">
          <input type="text" class="form-control region-info" disabled id="population" data-input="region_population">
          <span class="input-group-addon">чел.</span>
        </div>
      </div>
      <div class="col-md-6 col-lg-6">
        <label for="population-place">Место по стране:</label>
        <input class="form-control region-info" disabled id="population-place" data-input="region_population_place">
      </div>
    </div>
    <div class="row form-group">
      <div class="col-md-4 col-lg-4">
        <label for="number">Номер:</label>
        <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
             data-toggle="tooltip" data-container="body" data-placement="top" title="<?= $rNumberTitle ?>"></sup>
        <input class="form-control region-info" type="text" disabled id="number" data-input="region_number">
      </div>
      <div class="col-md-8 col-lg-8">
        <label for="temp">Температура:</label>
        <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
             data-toggle="tooltip" data-container="body" data-placement="top" title="<?= $rTempTitle ?>"></sup>
        <input class="form-control region-info" disabled id="temp" data-input="region_temp">
      </div>
    </div>
  </div>


</div>


<script>

  $('[data-toggle="tooltip"]').tooltip();

  // отображение и логика работа дерева
  jQuery(function ($) {
    var main_url = '/maps/control/regions/regions';
    var move_url = "/maps/control/regions/move";
    var create_url = '/maps/control/regions/regions-create';
    var update_url = '/maps/control/regions/update';
    var detail_url = '/maps/control/regions/details';

    $("#fancytree_maps_regions").fancytree({
      source: {
        url: main_url
      },
      extensions: ['dnd', 'edit', 'filter'],
      quicksearch: true,
      minExpandLevel: 1,
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
            if (data.node.data.lvl >= 2 || data.otherNode.isFolder()) {             // Ограничение на вложенность
              jc = $.confirm({
                icon: 'fa fa-exclamation-triangle',
                title: 'Запрещено!',
                content: 'Суммарная глубина вложенности данного дерева не должна превышать 3х уровней!',
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
        var id = node.data.id;
        // if (lvl > 1) {
        //   $(".add-subcategory").hide();
        // }
        if (lvl == 0) {
          $(".del-node").hide();
          $(".del-multi-nodes").hide();
          $('.region-info').val('');
          $('.about label').css('color', '#000');
          $('.region-info').prop("disabled", true);
        } else {
          if (node.hasChildren()) {
            $(".del-multi-nodes").show();
          } else {
            $(".del-multi-nodes").hide();
          }
          $('.about label').css('color', '#000');
          $('.region-info').val('');
          $(".del-node").show();
          var url = detail_url;
          $('.region-info').prop("disabled", false);
          $.get(url, {
            id: id
          }, function (data) {
            if (data) {
              for (key in data) {
                if (data[key]) {
                  $('#' + key).val(data[key]);
                }
              }
            }
          })
        }
      },
      renderNode: function (node, data) {
        return;
      }
    });

    $('.region-info').on('change', function () {
      var url = '/maps/control/regions/save-details';
      var csrf = $('meta[name=csrf-token]').attr("content");
      var node = $('#fancytree_maps_regions').fancytree("getActiveNode");
      var val = $(this).val();
      var input = $(this).data('input');
      var id = $(this).attr('id');
      var label = $('label[for="' + id + '"]');
      $.ajax({
        url: url,
        method: 'post',
        data: {
          _csrf: csrf,
          id: node.data.id,
          input: input,
          val: val
        }
      }).done(function (response) {
        label.css('color', 'green');
      }).fail(function (response) {
        console.log(response);
        label.css('color', 'red');
      });
    });


  })

</script>