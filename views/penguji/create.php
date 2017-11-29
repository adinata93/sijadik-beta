<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Penguji */

$this->title = 'Create Penguji';
$this->params['breadcrumbs'][] = ['label' => 'Penguji', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penguji-create">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
