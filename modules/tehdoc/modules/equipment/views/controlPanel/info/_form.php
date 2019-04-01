<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use app\modules\tehdoc\models\Equipment;
use app\modules\tehdoc\asset\TehFormAsset;

?>

<style>
  .nonreq {
    color: #1e6887;
    font-size: 14px;
  }
  .select-selected {
    padding-left: 40px;
  }
</style>

<?php
TehFormAsset::register($this);

// текст к подсказкам
$class_hint = 'Выберите класс оборудования.';
$cat_hint = 'Необходима для классификации оборудования.';
$title_hint = 'Необходимо для отображения в таблице.';
$serial_hint = 'Укажите серийный номер (s/n), на некоторых моделях оборудования указывается только производственный номер (p/n), 
                  тогда укажите его.';
$invent_hint = 'Укажите инвентарный номер.';
$oTime_hint = 'Если известно, укажите наработку в часах на день занесения.';
$place_hint = 'Укажите точное размещение оборудования.';
$date_hint = 'Если не известен месяц, выберите январь известного года.';
$quantity_hint = 'Внимание! Указывайте отличную от 1 цифру 
ТОЛЬКО для идентичного оборудования и расходных материалов. Например: офисная бумага, батарейки. 
Будьте ВНИМАТЕЛЬНЫ, не вводите себя в заблуждение.';

?>

<div class="row">
  <div class="col-lg-12 col-md-12" style="border-radius:2px;padding-top:10px">
    <div class="customer-form">
      <?php $form = ActiveForm::begin([
        'options' => [
          'enctype' => 'multipart/form-data'],
        'action' => 'index'
      ]); ?>
      <li class="list-group-item" style="margin-bottom: 15px">
        <div class="form-checkbox js-complex-option">
          <input class="ch" id="consolidated-feature" type="checkbox" data-check='consolidated-check'
                 data-id="<?= $model->ref ?>" <?php if ($model->task) echo 'checked' ?> >
          <label for="consolidated-feature" style="font-weight: 500">В задание на обновление</label>
          <span class="status-indicator" id="consolidated-check"></span>
          <p class="note" style="margin-bottom: 10px">Добавить данное оборудование в задание на обновление данных.</p>
        </div>
      </li>
      <div class="row">
        <div class="col-md-6 col-lg-6">
          <?php
          echo $form->field($model, 'category_id', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $cat_hint . '"></sup>{input}{hint}'])
            ->dropDownList($model->toolCategoryList, ['data-name' => 'vks_type', 'prompt' => ['text' => 'Выберите',
              'options' => [
                'value' => 'none',
                'disabled' => 'true',
                'selected' => 'true'
              ]]])->hint('Выберите категорию', ['class' => ' w3-label-under']);
          ?>
        </div>
        <div class="col-md-6 col-lg-6">
          <?= $form->field($model, 'eq_title', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $title_hint . '"></sup>{input}{hint}'])
            ->textInput()->hint('Например: Коммутатор с автоопределителем', ['class' => ' w3-label-under']); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-6">
          <?= $form->field($model, 'eq_manufact')->textInput(['id' => 'manufact'])
            ->hint('Например: HP, ACER', ['class' => ' w3-label-under']); ?>
        </div>
        <div class="col-md-6 col-lg-6">
          <?= $form->field($model, 'eq_model')->textInput(['id' => 'models'])
            ->hint('Например: LJ 1022', ['class' => ' w3-label-under']); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-6">
          <?= $form->field($model, 'eq_serial', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $serial_hint . '"></sup>{input}{hint}'
          ])->textInput()->hint('Например: HRUEO139UI92', ['class' => ' w3-label-under']); ?>
        </div>
        <div class="col-md-6 col-lg-6">
          <?= $form->field($model, 'invent_number', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $invent_hint . '"></sup>{input}{hint}'
          ])->textInput()->hint('Например: 20205147', ['class' => ' w3-label-under']); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-6">
          <?= $form->field($model, 'eq_factdate', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $date_hint . '"></sup>{input}{hint}'
          ])->textInput([
            'class' => 'fact-date form-control'
          ])->hint('Введите или выберите дату', ['class' => ' w3-label-under']); ?>
        </div>
        <div class="form-group col-md-6 col-lg-6">
          <?= $form->field($model, 'eq_operating_time', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $oTime_hint . '"></sup>{input}{hint}'
          ])->textInput()->hint('Например: 124948', ['class' => ' w3-label-under']); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <?php
          echo $form->field($model, 'place_id', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $place_hint . '"></sup>{input}{hint}'
          ])->dropDownList($model->toolPlacesList, ['data-name' => 'vks_type', 'prompt' => ['text' => 'Выберите',
            'options' => [
              'value' => 'none',
              'disabled' => 'true',
              'selected' => 'true'
            ]]])->hint('Выберите место нахождения оборудования', ['class' => ' w3-label-under']);
          ?>
        </div>
        <div class="col-md-4">
          <?= $form->field($model, 'quantity', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $quantity_hint . '"></sup>{input}{hint}'])
            ->textInput()->hint('Введите количество', ['class' => ' w3-label-under']); ?>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 col-lg-12">
          <?= $form->field($model, 'eq_comments')->textArea(array('style' => 'resize:vertical', 'rows' => '2')) ?>
        </div>
      </div>

      <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => 'btn btn-primary']) ?>
      </div>
    </div>
  </div>
</div>

<?php ActiveForm::end(); ?>

<script>


  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('#tools-eq_title').on('input', function (e) {
      var node = $("#fancyree_w0").fancytree("getActiveNode");
      node.setTitle($(this).val());
      node.render();
    })

  });

  $(document).ready(function () {
    $('.fact-date').datepicker({
      format: 'MM yyyy г.',
      autoclose: true,
      language: "ru",
      startView: "months",
      minViewMode: "months",
      clearBtn: true
    })
  });

  $(document).ready(function () {
    if ($('.fact-date').val()) {
      var date = new Date($('.fact-date').val());
      moment.locale('ru');
      $('.fact-date').datepicker('update', moment(date).format('MMMM YYYY'))
    }
  });

  //преобразование дат перед отправкой
  $(document).ready(function () {
    $('#w0').submit(function () {
      var d = $('.fact-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
      $('.fact-date').val(d);
    });
  });

  function contains(arr, elem) {
    for (var i = 0; i < arr.length; i++) {
      if (arr[i] === elem) {
        return true;
      }
    }
    return false;
  }

  // функционал улучшения интерфейса формы

  $(document).ready(function () {
    $.ajax({
      type: 'get',
      url: '/tehdoc/settings/interface/manufact',
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
  });

  $(document).ready(function () {
    $.ajax({
      type: 'get',
      url: '/tehdoc/settings/interface/models',
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
  });

  $(document).ready(function () {
    var successCheck = '<i class="fa fa-check" id="consolidated-check" aria-hidden="true" style="color: #4eb305"></i>';
    var warningCheck = '<i class="fa fa-times" id="consolidated-check" aria-hidden="true" style="color: #cc0000"></i>';
    var waiting = '<i class="fa fa-cog fa-spin" aria-hidden="true"></i>';
    $('.ch').change(function (e) {
      var checkId = $(this).data('check');
      var csrf = $('meta[name=csrf-token]').attr("content");
      $('#' + checkId).html(waiting);
      var url = '/tehdoc/equipment/control-panel/settings/task-set';
      var nodeId = $(this).data('id');
      var result = $(this).is(':checked');
      console.log(result);
      $.ajax({
        url: url,
        type: "post",
        data: {
          toolId: nodeId,
          _csrf: csrf,
          bool: result
        },
        success: function (data) {
          $('#' + checkId).html(successCheck);
        },
        error: function (data) {
          $('#' + checkId).html(warningCheck);
        }
      });
    })
  })

</script>


