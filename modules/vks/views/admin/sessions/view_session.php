<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

?>

<div class="view-deleted-session">
  <div class="container-fluid col-lg-6 col-md-6">
    <?= DetailView::widget([
      'model' => $model,
      'attributes' => [
        [
          'label' => 'Дата проведения ВКС:',
          'value' => date('d.m.Y', strtotime($model->vks_date)) . ' г.'
        ],
        [
          'label' => 'Время:',
          'value' => function ($data) {
            $duration = $data->vks_duration_teh ? ' (' . $data->vks_duration_teh . ' мин.)' : '';
            return $data->vks_teh_time_start . ' - ' . $data->vks_teh_time_end . $duration;
          },
        ],
        [
          'label' => 'Время:',
          'value' => function ($data) {
            $duration = $data->vks_duration_work ? ' (' . $data->vks_duration_work . ' мин.)' : '';
            return $data->vks_work_time_start . ' - ' . $data->vks_work_time_end . $duration;
          },
        ],
        [
          'label' => 'Тип сеанса ВКС:',
          'value' => $model->type
        ],
        [
          'label' => 'Студия проведения ВКС:',
          'value' => $model->place
        ],
        [
          'label' => 'Старший Абонент:',
          'value' => $model->subscriber
        ],
        [
          'label' => 'Абонент в регионе:',
          'value' => $model->subscriberReg
        ],
        [
          'label' => 'Сотрудник СпецСвязи:',
          'value' => $model->employee
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
          'value' => date('d.m.Y', strtotime($model->vks_receive_msg_datetime))
        ],
        [
          'label' => 'Передавший сообщение:',
          'value' => $model->sendMsg
        ],
        'vks_comments',
        'vks_record_create',
        'vks_record_update'
      ]
    ]) ?>
  </div>

  <div class="vks-log col-lg-6 col-md-6">
    <?php
    foreach ($logs as $log) {

      if ($log->status == 'danger') {
        $info = 'alert-danger';
      } else {
        $info = 'alert-info';
      }
      echo '<div class="alert ' . $info . '" role="alert" style="margin-bottom: 10px">';
      echo '<div class="clock" style="font-size: 12px">';
      echo '<i class="fa fa-clock-o" aria-hidden="true"></i>';
      echo ' ';
      echo date('d.m.Y', strtotime($log->log_time)) . ' ' . date('H:i.s', strtotime($log->log_time));
      echo '</div>';
      echo " Пользователь: " . '<strong>' . $log->userName . '</strong>';
      echo '<br>';
      echo $log->log_text;
      echo '</div>';
    }
    ?>
  </div>

</div>
<br>

<script>
  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>


