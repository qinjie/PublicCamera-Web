<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\FloorNode */

$this->title = 'Create Floor Node';
$this->params['breadcrumbs'][] = ['label' => 'Floor Nodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="floor-node-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
