<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'User Management', 'url' => ['/user-management/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

        <?= $form->field($model, 'nip')->textInput(['maxlength' => true])->label('NIP',['class'=>'label-class']) ?>
        
        <?= $form->field($model, 'nama') ?>

        <?= $form->field($model, 'role')->widget(Select2::classname(), [
            'data' => [ 
                'Manajer Pendidikan' => 'Manajer Pendidikan',
                'Manajer Umum' => 'Manajer Umum',
                'KPS Profesi' => 'KPS Profesi',
                'KPS S1' => 'KPS S1',
                'KPS S2 IKGD' => 'KPS S2 IKGD',
                'KPS S2 IKGK' => 'KPS S2 IKGK',
                'KPS S3' => 'KPS S3',
                'KPS Sp BM' => 'KPS Sp BM',
                'KPS Sp IKGA' => 'KPS Sp IKGA',
                'KPS Sp IPM' => 'KPS Sp IPM',
                'KPS Sp Konservasi' => 'KPS Sp Konservasi',
                'KPS Sp Orto' => 'KPS Sp Orto',
                'KPS Sp Perio' => 'KPS Sp Perio',
                'KPS Sp Prosto' => 'KPS Sp Prosto',
                'Tenaga Kependidikan SDM' => 'Tenaga Kependidikan SDM',
            ],
            'options' => ['placeholder' => 'Search role'],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'theme' => Select2::THEME_DEFAULT,
        ]) ?>

        <?= $form->field($model, 'username')->textInput() ?>

        <?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Signup', ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
