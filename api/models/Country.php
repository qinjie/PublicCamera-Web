<?php

namespace app\api\models;

use Yii;
use yii\helpers\Url;
use yii\web\Link;
use yii\web\Linkable;

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
class Country extends \app\models\Country implements Linkable
{

    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }

    public function extraFields()
    {
        return ['user', 'persons'];
    }

    /**
     * Returns a list of links.
     */
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['country/view', 'id' => $this->id], true),
            'owner' => Url::to(['user/view', 'id' => $this->userId], true),
        ];
    }

    public function beforeSave($insert)
    {
        // Use current login user's ID if userId is null
        if ($this->userId == null) {
            $this->userId = Yii::$app->user->identity->getId();
        }
        return parent::beforeSave($insert);
    }

}
