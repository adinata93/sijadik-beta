<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Pembimbing */

$this->title = 'Create Pembimbing';
$this->params['breadcrumbs'][] = ['label' => 'Pembimbing', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pembimbing-create">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
