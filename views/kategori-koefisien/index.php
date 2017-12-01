<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KategoriKoefisienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kategori Koefisien';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kategori-koefisien-index">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Kategori Koefisien', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'panel'=>[
            'type'=>'success',
            'before' => false,
            'after' => false,
        ],
        'panelHeadingTemplate' => '{summary}{toggleData}',
        'toggleDataContainer' => ['class' => 'btn-group-xs text-right'],
        'summaryOptions' => ['class' => 'pull-left'],
        'toggleDataOptions' => [
            'all' => [
                'icon' => 'resize-full',
                'label' => 'Show All Data',
                'class' => 'btn btn-primary',
                'title' => ''
            ],
            'page' => [
                'icon' => 'resize-small',
                'label' => 'Show Data by Pages',
                'class' => 'btn btn-primary',
                'title' => ''
            ],
        ],
        'toolbar' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'striped' => false,
        // 'pjax' => true,
        'hover' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'nama',
            'last_updated_by',
            'last_updated_time',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>
</div>
