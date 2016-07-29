<?php
use app\components\Util;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\web\View;

/* @var $model app\models\Node */
/* @var $nodeFile app\models\NodeFile */
?>

<style>
    .image {
        position: relative;
        width: 100%;
    }

    .imageTitle {
        position: absolute;
        top: 0px;
        left: 0px;
        background-color: black;
        opacity: 0.8;
        color: #fff;
        display: inline;
        padding: 0.5rem;
    }

    .imageFooter {
        position: absolute;
        bottom: 0px;
        right: 0px;
        opacity: 0.3;
        background-color: black;
        color: #fff;
        display: inline;
        padding: 0.5rem;
    }

    .imageRefresh {
        position: absolute;
        top: 0px;
        right: 0px;
        display: inline;
        padding: 0.5rem;
    }

    .imageBottomLeft {
        position: absolute;
        bottom: 0px;
        left: 0px;
        opacity: 0.3;
        background-color: black;
        color: #fff;
        display: inline;
        padding: 0.5rem;
    }
</style>

<?php
if (!$model->latestNodeFile) return;

$nodeFile = $model->latestNodeFile;
$thumbnailUrl = null;
if ($nodeFile && Util::checkRemoteFile($nodeFile->getThumbnailUrl())) {
    $thumbnailUrl = $nodeFile->getThumbnailUrl();
}
if (!$thumbnailUrl) {
    $thumbnailUrl = $nodeFile->getImageNotAvailableUrl();
}
$fileUrl = null;
if ($nodeFile && Util::checkRemoteFile($nodeFile->getFileUrl())) {
    $fileUrl = $nodeFile->getFileUrl();
}
if (!$fileUrl) {
    $fileUrl = $nodeFile->getImageNotAvailableUrl();
}

//$btId = "refreshButton" . $model->id;
//$script = "var bt = '#" . $btId . "';";
//$script = $script . <<< JS
//    $(document).ready(function() {
//        setInterval(function(){ $(bt).click(); }, 5000);
//    });
//JS;
//$this->registerJs($script, View::POS_END, $btId);
?>

<div class="image">
    <?= Html::img($fileUrl, ['alt' => $nodeFile->label, 'style' => "width: 100%;max-height: 100%"]) ?>
    <div class="imageTitle">
        <?= $model->label ?>
    </div>
    <div class="imageFooter">
        <?= $nodeFile->modified ?>
    </div>
<!--    <div class="imageBottomLeft">-->
<!--        --><?php
//        $time = new \DateTime('now', new \DateTimeZone('Asia/Singapore'));
//        echo $time->format('H:i:s');
//        ?>
<!--    </div>-->
</div>
<br><br>