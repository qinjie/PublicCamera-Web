<?php

namespace app\models;

use app\api\models\Node;
use app\components\MyActiveRecord;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
 * @property Project $project
 * @property FloorSetting[] $floorSettings
 * @property FloorData[] $floorDatas
 * @property Node[] $nodes
 * @property FloorData[] $latestFloorData
 */
class Floor extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'floor';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                // Modify only created not updated attribute
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created', 'modified'],
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
            [['status', 'projectId'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['label'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 200],
            [['serial'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Label',
            'remark' => 'Remark',
            'status' => 'Status',
            'serial' => 'Serial',
            'projectId' => 'Project ID',
            'created' => 'Created',
            'modified' => 'Modified',
            'nodeCount' => 'Nodes',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'projectId']);
    }

    public function getNodes()
    {
        return $this->hasMany(Node::className(), ['floorId' => 'id']);
    }

    public function getFloorDatas()
    {
        return $this->hasMany(FloorData::className(), ['floorId' => 'id']);
    }

    public function getFloorSettings()
    {
        return $this->hasMany(FloorSetting::className(), ['floorId' => 'id']);
    }

    public function getNodeCount()
    {
        return $this->hasMany(Node::className(), ['floorId' => 'id'])->count();
    }

    public function getLatestCrowdIndex()
    {
        $type = FloorData::CROWD_NOW_INT;
        return $this->getLatestFloorDataByType($type);
    }

    public function getLatestFloorDataByType($type)
    {
        $sql = 'SELECT n1.id
            FROM floordata AS n1
            LEFT JOIN floordata AS n2
              ON (n1.floorId = n2.floorId AND n1.type = n2.type AND n1.id < n2.id)
            WHERE n2.id IS NULL AND n1.floorId = :floorId AND n1.type = :type';

        $connection = \Yii::$app->db;
        $cmd = $connection->createCommand($sql, ['type' => $type, 'floorId' => $this->id]);
        $id = $cmd->queryScalar();
        if ($id) {
            return FloorData::findOne($id);
        } else {
            return null;
        }
    }

    public function listLatestFloorData()
    {
        $sql = "SELECT n1.*
            FROM floordata AS n1
            LEFT JOIN floordata AS n2
              ON (n1.floorId = n2.floorId AND n1.type = n2.type AND n1.id < n2.id)
            WHERE n2.id IS NULL AND n1.floorId = :floorId AND n1.type IS NOT NULL";

        $models = FloorData::findBySql($sql, ['floorId' => $this->id])->all();

        return $models;
    }


}
