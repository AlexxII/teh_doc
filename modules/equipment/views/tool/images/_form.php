<?php

use yii\widgets\ActiveForm;
use kartik\file\FileInput;

?>

<div id="complex-docs">

  <div style="padding-top: 15px">
    <?php $form = ActiveForm::begin([
      'options' => [
        'enctype' => 'multipart/form-data'],
        'action' => 'create'
    ]); ?>
    <div>
      <div class="col-md-12 col-lg-12">
        <?= $form->field($model, "imageFiles[]")->widget(FileInput::class, [
          'language' => 'ru',
          'options' => ['multiple' => true],
          'pluginOptions' => [
            'maxFileCount' => 15,
            'showUpload' => false,
            'previewFileType' => 'any',
            'overwriteInitial' => false
          ],
        ]); ?>
      </div>
    </div>

    <?php ActiveForm::end(); ?>
  </div>

</div>