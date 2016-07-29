<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\FloorData */

$this->title = 'Create Floor Data';
$this->params['breadcrumbs'][] = ['label' => 'Floor Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="floor-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
