<?php


namespace app\modules\quanly\controllers;


use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\models\aphu\DonghoKh;
use app\modules\quanly\models\aphu\NhamayNuoc;
use app\modules\quanly\models\aphu\VanMangluoi;
use app\modules\quanly\models\capnuocgd\GdDonghoKhGd;
use app\modules\quanly\models\capnuocgd\GdDonghoTongGd;
use app\modules\quanly\models\capnuocgd\GdOngcai;
use app\modules\quanly\models\capnuocgd\GdVanphanphoi;
use app\modules\quanly\models\Ktvhxh;
use app\modules\quanly\models\aphu\OngPhanphoi;
use app\modules\quanly\models\capnuocgd\GdSuco;
use app\modules\quanly\models\capnuocgd\GdTrambom;
use app\modules\quanly\models\capnuocgd\GdTramcuuhoa;
use app\modules\quanly\models\capnuocgd\GdHamkythuat;

class DashboardController extends QuanlyBaseController
{
    public function actionIndex()
    {
        $thongke['dongho_kh'] = GdDonghoKhGd::find()->count();
        $thongke['nhamay_nuoc'] = GdDonghoTongGd::find()->count();
        $thongke['van_mangluoi'] = GdVanphanphoi::find()->count();
        $thongke['ong_phanphoi'] = GdOngcai::find()->select('shape_leng')->sum('shape_leng');
        $thongke['ong_phanphoi'] = round($thongke['ong_phanphoi']/1000);
        $thongke['suco'] = GdSuco::find()->count();
        $thongke['pccc'] = GdTramcuuhoa::find()->count();
        $thongke['trambom'] = GdTrambom::find()->count();
        $thongke['ham'] = GdHamkythuat::find()->count();

        return $this->render('index', [
            'thongke' => $thongke,
        ]);
    }
}