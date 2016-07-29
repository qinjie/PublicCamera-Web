<?php

namespace app\api\models;

use Yii;

/**
 * This is the model class for table "nodesetting".
 *
 * @property string $id
 * @property string $nodeId
 * @property string $label
 * @property string $value
 * @property string $created
 * @property string $modified
 *
 * @property Node $node
 */
class NodeSetting extends \app\models\NodeSetting
{
    public function extraFields()
    {
        $new = ['node'];
        $fields = array_merge(parent::fields(), $new);
        return $fields;
    }

}
