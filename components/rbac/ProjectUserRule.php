<?php
/**
 * Created by PhpStorm.
 * User: zqi2
 * Date: 25/5/2015
 * Time: 2:15 PM
 */

namespace app\components\rbac;


use app\api\models\ProjectUser;
use yii\rbac\Rule;

class ProjectUserRule extends Rule
{
    public $name = 'isProjectUser';

    public function execute($user, $item, $params)
    {
        if(!isset($params['model']['projectId'])) return false;
        $model = $params['model'];
        $obj = ProjectUser::findOne(['projectId'=>$model->projectId, 'userId'=>$user]);
        if($obj){
            return true;
        }
        return false;
    }
}