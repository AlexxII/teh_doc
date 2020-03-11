function saveEvent(data) {
  var csrf = $('meta[name=csrf-token]').attr("content");
  $.ajax({
    url: '/scheduler/events/save-event',
    method: 'post',
    data: {
      _csrf: csrf,
      msg: data
    }
  }).done(function (response) {
    calendar.refetchEvents();
  }).fail(function () {
    console.log('Что-то пошло не так!');
  });
}

function loadProductionCalendar() {
  $.ajax({
    url: '/scheduler/production',
    method: 'get',
  }).done(function (response) {
    return response.data.data;
  }).fail(function (response) {
    console.log('Что-то пошло не так!');
  });
}

function editEvent() {
  var url = '/scheduler/events/update-event';
  var id = $('#event-info').data('eventId');
  eventView.close();
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
          updateEvent(msg, id);
        }
      },
      cancel: {
        btnClass: 'btn-red',
        text: 'Отмена'
      }
    },
    onContentReady: function () {
      var self = this;
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

function updateEvent(msg, id) {
  var csrf = $('meta[name=csrf-token]').attr("content");
  $.ajax({
    url: '/scheduler/events/save-updated-event',
    method: 'post',
    data: {
      _csrf: csrf,
      id: id,
      msg: msg
    }
  }).done(function (response) {
    calendar.refetchEvents();
  }).fail(function () {
    console.log('Что-то пошло не так!');
  });
}

function deleteEvent() {
  var csrf = $('meta[name=csrf-token]').attr("content");
  var url = '/scheduler/events/delete-event';
  var id = $('#event-info').data('eventId');
  eventView.close();
  jc = $.confirm({
    icon: 'fa fa-question',
    title: 'Вы уверены?',
    content: 'Если событие прошло, то отметьте его как прошедшее. При ошибочном создании события - удалите его. Удалить событие?',
    type: 'red',
    closeIcon: false,
    autoClose: 'cancel|9000',
    buttons: {
      ok: {
        btnClass: 'btn-danger',
        action: function () {
          jc.close();
          deleteProcess(url, id);
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
              calendar.refetchEvents();
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
