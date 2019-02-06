<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;

?>
<div class="tool-view">

  <div class="row">

    <div class="col-lg-9 col-md-6">
      <h3 style="margin-top: 0px"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="col-lg-3 col-md-6 text-right">
      <p>
        <?= Html::a('Изменить', ['update-c', 'id' => $model->ref], ['class' => 'btn btn-sm btn-primary ']) ?>
      </p>
    </div>
  </div>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      'id',
      'name'
    ],
  ]) ?>

</div>