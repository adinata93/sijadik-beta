<?php

namespace app\controllers;

use Yii;
use app\models\Periode;
use app\models\PeriodeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * PeriodeController implements the CRUD actions for Periode model.
 */
class PeriodeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'login',
                        ],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback'=>function(){
                            return (
                                (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM') 
                            );
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback'=>function(){
                            return (
                                (Yii::$app->user->identity->role=='Manajer Pendidikan') ||
                                (Yii::$app->user->identity->role=='Manajer Umum')
                            );
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback'=>function(){
                            return (
                                (Yii::$app->user->identity->role=='KPS Profesi') ||
                                (Yii::$app->user->identity->role=='KPS S1') ||
                                (Yii::$app->user->identity->role=='KPS S2 IKGD') ||
                                (Yii::$app->user->identity->role=='KPS S2 IKGK') ||
                                (Yii::$app->user->identity->role=='KPS S3') ||
                                (Yii::$app->user->identity->role=='KPS Sp BM') ||
                                (Yii::$app->user->identity->role=='KPS Sp IKGA') ||
                                (Yii::$app->user->identity->role=='KPS Sp IPM') ||
                                (Yii::$app->user->identity->role=='KPS Konservasi') ||
                                (Yii::$app->user->identity->role=='KPS Orto') ||
                                (Yii::$app->user->identity->role=='KPS Perio') ||
                                (Yii::$app->user->identity->role=='KPS Prosto')
                            );
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Periode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PeriodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Periode model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Periode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Periode();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->is_locked == 'Locked') {
                if (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM') {
                    true;
                } else {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            }
            
            $per = Periode::find()
                ->where(
                    ['nama' => $model->nama]
                )
            ->one();

            if ($per != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL CREATE</b> <br>Peride dengan <i> Nama: ' . $per->nama . '</i> sudah ada');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama</i> yang berbeda untuk menambahkan periode baru');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'id' => $model->nama]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Periode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $per = Periode::find()->where(['nama' => $id])->one();

            if ($model->is_locked != $per->is_locked) {
                if (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM') {
                    true;
                } else {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            }
            
            $per = Periode::find()
                ->where(
                    ['and',
                        ['!=', 'nama', $id],
                        ['nama' => $model->nama]
                    ]
                )
            ->one();

            if ($per != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL UPDATE</b> <br>Peride dengan <i> Nama: ' . $per->nama . '</i> sudah ada');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama</i> yang berbeda untuk menambahkan periode baru');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'id' => $model->nama]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Periode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Periode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Periode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Periode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
