<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div class="login-box-body">
    <h3 class="text-center bg-success" style="margin-top: 0">Login</h3>

    <p class="login-box-msg">Please fill out the following fields to login</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form'
    ]); ?>

        <?= $form->field($model, 'username')->textInput() ?>

        <?= $form->field($model, 'password')->passwordInput() ?>
                
        <?= $form->field($model, 'rememberMe')->checkbox() ?>

        <div class="form-group">
            <?= Html::submitButton('Login', ['class' => 'btn btn-success btn-block', 'name' => 'login-button']) ?>
        </div>

        <div class="form-group text-center">
            If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
