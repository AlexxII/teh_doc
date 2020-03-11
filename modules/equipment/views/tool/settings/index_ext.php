<div>
  <div id="complex-settings" style="margin: 10px">
    <div class="settings">
      <div class="subhead">
        <h3 class="setting-header">Отображение</h3>
      </div>
      <ul class="list-group">
        <li class="list-group-item">
          <div class="form-checkbox js-complex-option">
            <label>
              <input class="check-it" id="general-feature" type="checkbox" data-id="<?= $model->id ?>"
                     data-check='general-check'
                     data-url='general-table' <?php if ($model->settings->eq_general) echo 'checked' ?>>
              В сводной таблице
            </label>
            <span class="status-indicator" id="general-check"></span>
            <p class="note">Отображать данный узел в сводной таблице.</p>
          </div>
        </li>

        <li class="list-group-item">
          <div class="form-checkbox js-complex-option">
            <label>
              <input class="check-it" type="checkbox"
                     id="oth-feature"
                     data-id="<?= $model->id ?>"
                     data-check='oth-check' data-url='oth' <?php if ($model->othStatus) echo 'checked' ?>>
              В перечне ОТХ
            </label>
            <span class="status-indicator" id="oth-check"></span>
            <p class="note">Отображать в таблице ОТХ.</p>
            <div class="d-blue border">
              <div class="form-checkbox js-complex-option">
                <label>Наименование в перечне</label>
                <p class="note pr-6">При отличии наименования в перечне ОТХ от истинного наименования оборудования,
                  укажите необходимое наименование.</p>
              </div>
              <div class="form-checkbox">
                <div class="input-group" style="position: relative">
                  <span class="input-group-addon">
                    <input class="input-check" type="checkbox" style="margin: 0"
                           id="oth-checkbox" data-input="oth-title" data-result="oth-result"
                           data-url="oth-title"
                           data-id="<?= $model->id ?>" <?php if ($model->othTitleCheck) echo 'checked' ?>>
                  </span>
                  <input class="form-control title-input" type="text"
                         id="oth-title" data-check="oth-checkbox" data-result="oth-result"
                         value="<?= $model->othTitle ?>">
                  <span class="result-check" id="oth-result"></span>
                </div>
              </div>
            </div>
          </div>
        </li>

        <li class="list-group-item">
          <div class="form-checkbox js-complex-option">
            <label>
              <input class="check-it" type="checkbox"
                     data-id="<?= $model->id ?>"
                     data-check='complex-check' data-url='complex' <?php if ($model->complex) echo 'checked' ?>>
              Комплект
            </label>
            <span class="status-indicator" id="complex-check"></span>
            <p class="note">Отображать как комплект.</p>
          </div>
        </li>
      </ul>

      <div class="subhead">
        <h3 class="setting-header">Специальные работы
          <i class="fa fa-shield" aria-hidden="true" title=" Проведены Специальные работы"></i>
        </h3>
      </div>
      <ul class="list-group">
        <li class="list-group-item">
          <div class="form-checkbox js-complex-option">
            <label>
              <input class="check-it" type="checkbox" id="special_works_feature"
                     data-id="<?= $model->id ?>"
                     data-check='special-check'
                     data-url='special-works' <?php if ($model->specialStatus) echo 'checked' ?>>
              Специальные работы</label>
            <span class="status-indicator" id="special-check"></span>
            <p class="note">Над данным оборудованием были проведены специальные работы.</p>
            <div class="d-blue border">
              <div class="form-checkbox js-complex-option">
                <label>Номера наклеек</label>
                <p class="note pr-6">Укажите номера галограмм. Например: Л 727 7806339 или только партию 727 (если
                  указана
                  только она)</p>
              </div>
              <div class="form-checkbox">
                <div class="input-group" style="position: relative">
                <span class="input-group-btn">
                  <button class="btn btn-default save-title" type="button"
                          data-url="special-sticker-number"
                          data-id="<?= $model->id ?>" data-input="special-sticker"
                          data-result="special-result">Save</button>
                </span>
                  <input class="form-control title-input" type="text"
                         id="special-sticker" data-check="special-checkbox"
                         value="<?= $model->specialStickerNumber ?>">
                  <span class="result-check" id="special-result">
                  </span>
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
            <label>
              <input class="check-it" type="checkbox" data-check='maintenance-check'
                     data-id="<?= $model->id ?>"
                     data-url='maintenance' <?php if ($model->toStatus) echo 'checked' ?>>
              В графике ТО</label>
            <span class="status-indicator" id="maintenance-check"></span>
            <p class="note">Отображать в графике ТО.</p>
          </div>
        </li>
        <li class="list-group-item">
          <div class="form-checkbox js-complex-option">
            <input class="check-it" id="maintenance-work-feature" type="checkbox" data-check='work-count-check'
                   data-url='work-count'>
            <label for="maintenance-work-feature">Наработка</label>
            <span class="status-indicator" id="work-count-check"></span>
            <p class="note">Вести учет наработанного времени.</p>
            <div class="d-blue border">
              <div class="form-checkbox js-complex-option">
                <label>Шаблон учета времени</label>
                <p class="note pr-6">Учет наработанного времени будет вычислятся по указанному шаблону (по умолчанию -
                  8ми
                  часовой рабочий день).</p>
              </div>
              <div class="form-checkbox">
                <div class="input-group">
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
            <input class="check-it" id="inventory_feature" type="checkbox" data-check='inventory-check' data-url='inventory'>
            <label for="inventory_feature">Инвентаризация</label>
            <span class="status-indicator" id="inventory-check"></span>
            <p class="note">Отображать в таблице бухгалтерского учета.</p>
            <div class="d-blue border">
              <div class="form-checkbox js-complex-option">
                <label>Наименование в таблице</label>
                <p class="note pr-6">При отличии наименования в таблице инвентаризации от истинного наименования
                  оборудования,
                  укажите необходимое наименование.</p>
              </div>
              <div class="form-checkbox">
                <div class="input-group">
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
            <input class="check-it" id="metals_feature" type="checkbox" data-check='metals-check' data-url='metals'>
            <label for="metals_feature">Драгоценные металлы</label>
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
            <input class="check-it" id="log_feature" type="checkbox" data-check='log-check' data-url='logging'>
            <label for="log_feature">Вести лог</label>
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
              <label>Удаление</label>
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
</div>

<script>

  $(document).ready(function () {

  })
</script>