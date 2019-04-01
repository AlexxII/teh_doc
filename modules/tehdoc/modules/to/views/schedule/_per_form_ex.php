<?php

use \yii\widgets\ActiveForm;
use \yii\helpers\Html;

?>

<style>
  #main-table tbody td {
    font-size: 12px;
  }
  #main-table tr {
    font-size: 12px;
  }
  #main-table tbody tr select {
    font-size: 12px;
  }
  tr input, select {
    min-height: 20px;
  }
  .form-control {
    width: 140px
  }
  .to-list {
    width: 170px
  }
</style>

<?php
$form = ActiveForm::begin([
  'fieldConfig' => [
    'options' => [
      'tag' => false,
      'class' => 'userform'

    ],
  ],
]); ?>

<div class="to-month-hidden" style="display:none">
  <input class="to-month" value="<?= $month ?>">
</div>

<div class="row">
  <div class="col-lg-10 col-md-8">
    <h3 style="float: left; padding-right: 15px"><?= $header; ?></h3>
    <div style="float: left; padding-top: 18px; padding-bottom: 15px; max-width: 290px">
      <div class="input-group date to-month-tooltip" data-toggle='tooltip' data-placement='top'>
        <input type="text" class="form-control" id="to-month"
               style="font-size: 22px;color: #C50100;font-weight: 600"><span
          class="input-group-addon"><i
            class="fa fa-calendar" aria-hidden="true" style="font-size: 18px"></i></span>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    var table = $('#main-table').DataTable({
      "columnDefs": [
        {"visible": false, "targets": 3},
        {"width": "150px", "targets": 5},
        {"width": "150px", "targets": 6},
        {"width": "150px", "targets": 7},
        {"width": "150px", "targets": 8},
        {"orderable": false, "className": 'select-checkbox', "targets": 9}
      ],
      dom: 'Bfrtip',
      select: {
        style: 'os',
        selector: 'td:last-child'
      },
      paging: false,
      rowGroup: {
        dataSrc: 3
      },
      buttons: [
        'selectAll',
        'selectNone'
      ],
      responsive: true,
      language: {
        url: "/lib/ru.json",
        "buttons": {
          "selectAll": "Выделить все",
          "selectNone": "Снять выделение"
        }
      }
    });
    table.on('order.dt search.dt', function () {
      table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1;
      });
    }).draw();

  });
</script>

<table id="main-table" class="display no-wrap cell-border" style="width:100%">
  <thead>
  <tr>
    <th data-priority="1">№</th>
    <th data-priority="2">Наименование</th>
    <th>s/n</th>
    <th>ПАК</th>
    <th data-priority="2">Вид ТО</th>
    <th data-priority="2">Дата проведения (план.)</th>
    <th data-priority="2">Дата проведения (факт.)</th>
    <th data-priority="2">Сотрудник</th>
    <th data-priority="2">Ответственный за контроль</th>
    <th data-priority="1"></th>
  </tr>
  </thead>
  <tbody>
  <?php
  foreach ($tos as $e => $to): ?>
    <tr>
      <td></td>
      <td>
        <?php
        if (!empty($to->toEq)) {
          echo $to->toEq->name;
        } else {
          echo '-';
        }; ?>
      </td>
      <td style="word-break:break-all;min-width: 100px;">
        <?php if (!empty($to->toEq)) {
          echo $to->toEq->eq_serial;
        } else {
          echo '<span style="color:#CC0000">Вероятно оборудование удалено</span>';
        }; ?>
      </td>
      <td>
        <?php
        if (!empty($to->toEq)) {
          echo $to->toEq->groupName->name;
        } else {
          echo '<span style="color:#CC0000">Вероятно оборудование удалено</span>';
        }; ?>
      </td>
      <td>
        <?= $to->toList[$to->to_type]; ?>
      </td>
      <td>
        <?= date("d.m.Y г.", strtotime($to->plan_date)) ?>
      </td>
      <td><?= $form->field($to, "[$e]fact_date", ['template' => "<div >{input}</div>"])->textInput(
          [
            'class' => 'to-date form-control m-date'
          ])->label(false) ?>
      </td>
      <td><?= $form->field($to, "[$e]admin_id", ['template' => "<div>{input}</div>"])->dropDownList($to->adminList,
          [
            'prompt' => [
              'text' => 'Выберите',
              'options' => [
                'value' => 'none',
                'disabled' => 'true',
                'selected' => 'true'
              ]
            ],
            'class' => 'form-control admin-list multi'
          ])->label(false); ?>
      </td>
      <td><?= $form->field($to, "[$e]auditor_id", ['template' => "<div>{input}</div>"])->dropDownList($to->auditorList,
          [
            'prompt' => [
              'text' => 'Выберите',
              'options' => [
                'value' => 'none',
                'disabled' => 'true',
                'selected' => 'true'
              ]
            ],
            'class' => 'form-control audit-list multi'
          ])->label(false); ?>
      </td>
      <td></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<br>
<div class="form-group">
  <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

<script>
  // обработка выбора ответственного за проведение ТО
  $('.admin-list').on('change', function (e) {
    var val = e.target.value;
    if ($(this).closest('tr').hasClass('selected')) {
      $('.selected').each(function () {
        $(this).find('.admin-list').val(val);
      });
    }
  });

  // обработка выбора ответственного за контроль ТО
  $('.audit-list').on('change', function (e) {
    var val = e.target.value;
    if ($(this).closest('tr').hasClass('selected')) {
      $('.selected').each(function () {
        $(this).find('.audit-list').val(val);
      });
    }
  });

  function copySl(e) {
    if ($(this).closest('tr').hasClass('selected')) {
      var dt = $(this).data('datepicker').getFormattedDate('dd-mm-yyyy');
      $('.selected').each(function () {
        var toDate = $(this).find('.to-date');
        toDate.off('change', copySl);           // чтобы не сработала рекурсия события 'change'
        if (!toDate.prop('disabled'))
          toDate.datepicker('update', dt);
        toDate.on('change', copySl);           //
      });
    }
  }


  $(document).ready(function () {
    $('#to-month').datepicker({
      format: 'MM yyyy г.',
      language: "ru"
    })
  });


  $(document).ready(function () {
    $('.m-date').datepicker({
      format: 'dd.mm.yyyy',
      autoclose: true,
      language: "ru",
      daysOfWeekDisabled: "0,6",
      forceParse: false,
      clearBtn: true
    })
  });


  $(document).ready(function () {
    $.get('/lib/free_days.js', function (data) {
      $('.m-date').datepicker('setDatesDisabled', arr);
    });
  });


  // обработка событий удаления дат
  $(document).ready(function () {
    $('.to-date').datepicker()
      .on('clearDate', function () {
        $('#main-table > tbody > tr').each(function () {
          if ($(this).hasClass('selected')) {
            $('.to-date').off('change', copySl);           // чтобы не сработала рекурсия события 'change'
            $(this).find('.to-date').datepicker('clearDates');
          }
        });
        $('.to-date').on('change', copySl);                    // снова включаем обработчик события 'change'
      });
  });


  // установка формата календаря при редактировании графика
  $(document).ready(function () {
    $('.m-date').off('change', copySl);           // чтобы не сработала рекурсия события 'change'
    $('.m-date').each(function () {
      var date;
      if (date = $(this).val()) {
        date = new moment(date);
        $(this).datepicker('update', date.format("DD.MM.Y"));
      }
    });
    $('.m-date').on('change', copySl);
  });


  // задание начальной даты календаря (не может быть раньше месяца проведения)
  $(document).ready(function () {
    var m = $('.to-month');
    if (m.val() != '') {
      var fullDate = new Date(m.val());
      var year = fullDate.getFullYear();
      var month = fullDate.getMonth();
      var nMonth = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
      $('.m-date').prop('disabled', false);
      $('.m-date').datepicker('setStartDate', '01-' + nMonth[month] + '-' + year);
      $('.m-date').datepicker('update');
      $('.m-date').on('change', copySl);                    // обработчик события 'change'
    } else {
      $('.m-date').prop('disabled', true);
      $('.m-date').val('');
      alert('Ошибка приложения. Обратитесь к разработчику. Отсутствует месяц ТО.')
    }
  });


  // установка значения месяца в шапке страницы
  $(document).ready(function () {
    var monthDate = $('.to-month-hidden input').val();
    var fullDate = new Date(monthDate);
    var month = fullDate.getMonth();
    var year = fullDate.getFullYear();
    var nMonth = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    $('#to-month').datepicker('update', nMonth[month] + '-' + year);
    $('#to-month').datepicker('remove').prop('readonly', true);
  });

  // форматирование дат в соответствии с форматом MySQL(UNIX) перед отправкой формы
  $(document).ready(function () {
    $('#w0').submit(function () {
      $('.m-date').each(function () {
        var dy = $(this).data('datepicker').getFormattedDate('yyyy-mm-dd');
        $(this).val(dy);
      })
    });
  });




</script>
