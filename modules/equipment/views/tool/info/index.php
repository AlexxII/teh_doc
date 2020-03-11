<?php

use yii\helpers\Html;

?>

<div class="row d-flex flex-items-center" style="position: relative">
  <div class="visible-xs visible-sm" style="padding-left: 10px">
    <button type="button" data-tree="tools-main-tree" class="btn btn-default btn-circle btn-xl tool-edit">
      <svg width="25" height="25" viewBox="0 0 25 25">
        <path fill="none" d="M0 0h24v24H0V0z"></path>
        <path d="M14.06 9.02l.92.92L5.92 19H5v-.92l9.06-9.06M17.66 3c-.25 0-.51.1-.7.29l-1.83 1.83 3.75 3.75
        1.83-1.83c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.2-.2-.45-.29-.71-.29zm-3.6 3.19L3 17.25V21h3.75L17.81 9.94l-3.75-3.75z"></path>
      </svg>
    </button>
    <button type="button" data-tree="tools-main-tree" class="btn btn-default btn-circle btn-xl tool-settings"
            title="Настройки">
      <svg width="25" height="25" viewBox="0 0 25 25" focusable="false">
        <path d="M13.85 22.25h-3.7c-.74 0-1.36-.54-1.45-1.27l-.27-1.89c-.27-.14-.53-.29-.79-.46l-1.8.72c-.7.26-1.47-.03-1.81-.65L2.2
        15.53c-.35-.66-.2-1.44.36-1.88l1.53-1.19c-.01-.15-.02-.3-.02-.46 0-.15.01-.31.02-.46l-1.52-1.19c-.59-.45-.74-1.26-.37-1.88l1.85-3.19c.34-.62
        1.11-.9 1.79-.63l1.81.73c.26-.17.52-.32.78-.46l.27-1.91c.09-.7.71-1.25 1.44-1.25h3.7c.74 0 1.36.54 1.45 1.27l.27 1.89c.27.14.53.29.79.46l1.8-.72c.71-.26
        1.48.03 1.82.65l1.84 3.18c.36.66.2 1.44-.36 1.88l-1.52 1.19c.01.15.02.3.02.46s-.01.31-.02.46l1.52 1.19c.56.45.72 1.23.37 1.86l-1.86
        3.22c-.34.62-1.11.9-1.8.63l-1.8-.72c-.26.17-.52.32-.78.46l-.27 1.91c-.1.68-.72 1.22-1.46 1.22zm-3.23-2h2.76l.37-2.55.53-.22c.44-.18.88-.44
        1.34-.78l.45-.34 2.38.96 1.38-2.4-2.03-1.58.07-.56c.03-.26.06-.51.06-.78s-.03-.53-.06-.78l-.07-.56 2.03-1.58-1.39-2.4-2.39.96-.45-.35c-.42-.32-.87-.58-1.33-.77l-.52-.22-.37-2.55h-2.76l-.37
        2.55-.53.21c-.44.19-.88.44-1.34.79l-.45.33-2.38-.95-1.39 2.39 2.03 1.58-.07.56a7 7 0 0 0-.06.79c0 .26.02.53.06.78l.07.56-2.03 1.58 1.38 2.4 2.39-.96.45.35c.43.33.86.58 1.33.77l.53.22.38 2.55z"></path>
        <circle cx="12" cy="12" r="3.5"></circle>
      </svg>
    </button>
    <button type="button" data-task="<?= $model->task ?>" data-tree="tools-main-tree"
            class="btn btn-default btn-circle btn-xl tool-task" title="Задание на обновление"
            style="background-color: <?php if ($model->task) echo '#fef7e0' ?>; fill: <?php if ($model->task) echo '#fbbc04' ?>; padding-top: 3px; padding-left: 4px">
      <svg viewBox="0 0 26 26" focusable="false" style="pointer-events: none; display: block">
        <path d="M11.9 3.75c-4.55 0-8.23 3.7-8.23 8.25H.92l3.57 3.57.04.13 3.7-3.7H5.5c0-3.54 2.87-6.42 6.42-6.42
          3.54 0 6.4 2.88 6.4 6.42s-2.86 6.42-6.4 6.42c-1.78 0-3.38-.73-4.54-1.9l-1.3 1.3c1.5 1.5 3.55 2.43 5.83 2.43
          4.58 0 8.28-3.7 8.28-8.25 0-4.56-3.7-8.25-8.26-8.25zM11 8.33v4.6l3.92 2.3.66-1.1-3.2-1.9v-3.9H11z">
        </path>
      </svg>
    </button>
  </div>
  <div class="col-lg-9">
    <h2 style="word-break: break-word">
        <span style="position: relative">
        <span id="info-tool-title"><?= Html::encode($model->name); ?></span>
        <?php if ($model->specialStatus) {
          echo '
          <i class="fa fa-shield" aria-hidden="true" style="font-size: 18px;position: absolute;top:-5px;right:-10px"
             data-toggle="tooltip" data-placement="right" title="Проведены Специальные работы"></i>';
        } else if ($model->specialChildrenStatus) {
          echo '
          <i class="fa fa-shield" aria-hidden="true" style="font-size: 12px;position: absolute;top:-5px;right:-10px"
             data-toggle="tooltip" data-placement="right" title="Проведены Специальные работы над дочерним оборудованием"></i>';
        }
        ?>
        </span>
    </h2>
  </div>
  <div class="text-right control hidden-xs hidden-sm" style="position: absolute;top: 15px; right: 15px;">
    <button id="tool-edit" type="button" data-tree="tools-main-tree" class="btn btn-default btn-circle btn-xl">
      <svg width="25" height="25" viewBox="0 0 25 25">
        <path fill="none" d="M0 0h24v24H0V0z"></path>
        <path d="M14.06 9.02l.92.92L5.92 19H5v-.92l9.06-9.06M17.66 3c-.25 0-.51.1-.7.29l-1.83 1.83 3.75 3.75
        1.83-1.83c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.2-.2-.45-.29-.71-.29zm-3.6 3.19L3 17.25V21h3.75L17.81 9.94l-3.75-3.75z"></path>
      </svg>
    </button>
    <button id="tool-settings" type="button" data-tree="tools-main-tree" class="btn btn-default btn-circle btn-xl"
            title="Настройки">
      <svg width="25" height="25" viewBox="0 0 25 25" focusable="false">
        <path d="M13.85 22.25h-3.7c-.74 0-1.36-.54-1.45-1.27l-.27-1.89c-.27-.14-.53-.29-.79-.46l-1.8.72c-.7.26-1.47-.03-1.81-.65L2.2
        15.53c-.35-.66-.2-1.44.36-1.88l1.53-1.19c-.01-.15-.02-.3-.02-.46 0-.15.01-.31.02-.46l-1.52-1.19c-.59-.45-.74-1.26-.37-1.88l1.85-3.19c.34-.62
        1.11-.9 1.79-.63l1.81.73c.26-.17.52-.32.78-.46l.27-1.91c.09-.7.71-1.25 1.44-1.25h3.7c.74 0 1.36.54 1.45 1.27l.27 1.89c.27.14.53.29.79.46l1.8-.72c.71-.26
        1.48.03 1.82.65l1.84 3.18c.36.66.2 1.44-.36 1.88l-1.52 1.19c.01.15.02.3.02.46s-.01.31-.02.46l1.52 1.19c.56.45.72 1.23.37 1.86l-1.86
        3.22c-.34.62-1.11.9-1.8.63l-1.8-.72c-.26.17-.52.32-.78.46l-.27 1.91c-.1.68-.72 1.22-1.46 1.22zm-3.23-2h2.76l.37-2.55.53-.22c.44-.18.88-.44
        1.34-.78l.45-.34 2.38.96 1.38-2.4-2.03-1.58.07-.56c.03-.26.06-.51.06-.78s-.03-.53-.06-.78l-.07-.56 2.03-1.58-1.39-2.4-2.39.96-.45-.35c-.42-.32-.87-.58-1.33-.77l-.52-.22-.37-2.55h-2.76l-.37
        2.55-.53.21c-.44.19-.88.44-1.34.79l-.45.33-2.38-.95-1.39 2.39 2.03 1.58-.07.56a7 7 0 0 0-.06.79c0 .26.02.53.06.78l.07.56-2.03 1.58 1.38 2.4 2.39-.96.45.35c.43.33.86.58 1.33.77l.53.22.38 2.55z"></path>
        <circle cx="12" cy="12" r="3.5"></circle>
      </svg>
    </button>
    <button id="tool-task" type="button" data-task="<?= $model->task ?>" data-tree="tools-main-tree"
            class="btn btn-default btn-circle btn-xl" title="Задание на обновление"
            style="background-color: <?php if ($model->task) echo '#fef7e0' ?>; fill: <?php if ($model->task) echo '#fbbc04' ?>; padding-top: 3px; padding-left: 4px">
      <svg viewBox="0 0 26 26" focusable="false" style="pointer-events: none; display: block">
          <path d="M11.9 3.75c-4.55 0-8.23 3.7-8.23 8.25H.92l3.57 3.57.04.13 3.7-3.7H5.5c0-3.54 2.87-6.42 6.42-6.42
          3.54 0 6.4 2.88 6.4 6.42s-2.86 6.42-6.4 6.42c-1.78 0-3.38-.73-4.54-1.9l-1.3 1.3c1.5 1.5 3.55 2.43 5.83 2.43
          4.58 0 8.28-3.7 8.28-8.25 0-4.56-3.7-8.25-8.26-8.25zM11 8.33v4.6l3.92 2.3.66-1.1-3.2-1.9v-3.9H11z">
          </path>
      </svg>
    </button>
  </div>
</div>

<?php
echo $this->renderAjax($view, [
  'model' => $model,
  'children' => $children
]) ?>

</div>

<script>
  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

</script>
