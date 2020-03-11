<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\tehdoc\asset;
use yii\widgets\DetailView;

$this->title = 'Просмотр';
?>
<style>
  .h-title {
    font-size: 18px;
    color: #1e6887;
  }
  table.detail-view th {
    width: 40%;
  }

</style>

<div class="">
  <div class="container-fluid">
    <?= DetailView::widget([
      'model' => $model,
      'attributes' => [
        [
          'label' => 'Дата проведения ВКС:',
          'value' => date('d.m.Y', strtotime($model->vks_date)) . ' г.'
        ],
        'vks_teh_time_start',
        'vks_work_time_start',
        [
          'label' => 'Тип сеанса ВКС:',
          'value' => $model->type
        ],
        [
          'label' => 'Студия проведения ВКС:',
          'value' => $model->place
        ],
        [
          'label' => 'Абонент:',
          'value' => $model->subscriber
        ],
        [
          'label' => 'Распоряжение:',
          'value' => $model->order
        ],
        [
          'label' => 'Принявший сообщение:',
          'value' => $model->vks_employee_receive_msg
        ],
        [
          'label' => 'Дата сообщения:',
          'value' => date('d.m.Y', strtotime($model->vks_receive_msg_datetime)) . ' г.'
        ],
        [
          'label' => 'Источник сообщения:',
          'value' => $model->sendMsg
        ],
        'vks_comments',
        'vks_record_create',
        'vks_record_update'
      ]
    ]) ?>
  </div>
</div>
<br>

