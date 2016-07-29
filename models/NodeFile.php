<?php

namespace app\models;

use app\components\MyActiveRecord;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
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
 * @property integer $status
 * @property string $created
 * @property string $modified
 *
 * @property Node $node
 */
class NodeFile extends MyActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_DONE = 2;

    public function fields()
    {
        $fields = parent::fields();
        $fields[] = 'fileUrl';
        $fields[] = 'thumbnailUrl';
//        unset($fields[array_flip($fields)['created']]);
        return $fields;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nodefile';
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
            [['nodeId'], 'required'],
            [['nodeId', 'fileSize'], 'integer'],
            [['label', 'fileUrl', 'status', 'created', 'modified'], 'safe'],
            [['label'], 'string', 'max' => 20],
            [['fileName'], 'string', 'max' => 50],
            [['fileType'], 'string', 'max' => 10]
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
            'fileName' => 'File Name',
            'fileType' => 'File Type',
            'fileSize' => 'File Size',
            'status' => 'Status',
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

    public function afterDelete()
    {
        # Remove related picture and thumbnail
        $folder = \Yii::getAlias('@siteroot') . \Yii::$app->params['folder.upload.files'];
        $file = $folder . $this->fileName;
        $thumbnail = $folder . 'thumbnail_' . $this->fileName;
        if (file_exists($file)) {
            unlink($file);
        }
        if (file_exists($thumbnail)) {
            unlink($thumbnail);
        }
        return parent::afterDelete();
    }

    public function getFileUrl()
    {
        if (!is_null($this->fileName)) {
            $app = Yii::$app->params['application'];
            $base = str_replace($app, '', Url::base(true));
            $base = $base . Yii::$app->params['folder.upload.files'];
            $url = $base . $this->fileName;

            return $url;
        }
        return null;
    }
//
//    public function getFileUrl()
//    {
//        if (!is_null($this->fileName)) {
//            $app = Yii::$app->params['application'];
//            $base = str_replace($app, '', Url::base(true));
//            $base = $base . Yii::$app->params['folder.upload.files'];
//            $url = $base . $this->fileName;
//
//            return $url;
//        }
//        return null;
//    }

    public function getThumbnailUrl()
    {
        if (!is_null($this->fileName)) {
            # Check if its an image file
            if (preg_match('#^image#', $this->fileType) == 1) {
                $app = Yii::$app->params['application'];
                $base = str_replace($app, '', Url::base(true));
                $base = $base . Yii::$app->params['folder.upload.files'];
                $url = $base . 'thumbnail_' . $this->fileName;
                return $url;
            }
        }
        return null;
    }


//    public function getThumbnailUrl()
//    {
//        if (!is_null($this->fileName)) {
//            # Check if its an image file
//            if (preg_match('#^image#', $this->fileType) == 1) {
//                $app = Yii::$app->params['application'];
//                $base = str_replace($app, '', Url::base(true));
//                $base = $base . Yii::$app->params['folder.upload.files'];
//                $url = $base . 'thumbnail_' . $this->fileName;
//                return $url;
//            }
//        }
//        return null;
//    }

    public function getImageNotAvailableUrl()
    {
        $name = '_images_not_available.jpg';
        $app = Yii::$app->params['application'];
        $base = str_replace($app, '', Url::base(true));
        $base = $base . Yii::$app->params['folder.upload'];
        $url = $base . $name;
        return $url;
    }

}