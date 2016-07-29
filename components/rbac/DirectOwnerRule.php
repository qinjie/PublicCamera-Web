<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 16/4/15
 * Time: 08:17
 */

namespace app\components\rbac;

use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params
 */
class DirectOwnerRule extends Rule
{
    public $name = 'isDirectOwner';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($userId, $item, $params)
    {
        # Assume the model contains an attribute "userId"
        # Usage: Yii::$app->user->can('updatePost', ['model' => $model])
        return isset($params['model']) ? $params['model']['userId'] == $userId : false;
    }
}