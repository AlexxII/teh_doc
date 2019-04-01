<?php

use yii\widgets\ActiveForm;
use kartik\tree\TreeViewInput;
use app\modules\admin\models\ClassifierTbl;

echo TreeViewInput::widget([
    'query' => ClassifierTbl::find()->addOrderBy('root, lft'),
    'id' => 'classifier',
    'name' => 'id',                  // input name
    'asDropdown' => true,            // will render the tree input widget as a dropdown.
    'headingOptions' => ['label' => 'Классификаторы'],
    'value' => true,
    'multiple' => true,              // set to false if you do not need multiple selection
    'fontAwesome' => true,           // render font awesome icons
    'rootOptions' => [
        'label' => '<i class="fa fa-tree"></i>',
    ],
    'dropdownConfig' => [
        'input' => [
            'placeholder' => 'Выберите классификатор...'
        ]
    ],
    'options' => [
        'class' => 'classifier-cl'
    ]
]);




