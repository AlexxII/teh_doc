<?php

namespace app\modules\vks\models;

use Yii;

use app\modules\admin\models\User;
use app\base\MHelper;

/**
 * This is the model class for table "vks_log_tbl".
 *
 * @property string $id
 * @property int $session_id
 * @property string $log_text
 * @property string $log_time
 * @property int $valid
 */
class VksLog extends \yii\db\ActiveRecord
{
  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'vks_log_tbl';
  }

  public function __construct()
  {
    $this->id = MHelper::generateId();
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['session_id', 'valid'], 'integer'],
      [['log_time'], 'safe'],
      [['log_text'], 'string', 'max' => 255],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'session_id' => 'Сеанс',
      'log_text' => 'Текст',
      'log_time' => 'Время',
      'valid' => 'Valid',
    ];
  }

  public function getUserName()
  {
    if ($this->user) {
      return $this->user->username;
    }
    return '-';
  }

  public function getUser()
  {
    return $this->hasOne(User::class, ['id' => 'user_id']);
  }
}
