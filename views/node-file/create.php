<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\NodeFile */

$this->title = 'Create Node File';
$this->params['breadcrumbs'][] = ['label' => 'Node Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-file-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
