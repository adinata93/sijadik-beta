<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Periode;
use app\models\Dosen;
use app\models\MataKuliah;

/* @var $this yii\web\View */
/* @var $model app\models\Pengajar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pengajar-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'periode_dosen')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Periode::find()
            ->where(['!=', 'is_locked', 'Locked'])
        ->all(),'nama','nama'),
        'hideSearch' => true,
    ]) ?>

    <?= $form->field($model, 'nip_nidn_dosen')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Dosen::find()->all(),'nip_nidn','nama_dosen'),
        'options' => ['placeholder' => 'Search nama dosen'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'nama_mata_kuliah')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(MataKuliah::find()->all(),'nama','nama'),
        'options' => ['placeholder' => 'Search nama mata kuliah'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'skenario')->textInput() ?>

    <?= $form->field($model, 'sks_ekivalen')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sks_kum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bkd_fte')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
