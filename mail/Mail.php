<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/27
 * Time: 19:47
 */

namespace ricefox\mail;
use Yii;
class Mail extends \yii\base\Component
{
    public $views='@ricefox/mail/views';
    public $layouts='@ricefox/mail/views/layouts';
    public $mailer='mailer';
    public $passwordResetView='passwordResetToken';
    public $passwordResetFrom='tiehuoban@163.com';

    /**
     * @param $user \ricefox\user\models\User
     * @param array $params
     * @return bool
     */
    public function sendPasswordReset($user,$params=[])
    {
        $view=$this->views.'/'.$this->passwordResetView;
        $state=$this->getMailer()->compose(['html'=>$view],['user'=>$user,'params'=>$params])
            ->setFrom($this->passwordResetFrom)
            ->setTo($user->email)
            ->setSubject('密码重置 '.Yii::$app->name)
            ->send();
        return $state;
    }
    public function sendSignup()
    {

    }

    /**
     * @return \yii\mail\MailerInterface
     */
    public function getMailer()
    {
        /** @var \yii\swiftmailer\Mailer $mailer */
        $mailer=$this->mailer;
        $mailer=Yii::$app->$mailer;
        $mailer->htmlLayout=$this->layouts.'/html';
        return $mailer;
    }
}