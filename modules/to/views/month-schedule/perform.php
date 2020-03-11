<?php

use yii\helpers\Html;

?>

<style>
  .form-control[readonly] {
    background-color: #fff;
  }
</style>

<table id="perform-schedule-tbl" data-id="<?= $scheduleId ?>" class="display no-wrap cell-border toTable" style="width:100%">
  <thead>
  <tr>
    <th></th>
    <th data-priority="1">№</th>
    <th data-priority="2">Наименование</th>
    <th>s/n</th>
    <th>ПАК</th>
    <th data-priority="2">Вид ТО</th>
    <th data-priority="2">Дата проведения</th>
    <th data-priority="2">Дата проведения</th>
    <th data-priority="2">Ответственный за проведение</th>
    <th data-priority="2">Ответственный за контроль</th>
    <th></th>
  </tr>
  </thead>
</table>
<br>
<button class="btn btn-ml" id="perform-schedule" style="background-color: #2196f3; color: #fff" title="Подтвердить выполнение ТО">Подтвердить</button>
<br>
<br>

<script>
  var toDateInput = '<input class="to-date form-control" name="to-date" style="max-width: 170px;" readonly>';

  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $.fn.datepicker.defaults.format = "dd.mm.yyyy";
    $.fn.datepicker.defaults.language = "ru";
    $.fn.datepicker.defaults.autoclose = 'true';
    $.fn.datepicker.defaults.daysOfWeekDisabled = "0,6";

    var id = $('#perform-schedule-tbl').data('id');
    var table = $('#perform-schedule-tbl').DataTable({
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
        {'data': 'toType'},
        {'data': 'plan_date'},
        {'defaultContent': toDateInput},
        {'data': 'admin'},
        {'data': 'auditor'},
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
        var pattern = /(\d{4})\-(\d{2})\-(\d{2})/;
        // console.log(aData);
        if (fDate != null) {
          $('.to-date', nRow).datepicker('setStartDate', pDate.replace(pattern, '$3.$2.$1'));
          $('.to-date', nRow).datepicker('setDate', fDate.replace(pattern, '$3.$2.$1'));
          $('.to-date', nRow).datepicker('update', fDate.replace(pattern, '$3.$2.$1'));
          $('.to-date', nRow).on('change', copySl);                                      // обработчик события 'change'
        } else {
          if (pDate != null) {
            $('.to-date', nRow).datepicker('setStartDate', pDate.replace(pattern, '$3.$2.$1'));
            $('.to-date', nRow).datepicker('update', pDate.replace(pattern, '$3.$2.$1'));
            $('.to-date', nRow).val('');
            $('.to-date', nRow).on('change', copySl);                                      // обработчик события 'change'
          } else {
            $('td:nth-child(5)', nRow).text('-');
          }
        }
        if (pDate != null) {
          $('td:nth-child(5)', nRow).text(pDate.replace(pattern, '$3.$2.$1'));
        } else {
          $('td:nth-child(5)', nRow).text('-');
        }
      },
      columnDefs: [
        {
          'targets': 0,
          'visible': false
        }, {
          'targets': 4,
          'visible': false
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

    $('#perform-schedule-tbl')
      .on('processing.dt', function (e, settings, processing) {
        $('#processingIndicator').css('display', processing ? 'block' : 'none');
      }).dataTable();



  });


</script>

