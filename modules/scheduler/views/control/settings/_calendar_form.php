<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\ColorPickerAsset;

ColorPickerAsset::register($this);

?>
<style>
  h2 {
    font-family: 'Google Sans', Roboto, Arial, sans-serif;
    font-size: 18px;
    font-weight: 400;
    line-height: 24px;
    padding-left: 0;
    margin-top: 0;
    color: #70757a;
  }
  .create-calendar {
    margin: 10px 20px 0 0;
  }
</style>

<div class="col-lg-6 col-md-6">
  <h2>Создать календарь</h2>
  <br>
  <?php $form = ActiveForm::begin(['id' => 'new-calendar', 'options' => ['enctype' => 'multipart/form-data']]); ?>

  <div class="form-group">
    <?= $form->field($model, 'title')->textInput([
      'class' => 'form-control',
      'id' => 'event-title'
    ])->hint(' ', ['class' => ' w3-label-under']); ?>
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
  <button id="save-new-calendar" class="btn btn-primary create-calendar">Создать календарь</button>
</div>


<script>
  $(document).ready(function () {

    $('#colorpicker').simplecolorpicker();

  });

</script>