function deleteRestoreProcess(url, table, csrf) {
  var data = table.rows({selected: true}).data();
  var ar = [];
  var count = data.length;
  for (var i = 0; i < count; i++) {
    ar[i] = data[i].schedule_id;
  }
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
    data: {jsonData: ar, _csrf: csrf}
  }).done(function (response) {
    if (response != false) {
      jc.close();
      var text = 'Выш запрос выполнен!';
      var yText = '<span style="font-weight: 600">Успех!</span><br>' + text;
      initNoty(yText, 'success');
      table.ajax.reload();
    } else {
      jc.close();
      var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Запрос не выполнен';
      initNoty(tText, 'warning');
    }
  }).fail(function () {
    jc.close();
    var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Запрос не выполнен';
    initNoty(tText, 'warning');
  });
}

//=============================================================================//
$(document).on('click', '.add-subcategory', function (e) {
  e.preventDefault();
  var rootTitle = $(this).data('root');
  var id = $(e.currentTarget).data('tree');
  var tree = $("#" + id).fancytree("getTree");
  var root = tree.findFirst(rootTitle);
  root.editCreateNode("child", " ");
});
//=============================================================================//
//tree
$(document).on('click', '.refresh', function (e) {
  e.preventDefault();
  var id = $(e.currentTarget).data('tree');
  var tree = $("#" + id).fancytree("getTree");
  tree.reload();
  $(".del-root").hide();
  $("#tool-ref").hide();
  $(".del-node").hide();
  $(".del-multi-nodes").hide();
  $('.about-info').html('');
});

$(document).on('click', '.del-node', function (e) {
  var idAttr = $(e.currentTarget).data('tree');
  var node = $("#" + idAttr).fancytree("getActiveNode");
  var url = $(e.currentTarget).data('delete');
  var tree = $("#" + idAttr).fancytree('getTree');
  // var parentNode = node.getParentList()[0].key;
  e.preventDefault();
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
          deleteProcess(url, node, tree);
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

$(document).on('click', '.del-multi-nodes', function (e) {
  e.preventDefault();
  var id = $(e.currentTarget).data('tree');
  var node = $("#" + id).fancytree("getActiveNode");
  var url = $(e.currentTarget).data('delete');
  jc = $.confirm({
    icon: 'fa fa-question',
    title: 'Вы уверены?',
    content: 'Вы действительно хотите удалить выделенное С вложениями?',
    type: 'red',
    closeIcon: false,
    autoClose: 'cancel|9000',
    buttons: {
      ok: {
        btnClass: 'btn-danger',
        action: function () {
          jc.close();
          deleteProcess(url, node);
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

$(document).on('click', '.btnResetSearch', function (e) {
  e.preventDefault();
  var id = $(e.currentTarget).data('tree');
  var tree = $("#" + id).fancytree("getTree");
  $("input[name=search]").val("");
  $("span#matches").text("");
  tree.clearFilter();
}).attr("disabled", true);

$(document).on('keyup', 'input[name=search]', function (e) {
  var id = $(e.currentTarget).data('tree');
  if ($(this).val() == '') {
    var tree = $("#" + id).fancytree("getTree");
    tree.clearFilter();
  }
  var n,
    tree = $("#" + id).fancytree("getTree"),
    args = "autoApply autoExpand fuzzy hideExpanders highlight leavesOnly nodata".split(" "),
    opts = {},
    filterFunc = $("#branchMode").is(":checked") ? tree.filterBranches : tree.filterNodes,
    match = $(this).val();

  $.each(args, function (i, o) {
    opts[o] = $("#" + o).is(":checked");
  });
  opts.mode = $("#hideMode").is(":checked") ? "hide" : "dimm";

  if (e && e.which === $.ui.keyCode.ESCAPE || $.trim(match) === "") {
    $("button#btnResetSearch").click();
    return;
  }
  if ($("#regex").is(":checked")) {
    // Pass function to perform match
    n = filterFunc.call(tree, function (node) {
      return new RegExp(match, "i").test(node.title);
    }, opts);
  } else {
    // Pass a string to perform case insensitive matching
    n = filterFunc.call(tree, match, opts);
  }
  $("#btnResetSearch").attr("disabled", false);
});

function deleteProcess(url, node, tree) {
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
    type: "post",
    data: {
      _csrf: csrf,
      id: node.data.id
    }
  }).done(function (response) {
    if (response != false) {
      jc.close();
      node.remove();
      tree.reload();
      $('.about-info').html('');
      $('.del-node').hide();
      $(".del-multi-nodes").hide();
      var yText = '<span style="font-weight: 600">Успех!</span><br>Удаление выполнено';
      initNoty(yText, 'success');
    } else {
      jc.close();
      var nText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Удалить не удалось';
      initNoty(nText, 'error');
    }
  }).fail(function () {
    jc.close();
    var nText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Удалить не удалось';
    initNoty(nText, 'error');
  });
}

function goodAlert(text) {
  var div = '' +
    '<div id="w3-success-0" class="alert-success alert fade in">' +
    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
    text +
    '</div>';
  return div;
}

function badAlert(text) {
  var div = '' +
    '<div id="w3-success-0" class="alert-danger alert fade in">' +
    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
    text +
    '</div>';
  return div;
}

function warningAlert(text) {
  var div = '' +
    '<div id="w3-success-0" class="alert-warning alert fade in">' +
    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
    text +
    '</div>';
  return div;
}

//=============================================================================//
// create schedule
$(document).on('click', '#create-new-schedule', function (e) {
  e.preventDefault();
  var $createTable = $('#schedule-create-tbl');
  var rows = $createTable[0].rows;
  var scheduleData = {};

  for (var key in rows) {
    if (rows[key].localName == 'tr') {
      if (rows[key].attributes.class != undefined && rows[key].attributes.class.value != 'group group-start') {
        if (rows[key].cells[3].firstChild.value == 'none') {
          $(rows[key].cells[3].firstChild).focus();
          $(rows[key]).effect("pulsate", {}, 2500);
          console.log('Есть пустые поля.');
          return;
        }
        var id = rows[key].cells[3].firstChild.attributes.id.value;
        var tempArray = {};
        tempArray['type'] = rows[key].cells[3].firstChild.value;
        if (rows[key].cells[4].firstChild.value == '') {
          var state = rows[key].cells[4].firstChild.disabled;
          rows[key].cells[4].firstChild.disabled = false;
          $(rows[key].cells[4].firstChild).focus();
          rows[key].cells[4].firstChild.disabled = state;
          $(rows[key]).effect("pulsate", {}, 2500);
          console.log('Есть пустые поля.');
          return;
        }
        var date = rows[key].cells[4].firstChild.value;
        var dd = date.match(/^(\d{1,2}).(\d{1,2}).(\d{4})$/);
        tempArray['date'] = dd[3] + '-' + dd[2] + '-' + dd[1];
        if (rows[key].cells[5].firstChild.value == 'none') {
          $(rows[key].cells[5].firstChild).focus();
          $(rows[key]).effect("pulsate", {}, 2500);
          console.log('Есть пустые поля.');
          return;
        }
        tempArray['admin'] = rows[key].cells[5].firstChild.value;
        if (rows[key].cells[6].firstChild.value == 'none') {
          $(rows[key].cells[6].firstChild).focus();
          $(rows[key]).effect("pulsate", {}, 2500);
          console.log('Есть пустые поля.');
          return;
        }
        tempArray['auditor'] = rows[key].cells[6].firstChild.value;
        scheduleData[id] = tempArray;
      }
    }
  }
  jc = $.confirm({
    icon: 'fa fa-cog fa-spin',
    title: 'Подождите!',
    content: 'Выполняется создание графика!',
    buttons: false,
    closeIcon: false,
    confirmButtonClass: 'hide'
  });
  var csrf = $('meta[name=csrf-token]').attr("content");
  var url = '/to/month-schedule/save-schedule';
  $.ajax({
    url: url,
    type: "post",
    format: 'JSON',
    data: {
      data: scheduleData,
      _csrf: csrf,
      year: scheduleYear,
      month: scheduleMonth
    }
  }).done(function (response) {
    jc.close();
    var yText = '<span style="font-weight: 600">Успех!</span><br>График ТО создан';
    initNoty(yText, 'success');
    goBack();
  }).fail(function (error) {
    jc.close();
    var nText = '<span style="font-weight: 600">Что-то пошло не так</span><br>График ТО не создан';
    initNoty(nText, 'error');
    console.log('Error - saving schedule');
  });
});

$(document).on('change', '#to-month', function (e) {
  var csrf = $('meta[name=csrf-token]').attr("content");
  if (e.target.value != '') {
    var createTable = $('#schedule-create-tbl').DataTable();
    createTable.rows('.selected').deselect();
    jc = $.confirm({
      icon: 'fa fa-cog fa-spin',
      title: 'Подождите!',
      content: 'Формируются необходимые данные на выбранный месяц!',
      buttons: false,
      closeIcon: false,
      confirmButtonClass: 'hide'
    });
    scheduleDate = $('#to-month').datepicker('getDate');
    scheduleYear = scheduleDate.getFullYear();
    scheduleMonth = scheduleDate.getMonth();
    var url = '/to/month-schedule/get-types';
    $.ajax({
      url: url,
      type: "post",
      data: {
        year: scheduleYear,
        month: scheduleMonth,
        _csrf: csrf
      }
    }).done(function (response) {
      if (response != false) {
        var result = JSON.parse(response);
        result.forEach(function (item, i, ar) {
          if (item.month == null) return;
          $('#' + item.eq_id).val(item.month);
        });
        var dates = getMonthBorders('to-month');
        setMonth(dates);
        $('.to-date').val('');
        $('.to-date').prop('disabled', true);
        $('.admin-list').val('none');
        $('.admin-list').prop('disabled', false);
        jc.close();
        jc = $.confirm({
          icon: 'fa fa-thumbs-up',
          title: 'Успех!',
          content: 'Данные сформированы',
          type: 'green',
          buttons: false,
          closeIcon: false,
          autoClose: 'ok|8000',
          confirmButtonClass: 'hide',
          buttons: {
            ok: {
              btnClass: 'btn-success',
              action: function () {
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
  } else {
    $('.to-date').val('');
    $('.to-date').prop('disabled', true);
    $('.admin-list').prop('disabled', true);
    $('.admin-list').val('none');
  }
});

function controlListsInit() {
  // инициализация списков для создания графика ТО
  toTypeSelect = '<select class="form-control to-list m-select">' +
    '<option value="none" selected="true" disabled="true">Выберите</option>';
  toAdminsSelect = '<select class="form-control admin-list m-select" style="width: 100% !important;">' +
    '<option value="none" selected="true" disabled="true">Выберите</option>';
  toAuditorsSelect = '<select class="form-control audit-list m-select" style="width: 100% !important;">' +
    '<option value="none" selected="true" disabled="true">Выберите</option>';
  $.ajax({
    url: '/to/control/settings/select-data',
    method: 'get',
    dataType: "JSON"
  }).done(function (response) {
    // types
    var types = response.types;
    types.forEach(function (value, index, array) {
      toTypeSelect += '<option value="' + value.id + '">' + value.name + '</option>';
    });
    toTypeSelect += '</select>';
    // admins
    var admins = response.admins;
    admins.forEach(function (value, index, array) {
      toAdminsSelect += '<option value="' + value.id + '">' + value.name + '</option>';
    });
    toAdminsSelect += '</select>';
    // auditors
    var auditors = response.auditors;
    auditors.forEach(function (value, index, array) {
      toAuditorsSelect += '<option value="' + value.id + '">' + value.name + '</option>';
    });
    toAuditorsSelect += '</select>';
  }).fail(function () {
    console.log('Не удалось загрузить служебные списки');
    toTypeSelect += '</select>';
    toAdminsSelect += '</select>';
    toAuditorsSelect += '</select>';
  });
}

function getMonthBorders(id) {
  var toMonth = $('#' + id).datepicker('getDate');
  console.log(toMonth);
  var month = toMonth.getMonth();
  var year = toMonth.getFullYear();
  var mDays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
  var nMonth = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
  var start_date = year + '-' + nMonth[month] + '-01';
  var end_date = year + '-' + nMonth[month] + '-' + mDays[month];

  // startDayBorder = '01-' + nMonth[month] + '-' + year;
  // endDayBorder = mDays[month] + '-' + nMonth[month] + '-' + year;

  startDay = '01.' + nMonth[month] + '.' + year;
  endDay = mDays[month] + '.' + nMonth[month] + '.' + year;

  return {
    'startDay': startDay,
    'endDay': endDay
  };
}


function setMonth(date) {
  if (date != null) {
    $('.to-date').prop('disabled', false);
    $('.to-date').datepicker('setStartDate', date.startDay);
    $('.to-date').datepicker('update', date.startDay);
    $('.to-date').on('change', copySl);                    // обработчик события 'change'
  }
  return;
}

// копирование селектов в выделенные ячейки
$(document).on('change', '.m-select', function (e) {
  var i = $(this).closest('td').index();
  var val = e.target.value;
  if ($(this).closest('tr').hasClass('selected')) {
    $('.selected').each(function () {
      $(this).find('td').eq(i).find(e.target.nodeName).val(val);
    });
  }
});
// обработка выбора ответственного за проведение ТО
$(document).on('change', '.admin-list', function (e) {
  var val = e.target.value;
  $(this).closest('tr').find('.to-date').prop('disabled', false);
  if ($(this).closest('tr').hasClass('selected')) {
    $('.selected').each(function () {
      $(this).find('.admin-list').val(val);
      $(this).find('.to-date').prop('disabled', false);
    });
  }
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

// ======================= Обработка подсказки ("Необходимо ввести месяц")==== ===================
$(document).on('mouseover', '#to-month', function (e) {
  if ($(this).val() == "") {
    $('#to-month').tooltip('enable');
    $('#to-month').tooltip('show');
  } else {
    $('#to-month').prop('title', '');
    $('#to-month-tooltip').tooltip('disable');
  }
});
$(document).on('mouseover', '.admin-list', function (e) {
  if ($(this).prop('disabled')) {
    $('#to-month').tooltip('enable');
    $('#to-month').tooltip('show');
  }
});
$(document).on('mouseover', '.to-date', function (e) {
  if ($(this).prop('disabled')) {
    if ($('#to-month').val() == '') {
      $('#to-month').prop('title', 'Необходимо выбрать месяц');
      $('#to-month').tooltip('enable');
      $('#to-month').tooltip('show');
    } else {
      var adminList = $(this).closest('tr').find('.admin-list');
      adminList.tooltip('enable');
      adminList.tooltip('show');
    }
  }
});
$(document).on('mouseleave', '.to-date', function (e) {
  $('#to-month').tooltip('hide');
  $('#to-month').tooltip('disable');
  $('.admin-list').tooltip('hide');
  $('.admin-list').tooltip('disable');
});
$(document).on('mouseleave', '.admin-list', function (e) {
  $('#to-month').tooltip('hide');
  $('#to-month').tooltip('disable');
});

//====================================================================
// проставление отметок о проведении ТО
$(document).on('click', '#perform-schedule', function (e) {
  e.preventDefault();
  var $createTable = $('#perform-schedule-tbl');
  var rows = $createTable[0].rows;
  var scheduleData = {};
  var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
  for (var key in rows) {
    if (rows[key].localName == 'tr') {
      if (rows[key].attributes.class != undefined && rows[key].attributes.class.value != 'group group-start') {
        var id = rows[key].cells[5].firstChild.attributes.id.value;
        if (rows[key].cells[5].firstChild.value !== '') {
          var fDate = rows[key].cells[5].firstChild.value;
          scheduleData[id] = fDate.replace(pattern, '$3-$2-$1');
        }
      }
    }
  }
  jc = $.confirm({
    icon: 'fa fa-cog fa-spin',
    title: 'Подождите!',
    content: 'Выполняется подтверждение в график!',
    buttons: false,
    closeIcon: false,
    confirmButtonClass: 'hide'
  });
  var csrf = $('meta[name=csrf-token]').attr("content");
  var url = '/to/month-schedule/perform-schedule';
  $.ajax({
    url: url,
    type: "post",
    format: 'JSON',
    data: {
      _csrf: csrf,
      data: scheduleData
    }
  }).done(function (response) {
    jc.close();
    var yText = '<span style="font-weight: 600">Успех!</span><br>Проведение ТО потверждено';
    initNoty(yText, 'success');
    goBack();
  }).fail(function (error) {
    jc.close();
    var nText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Подтвердить не удалось';
    initNoty(nText, 'error');
    console.log('Error - saving schedule');
  });
});
$(document).on('click', '#save-edit', function (e) {
  e.preventDefault();
  var $createTable = $('#edit-schedule-tbl');
  var rows = $createTable[0].rows;
  var scheduleData = {};
  var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
  for (var key in rows) {
    if (rows[key].localName == 'tr') {
      if (rows[key].attributes.class != undefined && rows[key].attributes.class.value != 'group group-start') {
        // console.log(rows[key].cells[4].firstChild.dataset.new);
        // console.log(rows[key].cells[4].firstChild.value);
        var tempArray = {};
        var id = rows[key].cells[4].firstChild.attributes.id.value;

        tempArray['type'] = rows[key].cells[3].firstChild.value;              // Вид ТО
        var fDate = rows[key].cells[4].firstChild.value;                      // ->
        tempArray['date'] = fDate.replace(pattern, '$3-$2-$1');               // Дата проведения
        tempArray['admin'] = rows[key].cells[5].firstChild.value;             // Ответственный за проведение
        tempArray['auditor'] = rows[key].cells[6].firstChild.value;             // Ответственный за контроль
        scheduleData[id] = tempArray;
      }
    }
  }
  jc = $.confirm({
    icon: 'fa fa-cog fa-spin',
    title: 'Подождите!',
    content: 'Выполняется сохранение изменений в график!',
    buttons: false,
    closeIcon: false,
    confirmButtonClass: 'hide'
  });
  var csrf = $('meta[name=csrf-token]').attr("content");
  var url = '/to/month-schedule/edit-save';
  $.ajax({
    url: url,
    type: "post",
    format: 'JSON',
    data: {
      _csrf: csrf,
      data: scheduleData
    }
  }).done(function (response) {
    var yText = '<span style="font-weight: 600">Успех!</span><br>График ТО обновлен';
    initNoty(yText, 'success');
    jc.close();
    goBack();
  }).fail(function (error) {
    jc.close();
    var nText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Обновить не удалось';
    initNoty(nText, 'error');
    console.log('Error - saving schedule');
  });
});
