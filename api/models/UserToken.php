<?php

namespace app\api\models;

use Yii;

/**
 * This is the model class for table "usertoken".
 *
 * @property integer $id
 * @property integer $userId
 * @property string $token
 * @property string $label
 * @property string $ipAddress
 * @property string $expire
 * @property string $created
 *
 * @property User $user
 */
class UserToken extends \app\models\UserToken
{

    public function extraFields()
    {
        $fields = parent::fields();
        $fields[] = 'user';
        return fields;
    }

}
