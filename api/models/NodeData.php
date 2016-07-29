<?php

namespace app\api\models;

use app\components\MyActiveRecord;
use Yii;

/**
 * This is the model class for table "nodedata".
 *
 * @property integer $id
 * @property integer $nodeId
 * @property string $label
 * @property string $value
 * @property string $created
 * @property string $modified
 *
 * @property Location $location
 * @property Node $node
 */
class NodeData extends \app\models\NodeData
{
    public function extraFields()
    {
        $new = ['node'];
        $fields = array_merge(parent::fields(), $new);
        return $fields;
    }

}
