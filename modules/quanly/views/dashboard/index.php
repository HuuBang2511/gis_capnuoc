<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\apexcharts\ApexchartsAsset;
use app\widgets\apexcharts\ApexChart;

ApexchartsAsset::register($this);
?>

<div class="row items-push">
    <div class="col-sm-6 col-xl-3">
        <div class="block block-themed block-rounded text-center d-flex flex-column h-100 mb-0">
            <div class="block-header bg-primary-dark">
                <div class="fs-4 fw-bold block-title">Van phân phối</div>
            </div>
            <div class="block-content block-content-full flex-grow-1">
                <div class="fs-1 fw-bold"><?= $thongke['van_mangluoi'] ?></div>
                <a href="<?= Yii::$app->urlManager->createUrl(['quanly/capnuocgd/gd-vanphanphoi/index']) ?>"
                   class="btn text-primary bg-primary-lighter">
                    Van phân phối
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="block block-themed block-rounded text-center d-flex flex-column h-100 mb-0">
            <div class="block-header bg-success">
                <div class="fs-4 fw-bold block-title">Đồng hồ tổng</div>
            </div>
            <div class="block-content block-content-full flex-grow-1">
                <div class="fs-1 fw-bold"><?= $thongke['nhamay_nuoc'] ?></div>
                <a href="<?= Yii::$app->urlManager->createUrl(['quanly/capnuocgd/gd-dongho-tong-gd/index']) ?>"
                   class="btn text-primary bg-primary-lighter">
                    Đồng hồ tổng
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="block block-themed block-rounded text-center d-flex flex-column h-100 mb-0">
            <div class="block-header bg-warning">
                <div class="fs-4 fw-bold block-title">Đồng hồ Khách hàng</div>
            </div>
            <div class="block-content block-content-full flex-grow-1">
                <div class="fs-1 fw-bold"><?= $thongke['dongho_kh'] ?></div>
                <a href="<?= Yii::$app->urlManager->createUrl(['quanly/capnuocgd/gd-dongho-kh-gd/index']) ?>"
                   class="btn text-primary bg-primary-lighter">
                   Đồng hồ Khách hàng
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="block block-themed block-rounded text-center d-flex flex-column h-100 mb-0">
            <div class="block-header bg-info">
                <div class="fs-4 fw-bold block-title">Số Km Ống cái</div>
            </div>
            <div class="block-content block-content-full flex-grow-1">
                <div class="fs-1 fw-bold"><?= $thongke['ong_phanphoi'] ?></div>
                <a href="<?= Yii::$app->urlManager->createUrl(['quanly/capnuocgd/gd-ongnganh/index']) ?>"
                   class="btn text-primary bg-primary-lighter">
                   Số Km Ống cái
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="block block-themed block-rounded text-center d-flex flex-column h-100 mb-0">
            <div class="block-header bg-info">
                <div class="fs-4 fw-bold block-title">Sự cố điểm bể</div>
            </div>
            <div class="block-content block-content-full flex-grow-1">
                <div class="fs-1 fw-bold"><?= $thongke['suco'] ?></div>
                <a href="<?= Yii::$app->urlManager->createUrl(['quanly/capnuocgd/gd-suco/index']) ?>"
                   class="btn text-primary bg-primary-lighter">
                    Sự cố điểm bể
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="block block-themed block-rounded text-center d-flex flex-column h-100 mb-0">
            <div class="block-header bg-info">
                <div class="fs-4 fw-bold block-title">Trụ PCCC</div>
            </div>
            <div class="block-content block-content-full flex-grow-1">
                <div class="fs-1 fw-bold"><?= $thongke['pccc'] ?></div>
                <a href="<?= Yii::$app->urlManager->createUrl(['quanly/capnuocgd/gd-tramcuuhoa/index']) ?>"
                   class="btn text-primary bg-primary-lighter">
                    Trụ PCCC
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="block block-themed block-rounded text-center d-flex flex-column h-100 mb-0">
            <div class="block-header bg-info">
                <div class="fs-4 fw-bold block-title">Trạm bơm</div>
            </div>
            <div class="block-content block-content-full flex-grow-1">
                <div class="fs-1 fw-bold"><?= $thongke['trambom'] ?></div>
                <a href="<?= Yii::$app->urlManager->createUrl(['quanly/capnuocgd/gd-trambom/index']) ?>"
                   class="btn text-primary bg-primary-lighter">
                    Trạm bơm
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="block block-themed block-rounded text-center d-flex flex-column h-100 mb-0">
            <div class="block-header bg-info">
                <div class="fs-4 fw-bold block-title">Hầm kỹ thuật</div>
            </div>
            <div class="block-content block-content-full flex-grow-1">
                <div class="fs-1 fw-bold"><?= $thongke['ham'] ?></div>
                <a href="<?= Yii::$app->urlManager->createUrl(['quanly/capnuocgd/gd-hamkythuat/index']) ?>"
                   class="btn text-primary bg-primary-lighter">
                    Hầm kỹ thuật
                </a>
            </div>
        </div>
    </div>
</div>

</div>
</div>