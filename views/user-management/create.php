<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\UserManagement */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'User Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-management-create">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

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

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_hash')->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
