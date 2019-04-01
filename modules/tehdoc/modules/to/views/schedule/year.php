<?php

use \yii\widgets\ActiveForm;
use \yii\helpers\Html;
use kartik\widgets\DatePicker;

?>

<style>
  tr input, select {
    min-height: 20px;
  }
  #main-table tbody td {
    font-size: 12px;
  }
  #main-table tr {
    font-size: 12px;
  }
  .highlight {
    /*background-color: #b2dba1;*/
    color: #CC0000;
    font-weight: 700;
  }
  .form-control {
    width: 140px
  }
  .to-list {
    width: 170px
  }
  .loading {
    background-color: #ffffff;
    background-image: url("/lib/3.gif");
    background-size: 20px 20px;
    background-position: right center;
    background-repeat: no-repeat;
  }
  .loading-ex {
    background-color: #ffffff;
    background-image: url("/lib/3.gif");
    background-size: 20px 20px;
    background-position: right 20px center;
    background-repeat: no-repeat;
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

<div class="row">
  <div class="col-lg-10 col-md-8">
    <h3 style="float: left; padding-right: 15px"><?= $header; ?></h3>
    <div style="float: left; padding-top: 18px; padding-bottom: 15px; max-width: 290px">
      <div class="input-group date to-month-tooltip" data-toggle='tooltip' data-placement='top'>
        <input type="text" class="form-control to-month" title="Необходимо ввести месяц"
               style="font-size: 22px;color:#C50100;font-weight: 600" name="month"><span
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
        {"width": "180px", "targets": 4},
      ],
      dom: 'Bfrtip',
      paging: false,
      rowGroup: {
        dataSrc: 3
      },
      buttons: [
        'selectAll',
        'selectNone'
      ],
      fixedHeader: {
        header: true,
        headerOffset: $('#topnav').height()
      },
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
    <td data-priority="1">№</td>
    <td data-priority="2">Наименование</td>
    <td>s/n</td>
    <td>ПАК</td>
    <td>Ответственный</td>
    <td>Январь</td>
    <td>Февраль</td>
    <td>Март</td>
    <td>Апрель</td>
    <td>Май</td>
    <td>Июнь</td>
    <td>Июль</td>
    <td>Август</td>
    <td>Сентябрь</td>
    <td>Октябрь</td>
    <td>Ноябрь</td>
    <td>Декабрь</td>
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
      <td>
        <?php
        if (!empty($to->toEq)) {
          echo $to->toEq->eq_serial;
        } else {
          echo '-';
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
      <td>-</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<br>
<div class="form-group">
  <?= Html::submitButton('Создать график', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>


<script>

  var freeDays = new Array();

  $('.admin-list').on('change', function (e) {
    var val = e.target.value;
    $(this).closest('tr').find('.to-date').prop('disabled', false);
    if ($(this).closest('tr').hasClass('selected')) {
      $('.selected').each(function () {
        $(this).find('.admin-list').val(val);
        $(this).find('.to-date').prop('disabled', false);
      });
    }
  });

  // копирование селектов в выделенные ячейки
  $('.m-select').on('change', function (e) {
    var i = $(this).closest('td').index();
    var val = e.target.value;
    if ($(this).closest('tr').hasClass('selected')) {
      $('.selected').each(function () {
        $(this).find('td').eq(i).find(e.target.nodeName).val(val);
      });
    }
  });

  $('.m-select').on('input', function (e) {
    var i = $(this).closest('td').index();
    var val = e.target.value;
    if ($(this).closest('tr').hasClass('selected')) {
      $('.selected').each(function () {
        $(this).find('td').eq(i).find(e.target.nodeName).val(val);
      });
    }
  });

  $(document).ready(function () {
    $('.m-select').on('change', copySl());
  });

  // инициализация календаря месяца проведения ТО
  $(document).ready(function () {
    $('.to-month').datepicker({
      format: 'MM yyyy г.',
      autoclose: true,
      language: "ru",
      startView: "months",
      minViewMode: "months",
      clearBtn: true
    })
  });

  // обработчик события - выбрать месяц проведения ТО
  // и формирование массива выходных дней
  $('.to-month').on('change', function (e) {
    if (e.target.value != '') {
      var fullDate = $('.to-month').datepicker('getDate');
      $('.admin-list').prop('disabled', true);
      $('.admin-list').addClass('loading-ex');
      $('.to-date').val('');
      $('.admin-list').val('none');
      $('.to-date').prop('disabled', true);
      getFreeDays(fullDate);
      $('.admin-list').prop('disabled', false);
      $('.admin-list').removeClass('loading-ex');
    } else {
      $('.to-date').val('');
      $('.to-date').prop('disabled', true);
      $('.admin-list').prop('disabled', true);
      $('.admin-list').val('none');
    }
  });

  var start_day, end_day; // глобальные переменных хранения дат
  var busyDays = new Array(); // глобальный массив хранения

  // формирует ajax запрос на получение выxодных дней в зависимоти от месяца
  function getFreeDays(fullDate) {
    var month = fullDate.getMonth();
    var year = fullDate.getFullYear();
    var mDays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    var nMonth = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    var start_date = year + '-' + nMonth[month] + '-01';
    var end_date = year + '-' + nMonth[month] + '-' + mDays[month];
    window.start_day = '01.' + nMonth[month] + '.' + year;
    window.end_day = mDays[month] + '.' + nMonth[month] + '.' + year;
    var tempArray = new Array();
    $.ajax({
      url: "free-days?start_date=" + start_date + "&end_date=" + end_date,
      cache: false,
      success: function (responce) {
        tempArray = $.parseJSON(responce);
        busyDays[tempArray[0]['people_id']] = new Array();
        busyDays[tempArray[0]['people_id']][tempArray[0]['free_dates']] = tempArray[0]['labor_title'];
        for (var j = 1; j < tempArray.length; j++) {
          if (busyDays[tempArray[j]['people_id']] == busyDays[tempArray[j - 1]['people_id']]) {
            busyDays[tempArray[j]['people_id']][tempArray[j]['free_dates']] = tempArray[j]['labor_title']
          } else {
            busyDays[tempArray[j]['people_id']] = new Array();
            busyDays[tempArray[j]['people_id']][tempArray[j]['free_dates']] = tempArray[j]['labor_title']
          }
        }
        console.log(busyDays);
      }
    });
  }

  // формат для всех календарей!!!!!!!!!!!!
  $(document).ready(function () {
    $.fn.datepicker.defaults.format = "dd.dd.yyyy";
    $.fn.datepicker.defaults.language = "ru";
    $.fn.datepicker.defaults.daysOfWeekDisabled = "0,6";
  });


  //селект не изменялся => нет неоходимости перегружать календарь!!!!!
  // обработка события нажатия на input даты проведения ТО
  $(document).ready(function () {
    $('.to-date').mouseup(function () {
      $(this).prop('disabled', true);
      $(this).addClass('loading');
      var val = $(this).closest('tr').find('.admin-list').val();
      $(this).datepicker('remove');
      if (busyDays[val]) {
        $(this).datepicker({
          autoclose: true,
          forceParse: false,
          clearBtn: true,
          beforeShowDay: function (date) {
            if (window.busyDays[val]) {
              if (window.busyDays[val][moment(date).format('Y-MM-DD')]) {
                return {
                  classes: 'highlight',
                  tooltip: window.busyDays[val][moment(date).format('Y-MM-DD')]
                };
              }
            }
          }
        });
      } else {
        $(this).datepicker({
          autoclose: true,
          forceParse: false,
          clearBtn: true
        })
      }
      $(this).datepicker('setStartDate', window.start_day);
      $(this).datepicker('setEndDate', window.end_day);
      $(this).datepicker('update');
      $(this).prop('disabled', false);
      $(this).removeClass('loading');
      $(this).datepicker('show');
      $(this).on('change', copySl);                           // обработчик изменения сосотяния input -> копирование
      $(this).on('input', copySl);                           // обработчик изменения сосотяния input -> копирование
    })
  });


  // функция копирования дат проведения ТО
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

  //****************************************************************************

  // форматирование дат в соответствии с форматом MySQL(UNIX) перед отправкой формы
  $(document).ready(function () {
    $('#w0').submit(function () {
      var d = $('.to-month').data('datepicker').getFormattedDate('yyyy-mm-dd');
      $('.to-month').val(d);
      $('.to-date').each(function () {
        var dy = $(this).data('datepicker').getFormattedDate('yyyy-mm-dd');
        $(this).val(dy);
      })
    });
  });

  // Обработка подсказки ("Необходимо ввести месяц")==== 76859

  $(document).ready(function () {
    $('.to-month').mouseover(function () {
      if ($(this).val() == "") {
        $('.to-month').tooltip('enable');
        $('.to-month').tooltip('show');
      } else {
        $('.to-month').prop('title', '');
        $('.to-month-tooltip').tooltip('disable');
      }
    })
  });

  $(document).ready(function () {
    $('.admin-list').mouseover(function () {
      if ($(this).prop('disabled')) {
        $('.to-month').tooltip('enable');
        $('.to-month').tooltip('show');
      }
    })
  });

  $(document).ready(function () {
    $('.to-date').mouseover(function () {
      if ($(this).prop('disabled')) {
        if ($('.to-month').val() == '') {
          $('.to-month').prop('title', 'Необходимо выбрать месяц');
          $('.to-month').tooltip('enable');
          $('.to-month').tooltip('show');
        } else {
          var adminList = $(this).closest('tr').find('.admin-list');
          adminList.tooltip('enable');
          adminList.tooltip('show');
        }
      }
    })
  });


  $('.to-date').mouseleave(function () {
    $('.to-month').tooltip('hide');
    $('.to-month').tooltip('disable');
  });
  $('.to-date').mouseleave(function () {
    $('.admin-list').tooltip('hide');
    $('.admin-list').tooltip('disable');
  });
  $('.admin-list').mouseleave(function () {
    $('.to-month').tooltip('hide');
    $('.to-month').tooltip('disable');
  });


  //==================================================== 76859

</script>

