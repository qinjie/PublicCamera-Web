<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 27/4/15
 * Time: 10:08
 */

namespace app\components;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\Linkable;

class MyActiveRecord extends ActiveRecord
{

    public function afterSave($insert, $changedAttributes)
    {
        $this->refresh();
        parent::afterSave($insert, $changedAttributes);
    }

}