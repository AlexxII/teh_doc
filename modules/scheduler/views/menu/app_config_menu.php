<div class="settings-menu">
  <a class="menu-link ex-click" href="" data-url="/scheduler/control/settings" data-back-url="/scheduler">Настройки</a>
</div>
<hr>
<div class="settings-menu">
  <a class="menu-link ex-click" href="" data-url="/scheduler/control/trash" data-back-url="/scheduler">Корзина</a>
</div>
<?php if(\Yii::$app->user->identity->isAdmin) : ?>
<hr>
<div class="settings-menu">
  <a class="menu-link ex-click" href="" data-url="/vks/admin/sessions" data-back-url="/scheduler">Корзина</a>
</div>
<?php endif; ?>
