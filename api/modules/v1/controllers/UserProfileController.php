<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 29/3/15
 * Time: 17:58
 */

namespace app\api\modules\v1\controllers;


use app\api\modules\MyActiveController;

class UserProfileController extends MyActiveController
{

    public $modelClass = 'app\api\models\UserProfile';
}