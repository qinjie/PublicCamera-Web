<?php
/**
 * Created by PhpStorm.
 * User: zqi2
 * Date: 25/5/2015
 * Time: 2:15 PM
 */

namespace app\components\rbac;


use app\api\models\Node;
use app\api\models\ProjectUser;
use yii\rbac\Rule;

class NodeUserRule extends Rule
{
    public $name = 'isNodeUser';

    public function execute($user, $item, $params)
    {
        if (!isset($params['model']['nodeId'])) return false;
        $nodeId = $params['model']['nodeId'];
        $node = Node::findOne($nodeId);
        if (!$node) return false;
        $projUser = ProjectUser::findOne(['projectId' => $node->projectId, 'userId' => $user]);
        if ($projUser)
            return true;
        return false;
    }
}