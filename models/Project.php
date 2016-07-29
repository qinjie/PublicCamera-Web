<?php

namespace app\models;

use app\components\MyActiveRecord;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "project".
 *
 * @property integer $id
 * @property string $label
 * @property string $remark
 * @property string $serial
 * @property integer $status
 * @property integer $userId
 * @property string $created
 * @property string $modified
 *
 * @property Floor[] $floors
 * @property Node[] $nodes
 * @property User $owner
 * @property ProjectSettings[] $projectsettings
 * @property ProjectUser[] $projectusers
 */
class Project extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
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
                'createdByAttribute' => 'userId',
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
            [['status', 'userId'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['label'], 'string', 'max' => 100],
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
            'serial' => 'Serial',
            'status' => 'Status',
            'userId' => 'Creator ID',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFloors()
    {
        return $this->hasMany(Floor::className(), ['projectId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveFloors()
    {
        return $this->hasMany(Floor::className(), ['projectId' => 'id', 'status' => 1]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNodes()
    {
        return $this->hasMany(Node::className(), ['projectId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectUsers()
    {
        return $this->hasMany(ProjectUser::className(), ['projectId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'userId'])->via('projectUsers');
    }

    public function getLatestNodeFiles()
    {
        $sql = "SELECT n1.*
            FROM nodefile AS n1
            LEFT JOIN nodefile AS n2
              ON (n1.nodeId = n2.nodeId AND n1.id < n2.id)
            LEFT JOIN node AS n
              ON (n1.nodeId = n.id)
            WHERE n2.nodeId IS NULL AND n.projectId = :projectId";

        $nodeFiles = NodeFile::findBySql($sql, ['projectId' => $this->id])->all();

        return $nodeFiles;
    }

    public function getProjectSettings()
    {
        return $this->hasMany(ProjectSetting::className(), ['projectId' => 'id']);
    }

}
