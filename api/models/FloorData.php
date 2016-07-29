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
 * @property Floor $floor
 */
class FloorData extends \app\models\FloorData
{

    public function extraFields()
    {
        $new = ['floor'];
        $fields = array_merge(parent::fields(), $new);
        return $fields;
    }
}
