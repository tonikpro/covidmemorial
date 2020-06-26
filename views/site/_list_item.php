<?php
// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class='people-item'> 
    <span><?= Html::a(Html::encode($model->firstname), Url::toRoute(['post/show', 'id' => $model->id]), ['firstname' => $model->firstname]) ?></span>
    <span><?= Html::a(Html::encode($model->lastname), Url::toRoute(['post/show', 'id' => $model->id]), ['lastname' => $model->lastname]) ?></span>
    <span><?= Html::a(Html::encode($model->middlename), Url::toRoute(['post/show', 'id' => $model->id]), ['middlename' => $model->middlename]) ?></span>
    <span><?= \Yii::$app->formatter->asDate($model->date_of_birth, 'php:d.m.Y') ?></span>
    <span><?= \Yii::$app->formatter->asDate($model->date_of_death, 'php:d.m.Y') ?></span>
    
    <?php  
        $city = $model->city;
        //var_dump($city);die();
        $country = $city->country;
        $p_city = $country->title_ru . ', ' . $city->title_ru;
        if(!empty($city->area_ru)) {
            $p_city .= ', ' . $city->area_ru;
        }
    ?>
    <span><?= $p_city ?></span>
</div>



    
