<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="clsf-extender">
  <form action="tested" method="post" class="input-add">
    <label style="font-size: 14px">Название классификатора:</label>
    <input class="form-control node-id" name="node-id" readonly style="display: none">
    <input class="form-control clsf-name" name="clsf-name" disabled
           title="Вводите имя в колонке дерева классификаторов. Клавиша F2 или двойной щелчок мыши"
           data-toggle="tooltip" data-placement="top">
    <label style="font-size: 14px">Добавить:</label>
    <p><select class="form-control" id="clsf-form-add" name="clsf-add">
        <option selected disabled value="0">Выберите</option>
        <option value="1">input</option>
        <option value="2">dateInput</option>
        <option value="3">select</option>
        <option value="4">textarea</option>
        <option value="5">radioButton</option>
        <option value="6">checkBox</option>
        <option value="7">fileInput</option>
      </select></p>
    <button type="submit" class="btn btn-primary update-btn insert-btn-cls" onclick="saveClick(event)">Обновить</button>
  </form>
</div>
