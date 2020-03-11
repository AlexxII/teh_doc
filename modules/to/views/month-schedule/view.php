<?php

use yii\helpers\Html;

?>

<div style="position: relative">
  <span class="scheduler-info" data-month="<?= $month?>" data-year="<?=$year?>"></span>
  <div class="get-pdf" style="position: absolute; top:10px; right:-60px"
       data-table="view-schedule-tbl">
    <a class="fab-button" title="Передать в PDF" style="cursor: pointer; background-color: blue" >
      <svg width="50" height="50" viewBox="0 0 24 24" style="padding-left: 10px;fill: #fff">
        <path d="M11.363 2c4.155 0 2.637 6 2.637 6s6-1.65 6 2.457v11.543h-16v-20h7.363zm.826-2h-10.189v24h20v-14.386c0-2.391-6.648-9.614-9.811-9.614zm4.811
           13h-2.628v3.686h.907v-1.472h1.49v-.732h-1.49v-.698h1.721v-.784zm-4.9 0h-1.599v3.686h1.599c.537 0 .961-.181
            1.262-.535.555-.658.587-2.034-.062-2.692-.298-.3-.712-.459-1.2-.459zm-.692.783h.496c.473
            0 .802.173.915.644.064.267.077.679-.021.948-.128.351-.381.528-.754.528h-.637v-2.12zm-2.74-.783h-1.668v3.686h.907v-1.277h.761c.619
            0 1.064-.277 1.224-.763.095-.291.095-.597 0-.885-.16-.484-.606-.761-1.224-.761zm-.761.732h.546c.235
            0 .467.028.576.228.067.123.067.366 0 .489-.109.199-.341.227-.576.227h-.546v-.944z"/>
      </svg>
    </a>
  </div>

  <table id="view-schedule-tbl" data-id="<?= $scheduleId ?>" class="display no-wrap cell-border toTable"
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
      <th data-priority="2">Дата проведения</th>
      <th data-priority="2">Ответственный за проведение</th>
      <th data-priority="2">Ответственный за контроль</th>
    </tr>
    </thead>
  </table>
</div>


<script>
  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  $(document).ready(function () {
    var id = $('#view-schedule-tbl').data('id');
    var table = $('#view-schedule-tbl').DataTable({
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
        {'data': 'fact_date'},
        {'data': 'admin'},
        {'data': 'auditor'}
      ],
      paging: false,
      ordering: false,
      orderFixed: [[4, 'desc']],
      rowGroup: {
        dataSrc: 'parent'
      },
      fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        $('select.to-list', nRow).attr('id', aData.id);
        var pDate = aData.plan_date;
        var fDate = aData.fact_date;
        var pattern = /(\d{4})\-(\d{2})\-(\d{2})/;
        if (pDate != null) {
          $('td:nth-child(5)', nRow).text(pDate.replace(pattern, '$3.$2.$1'));
        } else {
          $('td:nth-child(5)', nRow).text('-');
        }
        if (fDate != null) {
          $('td:nth-child(6)', nRow).text(fDate.replace(pattern, '$3.$2.$1'));
        } else {
          $('td:nth-child(6)', nRow).text('-');
        }
      },
      columnDefs: [
        {
          'targets': 0,
          'visible': false
        }, {
          'targets': 4,
          'visible': false
        },
      ],
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

  });


</script>

