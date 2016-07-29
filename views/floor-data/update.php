<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FloorData */

$this->title = 'Update Floor Data: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Floor Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="floor-data-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
