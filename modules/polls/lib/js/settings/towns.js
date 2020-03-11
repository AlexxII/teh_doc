function showTownTree() {
  $('[data-toggle="tooltip"]').tooltip();

  var main_url = '/polls/settings/towns/towns';
  var move_url = "/polls/settings/towns/move";
  var create_url = '/polls/settings/towns/town-create';
  var update_url = '/polls/settings/towns/update';
  var detail_url = '/polls/settings/towns/details';

  $("#fancytree_poll_towns").fancytree({
    source: {
      url: main_url
    },
    extensions: ['dnd', 'edit', 'filter'],
    quicksearch: true,
    minExpandLevel: 1,
    dnd: {
      preventVoidMoves: true,
      preventRecursiveMoves: true,
      autoCollapse: true,
      dragStart: function (node, data) {
        if (data.node.data.lvl == 0) {
          return false;
        }
        return true;
      },
      dragEnter: function (node, data) {
        return true;
      },
      dragDrop: function (node, data) {
        if (data.hitMode == 'over') {
          if (data.node.data.lvl >= 2 || data.otherNode.isFolder()) {             // Ограничение на вложенность
            jc = $.confirm({
              icon: 'fa fa-exclamation-triangle',
              title: 'Запрещено!',
              content: 'Суммарная глубина вложенности данного дерева не должна превышать 3х уровней!',
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
            return false;
          }
          var pId = data.node.data.id;
        } else {
          var pId = data.node.parent.data.id;
        }
        $.get(move_url, {
          item: data.otherNode.data.id,
          action: data.hitMode,
          second: node.data.id,
          parentId: pId
        }, function () {
          data.otherNode.moveTo(node, data.hitMode);
        })
      }
    },
    filter: {
      autoApply: true,                                    // Re-apply last filter if lazy data is loaded
      autoExpand: true,                                   // Expand all branches that contain matches while filtered
      counter: true,                                      // Show a badge with number of matching child nodes near parent icons
      fuzzy: false,                                       // Match single characters in order, e.g. 'fb' will match 'FooBar'
      hideExpandedCounter: true,                          // Hide counter badge if parent is expanded
      hideExpanders: true,                                // Hide expanders if all child nodes are hidden by filter
      highlight: true,                                    // Highlight matches by wrapping inside <mark> tags
      leavesOnly: true,                                   // Match end nodes only
      nodata: true,                                       // Display a 'no data' status node if result is empty
      mode: 'hide'                                        // Grayout unmatched nodes (pass "hide" to remove unmatched node instead)
    },
    edit: {
      inputCss: {
        minWidth: '10em'
      },
      triggerStart: ['clickActive', 'dbclick', 'f2', 'mac+enter', 'shift+click'],
      beforeEdit: function (event, data) {
        return true;
      },
      edit: function (event, data) {
        return true;
      },
      beforeClose: function (event, data) {
        data.save
      },
      save: function (event, data) {
        var node = data.node;
        if (data.isNew) {
          $.ajax({
            url: create_url,
            data: {
              parentId: node.parent.data.id,
              title: data.input.val()
            }
          }).done(function (result) {
            if (result) {
              result = JSON.parse(result);
              node.data.id = result.acceptedId;
              node.setTitle(result.acceptedTitle);
              node.data.lvl = result.lvl;
              $('.about-info').hide().html(goodAlert('Запись успешно сохранена в БД.')).fadeIn('slow');
            } else {
              node.setTitle(data.orgTitle);
              $('.about-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
                ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
            }
          }).fail(function (result) {
            node.setTitle(data.orgTitle);
            $('.about-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
              ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
          }).always(function () {
            // data.input.removeClass("pending")
          });
        } else {
          $.ajax({
            url: update_url,
            data: {
              id: node.data.id,
              title: data.input.val()
            }
          }).done(function (result) {
            if (result) {
              result = JSON.parse(result);
              node.setTitle(result.acceptedTitle);
              $('.about-info').hide().html(goodAlert('Запись успешно изменена в БД.')).fadeIn('slow');
            } else {
              node.setTitle(data.orgTitle);
              $('.about-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
                ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
            }
          }).fail(function (result) {
            $('.about-info').hide().html(badAlert('Запись не сохранена в БД. Попробуйте перезагрузить страницу и попробовать' +
              ' снова. При повторных ошибках обратитесь к разработчику.')).fadeIn('slow');
            node.setTitle(data.orgTitle);
          }).always(function () {
          });
        }
        return true;
      },
      close: function (event, data) {
        if (data.save) {
          // Since we started an async request, mark the node as preliminary
          $(data.node.span).addClass("pending")
        }
      }
    },
    activate: function (node, data) {
      $('.about-info').html('');
      var node = data.node;
      var lvl = node.data.lvl;
      var id = node.data.id;
      // if (lvl > 1) {
      //   $(".add-subcategory").hide();
      // }
      if (lvl == 0) {
        $(".del-node").hide();
        $(".del-multi-nodes").hide();
        $('.region-info').val('');
        $('.about label').css('color', '#000');
        $('.region-info').prop("disabled", true);
      } else {
        if (node.hasChildren()) {
          $(".del-multi-nodes").show();
        } else {
          $(".del-multi-nodes").hide();
        }
        $('.about label').css('color', '#000');
        $('.region-info').val('');
        $(".del-node").show();
        var url = detail_url;
        $('.region-info').prop("disabled", false);
        $.get(url, {
          id: id
        }, function (data) {
          if (data) {
            for (key in data) {
              if (data[key]) {
                $('#' + key).val(data[key]);
              }
            }
          }
        })
      }
    },
    renderNode: function (node, data) {
      return;
    }
  });
}

$(document).keydown(function (e) {
  if (e.keyCode == 65 && e.ctrlKey) {
    e.preventDefault();
    var treeIdAttr = 'fancytree_maps_regions';
    var node = $("#" + treeIdAttr).fancytree("getActiveNode");
    if (!node) {
      alert("Выберите родительскую категорию");
      return;
    }
    node.editCreateNode("child", " ");

  }
});

//=============================================================================//
// jconfirm btns
$(document).on('click', '.add-subcategory', function (e) {
  e.preventDefault();
  var id = $(e.currentTarget).data('tree');
  var node = $("#" + id).fancytree("getActiveNode");
  if (!node) {
    alert("Выберите родительскую категорию");
    return;
  }
  node.editCreateNode("child", " ");
});

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
          deleteIt(url, node);
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

function deleteIt(url, node) {
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

