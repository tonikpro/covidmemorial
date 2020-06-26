<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Country model
 *
 * @property integer $country_id
 * @property string $title_ru
 */
class Country extends ActiveRecord {
    /**
     * @return string название таблицы, сопоставленной с этим ActiveRecord-классом.
     */
    public static function tableName()
    {
        return '_countries';
    }
}

?>