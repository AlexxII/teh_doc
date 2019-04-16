<?php

namespace app\modules\tehdoc\models;

use Yii;

/**
 * This is the model class for table "teh_interface_tbl".
 *
 * @property string $id
 * @property string $name
 * @property string $text
 */
class TehInterface extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
    return 'teh_interface_tbl';
  }

  public function rules()
  {
    return [
      [['name'], 'required'],
      [['text'], 'string'],
      [['name'], 'string', 'max' => 120],
    ];
  }

  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'name' => 'Наименования',
      'text' => 'Перечисленияыы',
    ];
  }

  public static function findModel($id)
  {
    if (($model = TehInterface::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException('Запрошенная страница не существует.');
  }



}
