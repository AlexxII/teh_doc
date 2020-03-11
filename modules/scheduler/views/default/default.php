<?php

use app\modules\scheduler\assets\SchedulerAppAsset;

use app\assets\NotyAsset;
use app\assets\BootstrapDatepickerAsset;
use app\assets\fullcalendar\CalendarDaygridAsset;
use app\assets\fullcalendar\CalendarTimegridAsset;
use app\assets\fullcalendar\CalendarInteractionAsset;
use app\assets\fullcalendar\CalendarBootstrapAsset;
use app\assets\fullcalendar\CalendarListAsset;
use app\assets\BootstrapYearCalendarAsset;
use app\assets\fullcalendar\CalendarCoreAsset;

SchedulerAppAsset::register($this);

NotyAsset::register($this);
CalendarCoreAsset::register($this);
CalendarDaygridAsset::register($this);
CalendarTimegridAsset::register($this);
CalendarInteractionAsset::register($this);
CalendarBootstrapAsset::register($this);
CalendarListAsset::register($this);
BootstrapDatepickerAsset::register($this);
BootstrapYearCalendarAsset::register($this);

?>

<style>
  #add-vks {
    margin: 0 !important;
  }
  .fc-day-number {
    font-size: 17px;
    text-decoration: none !important;
    font-weight: 900;
  }
  .fc-week-number {
    text-decoration: none !important;
  }
</style>

<div class="main-wrap">
  <div class="main-scheduler">
    <div id="calendar">
    </div>
  </div>
</div>

<script>

  var calendar, calendarView, calendarTitle, fullYear, addEventDialog, eventView;
  $(document).ready(function () {

    $('#push-it').removeClass('hidden');
    $('#app-control').removeClass('hidden');

    initLeftCustomData('/scheduler/menu/left-side-data');
    initRightCustomData('/scheduler/menu/right-side-data');
    initLeftMenu('/scheduler/menu/left-side');
    initAppConfig('/scheduler/menu/app-config');

    var csrf = $('meta[name=csrf-token]').attr("content");

    var fcSources = {
      vks: {
        id: '1111',
        url: '/scheduler/events/vks-data',
        method: 'POST',
        extraParams: {
          _csrf: csrf
        },
        failure: function () {
          console.log('Внимание! Ошибка получения сеансов ВКС!');
        },
        color: 'green',   // a non-ajax option
        textColor: 'white' // a non-ajax optio
      },
      to: {
        url: '/scheduler/events/to-data',
        method: 'POST',
        extraParams: {
          _csrf: csrf
        },
        failure: function () {
          console.log('Внимание! Ошибка получения графиков ТО!');
        },
        color: 'green',   // a non-ajax option
        textColor: 'white' // a non-ajax optio
      },
      events: {
        id: '222222',
        url: '/scheduler/events/events-data',
        method: 'POST',
        extraParams: {
          _csrf: csrf
        },
        failure: function () {
          console.log('Внимание! Ошибка получения событий!');
        },
        color: 'green',   // a non-ajax option
        textColor: 'white' // a non-ajax optio
      },
      holidays: {
        id: '333333',
        url: '/scheduler/events/holidays-data',
        method: 'POST',
        extraParams: {
          _csrf: csrf
        },
        failure: function () {
          console.log('Внимание! Ошибка получения праздников!');
        },
        textColor: 'white'
      },
      waste: {
        id: '999999',
        url: '/scheduler/events/waste-events',
        method: 'POST',
        extraParams: {
          _csrf: csrf
        },
        failure: function () {
          console.log('Внимание! Ошибка получения отработанных событий!');
        },
        textColor: 'white'
      }
    };

    var calendarEl = document.getElementById('calendar');

    var editBtn = '<div id="event-edit-btn" onClick="editEvent()">' +
      '<svg width="20" height="20" viewBox="2 2 22 22">' +
      '<path fill="none" d="M0 0h24v24H0V0z"></path>' +
      '<path d="M14.06 9.02l.92.92L5.92 19H5v-.92l9.06-9.06M17.66 3c-.25 0-.51.1-.7.29l-1.83 1.83 3.75 3.75' +
      '1.83-1.83c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.2-.2-.45-.29-.71-.29zm-3.6 3.19L3 17.25V21h3.75L17.81 9.94l-3.75-3.75z"></path>' +
      '</svg></div>';

    var deleteBtn = '<div id="event-delete-btn" title="Удалить к чертям" onClick="deleteEvent()">' +
      '<svg width="20" height="20" viewBox="2 2 22 22">' +
      '<path d="M15 4V3H9v1H4v2h1v13c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6h1V4h-5zm2 15H7V6h10v13z"></path>' +
      '<path d="M9 8h2v9H9zm4 0h2v9h-2z"></path>' +
      '</svg>' +
      '</div>';

    calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
      locale: 'ru',
      height: function () {
        return $(window).height() - 55;
      },
      navLinks: true,
      navLinkWeekClick: function (weekStart, jsEvent) {
        calendar.gotoDate(weekStart);
        calendar.changeView('timeGridWeek');
      },
      navLinkDayClick: function (date, jsEvent) {
        calendar.gotoDate(date);
        calendar.changeView('timeGridDay');
      },
      weekNumbers: true,
      weekNumbersWithinDays: true,
      selectable: true,
      nowIndicator: true,
      slotDuration: '00:15:00',
      minTime: '06:00:00',
      eventLimit: true,
      eventSources: [
        fcSources.vks,
        fcSources.to,
        fcSources.events,
        fcSources.holidays
      ],
      defaultView: 'dayGridMonth',
      header: false,
      businessHours: [
        {
          daysOfWeek: [1, 2, 3, 4], // Monday, Tuesday, Wednesday, Thursday
          startTime: '09:00',
          endTime: '18:15'
        },
        {
          daysOfWeek: [5], // Friday
          startTime: '09:00',
          endTime: '17:00'
        }
      ],
      showNonCurrentDates: true,

      //========================= rendering ==================================
      eventRender: function (info) {
        var ntoday = new Date();
        if (info.event._def.rendering == 'background') {
          var type = info.event._def.extendedProps.holiday_type;
          if (type == 0) {
            $(info.el).css('background-color', '#E0FFFF');
          } else if (type == 1 || type == 2) {
            $(info.el).css('background-color', '#FFA07A');
          } else {
            $(info.el).css('background-color', '#ff5a35');
          }
        }
      },
      dayRender: function (info) {
        var title = info.view.title;
        $("#left-custom-data-ex").html(title);
      },
      //========================= actions =====================================
      select: function (info) {
        addEventDialog = $.confirm({
          content: function () {
            var self = this;
            return $.ajax({
              url: '/scheduler/events/event-form',
              method: 'get',
              data: {
                startDate: info.startStr,
                endDate: info.endStr
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
          closeIcon: true,
          title: 'Добавить событие',
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
                msg.title = $('#event-title').val();
                msg.start = $('#start-date').val();
                msg.end = $('#end-date').val();
                msg.desc = $('#event-description').val();
                msg.color = $('#colorpicker').val();
                var q = saveEvent(msg);
              }
            }
          },
          onContentReady: function () {
            var self = this;
            this.buttons.ok.disable();
            this.$content.find('#event-title').on('keyup mouseclick', function () {
              if ($(this).val() != '') {
                self.buttons.ok.enable();
              } else {
                self.buttons.ok.disable();
              }
            });
          }
        })
      },

      //========================= events =======================================
      eventClick: function (info) {
        info.jsEvent.preventDefault();
        if (info.event.extendedProps) {
          var url = info.event.url;
          var urlText = info.event.extendedProps.exUrl;
          var ar = urlText.split('/');
          var req = ar[0];
          var ident = ar[1];
          eventView = $.confirm({
            content: function () {
              var self = this;
              return $.ajax({
                url: '/scheduler/events/' + req,
                method: 'get',
                data: {
                  i: ident
                }
              }).fail(function () {
                self.setContentAppend('<div>Что-то пошло не так!</div>');
              });
            },
            contentLoaded: function (data, status, xhr) {
              this.setContentAppend('<div>' + data + '</div>');
              if (req == 'sub-event') {
                $('.jconfirm-box').append(editBtn);
                $('.jconfirm-box').append(deleteBtn);
                var eventInfo = '<span id="event-info" data-event-id="' + ident + '">';
                $('.jconfirm-box').append(eventInfo);
                $('.jconfirm-box button').css('padding', '1px 7px');
              }
            },
            type: 'blue',
            columnClass: 'medium',
            title: 'Подробности',
            closeIcon: true,
            buttons: {
              ok: {
                btnClass: 'btn-blue',
                text: 'К СОБЫТИЮ',
                action: function () {
                  window.open(url);
                }
              },
              done: {
                btnClass: 'btn-green',
                text: '<svg width="20" height="24" viewBox="0 0 38 40" style="fill:#fff">' +
                  '<path d="M1.36,17.427c0,0,7.311-0.122,10.844,8.163c0,0,15.474-22.175,31.435-18.885c0,0-17.789,7.067-32.045,31.922L1.36,17.427z"/>' +
                  '</svg>',
                action: function () {
                  console.log('Событие исполнено!');
                }
              }
            },
            onContentReady: function () {
              var self = this;
              if (req == 'sub-event') {
                this.buttons.ok.hide();
              } else {
                this.buttons.done.hide();
              }
              this.$content.find('#event-title').on('keyup mouseclick', function () {
                if ($(this).val() != '') {
                  self.buttons.ok.enable();
                } else {
                  self.buttons.ok.disable();
                }
              });
            }
          })
        }
      }
    });
    calendar.render();
    window.ee = calendar.getEventSources();
    $('#left-custom-data-ex').html(calendar.view.title);
  });

  // загрузка календаря - год
  $.ajax({
    url: '/scheduler/full-year',
    method: 'get'
  }).done(function (response) {
    fullYear = response.data.data;
  }).fail(function (response) {
    console.log('Ошибка загрузки годового календаря!');
  });

  function calendarShow(e) {
    var id = $(e).attr('id');
    $('#' + id).datepicker({}).data('datepicker');
    $('#' + id).datepicker('show');
    $('#' + id).datepicker()
      .on('hide', function (e) {
        var momentDate = moment(e.date);
        var fDate = momentDate.format('Y-MM-DD');
        calendar.gotoDate(fDate);
        navCalendar.close();
      });
  }

  $(document).on('click', '#add-vks', function (e) {
    var date = $('#start-date').val();
    var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
    var vksDate = date.replace(pattern, '$3-$2-$1');
    var url = "/vks/sessions/create-up-session-ajax?vks_date=" + vksDate;
    $.confirm({
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
              sendFormData(url, $form, function () {
                  calendar.refetchEvents()
                }
              );
            }
          }
        },
        cancel: {
          text: 'НАЗАД'
        }
      }
    });
    addEventDialog.close();
  });

  function sendFormData(url, form, callback) {
    $.ajax({
      type: 'POST',
      url: url,
      dataType: 'json',
      data: form.serialize(),
      success: function (response) {
        callback();
      },
      error: function (response) {
        console.log(response.data.data);
      }
    });
  }

  $(document).on('click', '#view-selector li', function (e) {
    e.preventDefault();
    var viewType = $(this).attr('value');
    if (viewType == 'year') {
      if ($('#full-calendar').length) return;
      calendarView = $('#calendar');
      calendarTitle = calendar.view.title;
      $('.main-scheduler').html(fullYear);
      $('#left-custom-data-ex').html('');
    } else if (viewType == 'holidays') {
      var holidays = calendar.getEventSourceById('333333');
      if (holidays == null) {
        calendar.addEventSource(ee[3]);
        $('#holidays-view .checkk').show();
        return;
      }
      holidays.remove();
      console.log($('#holidays-view .checkk'));
      $('#holidays-view .checkk').hide();
      return;
    } else if (viewType == 'waste') {
      var wasteEvents = calendar.getEventSourceById('333333');
      wasteEvents.remove();
      return;
    } else {
      $('#left-custom-data-ex').html(calendarTitle);
      $('.main-scheduler').html(calendarView);
      calendar.changeView(viewType);
      calendar.refetchEvents();
    }
    $('#view-menu-btn > #title').text($(this).text());
    $('#view-menu-btn > #title').attr('title', $(this).text());
  });

  $(document).on('click', '#previous-date', function (e) {
    e.preventDefault();
    calendar.prev();
  });

  $(document).on('click', '#next-date', function (e) {
    e.preventDefault();
    calendar.next();
  });

  $(document).on('click', '#today-btn', function (e) {
    e.preventDefault();
    calendar.today();
  });

  /* скролл над календарем (вид - месяц) */
  var timeStamp = new Date().getTime();
  $(document).on('wheel', '.fc-dayGridMonth-view', function (e) {
    if (e.ctrlKey == true) return;
    e.preventDefault();
    var timeNow = new Date().getTime();
    if (timeNow - timeStamp < 200) {          //задержка для прокрутки (сдвиг на один месяц)
      timeStamp = timeNow;
      return;
    } else {
      timeStamp = timeNow;
      if (e.originalEvent.deltaY < 0) {
        calendar.prev();
      }
      else {
        calendar.next();
      }
    }
  });

  /* нажатие на неделю */
  $(document).on('click', '.fc-week-number', function (e) {
    $('#view-menu-btn > #title').text('Неделя');
  });

  /* нажатие на день */
  $(document).on('click', '.fc-day-number', function (e) {
    $('#view-menu-btn > #title').text('День');
  });
  $(document).on('click', '.fc-day-number, .fc-list-heading-main', function (e) {
    $('#view-menu-btn > #title').text('День');
  });

</script>