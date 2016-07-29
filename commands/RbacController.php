<?php
namespace app\commands;

use app\components\rbac\ObjectOwnerRule;
use app\models\User;
use Yii;
use yii\console\Controller;
use app\components\rbac\UserRoleRule;

## Excecute the action on command line
## "php ..\yii rbac/init"

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        ### CREATE & ADD ROLES using UserRoleRule
        $rule = new UserRoleRule();
        $auth->add($rule);

        $user = $auth->createRole('user');
        $user->ruleName = $rule->name;
        $manager = $auth->createRole('manager');
        $manager->ruleName = $rule->name;
        $admin = $auth->createRole('admin');
        $admin->ruleName = $rule->name;
        $master = $auth->createRole('master');
        $master->ruleName = $rule->name;

        $auth->add($user);
        $auth->add($manager);
        $auth->add($admin);
        $auth->add($master);

        $auth->addChild($manager, $user);
        $auth->addChild($admin, $manager);
        $auth->addChild($master, $admin);

        ### ADD RULES & CREATE PERMISSIONS ###

        $pCreate = $auth->createPermission('create');
        $pCreate->description = 'create';
        $auth->add($pCreate);

        $pIndex = $auth->createPermission('index');
        $pIndex->description = 'index';
        $auth->add($pIndex);

        $pUpdate = $auth->createPermission('update');
        $pUpdate->description = 'update';
        $auth->add($pUpdate);

        $pDelete = $auth->createPermission('delete');
        $pDelete->description = 'delete';
        $auth->add($pDelete);

        $pView = $auth->createPermission('view');
        $pView->description = 'view';
        $auth->add($pView);

        ### ASSIGN PERMISSION TO ROLES

        $auth->addChild($user, $pView);
        $auth->addChild($user, $pIndex);
        $auth->addChild($user, $pCreate);
        $auth->addChild($manager, $pUpdate);
        $auth->addChild($manager, $pDelete);

//        $objectOwnerRule = new ObjectOwnerRule();
//        $auth->add($objectOwnerRule);

//        $pViewOwn = $auth->createPermission('viewOwn');
//        $pViewOwn->description = 'view own';
//        $pViewOwn->ruleName = $objectOwnerRule->name;
//        $auth->add($pViewOwn);
//        $auth->addChild($pViewOwn, $pView);

//        $pUpdateOwnObject = $auth->createPermission('updateOwnObject');
//        $pUpdateOwnObject->description = 'update own object';
//        $pUpdateOwnObject->ruleName = $objectOwnerRule->name;
//        $auth->add($pUpdateOwnObject);
//        $auth->addChild($pUpdateOwnObject, $pUpdate);

//        $pDeleteOwnObject = $auth->createPermission('deleteOwnObject');
//        $pDeleteOwnObject->description = 'delete own object';
//        $pDeleteOwnObject->ruleName = $objectOwnerRule->name;
//        $auth->add($pDeleteOwnObject);
//        $auth->addChild($pDeleteOwnObject, $pDelete);
//        $auth->addChild($user, $pUpdateOwnObject);
//        $auth->addChild($user, $pDeleteOwnObject);

    }
}