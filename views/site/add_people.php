<?php
 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;
 
$this->title = 'Add People';
$this->params['breadcrumbs'][] = $this->title;
$url = \yii\helpers\Url::to(['city-list']);
?>
<div class="site-add_people">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please fill out the following fields to signup:</p>
    <div class="row">
        <div class="col-lg-5">
 
            <?php $form = ActiveForm::begin(['id' => 'form-add_people']); ?>
                <?= $form->field($model, 'firstname')->textInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'lastname')->textInput() ?>
                <?= $form->field($model, 'middlename')->textInput() ?>
                <?= $form->field($model, 'city_id')->widget(Select2::classname(), [
                    'options' => ['multiple'=>false, 'placeholder' => 'Search for a city ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => $url,
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(city) { return city.text; }'),
                        'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                    ],
                ]); ?>
                <?= $form->field($model, 'date_of_birth')->textInput() ?>
                <?= $form->field($model, 'date_of_death')->textInput() ?>
                <?= $form->field($model, 'description')->textInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Add', ['class' => 'btn btn-primary', 'name' => 'add_people-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
 
        </div>
    </div>
</div>