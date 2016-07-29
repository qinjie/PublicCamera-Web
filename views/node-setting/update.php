<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NodeSetting */

$this->title = 'Update Node Setting: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Node Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="node-setting-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
