<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Departemen;
use app\models\Periode;

/* @var $this yii\web\View */
/* @var $model app\models\Dosen */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dosen-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'periode')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Periode::find()
            ->where(['!=', 'is_locked', 'Locked'])
        ->all(),'nama','nama'),
        'hideSearch' => true,
        'theme' => Select2::THEME_DEFAULT,
    ]) ?>

    <?= $form->field($model, 'departemen')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Departemen::find()->all(),'nama','nama'),
        'options' => ['placeholder' => 'Search departemen'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'theme' => Select2::THEME_DEFAULT,
    ]) ?>

    <?= $form->field($model, 'nama_dosen')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nip_nidn')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
