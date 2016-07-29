<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;

/**
 * LoginForm is the model behind the login form.
 */
class RegisterForm extends Model
{
    public $username;
    public $fullName;
    public $email;
    public $phone;
    public $company;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and email are both required
            [['username', 'email'], 'required'],
            // email must be a email
            ['email', 'email'],
            ['username', 'validateUsername'],
            ['email', 'validateEmail'],
        ];
    }

    /**
     * Validates the username
     * This method serves as the inline validation for username.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (User::existsUsername($this->username)) {
                $this->addError($attribute, 'Username is not available. Try another one.');
            }
        }
    }

    /**
     * Validates the email
     * This method serves as the inline validation for email.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (User::existsEmail($this->email)) {
                $this->addError($attribute, 'This email is already registered. Proceed to ' . Html::a('login', ['/site/login']) . ' or ' . Html::a("reset password", ['/site/resetPassword']));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function register()
    {
        if ($this->validate()) {



            return true;
        } else {
            return false;
        }
    }

}
