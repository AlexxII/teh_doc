<?php

use yii\helpers\Html;

// Для администратора системы

$this->title = 'Журнал удаленных сеансов ВКС (предстоящие)';
$this->params['breadcrumbs'][] = ['label' => 'ВКС', 'url' => ['/vks']];
$this->params['breadcrumbs'][] = "Журнал";

$about = "Журнал сеансов видеосвязи, которые были удалены из таблицы предстоящих сеансов ВКС";
$dell_hint = 'Удалить выделенные сеансы окончательно';
$return_hint = 'Восстановить удаленные сеансы';

?>

<div class="vks-pannel">
  <div class="row" style="border-radius:2px;padding-left:15px;margin-top: -10px">
    <h3><?= Html::encode($this->title) ?>
      <sup class="h-title fa fa-question-circle-o" aria-hidden="true"
           data-toggle="tooltip" data-placement="right" title="<?php echo $about ?>"></sup>
    </h3>
  </div>
</div>

<style>
  td .fa {
    font-size: 25px;
  }
  #main-table tbody td {
    font-size: 12px;
  }
  .h-title {
    font-size: 18px;
    color: #1e6887;
  }
  .testt {
    position: relative;
  }
</style>

<div class="row">
  <div class="">
    <div class="container-fluid" style="margin-bottom: 20px">
      <a href="#" class="btn btn-sm btn-danger"
         data-toggle="tooltip" data-placement="top" title="<?= $dell_hint ?>" id="delete" disabled="true">Удалить</a>
      <a href="#" class="btn btn-sm btn-info"
         data-toggle="tooltip" data-placement="top" title="<?= $return_hint ?>" id="return"
         disabled="true">Восстановить</a>
    </div>
  </div>

  <div class="container-fluid">
    <?php

    echo '
        <table id="main-table" class="display no-wrap cell-border" style="width:100%">
          <thead>
            <tr>
              <th></th>
              <th >Дата</th>
              <th >Месяц</th>
              <th >Время</th>
              <th >Время</th>
              <th >Тип ВКС</th>
              <th >Студии</th>
              <th >Абонент</th>
              <th >Абонент</th>
              <th >Распоряжение</th>
              <th data-priority="3">Action</th>
              <th></th>
            </tr>
          </thead>
        </table>';
    ?>
  </div>

  <input class="csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" style="display: none">
</div>
<br>


<script>
  // $(document).ready(function () {
  //     $('[data-toggle="tooltip"]').tooltip();
  // });

  // ************************* Работа таблицы **************************************

  $(document).ready(function () {
    $.fn.dataTable.pipeline = function (opts) {
      var conf = $.extend({
        pages: 2,     // number of pages to cache
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
      "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        var today = new Date();
        var date = aData[1];
        var important = aData[10];
        var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
        var dt = new Date(date.replace(pattern, '$3-$2-$1'));
        if (important == 1) {
          $(nRow.cells[0]).css('color', 'red');
          $(nRow.cells[0]).css('font-weight', '600');
        }
        if (moment().isAfter(dt, 'day')) {
          $('td', nRow).css('background-color', '#faeeec');
        }
        else if (moment().isSame(dt, 'day')) {
          $('td', nRow).css('background-color', '#e4f0dc');
        }
      },
      "ajax": $.fn.dataTable.pipeline({
        url: '/vks/sessions/server-side?index=1',
        pages: 2 // number of pages to cache
      }),
      orderFixed: [2, 'asc'],
      rowGroup: {
        dataSrc: 2
      },
      "columnDefs": [
        {
          "orderable": false,
          "targets": -2,
          "data": null,
          "width": '70px',
          "defaultContent":
            "<a href='#' class='fa fa-info view' title='Подробности' style='padding-right: 5px'></a>"
        }, {
          "orderable": false,
          "className": 'select-checkbox',
          "targets": -1,
          "defaultContent": ""
        },
        {
          "targets": 0,
          "data": null,
          "visible": false
        }, {
          "targets": 2,
          "visible": false
        }, {
          "targets": 4,
          "visible": false
        },
        {
          "targets": 3,
          "render": function (data, type, row) {
            return row[3] + ' /т' + "<br> " + row[4] + ' /р';
          }
        },
        {
          "targets": 7,
          "render": function (data, type, row) {
            return row[7] + "<br> " + row[8];
          }
        }, {
          "targets": 8,
          "visible": false
        }
      ],
      select: {
        style: 'os',
        selector: 'td:last-child'
      },
      language: {
        url: "/lib/ru.json"
      }
    });

    $('#main-table tbody').on('click', '.view', function (e) {
      e.preventDefault();
      var data = table.row($(this).parents('tr')).data();
      var href = "/vks/admin/sessions/view-up-session?id=" + data[0];
      window.open(href);
    });
  });

  // Работа таблицы -> событие выделения и снятия выделения

  $(document).ready(function () {
    var table = $('#main-table').DataTable();
    table.on('select', function (e, dt, type, indexes) {
      if (type === 'row') {
        $('#return').removeAttr('disabled');
        $('#delete').removeAttr('disabled');
      }
    });
    table.on('deselect', function (e, dt, type, indexes) {
      if (type === 'row') {
        if (table.rows({selected: true}).count() > 0) return;
        $('#return').attr('disabled', true);
        $('#delete').attr('disabled', true);
      }
    });
  });


  //********************** Удаление и восстановление записей ***********************************

  $(document).ready(function () {
    $('#delete').click(function (event) {
      var url = "/vks/admin/sessions/delete-completely";
      event.preventDefault();
      if ($(this).attr('disabled')) {
        return;
      }
      jc = $.confirm({
        icon: 'fa fa-question',
        title: 'Вы уверены?',
        content: 'Вы действительно хотите удалить выделенное безвозвратно?',
        type: 'red',
        closeIcon: false,
        autoClose: 'cancel|9000',
        buttons: {
          ok: {
            btnClass: 'btn-danger',
            action: function () {
              jc.close();
              if (remoteProcess(url)) {
                $('#return').attr('disabled', true);
                $('#delete').attr('disabled', true);
              }
            }
          },
          cancel: {
            action: function () {
              return;
            }
          }
        }
      })
    });

    $('#return').click(function (event) {
      var url = "/vks/admin/sessions/restore";
      event.preventDefault();
      if ($(this).attr('disabled')) {
        return;
      }
      jc = $.confirm({
        icon: 'fa fa-question',
        title: 'Вы уверены?',
        content: 'Восстановить удаленные записи?',
        type: 'blue',
        closeIcon: false,
        autoClose: 'cancel|9000',
        buttons: {
          ok: {
            btnClass: 'btn-info',
            action: function () {
              jc.close();
              remoteProcess(url)
            }
          },
          cancel: {
            action: function () {
              return;
            }
          }
        }
      })
    });

    function remoteProcess(url) {
      var csrf = $('meta[name=csrf-token]').attr("content");
      var table = $('#main-table').DataTable();
      var data = table.rows({selected: true}).data();
      var ar = [];
      var count = data.length;
      for (var i = 0; i < count; i++) {
        ar[i] = data[i][0];
      }
      jc = $.confirm({
        icon: 'fa fa-cog fa-spin',
        title: 'Подождите!',
        content: 'Ваш запрос выполняется!',
        buttons: false,
        closeIcon: false,
        confirmButtonClass: 'hide'
      });
      $.ajax({
        url: url,
        method: 'post',
        dataType: "JSON",
        data: {jsonData: ar, _csrf: csrf},
      }).done(function (response) {
        if (response != false) {
          jc.close();
          jc = $.confirm({
            icon: 'fa fa-thumbs-up',
            title: 'Успех!',
            content: 'Ваш запрос выполнен.',
            type: 'green',
            buttons: false,
            closeIcon: false,
            autoClose: 'ok|8000',
            confirmButtonClass: 'hide',
            buttons: {
              ok: {
                btnClass: 'btn-success',
                action: function () {
                  $("#main-table").DataTable().clearPipeline().draw();
                  $('#return').attr('disabled', true);
                  $('#delete').attr('disabled', true);
                }
              }
            }
          });
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

    $('[data-toggle="tooltip"]').tooltip();

  });

</script>