<?php

use yii\helpers\Html;
use app\assets\FancytreeAsset;
use app\assets\BootstrapDatepickerAsset;
use app\assets\FloatTheadAsset;

FancytreeAsset::register($this);
BootstrapDatepickerAsset::register($this);
FloatTheadAsset::register($this);

$about = "Панель формирования графика ТО на год.";
$refresh_hint = 'Перезапустить форму';
$ref_hint = 'К оборудованию в основном перечне';
$expand_hint = 'Раскрыть все';
$collapse_hint = 'Скрыть все';

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
  .ui-fancytree {
    overflow: auto;
  }
  td.alignRight {
    text-align: right;
  }
  td.alignCenter {
    text-align: center;
  }
  td input[type=input] {
    width: 40px;
  }
  #tree tr {
    border-top: 5px solid transparent;
    border-bottom: 5px solid transparent;
  }
  .save-it {
    color: #0b58a2;
    font-size: 18px;
    cursor: pointer;
  }
  .main {
    font-size: 24px;
  }
  table.fancytree-ext-table tbody tr.fancytree-active {
    background-color: #ecedf0;
  }
  form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background-color: #fff;
    opacity: 1;
  }

</style>

<div class="admin-category-pannel">
  <h3 style="float: left; padding-right: 15px;padding-bottom: 0px">План ТО на - </h3>
  <div style="float: left; padding-top: 18px; max-width: 200px">
    <div class="input-group date to-month-tooltip" data-toggle='tooltip' data-placement='top'>
      <input type="text" class="form-control" readonly id="to-year" title="Необходимо ввести год"
             style="font-size: 22px;color:#C50100;font-weight: 600" name="year"><span
              class="input-group-addon"><i
                class="fa fa-calendar" aria-hidden="true" style="font-size: 18px"></i></span>
    </div>
  </div>
</div>

<div class="row" style="clear: both">
  <div class="">
    <div class="container-fluid" style="margin-bottom: 10px">
      <button class="btn btn-success btn-sm refresh" title="<?= $refresh_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_equipment">
        <i class="fa fa-refresh" aria-hidden="true"></i>
      </button>
      <button class="btn btn-success btn-sm expand-all" title="<?= $refresh_hint ?>" data-toggle="tooltip"
              data-placement="top" data-container="body" data-tree="fancytree_to_equipment">
        <i class="fa fa-expand" aria-hidden="true"></i>
      </button>
      <?= Html::a('<i class="fa fa-level-up" aria-hidden="true"></i>', ['#'], ['class' => 'btn btn-info btn-sm',
        'style' => ['margin-top' => '5px', 'display' => 'none'],
        'title' => $ref_hint,
        'data-toggle' => 'tooltip',
        'data-placement' => 'top',
        'id' => 'tool-ref'
      ]) ?>

    </div>
  </div>
  <div class="col-lg-4 col-md-4" style="padding-bottom: 10px">
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
  </div>
</div>

<table id="tree">
  <colgroup>
    <col width="30px">
    <col width="50px">
    <col width="750px">
    <col width="70px">
    <col width="70px">
    <col width="70px">
    <col width="70px">
    <col width="70px">
    <col width="70px">
    <col width="70px">
    <col width="70px">
    <col width="70px">
    <col width="70px">
    <col width="70px">
    <col width="30px">
    <col width="30px">
    <col width="30px">
  </colgroup>
  <thead>
  <tr>
    <th></th>
    <th>№</th>
    <th>Оборудование</th>
    <th>Янв.</th>
    <th>Фев.</th>
    <th>Мар.</th>
    <th>Апр.</th>
    <th>Май</th>
    <th>Июн.</th>
    <th>Июл.</th>
    <th>Авг.</th>
    <th>Сен.</th>
    <th>Окт.</th>
    <th>Ноя.</th>
    <th>Дек.</th>
    <th></th>
    <th></th>
  </tr>
  </thead>
  <tbody>
  <!-- Define a row template for all invariant markup: -->
  <tr>
    <td class="alignCenter"></td>
    <td></td>
    <td></td>
    <td>
      <?= Html::dropDownList('name', 0, $list, ['class' => 'jan']) ?>
    </td>
    <td>
      <?= Html::dropDownList('name', 0, $list, ['class' => 'feb']) ?>
    </td>
    <td>
      <?= Html::dropDownList('name', 0, $list, ['class' => 'march']) ?>
    </td>
    <td>
      <?= Html::dropDownList('name', 0, $list, ['class' => 'apr']) ?>
    </td>
    <td>
      <?= Html::dropDownList('name', 0, $list, ['class' => 'may']) ?>
    </td>
    <td>
      <?= Html::dropDownList('name', 0, $list, ['class' => 'jun']) ?>
    </td>
    <td>
      <?= Html::dropDownList('name', 0, $list, ['class' => 'jul']) ?>
    </td>
    <td>
      <?= Html::dropDownList('name', 0, $list, ['class' => 'aug']) ?>
    </td>
    <td>
      <?= Html::dropDownList('name', 0, $list, ['class' => 'sep']) ?>
    </td>
    <td>
      <?= Html::dropDownList('name', 0, $list, ['class' => 'oct']) ?>
    </td>
    <td>
      <?= Html::dropDownList('name', 0, $list, ['class' => 'nov']) ?>
    </td>
    <td>
      <?= Html::dropDownList('name', 0, $list, ['class' => 'dec']) ?>
    </td>
    <td class="save" style="text-align: center">
    </td>
    <td class="status" style="text-align: center">
    </td>
  </tr>
  </tbody>
</table>

<script>


  var successCheck = '<i class="fa fa-check" id="consolidated-check" aria-hidden="true" style="color: #4eb305"></i>';
  var warningCheck = '<i class="fa fa-times" id="consolidated-check" aria-hidden="true" style="color: #cc0000"></i>';
  var waiting = '<i class="fa fa-cog fa-spin" aria-hidden="true"></i>';

  var typesArray = [];

  $(document).ready(function () {
    $("#tree").on("change", "select", function (e) {
      var val = $(this).val();
      var cl = $(this).attr('class');
      if (!cl) {
        return;
      }
      var ar = $('#tree').fancytree('getTree').getSelectedNodes();
      ar.forEach(function (item, i, arr) {
        var tr = item.tr;
        $(tr).find('.' + cl).each(function () {
          $(this).val(val);
        })
      });
    });

    $('.refresh').click(function (event) {
      event.preventDefault();
      var tree = $("#tree").fancytree("getTree");
      tree.reload();
      $('#to-year').val('').datepicker('update');
      $("#del-node").hide();
      $("#save-btn").prop('disabled', true);
      $('#tool-ref').hide();
    });

    $('.expand-all').click(function (event) {
      event.preventDefault();
      $("#tree").fancytree("getTree").expandAll();
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


    $('#tool-ref').click(function (event) {
      event.preventDefault();
      var node = $("#tree").fancytree("getActiveNode");
      var toolId = node.data.eq_id;
      var prefix = '/tehdoc/equipment/tool/';
      var href = prefix + toolId + '/info/index';
      if (event.ctrlKey) {
        window.open(href);
      } else {
        location.href = href;
      }
    });

    $('#to-year').datepicker({
      format: 'yyyy г.',
      autoclose: true,
      language: "ru",
      startView: "years",
      minViewMode: "years",
      clearBtn: true
    });

    $('#to-year').datepicker().on('changeDate', function (e) {
      var obj = $(this).data('datepicker');
      var year = $(this).data('datepicker').getFormattedDate('yyyy');
      var tree = $("#tree").fancytree("getTree");
      if (year) {
        monthProcess('/to/year-schedule/create-year-schedule', year, obj);
      }
      tree.reload();
    });

    function monthProcess(url, year, obj) {
      var csrf = $('meta[name=csrf-token]').attr("content");
      jc = $.confirm({
        icon: 'fa fa-cog fa-spin',
        title: 'Подождите!',
        content: 'Формируются необходимые данные!',
        buttons: false,
        type: 'blue',
        closeIcon: false,
        confirmButtonClass: 'hide'
      });
      $.ajax({
        url: url,
        type: "post",
        data: {year: year, _csrf: csrf}
      }).done(function (response) {
        var result = JSON.parse(response);
        if (result.status != false) {
          jc.close();
          typesArray = result.data;
          jc = $.confirm({
            icon: 'fa fa-thumbs-up',
            title: 'Успех!',
            content: 'Данные сформированы',
            type: 'green',
            buttons: false,
            closeIcon: false,
            autoClose: 'ok|8000',
            confirmButtonClass: 'hide',
            buttons: {
              ok: {
                btnClass: 'btn-success',
                action: function () {
                }
              }
            }
          });
        } else {
          jc.close();
          obj.clearDates();
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
        obj.clearDates();
        jc = $.confirm({
          icon: 'fa fa-exclamation-triangle',
          title: 'Неудача!',
          content: 'Запрос не выполнен. Что-то пошло не так.',
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
      var tree = $("#tree").fancytree("getTree");
      tree.clearFilter();
    }).attr("disabled", true);

    $("input[name=search]").keyup(function (e) {
      if ($(this).val() == '') {
        var tree = $("#tree").fancytree("getTree");
        tree.clearFilter();
      }
    });

    $("#tree").on("click", ".save-it", function (e) {
      var node = $.ui.fancytree.getNode(e);
      var $td = $(node.tr).find(">td");
      $td.eq(4).html(waiting);
      var yearString = $('#to-year').val();
      var year = yearString.match(/[0-9]*/i)[0];
      if (!year) {
        jc = $.confirm({
          icon: 'fa fa-exclamation-triangle',
          title: 'Неудача!',
          content: 'Необходимо выбрать год.',
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
        return;
      }
      var result = [];
      var children = node.children;
      children.forEach(function (item, i, ar) {
        if (item.isFolder()) {
          var children = item.children;
          children.forEach(function (item, i, ar) {
            var os = {};
            var temp = [];
            var $tdList = $(item.tr).find(">td");
            if ($tdList.length == 0) {
              return;
            } else {
              for (var c = 0; c < 12; c++) {
                var tVal = $($tdList.eq(3 + c)[0].children[0].selectedOptions).val();
                if (tVal !== undefined) {
                  temp.push(tVal);
                } else {
                  temp.push(null);
                }
              }
              var index = item.data.id;
              o['eqId'] = index;
              o['types'] = temp;
            }
          });
        } else {
          var $tdList = $(item.tr).find(">td");
          if ($tdList.length == 0) {
            return;
          } else {
            var o = {};
            var temp = [];
            for (var c = 0; c < 12; c++) {
              var tVal = $($tdList.eq(3 + c)[0].children[0].selectedOptions).val();
              if (tVal !== undefined) {
                temp.push(tVal);
              } else {
                temp.push(null);
              }
            }
            var index = item.data.id;
            o['eqId'] = index;
            o['types'] = temp;
          }
        }
        result.push(o);
      });

      var url = '/to/year-schedule/save-types';
      var csrf = $('meta[name=csrf-token]').attr("content");
      $.ajax({
        url: url,
        type: "post",
        data: {
          id: result,
          year: year,
          _csrf: csrf
        }
      }).done(function (response) {
        $td.eq(4).html(successCheck);
      }).fail(function (response) {
        $td.eq(4).html(warningCheck);
      });
    });

    $("#tree").fancytree({
      checkbox: true,
      quicksearch: true,        // Jump to nodes when pressing first character
      source: {url: '/to/control/schedule/to-equipment/all-tools'},
      extensions: ["table"],
      minExpandLevel: 2,
      selectMode: 3,
      table: {
        indentation: 20,
        nodeColumnIdx: 2,
        checkboxColumnIdx: 0
      },
      createNode: function (event, data) {
        var node = data.node,
          $tdList = $(node.tr).find(">td");
        if (node.data.lvl == 0) {
          children = node.children;
          var flag = false;
          children.forEach(function (item, i, children) {
            if (!item.folder) {
              flag = true;
              return;
            }
          });
          if (!flag) {
            $tdList.eq(2).prop("colspan", 13);
            $tdList.eq(3).html('');
            $tdList.eq(4).html('').nextAll().remove();
          } else {
            $tdList.eq(2).prop("colspan", 13);
            $tdList.eq(3).html(
              '<span class="fa fa-floppy-o save-it" data-name="' + node.data.name + '" aria-hidden="true"></span>');
            $tdList.eq(4).html('').nextAll().remove();
          }
        } else if (node.isFolder()) {
          $tdList.eq(2).prop("colspan", 13);
          $tdList.eq(3).html('');
          $tdList.eq(4).html('').nextAll().remove();
        }
      },
      expand: function (node, data) {
        if (!$('#to-year').val()) {
          return false;
        }
        var node = data.node,
          $tdList = $(node.tr).find(">td");
        $tdList.eq(3).html(
          '<span class="fa fa-floppy-o save-it" data-name="' + node.data.name + '" aria-hidden="true"></span>');
        $tdList.eq(4).html('');
      },
      collapse: function (node, data) {
        var node = data.node,
          $tdList = $(node.tr).find(">td");
        $tdList.eq(3).html('');
        $tdList.eq(4).html('');
        $('#tool-ref').hide();
      },
      activate: function (node, data) {
        var node = data.node;
        if (node.data.eq_id != 0) {
          $('#tool-ref').show();
        } else {
          $('#tool-ref').hide();
        }
      },
      click: function (event, data) {
      },
      renderColumns: function (event, data) {
        var node = data.node;
        $tdList = $(node.tr).find(">td");
        $tdList.eq(1).text(node.getIndexHier());
        if (!node.folder) {
          if (!$('#to-year').val()) {
            for (var i = 0; i < 12; i++) {
              $($tdList.eq(3 + i)[0].children[0]).val('')
            }
          } else {
            var tempAr = typesArray[node.key];
            for (var ii = 0; ii < 12; ii++) {
              $($tdList.eq(3 + ii)[0].children[0]).val(tempAr[ii]);
            }
          }
        }
      }
    });

  });

</script>


