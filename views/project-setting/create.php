<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProjectSetting */

$this->title = 'Create Project Setting';
$this->params['breadcrumbs'][] = ['label' => 'Project Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-setting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
