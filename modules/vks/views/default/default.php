<?php

use yii\helpers\Html;

use app\assets\NotyAsset;
use app\assets\PdfmakeAsset;
use app\modules\vks\assets\VksAppAsset;
use app\assets\TableBaseAsset;
use app\assets\FancytreeAsset;
use app\assets\BootstrapDatepickerAsset;

VksAppAsset::register($this);
PdfmakeAsset::register($this);
NotyAsset::register($this);
TableBaseAsset::register($this);
FancytreeAsset::register($this);
BootstrapDatepickerAsset::register($this);

?>
<div class="row">
  <div class="container-fluid" style="position: relative">
    <div id="add-session-wrap" style="position: absolute; top: 10px; left:-60px" class="hidden-xs hidden-sm">
      <a id="add-session" class="fab-button" title="Добавить предстоящий сеанс" style="cursor: pointer">
        <div class="plus"></div>
      </a>
    </div>

    <div id="add-session-wrap-ex" style="position: absolute; top: 10px; right:-60px" class="hidden-xs hidden-sm">
      <a id="add-session-ex" class="fab-button" title="Добавить прошедший сеанс"
         style="cursor: pointer; background-color: #4CAF50">
        <i class='check'></i>
      </a>
    </div>
    <div id="delete-wrap" style="position: absolute; top: 70px; right:-60px;display: none">
      <a id="del-session-ex" class="fab-button" title="Удалить выделенный(е) сеанс(ы)"
         style="cursor: pointer; background-color: red">
        <svg width="50" height="50" viewBox="0 0 24 24">
          <path d="M15 4V3H9v1H4v2h1v13c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6h1V4h-5zm2 15H7V6h10v13z"></path>
          <path d="M9 8h2v9H9zm4 0h2v9h-2z"></path>
        </svg>
      </a>
    </div>
    <div class="get-pdf" style="position: absolute; top: 130px; right:-60px;display: none"
         data-pdf-header="Предстоящие сеансы видеосвязи" data-table="up-sessions-table">
      <a class="fab-button" title="Передать в PDF" style="cursor: pointer; background-color: blue" >
        <svg width="50" height="50" viewBox="0 0 24 24" style="padding-left: 10px">
          <path d="M11.363 2c4.155 0 2.637 6 2.637 6s6-1.65 6 2.457v11.543h-16v-20h7.363zm.826-2h-10.189v24h20v-14.386c0-2.391-6.648-9.614-9.811-9.614zm4.811
           13h-2.628v3.686h.907v-1.472h1.49v-.732h-1.49v-.698h1.721v-.784zm-4.9 0h-1.599v3.686h1.599c.537 0 .961-.181
            1.262-.535.555-.658.587-2.034-.062-2.692-.298-.3-.712-.459-1.2-.459zm-.692.783h.496c.473
            0 .802.173.915.644.064.267.077.679-.021.948-.128.351-.381.528-.754.528h-.637v-2.12zm-2.74-.783h-1.668v3.686h.907v-1.277h.761c.619
            0 1.064-.277 1.224-.763.095-.291.095-.597 0-.885-.16-.484-.606-.761-1.224-.761zm-.761.732h.546c.235
            0 .467.028.576.228.067.123.067.366 0 .489-.109.199-.341.227-.576.227h-.546v-.944z"/>
        </svg>
      </a>
    </div>

    <table id="up-sessions-table" class="display no-wrap cell-border" style="width:100%">
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
</div>

<script>

  var table;

  $(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    initLeftMenu('/vks/menu/left-side');
    initSmallMenu('/vks/menu/small-menu');
    initAppConfig('/vks/menu/app-config');
    initRightCustomData('/vks/menu/right-side-data');

    // ************************* Работа таблицы **************************************

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

    table = $('#up-sessions-table').DataTable({
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
        url: '/vks/sessions/server-side?index=0',
        pages: 2, // number of pages to cache
        data: function () {
          var stDate = $("#start-date").val();
          var eDate = $("#end-date").val();
          var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
          if (stDate == undefined || eDate == undefined) {
            return {
              'stDate': '1970-01-01',
              'eDate': '2099-12-31'
            }
          }
          stDate = stDate.replace(pattern, '$3-$2-$1');
          eDate = eDate.replace(pattern, '$3-$2-$1');
          if (stDate != '--') {
            var startDate = stDate;
          } else {
            var startDate = '1970-01-01';
          }
          if (eDate != '--') {
            var endDate = eDate;
          } else {
            var endDate = '2099-12-31';
          }
          return {
            'stDate': startDate,
            'eDate': endDate
          }
        }
      }),
      orderFixed: [2, 'asc'],
      order: [[4, 'asc'], [3, 'asc']],
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
            "<a href='#' id='edit' class='fa fa-edit' style='padding-right: 5px' title='Обновить'></a>" +
            "<a href='#' id='view' class='fa fa-info ' title='Подробности' style='padding-right: 5px'></a>" +
            "<a href='#' id='confirm-session' class='fa fa-calendar-check-o ' title='Подтвердить сеанс' style='padding-right: 5px'></a>"
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
          "width": '80px',

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

    table.on('click', '#edit', function (e) {
      e.preventDefault();
      var data = table.row($(this).parents('tr')).data();
      var url = "/vks/sessions/update-up-session-ajax?id=" + data[0];
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
        columnClass: 'large',
        title: 'Обновить предстоящий сеанс',
        buttons: {
          ok: {
            btnClass: 'btn-blue',
            text: 'Обновить',
            action: function () {
              var $form = $("#w0"),
                data = $form.data("yiiActiveForm");
              $.each(data.attributes, function () {
                this.status = 3;
              });
              $form.yiiActiveForm("validate");
              if ($("#w0").find(".has-error").length) {
                return false;
              } else {
                var d = $('.vks-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
                $('.vks-date').val(d);
                var d = $('.vks_receive-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
                $('.vks_receive-date').val(d);
                var yText = '<span style="font-weight: 600">Успех!</span><br>Сеанс обновлен';
                var nText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Обновить не удалось';
                sendFormData(url, table, $form, yText, nText);
              }
            }
          },
          cancel: {
            text: 'НАЗАД'
          }
        }
      });
    });

    table.on('click', '#view', function (e) {
      e.preventDefault();
      var data = table.row($(this).parents('tr')).data();
      var url = "/vks/sessions/view-up-session-ajax?id=" + data[0];
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

    table.on('click', '#confirm-session', function (e) {
      e.preventDefault();
      var data = table.row($(this).parents('tr')).data();
      var url = "/vks/sessions/confirm-ajax?id=" + data[0];
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
        columnClass: 'large',
        title: 'Подтвердить сеанс',
        buttons: {
          ok: {
            btnClass: 'btn-blue',
            text: 'Сохранить',
            action: function () {
              var $form = $("#w0"),
                data = $form.data("yiiActiveForm");
              $.each(data.attributes, function () {
                this.status = 3;
              });
              $form.yiiActiveForm("validate");
              if ($("#w0").find(".has-error").length) {
                return false;
              } else {
                var d = $('.fact-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
                $('.fact-date').val(d);
                var tehStart = (moment($('#teh-start').val(), 'HH:mm'));
                var tehEnd = (moment($('#teh-end').val(), 'HH:mm'));
                var duration = moment.duration(tehEnd.diff(tehStart)).asMinutes();
                if (duration > 0) {
                  $('#vks-duration-teh').val(duration);
                } else {
                  $('#vks-duration-teh').val('');
                }
                var workStart = (moment($('#work-start').val(), 'HH:mm'));
                var workEnd = (moment($('#work-end').val(), 'HH:mm'));
                var duration = moment.duration(workEnd.diff(workStart)).asMinutes();
                var label = $(this).parent().find('label');
                if (duration > 0) {
                  $('#vks-duration-work').val(duration);
                } else {
                  $('#vks-duration-work').val('');
                }
                var yText = '<span style="font-weight: 600">Успех!</span><br>Сеанс подтвержден';
                var nText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Подтвердить не удалось';
                sendFormData(url, table, $form, yText, nText);
              }
            }
          },
          cancel: {
            text: 'НАЗАД'
          }
        }
      });
    });


    // Работа таблицы -> событие выделения и снятия выделения

    table.on('select', function (e, dt, type, indexes) {
      if (type === 'row') {
        $('#delete-wrap').show();
        $('.get-pdf').show();
      }
    });
    table.on('deselect', function (e, dt, type, indexes) {
      if (type === 'row') {
        if (table.rows({selected: true}).count() > 0) return;
        $('#delete-wrap').hide();
        $('.get-pdf').hide();
      }
    });

    // Работа таблицы -> перерисовка или изменение размера страницы

    table.on('length.dt', function (e, settings, len) {
      $('#delete-wrap').hide();
      $('.get-pdf').show();
    });
    table.on('draw.dt', function (e, settings, len) {
      $('#delete-wrap').hide();
      $('.get-pdf').hide();
    });

    // Удаление записей

    $('#delete-wrap').click(function (event) {
      event.preventDefault();
      var csrf = $('meta[name=csrf-token]').attr("content");
      var url = "/vks/sessions/delete";
      if ($(this).attr('disabled')) {
        return;
      }
      jc = $.confirm({
        icon: 'fa fa-question',
        title: 'Вы уверены?',
        content: 'Вы действительно хотите удалить выделенное?',
        type: 'red',
        closeIcon: false,
        autoClose: 'cancel|9000',
        buttons: {
          ok: {
            btnClass: 'btn-danger',
            action: function () {
              jc.close();
              deleteRestoreProcess(url, table, csrf);
            }
          },
          cancel: {
            action: function () {
              return;
            }
          }
        }
      });
    });

    // Cоздание предстоящего сеанса

    $('#add-session').click(function (event) {
      event.preventDefault();
      var url = "/vks/sessions/create-up-session-ajax";
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
        columnClass: 'large',
        title: 'Добавить предстоящий сеанс',
        buttons: {
          ok: {
            btnClass: 'btn-blue',
            text: 'Добавить',
            action: function () {
              var $form = $("#w0"),
                data = $form.data("yiiActiveForm");
              $.each(data.attributes, function () {
                this.status = 3;
              });
              $form.yiiActiveForm("validate");
              if ($("#w0").find(".has-error").length) {
                return false;
              } else {
                var d = $('.vks-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
                $('.vks-date').val(d);
                var d = $('.vks_receive-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
                $('.vks_receive-date').val(d);
                var yText = '<span style="font-weight: 600">Успех!</span><br>Сеанс добавлен';
                var nText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Добавить не удалось';
                sendFormData(url, table, $form, yText, nText);
              }
            }
          },
          cancel: {
            text: 'НАЗАД'
          }
        }
      });
    });

    // Cоздание прошедшего сеанса

    $('#add-session-ex').click(function (event) {
      event.preventDefault();
      var url = "/vks/sessions/create-session-ajax";
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
        columnClass: 'large',
        title: 'Добавить прошедший сеанс',
        buttons: {
          ok: {
            btnClass: 'btn-blue',
            text: 'Добавить',
            action: function () {
              var $form = $("#w0"),
                data = $form.data("yiiActiveForm");
              $.each(data.attributes, function () {
                this.status = 3;
              });
              $form.yiiActiveForm("validate");
              if ($("#w0").find(".has-error").length) {
                return false;
              } else {
                var d = $('.fact-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
                $('.fact-date').val(d);
                var tehStart = (moment($('#teh-start').val(), 'HH:mm'));
                var tehEnd = (moment($('#teh-end').val(), 'HH:mm'));
                var duration = moment.duration(tehEnd.diff(tehStart)).asMinutes();
                if (duration > 0) {
                  $('#vks-duration-teh').val(duration);
                } else {
                  $('#vks-duration-teh').val('');
                }
                var workStart = (moment($('#work-start').val(), 'HH:mm'));
                var workEnd = (moment($('#work-end').val(), 'HH:mm'));
                var duration = moment.duration(workEnd.diff(workStart)).asMinutes();
                var label = $(this).parent().find('label');
                if (duration > 0) {
                  $('#vks-duration-work').val(duration);
                } else {
                  $('#vks-duration-work').val('');
                }
                var yText = '<span style="font-weight: 600">Успех!</span><br>Сеанс подтвержден';
                var nText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Обновить не удалось';
                sendFormData(url, table, $form, yText, nText);
              }
            }
          },
          cancel: {
            text: 'НАЗАД'
          }
        }
      });
    });
  });

  $(document).on('hide', '#vks-daterange', function (e) {
    $("#up-sessions-table").DataTable().clearPipeline().draw();
  });

  function returnCallback() {
    table.clearPipeline().draw();
  }

</script>