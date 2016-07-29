<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FloorNode */

$this->title = 'Update Floor Node: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Floor Nodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="floor-node-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
