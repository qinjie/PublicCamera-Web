<?php

namespace app\models;

use app\components\MyActiveRecord;
use Yii;

/**
 * This is the model class for table "floorsetting".
 *
 * @property string $id
 * @property string $floorId
 * @property string $label
 * @property string $value
 * @property string $created
 * @property string $modified
 *
 * @property Floor $floor
 */
class FloorSetting extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'floorsetting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['floorId'], 'required'],
            [['floorId'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['label'], 'string', 'max' => 10],
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
            'floorId' => 'Floor ID',
            'label' => 'Label',
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
}
