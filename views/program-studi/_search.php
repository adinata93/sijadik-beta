<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProgramStudiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="program-studi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'kategori_koefisien') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'last_updated_by') ?>

    <?= $form->field($model, 'last_updated_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
