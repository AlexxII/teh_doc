<?php

use yii\db\Migration;

class m181130_082239_vks_sessions_tbl extends Migration
{
    const TABLE_NAME = '{{%vks_sessions_tbl}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'vks_date' => $this->date()->notNull(),

            'vks_teh_time_start' => $this->string(25),
            'vks_teh_time_end' => $this->string(25),
            'vks_duration_teh' => $this->integer(),
            'vks_work_time_start' => $this->string(25),
            'vks_work_time_end' => $this->string(25),
            'vks_duration_work' => $this->integer(),

            'vks_type' => $this->bigInteger()->notNull(),
            'vks_type_text' => $this->string(255),
            'vks_place' => $this->bigInteger(),
            'vks_place_text' => $this->string(255),

            'vks_subscriber_office' => $this->bigInteger(),
            'vks_subscriber_office_text' => $this->string(255),
            'vks_subscriber_name' => $this->string(255),

            'vks_order' => $this->bigInteger(),
            'vks_order_text' => $this->string(255),                               // колонка с текстовым содержанием

            'vks_order_number' => $this->string(255),
            'vks_order_date' => $this->date(),

            'vks_equipment' => $this->bigInteger(),
            'vks_remarks' => $this->boolean()->defaultValue(0),                   // замечания

            'vks_employee' => $this->bigInteger(),                                         // сотрудник спецсвязи, участв. в приеме
            'vks_employee_text' => $this->string(255),

            'vks_subscriber_reg_office' => $this->bigInteger(),                            // региональный абонент
            'vks_subscriber_reg_office_text' => $this->string(),
            'vks_subscriber_reg_name' => $this->string(255),

            'vks_employee_receive_msg' => $this->string(255),
            'vks_receive_msg_datetime' => $this->dateTime(),
            'vks_employee_send_msg' => $this->bigInteger(),
            'vks_employee_send_msg_text' => $this->string(255),

            'vks_comments' => $this->text(),

            'vks_record_create' => $this->dateTime(),
            'vks_record_update' => $this->dateTime(),

            'vks_upcoming_session' => $this->boolean(),                                  // предстоящий сеанс ВКС

            'important' => $this->boolean()->defaultValue(0),                                            // важный сеанс
            'vks_cancel' => $this->boolean()->defaultValue(0),                                            // отмена сеанса
            'vks_cancel_reason' => $this->string(255)
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME );
        return false;
    }
}
