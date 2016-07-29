<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 31/3/15
 * Time: 13:31
 */
namespace app\api\moudles\v1\controllers\actions;

use yii\rest\Action;

class ActionUserExists extends Action
{
    /**
     * Displays a model.
     * @param string $id the primary key of the model.
     * @return \yii\db\ActiveRecordInterface the model being displayed
     */
    public function run($username)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }
        $modelClass = $this->modelClass;
        $model = $modelClass::find()->where(['username' => $username])->one();
        return !is_null($model);
    }

}