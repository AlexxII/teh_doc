/* form */
function sendFormData(url, table, form, yTest, nTest) {
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: form.serialize(),
    success: function (response) {
      initNoty(yTest, 'success');
      table.clearPipeline().draw();
    },
    error: function (response) {
      console.log(response.data.data);
      initNoty(nTest, 'error');
    }
  });
}

function deleteRestoreProcess(url, table, csrf) {
  var data = table.rows({selected: true}).data();
  var ar = [];
  var count = data.length;
  for (var i = 0; i < count; i++) {
    ar[i] = data[i][0];
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
              table.clearPipeline().draw();
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

//=============================================================================//
// jconfirm btns
$(document).on('click', '.add-subcategory', function (e) {
  e.preventDefault();
  var id = $(e.currentTarget).data('tree');
  var node = $('#' + id).fancytree('getActiveNode');
  if (!node) {
    alert('Выберите родительскую категорию');
    return;
  }
  if (node.data.lvl <= 1) {
    node.editCreateNode('child', ' ');
  } else {
    alert('Нельзя создавать вложенность более 3х');
    return;
  }
});

//Fancytree control

$(document).on('click', '.refresh', function (e) {
  e.preventDefault();
  var id = $(e.currentTarget).data('tree');
  var tree = $("#" + id).fancytree("getTree");
  tree.reload();
  $(".del-root").hide();
  $(".del-node").hide();
  $(".del-multi-nodes").hide();
  $('.about-info').html('');
});

$(document).on('click', '.del-node', function (e) {
  var id = $(e.currentTarget).data('tree');
  var node = $("#" + id).fancytree("getActiveNode");
  var url = $(e.currentTarget).data('delete');
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
  if ($(this).val() == '') {
    var tree = $(".ui-draggable-handle").fancytree("getTree");
    tree.clearFilter();
  }
  var n,
    tree = $.ui.fancytree.getTree(),
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

function deleteProcess(url, node) {
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
    data: {id: node.data.id, _csrf: csrf}
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
              node.remove();
              $('.about-info').html('');
              $('.del-node').hide();
              $(".del-multi-nodes").hide();
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
      content: 'Запрос не вы!!!полнен. Что-то пошло не так.',
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

// fancytree - subscribes
$(document).on('keyup mouseclick', '#surnames-control', function (e) {
  $(".save-btn").prop("disabled", this.value.length == "" ? true : false);
});

function saveClick(e) {
  e.preventDefault();
  var csrf = $('meta[name=csrf-token]').attr("content");
  var nodeId = window.nodeId;
  var surnames = $("textarea").val();
  $.ajax({
    url: "/vks/control/vks-subscribes/surnames-save",
    type: "post",
    data: {Data: surnames, _csrf: csrf, id: nodeId},
    success: function (result) {
      if (result) {
        $('.about-info').hide().html(goodAlert('Записи добавлены в БД.')).fadeIn('slow');
      } else {
        $('.about-info').hide().html(badAlert('Записи не сохранены в БД. Попробуйте перезагрузить страницу и попробовать' +
          'снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
      }
    },
    error: function () {
      $('.about-info').hide().html(badAlert('Записи не сохранены в БД. Попробуйте перезагрузить страницу и попробовать' +
        'снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
    }
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

// PDFMake функции
// создание таблицы
function buildTable(data, columns, witdhsDef, showHeaders, headers, layoutDef) {
  return {
    table: {
      headerRows: 1,
      widths: witdhsDef,
      body: buildTableBody(data, columns, showHeaders, headers)
    },
    layout: layoutDef
  };
}
// создание тела таблицы
function buildTableBody(data, columns, showHeaders, headers) {
  var body = [];
  if (showHeaders) {
    body.push(headers);
  }
  data.forEach(function (row) {
    var dataRow = [];
    var i = 0;
    columns.forEach(function (column) {
      dataRow.push({text: Object.byString(row, column), style: headers[i].cellsStyle, });
      i++;
    });
    body.push(dataRow);
  });
  return body;
}

Object.byString = function (o, s) {
  s = s.replace(/\[(\w+)\]/g, '.$1'); // convert indexes to properties
  s = s.replace(/^\./, '');           // strip a leading dot
  var a = s.split('.');
  for (var i = 0, n = a.length; i < n; ++i) {
    var k = a[i];
    if (k in o) {
      o = o[k];
    } else {
      return;
    }
  }
  return o;
};





