<?php

use yii\helpers\Html;
use app\assets\FancytreeAsset;

FancytreeAsset::register($this);

$this->title = 'Оборудование по местам размещения';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = ['label' => 'Перечень оборудования', 'url' => ['/tehdoc/equipment/tools']];
$this->params['breadcrumbs'][] = $this->title;

$about = "Панель отображения оборудования по местам размещения. При сбое, перезапустите форму, воспользовавшись соответствующей клавишей.";
$refresh_hint = 'Перезапустить форму';
$dell_hint = 'Удалить выделенное оборудование из ОСНОВНОЙ таблицы. БУДЬТЕ ВНИМАТЕЛЬНЫ, данные будут удалены безвозвратно.';
$send_hint = 'Передать выделенные строки в подробную версию таблицы';
$classif_hint = 'Присвоить выделенному оборудованию пользовательский классификатор';


?>

<style>
  .h-title {
    font-size: 18px;
    color: #1e6887;
  }
  li {
    word-wrap: break-word
  }
  .fa {
    font-size: 15px;
  }
  ul.fancytree-container {
    font-size: 12px;
  }
  input {
    color: black;
  }
  #main-table {
    font-size: 12px;
  }
  td .fa {
    font-size: 22px;
  }
  .about-padding-zero {
    padding-top: 10px;
  }
  .about-padding {
    padding-top: 0px;
  }
  .show-menu-button {
    position: absolute;
    background-color: #f5f7f8;
    top: 0px;
    left: -20px;
    width: 15px;
    height: 100%;
    cursor: pointer;
    text-align: center;
    padding-top: 25px;
    border-radius: 1px;
  }

</style>

<div class="eq-placement-pannel">
  <h3><?= Html::encode($this->title) ?>
    <sup class="h-title fa fa-question-circle-o" aria-hidden="true"
         data-toggle="tooltip" data-placement="right" title="<?php echo $about ?>"></sup>
  </h3>
</div>

<div class="row">
  <div class="col-lg-4 col-md-4 fancy-tree" style="padding-bottom: 5px">
    <div class="row" style="margin-bottom: 10px;padding-left: 15px">
      <?= Html::a('<i class="fa fa-refresh" aria-hidden="true"></i>', ['#'], ['class' => 'btn btn-success btn-sm refresh',
        'style' => ['margin-top' => '5px'],
        'title' => $refresh_hint,
        'data-toggle' => 'tooltip',
        'data-placement' => 'top'
      ]) ?>
    </div>

    <div style="position: relative">
      <div class="hideMenu-button hidden-sm hidden-xs" style="position: absolute;top: 5px;right: -20px">
        <a href="#" class="fa fa-reply-all" data-placement="top" data-toggle="tooltip" title="Свернуть"
           aria-hidden="true"></a>
      </div>

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

  <div class="col-lg-8 col-md-8 about about-padding" style="position: relative;">
    <div class="control-buttons-wrap" style="position: absolute;top: 0px;width: 300px">
      <?= Html::a('Удалить',
        [''], [
          'class' => 'btn btn-danger btn-sm hiddendel',
          'style' => ['margin-top' => '5px', 'display' => 'none'],
          'data-toggle' => "tooltip",
          'data-placement' => "top",
          'title' => $dell_hint,
        ]) ?>
      <?= Html::a('Передать->',
        [''], [
          'class' => 'btn btn-primary btn-sm sendbtn',
          'style' => ['margin-top' => '5px', 'display' => 'none'],
          'data-toggle' => "tooltip",
          'data-placement' => "top",
          'title' => $send_hint,
        ]) ?>
      <?= Html::a('Классиф-тор',
        [''], [
          'class' => 'btn btn-info btn-sm classif',
          'style' => ['margin-top' => '5px', 'display' => 'none'],
          'data-toggle' => "tooltip",
          'data-placement' => "top",
          'title' => $classif_hint,
        ]) ?>

    </div>

    <input class="root" style="display: none">
    <input class="lft" style="display: none">
    <input class="rgt" style="display: none">
    <div class="table-wrapper" style="min-height:40px">
    </div>
    <div class="about-header" style="font-size:18px"></div>
    <table id="main-table" class="display no-wrap cell-border" style="width:100%">
      <thead>
      <tr>
        <th></th>
        <th data-priority="1">Наименование</th>
        <th data-priority="5">Производитель/Модель</th>
        <th>Модель</th>
        <th data-priority="6">s/n</th>
        <th>Дата производства</th>
        <th data-priority="4" title="Количество">Кол.</th>
        <th data-priority="2">Action</th>
        <th data-priority="3"></th>
      </tr>
      </thead>
    </table>
  </div>
</div>


<script>
  // Глобальные переменные

  var nodeid;
  var treeId;

  //************************ Работа над стилем ****************************

  var showMenuBtn =
    '<div class="show-menu-button" data-placement="top" data-toggle="tooltip" title="Развернуть" onclick="onClick()">' +
    '<i class="fa fa-chevron-right" aria-hidden="true"></i>' +
    '</div>';

  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('.hideMenu-button').click(function (e) {
      var indexes;
      e.preventDefault();
      $('.fancy-tree').animate({
          width: "0%"
        },
        {
          duration: 1000,
          start: indexes = rememberSelectedRows(),
          complete: function () {
            $('#main-table_wrapper').css('margin-left', '20px');
            $('.about').css('width', '');
            $('.about').removeClass('col-lg-9 col-md-9').addClass('col-lg-12 col-md-12');
            redrawTable();
            restoreSelectedRows(indexes);
            $('.fancy-tree').hide();
            $('[data-toggle="tooltip"]').tooltip();
            if ($('.show-menu-button').length === 0) {
              $('#main-table_wrapper').append(showMenuBtn);
            }
            $('.show-menu-button').show();
          },
          step: function (now, fx) {
            if (now <= 25) {
              $('.about').removeClass('col-lg-8 col-md-8').addClass('col-lg-9 col-md-9');
            }
            if (now <= 11 && now >= 5) {
              $('.fancy-tree').hide();
              $('#main-table_wrapper').css('position', 'relative');
              $('[data-toggle="tooltip"]').tooltip();
              if ($('.show-menu-button').length === 0) {
                $('#main-table_wrapper').append(showMenuBtn);
              }
              $('.show-menu-button').show();
            }
          }
        }
      );
    });
  });

  function rememberSelectedRows() {
    var table = $('#main-table').DataTable();
    var indexes = table.rows({selected: true}).indexes();
    return indexes;
  }

  $('#main-table').on('length.dt', function (e, settings, len) {
    $('.hiddendel').hide();
    $('.classif').hide();
    $('.classifier-add').fadeOut('slow');
  });

  function restoreSelectedRows(indexes) {
    var table = $('#main-table').DataTable();
    var count = indexes.count();
    for (var i = 0; i < count; i++) {
      table.rows(indexes[i]).select();
    }
  }

  function redrawTable() {
    var table = $('#main-table').DataTable();
    table.draw();
    return true;
  }

  function onClick() {
    var width = '33%';
    var indexes;
    if ($(document).width() < 600) {
      width = '100%';
    }
    $('.show-menu-button').hide();
    $('.fancy-tree').animate({
        width: width
      },
      {
        duration: 1000,
        start: indexes = rememberSelectedRows(),
        complete: function () {
          $('.about').css('width', '');
          $('#main-table_wrapper').css('margin-left', '0px');
          $('#main-table_wrapper').css('position', 'inherit');
          redrawTable();
          restoreSelectedRows(indexes);
          $('[data-toggle="tooltip"]').tooltip();
          $('.fancy-tree').css('width', '');
        },
        step: function (now, fx) {
          if (now > 5 && now < 14) {
            $('.fancy-tree').show();
            $('.about').removeClass('col-lg-12 col-md-12').addClass('col-lg-10 col-md-10');
          } else if (now > 16) {
            $('.about').removeClass('col-lg-10 col-md-10').addClass('col-lg-8 col-md-8');
          }
        }
      }
    );
  }

  //************************* Управление деревом ***************************************

  window.treeId = "#fancyree_w0";

  $(document).ready(function () {
    $('.refresh').click(function (event) {
      event.preventDefault();
      var tree = $(window.treeId).fancytree("getTree");
      tree.reload();
      $(".about-header").text("");
      $(".about-main").html('');
      $(".del-node").hide();
      $(".del-multi-nodes").hide();
      $(".root").text('');
      $(".lft").text('');
      $(".rgt").text('');
      $('.hiddendel').hide();
      $('.classif').hide();
      $('.sendbtn').hide();
      $("#main-table").DataTable().clearPipeline().draw();
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
    var tree = $(window.treeId).fancytree("getTree");
    tree.clearFilter();
  }).attr("disabled", true);


  $(document).ready(function () {
    $("input[name=search]").keyup(function (e) {
      if ($(this).val() == '') {
        var tree = $(window.treeId).fancytree("getTree");
        tree.clearFilter();
      }
    })
  });

  // ************************* Работа таблицы **************************************

  $(document).ready(function () {
    $.fn.dataTable.pipeline = function (opts) {
      var conf = $.extend({
        pages: 5,     // number of pages to cache
        url: '',      // script url
        data: null,   // function or object with parameters to send to the server
                      // matching how `ajax.data` works in DataTables
        method: 'GET' // Ajax HTTP method
      }, opts);
      var cacheLower = -1;
      var cacheUpper = null;
      var cacheLastRequest = null;
      var cacheLastJson = null;
      return function (request, drawCallback, settings) {
        var ajax = false;
        var requestStart = request.start;
        var drawStart = request.start;
        var requestLength = request.length;
        var requestEnd = requestStart + requestLength;
        if (settings.clearCache) {
          ajax = true;
          settings.clearCache = false;
        }
        else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
          ajax = true;
        }
        else if (JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
          JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
          JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
        ) {
          ajax = true;
        }
        cacheLastRequest = $.extend(true, {}, request);
        if (ajax) {
          if (requestStart < cacheLower) {
            requestStart = requestStart - (requestLength * (conf.pages - 1));
            if (requestStart < 0) {
              requestStart = 0;
            }
          }
          cacheLower = requestStart;
          cacheUpper = requestStart + (requestLength * conf.pages);
          request.start = requestStart;
          request.length = requestLength * conf.pages;
          if (typeof conf.data === 'function') {
            var d = conf.data(request);
            if (d) {
              $.extend(request, d);
            }
          }
          else if ($.isPlainObject(conf.data)) {
            $.extend(request, conf.data);
          }
          settings.jqXHR = $.ajax({
            "type": conf.method,
            "url": conf.url,
            "data": request,
            "dataType": "json",
            "cache": false,
            "success": function (json) {
              cacheLastJson = $.extend(true, {}, json);
              if (cacheLower != drawStart) {
                json.data.splice(0, drawStart - cacheLower);
              }
              if (requestLength >= -1) {
                json.data.splice(requestLength, json.data.length);
              }
              drawCallback(json);
            }
          });
        }
        else {
          json = $.extend(true, {}, cacheLastJson);
          json.draw = request.draw; // Update the echo for each response
          json.data.splice(0, requestStart - cacheLower);
          json.data.splice(requestLength, json.data.length);
          drawCallback(json);
        }
      }
    };
    $.fn.dataTable.Api.register('clearPipeline()', function () {
      return this.iterator('table', function (settings) {
        settings.clearCache = true;
      });
    });
  });

  $(document).ready(function () {
    var table = $('#main-table').DataTable({
      "processing": true,
      "serverSide": true,
      "responsive": true,
      "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
      "ajax": $.fn.dataTable.pipeline({
        url: 'server-side',
        pages: 2, // number of pages to cache
        data: function () {
          var root = $(".root").text();
          var lft = $(".lft").text();
          var rgt = $(".rgt").text();
          return {
            'db_tbl': 'teh_placement_tbl',
            'identifier': 'place_id',
            'root': root,
            'lft': lft,
            'rgt': rgt
          }
        }
      }),
      "columnDefs": [{
        "targets": -2,
        "data": null,
        "defaultContent": "<a href='#' class='fa fa-edit edit' style='padding-right: 5px'></a>" +
          "<a href='#' class='fa fa-eye view'></a>",
        "orderable": false
      }, {
        "orderable": false,
        "className": 'select-checkbox',
        "targets": -1,
        "defaultContent": ""
      }, {
        "targets": 0,
        "data": null,
        "visible": false
      }, {
        "targets": 3,
        "data": null,
        "visible": false
      }, {
        "targets": 2,
        "render": function (data, type, row) {
          return row[2] + " " + row[3];
        }
      }],
      select: {
        style: 'os',
        selector: 'td:last-child'
      },
      language: {
        url: "/lib/ru.json"
      }
    });

    $('#main-table tbody').on('click', '.edit', function (e) {
      e.preventDefault();
      var data = table.row($(this).parents('tr')).data();
      var href = "/tehdoc/equipment/control-panel/" + data[0] + "/info/index";
      if (e.ctrlKey) {
        window.open(href);
      } else {
        location.href = href;
      }
    });
    $('#main-table tbody').on('click', '.view', function (e) {
      e.preventDefault();
      var data = table.row($(this).parents('tr')).data();
      var href = "/tehdoc/equipment/tool/" + data[0] + "/info/index";
      if (e.ctrlKey) {
        window.open(href);
      } else {
        location.href = href;
      }
    });
  });

  // Работа таблицы -> событие выделения и снятия выделения

  $(document).ready(function () {
    var table = $('#main-table').DataTable();
    table.on('select', function (e, dt, type) {
      if (type === 'row') {
        $('.hiddendel').show();
        $('.classif').show();
        $('.sendbtn').show();
      }
    });
    table.on('deselect', function (e, dt, type) {
      var i = table.rows({selected: true}).indexes();
      if (type === 'row' && i.count() == 0) {
        $('.hiddendel').hide();
        $('.classif').hide();
        $('.sendbtn').hide();
      }
    });
  });

  //********************** Удаление записей ***********************************

  $(document).ready(function () {
    $('.hiddendel').click(function (event) {
      event.preventDefault();
      var csrf = $('meta[name=csrf-token]').attr("content");
      var table = $('#main-table').DataTable();
      var data = table.rows({selected: true}).data();
      var ar = [];
      var count = data.length;
      for (var i = 0; i < count; i++) {
        ar[i] = data[i][0];
      }
      if (confirm('Вы действительно хотите удалить выделенное оборудование? Выделено ' + data.length + '!!!  ')) {
        $(".modal").modal("show");
        $.ajax({
          url: "/tehdoc/kernel/equipment/delete",
          type: "post",
          dataType: "JSON",
          data: {jsonData: ar, _csrf: csrf},
          success: function (result) {
            $("#main-table").DataTable().clearPipeline().draw();
            $(".modal").modal('hide');
            $('.hiddendel').hide();
            $('.sendbtn').hide();
          },
          error: function () {
            alert('Ошибка! Обратитесь к разработчику.');
            $(".modal").modal('hide');
          }
        });
      }
    })
  });

  //************************** Добавление классификатора **********************************

  $(document).ready(function () {
    $('.classif').click(function (event) {
      event.preventDefault();
      var csrf = $("meta[name=csrf-token]").attr("content");
      var table = $("#main-table").DataTable();
      var data = table.rows({selected: true}).data();
      var ar = [];
      var count = data.length;
      for (var i = 0; i < count; i++) {
        ar[i] = data[i][0];
      }
      $("#classifier-modal").modal("show");
      $('#classifier').off('change').on('change', function () {
        var val = $(this).val();
        console.log(val);
        if (val != '') {
          var el = $('#kv-tree-dropdown-container').find('.kv-selected');
          $.ajax({
            url: "/admin/classifier/extended-data-form?id=" + val,
            type: "GET",
            success: function (result) {
              $("#classifier-body").html(result);
              $("#assign-classifier-btn").removeAttr('disabled');
              $("#assign-classifier-btn").off('click').on("click", function (e) {
                e.preventDefault();
                var data = $('#form-classifier').serializeArray();
                var sendData = data.filter(function (item, i, arr) {
                  return arr[i]['value'] != 0;
                });
                $.ajax({
                  url: "/admin/classifier/assign-classifier",
                  type: "post",
                  data: {id: ar, _csrf: csrf, data: sendData},
                  success: function (result) {
                    $("#form-classifier")[0].reset();
                    $("#classifier-modal").modal("hide");

                  },
                  error: function () {
                    console.log("Ошибка cat_1! Обратитесь к разработчику.");
                    $("#form-classifier")[0].reset();
                    $("#classifier-modal").modal("hide");
                  }
                });
              })
            },
            error: function () {
              console.log("Ошибка cat_2! Обратитесь к разработчику.");
              $("#classifier-modal").modal("hide");
            }
          });
        } else {
          $("#classifier-body").html('');
          $("#assign-classifier-btn").attr("disabled", "disabled");
        }
      });
    })
  });

  jQuery(function ($) {
    var main_url = '/tehdoc/control/placement/placements';

    $("#fancyree_w0").fancytree({
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
        $(".hiddendel").hide();
        $(".classif").hide();
        $(".sendbtn").hide();
        var node = data.node;
        if (node.key == -999) {
          $(".add-subcategory").hide();
          return;
        } else {
          $(".add-subcategory").show();
        }
        var title = node.title;
        var id = node.data.id;
        window.nodeId = id;
        $(".root").text(node.data.root);
        $(".lft").text(node.data.lft);
        $(".rgt").text(node.data.rgt);
        $("#main-table").DataTable().clearPipeline().draw();
      },
      renderNode: function (node, data) {
      }
    });
  })


</script>