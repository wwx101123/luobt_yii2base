<?php

use yii\db\Migration;
/**
 * Handles the creation of table `income`.
 */
class m171130_060448_create_income_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%income_record}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->comment('会员ID'),
            'money' => $this->decimal(12,2)->notNull()->defaultValue(0.00)->comment('金额'),
            'create_time' => $this->integer()->notNull()->comment('时间'),
            'bz' =>  $this->text()->comment('备注'),
        ],'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%income_record}}');
    }
}
