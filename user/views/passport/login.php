<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \ricefox\user\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('rf_user','Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>
                <div class="col">
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                </div>
            <div class="col" style="color:#999;margin-left:1em">
                    <?= Html::a('找回密码', ['request-password-reset']) ?>.
            </div>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('rf_user','Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
