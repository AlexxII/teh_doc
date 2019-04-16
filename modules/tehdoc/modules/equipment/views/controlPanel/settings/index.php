<div class="complex-settings" style="margin-top: 15px">
  <div class="settings">

    <div class="subhead">
      <h3 class="setting-header">Отображение</h3>
    </div>
    <ul class="list-group">
      <li class="list-group-item">
        <div class="form-checkbox js-complex-option">
          <label style="font-weight: 500">
            <input class="ch" id="general-feature" type="checkbox" data-id="<?= $model->id ?>"
                   data-check='general-check'
                   data-url='general-table' <?php if ($model->settings->eq_general) echo 'checked' ?>>
            В сводной таблице
          </label>
          <span class="status-indicator" id="general-check"></span>
          <p class="note" style="margin-bottom: 10px">Отображать данный узел в сводной таблице.</p>
        </div>
      </li>

      <li class="list-group-item">
        <div class="form-checkbox js-complex-option">
          <label style="font-weight: 500">
            <input class="ch" type="checkbox"
                   id="oth-feature"
                   data-id="<?= $model->id ?>"
                   data-check='oth-check' data-url='oth' <?php if ($model->othStatus) echo 'checked' ?>>
            В перечне ОТХ
          </label>
          <span class="status-indicator" id="oth-check"></span>
          <p class="note" style="margin-bottom: 10px">Отображать в таблице ОТХ.</p>
          <div class="d-blue border">
            <div class="form-checkbox js-complex-option">
              <label style="font-weight: 500">Наименование в перечне</label>
              <p class="note pr-6">При отличии наименования в перечне ОТХ от истинного наименования оборудования,
                укажите необходимое наименование.</p>
            </div>
            <div class="form-checkbox">
              <div class="input-group" style="padding-right: 20px">
                  <span class="input-group-addon">
                    <input class="input-check" type="checkbox" style="margin: 0"
                           id="oth-checkbox" data-input="oth-title" data-result="oth-result"
                           data-url="oth-title"
                           data-id="<?= $model->id ?>" <?php if ($model->othTitleCheck) echo 'checked' ?>>
                  </span>
                <div style="position: relative">
                  <input class="form-control title-input" type="text"
                         id="oth-title" data-check="oth-checkbox" data-result="oth-result"
                         value="<?= $model->othTitle ?>">
                  <span style="position: absolute; top:7px; right:10px;z-index: 900"
                        id="oth-result">
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </li>

      <li class="list-group-item">
        <div class="form-checkbox js-complex-option">
          <label style="font-weight: 500">
            <input class="ch" type="checkbox"
                   data-id="<?= $model->id ?>"
                   data-check='complex-check' data-url='complex' <?php if ($model->complex) echo 'checked' ?>>
            Комплект
          </label>
          <span class="status-indicator" id="complex-check"></span>
          <p class="note" style="margin-bottom: 10px">Отображать как комплект.</p>
        </div>
      </li>

      <li class="list-group-item">
        <div class="form-checkbox js-complex-option">
          <label style="font-weight: 500">
            <input id="wrap" type="checkbox"
                   data-id="<?= $model->id ?>"
                   data-check='wrap-check' data-url='wrap' <?php if ($model->wrap) echo 'checked' ?>>
            Обертка
          </label>
          <span class="status-indicator" id="wrap-check"></span>
          <p class="note" style="margin-bottom: 10px">Отображать данный объект как обертку вокруг других объектов.</p>
        </div>
      </li>
    </ul>


    <div class="subhead">
      <h3 class="setting-header">Специальные работы
        <i class="fa fa-shield" aria-hidden="true" style="font-size: 20px;
           title=" Проведены Специальные работы"></i>
      </h3>
    </div>
    <ul class="list-group">
      <li class="list-group-item">
        <div class="form-checkbox js-complex-option">
          <label style="font-weight: 500">
            <input class="ch" type="checkbox" id="special_works_feature"
                   data-id="<?= $model->id ?>"
                   data-check='special-check'
                   data-url='special-works' <?php if ($model->specialStatus) echo 'checked' ?>>
            Проведены Специальные работы</label>
          <span class="status-indicator" id="special-check"></span>
          <p class="note" style="margin-bottom: 10px">Над данным оборудованием были проведены специальные работы.</p>
          <div class="d-blue border">
            <div class="form-checkbox js-complex-option">
              <label style="font-weight: 500">Номера наклеек</label>
              <p class="note pr-6">Укажите номера галограмм. Например: Л 727 7806339 или только партию 727 (если указана
                только она)</p>
            </div>
            <div class="form-checkbox">
              <div class="input-group" style="padding-right: 20px">
                <span class="input-group-btn">
                  <button class="btn btn-default save" type="button"
                          data-url="special-sticker-number"
                          data-id="<?= $model->id ?>" data-input="special-sticker"
                          data-result="special-result">Save</button>
                </span>
                <div style="position: relative">
                  <input class="form-control title-input" type="text"
                         id="special-sticker" data-check="special-checkbox"
                         value="<?= $model->specialStickerNumber ?>">
                  <span style="position: absolute; top:7px; right:10px;z-index: 900"
                        id="special-result">
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </li>
    </ul>

    <div class="subhead">
      <h3 class="setting-header">Техническое обслуживание</h3>
    </div>
    <ul class="list-group">
      <li class="list-group-item">
        <div class="form-checkbox js-complex-option">
          <label style="font-weight: 500">
            <input class="ch" type="checkbox" data-check='maintenance-check'
                   data-id="<?= $model->id ?>"
                   data-url='maintenance' <?php if ($model->toStatus) echo 'checked' ?>>
            В графике ТО</label>
          <span class="status-indicator" id="maintenance-check"></span>
          <p class="note" style="margin-bottom: 10px">Отображать в графике ТО.</p>
        </div>
      </li>
      <li class="list-group-item">
        <div class="form-checkbox js-complex-option">
          <input class="ch" id="maintenance-work-feature" type="checkbox" data-check='work-count-check'
                 data-url='work-count'>
          <label for="maintenance-work-feature" style="font-weight: 500">Наработка</label>
          <span class="status-indicator" id="work-count-check"></span>
          <p class="note" style="margin-bottom: 10px">Вести учет наработанного времени.</p>
          <div class="d-blue border">
            <div class="form-checkbox js-complex-option">
              <label style="font-weight: 500">Шаблон учета времени</label>
              <p class="note pr-6">Учет наработанного времени будет вычислятся по указанному шаблону (по умолчанию - 8ми
                часовой рабочий день).</p>
            </div>
            <div class="form-checkbox">
              <div class="input-group" style="padding-right: 20px">
                  <span class="input-group-addon">
                    <input type="checkbox" style="margin: 0">
                  </span>
                <select class="form-control">
                  <option value="0" disabled selected>Выберите</option>
                  <option value="1">Круглосуточное</option>
                  <option value="2">Рабочий день</option>
                  <option value="3">Шаблон 2</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </li>
    </ul>

    <div class="subhead">
      <h3 class="subsetting-header">Бухгалтерский учет</h3>
    </div>
    <ul class="list-group">
      <li class="list-group-item">
        <div class="form-checkbox js-complex-option">
          <input class="ch" id="inventory_feature" type="checkbox" data-check='inventory-check' data-url='inventory'>
          <label for="inventory_feature" style="font-weight: 500">Инвентаризация</label>
          <span class="status-indicator" id="inventory-check"></span>
          <p class="note" style="margin-bottom: 10px">Отображать в таблице бухгалтерского учета.</p>
          <div class="d-blue border">
            <div class="form-checkbox js-complex-option">
              <label style="font-weight: 500">Наименование в таблице</label>
              <p class="note pr-6">При отличии наименования в таблице инвентаризации от истинного наименования
                оборудования,
                укажите необходимое наименование.</p>
            </div>
            <div class="form-checkbox">
              <div class="input-group" style="padding-right: 20px">
                  <span class="input-group-addon">
                    <input type="checkbox" aria-label="..." style="margin: 0">
                  </span>
                <input type="text" class="form-control" aria-label="...">
              </div>
            </div>
          </div>
        </div>
      </li>
      <li class="list-group-item">
        <div class="form-checkbox js-complex-option">
          <input class="ch" id="metals_feature" type="checkbox" data-check='metals-check' data-url='metals'>
          <label for="metals_feature" style="font-weight: 500">Драгоценные металлы</label>
          <span class="status-indicator" id="metals-check"></span>
          <p class="note">Отображать в таблице учета драгоценных металлов.</p>
        </div>
      </li>
    </ul>

    <div class="head subhead">
      <h3 class="setting-header">Логирование</h3>
    </div>
    <p class="note-2">
      <a href="#">Просмотреть ЛОГ</a>
    </p>
    <ul class="list-group">
      <li class="list-group-item">
        <div class="form-checkbox js-complex-option">
          <input class="ch" id="log_feature" type="checkbox" data-check='log-check' data-url='logging'>
          <label for="log_feature" style="font-weight: 500">Вести лог</label>
          <span class="status-indicator" id="log-check"></span>
          <p class="note">Позволяет системе отслеживать и записывать производимые действия над оборудованием.</p>
        </div>
      </li>
    </ul>

    <div class="subhead">
      <h3 class="subsetting-header">Danger zone</h3>
    </div>
    <ul class="list-group">
      <li class="list-group-item" style="border-color: #ed1d1a">
        <div class="d-flex flex-items-center">
          <div class="form-checkbox js-complex-option">
            <label style="font-weight: 500">Удаление</label>
            <p class="note pr-6">После удаления данные восстановить не удастся.</p>
          </div>
          <div style="text-align: right">
            <a id="delete-object" class="btn btn-sm btn-danger mr-5">Удалить</a>
          </div>
        </div>
      </li>
    </ul>
  </div>
</div>

<script>

    $(document).ready(function () {
        var successCheck = '<i class="fa fa-check" id="consolidated-check" aria-hidden="true" style="color: #4eb305"></i>';
        var warningCheck = '<i class="fa fa-times" id="consolidated-check" aria-hidden="true" style="color: #cc0000"></i>';
        var infoCheck = '<i class="fa fa-exclamation" id="consolidated-check" aria-hidden="true" style="color: #cc0000"></i>';
        var waiting = '<i class="fa fa-cog fa-spin" aria-hidden="true"></i>';
        var csrf = $('meta[name=csrf-token]').attr("content");

        $('.ch').change(function (e) {
            var checkId = $(this).data('check');
            $('#' + checkId).html(waiting);
            var url = $(this).data('url');
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


        $('.title-input').on('input', function (e) {
            var checkId = $(this).data('check');
            var resultH = $(this).data('result');
            $('#' + checkId).prop('checked', false);
            $('#' + resultH).html('');
        });


        $('.save').on('click', function () {
            var nodeId = $(this).data('id');
            var inputHId = $(this).data('input');
            var input = $('#' + inputHId);
            var title = input.val();
            var resultH = $(this).data('result');
            var url = $(this).data('url');
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

        $('.input-check').change(function (e) {
            var bool = $(this).is(':checked');
            var inputHId = $(this).data('input');
            var input = $('#' + inputHId);
            var title = input.val();
            var resultH = $(this).data('result');
            var nodeId = $(this).data('id');
            var url = $(this).data('url');
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


        $('#wrap').change(function (e) {
            var checkId = $(this).data('check');
            $('#' + checkId).html(waiting);
            var url = $(this).data('url');
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
                    jc = $.confirm({
                        icon: 'fa fa-thumbs-up',
                        title: 'Успех!',
                        content: 'Данный узел объявлен как обертка. Страница перезагрузится!',
                        type: 'green',
                        buttons: false,
                        closeIcon: false,
                        autoClose: 'ok|8000',
                        confirmButtonClass: 'hide',
                        buttons: {
                            ok: {
                                btnClass: 'btn-success',
                                action: function () {
                                    window.location.href = 'wrap-config';
                                }
                            }
                        }
                    });
                },
                error: function (data) {
                    $('#' + checkId).html(warningCheck);
                }
            });
        });


    })
</script>