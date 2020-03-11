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
    <?= $form->field($model, 'duration')->hiddenInput([
      'class' => 'form-control',
      'id' => 'duration',
      'readonly' => 'true',
    ])->label(false)->hint(' ', ['class' => ' w3-label-under']); ?>
    <div class="col-lg-6 col-md-6">
      <?= $form->field($model, 'user_id')->dropDownList($model->userList, [
        'id' => 'user'
      ])->hint('', ['class' => ' w3-label-under']);
      ?>
    </div>
    <div class="col-lg-6 col-md-6">
      <?= $form->field($model, 'duty_type')->dropDownList($model->dutyList, [
        'prompt' => ['text' => 'Выберите',
          'options' => [
            'value' => 'none',
            'disabled' => 'true',
            'selected' => 'true'
          ]],
        'class' => 'form-control',
        'id' => 'duty-type'
      ])->hint(' ', ['class' => ' w3-label-under']); ?>
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