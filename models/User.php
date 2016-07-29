<?php
namespace app\models;

use app\components\MyActiveRecord;
use app\components\TokenHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\filters\RateLimitInterface;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use yii\web\Link;
use yii\web\Linkable;

/** Reference: http://www.elisdn.ru/blog/65/seo-service-on-yii2-moving-users-into-db */
/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $email_confirm_token
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends MyActiveRecord implements IdentityInterface, Linkable, RateLimitInterface
{
    const STATUS_DELETED = 0;
    const STATUS_BLOCKED = 1;
    const STATUS_WAIT = 5;
    const STATUS_ACTIVE = 10;

    const ROLE_USER = 10;
    const ROLE_MANAGER = 20;
    const ROLE_ADMIN = 30;
    const ROLE_MASTER = 40;

    public $password;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#i'],
            ['username', 'unique', 'targetClass' => self::className(), 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => self::className(), 'message' => 'This email address has already been taken.'],
            ['email', 'string', 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
//    public static function findIdentityByAccessToken($token, $type = null)
//    {
//        # throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
//        return static::findOne(['access_token' => $token]);
//    }

    # Customize using TokenHelper class
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $id = TokenHelper::authenticateToken($token, true);
        if ($id) {
            return static::findIdentity($id);
//            $user = \app\models\User::find($id);
//            return $user;
        } else {
            return null;
        }
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @param string $status
     * @return static|null
     */
    public static function findByUsername($username, $status = NULL)
    {
        if(!$status) $status = self::STATUS_ACTIVE;
        return static::findOne(['username' => $username, 'status' => $status]);
    }

    public static function existsUsername($username){
        return static::find()->where(['username' => $username])->exists();
    }

    public static function existsEmail($email){
        return static::find()->where(['email' => $email])->exists();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token'],
            $fields['updated_at'], $fields['created_at']);
        return $fields;
    }

    # For HATEOAS
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['user/view', 'id' => $this->id], true),
        ];
    }

    public function beforeSave($insert)
    {
        if (isset($this->password))
            $this->setPassword($this->password);
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }


    ## To add the error message when unknown attributes are received.

    public function onUnsafeAttribute($name, $value)
    {
        parent::onUnsafeAttribute($name, $value);

        $this->addError(
            $name,
            Yii::t('app', 'Unknown parameter `{name}`', ['name' => $name])
        );
    }

    public function clearErrors($attribute = null)
    {
        if (!$attribute || !isset($this->attributes[$attribute])) {
            return;
        }

        parent::clearErrors($attribute);
    }

    ## Implementation for RateLimitInterface

    /**
     * Returns the maximum number of allowed requests and the window size.
     * @param \yii\web\Request $request the current request
     * @param \yii\base\Action $action the action to be executed
     * @return array an array of two elements. The first element is the maximum number of allowed requests,
     * and the second element is the size of the window in seconds.
     */
    public function getRateLimit($request, $action)
    {
        ## HARDCODED
        //-- 300 times for every 10 minutes
        return [6000, 600];
    }

    /**
     * Loads the number of allowed requests and the corresponding timestamp from a persistent storage.
     * @param \yii\web\Request $request the current request
     * @param \yii\base\Action $action the action to be executed
     * @return array an array of two elements. The first element is the number of allowed requests,
     * and the second element is the corresponding UNIX timestamp.
     */
    public function loadAllowance($request, $action)
    {
        if (is_null($this->allowance || is_null($this->timestamp))) {
            ## HARDCODED
            $this->timestamp = 0;
            $this->save(false, ['allowance', 'timestamp']);
        }
        return [$this->allowance, $this->timestamp];
    }

    /**
     * Saves the number of allowed requests and the corresponding timestamp to a persistent storage.
     * @param \yii\web\Request $request the current request
     * @param \yii\base\Action $action the action to be executed
     * @param integer $allowance the number of allowed requests remaining.
     * @param integer $timestamp the current timestamp.
     */
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = $allowance;
        $this->timestamp = $timestamp;
        $this->save(false, ['allowance', 'timestamp']);
    }

    ## Define Relationships

    public function getUserTokens()
    {
        return $this->hasMany(UserToken::className(), ['userId' => 'id']);
    }

    public function getProjectsInvolved()
    {
        return $this->hasMany(Project::className(), ['ID' => 'projectId'])
            ->viaTable(ProjectUser::className(), ['userId' => 'ID']);
    }

    public function getProjectsOwned()
    {
        return $this->hasMany(Project::className(), ['ownerId' => 'id']);
    }

    public function extraFields()
    {
        $new = ['projectsOwned', 'projectsInvolved'];
        $fields = array_merge(parent::fields(), $new);
        return $fields;
    }

    /**
     * @param string $email_confirm_token
     * @return static|null
     */
    public static function findByEmailConfirmToken($email_confirm_token)
    {
        return static::findOne(['email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_WAIT]);
    }

    /**
     * Generates email confirmation token
     */
    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Removes email confirmation token
     */
    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }

}
