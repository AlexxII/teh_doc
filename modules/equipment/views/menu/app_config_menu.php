<div class="settings-menu">
  <a class="menu-link jclick" href="" data-wsize="large" data-url="/equipment/control/category" data-title="Категории">Категории</a>
</div>
<div class="settings-menu">
  <a class="menu-link jclick" href="" data-wsize="large" data-url="/equipment/control/placement" data-title="Места размещения">Места размещения</a>
</div>
<hr>
<div class="settings-menu">
  <a class="menu-link jclick" href="" data-wsize="large" data-url="/equipment/control/interface" data-title="Интерфейс">Интерфейс</a>
</div>
<?php if(\Yii::$app->user->identity->isAdmin) : ?>
<hr>
<div class="settings-menu">
  <a class="menu-link ex-click" href="" data-url="/vks/admin/sessions" data-back-url="/scheduler">Корзина</a>
</div>
<?php endif; ?>
