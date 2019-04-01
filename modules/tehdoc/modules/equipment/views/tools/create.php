<?php

use yii\helpers\Html;

$this->title = 'Добавить оборудование';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = ['label' => 'Перечень оборудования', 'url' => ['/tehdoc/equipment/tools']];
$this->params['breadcrumbs'][] = $this->title;

$about_hint = 'Внимание! Использовать данную форму желательно только в мобильных устройствах. Для добавления оборудования
с помощью стационарных устройств, используйте Панель управления.';

?>

<div class="tool-create">

  <h3><?= Html::encode($this->title) ?>
    <sup class="h-title fa fa-question-circle-o" aria-hidden="true"
         data-toggle="tooltip" data-placement="right" title="<?php echo $about_hint ?>"></sup>
  </h3>
  <?= $this->render('_form', [
    'model' => $model,
    'fUpload' => $fupload
  ]) ?>

</div>
