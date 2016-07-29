<?php
namespace app\api\models;
use app\components\MyActiveRecord;
use app\components\TokenHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\Link;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends \app\models\User
{

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token'],
            $fields['updated_at'], $fields['created_at']);
        return $fields;
    }

    public function extraFields()
    {
        $new = ['userProfile', 'projectsOwned', 'projectsInvolved'];
        $fields = array_merge(parent::fields(), $new);
        return $fields;
    }

    # For HATEOAS
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['user/view', 'id' => $this->id], true),
        ];
    }

}
