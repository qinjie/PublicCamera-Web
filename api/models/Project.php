<?php

namespace app\api\models;

use app\components\MyActiveRecord;
use Yii;

/**
 * This is the model class for table "project".
 *
 * @property integer $id
 * @property string $label
 * @property string $remark
 * @property string $serial
 * @property integer $status
 * @property integer $ownerId
 * @property string $created
 * @property string $modified
 *
 * @property Floor[] $floors
 * @property Node[] $nodes
 * @property User $owner
 * @property Projectuser[] $projectusers
 */
class Project extends \app\models\Project
{
    public function extraFields()
    {
        $new = ['owner', 'floors', 'nodes', 'users'];
        $fields = array_merge(parent::fields(), $new);
        return $fields;
    }

}
