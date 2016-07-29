<?php

namespace app\models;

use app\components\MyActiveRecord;
use DateTime;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "nodedata".
 *
 * @property integer $id
 * @property integer $nodeId
 * @property string $label
 * @property string $type
 * @property string $value
 * @property integer $nodeFileId
 * @property string $remark
 * @property string $created
 * @property string $modified
 *
 * @property Node $node
 */
class NodeData extends MyActiveRecord
{
    const CROWD_NOW = 'CrowdNow';
    const CROWD_NOW_INT = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nodedata';
    }

    public function behaviors()
    {
        return [
//            'timestamp' => [
//                'class' => TimestampBehavior::className(),
//                // Modify only created not updated attribute
//                'attributes' => [
//                    ActiveRecord::EVENT_BEFORE_INSERT => ['created'],
//                    ActiveRecord::EVENT_BEFORE_UPDATE => ['modified'],
//                ],
//                'value' => new Expression('NOW()'),
//            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => null,
                'updatedByAttribute' => null,
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if (is_null($this->created)) {
            $now = new DateTime();
            $this->created = $now->format('Y-m-d H:i:s');
        }
        switch ($this->type) {
            case NodeData::CROWD_NOW_INT:
                $this->label = NodeData::CROWD_NOW;
                break;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nodeId'], 'required'],
            [['nodeId', 'type'], 'integer'],
            [['nodeFileId', 'remark', 'created', 'modified'], 'safe'],
            [['label'], 'string', 'max' => 20],
            [['value'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nodeId' => 'Node ID',
            'label' => 'Label',
            'type' => 'Type',
            'value' => 'Value',
            'nodeFileId' => 'Node File ID',
            'remark' => 'Remark',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNode()
    {
        return $this->hasOne(Node::className(), ['id' => 'nodeId']);
    }


}
