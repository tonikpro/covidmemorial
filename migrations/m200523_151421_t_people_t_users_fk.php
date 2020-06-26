<?php

use yii\db\Migration;

/**
 * Class m200523_151421_t_people_t_users_fk
 */
class m200523_151421_t_people_t_users_fk extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-t_people-user_id',
            't_people',
            'user_id',
            't_users',
            'user_id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-t_people-user_id',
            't_people'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200523_151421_t_people_t_users_fk cannot be reverted.\n";

        return false;
    }
    */
}
