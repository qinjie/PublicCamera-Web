<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\NodeSetting */

$this->title = 'Create Node Setting';
$this->params['breadcrumbs'][] = ['label' => 'Node Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-setting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
