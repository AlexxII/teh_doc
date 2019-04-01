<?php

echo 'Профиль пользователя - ' . Yii::$app->user->identity->username;
echo '<p>Раздел находится в разработке</p>';
Yii::$app->cache->flush();
?>

