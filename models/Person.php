<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "person".
 *
 * @property integer $id
 * @property string $firstName
 * @property string $lastName
 * @property string $parentId
 * @property string $countryId
 * @property string $created
 * @property string $modified
 *
 * @property Country $country
 */
class Person extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstName', 'lastName'], 'required'],
            [['parentId', 'countryId'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['firstName', 'lastName'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'parentId' => 'Parent ID',
            'countryId' => 'Country ID',
            'created' => 'Created',
            'modified' => 'Modified',
            /* Custom attribute labels */
            'fullName' => Yii::t('app', 'Full Name')
        ];
    }

    /* Getter for person full name */
    public function getFullName() {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'countryId']);
    }
}
