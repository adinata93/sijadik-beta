<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\JadwalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jadwal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'periode_dosen_pengajar') ?>

    <?= $form->field($model, 'departemen_dosen_pengajar') ?>

    <?= $form->field($model, 'nip_nidn_dosen_pengajar') ?>

    <?= $form->field($model, 'periode_mata_kuliah_pengajar') ?>

    <?php // echo $form->field($model, 'program_studi_mata_kuliah_pengajar') ?>

    <?php // echo $form->field($model, 'kategori_koefisien_program_studi_mata_kuliah_pengajar') ?>

    <?php // echo $form->field($model, 'nama_mata_kuliah_pengajar') ?>

    <?php // echo $form->field($model, 'jenis_mata_kuliah_pengajar') ?>

    <?php // echo $form->field($model, 'jadwal_start') ?>

    <?php // echo $form->field($model, 'jadwal_end') ?>

    <?php // echo $form->field($model, 'last_updated_by') ?>

    <?php // echo $form->field($model, 'last_updated_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
