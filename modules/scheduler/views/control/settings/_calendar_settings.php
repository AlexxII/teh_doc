<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\ColorPickerAsset;

ColorPickerAsset::register($this);

?>
<style>
  .calendar-settings h2 {
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
  .calendar-settings button {
    margin-left: 0;
  }
  .calendar-settings hr {
    margin-top: 10px;
    margin-bottom: 10px;
  }
</style>

<div class="col-lg-6 col-md-6 calendar-settings">
  <span id="calendar-id" style="display: none"><?= $model->id ?></span>
  <h2>Настройки календаря</h2>
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
      'id' => 'colorpic'
    ])->hint('', ['class' => ' w3-label-under']);
    ?>
  </div>
  <?php ActiveForm::end(); ?>

  <br>
  <h2>Разрешения на доступ</h2>
  <hr>
  <p><label><input type="checkbox"> Сделать общедоступным</label></p>

  <br>
  <br>
  <h2>Удаление календаря</h2>
  <hr>
  <p style="font-size: 10px">Окончательное удаление календаря и его событий из базы данных.</p>
  <p>
    <button class="btn btn-default">Удалить</button>
  </p>


</div>


<script>

  $(document).ready(function () {

    $('#colorpic').simplecolorpicker({
      theme: 'glyphicons'
    }).on('change', function (e) {
      var id = $('#calendar-id').text();
      var color = $(this).val();
      var url = '/scheduler/control/settings/calendar-color?id=' + id + '&color='+color;
      $.ajax({
        url: url,
        method: 'POST'
      }).done(function (response) {
        // $('#setting-content').html(response.data.data);
      }).fail(function () {
        console.log('Что-то пошло не так');
      });
    });

  })

</script>