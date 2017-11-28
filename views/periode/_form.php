<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Periode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="periode-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama')->widget(MaskedInput::classname(),[
        'mask' => '2099/2099 - 9'
    ]) ?>

    <?= $form->field($model, 'is_locked')->widget(Select2::classname(), [
        'data' => [ 'Unlocked' => 'Unlocked', 'Locked' => 'Locked', ],
        'hideSearch' => true,
        'theme' => Select2::THEME_DEFAULT,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
