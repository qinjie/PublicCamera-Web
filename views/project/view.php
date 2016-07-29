<?php
use app\components\Util;
use app\models\FloorData;
use app\models\ProjectSetting;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model app\models\Project */
?>

<style>
    table#albums {
        border-collapse: separate;
        border-spacing: 10px 5px;
    }
</style>


<?php
$this->title = $model->label;
$listData = ArrayHelper::map($model->getActiveFloors(), 'id', 'label');
$floors = $dataProvider->getModels();
?>

<div class="project-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['width' => '50']],
//        'id',
            ['attribute' => 'Canteen',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->label, '#anchor' . $data->id);
                },
                'headerOptions' => ['width' => '250']],
            ['attribute' => 'nodeCount',
                'label' => 'Cameras',
                'headerOptions' => ['width' => '150', 'style' => 'text-align:left',]],
            ['label' => 'Crowd Index',
                'headerOptions' => ['width' => '150', 'style' => 'text-align:left',],
                'value' => function ($data) {
                    $obj = $data->getLatestCrowdIndex();
                    if ($obj) {
                        return $obj->value;
                    } else {
                        return '';
                    }
                },
            ],
            ['label' => 'Date/Time',
                'headerOptions' => ['width' => '150', 'style' => 'text-align:left',],
                'value' => function ($data) {
                    $obj = $data->getLatestCrowdIndex();
                    if ($obj) {
                        return $obj->created;
                    } else {
                        return '';
                    }
                },
            ],
//            'remark',
//        'status',
//        'serial',
//         'projectId',
//         'created',
//         'modified',
//        ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
<br>
<?php
$timingSettings = ProjectSetting::find(['projectId' => $model->id, 'label' => ProjectSetting::TIMING])->orderBy('value')->all();
$all_keys = ArrayHelper::getColumn($timingSettings, 'value');
$default = array_fill_keys($all_keys, null);

foreach ($floors as $floor) {
?>
<h3><?= $floor->label ?></h3>
<a name=<?= "anchor" . $floor->id ?> id=<?= "anchor" . $floor->id ?>/>
    <?php
    $data = [];

    $fd = FloorData::findAll(['floorId' => $floor->id, 'label' => FloorData::CROWD_MONTHLY]);
    $keys = ArrayHelper::getColumn($fd, 'marker');
    $vals = ArrayHelper::getColumn($fd, 'value');
    $t = array_combine($keys, array_map('intval', $vals));
    $f = array_merge($default, array_intersect_key($t, $default));
    $data[] = ['name' => "Monthly Average", 'data' => array_values($f)];

    $fd = FloorData::findAll(['floorId' => $floor->id, 'label' => FloorData::CROWD_WEEKLY]);
    $keys = ArrayHelper::getColumn($fd, 'marker');
    $vals = ArrayHelper::getColumn($fd, 'value');
    $t = array_combine($keys, array_map('intval', $vals));
    $f = array_merge($default, array_intersect_key($t, $default));
    $data[] = ['name' => "Weekly Average", 'data' => array_values($f)];

    $fd = FloorData::findAll(['floorId' => $floor->id, 'label' => FloorData::CROWD_NOW]);
    $keys = ArrayHelper::getColumn($fd, 'marker');
    $vals = ArrayHelper::getColumn($fd, 'value');
    $t = array_combine($keys, array_map('intval', $vals));
    foreach ($t as $key => $val) {
        if (strtotime($key) > time()) {
            $t[$key] = null;
        }
    }
    $f = array_merge($default, array_intersect_key($t, $default));
    $data[] = ['name' => "Last Record", 'data' => array_values($f)];

    ?>

    <?= \dosamigos\highcharts\HighCharts::widget([
        'clientOptions' => [
            'chart' => [
                'type' => 'line'
            ],
            'title' => [
                'text' => $floor->label,
            ],
            'subtitle' => [
                'text' => 'Current & Past Crowd Level'
            ],
            'xAxis' => [
                'categories' => $all_keys,
                // Not Working. Plot a vertical line at current time
                'line' => [
                    'value' => 10,
                    'width' => 10,
                    'color' => '#808080',
                ]
            ],
            'yAxis' => [
                'title' => [
                    'text' => 'Crowd Index'
                ],
                'max' => 100,
                'min' => 0,
            ],
            'series' => $data,
        ]
    ]); ?>
    <br>
    <?php
    //    echo
    //    ListView::widget([
    //        'dataProvider' => $dataProvider,
    //        'summary' => '',
    //        'itemView' => '_floorItem',
    //    ]);
    ?>

    <?php
    $provider = new ArrayDataProvider([
        'allModels' => $floor->nodes,
        'sort' => [
            'attributes' => ['id'],
        ],
        'pagination' => [
            'pageSize' => 18,
        ],
    ]);
    ?>
    <?php
    foreach ($floor->nodes as $node) {
        echo $this->render('_nodeItem', ['model' => $node]);
    }
    ?>

    <?php
    //    $count = 0;
    //    echo '<table id="albums" cellspacing="0px"  width="100%">';
    //    foreach ($floor->nodes as $node) {
    //        if ($count % 2 == 0)
    //            echo '<tr>';
    //        echo '<td width="50%">';
    //        echo $this->render('_nodeItem', ['model' => $node]);
    ////        if ($count % 2 == 0)
    ////            echo '</tr>';
    //        $count++;
    //    }
    //    if ($count % 2 == 0)
    //        echo '<td width="50%">';
    //    echo '</table>';
    ?>

    <?php
    //    $item_list=[];
    //    foreach ($floor->nodes as $node) {
    //
    //        if (!$node->latestNodeFile) return;
    //
    //        $nodeFile = $node->latestNodeFile;
    //        $thumbnailUrl = null;
    //        if ($nodeFile && Util::checkRemoteFile($nodeFile->getThumbnailUrl())) {
    //            $thumbnailUrl = $nodeFile->getThumbnailUrl();
    //        }
    //        if (!$thumbnailUrl) {
    //            $thumbnailUrl = $nodeFile->getImageNotAvailableUrl();
    //        }
    //        $fileUrl = null;
    //        if ($nodeFile && Util::checkRemoteFile($nodeFile->getFileUrl())) {
    //            $fileUrl = $nodeFile->getFileUrl();
    //        }
    //        if (!$fileUrl) {
    //            $fileUrl = $nodeFile->getImageNotAvailableUrl();
    //        }
    //
    //        $item['url'] = $fileUrl;
    //        $item['src'] = $fileUrl;
    //        if(is_null($nodeFile))
    //            $item['options'] = array('title' => $node->label);
    //        else
    //            $item['options'] = array('title' => $node->label . " (" . $nodeFile->modified . ")");
    //        $item['thumbnail'] = $thumbnailUrl;
    //        $item_list[] = $item;
    //    }
    //    echo dosamigos\gallery\Gallery::widget(['items' => $item_list]);
    ?>

    <p align="right"><a href="#anchorTop">&#8657; Top</a></p>

    <?php } ?>
