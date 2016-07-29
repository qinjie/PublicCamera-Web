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
    public $name = 'isProjectOwner';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($userId, $item, $params)
    {
        return isset($params['project']) ? $params['project']->ownerId == $userId : false;
    }
}