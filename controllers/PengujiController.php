<?php

namespace app\controllers;

use Yii;
use app\models\Penguji;
use app\models\PengujiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Dosen;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use app\models\Periode;
use yii\helpers\Url;

/**
 * PengujiController implements the CRUD actions for Penguji model.
 */
class PengujiController extends Controller
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
     * Lists all Penguji models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PengujiSearch();
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
     * Displays a single Penguji model.
     * @param string $periode_dosen
     * @param string $departemen_dosen
     * @param string $nip_nidn_dosen
     * @param string $jenis_ujian
     * @param string $peran
     * @return mixed
     */
    public function actionView($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $jenis_ujian, $peran)
    {
        return $this->render('view', [
            'model' => $this->findModel($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $jenis_ujian, $peran),
        ]);
    }

    /**
     * Creates a new Penguji model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Penguji();

        if ($model->load(Yii::$app->request->post())) {
            $dos = Dosen::find()->where([
                'nip_nidn' => $model->nip_nidn_dosen,
                'periode' => $model->periode_dosen,
            ])->one();

            if ($dos == null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL CREATE</b> <br> Input <i>Nama Dosen</i> yang diberikan belum terdaftar pada <i>Periode: ' . $model->periode_dosen . '</i>');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama Dosen</i> yang sudah terdaftar pada <i>Periode</i> tertentu jika ingin menambahkan penguji baru');
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

            $pen = Penguji::find()
                ->where(
                    ['and',
                        ['and',
                            ['periode_dosen' => $model->periode_dosen],
                            ['nip_nidn_dosen' => $model->nip_nidn_dosen]
                        ],
                        ['and',
                            ['jenis_ujian' => $model->jenis_ujian],
                            ['peran' => $model->peran]
                        ]
                    ]
                )
            ->one();

            if ($pen != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL CREATE</b> <br> <i>' . $dos->nama_dosen . '</i> sudah memiliki jenis ujian <i>' . $pen->jenis_ujian . '</i> dengan peran <i>' . $pen->peran . '</i> pada periode <i>' . $model->periode_dosen . '</i>');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Lakukan update <i>Jumlah Mahasiswa</i> pada data dengan filter <i>Nama Dosen: ' . $dos->nama_dosen . '</i>, <i>Jenis Ujian: ' . $pen->jenis_ujian . '</i>, <i> Peran: ' . $pen->peran . '</i>, dan <i>Periode: ' . $model->periode_dosen . '</i>');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            // Validation of Jenis Ujian and Peran
            if (
                ($model->jenis_ujian=='S1 - Ujian Karya Ilmiah' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji' || $model->peran=='Pembimbing 1' || $model->peran=='Pembimbing 2')) || 
                ($model->jenis_ujian=='Profesi - Ujian UDG' && ($model->peran=='Ketua Penguji' || 'Anggota Penguji')) ||
                ($model->jenis_ujian=='Profesi - Ujian Komprehensif' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji')) ||
                ($model->jenis_ujian=='Spesialis - Ujian Laporan Penelitian' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji' || $model->peran=='Pembimbing 1' || $model->peran=='Pembimbing 2')) ||
                ($model->jenis_ujian=='Spesialis - Ujian Komprehensif' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji')) ||
                ($model->jenis_ujian=='S2 - Ujian Proposal Penelitian' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji')) ||
                ($model->jenis_ujian=='S2 - Ujian Tesis' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji' || $model->peran=='Pembimbing 1' || $model->peran=='Pembimbing 2')) ||
                ($model->jenis_ujian=='S3 - Ujian Proposal' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji' || $model->peran=='Pembimbing Akademik')) ||
                ($model->jenis_ujian=='S3 - Ujian Seminar Hasil' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji')) ||
                ($model->jenis_ujian=='S3 - Ujian Pra Promosi' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji')) ||
                ($model->jenis_ujian=='S3 - Ujian Promosi' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji' || $model->peran=='Promotor' || $model->peran=='Ko-Promotor'))
            ) {
                true;
            } else {
                Yii::$app->session->setFlash('danger', '<b>GAGAL CREATE</b> <br> <i>Jenis Ujian: ' . $model->jenis_ujian . '</i> dengan <i>Peran: ' . $model->peran . '</i> tidak valid');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Jenis Ujian</i> dan <i>Peran</i> yang berbeda dan valid untuk menambahkan penguji baru');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            
            if ($model->sks_kum == null) {
                $model->sks_kum = 0;
            }

            $model->departemen_dosen = $dos->departemen;
            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');

            // Send calculation to Dosen Page
            $dos->total_sks_kum = $dos->total_sks_kum + $model->sks_kum;
            $dos->save();

            $model->save();
            return $this->redirect(['view', 'periode_dosen' => $model->periode_dosen, 'departemen_dosen' => $model->departemen_dosen, 'nip_nidn_dosen' => $model->nip_nidn_dosen, 'jenis_ujian' => $model->jenis_ujian, 'peran' => $model->peran]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Penguji model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $periode_dosen
     * @param string $departemen_dosen
     * @param string $nip_nidn_dosen
     * @param string $jenis_ujian
     * @param string $peran
     * @return mixed
     */
    public function actionUpdate($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $jenis_ujian, $peran)
    {
        $model = $this->findModel($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $jenis_ujian, $peran);

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
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Nama Dosen</i> yang sudah terdaftar pada <i>Periode</i> tertentu jika ingin melakukan update penguji');
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
                if (($model->departemen == 'Oral Biologi') ||
                    ($model->departemen == 'Ilmu Material Kedokteran Gigi') ||
                    ($model->departemen == 'Ilmu Kesehatan Gigi Masyarakat Pencegahan')) {
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

            $pen = Penguji::find()
                ->where(
                    ['and',
                        ['or',
                            ['or',
                                ['!=', 'periode_dosen', $periode_dosen],
                                ['!=', 'nip_nidn_dosen', $nip_nidn_dosen]
                            ],
                            ['or',
                                ['!=', 'jenis_ujian', $jenis_ujian],
                                ['!=', 'peran', $peran]
                            ]
                        ],
                        ['and',
                            ['and',
                                ['periode_dosen' => $model->periode_dosen],
                                ['nip_nidn_dosen' => $model->nip_nidn_dosen]
                            ],
                            ['and',
                                ['jenis_ujian' => $model->jenis_ujian],
                                ['peran' => $model->peran]
                            ]
                        ]
                    ]
                )
            ->one();

            if ($pen != null) {
                Yii::$app->session->setFlash('danger', '<b>GAGAL UPDATE</b> <br> <i>' . $dos->nama_dosen . '</i> sudah memiliki jenis ujian <i>' . $pen->jenis_ujian . '</i> dengan peran <i>' . $pen->peran . '</i> pada periode <i>' . $model->periode_dosen . '</i>');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Lakukan update <i>Jumlah Mahasiswa</i> pada data dengan filter <i>Nama Dosen: ' . $dos->nama_dosen . '</i>, <i>Jenis Ujian: ' . $pen->jenis_ujian . '</i>, <i> Peran: ' . $pen->peran . '</i>, dan <i>Periode: ' . $model->periode_dosen . '</i>');
                return $this->redirect(Url::current());
            }

            // Validation of Jenis Ujian and Peran
            if (
                ($model->jenis_ujian=='S1 - Ujian Karya Ilmiah' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji' || $model->peran=='Pembimbing 1' || $model->peran=='Pembimbing 2')) || 
                ($model->jenis_ujian=='Profesi - Ujian UDG' && ($model->peran=='Ketua Penguji' || 'Anggota Penguji')) ||
                ($model->jenis_ujian=='Profesi - Ujian Komprehensif' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji')) ||
                ($model->jenis_ujian=='Spesialis - Ujian Laporan Penelitian' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji' || $model->peran=='Pembimbing 1' || $model->peran=='Pembimbing 2')) ||
                ($model->jenis_ujian=='Spesialis - Ujian Komprehensif' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji')) ||
                ($model->jenis_ujian=='S2 - Ujian Proposal Penelitian' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji')) ||
                ($model->jenis_ujian=='S2 - Ujian Tesis' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji' || $model->peran=='Pembimbing 1' || $model->peran=='Pembimbing 2')) ||
                ($model->jenis_ujian=='S3 - Ujian Proposal' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji' || $model->peran=='Pembimbing Akademik')) ||
                ($model->jenis_ujian=='S3 - Ujian Seminar Hasil' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji')) ||
                ($model->jenis_ujian=='S3 - Ujian Pra Promosi' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji')) ||
                ($model->jenis_ujian=='S3 - Ujian Promosi' && ($model->peran=='Ketua Penguji' || $model->peran=='Anggota Penguji' || $model->peran=='Promotor' || $model->peran=='Ko-Promotor'))
            ) {
                true;
            } else {
                Yii::$app->session->setFlash('danger', '<b>GAGAL UPDATE</b> <br> <i>Jenis Ujian: ' . $model->jenis_ujian . '</i> dengan <i>Peran: ' . $model->peran . '</i> tidak valid');
                Yii::$app->session->setFlash('info', '<b>SOLUSI</b> <br> Berikan input <i>Jenis Ujian</i> dan <i>Peran</i> yang berbeda dan valid untuk menambahkan penguji baru');
                return $this->redirect(Url::current());
            }

            if ($model->sks_kum == null) {
                $model->sks_kum = 0;
            }

            $model->departemen_dosen = $dos->departemen;
            $model->last_updated_by = Yii::$app->user->identity->nama;
            $model->last_updated_time = date('Y-m-d H:i:s');

            $old_pen = Penguji::find()
                ->where(
                    ['and',
                        ['and',
                            ['periode_dosen' => $periode_dosen],
                            ['nip_nidn_dosen' => $nip_nidn_dosen]
                        ],
                        ['and',
                            ['jenis_ujian' => $jenis_ujian],
                            ['peran' => $peran]
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
                $dos->total_sks_kum = $dos->total_sks_kum + $model->sks_kum;
                $old_dos->save();
            } else {
                $dos->total_sks_kum = $dos->total_sks_kum + $model->sks_kum - $old_pen->sks_kum;
            }
            $dos->save();

            $model->save();
            return $this->redirect(['view', 'periode_dosen' => $model->periode_dosen, 'departemen_dosen' => $model->departemen_dosen, 'nip_nidn_dosen' => $model->nip_nidn_dosen, 'jenis_ujian' => $model->jenis_ujian, 'peran' => $model->peran]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Penguji model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $periode_dosen
     * @param string $departemen_dosen
     * @param string $nip_nidn_dosen
     * @param string $jenis_ujian
     * @param string $peran
     * @return mixed
     */
    public function actionDelete($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $jenis_ujian, $peran)
    {
        $per = Periode::find()
            ->where(
                ['nama' => $periode_dosen]
            )
        ->one();

        # AccessControl Detail
        if ($per->is_locked == 'Locked') {
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }
        
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

        $model = $this->findModel($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $jenis_ujian, $peran);
        $dos = Dosen::find()->where([
            'nip_nidn' => $model->nip_nidn_dosen,
            'periode' => $model->periode_dosen,
        ])->one();
        
        # Send calculation to Dosen Page
        $dos->total_sks_kum = $dos->total_sks_kum - $model->sks_kum;
        $dos->save();

        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Penguji model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $periode_dosen
     * @param string $departemen_dosen
     * @param string $nip_nidn_dosen
     * @param string $jenis_ujian
     * @param string $peran
     * @return Penguji the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($periode_dosen, $departemen_dosen, $nip_nidn_dosen, $jenis_ujian, $peran)
    {
        if (($model = Penguji::findOne(['periode_dosen' => $periode_dosen, 'departemen_dosen' => $departemen_dosen, 'nip_nidn_dosen' => $nip_nidn_dosen, 'jenis_ujian' => $jenis_ujian, 'peran' => $peran])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
