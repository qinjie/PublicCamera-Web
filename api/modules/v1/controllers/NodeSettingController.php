<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 29/3/15
 * Time: 17:58
 */

namespace app\api\modules\v1\controllers;


use app\api\components\MyActiveController;
use app\api\models\Node;
use app\models\NodeSetting;
use yii\filters\AccessControl;

class NodeSettingController extends MyActiveController
{
    public $modelClass = 'app\api\models\NodeSetting';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = ['index', 'view', 'search'];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['view', 'index', 'search'],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                [
                    'actions' => ['create', 'delete', 'update', 'update-ip', ],
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

    public function actionUpdateIp($nodeId)
    {

        $ns = NodeSetting::findOne(['nodeId' => $nodeId, 'label' => 'IP']);
        if (!$ns) {
            $ns = new NodeSetting();
            $ns->nodeId = $nodeId;
            $ns->label = 'IP';
        }
        $ns->value = \Yii::$app->request->userIP;

        $ok = $ns->save();
        if ($ok)
            return 'OK';
        else
            return 'Failed';
    }

}