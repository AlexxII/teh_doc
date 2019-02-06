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
    <p>
      Панель для управления свойствами данного оборудования!
    </p>
  </div>

</div>