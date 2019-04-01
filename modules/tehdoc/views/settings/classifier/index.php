<?php

use yii\helpers\Html;
use app\assets\FancytreeAsset;

FancytreeAsset::register($this);

$this->title = 'Классификатор';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = $this->title;

$about = "Панель управления классификаторами информации. 
            Позволяет произвольно задавать параметры отображения информации";
$add_hint = 'Добавить дочерний классификатор';
$add_tree_hint = 'Добавить родительский классификатор';
$refresh_hint = 'Перезапустить дерево классификаторов';
$del_hint = 'Удалить выбранный классификатор БЕЗ вложений';
$del_root_hint = 'Удалить ветку полностью. ВНИМАНИЕ! Удаление распространяется 
                  на вложенные классификаторы. В том числе будут удалены таблицы в базе данных.';
$del_multi_nodes = 'Удалить классификатор С вложениями';

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
    font-size: 20px;
  }
  input {
    color: black;
  }
</style>

<div class="admin-classifier-pannel">

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

  <div class="col-lg-4 col-md-4">
    <div class="">
      <div style="position: relative">
        <div class="container-fuid" style="float:left; width: 100%">
          <input class="form-control form-control-sm" autocomplete="off" name="search" placeholder="Поиск...">
        </div>
        <div style="padding-top: 8px; right: 10px; position: absolute">
          <a href="" id="btnResetSearch"><i class="fa fa-times-circle" aria-hidden="true"
                                            style="font-size:20px; color: #9d9d9d"></i></a>
        </div>
      </div>
    </div>

    <div class="row" style="padding: 0 15px">
      <div style="border-radius:2px;padding-top:40px">
        <div id="fancyree_w0" class="ui-draggable-handle"></div>
      </div>
    </div>

  </div>


  <div class="col-lg-8 col-md-8 about">
    <div class="about-info"></div>
    <form action="create-table" method="post" class="input-add">
      <div class="about-header" style="font-size:18px"></div>
      <div class="about-main"></div>
      <div class="about-footer"></div>
    </form>
  </div>

</div>


<script>
  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

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
    $('.add-category').click(function (event) {
      var tree = $(".ui-draggable-handle").fancytree("getTree");
      $.ajax({
        url: "/admin/classifier/create-root",
        data: {title: 'Классификатор'}
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
      $(".del-node").hide();
      $(".del-root").hide();
      $(".del-multi-nodes").hide();
    })
  });

  $(document).ready(function () {
    $('.del-node').click(function (event) {
      if (confirm('Вы уверены, что хотите удалить выбранный классификатор?')) {
        event.preventDefault();
        var csrf = $('meta[name=csrf-token]').attr("content");
        var node = $(".ui-draggable-handle").fancytree("getActiveNode");
        $.ajax({
          url: "/admin/classifier/delete",
          type: "post",
          data: {id: node.data.id, _csrf: csrf}
        })
          .done(function () {
            node.remove();
            restoreInputs(false, false);
            $('.about-info').html('');
            $('.del-node').hide();
          })
          .fail(function () {
            alert("Что-то пошло не так. Перезагрузите форму с помошью клавиши.");
          });
      }
    });
    $('.del-root').click(function (event) {
      var csrf = $('meta[name=csrf-token]').attr("content");
      if (confirm('Вы уверены, что хотите удалить выбранный классификатор вместе с вложениями?')) {
        event.preventDefault();
        var node = $(".ui-draggable-handle").fancytree("getActiveNode");
        if (!node) {
          alert('Выберите родительский классификатор');
          return;
        }
        $.ajax({
          url: "/admin/classifier/delete-root",
          type: "post",
          data: {id: node.data.id, _csrf: csrf}
        })
          .done(function () {
            node.remove();
            restoreInputs(false, false);
            $('.about-info').html('');
            $('.del-root').hide();
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
          url: "/admin/classifier/delete-root",
          type: "post",
          data: {id: node.data.id, _csrf: csrf}
        })
          .done(function () {
            node.remove();
            restoreInputs(false, false);
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
      n = filterFunc.call(tree, function (node) {
        return new RegExp(match, "i").test(node.title);
      }, opts);
    } else {
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


  //========================== Extention ===============================================

  function saveClick(e) {
    var csrf = $('meta[name=csrf-token]').attr("content");
    e.preventDefault();
    var data_array = new Array();
    var nodeId = $('.node-id').val();
    if (!nodeId) {
      return false;
    }
    data_array.push(nodeId);
    $('.input-name-ex').each(function (i) {
      var item = {};
      item['order'] = i;
      item['id'] = $(this).data('id');
      item['label'] = $(this).val();
      item['typeName'] = $(this).attr('name');
      data_array.push(item);
    });
    var sendData = data_array.filter(function (item, i, arr) {
      return arr[i]['label'] != 0;                        // убираются незаполненные поля
    });
    $.ajax({
      url: "/admin/classifier/create-table",
      type: "post",
      data: {Data: sendData, _csrf: csrf},
      success: function (result) {
        $('.about-info').hide().html(goodAlert('Таблица базы данных успешно создана. ' +
          'Есть возможность пользоваться классификатором.')).fadeIn('slow');
        $('.about-footer').html(updateButton);
      },
      error: function () {
        $('.about-info').hide().html(badAlert('Таблица базы данных не создана. ' +
          'Перезапустите дерево классификаторов и повторите Ваши действия.')).fadeIn('slow');
      }
    });
  }

  function updateClick(e) {
    var csrf = $('meta[name=csrf-token]').attr("content");
    e.preventDefault();
    var oldData = new Array();
    var createData = new Array();
    var id = $('.node-id').val();
    $('.input-name-ex').each(function (i) {
      var item = {};
      item['order'] = i;
      item['id'] = $(this).data('id');
      item['label'] = $(this).val();
      item['typeName'] = $(this).attr('name');
      item['condition'] = $(this).data('condition');
      if (item['condition'] == 'create') {
        createData.push(item);
      } else {
        oldData.push(item);
      }
    });
    oldData = oldData.filter(function (item, i, arr) {
      if (arr[i]['condition'] != 'delete') {
        return arr[i]['label'] != 0;                       // убираются незаполненные поля
      }
      return true;
    });
    createData = createData.filter(function (item, i, arr) {
      return arr[i]['label'] != 0;                       // убираются незаполненные поля
    });
    $.ajax({
      url: "/admin/classifier/update-table",
      type: "post",
      data: {old: oldData, create: createData, _csrf: csrf, id: id},
      success: function (result) {
        $('.about-info').hide().html(goodAlert('Таблица базы данных успешно обновлена.')).fadeIn('slow');
      },
      error: function () {
        $('.about-info').hide().html(badAlert('Таблица базы данных не обновлена. ' +
          'Перезапустите дерево классификаторов и повторите Ваши действия.')).fadeIn('slow');
      }
    });
  }

  var headerInput = '' +
    '<label style="font-size: 14px">Название классификатора:</label>' +
    '<input class="form-control node-id" name="node-id" readonly style="display:none">' +
    '<input class="form-control clsf-name" disabled name="clsf-name"' +
    'title="Вводите имя в колонке дерева классификаторов. Клавиша F2 или двойной щелчок мыши"' +
    'data-toggle="tooltip" data-placement="top">';

  var headerSelect = '' +
    '<label style="font-size: 14px">Добавить:</label>' +
    '<p><select class="form-control" id="clsf-form-add" name="clsf-add"' +
    'title="Поля формы для добавления пользовательской информации." data-toggle="tooltip" data-placement="top"' +
    '>' +
    '<option selected disabled value="0">Выберите</option>' +
    '<option value="1">input</option>' +
    '<option value="2">floatInput</option>' +
    '<option value="3">dateInput</option>' +
    '<option value="4">select</option>' +
    '<option value="5">textarea</option>' +
    '<option value="6">radioButton</option>' +
    '<option value="7">checkBox</option>' +
    '<option value="8">fileInput</option>' +
    '</select></p>';

  var saveButton = '' +
    '<button type="submit" class="btn btn-primary save-btn insert-btn-cls" ' +
    'title="Создать новую таблицу БД для классификатора" data-toggle="tooltip" data-placement="top"' +
    'onclick="saveClick(event)">Создать' +
    '</button>';

  var updateButton = '' +
    '<button type="submit" class="btn btn-primary update-btn insert-btn-cls"' +
    'title="Обновить таблицу. В связи с возможным нарушением целостности данных изменить тип данных столбца невозможно." data-toggle="tooltip" data-placement="top"' +
    ' onclick="updateClick(event)">Обновить' +
    '</button>';

  var nameInput = '' +
    '<div class="add-wrap" style="padding: 20px 20px;border: dashed 1px lightgrey;margin-bottom: 10px;position: relative">' +
    '<a class="fa fa-angle-up move-up"' +
    'style="position: absolute;top:0;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить наверх"' +
    'data-toggle="tooltip" data-placement="left"></a>' +
    '<a class="fa fa-angle-down move-down" ' +
    'style="position: absolute;top:20px;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить вниз"' +
    'data-toggle="tooltip" data-placement="left"></a>' +
    '<div class="col-lg-11 col-md-11 col-xs-11" style="padding: 0px 0px">' +
    '<label style="font-size: 14px">Название поля ввода:</label>' +
    '<input class="form-control input-name-ex" id="input" data-order="" data-id="" data-condition="" name="input">' +
    '<p></p>' +
    '</div>' +
    '<div class="col-lg-1 col-md-1 col-xs-1" style="padding: 30px 0px 20px 0px;text-align: right;margin">' +
    '<i class="fa fa-minus-square delete-input" aria-hidden="true" style="color:red;cursor: pointer;font-size:24px;opacity: 0.7"></i>' +
    '</div>' +
    '<p></p>' +
    '<label style="font-size: 16px;color: #000;" class="label"></label>' +
    '<input class="form-control" disabled value="Текст (max: 255 симв.)">' +
    '</div>';
  var floatInput = '' +
    '<div class="add-wrap" style="padding: 20px 20px;border: dashed 1px lightgrey;margin-bottom: 10px;position: relative">' +
    '<a class="fa fa-angle-up move-up"' +
    'style="position: absolute;top:0;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить наверх"' +
    'data-toggle="tooltip" data-placement="left"></a>' +
    '<a class="fa fa-angle-down move-down" ' +
    'style="position: absolute;top:20px;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить вниз"' +
    'data-toggle="tooltip" data-placement="left"></a>' +
    '<div class="col-lg-11 col-md-11 col-xs-11" style="padding: 0px 0px">' +
    '<label style="font-size: 14px">Название поля ввода:</label>' +
    '<input class="form-control input-name-ex" id="input" data-order="" data-id="" data-condition="" name="floatinput">' +
    '<p></p>' +
    '</div>' +
    '<div class="col-lg-1 col-md-1 col-xs-1" style="padding: 30px 0px 20px 0px;text-align: right;margin">' +
    '<i class="fa fa-minus-square delete-input" aria-hidden="true" style="color:red;cursor: pointer;font-size:24px;opacity: 0.7"></i>' +
    '</div>' +
    '<p></p>' +
    '<label style="font-size: 16px;color: #000;" class="label"></label>' +
    '<input class="form-control" disabled value="Вещественные числа">' +
    '</div>';
  var dateInput = '' +
    '<div class="add-wrap" style="padding: 20px 20px;border: dashed 1px lightgrey;margin-bottom: 10px; position: relative">' +
    '<i class="fa fa-angle-up move-up"' +
    'style="position: absolute;top:0;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить наверх"' +
    'data-toggle="tooltip" data-placement="left"></i>' +
    '<i class="fa fa-angle-down move-down"' +
    'style="position: absolute;top:20px;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить вниз"' +
    'data-toggle="tooltip" data-placement="left"></i>' +
    '<div class="col-lg-11 col-md-11 col-xs-11" style="padding: 0px 0px">' +
    '<label style="font-size: 14px">Название поля ввода:</label>' +
    '<input class="form-control input-name-ex" id="dateinput" data-order="" data-id="" data-condition=""  name="dateinput" data-order="">' +
    '<p></p>' +
    '</div>' +
    '<div class="col-lg-1 col-md-1 col-xs-1" style="padding: 30px 0px 20px 0px;text-align: right;margin">' +
    '<i class="fa fa-minus-square delete-input" aria-hidden="true" style="color:red;cursor: pointer;font-size:24px;opacity: 0.7"></i>' +
    '</div>' +
    '<p></p>' +
    '<label style="font-size: 16px;color: #000;" class="label"></label>' +
    '<input class="form-control" disabled value="ДД.ММ.ГГГГ">' +
    '</div>';
  var textArea = '' +
    '<div class="add-wrap" style="padding: 20px 20px;border: dashed 1px lightgrey;margin-bottom: 10px; position: relative">' +
    '<i class="fa fa-angle-up move-up"' +
    'style="position: absolute;top:0px;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить наверх"' +
    'data-toggle="tooltip" data-placement="left"></i>' +
    '<i class="fa fa-angle-down move-down"' +
    'style="position: absolute;top:20px;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить вниз"' +
    'data-toggle="tooltip" data-placement="left"></i>' +
    '<div class="col-lg-11 col-md-11 col-xs-11" style="padding: 0px 0px">' +
    '<label style="font-size: 14px">Название текстового поля:</label>' +
    '<input class="form-control input-name-ex" id="textarea" data-order="" data-id="" data-condition=""  name="textarea">' +
    '<p></p>' +
    '</div>' +
    '<div class="col-lg-1 col-md-1 col-xs-1" style="padding: 30px 0px 20px 0px;text-align: right;margin">' +
    '<i class="fa fa-minus-square delete-input" aria-hidden="true" style="color:red;cursor: pointer;font-size:24px;opacity: 0.7"></i>' +
    '</div>' +
    '<p></p>' +
    '<label style="font-size: 16px;color: #000;" class="label"></label>' +
    '<textarea class="form-control" disabled placeholder="Текст (16777215 символов)" style="resize: none"></textarea>' +
    '</div>';
  var radioButton = '' +
    '<div class="add-wrap" style="padding: 20px 20px;border: dashed 1px lightgrey;margin-bottom: 10px; position: relative">' +
    '<i class="fa fa-angle-up move-up"' +
    'style="position: absolute;top:0;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить наверх"' +
    'data-toggle="tooltip" data-placement="left"></i>' +
    '<i class="fa fa-angle-down move-down"' +
    'style="position: absolute;top:20px;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить вниз"' +
    'data-toggle="tooltip" data-placement="left"></i>' +
    '<div class="col-lg-11 col-md-11 col-xs-11" style="padding: 0px 0px">' +
    '<label style="font-size: 14px">Название флажка:</label>' +
    '<input class="form-control input-name-ex" id="radio" data-order="" data-id="" data-condition="" name="radio">' +
    '<p></p>' +
    '</div>' +
    '<div style="padding: 30px 0px 20px 0px;text-align: right;margin">' +
    '<i class="fa fa-minus-square delete-input" aria-hidden="true" style="color:red;cursor: pointer;font-size:24px;opacity: 0.7"></i>' +
    '</div>' +
    '<p></p>' +
    '<input type="radio" disabled >' +
    '<label style="font-size: 16px;color: #000" class="label"></label>' +
    '</div>';

  var select = '' +
    '<div class="add-wrap" style="padding: 20px 20px;border: dashed 1px lightgrey;margin-bottom: 10px; position: relative">' +
    '<i class="fa fa-angle-up move-up"' +
    'style="position: absolute;top:0;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить наверх"' +
    'data-toggle="tooltip" data-placement="left"></i>' +
    '<i class="fa fa-angle-down move-down"' +
    'style="position: absolute;top:20px;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить вниз"' +
    'data-toggle="tooltip" data-placement="left"></i>' +
    '<div class="col-lg-11 col-md-11 col-xs-11" style="padding: 0px 0px">' +
    '<label style="font-size: 14px">Название флажка:</label>' +
    '<input class="form-control input-name-ex" id="radio" data-order="" data-id="" data-condition="" name="radio">' +
    '<p></p>' +
    '</div>' +
    '<div style="padding: 30px 0px 20px 0px;text-align: right;margin">' +
    '<i class="fa fa-minus-square delete-input" aria-hidden="true" style="color:red;cursor: pointer;font-size:24px;opacity: 0.7"></i>' +
    '</div>' +
    '<p></p>' +
    '<label style="font-size: 16px;color: #000" class="label"></label>' +
    '<span class="select-input-place"></span>'
  '</div>';

  var checkBox = '' +
    '<div class="add-wrap" style="padding: 20px 20px;border: dashed 1px lightgrey;margin-bottom: 10px; position: relative">' +
    '<i class="fa fa-angle-up move-up"' +
    'style="position: absolute;top:0;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить наверх"' +
    'data-toggle="tooltip" data-placement="left"></i>' +
    '<i class="fa fa-angle-down move-down"' +
    'style="position: absolute;top:20px;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить вниз"' +
    'data-toggle="tooltip" data-placement="left"></i>' +
    '<div class="col-lg-11 col-md-11 col-xs-11" style="padding: 0px 0px">' +
    '<label style="font-size: 14px">Название переключателя:</label>' +
    '<input class="form-control input-name-ex" id="checkbox" data-order="" data-id="" data-condition="" name="checkbox">' +
    '<p></p>' +
    '</div>' +
    '<div style="padding: 30px 0px 20px 0px;text-align: right;margin">' +
    '<i class="fa fa-minus-square delete-input" aria-hidden="true" style="color:red;cursor: pointer;font-size:24px;opacity: 0.7"></i>' +
    '</div>' +
    '<p></p>' +
    '<input type="checkbox" disabled>' +
    '<label style="font-size: 16px;color: #000" class="label"></label>' +
    '</div>';
  var fileInput = '' +
    '<div class="add-wrap" style="padding: 20px 20px;border: dashed 1px lightgrey;margin-bottom: 10px; position: relative">' +
    '<i class="fa fa-angle-up move-up"' +
    'style="position: absolute;top:0;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить наверх"' +
    'data-toggle="tooltip" data-placement="left"></i>' +
    '<i class="fa fa-angle-down move-down"' +
    'style="position: absolute;top:20px;right:10px;font-size:20px;cursor:pointer;" ' +
    'aria-hidden="true" title="Переместить вниз"' +
    'data-toggle="tooltip" data-placement="left"></i>' +
    '<div class="col-lg-11 col-md-11 col-xs-11" style="padding: 0px 0px">' +
    '<label style="font-size: 14px">Название поля для загрузка файлов:</label>' +
    '<input class="form-control input-name-ex" id="fileinput" data-order="" data-id="" data-condition="" name="fileinput">' +
    '<p></p>' +
    '</div>' +
    '<div style="padding: 30px 0px 20px 0px;text-align: right;margin">' +
    '<i class="fa fa-minus-square delete-input" aria-hidden="true" style="color:red;cursor: pointer;font-size:24px;opacity: 0.7"></i>' +
    '</div>' +
    '<p></p>' +
    '<label style="font-size: 16px;color: #000;" class="label"></label>' +
    // '<span class="file-input-place"></span>' +
    '<input class="form-control" disabled placeholder="Добавить файл..." style="max-width: 170px">' +
    '</div>';

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

  function insertHeader() {
    var header = window.headerInput + window.headerSelect;
    return header;
  }

  function restoreInputs(table, init = true) {
    var divHeader = $('.about-header');
    var divMain = $('.about-main');
    var divFooter = $('.about-footer');
    divHeader.html(insertHeader());
    if (table) {
      divMain.html('');
      var columns = new Array();
      columns = table.columns;
      columns.sort(function (a, b) {                      // сортировка отображения
        return a.order - b.order;                     // форм
      });
      columns.forEach(function (item, i, arr) {
        switch (item.typeName) {
          case 'input':
            divMain.append(window.nameInput);
            break;
          case 'floatinput':
            divMain.append(window.nameInput);
            break;
          case 'dateinput':
            divMain.append(window.dateInput);
            break;
          case 'textarea':
            divMain.append(window.textArea);
            break;
          case 'select':
            divMain.append(window.select);
            break;
          case 'radio':
            divMain.append(window.radioButton);
            break;
          case 'checkbox':
            divMain.append(window.checkBox);
            break;
          case 'fileinput':
            divMain.append(window.fileInput);
            break;
        }
        var c = divMain[0].childNodes[i];
        var input = $(c).find('.input-name-ex');
        $(input).val(item.label);
        input.data("id", item.id);
        input.data("condition", "old");
        var label = $(c).find('.label');
        $(label).text(item.label);
      });
      divFooter.html(updateButton);
      $('.alert').fadeOut(400);
    } else {
      if (init) {
        $('.about-info').hide().html(badAlert('Таблица базы данных не создана, пользоваться классификатором не удастся.')).fadeIn('slow');
      }
      divMain.html('');
      divFooter.html(saveButton);
    }
  }

  function handler() {
    $(".delete-input").on("click", function () {
      var div = $(this).closest('.add-wrap');
      var elem = div.find('.input-name-ex');
      if (elem.data("condition") != "create") {
        elem.data("condition", "delete");
        div.hide();
      } else {
        div.remove();
      }
    });
    $(".input-name-ex").on("change paste keyup", function () {
      var ch = $(this).val();
      var div = $(this).closest('.add-wrap');
      var label = div.children('.label');
      label.text(ch);
    });
    $(".move-up").click(function (e) {
      e.preventDefault();
      var currentDiv = $(this).parent();
      var prevDiv = currentDiv.prev();
      currentDiv.insertBefore(prevDiv);
      return false;
    });
    $(".move-down").click(function (e) {
      e.preventDefault();
      var currentDiv = $(this).parent();
      var nextDiv = currentDiv.next();
      nextDiv.insertBefore(currentDiv);
      return false;
    })
  }

  function addInput(div) {
    $('#clsf-form-add').change(function () {
      var val = $(this).val();
      if (val == 1) {
        div.append(window.nameInput);
      } else if (val == 2) {
        div.append(window.floatInput);
      } else if (val == 3) {
        div.append(window.dateInput);
      } else if (val == 4) {
        div.append(window.select);
        $.ajax({
          url: "/admin/user/test",
          type: "get",
          success: function (result) {
            $('.select-input-place').append(result);
          },
          error: function () {
            alert('Ошибка! Обратитесь к разработчику.');
          }
        });
      } else if (val == 5) {
        div.append(window.textArea);
      } else if (val == 6) {
        div.append(window.radioButton);
      } else if (val == 7) {
        div.append(window.checkBox);
      } else if (val == 8) {
        div.append(window.fileInput);
        /*
                        $.ajax({
                            url: "/admin/user/test2",
                            type: "get",
                            success: function (result) {
                                $('.file-input-place').append(result);
                            },
                            error: function () {
                                alert('Ошибка! Обратитесь к разработчику.');
                            }
                        });
        */

      }
      var lastIn = $(div).children().last();
      lastIn.find('.input-name-ex').data("id", Date.now());
      lastIn.find('.input-name-ex').data("condition", "create");
      $(this).val('0');
      handler();
      $('[data-toggle="tooltip"]').tooltip();

    });
  }

  // отображение и логика работа дерева
  jQuery(function ($) {
    var main_url = '/tehdoc/control/classifier/classifiers';
    var move_url = '/tehdoc/control/classifier/move';
    var create_url = '/tehdoc/control/classifier/create';
    var update_url = '/tehdoc/control/classifier/update';

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
        mode: 'dimm'                                        // Grayout unmatched nodes (pass "hide" to remove unmatched node instead)
      },
      edit: {
        inputCss: {
          minWidth: '10em'
        },
        triggerStart: ['clickActive', 'dbclick', 'f2', 'mac+enter', 'shift+click'],
        beforeEdit: function (event, data) {
          var node = data.node;
          if (node.key == -999) {
            return false;
          }
        },
        edit: function (event, data) {
          return true;
        },
        beforeClose: function (event, data) {
          data.save
        },
        save: function (event, data) {
          var node = data.node;
          var tree = $(".ui-draggable-handle").fancytree("getTree")
          if (data.isNew) {
            $.ajax({
              url: create_url,
              data: {
                parentId: node.parent.data.id,
                title: data.input.val()
              }
            }).done(function (result) {
              node.setTitle(result.acceptedTitle);
            }).fail(function (result) {
              node.setTitle(data.orgTitle);
            }).always(function () {
              tree.reload();
            });
          } else {
            console.log(data);
            $.ajax({
              url: update_url,
              data: {
                id: node.data.id,
                title: data.input.val()
              }
            }).done(function (result) {
              node.setTitle(result.acceptedTitle);
            }).fail(function (result) {
              node.setTitle(data.orgTitle);
            }).always(function () {
              tree.reload();
            });
          }
          return true;
        },
        close: function (event, data) {
          if (data.save) {
            // Since we started an async request, mark the node as preliminary
            $(data.node.span).addClass("pending");
            $(".clsf-name").val(data.node.title);
          }
        }
      },
      activate: function (node, data) {
        var node = data.node;
        var lvl = node.data.lvl;
        if (node.key == -999) {
          $(".add-subcategory").hide();
          return;
        } else {
          $(".add-subcategory").show();
        }
        if (lvl > 1) {                            // ограничение на вложенность
          $(".add-subcategory").hide();
        }
        if (lvl == 0) {
          $(".del-root").show();
          $(".del-node").hide();
          $(".del-multi-nodes").hide();
        } else {
          if (node.hasChildren()) {
            $(".del-multi-nodes").show();
          } else {
            $(".del-multi-nodes").hide();
          }
          $(".del-root").hide();
          $(".del-node").show();
        }
        var title = node.title;
        var id = node.data.id;
        var url = "/admin/classifier/classifier-display";
        $.get(url, {id: id}, function (data) {
          if (data) {
            var tableInfo = JSON.parse(data);
            $(".about-main").html(restoreInputs(tableInfo));
          } else {
            $(".about-main").html(restoreInputs(false));
          }
          addInput($(".about-main"));
          handler();
          $(".clsf-name").val(title);
          $(".node-id").val(id);
          $('[data-toggle="tooltip"]').tooltip();
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