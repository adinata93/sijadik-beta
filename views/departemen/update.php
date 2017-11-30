<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Departemen */

$this->title = 'Update Departemen: ' . $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Departemen', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama, 'url' => ['view', 'id' => $model->nama]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="departemen-update">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
