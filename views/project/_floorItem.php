<?php
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;

/* @var $model app\models\Floor */
?>

<a name=<?= "anchor" . $model->id ?> id=<?= "anchor" . $model->id ?>></a>
<h3><?= $model->label ?></h3>
<?php
if(!$model->nodes){
    echo "No camera available";
}

$provider = new ArrayDataProvider([
    'allModels' => $model->nodes,
    'sort' => [
        'attributes' => ['id'],
    ],
    'pagination' => [
        'pageSize' => 18,
    ],
]);
?>
<div class="body-content">
    <div class="row">
        <?= ListView::widget([
            'dataProvider' => $provider,
            'summary' => '',
            'emptyText' => '',
            'itemView' => '_nodeItem',
        ]); ?>
    </div>
    <p align="right"><a href="#anchorTop">&#8657; Top</a></p>
</div>