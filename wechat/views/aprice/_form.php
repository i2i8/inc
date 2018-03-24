<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use wechat\models\Amodel;
use wechat\models\Atypes;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model wechat\models\Aprice */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="aprice-form">

   <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

   <?= $form->field($model, 'model_amid')->dropDownList(Amodel::getModelname(),
       [
           'prompt' => 'Select Model',
           'id' => 'pid',
       ]
    );
   ?>

    
    <?= $form->field($model, 'types_atid')->widget(DepDrop::classname(), 
        [
            'data' => Atypes::getSubCatList($model->model_amid),
            'options'=>['id'=>'sid'],
            'pluginOptions'=>
            [
                'depends'=>['pid'],
                'placeholder'=>'Select Types...',
                'url'=>Url::to(['/atypes/smm'])
            ]
        ]
    );
    ?>

    <?= $form->field($model, 'nowprice')->textInput() ?>

    <?= $form->field($model, 'willprice')->textInput() ?>
    
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-info','style'=>'width:100%;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>