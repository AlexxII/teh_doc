<?php

use yii\helpers\Html;
use app\assets\FancytreeAsset;
use app\assets\AirDatepickerAsset;

FancytreeAsset::register($this);
AirDatepickerAsset::register($this);

$this->title = 'Контроль ТО';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = ['label' => 'ТО', 'url' => ['/tehdoc/to']];
$this->params['breadcrumbs'][] = $this->title;

$about = "Контроль проведения ТО";
$refresh_hint = 'Перезапустить форму';
$del_hint = 'Удалить';
$date_about = 'Выберите период';

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
  .ui-fancytree {
    overflow: auto;
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

  <h3><?= Html::encode('Контроль ТО') ?>
    <sup class="h-title fa fa-question-circle-o" aria-hidden="true"
         data-toggle="tooltip" data-placement="right" title="<?php echo $about ?>"></sup>
  </h3>
</div>
<div class="row">
  <div class="col-lg-5 col-md-5" style="padding-bottom: 10px">
    <div class="container-fluid row" style="margin-bottom: 10px;position: relative">
      <?= Html::a('<i class="fa fa-refresh" aria-hidden="true"></i>', ['#'], ['class' => 'btn btn-success btn-sm refresh',
        'style' => ['margin-top' => '5px'],
        'title' => $refresh_hint,
        'data-toggle' => 'tooltip',
        'data-placement' => 'top'
      ]) ?>
      <div style="position: absolute;top:0px;right:15px;width:180px">
        <input class="form-control input-sm" id="dates" style="margin-top:5px;po" type="text" data-range="true"
               data-multiple-dates-separator=" - " placeholder="Выберите период"/>
      </div>
    </div>

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


  <div class="col-lg-7 col-md-7">
    <div id="about-info"></div>
  </div>

</div>


<script>
  $(document).ready(function () {

    $('#dates').datepicker({
      clearButton: true,
      onHide: function (dp, animationCompleted) {
        if (animationCompleted) {
          var range = $('#vks-dates').val();
          var stDate = range.substring(6, 10) + '-' + range.substring(3, 5) + '-' + range.substring(0, 2);
          var eDate = range.substring(19, 24) + '-' + range.substring(16, 18) + '-' + range.substring(13, 15);
          $("#main-table").DataTable().clearPipeline().draw();
        }
      }
    });

    $('[data-toggle="tooltip"]').tooltip();

    $('.refresh').click(function (event) {
      event.preventDefault();
      var datepicker = $('#dates').datepicker().data('datepicker');
      datepicker.clear();
      var tree = $(".ui-draggable-handle").fancytree("getTree");
      tree.reload();
      $('.about-info').html('')
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


  function loadShowData(id) {
    var url = '/tehdoc/to/to-audit/get-days';
    var csrf = $('meta[name=csrf-token]').attr("content");
    var uri = window.location.href;
    jc = $.confirm({
      icon: 'fa fa-cog fa-spin',
      title: 'Подождите!',
      content: 'Выполняется запрос к серверу и формируются данные!',
      buttons: false,
      closeIcon: false,
      confirmButtonClass: 'hide'
    });
    $.ajax({
      url: url,
      method: 'post',
      dataType: "JSON",
      data: {
        auditorId: id,
        _csrf: csrf
      }
    }).done(function (response) {
      if (response != false) {
        // var result = $.parseJSON(response);
        // var result = JSON.parse(response);
        var result = responses;
        result.forEach(function (item, i, ar) {
          $('#about-info').append(item);
        });
        jc.close();
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


  // отображение и логика работа дерева
  jQuery(function ($) {
    var main_url = '/tehdoc/to/to-audit/auditors';

    $("#fancyree_w0").fancytree({
      source: {
        url: main_url,
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
        var node = data.node;
        var id = node.data.id;
        var lt = loadShowData(id);
      },
      renderNode: function (node, data) {

      }
    });
  })


</script>