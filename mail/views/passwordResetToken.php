<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user ricefox\user\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/user/passport/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>你好 <?= Html::encode($user->username) ?>,</p>
    <p>请点击下面的链接重置你的密码:</p>
    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>

</div>
