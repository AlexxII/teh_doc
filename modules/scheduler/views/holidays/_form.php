<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\BootstrapDatepickerAsset;

BootstrapDatepickerAsset::register($this);

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
    <div class="col-lg-12 col-md-12">
      <?= $form->field($model, 'title')->textInput([
        'class' => 'form-control',
        'id' => 'holiday-title'
      ])->hint(' ', ['class' => ' w3-label-under']); ?>
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-6 col-md-6">
      <?= $form->field($model, 'duration')->textInput([
        'class' => 'form-control',
        'id' => 'duration',
        'readonly' => 'true',
      ])->hint(' ', ['class' => ' w3-label-under']); ?>
    </div>
    <div class="col-lg-6 col-md-6">
      <?= $form->field($model, 'approval_year')->textInput([
        'class' => 'form-control',
        'id' => 'approval-year',
        'readonly' => 'true',
      ])->hint(' ', ['class' => ' w3-label-under']); ?>
    </div>
  </div>
  <div class="form-group">
    <div class="col-lg-8 col-md-8">
      <?= $form->field($model, 'holiday_type')->dropDownList($model->holidayType, [
        'id' => 'holiday-type'
      ])->hint('', ['class' => ' w3-label-under']);
      ?>
    </div>
    <div class="col-lg-4 col-md-4">
      <?= $form->field($model, 'year_repeat', [
        'template' => '<div style="margin-top: 30px">{input} {label}</div>',
        'labelOptions' => ['for' => 'year-repeat']])->checkbox(
        ['uncheck' => 0, 'class' => 'hidden-face', 'id' => 'year-repeat',],
        false); ?>
    </div>
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
    <div class="col-lg-12 col-md-12">
      <?= $form->field($model, 'description')->textArea([
        'style' => 'resize:vertical',
        'rows' => '2',
        'id' => 'holiday-description'
      ]) ?>
    </div>
  </div>
  <?php ActiveForm::end(); ?>
</div>


<script>
  $(document).ready(function () {

    $('#start-date').datepicker({
      autoclose: true,
      language: "ru",
      todayHighlight: true,
      weekends: [6, 0]
    });

    $('#end-date').datepicker({
      autoclose: true,
      language: "ru",
      todayHighlight: true,
      weekends: [6, 0]
    });

    $('#approval-year').datepicker({
      autoclose: true,
      format: 'yyyy',
      language: "ru",
      todayHighlight: true,
      minViewMode: 'years'
    });

  });

  $('.date').on('change', function (e) {
    var def = e.target.defaultValue;
    var strStartDate = $('#start-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
    var strEndDate = $('#end-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
    var startDate = new Date(strStartDate);
    var endDate = new Date(strEndDate);

    var diff = endDate - startDate;
    if (diff >= 0) {
      var duration = (diff / (24 * 60 * 60 * 1000)) + 1;
      $('#duration').val(duration);
    } else {
      $(e.target).datepicker('update', def);
    }
  });


</script>