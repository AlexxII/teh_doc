<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\file\FileInput;


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
        <?= $form->field($model, "imageFiles[]")->widget(FileInput::class, [
          'language' => 'ru',
          'options' => ['multiple' => true],
          'pluginOptions' => [
            'maxFileCount' => 15,
            'uploadUrl' => Url::to(['file-upload']),
            'showUpload' => false,
            'previewFileType' => 'any',
            'overwriteInitial' => false
          ],
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