<div class="settings-menu">
  <a class="menu-link jclick" href="" data-wsize="large" data-url="/vks/control/vks-type" data-title="Тип ВКС">Тип
    ВКС</a>
</div>
<div class="settings-menu">
  <a class="menu-link jclick" href="" data-wsize="large" data-url="/vks/control/vks-place"
     data-title="Студии проведения ВКС">Студии проведения ВКС</a>
</div>
<div class="settings-menu">
  <a class="menu-link jclick" href="" data-wsize="xlarge" data-url="/vks/control/vks-subscribes" data-title="Абоненты">Абоненты</a>
</div>
<div class="settings-menu">
  <a class="menu-link jclick" href="" data-wsize="large" data-url="/vks/control/vks-order" data-title="Распоряжения">Распоряжения</a>
</div>
<div class="settings-menu">
  <a class="menu-link jclick" href="" data-wsize="large" data-url="/vks/control/vks-employee" data-title="Сотрудники">Сотрудники</a>
</div>
<div class="settings-menu">
  <a class="menu-link jclick" href="" data-wsize="large" data-url="/vks/control/vks-tools" data-title="Оборудование">Оборудование</a>
</div>
<?php if(\Yii::$app->user->identity->isAdmin) : ?>
<hr>
<div class="settings-menu">
  <a class="menu-link ex-click" href="" data-url="/vks/admin/sessions" data-back-url="/vks">Корзина</a>
</div>
<?php endif; ?>
