<?php

use yii\helpers\Html;
use app\modules\equipment\asset\MasonryAsset;

MasonryAsset::register($this);

?>

<div class="complex-fotos">

  <div class="row">
    <div class="col-lg-9 col-md-6">
      <h3>
        <?= Html::encode('Изображения') ?>
      </h3>
    </div>
    <div class="col-lg-3 col-md-6 text-right">
      <p>
        <a href="" class="btn btn-sm btn-danger" data-tree="tools-main-tree" id="delete-image" disabled="true">Удалить</a>
        <a href="" class="btn btn-sm btn-success" data-tree="tools-main-tree" id="add-image">Добавить</a>
      </p>
    </div>
  </div>

  <div>
    <div class="dw foto-panel">
      <?php foreach ($photoModels as $photoModel): ?>
        <div class="dw-panel">
          <div class="thumbnail dw-panel__content">
            <div>
              <input class="foto-select" type="checkbox" data-docid="<?= $photoModel->id ?>">
            </div>
            <a href="<?= $photoModel->imageUrl ?>" target="_blank">
              <img src="<?= $photoModel->imageUrl ?>">
            </a>
            <span class="date">Добавлено: <?= $photoModel->uploadDate ?> </span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>


<script>
  $(document).ready(function () {
    $('.foto-select').change(function (e) {
      if ($('input:checkbox:checked').length) {
        $('#delete-image').attr('disabled', false);
      } else {
        $('#delete-image').attr('disabled', true);
      }
    });

    var jc;

    $('.fact-date').datepicker({
      format: 'dd MM yyyy г.',
      autoclose: true,
      language: "ru",
      clearBtn: true
    });

    if ($('.fact-date').val()) {
      var date = new Date($('.fact-date').val());
      moment.locale('ru');
      $('.fact-date').datepicker('update', moment(date).format('MMMM YYYY'))
    }

    $('.foto-select').change(function (e) {
      if ($('input:checkbox:checked').length) {
        $('#delete-image').attr('disabled', false);
      } else {
        $('#delete-image').attr('disabled', true);
      }
    });
  });


</script>
