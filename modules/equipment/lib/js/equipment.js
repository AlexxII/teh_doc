//===========================================================
// Добавление оборудования
var tId,
  toolsTreeIdAttr = 'tools-main-tree';

$(document).on('click', '#add-equipment', function (e) {
  e.preventDefault();
  var tree = $('#' + toolsTreeIdAttr).fancytree('getTree');
  var node = $('#' + toolsTreeIdAttr).fancytree('getActiveNode');
  var rootTitle = $(e.currentTarget).data('root');
  if (!e.ctrlKey) {
    createWindow(tree, node, rootTitle);
  } else {
    simpleEquipmentAdd(tree, node, rootTitle);
  }
});

// добавление оборудования с присвоением ему данных
function createWindow(tree, node, rootTitle) {
  if (node) {
    var url = '/equipment/tool/info/create?root=' + node.data.id;
  } else {
    var url = '/equipment/tool/info/create';
  }
  c = $.confirm({
    content: function () {
      var self = this;
      return $.ajax({
        url: url,
        method: 'get'
      }).fail(function () {
        self.setContentAppend('<div>Что-то пошло не так</div>');
      });
    },
    contentLoaded: function (response, status, xhr) {
      this.setContentAppend('<div>' + response.data.data + '</div>');
    },
    type: 'blue',
    columnClass: 'large',
    title: 'Добавить оборудование',
    buttons: {
      ok: {
        btnClass: 'btn-blue',
        text: 'Сохранить',
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
            //преобразование дат перед отправкой
            var d = $('.fact-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
            $('.fact-date').val(d);
            $.ajax({
              type: 'POST',
              url: url,
              dataType: 'json',
              data: $form.serialize(),
              success: function (response) {
                var tText = '<span style="font-weight: 600">Успех!</span><br>Оборудование добавлено';
                initNoty(tText, 'success');
                tId = response.data.data;                 // запись в глобальную переменную для инициализации дерева
                tree.reload();
              },
              error: function (response) {
                console.log(response.data.data);
                var tText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Добавить оборудование не удалось';
                initNoty(tText, 'error');
              }
            });
          }
        }
      },
      cancel: {
        text: 'НАЗАД',
      }
    }
  });
}

// добавление оборудования в дереве (без присвоения определенных данных - кроме названия)
function simpleEquipmentAdd(tree, node, rootTitle) {
  if (!node) {
    var root = tree.findFirst(rootTitle);
    root.editCreateNode('child', ' ');
  } else {
    node.editCreateNode('child', ' ');
  }
}

// Удаление оборудования
$(document).on('click', '.delete-tool', function (e) {
  e.preventDefault();
  var tree = $('#' + toolsTreeIdAttr).fancytree('getTree');
  var node = $('#' + toolsTreeIdAttr).fancytree('getActiveNode');
  var parent = node.parent.key;
  var url = '/equipment/tool/info/delete';
  var text, dArray = {};
  var children = node.children;
  if (children && e.ctrlKey) {
    var index = 0;
    children.forEach(function (val, i, ar) {
      dArray[i] = val.key;
      index++;
    });
    dArray[index] = node.data.id;
    text = 'Вы действительно хотите удалить выделенное С вложениями?';
  } else {
    dArray['0'] = node.data.id;
    text = 'Вы действительно хотите удалить выделенное?';
  }
  jc = $.confirm({
    icon: 'fa fa-question',
    title: 'Вы уверены?',
    content: text,
    type: 'red',
    closeIcon: false,
    autoClose: 'cancel|9000',
    buttons: {
      ok: {
        btnClass: 'btn-danger',
        action: function () {
          jc.close();
          deleteTool(url, dArray, tree, parent, jc);
        }
      },
      cancel: {
        text: 'Отмена'
      }
    }
  });
});

// процесс удаления оборудования
function deleteTool(url, data, tree, parent) {
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
    data: {
      _csrf: csrf,
      data: data
    }
  }).done(function (response) {
    if (response != false) {
      tId = parent;
      var tText = '<span style="font-weight: 600">Успех!</span><br>Оборудование удалено';
      initNoty(tText, 'success');
      tree.reload();
    } else {
      var tText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Удалить не удалось';
      initNoty(tText, 'error');
    }
    jc.close();
  }).fail(function () {
    jc.close();
    var tText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Удалить не удалось';
    initNoty(tText, 'error');
  });
}

/*================= Управление деревьями (tools_tree, category_tree, placement_tree) ===============*/
$(document).on('keyup', 'input[name=search]', function (e) {
  var n,
    treeId = $(e.currentTarget).data('tree'),
    tree = $("#" + treeId).fancytree("getTree"),
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
}).focus();

$(document).on('keyup', 'input[name=search]', function (e) {
  if ($(this).val() == '') {
    var treeId = $(e.currentTarget).data('tree');
    var tree = $('#' + treeId).fancytree("getTree");
    tree.clearFilter();
  }
});

$(document).on('click', '.btnResetSearch', function (e) {
  e.preventDefault();
  var treeId = $(e.currentTarget).data('tree');
  var tree = $('#' + treeId).fancytree('getTree');
  $('input[name=search]').val('');
  $('span#matches').text('');
  tree.clearFilter();
}).attr('disabled', true);

// Обновление дерева (только tools_tree)
$(document).on('click', '#refresh-tools-tree', function (e) {
  e.preventDefault();
  var tree = $("#" + toolsTreeIdAttr).fancytree("getTree");
  tree.reload();
  tree.clearFilter();
  $(".delete-tool-wrap").hide();
  $('#tool-info').hide();
});

/*==================== tool/info ===================== */
/* Обновить сведения об оборудовании на главной странице */
$(document).on('click', '#tool-edit', function (e) {
  e.preventDefault();
  var node = $('#' + toolsTreeIdAttr).fancytree("getActiveNode");
  var toolId = node.data.id;
  var url = '/equipment/tool/info/update?id=' + toolId;
  c = $.confirm({
    content: function () {
      var self = this;
      return $.ajax({
        url: url,
        method: 'get'
      }).fail(function () {
        self.setContentAppend('<div>Что-то пошло не так</div>');
      });
    },
    contentLoaded: function (response, status, xhr) {
      this.setContentAppend('<div>' + response.data.data + '</div>');
    },
    type: 'blue',
    columnClass: 'large',
    title: 'Редактровать данные',
    buttons: {
      ok: {
        btnClass: 'btn-blue',
        text: 'Сохранить',
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
            //преобразование дат перед отправкой
            var d = $('.fact-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
            $('.fact-date').val(d);
            $.ajax({
              type: 'POST',
              url: url,
              dataType: 'json',
              data: $form.serialize(),
              success: function (response) {
                var tText = '<span style="font-weight: 600">Успех!</span><br>Данные обновлены';
                initNoty(tText, 'success');
                $('#tool-info-view').html(response.data.data);
                node.setTitle(response.data.message);
              },
              error: function (response) {
                console.log(response.data.data);
                var tText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Обновить не удалось';
                initNoty(tText, 'error');
              }
            });
          }
        }
      },
      cancel: {
        text: 'НАЗАД'
      }
    }
  });
});
/* Настройка оборудования */
$(document).on('click', '#tool-settings', function (e) {
  e.preventDefault();
  var node = $('#' + toolsTreeIdAttr).fancytree("getActiveNode");
  var toolId = node.data.id;
  var url = '/equipment/tool/settings/index?id=' + toolId;
  c = $.confirm({
    content: function () {
      var self = this;
      return $.ajax({
        url: url,
        method: 'get'
      }).done(function (response) {

      }).fail(function () {
        self.setContentAppend('<div>Что-то пошло не так!</div>');
      });
    },
    contentLoaded: function (response, status, xhr) {
      this.setContentAppend('<div>' + response.data.data + '</div>');
    },
    columnClass: 'large',
    title: 'Настройки',
    buttons: {
      cancel: {
        text: 'НАЗАД',
        action: function () {
          url = '/equipment/tool/info/index?id=' + toolId;
          $.ajax({
            url: url,
            method: 'get'
          }).done(function (response) {
            $('#tool-info-view').html(response.data.data);
          }).fail(function () {
            self.setContentAppend('<div>Что-то пошло не так</div>');
          });
        }
      }
    }
  });
});
/* Задание на обновление */
$(document).on('click', '#tool-task', function (e) {
  e.preventDefault();
  var btn = $(this);
  var node = $('#' + toolsTreeIdAttr).fancytree('getActiveNode');
  var toolId = node.data.id;
  var url = '/equipment/task/set';
  var csrf = $('meta[name=csrf-token]').attr('content');
  var bool;
  if (btn.data('task')) {
    bool = 0;
  } else {
    bool = 1;
  }
  $.ajax({
    url: url,
    type: 'post',
    data: {
      _csrf: csrf,
      toolId: toolId,
      bool: bool
    },
    success: function (responce) {
      if (responce.code == 1) {
        if (responce.data.data == 1) {
          btn.css('background-color', '#fef7e0');
          btn.css('fill', '#fbbc04');
          btn.data('task', 1);
        } else {
          btn.css('background-color', '');
          btn.css('fill', '');
          btn.data('task', 0);
        }
      } else {
        console.log(responce.data.data);
        var tText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Добавить в задание не удалось';
        initNoty(tText, 'error');
      }
    },
    error: function (responce) {
      console.log('Ошибка в контроллере!' + responce.data.message);
      var tText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Добавить в задание не удалось';
      initNoty(tText, 'error');
    }
  });
});

// функционал улучшения интерфейса формы
function loadManufacturers() {
  $.ajax({
    type: 'get',
    url: '/equipment/control/interface/manufact',
    autoFocus: true,
    success: function (data) {
      var manufact = $.parseJSON(data);
      $(function () {
        $("#manufact").autocomplete({
          source: manufact,
        });
      });
    },
    error: function (data) {
      console.log('Error loading Manufact list.');
    }
  });
}

function loadModels() {
  $.ajax({
    type: 'get',
    url: '/equipment/control/interface/models',
    autoFocus: true,
    success: function (data) {
      var models = $.parseJSON(data);
      $(function () {
        $("#models").autocomplete({
          source: models,
        });
      });
    },
    error: function (data) {
      console.log('Error loading Models list');
    }
  });
}

// ссылки на оборудование в предствлении 'Комплекс' (view_complex)
$(document).on('click', '.tool-ref', function (e) {
  e.preventDefault();
  var toolId = $(this).data('toolId');
  var tree = $('#' + toolsTreeIdAttr).fancytree('getTree');
  tree.getNodeByKey(toolId.toString()).setActive();
});

//=============================================================================//
// Классификаторы (категории/места размещения) - управление деревьями
$(document).on('click', '.add-subcategory', function (e) {
  e.preventDefault();
  var rootTitle = $(e.currentTarget).data('root');
  var treeId = $(e.currentTarget).data('tree');
  var node = $('#' + treeId).fancytree('getActiveNode');
  if (!node) {
    var tree = $('#' + treeId).fancytree('getTree');
    var root = tree.findFirst(rootTitle);
    root.editCreateNode('child', '');
  } else {
    node.editCreateNode('child', ' ');
  }
});

$(document).on('click', '.refresh', function (e) {
  e.preventDefault();
  var treeId = $(e.currentTarget).data('tree');
  var tree = $("#" + treeId).fancytree("getTree");
  tree.reload();
  $(".del-root").hide();
  $(".del-node").hide();
  $(".del-multi-nodes").hide();
  $('.about-info').html('');
});

$(document).on('click', '.del-node', function (e) {
  var url = $(e.currentTarget).data('delete');
  var treeId = $(e.currentTarget).data('tree');
  var node = $("#" + treeId).fancytree("getActiveNode");
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
          deleteToolSettingsNodes(url, node);
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
  var treeId = $(e.currentTarget).data('tree');
  var node = $("#" + treeId).fancytree("getActiveNode");
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
          deleteToolSettingsNodes(url, node);
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
  var treeId = $(e.currentTarget).data('tree');
  var tree = $("#" + treeId).fancytree("getTree");
  $("input[name=search]").val("");
  $("span#matches").text("");
  tree.clearFilter();
}).attr("disabled", true);

$(document).on('keyup', 'input[name=search]', function (e) {
  var treeId = $(e.currentTarget).data('tree');
  if ($(this).val() == '') {
    var tree = $('#' + treeId).fancytree("getTree");
    tree.clearFilter();
  }
  var n,
    tree = $('#' + treeId).fancytree("getTree"),
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

function deleteToolSettingsNodes(url, node) {
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
      node.remove();
      $('.about-info').html('');
      $('.del-node').hide();
      $(".del-multi-nodes").hide();
      jc.close();
      var yText = '<span style="font-weight: 600">Успех!</span><br>Узел удален';
      initNoty(yText, 'success');
    } else {
      jc.close();
      var nText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Запрос не выполнен';
      initNoty(nText, 'error');
    }
  }).fail(function () {
    jc.close();
    var nText = '<span style="font-weight: 600">Что-то пошло не так</span><br>Запрос не выполнен';
    initNoty(nText, 'error');
  });
}

/*==================== tool/doc ===================== */
/* Добавить документ */
$(document).on('click', '#add-doc', function (e) {
  e.preventDefault();
  var node = $('#' + toolsTreeIdAttr).fancytree("getActiveNode");
  var toolId = node.data.id;
  var url = '/equipment/tool/docs/create-ajax?id=' + toolId;
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
    title: 'Добавить документ',
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

            var d = $('.doc-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
            $('.doc-date').val(d);
            var form = $('form')[0];
            var formData = new FormData(form);
            $.ajax({
              type: 'POST',
              url: url,
              processData: false,
              contentType: false,
              data: formData,
              success: function (response) {
                var tText = '<span style="font-weight: 600">Успех!</span><br>Документ добавлен';
                initNoty(tText, 'success');
                getCounters(toolId);
                $('#tool-info-view').html(response.data.data);

              },
              error: function (response) {
                var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Документ не добавлен';
                initNoty(tText, 'warning');
                console.log(response.data.data);
              }
            });
          }
        }
      },
      cancel: {
        text: 'НАЗАД'
      }
    }
  });

});
/* Удалить документы */
$(document).on('click', '#delete-doc', function (e) {
  e.preventDefault();
  if ($(this).attr('disabled')) {
    return;
  }
  var url = '/equipment/tool/docs/delete-docs';
  var node = $('#' + toolsTreeIdAttr).fancytree("getActiveNode");
  var toolId = node.data.id;
  var selected = [];
  $('.doc-select:checked').each(function () {
    selected.push($(this).data('docid'));
  });
  var text = '';
  if (selected.length > 1) {
    text = 'Документы удалены';
  } else {
    text = 'Документ удален';
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
          deleteProcess(url, toolId, selected, text);
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

/*==================== tool/images ===================== */
/* Добавить изображение */
$(document).on('click', '#add-image', function (e) {
  e.preventDefault();
  var node = $('#' + toolsTreeIdAttr).fancytree("getActiveNode");
  var toolId = node.data.id;
  var url = '/equipment/tool/images/create?id=' + toolId;
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
    title: 'Добавить фото',
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
          var files = $form.find('#images-imagefiles')[0].files;
          if ($form.find('#images-imagefiles')[0].files.length) {
            if ($("#w0").find(".has-error").length) {
              return false;
            } else {
              var size = 0;
              for (var i = 0; files.length > i; i++) {
                size += parseInt(files[i].size, 10);
              }
              var serverMaxPost = $('#max-post-size').data('size');
              if (size >= serverMaxPost) {
                var tText = '<span style="font-weight: 600">Ошибка! Тяжелые изображения</span>' +
                  '<br>Максимум за раз - ' + serverMaxPost + ' байт';
                initNoty(tText, 'warning');
                return;
              }
              var form = $('form')[0];
              var formData = new FormData(form);
              jc = $.confirm({
                icon: 'fa fa-cog fa-spin',
                title: 'Подождите!',
                content: 'Ваш запрос выполняется!',
                buttons: false,
                closeIcon: false,
                confirmButtonClass: 'hide'
              });
              $.ajax({
                type: 'POST',
                url: url,
                processData: false,
                contentType: false,
                data: formData,
                success: function (response) {
                  jc.close();
                  var tText = '<span style="font-weight: 600">Успех!</span><br>Изображения добавлены';
                  initNoty(tText, 'success');
                  getCounters(toolId);
                  $('#tool-info-view').html(response.data.data);
                },
                error: function (response) {
                  jc.close();
                  var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Изображения не добавлены';
                  initNoty(tText, 'warning');
                  console.log(response.data.data);
                }
              });
            }
          }
          return;
        }
      },
      cancel: {
        text: 'НАЗАД'
      }
    }
  })
});

/* Удалить изображения */
$(document).on('click', '#delete-image', function (e) {
  e.preventDefault();
  if ($(this).attr('disabled')) {
    return;
  }
  var node = $('#' + toolsTreeIdAttr).fancytree("getActiveNode");
  var url = '/equipment/tool/images/delete-images';
  var toolId = node.data.id;
  var selected = [];
  $('.foto-select:checked').each(function () {
    selected.push($(this).data('docid'));
  });
  var text = '';
  if (selected.length > 1) {
    text = 'Изображения удалены';
  } else {
    text = 'Изображение удалено';
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
          deleteProcess(url, toolId, selected, text);
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

/* Процесс удаления (документов/изображений)*/
function deleteProcess(url, toolId, data, text) {
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
    data: {
      _csrf: csrf,
      toolId: toolId,
      data: data
    }
  }).done(function (response) {
    if (response != false) {
      jc.close();
      var yText = '<span style="font-weight: 600">Успех!</span><br>' + text;
      initNoty(yText, 'success');
      getCounters(toolId);
      $('#tool-info-view').html(response.data.data);
    } else {
      var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Запрос не выполнен';
      initNoty(tText, 'warning');
      jc.close();
    }
  }).fail(function () {
    jc.close();
    var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Запрос не выполнен';
    initNoty(tText, 'warning');
  });
}

/*==================== tool/settings ===================== */
var successCheck = '<i class="fa fa-check" id="consolidated-check" aria-hidden="true" style="color: #4eb305"></i>';
var warningCheck = '<i class="fa fa-times" id="consolidated-check" aria-hidden="true" style="color: #cc0000"></i>';
var infoCheck = '<i class="fa fa-exclamation" id="consolidated-check" aria-hidden="true" style="color: #cc0000"></i>';
var waiting = '<i class="fa fa-cog fa-spin" aria-hidden="true"></i>';
var csrf = $('meta[name=csrf-token]').attr("content");

$(document).on('click', '.check-it', function (e) {
  var checkId = $(this).data('check');
  $('#' + checkId).html(waiting);
  var url = '/equipment/tool/settings/' + $(this).data('url');
  var nodeId = $(this).data('id');
  var result = $(this).is(':checked');
  $.ajax({
    url: url,
    type: "post",
    dataType: "JSON",
    data: {
      _csrf: csrf,
      toolId: nodeId,
      bool: result
    },
    success: function (data) {
      $('#' + checkId).html(successCheck);
    },
    error: function (data) {
      $('#' + checkId).html(warningCheck);
    }
  });
});

$(document).on('click', '.save-title', function (e) {
  var nodeId = $(this).data('id');
  var inputHId = $(this).data('input');
  var input = $('#' + inputHId);
  var title = input.val();
  var resultH = $(this).data('result');
  var url = '/equipment/tool/settings/' + $(this).data('url');
  if (title != '') {
    $('#' + resultH).html(waiting);
    $.ajax({
      url: url,
      type: "post",
      dataType: "JSON",
      data: {
        _csrf: csrf,
        toolId: nodeId,
        title: title
      },
      success: function (data) {
        $('#' + resultH).html(successCheck);
      },
      error: function (data) {
        $('#' + resultH).html(warningCheck);
      }
    });
  } else {
    $('#' + resultH).html(infoCheck);
  }
});

$(document).on('change', '.input-check', function (e) {
  var bool = $(this).is(':checked');
  var inputHId = $(this).data('input');
  var input = $('#' + inputHId);
  var title = input.val();
  var resultH = $(this).data('result');
  var nodeId = $(this).data('id');
  var url = '/equipment/tool/settings/' + $(this).data('url');
  if (title != '') {
    $('#' + resultH).html(waiting);
    $.ajax({
      url: url,
      type: "post",
      dataType: "JSON",
      data: {
        _csrf: csrf,
        toolId: nodeId,
        title: title,
        bool: bool
      },
      success: function (data) {
        $('#' + resultH).html(successCheck);
      },
      error: function (data) {
        $('#' + resultH).html(warningCheck);
      }
    });
  } else {
    $(this).prop('checked', false);
    $('#' + resultH).html(infoCheck);
  }
});

$(document).on('input', '.title-input', function (e) {
  var checkId = $(this).data('check');
  var resultH = $(this).data('result');
  $('#' + checkId).prop('checked', false);
  $('#' + resultH).html('');
});

$(document).on('change', '#wrap', function (e) {
  var checkId = $(this).data('check');
  $('#' + checkId).html(waiting);
  var url = '/equipment/tool/settings/' + $(this).data('url');
  var nodeId = $(this).data('id');
  var result = $(this).is(':checked');
  $.ajax({
    url: url,
    type: "post",
    dataType: "JSON",
    data: {
      _csrf: csrf,
      toolId: nodeId,
      bool: result
    },
    success: function (data) {
      $('#' + checkId).html(successCheck);
    },
    error: function (data) {
      $('#' + checkId).html(warningCheck);
    }
  });
});

/* Задание на обновление */
$(document).on('change', '.ch', function (e) {
  e.preventDefault();
  var csrf = $('meta[name=csrf-token]').attr("content");
  var nodeId = $(this).data('id');
  var result = $(this).is(':checked');
  var parentDiv = $(this).closest('.task-wrap');
  var url = '/equipment/task/set';
  var checkId = parentDiv.find('.status-indicator');
  checkId.html(waiting);
  $.ajax({
    url: url,
    type: "post",
    data: {
      _csrf: csrf,
      toolId: nodeId,
      bool: result
    },
    success: function (data) {
      checkId.html(successCheck);
      parentDiv.fadeOut();
    },
    error: function (data) {
      checkId.html(warningCheck);
    }
  });
});

/*==================== tool/wiki ===================== */
// загрузка страницы создания wiki
$(document).on('click', '#new-wiki-page', function (e) {
  e.preventDefault();
  var node = $('#' + toolsTreeIdAttr).fancytree("getActiveNode");
  var toolId = node.data.id;
  var url = '/equipment/tool/wiki/create?id=' + toolId;
  $.ajax({
    url: url,
    method: 'get'
  }).done(function (response) {
    getCounters(toolId);
    $('#wiki-content').html(response.data.data);
  }).fail(function (response) {
    console.log(response.data.message);
  });
});
// загрузка страницы обновления wiki
$(document).on('click', '#update-wikipage', function (e) {
  e.preventDefault();
  var wikiId = $(this).data('id');
  var url = '/equipment/tool/wiki/update';
  $.ajax({
    url: url,
    method: 'get',
    data: {
      'page': wikiId
    }
  }).done(function (response) {
    $('#wiki-content').html(response.data.data);
  }).fail(function (response) {
    console.log(response.data.message);
  });
});

// процесс содание и обновление страницы wiki -> отсюда небольшие костыли
$(document).on('submit', 'form#wiki-create-form', function (e) {
  e.preventDefault();
  var node = $('#' + toolsTreeIdAttr).fancytree("getActiveNode");
  var toolId = node.data.id;
  var uri = $('#wiki-submit-btn').data('uri');
  var url, notyYText, notyNText;
  if (uri === 'create') {
    url = '/equipment/tool/wiki/' + uri + '?id=' + toolId;
    notyYText = 'Страница wiki создана';
    notyNText = 'Страница wiki не создана';
  } else {
    url = '/equipment/tool/wiki/' + uri + '&id=' + toolId;
    notyYText = 'Страница wiki обновлена';
    notyNText = 'Страница wiki не обновлена';
  }
  var form = $('form')[0];
  var formData = new FormData(form);
  $.ajax({
    type: 'POST',
    url: url,
    processData: false,
    contentType: false,
    data: formData,
    success: function (response) {
      var tText = '<span style="font-weight: 600">Успех!</span><br>' + notyYText;
      initNoty(tText, 'success');
      getCounters(toolId);
      $('#wiki-content').html(response.data.data);
    },
    error: function (response) {
      var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>' + notyNText;
      initNoty(tText, 'warning');
      console.log(response.data.message);
    }
  });
});

// Просмотр wiki страниц
$(document).on('click', '#cancel-wiki-form', function (e) {
  e.preventDefault();
  var node = $('#' + toolsTreeIdAttr).fancytree('getActiveNode');
  var toolId = node.data.id;
  var wikiId = $(this).data('wikiId');
  var url = '/equipment/tool/wiki/view';
  $.ajax({
    type: 'GET',
    url: url,
    data: {
      'page': wikiId,
      'id': toolId
    },
    success: function (response) {
      $('#wiki-content').html(response.data.data);
    },
    error: function (response) {
      console.log(response);
    }
  });
});

// Просмотр wiki страницы
$(document).on('click', '.show-wiki', function (e) {
  e.preventDefault();
  var node = $('#' + toolsTreeIdAttr).fancytree('getActiveNode');
  var toolId = node.data.id;
  var url = 'equipment/tool/wiki/view';
  var wikiId = $(this).data('wikiId');
  $.ajax({
    url: url,
    method: 'GET',
    data: {
      'page': wikiId,
      'id': toolId
    }
  }).done(function (response) {
    $('#wiki-content').html(response.data.data);
  }).fail(function (response) {
    console.log(response.data.message);
  });
});

// Удаление wiki страницы
$(document).on('click', '#delete-wiki-page', function (e) {
  e.preventDefault();
  var node = $('#' + toolsTreeIdAttr).fancytree('getActiveNode');
  var toolId = node.data.id;
  jc = $.confirm({
    icon: 'fa fa-question',
    title: 'Вы уверены?',
    content: 'Вы действительно хотите удалить страницу?',
    type: 'red',
    closeIcon: false,
    autoClose: 'cancel|9000',
    buttons: {
      ok: {
        btnClass: 'btn-danger',
        action: function () {
          var wikiId = $('#delete-wiki-page').data('wikiId');
          var url = '/equipment/tool/wiki/delete';
          $.ajax({
            type: 'GET',
            url: url,
            data: {
              'page': wikiId,
              'id': toolId
            },
            success: function (response) {
              var tText = '<span style="font-weight: 600">Успех!</span><br>Страница удалена';
              initNoty(tText, 'success');
              getCounters(toolId);
              $('#wiki-content').html(response.data.data);
            },
            error: function (response) {
              var tText = '<span style="font-weight: 600">Что-то пошло не так!</span><br>Удалить не удалось';
              initNoty(tText, 'warning');
              console.log(response);
            }
          });
          jc.close();
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

/*======================= equipment/show ========================= */
//
var treeCategoryShowId = "fancytree_categories_show";

// Обновление дерева (category_tree, placement_tree)
$(document).on('click', '.refresh-tree', function (e) {
  e.preventDefault();
  var treeId = $(e.currentTarget).data('tree');
  var tree = $("#" + treeId).fancytree("getTree");
  tree.reload();
  $('.task-it').hide();
  $('.sendbtn').hide();
  $(".root").text('');
  $(".lft").text('');
  $(".rgt").text('');
  $("#main-table").DataTable().clearPipeline().draw();
});


//отработка сворачивания дерева

var showMenuBtn =
  '<div class="show-menu-button" data-placement="top" data-toggle="tooltip" title="Развернуть" onclick="chageView()">' +
  '<i class="fa fa-chevron-right" aria-hidden="true"></i>' +
  '</div>';

function rememberSelectedRows() {
  var table = $('#main-table').DataTable();
  var indexes = table.rows({selected: true}).indexes();
  return indexes;
}

function restoreSelectedRows(indexes) {
  var table = $('#main-table').DataTable();
  var count = indexes.count();
  for (var i = 0; i < count; i++) {
    table.rows(indexes[i]).select();
  }
}

function redrawTable() {
  var table = $('#main-table').DataTable();
  table.draw();
  return true;
}

function chageView() {
  var width = '33%';
  var indexes;
  if ($(document).width() < 600) {
    width = '100%';
  }
  $('.show-menu-button').hide();
  $('.fancy-tree').animate({
      width: width
    },
    {
      duration: 1000,
      start: indexes = rememberSelectedRows(),
      complete: function () {
        $('.about').css('width', '');
        $('#main-table_wrapper').css('margin-left', '0px');
        $('#main-table_wrapper').css('position', 'inherit');
        redrawTable();
        restoreSelectedRows(indexes);
        $('[data-toggle="tooltip"]').tooltip();
        $('.fancy-tree').css('width', '');
      },
      step: function (now, fx) {
        if (now > 5 && now < 14) {
          $('.fancy-tree').show();
          $('.about').removeClass('col-lg-12 col-md-12').addClass('col-lg-10 col-md-10');
        } else if (now > 16) {
          $('.about').removeClass('col-lg-10 col-md-10').addClass('col-lg-8 col-md-8');
        }
      }
    }
  );
}

$(document).on('click', '.hideMenu-button', function (e) {
  var indexes;
  var treeId = $(e.currentTarget).data('tree');
  e.preventDefault();
  $('.fancy-tree').animate({
      width: "0%"
    },
    {
      duration: 1000,
      start: indexes = rememberSelectedRows(),
      complete: function () {
        $('#main-table_wrapper').css('margin-left', '20px');
        $('.about').css('width', '');
        $('.about').removeClass('col-lg-9 col-md-9').addClass('col-lg-12 col-md-12');
        redrawTable();
        restoreSelectedRows(indexes);
        $('.fancy-tree').hide();
        $('[data-toggle="tooltip"]').tooltip();
        if ($('.show-menu-button').length === 0) {
          $('#main-table_wrapper').append(showMenuBtn);
        }
        $('.show-menu-button').show();
      },
      step: function (now, fx) {
        if (now <= 25) {
          $('.about').removeClass('col-lg-8 col-md-8').addClass('col-lg-9 col-md-9');
        }
        if (now <= 11 && now >= 5) {
          $('.fancy-tree').hide();
          $('#main-table_wrapper').css('position', 'relative');
          $('[data-toggle="tooltip"]').tooltip();
          if ($('.show-menu-button').length === 0) {
            $('#main-table_wrapper').append(showMenuBtn);
          }
          $('.show-menu-button').show();
        }
      }
    }
  );
});


/*======================== Общие функции модуля ========================= */
function getCounters(toolId) {
  var url = '/equipment/tool/info/counters?id=' + toolId;
  $.ajax({
    url: url,
    method: 'get'
  }).done(function (response) {
    var counters = JSON.parse(response);
    if (counters != false) {
      $('#docs-tab .counter').html(counters.docsCount);
      $('#images-tab .counter').html(counters.imagesCount);
      $('#wiki-tab .counter').html(counters.wikiCount);
    }
    return;
  }).fail(function () {
    console.log('Что-то пошло не так');
  });
}

function loadTabsData(ref, toolId) {
  if (ref != undefined) {
    url = '/equipment/tool/' + ref + '/index?id=' + toolId;
  } else {
    url = '/equipment/tool/info/index?id=' + toolId;
  }
  $.ajax({
    url: url,
    method: 'get'
  }).done(function (response) {
    $('#tool-info-view').html(response.data.data);
  }).fail(function () {
    self.setContentAppend('<div>Что-то пошло не так!</div>');
  });
}

function initNoty(text, type) {
  new Noty({
    type: type,
    theme: 'mint',
    text: text,
    progressBar: true,
    timeout: '4000',
    closeWith: ['click'],
    killer: true,
    animation: {
      open: 'animated noty_effects_open noty_anim_out', // Animate.css class names
      close: 'animated noty_effects_close noty_anim_in' // Animate.css class names
    }
  }).show();
}


$(document).on('click', '.tool-send', function (e) {
  e.preventDefault();
  var toolId = $(e.currentTarget).data('id');
  tId = toolId;
  goBack();
});


$(document).on('click', '.sendbtn', function (e) {
  e.preventDefault();
  var treeIdAttr = $(this).closest('.page-data').data('tree');
  var tableIdAttr = $(this).closest('.page-data').data('table');
  var table = $('#' + tableIdAttr).DataTable();
  var data = table.rows({selected: true}).data();
  var url = '/equipment/show/extended-info';
  var ar = {};
  var count = data.length;
  for (var i = 0; i < count; i++) {
    ar[data[i][0]] = 1;
  }
  goBack(ar);
});


$(document).on('change', '#images-imagefiles', function () {
  var files = $(this).files;
  var size = 0;
  // console.log(this);
  for (var i = 0; files.length > i; i++) {
    size += files[i];
  }
  console.log(files);
});