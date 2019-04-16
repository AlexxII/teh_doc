<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;

use app\modules\admin\models\User;

/**
 * Password reset form
 */
class PasswordChangeForm extends Model
{
  public $currentPassword;
  public $newPassword;
  public $newPasswordRepeat;
  public $userName;


  /**
   * @var User
   */
  private $_user;

  /**
   * @param User $user
   * @param array $config
   */
  public function __construct(User $user, $config = [])
  {
    $this->_user = $user;
    $this->userName = $user->username;
    parent::__construct($config);
  }

  public function rules()
  {
    return [
        [['newPassword', 'newPasswordRepeat'], 'required'],
        ['newPassword', 'string', 'min' => 6],
        ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword', 'message' => 'Пароли должны совпадать'],
    ];
  }

  public function attributeLabels()
  {
    return [
        'newPassword' => 'Новый пароль',
        'newPasswordRepeat' => 'Повторить пароль  ',
    ];
  }

  /**
   * @param string $attribute
   * @param array $params
   */
  public function currentPassword($attribute, $params)
  {
    if (!$this->hasErrors()) {
      if (!$this->_user->validatePassword($this->$attribute)) {
        $this->addError($attribute, Yii::t('app', 'ERROR_WRONG_CURRENT_PASSWORD'));
      }
    }
  }

  /**
   * @return boolean
   */
  public function changePassword()
  {
    if ($this->validate()) {
      $user = $this->_user;
      $user->setPassword($this->newPassword);
      return $user->save();
    } else {
      return false;
    }
  }
}