<?php

foreach ($models as $key => $model) {
  if (!empty($to->toEq->groupName)) {
    echo $to->toEq->groupName->name . '<br>';
  } else {
    echo $model->toEq->name . "<br>";
  }

}

