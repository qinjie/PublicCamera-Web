<?php

namespace app\models;

use app\components\MyActiveRecord;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "nodesummary".
 *
 * @property string $id
 * @property string $node_id
 * @property string $label
 * @property integer $value
 * @property string $marker
 * @property string $created_at
 * @property string $modified_at
 *
 * @property Node $node
 */
class NodeSummary extends MyActiveRecord
{
    const CROWD_NOW = 'CrowdNow';
    const CROWD_NOW_INT = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nodesummary';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                // Modify only created not updated attribute
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => null,
                'updatedByAttribute' => null,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['node_id', 'value'], 'required'],
            [['node_id', 'value'], 'integer'],
            [['marker', 'created_at', 'modified_at'], 'safe'],
            [['label'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'node_id' => 'Node ID',
            'label' => 'Label',
            'value' => 'Value',
            'marker' => 'Marker',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNode()
    {
        return $this->hasOne(Node::className(), ['id' => 'node_id']);
    }

    public function beforeSave($insert)
    {
        switch ($this->type) {
            case NodeSummary::CROWD_NOW_INT:
                $this->label = NodeSummary::CROWD_NOW;
                break;
        }
        return parent::beforeSave($insert);
    }

}
