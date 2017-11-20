<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProgramStudi */

$this->title = 'Update Program Studi: ' . $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Program Studi', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama, 'url' => ['view', 'kategori_koefisien' => $model->kategori_koefisien, 'nama' => $model->nama]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="program-studi-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
