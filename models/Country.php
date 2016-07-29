<?php

namespace app\models;

use app\components\MyActiveRecord;
use Yii;

/**
 * This is the model class for table "country".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $population
 * @property integer $userId
 * @property string $created
 * @property string $modified
 */
class Country extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['population', 'userId'], 'integer'],
            [['code'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 52]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'population' => 'Population',
            'userId' => 'Owner',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    public function getPersons()
    {
        return $this->hasMany(Person::className(), ['countryId' => 'id']);
    }
}
