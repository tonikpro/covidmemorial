<?php

use yii\db\Migration;

/**
 * Class m200523_145013_t_people
 */
class m200523_145013_t_people extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_people', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'firstname' => $this->string()->notNull(),
            'lastname' => $this->string()->notNull(),
            'middlename' => $this->string(),
            'date_of_birth' => $this->datetime()->notNull(),
            'date_of_death' => $this->datetime()->notNull(),
            'age' => $this->integer()->notNull(),
            'city_id' => $this->integer()->notNull(),
            'description' => $this->text(),
            'created_at' => $this->datetime()->notNull()->defaultValue(new \yii\db\Expression("NOW()") ),
        ]);

        $this->createIndex(
            'idx-t_people-city',
            't_people',
            'city'
        );

        $this->createIndex(
            'idx-t_people-firstname',
            't_people',
            'firstname'
        );

        $this->createIndex(
            'idx-t_people-lastname',
            't_people',
            'lastname'
        );

        $this->createIndex(
            'idx-t_people-middlename',
            't_people',
            'middlename'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_people');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200523_145013_t_people cannot be reverted.\n";

        return false;
    }
    */
}
