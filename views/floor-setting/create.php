<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\FloorSetting */

$this->title = 'Create Floor Setting';
$this->params['breadcrumbs'][] = ['label' => 'Floor Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="floor-setting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
