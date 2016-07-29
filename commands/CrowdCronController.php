<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\api\models\FloorData;
use app\api\models\NodeFile;
use app\api\models\NodeSummary;
use yii\console\Controller;
use yii\data\ActiveDataProvider;

## "php ..\yii crowd-cron/node-file-delete-hours-older"

/**
 * This controller contains actions which is to be scheduled.
 */
class CrowdCronController extends Controller
{

    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }
    
}
