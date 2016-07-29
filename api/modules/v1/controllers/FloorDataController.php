<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 29/3/15
 * Time: 17:58
 */

namespace app\api\modules\v1\controllers;

use app\api\components\MyActiveController;
use app\api\models\Floor;
use app\api\models\FloorData;
use app\models\ProjectSetting;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use app\api\models\NodeFile;
use app\api\models\NodeSummary;
use yii\console\Controller;

class FloorDataController extends MyActiveController
{
    public $modelClass = 'app\api\models\FloorData';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = ['index', 'view', 'search',
            'list-by-project-and-label',
            'list-by-floor-and-label',
            'latest-by-project-and-label',
            'latest-by-floor-and-label',
            'list-by-project-and-type',
            'list-by-floor-and-type',
            'latest-by-project-and-type',
            'latest-by-floor-and-type',
            'floor-crowd-today',
            'floor-crowd-weekly',
            'floor-crowd-monthly',
//            'floor-crowd-weekdays'
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['view', 'index', 'search',
                        'list-by-project-and-label',
                        'list-by-floor-and-label',
                        'latest-by-project-and-label',
                        'latest-by-floor-and-label',
                        'list-by-project-and-type',
                        'list-by-floor-and-type',
                        'latest-by-project-and-type',
                        'latest-by-floor-and-type',
                        'floor-crowd-today',
                        'floor-crowd-weekly',
                        'floor-crowd-monthly',
//                        'floor-crowd-weekdays'
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

    private function filterMarkerByProjectTiming($projectId, $models)
    {
        $ps = ProjectSetting::find()
            ->where(["projectId" => $projectId, "label" => "Timing"])
            ->orderBy('value')
            ->all();
        $timings = [];
        foreach ($ps as $item) {
            $timings[] = $item->value;
        }

        $array = [];
        foreach ($models as $model) {
            $array[$model->marker][] = $model;
        }
        $array2 = array_values(array_intersect_key($array, array_flip($timings)));

        $result = array();
        foreach ($array2 as $arr) {
            $result = array_merge($result, $arr);
        }
        return $result;
    }


    public function actionListByProjectAndLabel($projectId, $label)
    {
        $sql = 'SELECT n1.*
            FROM floordata AS n1
            LEFT JOIN floor AS n
                ON (n1.floorId = n.id)
            WHERE n.projectId = :projectId AND n1.label = :label AND n1.marker IS NOT NULL
            ORDER BY n1.floorId, n1.marker';

        $models = FloorData::findBySql($sql, ['projectId' => $projectId, 'label' => $label])->all();
//        return $models;

        $result = $this->filterMarkerByProjectTiming($projectId, $models);

        return $result;
    }

    public function actionListByProjectAndType($projectId, $type)
    {
        $sql = 'SELECT n1.*
            FROM floordata AS n1
            LEFT JOIN floor AS n
                ON (n1.floorId = n.id)
            WHERE n.projectId = :projectId AND n1.type = :type AND n1.marker IS NOT NULL
            ORDER BY n1.floorId, n1.marker';

        $models = FloorData::findBySql($sql, ['projectId' => $projectId, 'type' => $type])->all();
//        return $models;

        $result = $this->filterMarkerByProjectTiming($projectId, $models);

        return $result;
    }

    public function actionListByFloorAndLabel($floorId, $label)
    {
        $sql = 'SELECT n1.*
            FROM floordata AS n1
            WHERE n1.floorId = :floorId AND n1.label = :label AND n1.marker IS NOT NULL
            ORDER BY n1.marker';

        $models = FloorData::findBySql($sql, ['floorId' => $floorId, 'label' => $label])->all();
//        return $models;

        $floor = Floor::findOne($floorId);
        $result = $this->filterMarkerByProjectTiming($floor->projectId, $models);

        return $result;
    }

    public function actionListByFloorAndType($floorId, $type)
    {
        $sql = 'SELECT n1.*
            FROM floordata AS n1
            WHERE n1.floorId = :floorId AND n1.type = :type AND n1.marker IS NOT NULL
            ORDER BY n1.marker';

        $models = FloorData::findBySql($sql, ['floorId' => $floorId, 'type' => $type])->all();
//        return $models;

        $floor = Floor::findOne($floorId);
        $result = $this->filterMarkerByProjectTiming($floor->projectId, $models);

        return $result;
    }

    public function actionLatestByProjectAndLabel($projectId, $label)
    {
        $sql = "SELECT n1.*
            FROM floordata AS n1
            LEFT JOIN floordata AS n2
                ON (n1.floorId = n2.floorId AND n1.type=n2.type AND n1.id<n2.id)
            LEFT JOIN floor AS n
                ON (n1.floorId = n.id)
            WHERE n2.floorId IS NULL AND n.projectId = :projectId AND n1.label = :label";

        $models = FloorData::findBySql($sql, ['projectId' => $projectId, 'label' => $label])->all();

        return $models;
    }

    public function actionLatestByProjectAndType($projectId, $type)
    {
        $sql = "SELECT n1.*
            FROM floordata AS n1
            LEFT JOIN floordata AS n2
                ON (n1.floorId = n2.floorId AND n1.type=n2.type AND n1.id<n2.id)
            LEFT JOIN floor AS n
                ON (n1.floorId = n.id)
            WHERE n2.floorId IS NULL AND n.projectId = :projectId 
            AND n1.type = :type ";

        $models = FloorData::findBySql($sql, ['projectId' => $projectId, 'type' => $type])->all();

        return $models;
    }

    public function actionLatestByFloorAndLabel($floorId, $label)
    {
        $sql = "SELECT n1.*
            FROM floordata AS n1
            LEFT JOIN floordata AS n2
                ON (n1.floorId = n2.floorId AND n1.type=n2.type AND n1.id<n2.id)
            WHERE n2.floorId IS NULL AND n1.floorId = :floorId AND n1.label = :label";

        $models = FloorData::findBySql($sql, ['floorId' => $floorId, 'label' => $label])->all();

        return $models;
    }

    public function actionLatestByFloorAndType($floorId, $type)
    {
        $sql = "SELECT n1.*
            FROM floordata AS n1
            LEFT JOIN floordata AS n2
                ON (n1.floorId = n2.floorId AND n1.type =n2.type AND n1.id<n2.id)
            WHERE n2.floorId IS NULL AND n1.floorId = :floorId AND n1.type = :type";

        $models = FloorData::findBySql($sql, ['floorId' => $floorId, 'type' => $type])->all();

        return $models;
    }


    public function actionFloorCrowdToday()
    {
        $old_type = NodeSummary::CROWD_NOW_INT;
        $new_type = FloorData::CROWD_NOW_INT;

        $today = date('Y-m-d', time());
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                if ($key == 'date') {
                    $today = $value;
                }
            }
        }

        $sql = "SELECT s.floorId, :new_type as type, s.marker, AVG(s.value) AS value, s.created
                FROM (
                    SELECT f.floorId, n.type,                         
                        DATE_FORMAT(n.marker,'%H:%i') AS marker, 
                        n.value*f.weight AS value, n.marker as created
                    FROM nodesummary AS n
                    LEFT JOIN node AS f ON (n.node_id = f.id)
                    WHERE n.type = :type  AND DATE(n.marker) = :date
                ) AS s
                GROUP BY s.floorId, s.type, s.marker, s.created";

        $entities = FloorData::findBySql($sql, ['new_type' => $new_type,
            'type' => $old_type, 'date' => $today])->all();

        // Clear all existing data of same day
        FloorData::deleteAll('date(created) = :date', [':date' => $today]);

        $id_list = [];
        foreach ($entities as $e) {
            $model = new FloorData();
            $model->attributes = $e->attributes;
            $model->created = $today . ' ' . $e->marker;
            $model->save();
            if ($model->hasErrors()) {
                return $model->getErrors();
            }
            $id_list[] = $model->id;
        }
        $query = FloorData::find()->where(['id' => $id_list]);
        $data_provider = new ActiveDataProvider(['query' => $query]);

        return $data_provider->getModels();
    }


    public function actionFloorCrowdWeekly()
    {
        $today = date('Y-m-d', time());
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                if ($key == 'date') {
                    $today = $value;
                }
            }
        }

        $days = 7;
        $find_type = NodeSummary::CROWD_NOW_INT;
        $new_type = FloorData::CROWD_WEEKLY_INT;

        $data_provider = $this->actionFloorCrowdDays($days, $find_type, $new_type, $today);

        return $data_provider->getModels();
    }

    public function actionFloorCrowdMonthly()
    {
        $today = date('Y-m-d', time());
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                if ($key == 'date') {
                    $today = $value;
                }
            }
        }

        $days = 30;
        $find_type = NodeSummary::CROWD_NOW_INT;
        $new_type = FloorData::CROWD_MONTHLY_INT;

        $data_provider = $this->actionFloorCrowdDays($days, $find_type, $new_type, $today);

        return $data_provider->getModels();
    }

//    public function actionFloorCrowdWeekdays()
//    {
//        $today = date('Y-m-d', time());
//        if (!empty($_GET)) {
//            foreach ($_GET as $key => $value) {
//                if ($key == 'date') {
//                    $today = $value;
//                }
//            }
//        }
//
//        // $dayofweek: weekday index for date (1 = Sunday, 2 = Monday, …, 7 = Saturday)
//        $week_days = array(
//            '2' => 'Monday', '3' => 'Tuesday', '4' => 'Wednesday',
//            '5' => 'Thursday', '6' => 'Friday', '7' => 'Saturday', '1' => 'Sunday');
//        $days = 60;
//        $find_label = NodeSummary::CROWD_NOW;
//
//        $id_list = [];
//        foreach ($week_days as $key => $value) {
//            $new_label = 'Crowd' . $value;
//            $ids = $this->actionFloorCrowdWeekday($key, $new_label, $find_label, $days, $today);
//            $id_list = array_merge($id_list, $ids);
//        }
//        $query = FloorData::find()->where(['id' => $id_list]);
//        $data_provider = new ActiveDataProvider(['query' => $query]);
//
//        return $data_provider->getModels();
//    }

    private function actionFloorCrowdDays($days, $find_type, $new_type, $today = null)
    {
        if (is_null($today))
            $today = date('Y-m-d', time());

        $sql = "SELECT s.floorId, :new_type as type, AVG(s.value) AS value, s.marker, 
                  CONCAT(:today, ' ', s.marker) AS created
                FROM (
                    SELECT f.floorId, n.node_id, n.type, n.value*f.weight AS value, 
                        DATE_FORMAT(n.marker,'%H:%i') AS marker
                    FROM nodesummary AS n
                    LEFT JOIN node AS f ON (n.node_id = f.id)
                    WHERE n.type = :type  AND 
                      (DATE(n.modified_at) BETWEEN DATE_SUB(:today, INTERVAL :days DAY) AND :today)
                ) AS s
                GROUP BY s.floorId, s.type, s.marker";

        $entities = FloorData::findBySql($sql,
            ['type' => $find_type, 'new_type' => $new_type, 'days' => $days, 'today' => $today])->all();

        $id_list = [];
        foreach ($entities as $e) {
            $model = FloorData::findOne(['floorId' => $e->floorId, 'type' => $e->type, 'marker' => $e->marker]);
            if (!$model) {
                $model = new FloorData();
            }
            $model->attributes = $e->attributes;
            $model->created = $today . ' ' . $e->marker;
            $model->save();
            if ($model->hasErrors()) {
                return $model->getErrors();
            }
            $id_list[] = $model->id;
        }
        $query = FloorData::find()->where(['id' => $id_list]);
        $data_provider = new ActiveDataProvider(['query' => $query]);
        return $data_provider;
    }

//    private function actionFloorCrowdWeekday($day_of_week, $new_label, $find_label, $new_type, $find_type, $days, $today = null)
//    {
//        // $dayofweek: weekday index for date (1 = Sunday, 2 = Monday, …, 7 = Saturday)
//
//        if (is_null($today))
//            $today = date('Y-m-d', time());
//
//        $sql = "SELECT s.floorId, :new_label AS label, :new_type as type, AVG(s.value) AS value, s.marker
//                FROM (
//                    SELECT f.floorId, n.node_id, n.label, n.value*f.weight AS value,
//                        DATE_FORMAT(n.marker,'%H:%i') AS marker
//                    FROM nodesummary AS n
//                    LEFT JOIN floornode AS f ON (n.node_id = f.nodeId)
//                    WHERE n.type = :type  AND DAYOFWEEK(n.modified_at) = :day_of_week
//                      AND (DATE(n.modified_at) BETWEEN DATE_SUB(:today, INTERVAL :days DAY) AND :today)
//                ) AS s
//                GROUP BY s.floorId, s.type, s.marker";
//
//        $entities = FloorData::findBySql($sql,
//            ['type' => $find_type, 'new_label' => $new_label,
//                'day_of_week' => $day_of_week, 'days' => $days, 'today' => $today])->all();
//
//
//        $id_list = [];
//        foreach ($entities as $e) {
//            $model = FloorData::findOne(['floorId' => $e->floorId, 'label' => $e->label, 'marker' => $e->marker]);
//            if (!$model) {
//                $model = new FloorData();
//            }
//            $model->attributes = $e->attributes;
//            $model->created = $today . ' ' . $e->marker;
//
//            $model->save();
//            if ($model->hasErrors()) {
//                return $model->getErrors();
//            }
//            $id_list[] = $model->id;
//        }
//        return $id_list;
//    }
}