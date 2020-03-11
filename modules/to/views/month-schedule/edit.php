<?php

use yii\helpers\Html;

?>

<style>
  .form-control[readonly] {
    background-color: #fff;
  }
</style>

<table id="edit-schedule-tbl" data-id="<?= $scheduleId ?>" class="display no-wrap cell-border toTable"
       style="width:100%">
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
    <th></th>
  </tr>
  </thead>
</table>
<br>
<button class="btn btn-ml" id="save-edit" style="background-color: #2196f3; color: #fff" title="Обновить график">
  Обновить
</button>
<br>
<br>

<script>
  var toPlanDateInput = '<input class="to-date plan-date form-control" name="plan-date" style="max-width: 170px;" readonly>';
  var toFactDateInput = '<input class="to-date fact-date form-control" name="fact-date" style="max-width: 170px;" readonly>';

  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $.fn.datepicker.defaults.format = 'dd.mm.yyyy';
    $.fn.datepicker.defaults.language = 'ru';
    $.fn.datepicker.defaults.autoclose = 'true';
    $.fn.datepicker.defaults.daysOfWeekDisabled = '0,6';

    var id = $('#edit-schedule-tbl').data('id');
    var table = $('#edit-schedule-tbl').DataTable({
      'processing': true,
      'ajax': {
        'url': '/to/month-schedule/schedule-view?id=' + id
      },
      'columns': [
        {'defaultContent': ''},
        {'defaultContent': ''},
        {'data': 'equipment'},
        {'data': 's/n'},
        {'data': 'parent'},
        {'defaultContent': toTypeSelect},
        {'defaultContent': toPlanDateInput},
        {'defaultContent': toAdminsSelect},
        {'defaultContent': toAuditorsSelect},
        {'defaultContent': ''}
      ],
      paging: false,
      ordering: false,
      orderFixed: [[4, 'desc']],
      rowGroup: {
        dataSrc: 'parent'
      },
      fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        $('.to-date', nRow).attr('id', aData.id);
        var pDate = aData.plan_date;
        var fDate = aData.fact_date;
        var toType = aData.toType;
        var pattern = /(\d{4})\-(\d{2})\-(\d{2})/;
        var admin = aData.admin;
        var auditor = aData.auditor;
        if (pDate != null) {
          $('.plan-date', nRow).datepicker('update', pDate.replace(pattern, '$3.$2.$1'));
          $('.plan-date', nRow).on('change', copySl);                                      // обработчик события 'change'
        }
        if (fDate != null) {
          $('.fact-date', nRow).datepicker('setStartDate', pDate.replace(pattern, '$3.$2.$1'));
          $('.fact-date', nRow).datepicker('setDate', fDate.replace(pattern, '$3.$2.$1'));
          $('.fact-date', nRow).datepicker('update', fDate.replace(pattern, '$3.$2.$1'));
          $('.fact-date', nRow).on('change', copySl);                                      // обработчик события 'change'
        }
        $('.admin-list option', nRow).filter(function () {
          return this.text == admin;
        }).attr('selected', true);
        $('.audit-list option', nRow).filter(function () {
          return this.text == auditor;
        }).attr('selected', true);
        $('.to-list option', nRow).filter(function () {
          return this.text == toType;
        }).attr('selected', true);
      },
      columnDefs: [
        {
          'targets': 0,
          'visible': false
        }, {
          'targets': 2,
          'width': '270px'
        }, {
          'targets': 4,
          'visible': false
        }, {
          'targets': 5,
          'orderable': false,
          'width': '110px'
        }, {
          'targets': -1,                    // последний столбец
          'orderable': false,
          'className': 'select-checkbox',
          'defaultContent': ''
        }
      ],
      select: {
        style: 'os',
        selector: 'td:last-child'
      },
      responsive: true,
      language: {
        url: '/lib/ru.json',
      }
    });
    table.on('order.dt search.dt', function () {
      table.column(1, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1;
      });
    }).draw();

    $('#edit-schedule-tbl')
      .on('processing.dt', function (e, settings, processing) {
        $('#processingIndicator').css('display', processing ? 'block' : 'none');
        if (!processing) {
          $('.plan-date').datepicker()
            .on('changeDate', function (e) {
              var input = e.currentTarget;
              input.dataset.new = 1;
            });
        }
      }).dataTable();
  });


</script>

