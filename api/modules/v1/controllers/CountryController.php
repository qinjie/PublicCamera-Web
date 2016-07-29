<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 28/3/15
 * Time: 21:06
 */
# For Testing Purpose

namespace app\api\modules\v1\controllers;

use app\api\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\MethodNotAllowedHttpException;
use yii\web\UnauthorizedHttpException;

### Controller implements the following steps in a RESTful API request handling cycle
//1. Resolving response format (see [[ContentNegotiator]]);
//2. Validating request method (see [[verbs()]]).
//3. Authenticating user (see [[\yii\filters\auth\AuthInterface]]);
//4. Rate limiting (see [[RateLimiter]]);
//5. Formatting response data (see [[serializeData()]])

class CountryController extends ActiveController
{
    public $modelClass = 'app\api\models\Country';

    # Include envelope
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    # Disable, override or add actions
    # when overriding default action, make sure current controller has checkAccess() method implemented
    public function actions()
    {
        return parent::actions();
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        //--check if the user can access $action & $model
        //--throw ForbiddenHttpException if access should be denied
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        # Allow two types of Authentication, Basic & Token
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            # exclude actions from authentication
            'except' => ['view', 'say-hello'],
            'authMethods' => [
                # Add following key-value pair in HTTP header where username:password is 64bit-encoded
                # Authorization     Basic <username:password>
                [
                    'class' => HttpBasicAuth::className(),
                    # using custom function '$this->auth()'
                    'auth' => [$this, 'auth'],
                ],
                # Append following behind URL while making request
                # ?access-token=<token>     ?others&access-token=<token>
                QueryParamAuth::className(),
                # Add following key-value pair in HTTP header
                # Authorization     Bearer <token>
                HttpBearerAuth::className(),
            ],
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    // No authentication required
                    'actions' => ['view', 'say-hello'],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                [
                    'actions' => ['index', 'search', 'create', 'update', 'delete'],
                    'allow' => true,
                    'roles' => ['user',],
                ],
//                [
//                    // User can only update own country; Manager or Admin can update all
//                    'actions' => ['update'],
//                    'allow' => true,
//                    'class' => 'app\components\rbac\ContextAccessRule',
//                    'modelClass' => $this->modelClass,
//                    'roles' => ['update'],
//                ],
//                [
//                    // User can only delete own country; Manager or Admin can delete all
//                    'actions' => ['delete'],
//                    'allow' => true,
//                    'class' => 'app\components\rbac\ContextAccessRule',
//                    'modelClass' => $this->modelClass,
//                    'roles' => ['delete'],
//                ],
            ],

            # if user not login, and not allowed for current action, return following exception
            'denyCallback' => function ($rule, $action) {
                throw new UnauthorizedHttpException('You are not allowed to access this page');
            },
        ];

        //-- Access Rules based on Role. But difficult ot
//        $behaviors['access'] = [
//            'class' => AccessControl::className(),
//            # We will override the default rule config with the new AccessRule class
//            'ruleConfig' => [
//                'class' => \app\api\components\AccessRule::className(),
//            ],
////            'only' => ['view', 'index', 'create', 'update', 'delete'],
//            'rules' => [
//                [
//                    'actions' => ['view', 'index'],
//                    'allow' => true,
//                    'roles' => ['?'],
//                ],
//                [
//                    'actions' => ['create'],
//                    'allow' => true,
//                    // Allow users, moderators and admins to create
//                    'roles' => [
//                        User::ROLE_USER,
//                        User::ROLE_MANAGER,
//                        User::ROLE_ADMIN
//                    ],
//                ],
//                [
//                    'actions' => ['update'],
//                    'allow' => true,
//                    // Allow moderators and admins to update
//                    'roles' => [
//                        User::ROLE_MANAGER,
//                        User::ROLE_ADMIN
//                    ],
//                ],
//                [
//                    'actions' => ['delete'],
//                    'allow' => true,
//                    // Allow admins to delete
//                    'roles' => [
//                        User::ROLE_ADMIN
//                    ],
//                ],
//            ],
//
//        # if user not login, and not allowed for current action, return following exception
//        'denyCallback' => function ($rule, $action)
//        {
//            throw new UnauthorizedHttpException('Login required.');
//        },
//        ];

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

    //-- Custom action, routing is defined in web.php
    public function actionSayHello()
    {
        return [
            'message' => 'Hello World',
            'siteroot' => \Yii::getAlias('@siteroot'),
            'baseurl' => Url::base(),
            'baseurl_true' => Url::base(true),
            'module' => \Yii::$app->controller->module->id,
        ];
    }

    public function actionSearch()
    {
        if (!empty($_GET)) {
            $model = new $this->modelClass;
            foreach ($_GET as $key => $value) {
                if (!$model->hasAttribute($key)) {
                    throw new \yii\web\HttpException(404, 'Invalid attribute:' . $key);
                }
            }
            try {
                $provider = new ActiveDataProvider([
                    'query' => $model->find()->where($_GET),
                    'pagination' => false
                ]);
            } catch (Exception $ex) {
                throw new \yii\web\HttpException(500, 'Internal server error');
            }

            if ($provider->getCount() <= 0) {
                throw new \yii\web\HttpException(404, 'No entries found with this query string');
            } else {
                return $provider;
            }
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }

    }

}