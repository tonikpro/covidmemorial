<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * City model
 *
 * @property integer $city_id
 * @property integer $country_id
 * @property integer $region_id
 * @property string $title_ru
 * @property string $region_ru
 */
class City extends ActiveRecord {
    /**
     * @return string название таблицы, сопоставленной с этим ActiveRecord-классом.
     */
    public static function tableName()
    {
        return '_cities';
    }

    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['country_id' => 'country_id']);
    }
}

?>