<?php
/**
 * Created by PhpStorm.
 * User: zqi2
 * Date: 25/5/2015
 * Time: 2:35 PM
 */

namespace app\components\rbac;

use yii\base\InvalidConfigException;
use yii\filters\AccessRule;
use yii\web\NotFoundHttpException;

class ContextAccessRule extends AccessRule
{
    public $modelClass;

    public $primaryKey;

    protected function matchRole($user)
    {
        if (parent::matchRole($user))
            return true;

        $model = $this->findModel();

        foreach ($this->roles as $role) {
            if ($user->can($role, ['model' => $model])) {
                return true;
            }
        }
        return false;
    }

    protected function findModel()
    {
        if (!isset($this->modelClass)) throw new InvalidConfigException(\Yii::t('app', 'the "modelClass" must be set for "{class}".', ['class' => __CLASS__]));
        $primaryKey = $this->getPrimaryKey();

        //get request params
        $queryParams = \Yii::$app->getRequest()->getQueryParams();

        // load model
        $model = call_user_func([$this->modelClass, 'findOne'], $queryParams[join(',', $primaryKey)]);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exists.'));
        }
    }

    protected function getPrimaryKey()
    {
        if (!isset($this->primaryKey)) {
            return call_user_func([$this->modelClass, 'primaryKey']);
        } else {
            return $this->primaryKey;
        }
    }
}