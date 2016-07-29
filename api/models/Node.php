<?php

namespace app\api\models;

use Yii;

/**
 * This is the model class for table "node".
 *
 * @property integer $id
 * @property string $label
 * @property string $type
 * @property integer $status
 * @property string $serial
 * @property integer $projectId
 * @property string $created
 * @property string $modified
 *
 * @property Project $project
 * @property NodeData[] $nodeData
 * @property NodeFile[] $nodeFile
 */
class Node extends \app\models\Node
{

    public function extraFields()
    {
        $new = ['project', 'floors', 'nodeDatas', 'nodeFiles', 'nodeSettings'];
        $fields = array_merge(parent::fields(), $new);
        return $fields;
    }

    public function fields()
    {
        $fields = parent::fields();
//        $fields[] = 'latestCrowdIndex';
        $fields[] = 'latestNodeFile';
        return $fields;
    }
}
