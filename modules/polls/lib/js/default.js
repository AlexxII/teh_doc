var pollTable;

$(document).ready(function (e) {

  NProgress.configure({showSpinner: false});
  NProgress.start();
  // setTimeout(() => NProgress.done(), 1300);

  initLeftMenu('/polls/menu/left-side');
  initAppConfig('/polls/menu/app-config');

  controlCallback = function () {
    return;
  };
  // процедуры возврата из второстепенного контента
  returnCallback = function () {
    pollTable.ajax.reload();
  };

  // ************************* Работа таблицы **************************************

  var editBtn = '<a href="#" id="edit" class="fa fa-edit" style="padding-right: 5px" title="Обновить"></a>';
  var infoBtn = '<a href="#" id="view" class="fa fa-info" ' +
    ' title="Подробности" data-url="/to/month-schedule/view" data-back-url="/to" style="padding-left: 5px"></a>';

  pollTable = $('#poll-main-table').DataTable({
    processing: true,
    responsive: true,
    ajax: {
      'url': '/polls/polls/index'
    },
    columns: [
      {'data': 'id'},
      {'data': 'year'},
      {'data': 'code'},
      {'data': 'title'},
      {'data': 'start_date'},
      {'data': 'end_date'},
      {'data': 'sample'},
      {'data': ''},
      {'data': ''}
    ],
    searching: false,
    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
      var date = aData.start_date;
      var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
      $('td:nth-child(6)', nRow).html(editBtn + infoBtn);
    },
    orderFixed: [[1, 'desc']],
    order: [[1, 'desc']],
    rowGroup: {
      dataSrc: 'year'
    },
    columnDefs: [
      {
        'targets': -2,                    // предпоследний столбец
        'orderable': false,
        'data': null,
        'width': '70px',
        'defaultContent': ''
      }, {
        'targets': -1,                    // последний столбец
        'orderable': false,
        'className': 'select-checkbox',
        'defaultContent': ''
      }, {
        'targets': 0,
        'data': null,
        'visible': false
      }, {
        'targets': 2,
        'render': function (data, type, row) {
          return '<span class="poll-in" data-id="' + row['id'] + '"><strong>' + row['code'] + '</strong></span>';
        }
      }, {
        'targets': 3,
        'render': function (data, type, row) {
          return '<span class="poll-in" data-id="' + row['id'] + '"><strong>' + row['title'] + '</strong></span>';
        }
      }, {
        'targets': 5,
        'visible': false
      }, {
        'targets': 4,
        'render': function (data, type, row) {
          var pattern = /(\d{4})\-(\d{2})\-(\d{2})/;

          return row['start_date'].replace(pattern, '$3.$2.$1') +
            "<br> " +
            row['end_date'].replace(pattern, '$3.$2.$1');
        }
      }
    ],
    select: {
      style: 'single',
      selector: 'td:last-child',
    },
    language: {
      url: '/lib/ru.json'
    },
  });

  pollTable.on('order.dt search.dt', function () {
    pollTable.column(1, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
      cell.innerHTML = i + 1;
    });
  }).draw();

  // Работа таблицы -> событие выделения и снятия выделения

  pollTable.on('select', function (e, dt, type, indexes) {
    if (type === 'row') {
      $('#delete-wrap').show();
      $('#batch-input').show();
      $('#construct-wrap').show();
    }
  });
  pollTable.on('deselect', function (e, dt, type, indexes) {
    if (type === 'row') {
      if (pollTable.rows({selected: true}).count() > 0) return;
      $('#delete-wrap').hide();
      $('#batch-input').hide();
      $('#construct-wrap').hide();
    }
  });

  // Работа таблицы -> перерисовка или изменение размера страницы

  pollTable.on('length.dt', function (e, settings, len) {
    pollTable.rows().deselect();
    $('#delete-wrap').hide();
    $('#batch-input').hide();
    $('#construct-wrap').hide();
  });

  pollTable.on('draw.dt', function (e, settings, len) {
    pollTable.rows().deselect();
    $('#delete-wrap').hide();
    $('#batch-input').hide();
    $('#construct-wrap').hide();
    NProgress.done();
  });

  $('#delete-wrap').click(function (event) {
    event.preventDefault();
    var csrf = $('meta[name=csrf-token]').attr("content");
    var url = "/polls/polls/delete";
    if ($(this).attr('disabled')) {
      return;
    }
    jc = $.confirm({
      icon: 'fa fa-question',
      title: 'Вы уверены?',
      content: 'Вы действительно хотите удалить выделенное?',
      type: 'red',
      closeIcon: false,
      autoClose: 'cancel|9000',
      buttons: {
        ok: {
          btnClass: 'btn-danger',
          action: function () {
            jc.close();
            deleteProcess(url, pollTable, csrf);
          }
        },
        cancel: {
          action: function () {
            return;
          }
        }
      }
    });
  });

  pollTable.on('click', '#edit', function (e) {
    e.preventDefault();
    var data = pollTable.row($(this).parents('tr')).data();
    var url = "/polls/polls/update-poll?id=" + data['id'];
    c = $.confirm({
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
      title: 'Обновление сведений об опросе',
      buttons: {
        ok: {
          btnClass: 'btn-blue',
          text: 'Обновить',
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
              var startDate = $('.start-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
              $('.start-date').val(startDate);
              var endDate = $('.end-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
              $('.end-date').val(endDate);
              var yText = '<span style="font-weight: 600">Успех!</span><br>Сведения об опросе обновлены';
              var nText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Обновить не удалось';
              sendPollFormData(url, pollTable, $form, xmlData, yText, nText);
            }
          }
        },
        cancel: {
          text: 'НАЗАД'
        }
      }
    });
  });

  pollTable.on('click', '#view', function (e) {
    e.preventDefault();
    var data = pollTable.row($(this).parents('tr')).data();
    var url = "/polls/polls/view-poll?id=" + data['id'];
    c = $.confirm({
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
      columnClass: 'xlarge',
      title: 'Подробности',
      buttons: {
        cancel: {
          text: 'НАЗАД'
        }
      }
    });
  });
});

function deleteProcess(url, table, csrf) {
  var data = table.rows({selected: true}).data();
  var id = data[0].id;
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
    data: {pollId: id, _csrf: csrf}
  }).done(function (response) {
    if (response != false) {
      jc.close();
      var text = 'Опрос удален полностью!';
      var yText = '<span style="font-weight: 600">Успех!</span><br>' + text;
      initNoty(yText, 'success');
      table.ajax.reload();
    } else {
      jc.close();
      var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Удалить опрос не удалось';
      initNoty(tText, 'warning');
    }
  }).fail(function () {
    jc.close();
    var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Удалить опрос не удалось';
    initNoty(tText, 'warning');
  });
}

$(document).on('click', '#batch-input', function (e) {
  e.preventDefault();
  NProgress.start();
  let data = pollTable.rows({selected: true}).data();
  let pollId = data[0].id;
  let url = '/polls/batch-input';
  loadExContentEx(url, () => loadPollConfigB(pollId, startBatchInput));
});

function loadPollConfigB(id, callback) {
  let url = '/polls/batch-input/get-poll-info?id=' + id;
  $.ajax({
    url: url,
    method: 'get'
  }).done(function (response) {
    if (response.code) {
      callback(response.data.data[0]);
    } else {
      console.log(response.data.message);
    }
  }).fail(function () {
    console.log('Failed to load poll config');
  });
}

function startBatchInput(config) {
  batch = new Batch(config);
  NProgress.done();
}