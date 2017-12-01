<?php

namespace app\controllers;

use Yii;
use app\models\ProgramStudi;
use app\models\ProgramStudiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ProgramStudiController implements the CRUD actions for ProgramStudi model.
 */
class ProgramStudiController extends Controller
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
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback'=>function(){
                            return (
                                (Yii::$app->user->identity->role=='Manajer Pendidikan') ||
                                (Yii::$app->user->identity->role=='Manajer Umum') ||
                                (Yii::$app->user->identity->role=='KPS Profesi') ||
                                (Yii::$app->user->identity->role=='KPS S1') ||
                                (Yii::$app->user->identity->role=='KPS S2 IKGD') ||
                                (Yii::$app->user->identity->role=='KPS S2 IKGK') ||
                                (Yii::$app->user->identity->role=='KPS S3') ||
                                (Yii::$app->user->identity->role=='KPS Sp BM') ||
                                (Yii::$app->user->identity->role=='KPS Sp IKGA') ||
                                (Yii::$app->user->identity->role=='KPS Sp IPM') ||
                                (Yii::$app->user->identity->role=='KPS Sp Konservasi') ||
                                (Yii::$app->user->identity->role=='KPS Sp Orto') ||
                                (Yii::$app->user->identity->role=='KPS Sp Perio') ||
                                (Yii::$app->user->identity->role=='KPS Sp Prosto')
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
     * Lists all ProgramStudi models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProgramStudiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProgramStudi model.
     * @param string $kategori_koefisien
     * @param string $nama
     * @return mixed
     */
    public function actionView($kategori_koefisien, $nama)
    {
        return $this->render('view', [
            'model' => $this->findModel($kategori_koefisien, $nama),
        ]);
    }

    /**
     * Creates a new ProgramStudi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProgramStudi();

        if ($model->load(Yii::$app->request->post())) {
            $ps = ProgramStudi::find()
                ->where(
                    ['nama' => $model->nama]
                )
            ->one();

            if ($ps != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL CREATE</b> <br>Program Studi dengan <i> Nama: ' . $ps->nama . '</i> sudah ada');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama</i> yang berbeda untuk menambahkan program studi baru');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'kategori_koefisien' => $model->kategori_koefisien, 'nama' => $model->nama]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProgramStudi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $kategori_koefisien
     * @param string $nama
     * @return mixed
     */
    public function actionUpdate($kategori_koefisien, $nama)
    {
        $model = $this->findModel($kategori_koefisien, $nama);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $ps = ProgramStudi::find()
                ->where(
                    ['and',
                        ['!=', 'nama', $nama],
                        ['nama' => $model->nama]
                    ]
                )
            ->one();

            if ($ps != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL UPDATE</b> <br>Program Studi dengan <i> Nama: ' . $ps->nama . '</i> sudah ada');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama</i> yang berbeda untuk melakukan update program studi');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'kategori_koefisien' => $model->kategori_koefisien, 'nama' => $model->nama]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProgramStudi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $kategori_koefisien
     * @param string $nama
     * @return mixed
     */
    public function actionDelete($kategori_koefisien, $nama)
    {
        $this->findModel($kategori_koefisien, $nama)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProgramStudi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $kategori_koefisien
     * @param string $nama
     * @return ProgramStudi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($kategori_koefisien, $nama)
    {
        if (($model = ProgramStudi::findOne(['kategori_koefisien' => $kategori_koefisien, 'nama' => $nama])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
