<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Jadwal */

$this->title = 'Create Jadwal';
$this->params['breadcrumbs'][] = ['label' => 'Jadwal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-create">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
