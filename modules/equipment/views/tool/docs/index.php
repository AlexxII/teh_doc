<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="complex-doc" style="margin-top: 15px">
  <div class="row">
    <div class="col-lg-9 col-md-6">
      <h3 style="margin-top: 0px">
        <?= Html::encode('Документы') ?>
      </h3>
    </div>
    <div class="col-lg-3 col-md-6 text-right">
      <p>
        <a href="" class="btn btn-sm btn-danger" data-tree="tools-main-tree" id="delete-doc" disabled="true">Удалить</a>
        <a href="" class="btn btn-sm btn-info" data-tree="tools-main-tree" id="add-doc">Добавить</a>
      </p>
    </div>
  </div>


  <div id="complex-docs">
    <?php if ($years): ?>
      <div class="calendar">
        <ul class="list-inline" style="border-top: 1px solid #cbcbcb;">
          <?php foreach ($years as $year): ?>
            <li class="year-select">
              <span><?= $year ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php foreach ($docModels as $docModel): ?>
      <div class="doc-wrap" data-doc-year="<?= $docModel->year ?>">
        <div>
          <input class="doc-select" type="checkbox" data-docid="<?= $docModel->id ?>">
        </div>
        <div class="news-item">
          <a href="<?= $docModel->docUrl ?>" target="_blank">
            <div>
              <div class="news-info__name">
                <?= $docModel->doc_title ?>
              </div>
              <span class="info-file">
          <span class="format-file doc-format">
            <?= $docModel->doc_extention ?>
            <i class="fa fa-download down" aria-hidden="true"></i>
          </span>
          <span class="weight-file"></span>
        </span>
            </div>
          </a>
          <div>
            <span class="date"><?= $docModel->uploadDate . ' (' . $docModel->docDate . ')' ?> </span>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>


<script>

  var jc;

  $(document).ready(function () {

    $('.year-select').on('click', function (e) {
      e.preventDefault();
      if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        $('.doc-wrap[data-doc-year]').each(function () {
          $(this).show();
        });
        return;
      }
      $('.year-select').not(this).removeClass('active');
      $(this).addClass('active');
      var year = $(this).text();
      $('.doc-wrap[data-doc-year]').each(function () {
        if ($(this).data('docYear') != year) {
          $(this).hide();
        } else {
          $(this).show();
        }
      });

    });

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

    $('.doc-select').change(function (e) {
      if ($('input:checkbox:checked').length) {
        $('#delete-doc').attr('disabled', false);
      } else {
        $('#delete-doc').attr('disabled', true);
      }
    });
  });


</script>