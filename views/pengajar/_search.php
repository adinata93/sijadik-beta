<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PengajarSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pengajar-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'periode_dosen') ?>

    <?= $form->field($model, 'departemen_dosen') ?>

    <?= $form->field($model, 'nip_nidn_dosen') ?>

    <?= $form->field($model, 'periode_mata_kuliah') ?>

    <?= $form->field($model, 'program_studi_mata_kuliah') ?>

    <?php // echo $form->field($model, 'kategori_koefisien_program_studi_mata_kuliah') ?>

    <?php // echo $form->field($model, 'nama_mata_kuliah') ?>

    <?php // echo $form->field($model, 'jenis_mata_kuliah') ?>

    <?php // echo $form->field($model, 'skenario') ?>

    <?php // echo $form->field($model, 'sks_ekivalen') ?>

    <?php // echo $form->field($model, 'sks_kum') ?>

    <?php // echo $form->field($model, 'bkd_fte') ?>

    <?php // echo $form->field($model, 'last_updated_by') ?>

    <?php // echo $form->field($model, 'last_updated_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
