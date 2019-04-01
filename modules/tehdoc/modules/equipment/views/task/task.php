<?php

use yii\helpers\Html;

$this->title = 'Задание на добавление';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = ['label' => 'Перечень оборудования', 'url' => ['/tehdoc/equipment/tools']];
$this->params['breadcrumbs'][] = $this->title;

$about = "Данный раздел позволяет планировать работу по добавлению оборудование с мобильных устройств. Оборудование 
появляется в данном перечне после соответствующих настроек в панели управления.";
$dell_hint = 'Удалить выделенные сеансы';

?>

<div class="tool-task">

  <h3><?= Html::encode($this->title) ?>
    <sup class="h-title fa fa-question-circle-o" aria-hidden="true"
         data-toggle="tooltip" data-placement="right" title="<?php echo $about ?>"></sup>
  </h3>

  <pre>
    <?=var_dump($models)?>
  </pre>

</div>

<script>
  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('#tools-category_id').on('change', function (e) {
      var text = $("#tools-category_id option:selected").text();
      $('#tools-eq_title').val(text);
    });
  });
</script>


