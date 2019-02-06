<?php

use yii\helpers\Html;

$this->title = 'Создать';

?>

<div class="tool-update">

  <h3><?= Html::encode($this->title) ?></h3>

  <?= $this->render('_form', [
    'model' => $model
  ]) ?>

</div>
