<?php
namespace ricefox\user\models;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\ricefox\user\models\User', 'message' => Yii::t('rf_user','This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\ricefox\user\models\User', 'message' => Yii::t('rf_user','This email address has already been taken.')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateAccessToken();
        $return=$user->insert();
        //$auth=Yii::$app->authManager;
        //$role=$auth->getRole('author');
        //$auth->assign($role,$user->getId());
        return $return ? $user : null;
    }
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'email'=>'邮箱'
        ];
    }
}
