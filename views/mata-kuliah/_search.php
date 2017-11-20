<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MataKuliahSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mata-kuliah-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'periode') ?>

    <?= $form->field($model, 'fakultas_unit_pengajaran') ?>

    <?= $form->field($model, 'kode_organisasi') ?>

    <?= $form->field($model, 'program_studi') ?>

    <?= $form->field($model, 'jenjang') ?>

    <?php // echo $form->field($model, 'program') ?>

    <?php // echo $form->field($model, 'kategori_koefisien_program_studi') ?>

    <?php // echo $form->field($model, 'nama') ?>

    <?php // echo $form->field($model, 'jenis') ?>

    <?php // echo $form->field($model, 'kode_kelas') ?>

    <?php // echo $form->field($model, 'jenis_kelas') ?>

    <?php // echo $form->field($model, 'last_updated_by') ?>

    <?php // echo $form->field($model, 'last_updated_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
