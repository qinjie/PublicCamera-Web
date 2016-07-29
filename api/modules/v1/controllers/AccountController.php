<?php

namespace app\api\modules\v1\controllers;

use app\components\TokenHelper;
use app\api\components\AccessRule;
use app\api\models\User;
use app\api\models\UserToken;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

class AccountController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'except' => [],
            'authMethods' => [
                [
                    'class' => HttpBasicAuth::className(),
                    'auth' => [$this, 'auth'],
                ],
            ],
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            # We will override the default rule config with the new AccessRule class
            'ruleConfig' => [
                'class' => AccessRule::className(),
            ],
            'only' => ['login', 'logoutCurrentSession', 'logoutAllSessions'],
            'rules' => [
                [
                    'actions' => ['login'],
                    'allow' => true,
                    # Allow any user
                    'roles' => ['?'],
                ],
                [
                    'actions' => ['logoutCurrentSession', 'logoutAllSessions'],
                    'allow' => true,
                    # Allow any authenticated user
                    'roles' => ['@'],
                ],
            ],

            # if user not login, and not allowed for current action, return following exception
            'denyCallback' => function ($rule, $action) {
                throw new UnauthorizedHttpException('Login required.');
            },
        ];

        return $behaviors;
    }

    public function auth($username, $password)
    {
        // Return Identity object or null
        $user = User::findByUsername($username);
        if ($user && $user->validatePassword($password))
            return $user;
        else
            return null;
    }

    ## Actions

    public function actionLogin()
    {
        $user = User::findOne(Yii::$app->getUser()->identity->getId());
        $token = TokenHelper::createUserToken($user->id);
        return $this->serializeData($token);
    }

    public function actionLogoutCurrentSession()
    {
        $token_string = Yii::$app->getRequest()->getQueryParam('access-token');
        $id = Yii::$app->getUser()->identity->getId();
        $token = UserToken::findOne(['userId' => $id, 'token' => $token_string, 'label' => 'ACCESS']);
        if (!$token) {
            throw new NotFoundHttpException('Token not found: ' . $token_string);
        } else {
            Yii::$app->response->statusCode = 204;
            return $token->delete();
        }
    }

    public function actionLogoutAllSessions()
    {
        $id = Yii::$app->getUser()->identity->getId();
        Yii::$app->response->statusCode = 204;
        return UserToken::deleteAll(['userId' => $id, 'label' => 'ACCESS']);
    }
}