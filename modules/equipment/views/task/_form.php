<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;

// текст к подсказкам
$cat_hint = 'Обязательное! Необходима для классификации оборудования.';
$title_hint = 'Обязательное! Необходимо для отображения в таблице.';
$serial_hint = 'Укажите серийный номер (s/n), на некоторых моделях оборудования указывается только производственный номер (p/n), 
                  тогда укажите его.';
$invent_hint = 'Укажите инвентарный номер.';
$oTime_hint = 'Если известно, укажите наработку в часах на день занесения.';
$place_hint = 'Обязательное! Укажите точное размещение оборудования.';
$date_hint = 'Если не известен месяц, выберите январь известного года.';
$quantity_hint = 'Внимание! Указывайте отличную от 1 цифру 
ТОЛЬКО для идентичного оборудования и расходных материалов. Например: офисная бумага, батарейки. 
Будьте ВНИМАТЕЛЬНЫ, не вводите себя в заблуждение.';

?>

<span><small><?= $model->toolParents(0) ?></small></span>

<div class="tool-update-form">
  <div class="col-lg-12 col-md-12" style="border-radius:2px;padding-top:10px">
    <div class="customer-form">
      <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => '']]); ?>
      <?= Html::hiddenInput('eqId', $model->tempId); ?>
      <div class="row">
        <div class="col-md-6 col-lg-6">
          <?php
          echo $form->field($model, 'category_id', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-container="body" data-placement="top" title="' . $cat_hint . '"></sup>{input}{hint}'])
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
                data-toggle="tooltip" data-container="body"
                data-placement="top" title="' . $title_hint . '"></sup>{input}{hint}'])
            ->textInput(['data-name' => $model->eq_title])->hint('Например: Коммутатор с автоопределителем', ['class' => ' w3-label-under']); ?>
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
                data-toggle="tooltip" data-container="body" data-placement="top" title="' . $serial_hint . '"></sup>{input}{hint}'
          ])->textInput()->hint('Например: HRUEO139UI92', ['class' => ' w3-label-under']); ?>
        </div>
        <div class="col-md-6 col-lg-6">
          <?= $form->field($model, 'invent_number', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-container="body" data-placement="top" title="' . $invent_hint . '"></sup>{input}{hint}'
          ])->textInput()->hint('Например: 20205147', ['class' => ' w3-label-under']); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-6">
          <?= $form->field($model, 'eq_factdate', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-container="body" data-placement="top" title="' . $date_hint . '"></sup>{input}{hint}'
          ])->textInput([
            'class' => 'fact-date form-control', 'readonly'=> true
          ])->hint('Введите или выберите дату', ['class' => ' w3-label-under']); ?>
        </div>
        <div class="form-group col-md-6 col-lg-6">
          <?= $form->field($model, 'eq_operating_time', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-container="body" data-placement="top" title="' . $oTime_hint . '"></sup>{input}{hint}'
          ])->textInput()->hint('Например: 124948', ['class' => ' w3-label-under']); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <?php
          echo $form->field($model, 'place_id', [
            'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-container="body" data-placement="top" title="' . $place_hint . '"></sup>{input}{hint}'
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
                data-toggle="tooltip" data-container="body" data-placement="top" title="' . $quantity_hint . '"></sup>{input}{hint}'])
            ->textInput()->hint('Введите количество', ['class' => ' w3-label-under']); ?>
        </div>
      </div>

      <?php
      if (!empty($model->images)) {
        foreach ($model->images as $k => $photo) {
          $allImages[] = "<img src='" . $photo->getImageUrl() . "' class='file-preview-image'
                          style='max-width:100%;max-height:100%'>";
          $previewImagesConfig[] = [
            'url' => Url::toRoute(ArrayHelper::merge(['/equipment/tool/images/delete-from-task'], [
              '_csrf' => Html::csrfMetaTags(),
              'id' => $photo->id
            ])),
            'key' => $photo->id
          ];
        }
      } else {
        $previewImagesConfig = false;
        $allImages = false;
      }
      ?>
      <div class="row">
        <div class="col-md-12 col-lg-12">
          <?= $form->field($fUpload, "imageFiles[]")->widget(FileInput::class, [
            'language' => 'ru',
            'options' => ['multiple' => true],
            'pluginOptions' => [
              'maxFileCount' => 15,
              'uploadUrl' => '/equipment/tool/images/upload-images',
              'uploadExtraData' => [
                'eqId' => $model->id,
              ],
              'showUpload' => false,
              'previewFileType' => 'any',
              'initialPreview' => $allImages,
              'initialPreviewConfig' => $previewImagesConfig,
              'overwriteInitial' => false,
            ],
          ]); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-lg-12">
          <?= $form->field($model, 'eq_comments')->textArea(array('style' => 'resize:vertical', 'rows' => '2')) ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php ActiveForm::end(); ?>

<script>

  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('#tools-category_id').on('change', function (e) {
      var text = $("#tools-category_id option:selected").text();
      if ($('#tools-eq_title').data('name') === undefined) {
        $('#tools-eq_title').val(text);
      }
    });

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

  function contains(arr, elem) {
    for (var i = 0; i < arr.length; i++) {
      if (arr[i] === elem) {
        return true;
      }
    }
    return false;
  }

  // функционал улучшения интерфецса формы

  $(document).ready(function () {
    $.ajax({
      type: 'get',
      url: '/equipment/control/interface/manufact',
      autoFocus: true,
      success: function (data) {
        var manufact = $.parseJSON(data);
        $(function () {
          $("#manufact").autocomplete({
            source: manufact
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
      url: '/equipment/control/interface/models',
      autoFocus: true,
      success: function (data) {
        var models = $.parseJSON(data);
        $(function () {
          $("#models").autocomplete({
            source: models
          });
        });
      },
      error: function (data) {
        console.log('Error loading Models list');
      }
    });
  });

</script>