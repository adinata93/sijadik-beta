<?php

namespace app\controllers;

use Yii;
use app\models\Pengajar;
use app\models\PengajarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Dosen;
use app\models\Periode;
use app\models\MataKuliah;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * PengajarController implements the CRUD actions for Pengajar model.
 */
class PengajarController extends Controller
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
                    [ // after login
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
                    [ // after login
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
                                (Yii::$app->user->identity->role!='Tenaga Kependidikan SDM') 
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
     * Lists all Pengajar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PengajarSearch();
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
     * Displays a single Pengajar model.
     * @param string $periode_dosen
     * @param string $departemen_dosen
     * @param string $nip_nidn_dosen
     * @param string $periode_mata_kuliah
     * @param string $program_studi_mata_kuliah
     * @param string $kategori_koefisien_program_studi_mata_kuliah
     * @param string $nama_mata_kuliah
     * @param string $jenis_mata_kuliah
     * @return mixed
     */
    public function actionView($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $periode_mata_kuliah, $program_studi_mata_kuliah, $kategori_koefisien_program_studi_mata_kuliah, $nama_mata_kuliah, $jenis_mata_kuliah)
    {
        return $this->render('view', [
            'model' => $this->findModel($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $periode_mata_kuliah, $program_studi_mata_kuliah, $kategori_koefisien_program_studi_mata_kuliah, $nama_mata_kuliah, $jenis_mata_kuliah),
        ]);
    }

    /**
     * Creates a new Pengajar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pengajar();

        if ($model->load(Yii::$app->request->post())) {
            $dos = Dosen::find()->where([
                'nip_nidn' => $model->nip_nidn_dosen,
                'periode' => $model->periode_dosen,
            ])->one();

            if ($dos == null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL CREATE</b> <br> Input <i>Nama Dosen</i> yang diberikan tidak terdaftar pada <i>Periode: ' . $model->periode_dosen . '</i>');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama Dosen</i> yang sudah terdaftar pada <i>Periode</i> tertentu jika ingin menambahkan pengajar baru');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            $matkul = MataKuliah::find()->where(['nama' => $model->nama_mata_kuliah])->one();

            # AccessControl Detail
            if ((Yii::$app->user->identity->role=='Manajer Pendidikan') ||
                (Yii::$app->user->identity->role=='Manajer Umum') ||
                (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM')) {
                true;
            } else if (Yii::$app->user->identity->role=='KPS S1') {
                if ($matkul->program_studi != 'S1 Pendidikan Dokter Gigi') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S3') {
                if ($matkul->program_studi != 'S3 Ilmu Kedokteran Gigi') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Profesi') {
                if ((($dos->departemen == 'Oral Biologi') ||
                    ($dos->departemen == 'Ilmu Material Kedokteran Gigi') ||
                    ($dos->departemen == 'Ilmu Kesehatan Gigi Masyarakat Pencegahan'))
                    && 
                    ($matkul->program_studi != 'Profesi Dokter Gigi')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
                if ((($dos->departemen != 'Oral Biologi') && 
                    ($dos->departemen != 'Ilmu Material Kedokteran Gigi')) 
                    && 
                    ($matkul->program_studi != 'S2 IKGD')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGK') {
                if (($dos->departemen != 'Ilmu Kesehatan Gigi Masyarakat Pencegahan') && 
                    ($matkul->program_studi != 'S2 IKGK')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp BM') {
                if (($dos->departemen != 'Ilmu Bedah Mulut') && 
                    ($matkul->program_studi != 'Spesialis Bedah Mulut')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp IPM') {
                if (($dos->departemen != 'Ilmu Penyakit Mulut') && 
                    ($matkul->program_studi != 'Spesialis Ilmu Penyakit Mulut')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');   
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Orto') {
                if (($dos->departemen != 'Ortodonsia') && 
                    ($matkul->program_studi != 'Spesialis Ortodonsia')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');   
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp IKGA') {
                if (($dos->departemen != 'Ilmu Kedokteran Gigi Anak') && 
                    ($matkul->program_studi != 'Spesialis IKGA')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Perio') {
                if (($dos->departemen != 'Periodonsia') && 
                    ($matkul->program_studi != 'Spesialis Periodonsia')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Konservasi') {
                if (($dos->departemen != 'Ilmu Koservasi Gigi') && 
                    ($matkul->program_studi != 'Spesialis Ilmu Konservasi Gigi')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Prosto') {
                if (($dos->departemen != 'Prostodonsia') && 
                    ($matkul->program_studi != 'Spesialis Prostodonsia')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            }

            $pen = Pengajar::find()
                ->where(
                    ['and',
                        ['periode_dosen' => $model->periode_dosen],
                        ['and',
                            ['nip_nidn_dosen' => $model->nip_nidn_dosen],
                            ['nama_mata_kuliah' => $model->nama_mata_kuliah]
                        ]
                    ]
                )
            ->one();

            if ($pen != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL CREATE</b> <br> <i>' . $dos->nama_dosen . '</i> sudah menjadi pengajar pada mata kuliah <i>' . $matkul->nama . '</i> pada periode <i>' . $model->periode_dosen . '</i>');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Periode</i>, <i>Nama Dosen</i> dan <i>Nama Mata Kuliah</i> yang berbeda jika ingin menambahkan pengajar baru pada suatu mata kuliah pada periode tertentu');
                
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            if ($model->skenario != null) {
                $model->sks_ekivalen = 0;
                $model->sks_kum = $model->skenario * 0.625;
                $model->bkd_fte = $model->skenario * 0.333;
            }

            if ($model->sks_ekivalen == null) {
                $model->sks_ekivalen = 0;
            }
            if ($model->sks_kum == null) {
                $model->sks_kum = 0;
            }
            if ($model->bkd_fte == null) {
                $model->bkd_fte = 0;
            }

            $model->periode_mata_kuliah = $model->periode_dosen;
            $model->departemen_dosen = $dos->departemen;
            $model->program_studi_mata_kuliah = $matkul->program_studi;
            $model->kategori_koefisien_program_studi_mata_kuliah = $matkul->kategori_koefisien_program_studi;
            $model->jenis_mata_kuliah = $matkul->jenis;
            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');
            
            # Send calculation to Dosen Page
            $dos->total_sks_kum = $dos->total_sks_kum + $model->sks_kum;
            $dos->total_bkd_fte = $dos->total_bkd_fte + $model->bkd_fte;
            $dos->save();

            $model->save();
            return $this->redirect(['view', 'periode_dosen' => $model->periode_dosen, 'departemen_dosen' => $model->departemen_dosen, 'nip_nidn_dosen' => $model->nip_nidn_dosen, 'periode_mata_kuliah' => $model->periode_mata_kuliah, 'program_studi_mata_kuliah' => $model->program_studi_mata_kuliah, 'kategori_koefisien_program_studi_mata_kuliah' => $model->kategori_koefisien_program_studi_mata_kuliah, 'nama_mata_kuliah' => $model->nama_mata_kuliah, 'jenis_mata_kuliah' => $model->jenis_mata_kuliah]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Pengajar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $periode_dosen
     * @param string $departemen_dosen
     * @param string $nip_nidn_dosen
     * @param string $periode_mata_kuliah
     * @param string $program_studi_mata_kuliah
     * @param string $kategori_koefisien_program_studi_mata_kuliah
     * @param string $nama_mata_kuliah
     * @param string $jenis_mata_kuliah
     * @return mixed
     */
    public function actionUpdate($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $periode_mata_kuliah, $program_studi_mata_kuliah, $kategori_koefisien_program_studi_mata_kuliah, $nama_mata_kuliah, $jenis_mata_kuliah)
    {
        $model = $this->findModel($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $periode_mata_kuliah, $program_studi_mata_kuliah, $kategori_koefisien_program_studi_mata_kuliah, $nama_mata_kuliah, $jenis_mata_kuliah);

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
            (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM')) {
            true;
        } else if (Yii::$app->user->identity->role=='KPS S1') {
            if ($program_studi_mata_kuliah != 'S1 Pendidikan Dokter Gigi') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS S3') {
            if ($program_studi_mata_kuliah != 'S3 Ilmu Kedokteran Gigi') {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS Profesi') {
            if ((($departemen_dosen == 'Oral Biologi') ||
                ($departemen_dosen == 'Ilmu Material Kedokteran Gigi') ||
                ($departemen_dosen == 'Ilmu Kesehatan Gigi Masyarakat Pencegahan'))
                && 
                ($program_studi_mata_kuliah != 'Profesi Dokter Gigi')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
            if ((($departemen_dosen != 'Oral Biologi') && 
                ($departemen_dosen != 'Ilmu Material Kedokteran Gigi')) 
                && 
                ($program_studi_mata_kuliah != 'S2 IKGD')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS S2 IKGK') {
            if (($departemen_dosen != 'Ilmu Kesehatan Gigi Masyarakat Pencegahan') && 
                ($program_studi_mata_kuliah != 'S2 IKGK')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp BM') {
            if (($departemen_dosen != 'Ilmu Bedah Mulut') && 
                ($program_studi_mata_kuliah != 'Spesialis Bedah Mulut')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp IPM') {
            if (($departemen_dosen != 'Ilmu Penyakit Mulut') && 
                ($program_studi_mata_kuliah != 'Spesialis Ilmu Penyakit Mulut')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');   
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Orto') {
            if (($departemen_dosen != 'Ortodonsia') && 
                ($program_studi_mata_kuliah != 'Spesialis Ortodonsia')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');   
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp IKGA') {
            if (($departemen_dosen != 'Ilmu Kedokteran Gigi Anak') && 
                ($program_studi_mata_kuliah != 'Spesialis IKGA')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Perio') {
            if (($departemen_dosen != 'Periodonsia') && 
                ($program_studi_mata_kuliah != 'Spesialis Periodonsia')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Konservasi') {
            if (($departemen_dosen != 'Ilmu Koservasi Gigi') && 
                ($program_studi_mata_kuliah != 'Spesialis Ilmu Konservasi Gigi')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        } else if (Yii::$app->user->identity->role=='KPS Sp Prosto') {
            if (($departemen_dosen != 'Prostodonsia') && 
                ($program_studi_mata_kuliah != 'Spesialis Prostodonsia')) {
                throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $dos = Dosen::find()->where([
                'nip_nidn' => $model->nip_nidn_dosen,
                'periode' => $model->periode_dosen,
            ])->one();

            if ($dos == null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL UPDATE</b> <br> Input <i>Nama Dosen</i> yang diberikan tidak terdaftar pada <i>Periode: ' . $model->periode_dosen . '</i>');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama Dosen</i> yang sudah terdaftar pada <i>Periode</i> tertentu jika ingin malakukan update pengajar');
                return $this->redirect(Url::current());
            }

            $matkul = MataKuliah::find()->where(['nama' => $model->nama_mata_kuliah])->one();

            # AccessControl Detail
            if ((Yii::$app->user->identity->role=='Manajer Pendidikan') ||
                (Yii::$app->user->identity->role=='Manajer Umum') ||
                (Yii::$app->user->identity->role=='Tenaga Kependidikan SDM')) {
                true;
            } else if (Yii::$app->user->identity->role=='KPS S1') {
                if ($matkul->program_studi != 'S1 Pendidikan Dokter Gigi') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S3') {
                if ($matkul->program_studi != 'S3 Ilmu Kedokteran Gigi') {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Profesi') {
                if ((($dos->departemen == 'Oral Biologi') ||
                    ($dos->departemen == 'Ilmu Material Kedokteran Gigi') ||
                    ($dos->departemen == 'Ilmu Kesehatan Gigi Masyarakat Pencegahan'))
                    && 
                    ($matkul->program_studi != 'Profesi Dokter Gigi')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGD') {
                if ((($dos->departemen != 'Oral Biologi') && 
                    ($dos->departemen != 'Ilmu Material Kedokteran Gigi')) 
                    && 
                    ($matkul->program_studi != 'S2 IKGD')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS S2 IKGK') {
                if (($dos->departemen != 'Ilmu Kesehatan Gigi Masyarakat Pencegahan') && 
                    ($matkul->program_studi != 'S2 IKGK')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp BM') {
                if (($dos->departemen != 'Ilmu Bedah Mulut') && 
                    ($matkul->program_studi != 'Spesialis Bedah Mulut')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp IPM') {
                if (($dos->departemen != 'Ilmu Penyakit Mulut') && 
                    ($matkul->program_studi != 'Spesialis Ilmu Penyakit Mulut')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');   
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Orto') {
                if (($dos->departemen != 'Ortodonsia') && 
                    ($matkul->program_studi != 'Spesialis Ortodonsia')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');   
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp IKGA') {
                if (($dos->departemen != 'Ilmu Kedokteran Gigi Anak') && 
                    ($matkul->program_studi != 'Spesialis IKGA')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Perio') {
                if (($dos->departemen != 'Periodonsia') && 
                    ($matkul->program_studi != 'Spesialis Periodonsia')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Konservasi') {
                if (($dos->departemen != 'Ilmu Koservasi Gigi') && 
                    ($matkul->program_studi != 'Spesialis Ilmu Konservasi Gigi')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            } else if (Yii::$app->user->identity->role=='KPS Sp Prosto') {
                if (($dos->departemen != 'Prostodonsia') && 
                    ($matkul->program_studi != 'Spesialis Prostodonsia')) {
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');                    
                }
            }

            $pen = Pengajar::find()
                ->where(
                    ['and',
                        ['or',
                            ['!=', 'periode_dosen', $periode_dosen],
                            ['or',
                                ['!=', 'nip_nidn_dosen', $nip_nidn_dosen],
                                ['!=', 'nama_mata_kuliah', $nama_mata_kuliah]
                            ]
                        ],
                        ['and',
                            ['periode_dosen' => $model->periode_dosen],
                            ['and',
                                ['nip_nidn_dosen' => $model->nip_nidn_dosen],
                                ['nama_mata_kuliah' => $model->nama_mata_kuliah]
                            ]
                        ]
                    ]
                )
            ->one();

            if ($pen != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL UPDATE</b> <br> <i>' . $dos->nama_dosen . '</i> sudah menjadi pengajar pada mata kuliah <i>' . $matkul->nama . '</i> pada periode <i>' . $model->periode_dosen . '</i>');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Periode</i>, <i>Nama Dosen</i> dan <i>Nama Mata Kuliah</i> yang berbeda jika ingin melakukan update pengajar');
                return $this->redirect(Url::current());
            }

            if ($model->skenario != null) {
                $model->sks_ekivalen = 0;
                $model->sks_kum = $model->skenario * 0.625;
                $model->bkd_fte = $model->skenario * 0.333;
            }

            if ($model->sks_ekivalen == null) {
                $model->sks_ekivalen = 0;
            }
            if ($model->sks_kum == null) {
                $model->sks_kum = 0;
            }
            if ($model->bkd_fte == null) {
                $model->bkd_fte = 0;
            }

            $model->periode_mata_kuliah = $model->periode_dosen;
            $model->departemen_dosen = $dos->departemen;
            $model->program_studi_mata_kuliah = $matkul->program_studi;
            $model->kategori_koefisien_program_studi_mata_kuliah = $matkul->kategori_koefisien_program_studi;
            $model->jenis_mata_kuliah = $matkul->jenis;
            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');
            
            $old_pen = Pengajar::find()
                ->where(
                    ['and',
                        ['periode_dosen' => $periode_dosen],
                        ['and',
                            ['nip_nidn_dosen' => $nip_nidn_dosen],
                            ['nama_mata_kuliah' => $nama_mata_kuliah]
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
                $old_dos->total_sks_kum = $old_dos->total_sks_kum - $old_pen->sks_kum;
                $old_dos->total_bkd_fte = $old_dos->total_bkd_fte - $old_pen->bkd_fte;
                $dos->total_sks_kum = $dos->total_sks_kum + $model->sks_kum;
                $dos->total_bkd_fte = $dos->total_bkd_fte + $model->bkd_fte;
                $old_dos->save();
            } else {
                $dos->total_sks_kum = $dos->total_sks_kum + $model->sks_kum - $old_pen->sks_kum;
                $dos->total_bkd_fte = $dos->total_bkd_fte + $model->bkd_fte - $old_pen->bkd_fte;
            }
            $dos->save();

            $model->save();
            return $this->redirect(['view', 'periode_dosen' => $model->periode_dosen, 'departemen_dosen' => $model->departemen_dosen, 'nip_nidn_dosen' => $model->nip_nidn_dosen, 'periode_mata_kuliah' => $model->periode_mata_kuliah, 'program_studi_mata_kuliah' => $model->program_studi_mata_kuliah, 'kategori_koefisien_program_studi_mata_kuliah' => $model->kategori_koefisien_program_studi_mata_kuliah, 'nama_mata_kuliah' => $model->nama_mata_kuliah, 'jenis_mata_kuliah' => $model->jenis_mata_kuliah]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Pengajar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $periode_dosen
     * @param string $departemen_dosen
     * @param string $nip_nidn_dosen
     * @param string $periode_mata_kuliah
     * @param string $program_studi_mata_kuliah
     * @param string $kategori_koefisien_program_studi_mata_kuliah
     * @param string $nama_mata_kuliah
     * @param string $jenis_mata_kuliah
     * @return mixed
     */
    public function actionDelete($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $periode_mata_kuliah, $program_studi_mata_kuliah, $kategori_koefisien_program_studi_mata_kuliah, $nama_mata_kuliah, $jenis_mata_kuliah)
    {
        $per = Periode::find()
            ->where(
                ['nama' => $periode_dosen]
            )
        ->one();

        if ($per->is_locked == 'Locked') {
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }
        
        $model = $this->findModel($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $periode_mata_kuliah, $program_studi_mata_kuliah, $kategori_koefisien_program_studi_mata_kuliah, $nama_mata_kuliah, $jenis_mata_kuliah);
        
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
     * Finds the Pengajar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $periode_dosen
     * @param string $departemen_dosen
     * @param string $nip_nidn_dosen
     * @param string $periode_mata_kuliah
     * @param string $program_studi_mata_kuliah
     * @param string $kategori_koefisien_program_studi_mata_kuliah
     * @param string $nama_mata_kuliah
     * @param string $jenis_mata_kuliah
     * @return Pengajar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $periode_mata_kuliah, $program_studi_mata_kuliah, $kategori_koefisien_program_studi_mata_kuliah, $nama_mata_kuliah, $jenis_mata_kuliah)
    {
        if (($model = Pengajar::findOne(['periode_dosen' => $periode_dosen, 'departemen_dosen' => $departemen_dosen, 'nip_nidn_dosen' => $nip_nidn_dosen, 'periode_mata_kuliah' => $periode_mata_kuliah, 'program_studi_mata_kuliah' => $program_studi_mata_kuliah, 'kategori_koefisien_program_studi_mata_kuliah' => $kategori_koefisien_program_studi_mata_kuliah, 'nama_mata_kuliah' => $nama_mata_kuliah, 'jenis_mata_kuliah' => $jenis_mata_kuliah])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
