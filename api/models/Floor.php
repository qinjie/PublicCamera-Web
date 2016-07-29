<?php

namespace app\api\models;

use Yii;

/**
 * This is the model class for table "floor".
 *
 * @property integer $id
 * @property string $label
 * @property string $remark
 * @property integer $status
 * @property string $serial
 * @property integer $projectId
 * @property string $created
 * @property string $modified
 *
 * @property Project $project
 */
class Floor extends \app\models\Floor
{

    public function extraFields()
    {
        $new = ['project', 'nodes', 'floorDatas', 'floorSettings'];
        $fields = array_merge(parent::fields(), $new);
        return $fields;
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields[] = 'nodeCount';
        $fields[] = 'latestCrowdIndex';
        return $fields;
    }


}
