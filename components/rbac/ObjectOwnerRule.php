<?php
/**
 * Created by PhpStorm.
 * User: zqi2
 * Date: 25/5/2015
 * Time: 2:15 PM
 */

namespace app\components\rbac;


use yii\rbac\Rule;

class ObjectOwnerRule extends Rule
{
    public $name = 'isObjectOwner';

    public $param = 'userId';

    public function execute($user, $item, $params)
    {
        $result = isset($params['model']) ? $params['model'][$this->param] == $user : false;
        return $result;
    }
}