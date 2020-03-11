<div id="full-calendar"></div>

<script>
  $(document).ready(function () {
    var startYear = new Date().getFullYear();
    var todayAll = new Date();
    todayAll.setHours(0, 0, 0, 0);
    var today = todayAll.getTime();
    var holidays, importantEvents;

    $('[data-toggle="tooltip"]').tooltip();

    function getHolidays(year, callback) {
      // getImportantEvents();
      $.ajax({
        url: "/scheduler/holidays/holidays-array",
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

    function getImportantEvents() {
      $.ajax({
        url: "/scheduler/full-year/important-events",
        type: 'GET',
        data: {
          // year: year
        },
        success: function (dataSource) {
          if (dataSource != '') {
            importantEvents = JSON.parse(dataSource);
            importantEvents instanceof Array ? importantEvents : [];
          }
        },
        error: function (error) {
          console.log('Ошибка выполнения запроса по праздникам!');
        },
        complete: function () {
          // if (typeof callback == 'function')
          // callback();
        }
      });
    }

    getHolidays(startYear, initFullCalendar);

    function initFullCalendar() {
      $('#full-calendar').calendar({
        language: 'ru',
        style: 'custom',
        enableContextMenu: true,
        enableRangeSelection: true,
        contextMenuItems: [
          {
            text: 'Инфо',
            click: viewFullYearInfo
          },
          {
            text: 'Обновить',
            click: editFullYearEvent
          },
          {
            text: 'Удалить',
            click: deleteFullYearEvent
          }
        ],
        dayContextMenu: function (e) {
          $(e.element).popover('hide');
        },
        clickMonth: function (e) {
          calendar.gotoDate(e.date);
          $('.main-scheduler').html(calendarView);
          $('#view-menu-btn > #title').text('Месяц');
          $('#left-custom-data-ex').html(calendar.view.title);
          calendar.changeView('dayGridMonth');
          calendar.refetchEvents();
        },
        mouseOnDay: function (e) {
          if (e.events.length > 0) {
            var content = '';
            for (var i in e.events) {
              content += '<div class="event-tooltip-content">'
                + '<div class="event-name" style="color: #ff5a35">' + e.events[i].name + '</div>'
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
          e.element.tooltip('show');
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
            // $(element).attr('title', 'Возьми МЕНЯ!!!!!');
            // $(element).attr('data-toggle', 'tooltip');
            // $(element).attr('data-placement', 'top');
            // $(element).tooltip('toggle');
          }
          if (contains(importantEvents, date.getTime() / 1000)) {
            $(element).css('font-weight', 'bold');
            $(element).css('font-size', '14px');
            $(element).css('color', 'green');
            // $(element).attr('title', 'Важное говно!');
            // $(element).attr('data-toggle', 'tooltip');
            // $(element).attr('data-placement', 'top');
            // $(element).tooltip('toggle');
          }
        },
        selectRange: function (e) {
          var day = 24 * 60 * 60 * 1000;
          var year = $('#full-calendar').data('calendar').getYear();
          // console.log
          // -(((e.endDate - e.startDate) / day) + 1);
          createFullYearEvent(e, year);
        },
        mouseOutDay: function (e) {
          if (e.events.length > 0) {
            $(e.element).popover('hide');
          }
        },
        customDataSourceRenderer: function (elt, date, events) {
          var weight = 0;
          if (events.length == 1) {
            weight = 4;
          }
          else if (events.length <= 3) {
            weight = 2;
          }
          else {
            elt.parent().css('box-shadow', 'inset 0 -4px 0 0 black');
          }
          if (weight > 0) {
            var boxShadow = '';
            for (var i = 0; i < events.length; i++) {
              if (boxShadow != '') {
                boxShadow += ",";
              }
              boxShadow += 'inset 0 -' + (parseInt(i) + 1) * weight + 'px 0 0 ' + events[i].color;
              if (events[i].hType == '3') {
                elt.parent().css('font-weight', 'bold');
                elt.parent().css('font-size', '14px');
                elt.parent().css('color', 'red');
              }
            }
            elt.parent().css('box-shadow', boxShadow);
          }
        },
        yearChanged: function (e) {
          $(e.target).append('<div style="text-align:center"><img src="/lib/3.gif" /></div>');
          e.preventRendering = true;
          var currentYear = e.currentYear;
          getHolidays(currentYear, yearRender(currentYear));
        }
      });
    }
  });

  function yearRender(year) {
    var csrf = $('meta[name=csrf-token]').attr("content");
    $.ajax({
      url: "/scheduler/full-year/year-events",
      type: 'GET',
      data: {
        // _csrf: csrf,
        year: year
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


  function createFullYearEvent(e, year) {
    var sDate = e.startDate;
    var eDate = e.endDate;
    var sDateStr = sDate.getDate() + '.' + (sDate.getMonth() + 1) + '.' + sDate.getFullYear();
    var eDateStr = eDate.getDate() + '.' + (eDate.getMonth() + 1) + '.' + eDate.getFullYear();

    var c = $.confirm({
      content: function () {
        var self = this;
        return $.ajax({
          url: '/scheduler/events/event-form',
          method: 'get',
          data: {
            startDate: sDateStr,
            endDate: eDateStr
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
            var q = saveFullYearEvent(msg, year);
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

  function saveFullYearEvent(data, year) {
    var csrf = $('meta[name=csrf-token]').attr("content");
    $.ajax({
      url: '/scheduler/full-year/save-event',
      method: 'post',
      data: {
        _csrf: csrf,
        msg: data
      }
    }).done(function (response) {
      $('#full-calendar').data('calendar').setYear(year);
    }).fail(function () {
      console.log('Что-то пошло не так!');
    });
  }

  function viewFullYearInfo(event) {
    var year = $('#full-calendar').data('calendar').getYear();
    var id = event.id;
    var req = event.req;
    var url = '/scheduler/full-year/' + req;
    c = $.confirm({
      content: function () {
        var self = this;
        return $.ajax({
          url: url,
          method: 'get',
          data: {
            id: id
          }
        }).done(function (response) {
          // console.log(response);
        }).fail(function () {
          self.setContentAppend('<div>Что-то пошло не так!</div>');
        });
      },
      contentLoaded: function (data, status, xhr) {
        this.setContentAppend('<div>' + data + '</div>');
      },
      type: 'blue',
      columnClass: 'medium',
      title: 'Подробности',
      buttons: {
        ok: {
          btnClass: 'btn-blue',
          text: 'К СОБЫТИЮ',
          action: function () {
            window.open(url);
          }
        },
        edit: {
          btnClass: 'btn-blue',
          text: 'Обновить',
          action: function () {
            editFullYearEvent(event);
          }
        },
        del: {
          btnClass: 'btn-red',
          text: 'Удалить',
          action: function () {
            deleteFullYearEvent(event)
          }
        },
        cancel: {
          text: 'НАЗАД'
        }
      },
      onContentReady: function () {
        var self = this;
        if (req == 'sub-event') {
          this.buttons.ok.hide();
        } else {
          this.buttons.del.hide();
          this.buttons.edit.hide();
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

  function editFullYearEvent(event) {
    var year = $('#full-calendar').data('calendar').getYear();
    var url = '/scheduler/full-year/update-event';
    var id = event.id;
    $.confirm({
      content: function () {
        var self = this;
        return $.ajax({
          url: url,
          method: 'get',
          data: {
            id: id
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
      title: 'Обновить событие',
      buttons: {
        ok: {
          btnClass: 'btn-blue',
          text: 'Обновить',
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
            updateFullYearEvent(msg, id, year);
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

  function updateFullYearEvent(msg, id, year) {
    var csrf = $('meta[name=csrf-token]').attr("content");
    $.ajax({
      url: '/scheduler/full-year/save-updated-event',
      method: 'post',
      data: {
        _csrf: csrf,
        id: id,
        msg: msg
      }
    }).done(function (response) {
      $('#full-calendar').data('calendar').setYear(year);
    }).fail(function () {
      console.log('Что-то пошло не так!');
    });
  }

  function deleteFullYearEvent(event) {
    var csrf = $('meta[name=csrf-token]').attr("content");
    var url = '/scheduler/full-year/delete-event';
    var id = event.id;
    var year = $('#full-calendar').data('calendar').getYear();
    jc = $.confirm({
      icon: 'fa fa-question',
      title: 'Вы уверены?',
      content: 'Вы действительно хотите удалить событие?',
      type: 'red',
      closeIcon: false,
      autoClose: 'cancel|9000',
      buttons: {
        ok: {
          btnClass: 'btn-danger',
          action: function () {
            jc.close();
            deleteFullYearProcess(url, id, year);
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

  function deleteFullYearProcess(url, id, year) {
    var csrf = $('meta[name=csrf-token]').attr("content");
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
        event: id,
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
                $('#full-calendar').data('calendar').setYear(year);
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
                $('#full-calendar').data('calendar').setYear(year);
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

</script>