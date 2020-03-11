<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\tehdoc\asset;
use yii\widgets\DetailView;

?>

<style>
  #w0 td {
    text-align: center;
  }
  .h-title {
    font-size: 18px;
    color: #1e6887;
  }
  table.detail-view th {
    width: 40%;
  }

</style>

<div class="">
  <div class="container-fluid col-lg-7 col-md-7">
    <?= DetailView::widget([
      "model" => $model,
      "attributes" => [
        "title",
        "code",
        [
          "label" => "Дата начала:",
          "value" => date("d.m.Y", strtotime($model->start_date)) . " г."
        ],
        [
          "label" => "Дата окончания:",
          "value" => date("d.m.Y", strtotime($model->end_date)) . " г."
        ],
        [
          "label" => "Выборка:",
          "value" => $model->sample . " человек "
        ],
        "poll_comments",
        "poll_record_create",
        "poll_record_update"
      ]
    ]) ?>
  </div>
</div>
<br>

<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>


