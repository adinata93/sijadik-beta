<?php

namespace app\controllers;

use Yii;
use app\models\Pembimbing;
use app\models\PembimbingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Dosen;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use app\models\Periode;
use yii\helpers\Url;

/**
 * PembimbingController implements the CRUD actions for Pembimbing model.
 */
class PembimbingController extends Controller
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
     * Lists all Pembimbing models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PembimbingSearch();
        # MAINTENANCE CODE
        $searchModel->periode_dosen = '2016/2017 - 1';
        # /.MAINTENANCE CODE
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pembimbing model.
     * @param string $periode_dosen
     * @param string $departemen_dosen
     * @param string $nip_nidn_dosen
     * @param string $jenis_bimbingan
     * @return mixed
     */
    public function actionView($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $jenis_bimbingan)
    {
        return $this->render('view', [
            'model' => $this->findModel($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $jenis_bimbingan),
        ]);
    }

    /**
     * Creates a new Pembimbing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pembimbing();

        if ($model->load(Yii::$app->request->post())) {
            $dos = Dosen::find()->where([
                'nip_nidn' => $model->nip_nidn_dosen,
                'periode' => $model->periode_dosen,
            ])->one();

            if ($dos == null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL CREATE</b> <br> Input <i>Nama Dosen</i> yang diberikan tidak terdaftar pada <i>Periode: ' . $model->periode_dosen . '</i>');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama Dosen</i> yang sudah terdaftar pada <i>Periode</i> tertentu jika ingin menambahkan pembimbing baru');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            # AccessControl Detail
            if ((Yii::$app->user->identity->role=='Manajer Pendidikan') ||
                (Yii::$app->user->identity->role=='Manajer Umum') ||
                (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM') ||
                (Yii::$app->user->identity->role=='KPS S1') || 
                (Yii::$app->user->identity->role=='KPS S3')) {
                true;
            } else if (Yii::$app->user->identity->role=='KPS Profesi') {
                if (($dos->departemen == 'Oral Biologi') ||
                    ($dos->departemen == 'Ilmu Material Kedokteran Gigi') ||
                    ($dos->departemen == 'Ilmu Kesehatan Gigi Masyarakat Pencegahan')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
                if (($dos->departemen != 'Oral Biologi') &&
                    ($dos->departemen != 'Ilmu Material Kedokteran Gigi')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGK') {
                if ($dos->departemen != 'Ilmu Kesehatan Gigi Masyarakat Pencegahan') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp BM') {
                if ($dos->departemen != 'Ilmu Bedah Mulut') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp IPM') {
                if ($dos->departemen != 'Ilmu Penyakit Mulut') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');   
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Orto') {
                if ($dos->departemen != 'Ortodonsia') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');   
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp IKGA') {
                if ($dos->departemen != 'Ilmu Kedokteran Gigi Anak') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Perio') {
                if ($dos->departemen != 'Periodonsia') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Konservasi') {
                if ($dos->departemen != 'Ilmu Koservasi Gigi') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Prosto') {
                if ($dos->departemen != 'Prostodonsia') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            }

            $pem = Pembimbing::find()
                ->where(
                    ['and',
                        ['periode_dosen' => $model->periode_dosen],
                        ['and',
                            ['nip_nidn_dosen' => $model->nip_nidn_dosen],
                            ['jenis_bimbingan' => $model->jenis_bimbingan]
                        ]
                    ]
                )
            ->one();

            if ($pem != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL CREATE</b> <br> <i>' . $dos->nama_dosen . '</i> sudah memiliki jenis bimbingan <i>' . $pem->jenis_bimbingan . '</i> pada periode <i>' . $model->periode_dosen . '</i>');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Lakukan update <i>Jumlah Mahasiswa</i> pada data dengan filter <i>Nama Dosen: ' . $dos->nama_dosen . '</i>, <i>Jenis Bimbingan: ' . $pem->jenis_bimbingan . '</i>, dan <i>Periode: ' . $model->periode_dosen . '</i>');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            if ($model->sks_kum == null) {
                $model->sks_kum = 0;
            }

            if ($model->jenis_bimbingan == 'Skripsi') {
                $model->bkd_fte = $model->jumlah_mahasiswa * 0.2; 
            } else if ($model->jenis_bimbingan == 'Disertasi') {
                $model->bkd_fte = $model->jumlah_mahasiswa * 0.5;
            } else {
                $model->bkd_fte = $model->jumlah_mahasiswa * 0.35; 
            }

            $model->departemen_dosen = $dos->departemen;
            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');

            # Send calculation to Dosen Page
            $dos->total_sks_kum = $dos->total_sks_kum + $model->sks_kum;
            $dos->total_bkd_fte = $dos->total_bkd_fte + $model->bkd_fte;
            $dos->save();
        
            $model->save();
            return $this->redirect(['view', 'periode_dosen' => $model->periode_dosen, 'departemen_dosen' => $model->departemen_dosen, 'nip_nidn_dosen' => $model->nip_nidn_dosen, 'jenis_bimbingan' => $model->jenis_bimbingan]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Pembimbing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $periode_dosen
     * @param string $departemen_dosen
     * @param string $nip_nidn_dosen
     * @param string $jenis_bimbingan
     * @return mixed
     */
    public function actionUpdate($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $jenis_bimbingan)
    {
        $model = $this->findModel($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $jenis_bimbingan);

        $per = Periode::find()
            ->where(
                ['nama' => $periode_dosen]
            )
        ->one();

        if ($per->is_locked == 'Locked') {
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }

        # AccessControl Detail
        if ((Yii::$app->user->identity->role=='Manajer Pendidikan') ||
            (Yii::$app->user->identity->role=='Manajer Umum') ||
            (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM') ||
            (Yii::$app->user->identity->role=='KPS S1') || 
            (Yii::$app->user->identity->role=='KPS S3')) {
            true;
        } else if (Yii::$app->user->identity->role=='KPS Profesi') {
            if (($departemen_dosen == 'Oral Biologi') ||
                ($departemen_dosen == 'Ilmu Material Kedokteran Gigi') ||
                ($departemen_dosen == 'Ilmu Kesehatan Gigi Masyarakat Pencegahan')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
            if (($departemen_dosen != 'Oral Biologi') &&
                ($departemen_dosen != 'Ilmu Material Kedokteran Gigi')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS S2 IKGK') {
            if ($departemen_dosen != 'Ilmu Kesehatan Gigi Masyarakat Pencegahan') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp BM') {
            if ($departemen_dosen != 'Ilmu Bedah Mulut') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp IPM') {
            if ($departemen_dosen != 'Ilmu Penyakit Mulut') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');   
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Orto') {
            if ($departemen_dosen != 'Ortodonsia') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');   
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp IKGA') {
            if ($departemen_dosen != 'Ilmu Kedokteran Gigi Anak') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Perio') {
            if ($departemen_dosen != 'Periodonsia') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Konservasi') {
            if ($departemen_dosen != 'Ilmu Koservasi Gigi') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Prosto') {
            if ($departemen_dosen != 'Prostodonsia') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $dos = Dosen::find()->where([
                'nip_nidn' => $model->nip_nidn_dosen,
                'periode' => $model->periode_dosen,
            ])->one();

            if ($dos == null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL UPDATE</b> <br> Input <i>Nama Dosen</i> yang diberikan belum terdaftar pada <i>Periode: ' . $model->periode_dosen . '</i>');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama Dosen</i> yang sudah terdaftar pada <i>Periode</i> tertentu jika ingin melakukan update pembimbing');
                return $this->redirect(Url::current());
            }

            # AccessControl Detail
            if ((Yii::$app->user->identity->role=='Manajer Pendidikan') ||
                (Yii::$app->user->identity->role=='Manajer Umum') ||
                (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM') ||
                (Yii::$app->user->identity->role=='KPS S1') || 
                (Yii::$app->user->identity->role=='KPS S3')) {
                true;
            } else if (Yii::$app->user->identity->role=='KPS Profesi') {
                if (($dos->departemen == 'Oral Biologi') ||
                    ($dos->departemen == 'Ilmu Material Kedokteran Gigi') ||
                    ($dos->departemen == 'Ilmu Kesehatan Gigi Masyarakat Pencegahan')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
                if (($dos->departemen != 'Oral Biologi') &&
                    ($dos->departemen != 'Ilmu Material Kedokteran Gigi')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else {
                if ($dos->departemen != $model->departemen_dosen) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            }

            $pem = Pembimbing::find()
                ->where(
                    ['and',
                        ['or',
                            ['!=', 'periode_dosen', $periode_dosen],
                            ['or',
                                ['!=', 'nip_nidn_dosen', $nip_nidn_dosen],
                                ['!=', 'jenis_bimbingan', $jenis_bimbingan]
                            ]
                        ],
                        ['and',
                            ['periode_dosen' => $model->periode_dosen],
                            ['and',
                                ['nip_nidn_dosen' => $model->nip_nidn_dosen],
                                ['jenis_bimbingan' => $model->jenis_bimbingan]
                            ]
                        ]
                    ]
                )
            ->one();

            if ($pem != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL UPDATE</b> <br> <i>' . $dos->nama_dosen . '</i> sudah memiliki jenis bimbingan <i>' . $pem->jenis_bimbingan . '</i> pada periode <i>' . $model->periode_dosen . '</i>');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Lakukan update <i>Jumlah Mahasiswa</i> pada data dengan filter <i>Nama Dosen: ' . $dos->nama_dosen . '</i>, <i>Jenis Bimbingan: ' . $pem->jenis_bimbingan . '</i>, dan <i>Periode: ' . $model->periode_dosen . '</i>');
                return $this->redirect(Url::current());
            }

            if ($model->sks_kum == null) {
                $model->sks_kum = 0;
            }

            if ($model->jenis_bimbingan == 'Skripsi') {
                $model->bkd_fte = $model->jumlah_mahasiswa * 0.2; 
            } else if ($model->jenis_bimbingan == 'Disertasi') {
                $model->bkd_fte = $model->jumlah_mahasiswa * 0.5;
            } else {
                $model->bkd_fte = $model->jumlah_mahasiswa * 0.35; 
            }
            
            $model->departemen_dosen = $dos->departemen;
            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');

            $odl_pem = Pembimbing::find()
                ->where(
                    ['and',
                        ['periode_dosen' => $periode_dosen],
                        ['and',
                            ['nip_nidn_dosen' => $nip_nidn_dosen],
                            ['jenis_bimbingan' => $jenis_bimbingan]
                        ]
                    ]
                )
            ->one();

            $old_dos = Dosen::find()->where([
                'nip_nidn' => $nip_nidn_dosen,
                'periode' => $periode_dosen,
            ])->one();

            # Send calculation to Dosen Page
            if ($old_dos->nip_nidn != $model->nip_nidn_dosen) {
                $old_dos->total_sks_kum = $old_dos->total_sks_kum - $odl_pem->sks_kum;
                $old_dos->total_bkd_fte = $old_dos->total_bkd_fte - $odl_pem->bkd_fte;
                $dos->total_sks_kum = $dos->total_sks_kum + $model->sks_kum;
                $dos->total_bkd_fte = $dos->total_bkd_fte + $model->bkd_fte;
                $old_dos->save();
            } else {
                $dos->total_sks_kum = $dos->total_sks_kum + $model->sks_kum - $odl_pem->sks_kum;
                $dos->total_bkd_fte = $dos->total_bkd_fte + $model->bkd_fte - $odl_pem->bkd_fte;
            }
            $dos->save();

            $model->save();
            return $this->redirect(['view', 'periode_dosen' => $model->periode_dosen, 'departemen_dosen' => $model->departemen_dosen, 'nip_nidn_dosen' => $model->nip_nidn_dosen, 'jenis_bimbingan' => $model->jenis_bimbingan]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Pembimbing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $periode_dosen
     * @param string $departemen_dosen
     * @param string $nip_nidn_dosen
     * @param string $jenis_bimbingan
     * @return mixed
     */
    public function actionDelete($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $jenis_bimbingan)
    {
        $per = Periode::find()
            ->where(
                ['nama' => $periode_dosen]
            )
        ->one();

        if ($per->is_locked == 'Locked') {
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }
        
        # AccessControl Detail
        if ((Yii::$app->user->identity->role=='Manajer Pendidikan') ||
            (Yii::$app->user->identity->role=='Manajer Umum') ||
            (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM') ||
            (Yii::$app->user->identity->role=='KPS S1') || 
            (Yii::$app->user->identity->role=='KPS S3')) {
            true;
        } else if (Yii::$app->user->identity->role=='KPS Profesi') {
            if (($departemen_dosen == 'Oral Biologi') ||
                ($departemen_dosen == 'Ilmu Material Kedokteran Gigi') ||
                ($departemen_dosen == 'Ilmu Kesehatan Gigi Masyarakat Pencegahan')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
            if (($departemen_dosen != 'Oral Biologi') &&
                ($departemen_dosen != 'Ilmu Material Kedokteran Gigi')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS S2 IKGK') {
            if ($departemen_dosen != 'Ilmu Kesehatan Gigi Masyarakat Pencegahan') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp BM') {
            if ($departemen_dosen != 'Ilmu Bedah Mulut') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp IPM') {
            if ($departemen_dosen != 'Ilmu Penyakit Mulut') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');   
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Orto') {
            if ($departemen_dosen != 'Ortodonsia') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');   
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp IKGA') {
            if ($departemen_dosen != 'Ilmu Kedokteran Gigi Anak') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Perio') {
            if ($departemen_dosen != 'Periodonsia') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Konservasi') {
            if ($departemen_dosen != 'Ilmu Koservasi Gigi') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Prosto') {
            if ($departemen_dosen != 'Prostodonsia') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        }
        
        $model = $this->findModel($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $jenis_bimbingan);
        $dos = Dosen::find()->where([
            'nip_nidn' => $model->nip_nidn_dosen,
            'periode' => $model->periode_dosen,
        ])->one();
        
        # Send calculation to Dosen Page
        $dos->total_sks_kum = $dos->total_sks_kum - $model->sks_kum;
        $dos->total_bkd_fte = $dos->total_bkd_fte - $model->bkd_fte;
        $dos->save();
    
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Pembimbing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $periode_dosen
     * @param string $departemen_dosen
     * @param string $nip_nidn_dosen
     * @param string $jenis_bimbingan
     * @return Pembimbing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $jenis_bimbingan)
    {
        if (($model = Pembimbing::findOne(['periode_dosen' => $periode_dosen, 'departemen_dosen' => $departemen_dosen, 'nip_nidn_dosen' => $nip_nidn_dosen, 'jenis_bimbingan' => $jenis_bimbingan])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
