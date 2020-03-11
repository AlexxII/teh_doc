<?php

use \yii\helpers\Html;
use \yii\widgets\ActiveForm;

use app\assets\TableBaseAsset;
use app\assets\BootstrapDatepickerAsset;

BootstrapDatepickerAsset::register($this);
TableBaseAsset::register($this);

?>
<div class="row">
  <div class="col-lg-10 col-md-8">
    <div style="float: left; padding-top: 18px; padding-bottom: 15px; max-width: 290px">
      <div class="input-group date to-month-tooltip" data-toggle='tooltip' data-placement='top'>
        <input type="text" class="form-control" id="to-month" title="Необходимо ввести месяц"
               style="font-size: 22px;color:#C50100;font-weight: 600" name="month"><span
                class="input-group-addon"><i
                  class="fa fa-calendar" aria-hidden="true" style="font-size: 18px"></i></span>
      </div>
    </div>
  </div>
</div>
<form id="schedule-create">

  <table id="schedule-create-tbl" class="display no-wrap cell-border toTable" style="width:100%">
    <thead>
    <tr>
      <th></th>
      <th data-priority="1">№</th>
      <th data-priority="2">Наименование</th>
      <th>s/n</th>
      <th>ПАК</th>
      <th data-priority="2">Вид ТО</th>
      <th data-priority="2">Дата проведения</th>
      <th data-priority="2">Ответственный за проведение</th>
      <th data-priority="2">Ответственный за контроль</th>
      <th data-priority="2"></th>
    </tr>
    </thead>
  </table>
  <br>
  <button class="btn btn-ml" id="create-new-schedule" style="background-color: #2196f3; color: #fff">Создать</button>
</form>
<br>
<br>
<script>

  //=============================== Работа с календарями =====================================

  var toDateInput = '<input class="to-date form-control" name="to-date" disabled style="max-width: 170px;">';

  var createTable;
  $(document).ready(function () {


    createTable = $('#schedule-create-tbl').DataTable({
      'processing': true,
      'ajax': {
        'url': '/to/month-schedule/equipment'
      },
      'columns': [
        {'defaultContent': 'id'},
        {'defaultContent': ''},
        {'data': 'name'},
        {'data': 'eq_serial'},
        {'data': 'parent'},
        {'defaultContent': toTypeSelect},
        {'defaultContent': toDateInput},
        {'defaultContent': toAdminsSelect},
        {'defaultContent': toAuditorsSelect},
        {'defaultContent': ''}
      ],
      dom: 'Bfrtip',
      buttons: [
        'selectAll',
        'selectNone'
      ],
      ordering: false,
      orderFixed: [[4, 'desc']],
      paging: false,
      select: {
        style: 'os',
        selector: 'td:last-child'
      },
      rowGroup: {
        dataSrc: 'parent'
      },
      fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        $('select.to-list', nRow).attr('id', aData.id);
      },
      columnDefs: [
        {
          'targets': -3,
          'width': '150px'
        }, {
          'targets': -2,                    // предпоследний столбец
          'orderable': false,
          'data': null,
          'width': '70px',
          'defaultContent':
            '<a href="#" id="edit" class="fa fa-edit" style="padding-right: 5px" title="Обновить"></a>' +
            '<a href="#" id="view" class="fa fa-info" title="Подробности" style="padding-right: 5px"></a>'
        }, {
          'targets': -1,                    // последний столбец
          'orderable': false,
          'className': 'select-checkbox',
          'defaultContent': ''
        }, {
          'targets': 0,
          'visible': false,
          'orderable': false
        }, {
          'targets': 4,
          'visible': false
        }
      ],
      responsive: true,
      language: {
        url: '/lib/ru.json',
        'buttons': {
          'selectAll': 'Выделить все',
          'selectNone': 'Снять выделение'
        }
      }
    });
    createTable.on('order.dt search.dt', function () {
      createTable.column(1, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1;
      });
    }).draw();

    $('#schedule-create-tbl')
      .on('processing.dt', function (e, settings, processing) {
        $('#processingIndicator').css('display', processing ? 'block' : 'none');

      }).dataTable();
  });

  var freeDays = new Array();
  var busyDays = new Array(); // глобальный массив хранения

  $(document).ready(function () {
    //первичные настройки интерфейса и календаря

    $.fn.datepicker.defaults.format = "dd.mm.yyyy";
    $.fn.datepicker.defaults.language = "ru";
    $.fn.datepicker.defaults.autoclose = 'true';
    $.fn.datepicker.defaults.daysOfWeekDisabled = "0,6";

    $('.admin-list').prop('disabled', true);
    $('.to-date').prop('disabled', true);

    // инициализация календаря месяца проведения ТО
    $('#to-month').datepicker({
      format: 'MM yyyy г.',
      autoclose: true,
      language: "ru",
      startView: "months",
      minViewMode: "months",
      clearBtn: true
    });

    // обработчик события - выбрать месяц проведения ТО
    // и формирование массива выходных дней

  });

  var scheduleYear, scheduleMonth, scheduleDate;

</script>