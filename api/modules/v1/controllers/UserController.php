<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 28/3/15
 * Time: 23:28
 */

namespace app\api\modules\v1\controllers;

use app\api\components\MyActiveController;
use app\models\User;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

class UserController extends MyActiveController
{
    public $modelClass = 'app\api\models\User';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        //-- Include pagination information directly to simplify the client development work
        'collectionEnvelope' => 'items',
    ];

    //-- disable, override or add actions
    //-- when overriding default action, make sure current controller has checkAccess() method implemented
    public function actions()
    {
        $actions = [
//            'delete' => null,
            //-- Add custom action
            'user-exists' => [
                'class' => 'app\controllers\actions\ActionUserExists',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
//                'params' => \Yii::$app->request->get(),
            ]
        ];

        //-- disable the "delete" action
//        unset($actions['delete']);
//        return $actions;
        return array_merge(parent::actions(), $actions);
    }

    public function verbs()
    {
        $verbs = [
            'user-exists' => ['GET']
        ];
        return array_merge(parent::verbs(), $verbs);
    }

    /**
     * Checks the privilege of the current user.
     * @param string $action the ID of the action to be executed
     * @param \yii\base\Model $model the model to be accessed. If null, it means no specific model is being accessed.
     * @param array $params additional parameters
     * @throws ForbiddenHttpException if the user does not have access
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // check if the user can access $action and $model
        // throw ForbiddenHttpException if access should be denied
    }

}