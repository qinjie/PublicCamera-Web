<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 29/3/15
 * Time: 17:58
 */

namespace app\api\modules\v1\controllers;

use app\api\components\MyActiveController;
use app\api\models\NodeData;
use yii\filters\AccessControl;

class NodeDataController extends MyActiveController
{

    public $modelClass = 'app\api\models\NodeData';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = ['index', 'view', 'search', 'latest-by-project',
            'latest-by-project-and-label',
            'latest-by-project-and-type'
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['view', 'index', 'search', 'latest-by-project',
                        'latest-by-project-and-label',
                        'latest-by-project-and-type',
                    ],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                [
                    'actions' => ['create', 'delete', 'update',],
                    'allow' => true,
                    'roles' => ['user',],
                ],
            ],
            # if user not login, and not allowed for current action, return following exception
            'denyCallback' => function ($rule, $action) {
                throw new \Exception('You are not allowed to access this page');
            },
        ];

        return $behaviors;
    }

    public function actionLatestByProject($projectId)
    {
        $sql = "SELECT n1.*
            FROM nodedata AS n1
            LEFT JOIN nodedata AS n2
                ON (n1.nodeId = n2.nodeId AND n1.id<n2.id)
            LEFT JOIN node AS n
                ON (n1.nodeId = n.id)
            WHERE n2.nodeId IS NULL AND n.projectId = :projectId";

        $nodeDatas = NodeData::findBySql($sql, ['projectId' => $projectId])->all();

        return $nodeDatas;
    }

    public function actionLatestByProjectAndLabel($projectId, $label)
    {
        $sql = "SELECT n1.*
            FROM nodedata AS n1
            LEFT JOIN nodedata AS n2
                ON (n1.nodeId = n2.nodeId AND n1.type=n2.type AND n1.id<n2.id)
            LEFT JOIN node AS n
                ON (n1.nodeId = n.id)
            WHERE n2.nodeId IS NULL AND n.projectId = :projectId AND n1.label = :label";

        $nodeDatas = NodeData::findBySql($sql, ['projectId' => $projectId, 'label' => $label])->all();

        return $nodeDatas;
    }

    public function actionLatestByProjectAndType($projectId, $type)
    {
        $sql = "SELECT n1.*
            FROM nodedata AS n1
            LEFT JOIN nodedata AS n2
                ON (n1.nodeId = n2.nodeId AND n1.type=n2.type AND n1.id<n2.id)
            LEFT JOIN node AS n
                ON (n1.nodeId = n.id)
            WHERE n2.nodeId IS NULL AND n.projectId = :projectId AND n1.type = :type";

        $nodeDatas = NodeData::findBySql($sql, ['projectId' => $projectId, 'type' => $type])->all();

        return $nodeDatas;
    }
}