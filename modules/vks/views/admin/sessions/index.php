<?php

use yii\helpers\Html;

// Для администратора системы

?>

<div class="row">
  <div class="container-fluid" style="position: relative">
    <div id="delete" style="position: absolute; top: 70px; right:-60px;display: none">
      <a id="del-session-ex" class="fab-button" title="Удалить окончательно"
         style="cursor: pointer; background-color: red">
        <svg width="50" height="50" viewBox="0 0 24 24">
          <path d="M15 4V3H9v1H4v2h1v13c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6h1V4h-5zm2 15H7V6h10v13z"></path>
          <path d="M9 8h2v9H9zm4 0h2v9h-2z"></path>
        </svg>
      </a>
    </div>
    <div id="restore" style="position: absolute; top: 135px; right:-60px;display: none">
      <a id="restore-ex" class="fab-button" title="Восстановить"
         style="cursor: pointer; background-color: #5bc0de">
        <svg viewBox="0 0 24 24" focusable="false" width="50" height="50">
          <path d="M14.1 8H7.83l2.59-2.59L9 4 4 9l5 5 1.41-1.41L7.83 10h6.27c2.15 0 3.9 1.57 3.9 3.5S16.25 17 14.1 17H7v2h7.1c3.25 0 5.9-2.47 5.9-5.5S17.35 8 14.1 8z">
          </path>
        </svg>
      </a>
    </div>
    <table id="main-table" class="display no-wrap cell-border" style="width:100%">
      <thead>
      <tr>
        <th></th>
        <th>Дата</th>
        <th>Месяц</th>
        <th>Время</th>
        <th>Время</th>
        <th>Тип ВКС</th>
        <th>Студии</th>
        <th>Абонент</th>
        <th>Абонент</th>
        <th>Распоряжение</th>
        <th data-priority="3">Action</th>
        <th></th>
      </tr>
      </thead>
    </table>
  </div>

  <input class="csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" style="display: none">
</div>
<br>


<script>
  var upMark = '  <div title="Предстоящий сеанс" class="up-mark">\n' +
    '    <svg width="15" height="15" viewBox="0 0 24 24">\n' +
    '      <path fill="none" d="M0 0h24v24H0V0z"></path>\n' +
    '      <path d="M13 3c-4.97 0-9 4.03-9 9H1l4 3.99L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 ' +
    '0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.25 ' +
    '2.52.77-1.28-3.52-2.09V8z"></path>\n' +
    '    </svg>\n' +
    '  </div>\n';

  // ************************* Работа таблицы **************************************

  var adding, deletedTable;

  $(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

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

    deletedTable = $('#main-table').DataTable({
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
        url: '/vks/admin/sessions/server-side',
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
          "width": '40px',
          "sClass": "align-center",
          "defaultContent":
            "<a href='#' class='fa fa-info' id='view' title='Подробности' style='padding-right: 5px'></a>"
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
          "targets": 1,
          "render": function (data, type, row) {
            if (row[15] == 1) {
              return '<div class="inline-wrap"> ' + '<div>' + row[1] + '</div>' + ' ' + upMark + '</div>';
            } else {
              return row[1];
            }
          }
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
            if (row[3] == '') {
              return '<strong>' + row[4] + '</strong>' + ' / <strong>Р</strong>';
            } else if (row[4] == '') {
              return '<strong>' + row[3] + '</strong>' + ' / <strong>Т</strong>';
            } else {
              return '<strong>' + row[3] + '</strong>' +
                ' / <strong>Т</strong>' + "<br> " +
                '<strong>' + row[4] + '</strong>' + ' / <strong>Р</strong>';
            }
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

    $('#main-table tbody').on('click', '#view', function (e) {
      e.preventDefault();
      var data = deletedTable.row($(this).parents('tr')).data();
      var url = "/vks/admin/sessions/view-session?id=" + data[0];
      c = $.confirm({
        content: function () {
          var self = this;
          return $.ajax({
            url: url,
            method: 'get'
          }).fail(function () {
            self.setContentAppend('<div>Что-то пошло не так!</div>');
          });
        },
        contentLoaded: function (data, status, xhr) {
          this.setContentAppend('<div>' + data + '</div>');
        },
        type: 'blue',
        columnClass: 'xlarge',
        title: 'Подробности',
        buttons: {
          cancel: {
            text: 'НАЗАД'
          }
        }
      });
    });

    // Работа таблицы -> событие выделения и снятия выделения

    deletedTable.on('select', function (e, dt, type, indexes) {
      if (type === 'row') {
        $('#restore').show();
        $('#delete').show();
      }
    });
    deletedTable.on('deselect', function (e, dt, type, indexes) {
      if (type === 'row') {
        if (deletedTable.rows({selected: true}).count() > 0) return;
        $('#restore').hide();
        $('#delete').hide();
      }
    });

    // Работа таблицы -> перерисовка или изменение размера страницы

    deletedTable.on('length.dt', function (e, settings, len) {
      $('#delete').hide();
      $('#restore').hide();
    });

    deletedTable.on('draw.dt', function (e, settings, len) {
      $('#delete').hide();
      $('#restore').hide();
    });

    //********************** Удаление и восстановление записей ***********************************

    $('#delete').click(function (event) {
      event.preventDefault();
      var csrf = $('meta[name=csrf-token]').attr("content");
      var url = "/vks/admin/sessions/delete-completely";
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
              deleteRestoreProcess(url, deletedTable, csrf);
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

    $('#restore').click(function (event) {
      event.preventDefault();
      var csrf = $('meta[name=csrf-token]').attr("content");
      var url = "/vks/admin/sessions/restore";
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
              deleteRestoreProcess(url, deletedTable, csrf)
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
  });

</script>