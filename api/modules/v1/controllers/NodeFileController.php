<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 29/3/15
 * Time: 17:58
 */

namespace app\api\modules\v1\controllers;

use app\api\components\CrowdIndexCal;
use app\api\components\MyActiveController;
use app\api\models\NodeData;
use app\api\models\NodeFile;
use app\models\FloorData;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\imagine\Image;

class NodeFileController extends MyActiveController
{
    const TAG = 'NodeFileController';

    public $modelClass = 'app\api\models\NodeFile';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = ['index', 'view', 'search',
            'latest-by-project',
            'latest-by-floor',
            'book-one'];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['view', 'index', 'search',
                        'latest-by-project',
                        'latest-by-floor', 'book-one'],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                [
                    'actions' => ['delete', 'update', 'upload'],
                    'allow' => true,
                    'roles' => ['user',],
                ],
                [
                    'actions' => ['delete-hours-older', 'keep-latest-n-each'],
                    'allow' => true,
                    'roles' => ['manager',],
                ],
//                [
//                    'actions' => ['update'],
//                    'allow' => true,
//                    'class' => 'app\components\rbac\ContextAccessRule',
//                    'modelClass' => $this->modelClass,
//                    'roles' => ['update'],
//                ],
//                [
//                    // User can only delete own country; Manager or Admin can delete all
//                    'actions' => ['delete'],
//                    'allow' => true,
//                    'class' => 'app\components\rbac\ContextAccessRule',
//                    'modelClass' => $this->modelClass,
//                    'roles' => ['delete'],
//                ],
            ],
            # if user not login, and not allowed for current action, return following exception
            'denyCallback' => function ($rule, $action) {
                throw new \Exception('You are not allowed to access this page');
            },
        ];

        return $behaviors;
    }


    # Define allowed Verbs for actions
    protected function verbs()
    {
        $verbs = parent::verbs();
        $verbs["upload"] = ['POST'];
        return $verbs;
    }

    public function actionUpload()
    {
        \yii::$app->request->enableCsrfValidation = false;
        $key_file = 'file';

        if (!array_key_exists($key_file, $_FILES)) {
            throw new InvalidParamException("No file uploaded with key = 'file'");
        }

        $nodeFile = new NodeFile();
        $nodeFile->attributes = $_POST;
        $nodeFile->fileType = $_FILES[$key_file]["type"];
        $nodeFile->fileSize = $_FILES[$key_file]["size"];
        if (!$nodeFile->validate()) {
            throw new InvalidParamException(implode('', $nodeFile->getFirstErrors()));
        }

        $t = explode(".", $_FILES[$key_file]["name"]);
        $fileExt = end($t);

        //-- get the nodeId as string with leading zeros
        $node_id = isset($nodeFile->node) ? $nodeFile->node->id : 0;
        $node_id_str = str_pad($node_id, 4, '0', STR_PAD_LEFT);
        //-- split the microtime on space with two tokens $usec and $sec
        list($usec, $sec) = explode(' ', microtime());
        $newFileName = $node_id_str . '_' . date('Ymd_His') . str_replace("0.", "_", $usec) . '.' . $fileExt;
        Yii::info("Uploaded file: " . $newFileName);
//        $newFileName = microtime() . '.' . end($temp);
        $nodeFile->fileName = $newFileName;

        //-- make sure the destination folder is ready
        $folder = \Yii::getAlias('@siteroot') . \Yii::$app->params['folder.upload.files'];
        if (!file_exists($folder) && !is_dir($folder))
            mkdir($folder, 0777, TRUE);

        $ok = move_uploaded_file($_FILES[$key_file]["tmp_name"], $folder . $newFileName);
        if (!$ok) {
            $error = "Failed to copy file to $folder";
            \Yii::error($error, "NodeFileController");
            throw new ServerErrorHttpException("Error in saving uploaded file", 500);
        }

        //-- get the file type
        if (!$nodeFile->fileType) {
            //-- XAMPP need to enable extension=php_fileinfo.dll in php.ini
            $fInfo = finfo_open(FILEINFO_MIME_TYPE);
            $nodeFile->fileType = finfo_file($fInfo, $folder . $newFileName);
        }
        //-- create thumbnail if it's an image type
        if (exif_imagetype($folder . $newFileName)) {
            Image::thumbnail($folder . $newFileName, 400, 400)->save($folder . 'thumbnail_' . $newFileName, ['quality' => 80]);
        }

        $ok = $nodeFile->save();
        if (!$ok) {
            $error = implode('', $nodeFile->getFirstErrors());
            \Yii::error($error, "NodeFileController");
            throw new ServerErrorHttpException($error, 500);
        }

//        //-- calculate crowd index using python file
//        $bg_image = \Yii::getAlias('@siteroot') . \Yii::$app->params['folder.upload.reference'] . $node_id_str . '.jpg';
//        $new_image = \Yii::getAlias('@siteroot') . \Yii::$app->params['folder.upload.files'] . $newFileName;
//        $crowd_index = CrowdIndexCal::calculate($bg_image, $new_image);
//        if (is_numeric($crowd_index)) {
//            // If crowd index is calculated correctly, save it.
//            $node_data = new NodeData();
//            $node_data->nodeId = $node_id;
//            $node_data->label = FloorData::CROWD_NOW;
//            $node_data->value = $crowd_index;
//            $node_data->save();
//        }

        \Yii::info("File uploaded $newFileName.", "NodeFileController");

//        return $bg_image. ', ' . $new_image . ', ' . $crowd_index;
        return $nodeFile;
    }

    public function actionLatestByProject($projectId)
    {
        $sql = "SELECT n1.*
            FROM nodefile AS n1
            LEFT JOIN nodefile AS n2
              ON (n1.nodeId = n2.nodeId AND n1.id < n2.id)
            LEFT JOIN node AS n
              ON (n1.nodeId = n.id)
            WHERE n2.nodeId IS NULL AND n.projectId = :projectId";

        $nodeFiles = NodeFile::findBySql($sql, ['projectId' => $projectId])->all();

        return $nodeFiles;
    }

    public function actionLatestByFloor($floorId)
    {
        $sql = "SELECT n1.*
            FROM nodefile AS n1
            LEFT JOIN nodefile AS n2
              ON (n1.nodeId = n2.nodeId AND n1.id < n2.id)
            LEFT JOIN node AS n
              ON (n1.nodeId = n.id)
            WHERE n2.nodeId IS NULL AND n.floorId = :floorId";

        $nodeFiles = NodeFile::findBySql($sql, ['floorId' => $floorId])->all();

        return $nodeFiles;
    }

    public function actionDeleteHoursOlder($hours = 168)
    {
        $deadline = date("Y-m-d H:i:s", strtotime('-' . $hours . ' hours'));
        $files = NodeFile::find()->where('modified < :modified', [':modified' => $deadline])->all();
        $count = 0;
        foreach ($files as $file) {
            // Use AR delete so that it wil delete the related file too
            if ($file->delete())
                $count = $count + 1;
        }
        return ['found' => sizeof($files), 'deleted' => $count];
    }

    public function actionKeepLatestNEach($cnt = 720)
    {
        //-- Keep latest N record for each node
        $sql = "select p.*
                FROM nodefile AS p
                LEFT JOIN (
                    SELECT id, nodeId FROM
                    (	SELECT id, nodeId,
                        @rn := IF(@prev = nodeId, @rn + 1, 1) AS rn,
                        @prev := nodeId
                            FROM nodefile
                            JOIN (SELECT @prev := NULL, @rn := 0) AS vars
                            ORDER BY nodeId, id DESC
                     ) AS t
                     WHERE rn <= :latest_n
                ) p2 USING(id) 
                WHERE p2.id IS NULL";
        $files = NodeFile::findBySql($sql,
            ['latest_n' => $cnt])->all();
        $count = 0;
        foreach ($files as $file) {
            // Use AR delete so that it wil delete the related file too
            if ($file->delete())
                $count = $count + 1;
        }
        return ['found' => sizeof($files), 'deleted' => $count];
    }

    public function actionBookOne()
    {
        $file = NodeFile::findOne(['status' => NodeFile::STATUS_NEW]);
        if ($file) {
            $file->status = NodeFile::STATUS_PROCESSING;
            $file->save();
            return $file;
        }
        throw new NotFoundHttpException("No un-processed NodeFile found.");
    }
}