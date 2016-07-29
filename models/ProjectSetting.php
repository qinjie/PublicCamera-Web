<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "projectsetting".
 *
 * @property string $id
 * @property string $projectId
 * @property string $label
 * @property string $value
 * @property string $created
 * @property string $modified
 *
 * @property Project $project
 */
class ProjectSetting extends \yii\db\ActiveRecord
{
    const TIMING = 'Timing';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projectsetting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projectId'], 'required'],
            [['projectId'], 'integer'],
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
            'projectId' => 'Project ID',
            'label' => 'Label',
            'value' => 'Value',
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
}
