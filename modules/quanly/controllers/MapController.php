<?php

namespace app\modules\quanly\controllers;

use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\models\tanglong\KdtRanh;
use app\modules\quanly\models\tanglong\KdtThuadat;
use app\modules\quanly\models\tanglong\KdtTramBienAp;
use app\modules\quanly\models\tanglong\KdtCapnuoc;
use app\modules\quanly\models\tanglong\KdtCayxanh;
use app\modules\quanly\models\tanglong\KdtChieusang;
use app\modules\quanly\models\tanglong\KdtGiaothong;
use app\modules\quanly\models\tanglong\KdtHathe;
use yii\db\Expression;

class MapController extends QuanlyBaseController
{
    // ──────────────────────────────────────────────────────────────
    //  BẢN ĐỒ KĐT TÂN LONG
    // ──────────────────────────────────────────────────────────────

    public function actionTanglong()
    {
        $layers = [
            'ranh'      => $this->buildGeoJson(KdtRanh::class, [
                'id', 'Shape_Leng',
            ]),
            'thuadat'   => $this->buildGeoJson(KdtThuadat::class, [
                'id', 'loai_dat', 'so_thua', 'tinhhinh_xd', 'chu_ho', 'Shape_Leng', 'Shape_Area',
            ]),
            'giaothong' => $this->buildGeoJson(KdtGiaothong::class, [
                'id', 'name', 'fclass', 'oneway', 'maxspeed', 'bridge', 'tunnel', 'Shape_Leng',
            ]),
            'capnuoc'   => $this->buildGeoJson(KdtCapnuoc::class, [
                'id', 'Layer', 'Linetype',
            ]),
            'hathe'     => $this->buildGeoJson(KdtHathe::class, [
                'id', 'ma',
            ]),
            'trambiap'  => $this->buildGeoJson(KdtTramBienAp::class, [
                'id', 'ma_so', 'loai_mba', 'nam', 'tinh_trang',
            ]),
            'cayxanh'   => $this->buildGeoJson(KdtCayxanh::class, [
                'id', 'loai_cay', 'tinh_trang', 'ma_so', 'nam_trong',
            ]),
            'chieusang' => $this->buildGeoJson(KdtChieusang::class, [
                'id', 'loai_den', 'tinh_trang',
            ]),
        ];

        return $this->render('tanglong', [
            'layers' => $layers,
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    //  CÁC ACTION BẢN ĐỒ KHÁC
    // ──────────────────────────────────────────────────────────────

    public function actionGiadinh()
    {
        return $this->render('giadinh');
    }

    public function actionMaptest()
    {
        return $this->render('maptest');
    }

    public function actionMap_iot()
    {
        return $this->render('map_iot');
    }

    // ──────────────────────────────────────────────────────────────
    //  HELPER: Chuyển dữ liệu từ model sang GeoJSON FeatureCollection
    //
    //  @param string $modelClass  Tên class model (full namespace)
    //  @param array  $properties  Các cột thuộc tính cần lấy (ngoài geom)
    //  @return array              Mảng GeoJSON FeatureCollection
    // ──────────────────────────────────────────────────────────────
    private function buildGeoJson(string $modelClass, array $properties = []): array
    {
        // Chọn cột geom dưới dạng GeoJSON text, cùng các cột thuộc tính
        $selectCols = array_merge(
            ['ST_AsGeoJSON(geom) AS geom_json'],
            $properties
        );

        $rows = $modelClass::find()
            ->select($selectCols)
            ->where(['IS NOT', 'geom', null])
            ->asArray()
            ->all();

        $features = [];
        foreach ($rows as $row) {
            $geomJson = $row['geom_json'] ?? null;
            if (!$geomJson) {
                continue;
            }

            // Lấy phần properties (bỏ cột geom_json)
            $props = array_diff_key($row, ['geom_json' => true]);

            $features[] = [
                'type'       => 'Feature',
                'geometry'   => json_decode($geomJson, true),
                'properties' => $props,
            ];
        }

        return [
            'type'     => 'FeatureCollection',
            'features' => $features,
        ];
    }
}