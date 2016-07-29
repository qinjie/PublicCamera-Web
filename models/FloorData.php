<?php

namespace app\models;

use app\components\MyActiveRecord;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "floordata".
 *
 * @property string $id
 * @property string $floorId
 * @property string $label
 * @property string $type
 * @property string $marker
 * @property string $value
 * @property string $created
 * @property string $modified
 *
 * @property Floor $floor
 */
class FloorData extends MyActiveRecord
{
    const CROWD_NOW = 'CrowdNow';
    const CROWD_NOW_INT = 0;
    const CROWD_WEEKLY = 'CrowdWeekly';
    const CROWD_WEEKLY_INT = 1;
    const CROWD_MONTHLY = 'CrowdMonthly';
    const CROWD_MONTHLY_INT = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'floordata';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                // Modify only created not updated attribute
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['modified'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['modified'],
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
            [['floorId'], 'required'],
            [['floorId', 'type', 'value'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['label'], 'string', 'max' => 30],
            [['marker'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'floorId' => 'Floor ID',
            'label' => 'Label',
            'type' => 'Type',
            'marker' => 'Marker',
            'value' => 'Value',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFloor()
    {
        return $this->hasOne(Floor::className(), ['id' => 'floorId']);
    }

    public function beforeSave($insert)
    {
        switch ($this->type) {
            case FloorData::CROWD_NOW_INT:
                $this->label = FloorData::CROWD_NOW;
                break;
            case FloorData::CROWD_WEEKLY_INT:
                $this->label = FloorData::CROWD_WEEKLY;
                break;
            case FloorData::CROWD_MONTHLY_INT:
                $this->label = FloorData::CROWD_MONTHLY;
        }
        return parent::beforeSave($insert);
    }


}
