<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div class="login-box-body">
    <h3 class="text-center bg-success" style="margin-top: 0">Reset password</h3>

    <p class="login-box-msg">Please choose your new password</p>

    <?php $form = ActiveForm::begin([
        'id' => 'reset-password-form'
    ]); ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-block']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
