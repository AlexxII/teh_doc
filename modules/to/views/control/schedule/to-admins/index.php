<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$add_hint = 'Добавить запись';
$refresh_hint = 'Перезапустить форму';
$del_hint = 'Удалить';

$user_hint = 'Выберите пользователя системы с которым вы хотите сопоставить узел в дереве';
$role_hint = 'Выберите роль пользователя при проведении ТО';

?>

<div class="">
  <div class="">
    <div class="container-fluid" style="margin-bottom: 10px">
      <button class="btn btn-success btn-sm add-subcategory" title="<?= $add_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_admins"
              data-root="Сотрудники, участвующие в ТО">
        <i class="fa fa-plus" aria-hidden="true"></i>
      </button>
      <button class="btn btn-success btn-sm refresh" title="<?= $refresh_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_admins">
        <i class="fa fa-refresh" aria-hidden="true"></i>
      </button>
      <button class="btn btn-danger btn-sm del-node" title="<?= $del_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_admins"
              data-delete="/to/control/schedule/to-admins/delete" style="display: none">
        <i class="fa fa-trash" aria-hidden="true"></i>
      </button>
    </div>
  </div>

  <div class="col-lg-7 col-md-7" style="padding-bottom: 10px">
    <div style="position: relative">
      <div class="container-fuid" style="float:left; width: 100%">
        <input class="form-control form-control-sm" autocomplete="off" name="search" placeholder="Поиск...">
      </div>
      <div style="padding-top: 8px; right: 10px; position: absolute">
        <a href="" class="btnResetSearch" data-tree="fancytree_to_admins">
          <i class="fa fa-times-circle" aria-hidden="true" style="font-size:20px; color: #9d9d9d"></i>
        </a>
      </div>
    </div>

    <div class="row" style="padding: 0 15px">
      <div style="border-radius:2px;padding-top:40px">
        <div id="fancytree_to_admins" class="ui-draggable-handle"></div>
      </div>
    </div>
  </div>


  <div class="col-lg-5 col-md-5">
    <div id="result-info" style="margin-bottom: 10px"></div>
    <div id="admin-settings">
      <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'user-form']]); ?>
      <label> Пользователь системы:
        <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
             data-toggle="tooltip" data-placement="top" data-container="body" title="<?= $user_hint ?>"></sup>
      </label>
      <?= $form->field($model, 'name', [
        'template' => '{input}{hint}'])
        ->dropDownList($model->usersList, ['data-name' => 'vks_type', 'prompt' => ['text' => 'Выберите',
          'options' => [
            'value' => 'none',
            'disabled' => 'true',
            'selected' => 'true'
          ]], 'id' => 'user-control', 'class' => 'form-control c-select'])->hint('', ['class' => ' w3-label-under']);
      ?>
      <label> Роль:
        <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
             data-toggle="tooltip" data-placement="top" data-container="body" title="<?= $role_hint ?>"></sup>
      </label>
      <?= $form->field($model, 'name', [
        'template' => '{input}{hint}'])
        ->dropDownList($model->rolesList, ['data-name' => 'vks_type', 'prompt' => ['text' => 'Выберите',
          'options' => [
            'value' => 'none',
            'disabled' => 'true',
            'selected' => 'true'
          ]], 'id' => 'role-control', 'class' => 'form-control c-select'])->hint('', ['class' => ' w3-label-under']);
      ?>
      <div class="form-group">
        <?= Html::button('Сохранить', ['class' => 'btn btn-primary', 'id' => 'submit']) ?>
        <span id="result"></span>
      </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>

</div>


<script>
  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  var successCheck = '<i class="fa fa-check result" id="consolidated-check" aria-hidden="true" style="color: #4eb305"></i>';
  var waiting = '<i class="fa fa-cog fa-spin result" aria-hidden="true"></i>';
  var warningCheck = '<i class="fa fa-times result" id="consolidated-check" aria-hidden="true" style="color: #cc0000"></i>';
  var parent;

  $(document).ready(function () {

    $('.refresh').click(function (event) {
      event.preventDefault();
      var tree = $(".ui-draggable-handle").fancytree("getTree");
      tree.reload();
      $(".del-node").hide();
      $('.c-select').prop('disabled', true);
      $('.c-select').val('none');
      $('#submit').prop('disabled', true);
      $('#result').html('');
      $('#result-info').html('');
    });

    $('#submit').prop("disabled", true);
    $('.c-select').prop('disabled', true);

    $('.c-select').change(function (e) {
      $('#submit').prop("disabled", false);
    });

    $('#submit').click(function (e) {
      var url = '/to/control/schedule/to-admins/save-settings';
      var csrf = $('meta[name=csrf-token]').attr("content");
      var node = $(".ui-draggable-handle").fancytree("getActiveNode");
      $('#result').html(waiting);
      var userVal = $('#user-control').val();
      var roleVal = $('#role-control').val();
      $.ajax({
        url: url,
        type: "post",
        data: {
          _csrf: csrf,
          id: node.data.id,
          userVal: userVal,
          roleVal: roleVal
        }
      }).done(function (response) {
        $('#result').html(successCheck);
        node.data.admin = roleVal;
        node.data.user_id = userVal;
      }).fail(function (response) {
        $('#result').html(warningCheck);
      })
    });

    // отображение и логика работа дерева
    var main_url = '/to/control/schedule/to-admins/all-admins';
    var move_url = '/to/control/schedule/to-admins/move-node';
    var create_url = '/to/control/schedule/to-admins/create-node';
    var update_url = '/to/control/schedule/to-admins/update-node';

    $("#fancytree_to_admins").fancytree({
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
            return false;
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
                id: node.data.id,
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
            // Since we started an async request, mark the node as preliminary
            $(data.node.span).addClass("pending")
          }
        }
      },
      activate: function (node, data) {
        var node = data.node;
        var lvl = node.data.lvl;
        if (node.key == -999) {
          $("#add-subcategory").hide();
          return;
        }
        if (lvl == 0) {
          $(".del-node").hide();
        } else {
          $(".del-node").show();
        }
        var userId = node.data.user_id;
        var roleId = node.data.admin;
        $('#result').html('');
        $('#result-info').html('');
        if (lvl == 0) {
          $('.c-select').prop('disabled', true);
          $('.c-select').val('none');
          $('#submit').prop('disabled', true);
        } else {
          $('.c-select').prop('disabled', false);
          $('#submit').prop('disabled', true);
          $('.c-select').val('none');
        }
        if (userId) {
          $('#user-control').val(userId);
        }
        if (roleId) {
          $('#role-control').val(roleId);
        }
      },
      click: function (event, data) {
      },
      renderNode: function (node, data) {
        if (data.node.key == -999) {
          $("#add-subcategory").hide();
        }
      }
    });
  })


</script>