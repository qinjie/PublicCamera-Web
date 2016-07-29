<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FloorNode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="floor-node-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'floorId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nodeId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created')->textInput() ?>

    <?= $form->field($model, 'modified')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
