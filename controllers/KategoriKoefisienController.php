<?php

namespace app\controllers;

use Yii;
use app\models\KategoriKoefisien;
use app\models\KategoriKoefisienSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * KategoriKoefisienController implements the CRUD actions for KategoriKoefisien model.
 */
class KategoriKoefisienController extends Controller
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
     * Lists all KategoriKoefisien models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KategoriKoefisienSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KategoriKoefisien model.
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
     * Creates a new KategoriKoefisien model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KategoriKoefisien();

        if ($model->load(Yii::$app->request->post())) {

            $kk = KategoriKoefisien::find()
                ->where(
                    ['nama' => $model->nama]
                )
            ->one();

            if ($kk != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL CREATE</b> <br>Kategori Koefisien dengan <i> Nama: ' . $kk->nama . '</i> sudah ada');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama</i> yang berbeda untuk menambahkan kategori koefisien baru');
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
     * Updates an existing KategoriKoefisien model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            $kk = KategoriKoefisien::find()
                ->where(
                    ['and',
                        ['!=', 'nama', $id],
                        ['nama' => $model->nama]
                    ]
                )
            ->one();

            if ($kk != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL UPDATE</b> <br>Kategori Koefisien dengan <i> Nama: ' . $kk->nama . '</i> sudah ada');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama</i> yang berbeda untuk menambahkan kategori koefisien baru');
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
     * Deletes an existing KategoriKoefisien model.
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
     * Finds the KategoriKoefisien model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return KategoriKoefisien the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KategoriKoefisien::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
