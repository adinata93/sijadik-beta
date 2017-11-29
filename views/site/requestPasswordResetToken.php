<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div class="login-box-body">
    <h3 class="text-center bg-success" style="margin-top: 0">Request password reset</h3>

    <p class="login-box-msg">Please fill out your email. A link to reset password will be sent there.</p>

    <?php $form = ActiveForm::begin([
        'id' => 'request-password-reset-form'
    ]); ?>

        <?= $form->field($model, 'email')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Send', ['class' => 'btn btn-success btn-block']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
