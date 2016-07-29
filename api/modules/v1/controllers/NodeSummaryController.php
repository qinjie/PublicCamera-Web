<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 29/3/15
 * Time: 17:58
 */

namespace app\api\modules\v1\controllers;

use app\api\components\MyActiveController;
use app\api\models\NodeSummary;
use app\models\NodeData;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class NodeSummaryController extends MyActiveController
{
    public $modelClass = 'app\api\models\NodeSummary';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = ['index', 'view', 'search',
            'latest-by-project', 'node-crowd-average'];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['view', 'index', 'search', 'latest-by-project', 'node-crowd-average'],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                [
                    'actions' => ['create', 'delete', 'update'],
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
            FROM nodesummary AS n1
            LEFT JOIN nodesummary AS n2
              ON (n1.node_id = n2.node_id AND n1.id < n2.id)
            LEFT JOIN node AS n
              ON (n1.node_id = n.id)
            WHERE n2.node_id IS NULL AND n.projectId = :projectId";

        $models = NodeSummary::findBySql($sql, ['projectId' => $projectId])->all();

        return $models;
    }

    public function actionNodeCrowdAverage()
    {
        $minutes = 15;
        $today = date('Y-m-d', time());
        return $this->actionNodeCrowdAverageOnDate($minutes, $today);
    }

    public function actionNodeCrowdAverageOnDate($minutes = 15, $today = null)
    {
        if (is_null($today))
            $today = date('Y-m-d', time());

        $type = NodeData::CROWD_NOW_INT;
        $new_type = NodeSummary::CROWD_NOW_INT;

        $sql = "SELECT nodeId AS node_id, :new_type AS type, AVG(VALUE) AS value, 
                STR_TO_DATE(CONCAT_WS(\"-\", DATE(remark), HOUR(remark), 
                  FLOOR(MINUTE(remark)/  :minutes)*  :minutes), \"%Y-%m-%d-%k-%i\") AS marker
                FROM nodedata 
                WHERE type = :type AND DATE(remark) = :today
                GROUP BY nodeId, type, DATE(remark), 
                  HOUR(remark), FLOOR(MINUTE(remark)/ :minutes)";

//        $models = Yii::$app->db->createCommand($sql, ['label' => $label, 'new_label' => $new_label,
//            'minutes' => $minutes])->queryAll();

        $entities = NodeSummary::findBySql($sql, ['type' => $type, 'new_type' => $new_type,
            'minutes' => $minutes, 'today' => $today])->all();
        $id_list = [];
        foreach ($entities as $e) {
            $model = NodeSummary::findOne(['node_id' => $e->node_id, 'type' => $e->type, 'marker' => $e->marker]);
            if ($model)
                $model->delete();
            $model = new NodeSummary();
            $model->attributes = $e->attributes;
            if($model->save())
                $id_list[] = $model->id;
            else{
                return $model->getFirstErrors();
            }
        }
        $query = NodeSummary::find()->where(['id' => $id_list]);
        $data_provider = new ActiveDataProvider(['query' => $query]);

        return $data_provider->getModels();
    }
}