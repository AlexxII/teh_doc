<?php

$this->title = 'Панель управления';
$this->params['breadcrumbs'][] = ['label' => 'Тех.документация', 'url' => ['/tehdoc']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="alert alert-warning alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <strong>Внимание!</strong><br>
  В данном разделе отображается оборудование, которое <strong> ждет обработки</strong>.
  Закрепите необработанное оборудование в дереве там, где вам будет удобно им манипулировать в дальнейшем.
</div>
