<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AirDatepickerAsset;
use app\assets\BootstrapDatepickerAsset;
use app\assets\ColorPickerAsset;

BootstrapDatepickerAsset::register($this);
ColorPickerAsset::register($this);

?>

<style>
  .datepicker {
    z-index: 999999999;
  }
  .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background-color: #fff;
    opacity: 1;
  }
</style>


<div>
  <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

  <div class="form-group">
    <?= $form->field($model, 'title')->textInput([
      'class' => 'form-control',
      'id' => 'event-title'
    ])->hint(' ', ['class' => ' w3-label-under']); ?>
  </div>
  <div class="form-group">
    <div class="col-lg-6 col-md-6">
      <?= $form->field($model, 'start_date')->textInput([
        'class' => 'date form-control',
        'id' => 'start-date',
        'readonly' => 'true'
      ])->hint(' ', ['class' => ' w3-label-under']); ?>
    </div>
    <div class="col-lg-6 col-md-6">
      <?= $form->field($model, 'end_date')->textInput([
        'class' => 'date form-control',
        'id' => 'end-date',
        'readonly' => 'true'
      ])->hint(' ', ['class' => ' w3-label-under']); ?>
    </div>
  </div>
  <div class="form-group">
    <?= $form->field($model, 'description')->textArea([
      'style' => 'resize:vertical',
      'rows' => '2',
      'id' => 'event-description'
    ]) ?>
  </div>
  <div class="form-group">
    <?= $form->field($model, 'color', [
      'template' => '{label} {input}{hint}'
    ])->dropDownList($model->colorList, [
      'id' => 'colorpicker'
    ])->hint('', ['class' => ' w3-label-under']);
    ?>
  </div>

  <?php ActiveForm::end(); ?>
</div>


<script>
  $(document).ready(function () {

    var stDate = $('#start-date').val();
    var eDate = $('#end-date').val();

    $('#start-date').datepicker({
      autoClose: true,
      language: "ru",
      todayHighlight: true,
      weekends: [6, 0],
      timepicker: true
    });

    $('#end-date').datepicker({
      autoClose: true,
      language: "ru",
      todayHighlight: true,
      weekends: [6, 0],
      timepicker: true
    });

    $('#colorpicker').simplecolorpicker();
  });

</script>