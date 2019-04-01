<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\file\FileInput;

$title_hint = 'Укажите точное наименование документа';
$date_hint = 'Дата документа';

?>

<div id="complex-docs">

  <div style="padding-top: 15px">
    <?php $form = ActiveForm::begin([
      'options' => [
        'enctype' => 'multipart/form-data'],
      'action' => 'create'
    ]); ?>
    <div class="row">
      <div class="col-md-12 col-lg-12">
        <?= $form->field($model, 'doc_title', [
          'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $title_hint . '"></sup>{input}{hint}'])
          ->textInput()->hint('Например: Акт готовности объекта', ['class' => ' w3-label-under']); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6ы col-lg-6">
        <?= $form->field($model, 'doc_date', [
          'template' => '{label} <sup class="h-title fa fa-info-circle nonreq" aria-hidden="true"
                data-toggle="tooltip" data-placement="top" title="' . $date_hint . '"></sup>{input}{hint}'
        ])->textInput([
          'class' => 'doc-date form-control'
        ])->hint('Веберите или введите дату документа (формат: дд.мм.гггг)', ['class' => ' w3-label-under']); ?>
      </div>
      <div class="col-md-6 col-lg-6">
        <?= $form->field($model, "docFiles")->widget(FileInput::class, [
          'language' => 'ru',
          'options' => ['multiple' => false],
          'pluginOptions' => [
            'showPreview' => false,
            'showUpload' => false,
            'showCaption' => true,
            'showRemove' => true,
            'browseLabel' => '',
            'removeLabel' => '',
          ]
        ]); ?>
      </div>
    </div>
    <div class="form-group">
      <?= Html::submitButton('Добавить', ['class' => 'btn btn-sm btn-success']) ?>
      <a onclick="history.back();" class="btn btn-sm btn-primary">Отмена</a>
    </div>

    <?php ActiveForm::end(); ?>
  </div>

</div>

<script>

  $(document).ready(function () {

    $('.doc-date').datepicker({
      format: 'dd MM yyyy г.',
      autoclose: true,
      language: "ru",
      clearBtn: true
    });

    $(document).ready(function () {
      $('#w0').submit(function () {
        var d = $('.doc-date').data('datepicker').getFormattedDate('yyyy-mm-dd');
        $('.doc-date').val(d);
      });
    });

    if ($('.doc-date').val()) {
      var date = new Date($('.doc-date').val());
      moment.locale('ru');
      $('.doc-date').datepicker('update', moment(date).format('MMMM YYYY'))
    }

  });
</script>