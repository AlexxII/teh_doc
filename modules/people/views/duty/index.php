<?php

use app\assets\BootstrapYearCalendarAsset;
use app\assets\BootstrapDatepickerAsset;

BootstrapYearCalendarAsset::register($this);
BootstrapDatepickerAsset::register($this);

$this->title = 'Календарь дежурств';
$this->params['breadcrumbs'][] = ['label' => 'Планировщик', 'url' => ['/scheduler']];
$this->params['breadcrumbs'][] = $this->title;


?>
<style>
  .calendar {
    overflow: visible;
  }
</style>


<div class="main-scheduler row">
  <div class="col-md-2 col-lg-2" style="margin-bottom: 15px;white-space: nowrap">
    <div id="info-panel">
      <span style="cursor: pointer" id="chbx-all"><h3>Сотрудники:</h3></span>
      <?php foreach ($models as $key => $model): ?>
        <div style="color: <?= $model->color_scheme ?>; font-weight: bold">
          <label class="labels" style="cursor: pointer">
            <input type="checkbox" class="users-checkboxes" id="<?= $model->id ?>" data-id="<?= $model->id ?>">
            <?= $model->username ?>
          </label>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="col-md-10 col-lg-10">
    <div id="full-calendar" data-provide="ec"></div>
  </div>
</div>

<script>

  $(document).ready(function () {
    getHolidays(startYear, initCalendar);
  });

  var startYear = new Date().getFullYear();
  var todayAll = new Date();
  todayAll.setHours(0, 0, 0, 0);
  var today = todayAll.getTime();
  var holidays;
  var month, firstDay, lastDay;

  $('.users-checkboxes').attr("disabled", true);

  function getHolidays(year, callback) {
    $.ajax({
      url: "holidays/holidays-array",
      type: 'GET',
      data: {
        year: year
      },
      success: function (dataSource) {
        if (dataSource != '') {
          holidays = JSON.parse(dataSource);
          holidays instanceof Array ? holidays : [];
        }
      },
      error: function (error) {
        console.log('Ошибка выполнения запроса по праздникам!');
      },
      complete: function () {
        if (typeof callback == 'function')
          callback();
      }
    });
  }

  function initCalendar() {
    $('#full-calendar').calendar({
      language: 'ru',
      enableContextMenu: true,
      enableRangeSelection: true,
      contextMenuItems: [
        {
          text: 'Обновить',
          click: editDuty
        },
        {
          text: 'Удалить',
          click: deleteDuty
        }
      ],
      dayContextMenu: function (e) {
        $(e.element).popover('hide');
      },
      clickMonth: function (e) {
        var date = e.date;
        var currentYear = e.date.getFullYear();
        clearCheckbox();
        if (e.event.ctrlKey) {
          var selectedMonth = e.date.getMonth() + 1;
          var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
          var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
          getDutyOnSelectedMonths(firstDay, lastDay);
        } else {
          clearBottom();
          month = e.date.getMonth() + 1;
          var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
          var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
          getDutyOnSelectedMonth(firstDay, lastDay);
        }
      },
      mouseOnDay: function (e) {
        if (e.events.length > 0) {
          var content = '';
          for (var i in e.events) {
            if ('hType' in e.events[i]) {
              return;
            }
            content += '<div class="event-tooltip-content">'
              + '<div class="event-name" style="color:' + e.events[i].color + ' ! important ">' + e.events[i].name + '</div>'
              + '<div class="event-location">' + e.events[i].location + '</div>'
              + '</div>';
          }
          $(e.element).popover({
            trigger: 'manual',
            container: 'body',
            html: true,
            content: content
          });
          $(e.element).popover('show');
        }
      },
      mouseOutDay: function (e) {
        if (e.events.length > 0) {
          $(e.element).popover('hide');
        }
      },
      customDayRenderer: function (element, date) {
        if (date.getTime() == today) {
          $(element).css('background-color', 'orange');
          $(element).css('color', 'white');
          $(element).css('border-radius', '15px');
        }
        if (contains(holidays, date.getTime() / 1000)) {
          $(element).css('font-weight', 'bold');
          $(element).css('font-size', '15px');
          $(element).css('color', 'red');
        }
      },
      selectRange: function (e) {
        createDuty(e);
      },
      yearChanged: function (e) {
        clearBottom();
        $(e.target).append('<div style="text-align:center"><img src="/lib/3.gif" /></div>');
        e.preventRendering = true;
        var currentYear = e.currentYear;
        getHolidays(currentYear, yearRender(currentYear));
      },
      renderEnd: function (e) {
        $('.users-checkboxes').removeAttr("disabled");
      }
    });
  }

  function clearBottom() {
    $('.labels').css('border-bottom', 'none')
  }

  function clearCheckbox() {
    $('.users-checkboxes').prop('checked', false);
  }

  function createDuty(e) {
    var sDate = e.startDate;
    var eDate = e.endDate;
    var sDateStr = sDate.getDate() + '.' + (sDate.getMonth() + 1) + '.' + sDate.getFullYear();
    var eDateStr = eDate.getDate() + '.' + (eDate.getMonth() + 1) + '.' + eDate.getFullYear();
    var day = 24 * 60 * 60 * 1000;
    var diff = ((e.endDate - e.startDate) / day) + 1;
    var c = $.confirm({
      content: function () {
        var self = this;
        return $.ajax({
          url: '/scheduler/duty/form',
          method: 'get',
          data: {
            startDate: sDateStr,
            endDate: eDateStr,
            diff: diff
          }
        }).fail(function () {
          self.setContentAppend('<div>Что-то пошло не так!</div>');
        });
      },
      contentLoaded: function (data, status, xhr) {
        this.setContentAppend('<div>' + data + '</div>');
      },
      type: 'blue',
      columnClass: 'medium',
      title: 'Добавить дежурство',
      buttons: {
        ok: {
          btnClass: 'btn-blue',
          text: 'Сохранить',
          action: function () {
            var msg = {};
            var dutyType = $('#duty-type').val();
            if (dutyType == '') {
              return;
            }
            msg.duration = $('#duration').val();  // field is hidden
            msg.user = $('#user').val();
            msg.dutyType = dutyType;
            msg.start = $('#start-date').val();
            msg.end = $('#end-date').val();
            saveDuty(msg, msg.user);
          }
        },
        cancel: {
          btnClass: 'btn-red',
          text: 'Отмена'
        }
      },
      onContentReady: function () {
        var self = this;
        this.buttons.ok.disable();
        this.$content.find('#duty-type').on('change', function (e) {
          if ($(this).val() != '') {
            self.buttons.ok.enable();
          } else {
            self.buttons.ok.disable();
          }
        });
      }
    })
  }

  function saveDuty(data, userId) {
    var csrf = $('meta[name=csrf-token]').attr("content");
    var currentYear = $('#full-calendar').data('calendar').getYear();
    $.ajax({
      url: '/scheduler/duty/save',
      method: 'post',
      data: {
        _csrf: csrf,
        msg: data
      }
    }).done(function (response) {
      $('#' + userId).prop('checked', true);
      $('#full-calendar').data('calendar').setYear(currentYear); // для перезагрузки
    }).fail(function () {
      console.log('Что-то пошло не так!');
    });
  }

  function yearRender(year) {
    var csrf = $('meta[name=csrf-token]').attr("content");
    var users = [1];
    $('.users-checkboxes').each(function (e) {
      if ($(this).is(':checked')) {
        users.push($(this).data('id'));
      }
    });
    data = [];
    if (users.length > 0) {
      data = [];
    }
    $.ajax({
      url: "/scheduler/duty/duty-data",
      type: 'POST',
      data: {
        _csrf: csrf,
        year: year,
        users: users
      },
      success: function (dataSource) {
        if (dataSource.length > 0) {
          var data = JSON.parse(dataSource);
          data instanceof Array ? data : [];
          if (data instanceof Array) {
            data.forEach(function (el, index, theArray) {
              theArray[index].startDate = new Date(el.sYear, el.sMonth, el.sDay);
              theArray[index].endDate = new Date(el.eYear, el.eMonth, el.eDay);
            });
          } else {
            data = [];
          }
        }
        $('#full-calendar').data('calendar').setDataSource(data);
      }
    });
  }

  function editDuty(event) {
    var currentYear = $('#full-calendar').data('calendar').getYear();
    var dutyId = event.id;
    var c = $.confirm({
      content: function () {
        var self = this;
        return $.ajax({
          url: '/scheduler/duty/update-form',
          method: 'get',
          data: {
            id: dutyId
          }
        }).fail(function () {
          self.setContentAppend('<div>Что-то пошло не так!</div>');
        });
      },
      contentLoaded: function (data, status, xhr) {
        this.setContentAppend('<div>' + data + '</div>');
      },
      type: 'blue',
      columnClass: 'medium',
      title: 'Обновить отпуск',
      buttons: {
        ok: {
          btnClass: 'btn-blue',
          text: 'Сохранить',
          action: function () {
            var msg = {};
            var title = $('#event-title').val();
            if (title == '') {
              return;
            }
            msg.id = dutyId;
            msg.user = $('#user').val();
            msg.start = $('#start-date').val();
            msg.end = $('#end-date').val();
            msg.duration = $('#duration').val();
            msg.dutyType = $('#duty-type').val();
            updateDuty(msg, msg.user);
          }
        },
        cancel: {
          btnClass: 'btn-red',
          text: 'Отмена'
        }
      }
    })
  }

  function updateDuty(data, userId) {
    var csrf = $('meta[name=csrf-token]').attr("content");
    var currentYear = $('#full-calendar').data('calendar').getYear();
    $.ajax({
      url: '/scheduler/duty/update-duty',
      method: 'post',
      data: {
        _csrf: csrf,
        msg: data
      }
    }).done(function (response) {
      $('#' + userId).prop('checked', true);
      $('#full-calendar').data('calendar').setYear(currentYear); // для перезагрузки
    }).fail(function () {
      console.log('Что-то пошло не так!');
    });
  }

  function deleteDuty(event) {
    var url = '/scheduler/duty/delete-duty';
    var eventId = event.id;
    jc = $.confirm({
      icon: 'fa fa-question',
      title: 'Вы уверены?',
      content: 'Вы действительно хотите удалить отпуск?',
      type: 'red',
      closeIcon: false,
      autoClose: 'cancel|9000',
      buttons: {
        ok: {
          btnClass: 'btn-danger',
          action: function () {
            jc.close();
            deleteProcess(url, eventId);
          }
        },
        cancel: {
          action: function () {
            return;
          }
        }
      }
    });
  }

  function deleteProcess(url, id) {
    var csrf = $('meta[name=csrf-token]').attr("content");
    var currentYear = $('#full-calendar').data('calendar').getYear();
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
      data: {
        id: id,
        _csrf: csrf
      }
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
                $('#full-calendar').data('calendar').setYear(currentYear);
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

  function contains(arr, elem) {
    if (arr) {
      for (var i = 0; i < arr.length; i++) {
        if (arr[i] === elem) {
          return true;
        }
      }
      return false;
    }
    return false;
  }

  function getDutyOnSelectedMonth(firstDay, lastDay) {
    var csrf = $('meta[name=csrf-token]').attr("content");
    if (firstDay.getDay() != 1) {
      firstDay = moment(firstDay).subtract(6, 'days');
    } else {
      firstDay = moment(firstDay);
    }
    if (lastDay.getDay() != 0) {
      lastDay = moment(lastDay).add(6, 'days');
    } else {
      lastDay = moment(lastDay);
    }
    $.ajax({
      url: "/scheduler/duty/month-duty-data",
      type: 'POST',
      data: {
        _csrf: csrf,
        firstDay: firstDay.format('YYYY-MM-DD'),
        lastDay: lastDay.format('YYYY-MM-DD')
      },
      success: function (dataSource) {
        if (dataSource != '') {
          data = JSON.parse(dataSource);
          data instanceof Array ? data : [];
          if (data instanceof Array) {
            data.forEach(function (el, index, theArray) {
              theArray[index].startDate = new Date(el.sYear, el.sMonth, el.sDay);
              theArray[index].endDate = new Date(el.eYear, el.eMonth, el.eDay);
              $('#' + el.user).closest('label').css({"border-bottom": "2px solid black"})
            });
          } else {
            $('#full-calendar').data('calendar').setDataSource(data);
          }
        }
        $('#full-calendar').data('calendar').setDataSource(data);
      }
    });
  }

  var data;

  function getDutyOnSelectedMonths(firstDay, lastDay) {
    var csrf = $('meta[name=csrf-token]').attr("content");
    if (firstDay.getDay() != 1) {
      firstDay = moment(firstDay).subtract(6, 'days');
    } else {
      firstDay = moment(firstDay);
    }
    if (lastDay.getDay() != 0) {
      lastDay = moment(lastDay).add(6, 'days');
    } else {
      lastDay = moment(lastDay);
    }
    $.ajax({
      url: "/scheduler/duty/month-duty-data",
      type: 'POST',
      data: {
        _csrf: csrf,
        firstDay: firstDay.format('YYYY-MM-DD'),
        lastDay: lastDay.format('YYYY-MM-DD')
      },
      success: function (dataSource) {
        if (dataSource != '') {
          var parseData = JSON.parse(dataSource);
          data = data.concat(parseData);
          data instanceof Array ? data : [];
          if (data instanceof Array) {
            data.forEach(function (el, index, theArray) {
              theArray[index].startDate = new Date(el.sYear, el.sMonth, el.sDay);
              theArray[index].endDate = new Date(el.eYear, el.eMonth, el.eDay);
              $('#' + el.user).closest('label').css({"border-bottom": "2px solid black"})
            });
          } else {
            $('#full-calendar').data('calendar').setDataSource(data);
          }
        }
        $('#full-calendar').data('calendar').setDataSource(data);
      }
    });
  }

  $(document).ready(function () {

    $('.users-checkboxes').click('on', function (e) {
      clearBottom();
      checkProcess();
    });

    $('#chbx-all').click(function (e) {
      clearBottom();
      var boxes = $("input:checkbox:not(:checked)");
      if (boxes.length > 0) {
        $('.users-checkboxes').prop('checked', true);
        checkProcess()
      } else {
        $('.users-checkboxes').prop('checked', false);
        data = [];
        $('#full-calendar').data('calendar').setDataSource(data);
      }
    });

  });

  function checkProcess() {
    var csrf = $('meta[name=csrf-token]').attr("content");
    var currentYear = $('#full-calendar').data('calendar').getYear();
    $('.months-container').html('<div style="text-align:center"><img src="/lib/3.gif" /></div>');
    var users = [];
    $('.users-checkboxes').each(function (e) {
      if ($(this).is(':checked')) {
        users.push($(this).data('id'));
      }
    });
    if (users.length > 0) {
      $.ajax({
        url: "/scheduler/duty/duty-data",
        type: 'POST',
        data: {
          _csrf: csrf,
          year: currentYear,
          users: users
        },
        success: function (dataSource) {
          if (dataSource != '') {
            var data = JSON.parse(dataSource);
            data instanceof Array ? data : [];
            if (data instanceof Array) {
              data.forEach(function (el, index, theArray) {
                theArray[index].startDate = new Date(el.sYear, el.sMonth, el.sDay);
                theArray[index].endDate = new Date(el.eYear, el.eMonth, el.eDay);
              });
            } else {
              data = [];
            }
          }
          $('#full-calendar').data('calendar').setDataSource(data);
        }
      });
    } else {
      data = [];
      $('#full-calendar').data('calendar').setDataSource(data);
    }
  }

</script>