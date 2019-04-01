<?php

namespace app\modules\vks\models;

use app\modules\admin\models\User;
use Faker\Provider\DateTime;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vks_sessions_tbl".
 *
 * @property int $id
 * @property string $vks_date
 * @property string $vks_teh_time_start
 * @property string $vks_teh_time_end
 * @property int $vks_duration_teh
 * @property string $vks_work_time_start
 * @property string $vks_work_time_end
 * @property int $vks_duration_work
 * @property int $vks_type
 * @property int $vks_place
 * @property string $vks_subscriber_office
 * @property string $vks_subscriber_name
 * @property int $vks_order
 * @property string $vks_order_number
 * @property string $vks_order_date
 * @property int $vks_equipment
 * @property int $vks_remarks
 * @property int $vks_employee
 * @property int $vks_employee_receive_msg
 * @property string $vks_receive_msg_datetime
 * @property int $vks_employee_send_msg
 * @property string $vks_comments
 * @property string $vks_record_create
 * @property string $vks_record_update
 */
class VksSessions extends \yii\db\ActiveRecord
{

  const SCENARIO_CREATE = 'create';
  const SCENARIO_CONFIRM = 'confirm';

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'vks_sessions_tbl';
  }

  public function scenarios()
  {
    $scenarios = parent::scenarios();
    $scenarios[self::SCENARIO_CREATE] = [
      'vks_date',
      'vks_type', 'vks_type_text',
      'vks_place', 'vks_place_text',
      'vks_teh_time_start', 'vks_work_time_start',
      'vks_subscriber_office', 'vks_subscriber_name', 'vks_subscriber_office_text',
      'vks_duration_teh', 'vks_duration_work',
      'vks_order', 'vks_order_text',
      'vks_employee_receive_msg', 'vks_receive_msg_datetime', 'vks_employee_send_msg',
      'vks_comments', 'vks_record_create', 'vks_record_update', 'combined'
    ];
    $scenarios[self::SCENARIO_CONFIRM] = [
      'vks_date',
      'vks_type', 'vks_type_text',
      'vks_subscriber_office', 'vks_subscriber_office_text', 'vks_subscriber_name', 'vks_subscriber_reg_name',
      'vks_teh_time_start', 'vks_work_time_start',
      'vks_teh_time_end', 'vks_work_time_end',
      'vks_order', 'vks_order_text',
      'vks_place', 'vks_place_text',
      'vks_equipment', 'vks_comments',
      'vks_duration_teh', 'vks_duration_work',
      'vks_employee',
      'vks_employee_receive_msg', 'vks_receive_msg_datetime', 'vks_employee_send_msg_text',
      'vks_subscriber_reg_office', 'vks_subscriber_reg_office_text',
      'vks_record_create', 'vks_record_update', 'combined'
    ];
    return $scenarios;
  }


  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['vks_date', 'vks_type', 'vks_employee_receive_msg', 'vks_receive_msg_datetime', 'vks_employee_send_msg', 'each'], 'required', 'on' => self::SCENARIO_CREATE],
      [[
        'vks_date', 'vks_type',
        'vks_subscriber_office',
        'vks_order',
        'vks_place',
        'vks_equipment',
        'vks_employee',
        'vks_subscriber_reg_office', 'combined'
      ], 'required', 'on' => self::SCENARIO_CONFIRM],
      ['vks_remarks', 'trim'],
      [['vks_date', 'vks_place', 'vks_teh_time_start', 'vks_teh_time_end',
        'vks_work_time_start', 'vks_work_time_end', 'vks_order_date',
        'vks_record_create', 'vks_record_update', 'vks_subscriber_office',
        'vks_subscriber_name', 'vks_subscriber_reg_name',
        'vks_duration_teh', 'vks_duration_work',
        'vks_order', 'vks_order_number', 'vks_order_date', 'vks_equipment',
        'vks_type_text', 'vks_place_text', 'vks_order_text', 'vks_subscriber_office_text',
        'vks_subscriber_reg_office_text', 'vks_employee_send_msg_text', 'combined'], 'safe'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'vks_date' => 'Дата проведения ВКС:',
      'vks_teh_time_start' => 'Время начала техн.:',
      'vks_teh_time_end' => 'Время окончания техн:',
      'vks_duration_teh' => 'Длительность техн.',
      'vks_work_time_start' => 'Время начала рабоч.:',
      'vks_work_time_end' => 'Время окончания рабоч:',
      'vks_duration_work' => 'Длительность рабоч.',
      'vks_type' => 'Тип ВКС:',
      'vks_place' => 'Место проведения ВКС:',
      'vks_subscriber_office' => 'Должность:',
      'vks_subscriber_name' => 'Фамилия:',
      'vks_order' => 'Распоряжение:',
      'vks_order_number' => '№ док-та:',
      'vks_order_date' => 'Дата док-та:',
      'vks_equipment' => 'Оборудование ВКС:',
      'vks_remarks' => 'Замечания',
      'vks_employee' => 'Сотрудник СпецСвязи:',
      'vks_subscriber_reg_office' => 'Должность:',
      'vks_subscriber_reg_name' => 'Фамилия:',
      'vks_employee_receive_msg' => 'Принявший сообщение:',
      'vks_receive_msg_datetime' => 'Дата сообщения:',
      'vks_employee_send_msg' => 'Передавший сообщение:',
      'vks_comments' => 'Примечание:',
      'vks_record_create' => 'Запись создана:',
      'vks_record_update' => 'Запись обновлена:',
      'combined' => 'Важность'
    ];
  }

  public function getVksTypesList()
  {
    $sql = "SELECT C1.ref, C1.name, C2.name as gr from vks_types_tbl C1 LEFT JOIN 
        vks_types_tbl C2 on C1.parent_id = C2.ref WHERE C1.lvl > 1 ORDER BY C1.lft";
    return ArrayHelper::map($this->findBySql($sql)->asArray()->all(), 'ref', 'name', 'gr');
  }

  public function getVksPlacesList()
  {
    $sql = "SELECT C1.ref, C1.name, C2.name as gr from vks_places_tbl C1 LEFT JOIN 
        vks_places_tbl C2 on C1.parent_id = C2.ref WHERE C1.lvl > 1 ORDER BY C1.lft";
    return ArrayHelper::map($this->findBySql($sql)->asArray()->all(), 'ref', 'name', 'gr');
  }

  public function getVksMskSubscribesList()
  {
    $sql = "SELECT C1.ref, C1.name, C2.name as gr from vks_subscribes_tbl C1 LEFT JOIN 
        vks_subscribes_tbl C2 on C1.parent_id = C2.ref WHERE C1.lvl > 1 AND C1.root = 1 ORDER BY C1.lft";
    return ArrayHelper::map($this->findBySql($sql)->asArray()->all(), 'ref', 'name', 'gr');
  }

  public function getVksRegionSubscribesList()
  {
    $sql = "SELECT C1.ref, C1.name, C2.name as gr from vks_subscribes_tbl C1 LEFT JOIN 
        vks_subscribes_tbl C2 on C1.parent_id = C2.ref WHERE C1.lvl > 1 AND C1.root = 2 ORDER BY C1.lft";
    return ArrayHelper::map($this->findBySql($sql)->asArray()->all(), 'ref', 'name', 'gr');
  }

  public function getVksOrdersList()
  {
    $sql = "SELECT C1.ref, C1.name, C2.name as gr from vks_orders_tbl C1 LEFT JOIN 
        vks_orders_tbl C2 on C1.parent_id = C2.ref WHERE C1.lvl > 1 ORDER BY C1.lft";
    return ArrayHelper::map($this->findBySql($sql)->asArray()->all(), 'ref', 'name', 'gr');
  }

  public function getVksEmployeesList()
  {
    $sql = "SELECT C1.ref, C1.name, C2.name as gr from vks_employees_tbl C1 LEFT JOIN 
        vks_employees_tbl C2 on C1.parent_id = C2.ref WHERE C1.lvl > 1 AND C1.root = 2 ORDER BY C1.lft";
    return ArrayHelper::map($this->findBySql($sql)->asArray()->all(), 'ref', 'name', 'gr');
  }

  public function getVksEmployees4List()
  {
    $sql = "SELECT C1.ref, C1.name, C2.name as gr from vks_employees_tbl C1 LEFT JOIN 
        vks_employees_tbl C2 on C1.parent_id = C2.ref WHERE C1.lvl > 1 AND C1.root = 1 ORDER BY C1.lft";
    return ArrayHelper::map($this->findBySql($sql)->asArray()->all(), 'ref', 'name', 'gr');
  }

  public function getVksToolsList()
  {
    $sql = "SELECT C1.ref, C1.name, C2.name as gr from vks_tools_tbl C1 LEFT JOIN 
        vks_tools_tbl C2 on C1.parent_id = C2.ref WHERE C1.lvl > 1 ORDER BY C1.lft";
    return ArrayHelper::map($this->findBySql($sql)->asArray()->all(), 'ref', 'name', 'gr');
  }

  public function getVksType()
  {
      return $this->hasOne(VksTypes::class, ['ref' => 'vks_type']);
  }

  public function getVksPlace()
  {
    return $this->hasOne(VksPlaces::class, ['ref' => 'vks_place']);
  }

  public function getVksSubscriber()
  {
    return $this->hasOne(VksSubscribes::class, ['ref' => 'vks_subscriber_office']);
  }

  public function getVksSubscriberReg()
  {
    return $this->hasOne(VksSubscribes::class, ['ref' => 'vks_subscriber_reg_office']);
  }

  public function getVksOrder()
  {
    return $this->hasOne(VksOrders::class, ['ref' => 'vks_order']);
  }

  public function getVksSendMsg()
  {
    return $this->hasOne(VksEmployees::class, ['ref' => 'vks_employee_send_msg']);
  }

  public function getVksEmployee()
  {
    return $this->hasOne(VksEmployees::class, ['ref' => 'vks_employee']);
  }

  public function getType()
  {
    $depth = 2; // сколько уровней
    if ($this->vksType) {
      $full = $this->vksType;
      $parentCount = $full->parents()->count();
      $parent = $full->parents($parentCount - $depth)->all();
      $fullname = '';
      foreach ($parent as $p) {
        $fullname .= $p->name . ' ->';
      }
      return $fullname . ' ' . $this->vksType->name;
    } else {
      return '-';
    }
  }

  public function getPlace()
  {
    $depth = 1; // сколько уровней
    if ($this->vksPlace) {
      $full = $this->vksPlace;
      $parentCount = $full->parents()->count();
      $parent = $full->parents($parentCount - $depth)->all();
      $fullname = '';
      foreach ($parent as $p) {
        $fullname .= $p->name . ' ->';
      }
      return $fullname . ' ' . $this->vksPlace->name;
    } else {
      return '-';
    }
  }

  public function getSubscriber()
  {
    $depth = 1; // сколько уровней
    if ($this->vksSubscriber) {
      $full = $this->vksSubscriber;
      $parentCount = $full->parents()->count();
      $parent = $full->parents($parentCount - $depth)->all();
      $fullname = '';
      foreach ($parent as $p) {
        $fullname .= $p->name . ' ->';
      }
      return $fullname . ' ' . $this->vksSubscriber->name . " -> " . $this->vks_subscriber_name;
    } else {
      return '-';
    }
  }

  public function getSubscriberReg()
  {
    $depth = 1; // сколько уровней
    if ($this->vksSubscriberReg) {
      $full = $this->vksSubscriberReg;
      $parentCount = $full->parents()->count();
      $parent = $full->parents($parentCount - $depth)->all();
      $fullname = '';
      foreach ($parent as $p) {
        $fullname .= $p->name . ' ->';
      }
      return $fullname . ' ' . $this->vksSubscriberReg->name . " -> " . $this->vks_subscriber_reg_name;
    } else {
      return '-';
    }
  }

  public function getOrder()
  {
    $depth = 1; // сколько уровней
    if ($this->vksOrder) {
      $full = $this->vksOrder;
      $parentCount = $full->parents()->count();
      $parent = $full->parents($parentCount - $depth)->all();
      $fullname = '';
      foreach ($parent as $p) {
        $fullname .= $p->name . ' ->';
      }
      return $fullname . ' ' . $this->vksOrder->name;
    } else {
      return '-';
    }
  }

  public function getSendMsg()
  {
    $depth = 1; // сколько уровней
    if ($this->vksSendMsg) {
      $full = $this->vksSendMsg;
      $parentCount = $full->parents()->count();
      $parent = $full->parents($parentCount - $depth)->all();
      $fullname = '';
      foreach ($parent as $p) {
        $fullname .= $p->name . ' ->';
      }
      return $fullname . ' ' . $this->vksSendMsg->name;
    } else {
      return '-';
    }
  }

  public function getEmployee()
  {
    $depth = 1; // сколько уровней
    if ($this->vksEmployee) {
      $full = $this->vksEmployee;
      $parentCount = $full->parents()->count();
      $parent = $full->parents($parentCount - $depth)->all();
      $fullname = '';
      foreach ($parent as $p) {
        $fullname .= $p->name . ' ->';
      }
      return $fullname . ' ' . $this->vksEmployee->name;
    } else {
      return '-';
    }
  }

}