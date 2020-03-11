<?php

use yii\helpers\Html;
use app\assets\TableBaseAsset;
use app\modules\to\assets\ToAsset;
use app\assets\BootstrapDatepickerAsset;
use app\assets\FancytreeAsset;
use app\assets\NotyAsset;
use app\assets\PdfmakeAsset;

FancytreeAsset::register($this);
NotyAsset::register($this);
PdfmakeAsset::register($this);

BootstrapDatepickerAsset::register($this);
ToAsset::register($this);                       // регистрация ресурсов модуля
TableBaseAsset::register($this);                // регистрация ресурсов таблиц datatables

$this->title = "Графики ТО";

?>

<div class="tool-task">
  <div class="" style="position: relative">
    <div class="container-fluid" style="position: relative">
      <div id="add-scheduler-wrap" class="hidden-xs hidden-sm">
        <a id="add-scheduler" class="fab-button ex-click"
           data-url="/to/month-schedule/create" data-back-url="/to" title="Добавить график ТО">
          <div class="plus"></div>
        </a>
      </div>
    </div>

    <div id="delete-wrap" style="position: absolute; top: 10px; right:-60px;display: none;fill: white">
      <a id="del-session-ex" class="fab-button" title="Удалить выделенный(е) график(и)"
         style="cursor: pointer; background-color: red">
        <svg width="50" height="50" viewBox="-1 -1 24 24">
          <path d="M15 4V3H9v1H4v2h1v13c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6h1V4h-5zm2 15H7V6h10v13z"></path>
          <path d="M9 8h2v9H9zm4 0h2v9h-2z"></path>
        </svg>
      </a>
    </div>

    <div class="container-fluid">
      <table id="to-mscheduler-table" class="display no-wrap cell-border toTable" style="width:100%">
        <thead>
        <tr>
          <th></th>
          <th data-priority="1">№ п.п.</th>
          <th data-priority="1">Месяц</th>
          <th data-priority="7">Отметки</th>
          <th>Год</th>
          <th>Вид ТО</th>
          <th>Ответственный за проведение</th>
          <th>Ответственный за контроль</th>
          <th data-priority="3">Action</th>
          <th></th>
        </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    $('#push-it').removeClass('hidden');
    $('#app-control').removeClass('hidden');

    initLeftMenu('/to/menu/left-side');
    initSmallMenu('/to/menu/small-menu');
    initAppConfig('/to/menu/app-config');

    controlListsInit();                                       // загрузка списков ТО

    // процедуры возврата из модальных окон
    controlCallback = function () {
      controlListsInit();
    };
    // процедуры возврата из второстепенного контента
    returnCallback = function () {
      archiveTable.ajax.reload();
      controlListsInit();                                         // загрузка списков ТО при возврате (возможно произошли изменения)
    };

    // ************************* Работа таблицы **************************************

    var editBtn = '<a href="#" id="edit" class="fa fa-edit" style="padding-right: 5px" title="Обновить"></a>';
    var infoBtn = '<a href="#" id="view" class="fa fa-info" ' +
      ' title="Подробности" data-url="/to/month-schedule/view" data-back-url="/to" style="padding-right: 5px"></a>';
    var cfrmBtn = '<a href="#" id="perform" class="fa fa-calendar-check-o" ' +
      'title="Подтвердить выполнение" style="padding-right: 5px"></a>';

    var toTypeSelect, toAdminsSelect, toAuditorsSelect;

    archiveTable = $('#to-mscheduler-table').DataTable({
      "processing": true,
      "responsive": true,
      "ajax": {
        'url': '/to/month-schedule'
      },
      "columns": [
        {"data": "schedule_id"},
        {"data": "id"},
        {"data": "plan_date"},
        {"data": "checkmark"},
        {"data": "plan_date"},
        {"data": "to_type"},
        {"data": "admins"},
        {"data": "auditors"},
        {"data": ""},
        {"data": ""}
      ],
      "searching": false,
      "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        var date = aData.plan_date;
        // var today = new Date();
        var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
        var dDate = new Date(date.replace(pattern, '$3-$2-$1'));
        var month = monthNames[dDate.getMonth()];
        $('td:nth-child(2)', nRow).text(month);
        aData['monthText'] = month;
        aData['monthVal'] = ("0" + (dDate.getMonth() + 1)).slice(-2);
        // $('td:nth-child(7)', nRow).html(editBtn + infoBtn + cfrmBtn);
        if (aData.checkmark == 0) {
          $('td:nth-child(7)', nRow).html(editBtn + infoBtn + cfrmBtn);
          $('td:nth-child(3)', nRow).css({color: 'red'});
        } else {
          if (aData.checkmark.length > 1) {
            $('td:nth-child(7)', nRow).html(infoBtn + cfrmBtn);
            $('td:nth-child(3)', nRow).css({color: 'blue'});
          } else {
            $('td:nth-child(7)', nRow).html(infoBtn);
            $('td:nth-child(3)', nRow).css({color: 'green'});
          }
        }
      },
      orderFixed: [[4, 'desc']],
      order: [[1, 'desc']],
      rowGroup: {
        dataSrc: 'year'
      },
      'columnDefs': [
        {
          'targets': -2,                    // предпоследний столбец
          'orderable': false,
          'data': null,
          'width': '70px',
          'defaultContent': ''
        }, {
          'targets': -1,                    // последний столбец
          'orderable': false,
          'className': 'select-checkbox',
          'defaultContent': ''
        }, {
          'targets': 0,
          'data': null,
          'visible': false
        }, {
          'targets': 1,
          'data': null
        }, {
          'targets': 2,
          'render': function (data) {
            return moment(data).format('MMMM');
          }
        }, {
          'targets': 3,
          'render': function (data) {
            if (data.length == 1) {
              if (data == '1') {
                return '<strong>ТО проведено</strong>';
              } else {
                return '<strong>ТО не проведено</strong>';
              }
            } else {
              return '<strong>Проведено не полность</strong>';
            }
          }
        }, {
          'targets': 4,
          'data': null,
          'visible': false
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

    archiveTable.on('order.dt search.dt', function () {
      archiveTable.column(1, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1;
      });
    }).draw();


    // Работа таблицы -> событие выделения и снятия выделения

    archiveTable.on('select', function (e, dt, type, indexes) {
      if (type === 'row') {
        $('#delete-wrap').show();
      }
    });
    archiveTable.on('deselect', function (e, dt, type, indexes) {
      if (type === 'row') {
        if (archiveTable.rows({selected: true}).count() > 0) return;
        $('#delete-wrap').hide();
      }
    });

    // Работа таблицы -> перерисовка или изменение размера страницы

    archiveTable.on('length.dt', function (e, settings, len) {
      $('#delete-wrap').hide();
    });

    archiveTable.on('draw.dt', function (e, settings, len) {
      $('#delete-wrap').hide();
    });

    archiveTable.on('click', '#view', function (e) {
      e.preventDefault();
      var data = archiveTable.row($(this).parents('tr')).data();
      var id = data.schedule_id;
      var year = data.year;
      var date = data.plan_date;
      var month = data.monthText;
      console.log(data);
      var sData = {
        'id': id,
        'year': year,
        'month': month,
        'monthVal': data.monthVal
      };
      var url = '/to/month-schedule/view';
      var backUrl = '/to';
      jc = $.confirm({
        icon: 'fa fa-cog fa-spin',
        title: 'Подождите!',
        content: 'Ваш запрос выполняется!',
        buttons: false,
        closeIcon: false,
        confirmButtonClass: 'hide'
      });
      loadContent(url, backUrl, jc, sData);
    });

    archiveTable.on('click', '#perform', function (e) {
      e.preventDefault();
      var data = archiveTable.row($(this).parents('tr')).data();
      var id = data.schedule_id;
      var year = data.year;
      var date = data.plan_date;
      var monthText = data.monthText;
      var monthVal = data.monthVal;
      var sData = {
        'id': id,
        'year': year,
        'monthText': monthText,
        'monthVal': monthVal
      };
      var url = '/to/month-schedule/perform';
      var backUrl = '/to';
      jc = $.confirm({
        icon: 'fa fa-cog fa-spin',
        title: 'Подождите!',
        content: 'Ваш запрос выполняется!',
        buttons: false,
        closeIcon: false,
        confirmButtonClass: 'hide'
      });
      loadContent(url, backUrl, jc, sData);
    });

    archiveTable.on('click', '#edit', function (e) {
      e.preventDefault();
      var data = archiveTable.row($(this).parents('tr')).data();
      var id = data.schedule_id;
      var year = data.year;
      var date = data.plan_date;
      var monthText = data.monthText;
      var monthVal = data.monthVal;
      var sData = {
        'id': id,
        'year': year,
        'monthText': monthText,
        'monthVal': monthVal
      };
      var url = '/to/month-schedule/edit';
      var backUrl = '/to';
      jc = $.confirm({
        icon: 'fa fa-cog fa-spin',
        title: 'Подождите!',
        content: 'Ваш запрос выполняется!',
        buttons: false,
        closeIcon: false,
        confirmButtonClass: 'hide'
      });
      loadContent(url, backUrl, jc, sData);
    });


    $('#delete-wrap').click(function (event) {
      event.preventDefault();
      var csrf = $('meta[name=csrf-token]').attr("content");
      var url = "/to/month-schedule/delete";
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
              deleteRestoreProcess(url, archiveTable, csrf);
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

  });

  function loadContent(url, backUrl, jc, data) {
    var csrf = $('meta[name=csrf-token]').attr("content");
    $.ajax({
      url: url,
      method: 'POST',
      data: {
        '_csrf': csrf,
        'data': data,
        'back-url': backUrl
      }
    }).done(function (response) {
      $('#app-wrap').append('<div id="ex-wrap">' + response.data.data + '</div>');
      jc.close();
    }).fail(function () {
      jc.close();
      console.log('fail');
    });
  }

</script>