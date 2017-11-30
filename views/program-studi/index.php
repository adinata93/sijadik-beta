<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProgramStudiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Program Studi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="program-studi-index">

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Program Studi', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'panel'=>[
            'type'=>'success',
            'footer'=>false,
            'beforeOptions'=>['class'=>'grid_panel_remove'],
            'afterOptions'=>['class'=>'grid_panel_remove'],
        ],
        'toolbar' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'striped' => false,
        // 'pjax' => true,
        'hover' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
                'attribute'=>'kategori_koefisien',
                'group'=>true,
            ],
            'nama',
            'last_updated_by',
            'last_updated_time',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>
</div>
