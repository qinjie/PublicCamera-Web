<?php
namespace app\components\rbac;

use Yii;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\rbac\Rule;

/** Reference: http://rgblog.ru/page/yii2-i-rbac-kontrol-dostupa-na-osnove-rolej */
/** Checks if user role matches user passed via params  */

class UserRoleRule extends Rule
{
    public $name = 'userRole';

    public function execute($user, $item, $params)
    {
        // Use the user value passed in $params
        $user = ArrayHelper::getValue($params, 'user', User::findOne($user));
        if(!$user && isset(Yii::$app->user->identity->role)) {
            // Use the login user if no user is passed in $params
            $user = Yii::$app->user->identity;
        }
        if(!$user)
            return false;

        $role = $user->role;
        if ($item->name === 'master') {
            return $role == User::ROLE_MASTER;
        } elseif ($item->name === 'admin') {
            return $role == User::ROLE_MASTER || $role == User::ROLE_ADMIN;
        } elseif ($item->name === 'manager') {
            return $role == User::ROLE_MASTER || $role == User::ROLE_ADMIN || $role == User::ROLE_MANAGER;
        } elseif ($item->name === 'user') {
            //user is a child of editor and admin, if we have no role defined this is also the default role
            return $role == User::ROLE_MASTER || $role == User::ROLE_ADMIN || $role == User::ROLE_MANAGER || $role == User::ROLE_USER || $role == NULL;
        } else {
            return false;
        }
    }
}