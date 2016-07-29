<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\NodeSummary */

$this->title = 'Create Node Summary';
$this->params['breadcrumbs'][] = ['label' => 'Node Summaries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-summary-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
