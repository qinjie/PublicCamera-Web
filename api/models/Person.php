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
class Person extends \app\models\Person implements Linkable
{

    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }

    public function extraFields()
    {
        return ['country'];
    }

    /**
     * Returns a list of links.
     */
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['person/view', 'id' => $this->id], true),
            'country' => Url::to(['country/view', 'id' => $this->countryId], true),
        ];
    }
}
