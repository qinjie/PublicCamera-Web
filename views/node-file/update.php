<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NodeFile */

$this->title = 'Update Node File: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Node Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="node-file-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
