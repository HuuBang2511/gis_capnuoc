<?php

namespace app\modules\quanly\controllers;

use app\modules\quanly\base\QuanlyBaseController;
use yii\web\Controller;
use yii\db\Expression;
use yii\helpers\Json;
use Yii;

// Import Models
use app\modules\quanly\models\swanbay\SbBaseThuadat;
use app\modules\quanly\models\swanbay\SbBaseThuyhe;
use app\modules\quanly\models\swanbay\SbBaseGiaothong;
use app\modules\quanly\models\swanbay\SbNetworkOngphanphoi;
use app\modules\quanly\models\swanbay\SbNetworkTrucuuhoa;
use app\modules\quanly\models\swanbay\SbNetworkVan;
use app\modules\quanly\models\swanbay\SbBaseCaodo;

class MapCamauController extends QuanlyBaseController
{
    // Sử dụng layout map riêng để loại bỏ các thành phần dư thừa của trang web chính
    public $layout = '@app/views/layouts/map/main'; 
    
    public function actionIndex()
    {
        // 1. Lấy dữ liệu lớp Nền
        $dataThuadat = $this->getGeoJsonData(SbBaseThuadat::class, ['sothua', 'soto', 'chusohuu', 'quyhoach', 'shape_area']);
        $dataThuyhe = $this->getGeoJsonData(SbBaseThuyhe::class, ['shape_area']);
        $dataGiaothong = $this->getGeoJsonData(SbBaseGiaothong::class, ['loaimat', 'shape_leng']);

        // 2. Lấy dữ liệu Mạng lưới cấp nước & PCCC
        $dataOng = $this->getGeoJsonData(SbNetworkOngphanphoi::class, ['vatlieu', 'coong', 'chieudai']);
        $dataTru = $this->getGeoJsonData(SbNetworkTrucuuhoa::class, ['loaitru', 'vatlieu', 'cotru']);
        $dataVan = $this->getGeoJsonData(SbNetworkVan::class, ['vatlieu', 'covan']);
        $dataCaodo = $this->getGeoJsonData(SbBaseCaodo::class, ['caodo']);

        // 3. Truyền dữ liệu sang View
        return $this->render('index', [
            'geoJsonData' => [
                'thuadat' => $dataThuadat,
                'thuyhe' => $dataThuyhe,
                'giaothong' => $dataGiaothong,
                'ong' => $dataOng,
                'tru' => $dataTru,
                'van' => $dataVan,
                'caodo' => $dataCaodo,
            ]
        ]);
    }

    /**
     * Hàm helper lấy dữ liệu và chuyển đổi thành GeoJSON chuẩn
     * @param string $modelClass Tên class Model
     * @param array $attributes Các thuộc tính cần lấy
     * @return array Cấu trúc GeoJSON FeatureCollection
     */
    protected function getGeoJsonData($modelClass, $attributes = [])
    {
        // Sử dụng ST_AsGeoJSON của PostGIS để lấy geometry dạng JSON string
        $select = array_merge(['id'], $attributes, [new Expression('ST_AsGeoJSON(geom) as geometry')]);
        
        $data = $modelClass::find()
            ->select($select)
            ->asArray()
            // ->limit(3000) // Có thể bỏ comment để giới hạn nếu dữ liệu quá lớn gây lag
            ->all();

        $features = [];
        foreach ($data as $row) {
            // Bỏ qua nếu không có dữ liệu hình học
            if (empty($row['geometry'])) continue;
            
            $properties = $row;
            // Xóa trường geometry string khỏi properties để giảm dung lượng file
            unset($properties['geometry']);

            $features[] = [
                'type' => 'Feature',
                'geometry' => Json::decode($row['geometry']), // Decode string thành object JSON
                'properties' => $properties
            ];
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
    }
}