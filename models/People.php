<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
// use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $firstname
 * @property string $lastname
 * @property string $middlename
 * @property string $date_of_birth
 * @property string $date_of_death
 * @property integer $age
 * @property integer $city_id
 * @property string $description
 * @property string $created_at
 */
class People extends ActiveRecord {
    /**
     * @return string название таблицы, сопоставленной с этим ActiveRecord-классом.
     */
    public static function tableName()
    {
        return 't_people';
    }

    public function getCity()
    {
        return $this->hasOne(City::className(), ['city_id' => 'city_id']);
    }
}

?>