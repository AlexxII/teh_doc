<?php

use yii\helpers\Html;

$this->title = 'Таблица ОТХ';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = $this->title;

$about = "Перечень всего оборудования для ОТХ, зарегистрированного в системе и отмеченного для доступа в панеле управления.";
$task_hint = 'Добавить выделенные элементы в задание на обновление данных.';

?>

<div class="tool-create">

  <h3><?= Html::encode($this->title) ?>
    <sup class="h-title fa fa-question-circle-o" aria-hidden="true"
         data-toggle="tooltip" data-placement="right" title="<?php echo $about ?>"></sup>
  </h3>

</div>

<style>
  /*td {*/
  /*text-align: center;*/
  /*}*/
  #main-table tbody td {
    font-size: 12px;
  }

  .h-title {
    font-size: 18px;
    color: #1e6887;
  }

  td .fa {
    font-size: 22px;
  }
</style>

<div class="row">
  <div class="">
    <div class="container-fluid" style="margin-bottom: 20px">
      <?= Html::a('В задание',
        [''], [
          'class' => 'btn btn-success btn-sm tasking',
          'style' => ['margin-top' => '5px'],
          'data-toggle' => "tooltip",
          'data-placement' => "top",
          'title' => $task_hint,
          'disabled' => true
        ]) ?>
    </div>
  </div>

  <div class="container-fluid">
    <?php

    echo '
        <table id="main-table" class="display no-wrap cell-border" style="width:100%">
          <thead>
            <tr>
              <th></th>
              <th data-priority="1">Наименование</th>
              <th >Производитель</th>
              <th >Модель</th>
              <th >s/n</th>
              <th >Инв.№</th>
              <th >Дата изготовл.</th>
              <th data-priority="2">Action</th>
              <th data-priority="3"></th>
            </tr>
          </thead>
        </table>';
    ?>
  </div>

  <input class="csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" style="display: none">
</div>
<br>


<script>

    // ************************* Работа таблицы **************************************

    $(document).ready(function () {
        $.fn.dataTable.pipeline = function (opts) {
            var conf = $.extend({
                pages: 2,     // number of pages to cache
                url: '',      // script url
                data: null,   // function or object with parameters to send to the server
                              // matching how `ajax.data` works in DataTables
                method: 'GET' // Ajax HTTP method
            }, opts);
            var cacheLower = -1;
            var cacheUpper = null;
            var cacheLastRequest = null;
            var cacheLastJson = null;
            return function (request, drawCallback, settings) {
                var ajax = false;
                var requestStart = request.start;
                var drawStart = request.start;
                var requestLength = request.length;
                var requestEnd = requestStart + requestLength;
                if (settings.clearCache) {
                    ajax = true;
                    settings.clearCache = false;
                }
                else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
                    ajax = true;
                }
                else if (JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
                    JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
                    JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
                ) {
                    ajax = true;
                }
                cacheLastRequest = $.extend(true, {}, request);
                if (ajax) {
                    if (requestStart < cacheLower) {
                        requestStart = requestStart - (requestLength * (conf.pages - 1));
                        if (requestStart < 0) {
                            requestStart = 0;
                        }
                    }
                    cacheLower = requestStart;
                    cacheUpper = requestStart + (requestLength * conf.pages);
                    request.start = requestStart;
                    request.length = requestLength * conf.pages;
                    if (typeof conf.data === 'function') {
                        var d = conf.data(request);
                        if (d) {
                            $.extend(request, d);
                        }
                    }
                    else if ($.isPlainObject(conf.data)) {
                        $.extend(request, conf.data);
                    }
                    settings.jqXHR = $.ajax({
                        "type": conf.method,
                        "url": conf.url,
                        "data": request,
                        "dataType": "json",
                        "cache": false,
                        "success": function (json) {
                            cacheLastJson = $.extend(true, {}, json);
                            if (cacheLower != drawStart) {
                                json.data.splice(0, drawStart - cacheLower);
                            }
                            if (requestLength >= -1) {
                                json.data.splice(requestLength, json.data.length);
                            }
                            drawCallback(json);
                        }
                    });
                }
                else {
                    json = $.extend(true, {}, cacheLastJson);
                    json.draw = request.draw; // Update the echo for each response
                    json.data.splice(0, requestStart - cacheLower);
                    json.data.splice(requestLength, json.data.length);
                    drawCallback(json);
                }
            }
        };
        $.fn.dataTable.Api.register('clearPipeline()', function () {
            return this.iterator('table', function (settings) {
                settings.clearCache = true;
            });
        });
    });

    $(document).ready(function () {
        var settings = 'eq_oth';
        var table = $('#main-table').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if ((aData[19] <= 0 && aData[15] != '') || (aData[20] <= 0 && aData[17] != '')) {
                    $('td', nRow).css('background-color', '#fff1ef');
                    $('td:eq(1)', nRow).append('<br>' + '<strong>Проверьте <i class="fa fa-clock-o" aria-hidden="true"></i></strong>');
                }
            },
            "ajax": $.fn.dataTable.pipeline({
                url: 'server-side-oth',
                type: "post",                                     ///////!!!!!!!!!!!!!!!!!!!!!!!!!
                data: {
                    index: settings
                },
                pages: 2 // number of pages to cache
            }),
            orderFixed: [2, 'asc'],
            "columnDefs": [
                {
                    "orderable": false,
                    "targets": -2,
                    "data": null,
                    "width": '45px',
                    "defaultContent":
                    "<a href='#' class='fa fa-edit edit' style='padding-right: 5px' title='Обновить' data-placement='top' data-toggle='tooltip'></a>" +
                    "<a href='#' class='fa fa-info view' title='Подробности' style='padding-right: 5px'></a>"
                }, {
                    "orderable": false,
                    "className": 'select-checkbox',
                    "targets": -1,
                    "defaultContent": ""
                }, {
                    "targets": 0,
                    "data": null,
                    "visible": false
                }
            ],
            select: {
                style: 'os',
                selector: 'td:last-child'
            },
            language: {
                url: "/lib/ru.json"
            }
        });

        $('#main-table tbody').on('click', '.edit', function (e) {
            e.preventDefault();
            var data = table.row($(this).parents('tr')).data();
            var href = "/tehdoc/equipment/control-panel/" + data[0] + '/info/index';
            if (e.ctrlKey) {
                window.open(href);
            } else {
                location.href = href;
            }
        });
        $('#main-table tbody').on('click', '.view', function (e) {
            e.preventDefault();
            var data = table.row($(this).parents('tr')).data();
            var href = "/tehdoc/equipment/tool/" + data[0] + '/info/index';
            if (e.ctrlKey) {
                window.open(href);
            } else {
                location.href = href;
            }

        });
    });

    // Работа таблицы -> событие выделения и снятия выделения

    $(document).ready(function () {
        var table = $('#main-table').DataTable();
        table.on('select', function (e, dt, type, indexes) {
            if (type === 'row') {
                $('.tasking').removeAttr('disabled');
            }
        });
        table.on('deselect', function (e, dt, type, indexes) {
            if (type === 'row') {
                console.log();
                if (table.rows({selected: true}).count() > 0) return;
                $('.tasking').attr('disabled', true);
            }
        });
    });

    //********************** В задание на обновление данных ***********************************

    $(document).ready(function () {
        $('.tasking').click(function (event) {
            event.preventDefault();
            if ($(this).attr('disabled')) {
                return;
            }
            var csrf = $('meta[name=csrf-token]').attr("content");
            var table = $('#main-table').DataTable();
            var data = table.rows({selected: true}).data();
            var ar = [];
            var count = data.length;
            var url = '/tehdoc/equipment/control-panel/settings/task-set-pckg';
            for (var i = 0; i < count; i++) {
                ar[i] = data[i][0];
            }
            jc = $.confirm({
                icon: 'fa fa-thumb-tack',
                title: 'Вы уверены?',
                content: 'Вы действительно хотите добавить выделенное оборудование в задание на обновление?',
                type: 'blue',
                closeIcon: false,
                autoClose: 'cancel|9000',
                buttons: {
                    ok: {
                        btnClass: 'btn-info',
                        action: function () {
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
                                dataType: "JSON",
                                data: {
                                    jsonData: ar,
                                    _csrf: csrf,
                                    bool: true
                                },
                                success: function (result) {
                                    jc.close();
                                    table.rows({page: 'current'}).deselect();
                                    $('.tasking').attr('disabled', true);
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
                                                }
                                            }
                                        }
                                    });
                                },
                                error: function () {
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
                                }
                            });
                        }
                    },
                    cancel: {
                        action: function () {
                            return;
                        }
                    }
                }
            });
        })
    });

    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>