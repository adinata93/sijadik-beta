<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <link rel="icon" href="dist/img/icon.png" sizes="32x32">
    <title>SIJADIK</title>
    <?php $this->head() ?>
</head>

<!--un sidebar-collapse -->
  <body class="hold-transition skin-green sidebar-mini">
  <?php $this->beginBody() ?>
    <div class="wrapper">

      <!-- Main Header -->
      <header class="main-header">

        <!-- Logo -->
        <a href=<?= Yii::$app->homeUrl ?> class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><img src="dist/img/icon.png" height="20" width="20"></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>SIJADIK</b></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
            <?php
            echo Nav::widget([
                'options' => ['class' => 'navbar-custom-menu'],
                'items' => [
                    Yii::$app->user->isGuest ? (
                        ['label' => 'Login', 'url' => ['/site/login']]
                    ) : (
                        '<li>'
                        . Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                            '<i class="glyphicon glyphicon-log-out"></i> Logout (' . Yii::$app->user->identity->nama . ')',
                            ['class' => 'logout btn btn-success']
                        )
                        . Html::endForm()
                        . '</li>'
                    )
                ],
            ]);
            ?>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="dist/img/mkr.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p>Fak. Kedokteran Gigi</p>
              <a>UNIVERSITAS INDONESIA</a>
            </div>
          </div>

          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">
            <li class="header">SIMULASI IMBAL JASA</li>
            <?php 
                $temp = stripos(Url::current(), 'dosen');
                if ($temp == true) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
            ?>
              <a href="index.php?r=dosen%2Findex">
                <i class="glyphicon glyphicon-user"></i>
                <span>Dosen</span> 
              </a>
            </li>
            <?php 
                $temp = stripos(Url::current(), 'pembimbing');
                if ($temp == true) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
            ?>
              <a href="index.php?r=pembimbing%2Findex">
                <i class="glyphicon glyphicon-road"></i>
                <span>Pembimbing</span> 
              </a>
            </li>
            <?php 
                $temp = stripos(Url::current(), 'penguji');
                if ($temp == true) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
            ?>
              <a href="index.php?r=penguji%2Findex">
                <i class="glyphicon glyphicon-check"></i>
                <span>Penguji</span> 
              </a>
            </li>
            <?php 
                $temp = stripos(Url::current(), 'pengajar');
                if ($temp == true) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
            ?>
              <a href="index.php?r=pengajar%2Findex">
                <i class="glyphicon glyphicon-education"></i>
                <span>Pengajar</span> 
              </a>
            </li>
            <li class="header">JADWAL AKADEMIK</li>
            <?php 
                $temp = stripos(Url::current(), 'mata-kuliah');
                if ($temp == true) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
            ?>
              <a href="index.php?r=mata-kuliah%2Findex">
                <i class="glyphicon glyphicon-list-alt"></i>
                <span>Mata Kuliah</span> 
              </a>
            </li>
            <?php 
                $temp = stripos(Url::current(), 'jadwal');
                if ($temp == true) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
            ?>
              <a href="index.php?r=jadwal%2Findex">
                <i class="glyphicon glyphicon-calendar"></i>
                <span>Jadwal Pengajar</span> 
              </a>
            </li>
            <li class="header">SETTING</li>
            <?php 
                $temp = stripos(Url::current(), 'periode');
                if ($temp == true) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
            ?>
              <a href="index.php?r=periode%2Findex">
                <i class="glyphicon glyphicon-time"></i>
                <span>Periode</span> 
                <small class="label pull-right bg-red"><i class="glyphicon glyphicon-lock"></i></small>
              </a>
            </li>
            <?php 
                $temp = stripos(Url::current(), 'departemen');
                if ($temp == true) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
            ?>
              <a href="index.php?r=departemen%2Findex">
                <i class="glyphicon glyphicon-th-large"></i>
                <span>Departemen</span> 
              </a>
            </li>
            <?php 
                $temp = stripos(Url::current(), 'kategori-koefisien');
                if ($temp == true) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
            ?>
              <a href="index.php?r=kategori-koefisien%2Findex">
                <i class="glyphicon glyphicon-tag"></i>
                <span>Kategori Koefisien</span> 
              </a>
            </li>
            <?php 
                $temp = stripos(Url::current(), 'program-studi');
                if ($temp == true) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
            ?>
              <a href="index.php?r=program-studi%2Findex">
                <i class="glyphicon glyphicon-tags"></i>
                <span>Program Studi</span> 
              </a>
            </li>
          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <section class="content">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <div class="box box-solid">
                <div class="box-body">
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </div>
            </div>
        </section>
      </div><!-- /.content-wrapper -->
      
      <!-- Main Footer -->
      <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
          SDM FKG UI
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2017 <a href="http://www.ui.ac.id/">Universitas Indonesia</a></strong>
      </footer>
    
    </div><!-- ./wrapper -->


    <?php $this->endBody() ?>
  </body>
</html>
<?php $this->endPage() ?>
