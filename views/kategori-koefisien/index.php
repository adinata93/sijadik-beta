<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KategoriKoefisienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kategori Koefisien';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kategori-koefisien-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Kategori Koefisien', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nama',
            'last_updated_by',
            'last_updated_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>