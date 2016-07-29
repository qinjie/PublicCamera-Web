<?php

namespace app\api\models;

use Yii;

/**
 * This is the model class for table "nodesummary".
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
class NodeSummary extends \app\models\NodeSummary
{
    public function extraFields()
    {
        $new = ['node'];
        $fields = array_merge(parent::fields(), $new);
        return $fields;
    }

}
