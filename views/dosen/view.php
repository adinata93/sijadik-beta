<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Dosen */

$this->title = $model->nama_dosen;
$this->params['breadcrumbs'][] = ['label' => 'Dosen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'periode' => $model->periode, 'departemen' => $model->departemen, 'nip_nidn' => $model->nip_nidn], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'periode' => $model->periode, 'departemen' => $model->departemen, 'nip_nidn' => $model->nip_nidn], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'periode',
            'departemen',
            'nama_dosen',
            'nip_nidn',
            'total_sks_kum',
            'total_bkd_fte',
            'last_updated_by',
            'last_updated_time',
        ],
    ]) ?>

</div>
