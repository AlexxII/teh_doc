<div class="settings-menu">
  <a class="menu-link ex-click" href="" data-url="/to/control/settings" data-back-url="/to">Настройки</a>
</div>
<?php if (\Yii::$app->user->identity->isAdmin) : ?>
  <hr>
  <div class="settings-menu">
    <a class="menu-link ex-click" href="" data-url="/to/trash" data-back-url="/to">Корзина</a>
  </div>
<?php endif; ?>
