<?php
use yii\widgets\DetailView;
?>

<style>
  .region-title {
    font-size: 14px;
    font-weight: 500;
  }
  #region-detail-tbl th {
    font-size: 12px;
    width: 25%;
  }
  .region-title {
    padding-bottom: 10px;
  }
</style>

<div class="region-detail">
  <div class="region-title"><?= $model->name?></div>
  <?= DetailView::widget([
    'model' => $model,
    'id' => 'region-detail-tbl',
    'attributes' => [
      [
        'label' => 'Код:',
        'value' => $model->region_number
      ],
      [
        'label' => 'Центр:',
        'value' => $model->region_center
      ],
      [
        'label' => 'Площадь:',
        'value' => $model->region_area
      ],
      [
        'label' => 'Население:',
        'value' => $model->region_population
      ],
    ]
  ]) ?>

</div>
