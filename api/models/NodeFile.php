<?php

namespace app\api\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "nodefile".
 *
 * @property integer $id
 * @property integer $nodeId
 * @property string $label
 * @property string $fileName
 * @property string $fileType
 * @property integer $fileSize
 * @property integer $locationId
 * @property string $created
 * @property string $modified
 *
 * @property Node $node
 */
class NodeFile extends \app\models\NodeFile
{
    
    public function extraFields()
    {
        $new = ['node'];
        $fields = array_merge(parent::fields(), $new);
        return $fields;
    }

}
