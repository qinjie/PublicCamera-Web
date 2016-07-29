<?php

namespace app\models;

use app\components\MyActiveRecord;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
 * @property NodeData[] $nodeDatas
 * @property NodeFile[] $nodeFiles
 * @property NodeSummary[] $nodeSummaries
 */
class Node extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'node';
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
            [['type'], 'string', 'max' => 10],
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
            'type' => 'Type',
            'status' => 'Status',
            'serial' => 'Serial',
            'projectId' => 'Project ID',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'projectId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNodeDatas()
    {
        $LIMIT = 10;
        return $this->hasMany(NodeData::className(), ['nodeId' => 'id'])
            ->orderBy(['id' => SORT_DESC])
            ->limit($LIMIT);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNodeFiles()
    {
        $LIMIT = 10;
        return $this->hasMany(NodeFile::className(), ['nodeId' => 'id'])
            ->orderBy(['id' => SORT_DESC])
            ->limit($LIMIT);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNodeSettings()
    {
        return $this->hasMany(NodeSetting::className(), ['nodeId' => 'id'])->orderBy(['id' => SORT_ASC])->limit(10);
    }

    public function getFloor()
    {
        return $this->hasOne(Floor::className(), ['id' => 'floorId']);
    }
    
    public function getLatestNodeFile()
    {
        $sql = "SELECT n1.*
            FROM nodefile AS n1
            LEFT JOIN nodefile AS n2
              ON (n1.nodeId = n2.nodeId AND n1.id < n2.id)
            WHERE n2.nodeId IS NULL AND n1.nodeId = :nodeId";

        $nodeFile = NodeFile::findBySql($sql, ['nodeId' => $this->id])->one();

        return $nodeFile;
    }

//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getNodeDatasByLabel($label, $limit = 10)
//    {
//        return $this->hasMany(NodeData::className(), ['nodeId' => 'id', 'label' => $label])
//            ->orderBy(['id' => SORT_DESC])
//            ->limit($limit);
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNodeDatasByType($type, $limit = 10)
    {
        return $this->hasMany(NodeData::className(), ['nodeId' => 'id', 'type' => $type])
            ->orderBy(['id' => SORT_DESC])
            ->limit($limit);
    }

    public function getLatestNodeDatas()
    {
        $sql = "SELECT n1.*
            FROM nodedata AS n1
            LEFT JOIN nodedata AS n2
              ON (n1.nodeId = n2.nodeId AND n1.type = n2.type AND n1.id < n2.id)
            WHERE n2.nodeId IS NULL AND n1.nodeId = :nodeId AND n1.type IS NOT NULL";

        $nodeDatas = NodeData::findBySql($sql, ['nodeId' => $this->id])->all();

        return $nodeDatas;
    }

    public function getLatestCrowdIndex()
    {
        $type = NodeData::CROWD_NOW_INT;

        $sql = 'SELECT n1.*
            FROM nodedata AS n1
            LEFT JOIN nodedata AS n2
              ON (n1.nodeId = n2.nodeId AND n1.type = n2.type AND n1.id < n2.id)
            WHERE n2.nodeId IS NULL AND n1.nodeId = :nodeId AND n1.type= :type';

        $nodeDatas = NodeData::findBySql($sql, ['nodeId' => $this->id, 'type' => $type])->one();
        return $nodeDatas;
    }
}
