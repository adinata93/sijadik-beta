<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\KategoriKoefisien;

/* @var $this yii\web\View */
/* @var $model app\models\ProgramStudi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="program-studi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kategori_koefisien')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(KategoriKoefisien::find()->all(),'nama','nama'),
        'options' => ['placeholder' => 'Search kategori koefisien'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
