<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NodeSummary */

$this->title = 'Update Node Summary: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Node Summaries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="node-summary-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
