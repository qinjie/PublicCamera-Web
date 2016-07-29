<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\NodeData */

$this->title = 'Create Node Data';
$this->params['breadcrumbs'][] = ['label' => 'Node Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
