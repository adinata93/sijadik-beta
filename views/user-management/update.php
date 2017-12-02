<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserManagement */

$this->title = 'Update User: ' . $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'User Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-management-update">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
