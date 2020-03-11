<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\BootstrapDatepickerAsset;
use app\assets\ColorPickerAsset;

BootstrapDatepickerAsset::register($this);
ColorPickerAsset::register($this);

?>

<?php if ($model->isNewRecord) : ?>
<strong>Добавить:</strong>
<button id="add-vks" class="btn btn-primary btn-sm">ВКС</button>
<?php endif; ?>

<div style="margin-top: 10px">
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
      todayHighlight: true
    });

    $('#end-date').datepicker({
      autoClose: true,
      language: "ru",
      todayHighlight: true
    });

    $('#colorpicker').simplecolorpicker();
  });

</script>