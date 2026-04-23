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
use app\modules\quanly\models\swanbay\SbBaseCaodo;
use app\modules\quanly\models\swanbay\SbBaseGiaothong as SbGiaothong;
use app\modules\quanly\models\swanbay\SbBaseThuadat as SbThuadat;
use app\modules\quanly\models\swanbay\SbBaseThuyhe;
use app\modules\quanly\models\swanbay\SbNetworkOngphanphoi;
use app\modules\quanly\models\swanbay\SbNetworkTrucuuhoa;
use app\modules\quanly\models\swanbay\SbNetworkVan;
use yii\db\Expression;
use yii\web\Response;
use yii\web\UploadedFile;

class MapController extends QuanlyBaseController
{
    /**
     * Tắt CSRF cho các API action SwanBay
     */
    public function beforeAction($action)
    {
        $noCsrfActions = ['swanbay-update', 'swanbay-upload', 'swanbay-delete-image'];
        if (in_array($action->id, $noCsrfActions)) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    // ──────────────────────────────────────────────────────────────
    //  BẢN ĐỒ KĐT TÂN LONG
    // ──────────────────────────────────────────────────────────────

	public $layout = '@app/views/layouts/map/main';

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

    public function actionSwanbay()
    {
        $layers = [
            'thuadat' => $this->buildGeoJson(SbThuadat::class, [
                'id', 'objectid', 'sothua', 'soto', 'sonha', 'diachi', 'chusohuu', 'quyhoach', 'image', 'shape_leng', 'shape_area',
            ]),
            'thuyhe' => $this->buildGeoJson(SbBaseThuyhe::class, [
                'id', 'objectid', 'shape_leng', 'shape_area',
            ]),
            'giaothong' => $this->buildGeoJson(SbGiaothong::class, [
                'id', 'objectid', 'loaimat', 'shape_leng', 'shape_area',
            ]),
            'ongphanphoi' => $this->buildGeoJson(SbNetworkOngphanphoi::class, [
                'id', 'objectid', 'vatlieu', 'coong', 'chieudai', 'shape_leng',
            ]),
            'trucuuhoa' => $this->buildGeoJson(SbNetworkTrucuuhoa::class, [
                'id', 'objectid', 'loaitru', 'vatlieu', 'cotru',
            ]),
            'van' => $this->buildGeoJson(SbNetworkVan::class, [
                'id', 'objectid', 'vatlieu', 'covan',
            ]),
            'caodo' => $this->buildGeoJson(SbBaseCaodo::class, [
                'id', 'objectid', 'caodo',
            ]),
        ];

        return $this->render('swanbay', [
            'layers' => $layers,
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    //  CẬP NHẬT THỬA ĐẤT SWANBAY
    // ──────────────────────────────────────────────────────────────

    /**
     * API: Cập nhật thông tin thửa đất SwanBay
     */
    public function actionSwanbayUpdate()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if (!\Yii::$app->request->isPost) {
            return ['success' => false, 'message' => 'Phương thức không hợp lệ'];
        }

        $id = \Yii::$app->request->post('id');
        if (!$id) {
            return ['success' => false, 'message' => 'Thiếu ID'];
        }

        $model = SbThuadat::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Không tìm thấy thửa đất'];
        }

        $fields = ['sothua', 'soto', 'sonha', 'diachi', 'chusohuu', 'quyhoach', 'shape_area'];
        foreach ($fields as $field) {
            $value = \Yii::$app->request->post($field);
            if ($value !== null) {
                $model->$field = $value;
            }
        }

        if ($model->save(false)) {
            return ['success' => true, 'message' => 'Cập nhật thành công'];
        }

        return ['success' => false, 'message' => 'Lỗi khi lưu', 'errors' => $model->errors];
    }

    /**
     * API: Upload hình ảnh cho thửa đất SwanBay (Hỗ trợ nhiều ảnh)
     */
    public function actionSwanbayUpload()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $id = \Yii::$app->request->post('id');
        if (!$id) {
            return ['success' => false, 'message' => 'Thiếu ID'];
        }

        $model = SbThuadat::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Không tìm thấy thửa đất'];
        }

        $uploadDir = \Yii::getAlias('@webroot') . '/resources/uploads/swanbay';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        $files = UploadedFile::getInstancesByName('image');
        if (empty($files)) {
            return ['success' => false, 'message' => 'Không có tệp hình ảnh'];
        }

        // Get existing images
        $currentImages = [];
        if (!empty($model->image)) {
            $decoded = json_decode($model->image, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $currentImages = $decoded;
            } else {
                // Handle old single string format
                $currentImages = [$model->image];
            }
        }

        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'ico'];
        $uploadedPaths = [];
        $errors = [];

        foreach ($files as $file) {
            $ext = strtolower($file->extension);
            if (!in_array($ext, $allowedExt)) {
                $errors[] = "Tệp {$file->name} không được hỗ trợ.";
                continue;
            }

            // Clean original filename and add timestamp+random for uniqueness among multiple files
            $cleanBaseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $file->baseName);
            $fileName = $cleanBaseName . '_' . $id . '_' . uniqid() . '.' . $ext;
            $filePath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

            if ($file->saveAs($filePath)) {
                $webPath = \Yii::$app->request->baseUrl . '/resources/uploads/swanbay/' . $fileName;
                $uploadedPaths[] = $webPath;
            } else {
                $errors[] = "Lỗi khi lưu tệp {$file->name}.";
            }
        }

        if (!empty($uploadedPaths)) {
            $currentImages = array_merge($currentImages, $uploadedPaths);
            $model->image = json_encode($currentImages);
            $model->save(false);
            
            return [
                'success' => true, 
                'message' => 'Upload thành công', 
                'images' => $currentImages,
                'errors' => $errors
            ];
        }

        return ['success' => false, 'message' => 'Không có tệp nào được lưu', 'errors' => $errors];
    }

    /**
     * API: Xóa hình ảnh thửa đất SwanBay
     */
    public function actionSwanbayDeleteImage()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $id = \Yii::$app->request->post('id');
        $imagePath = \Yii::$app->request->post('image_path'); // Specific image to delete
        
        if (!$id) {
            return ['success' => false, 'message' => 'Thiếu ID'];
        }

        $model = SbThuadat::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Không tìm thấy thửa đất'];
        }

        if (empty($model->image)) {
            return ['success' => true, 'message' => 'Không có ảnh để xóa'];
        }

        $currentImages = [];
        $decoded = json_decode($model->image, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $currentImages = $decoded;
        } else {
            $currentImages = [$model->image];
        }

        $uploadDir = \Yii::getAlias('@webroot') . '/resources/uploads/swanbay';

        if ($imagePath) {
            // Delete a specific image
            $index = array_search($imagePath, $currentImages);
            if ($index !== false) {
                $fileName = basename($imagePath);
                $fullPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
                unset($currentImages[$index]);
                $currentImages = array_values($currentImages); // Reset index
            }
        } else {
            // Delete all images
            foreach ($currentImages as $path) {
                $fileName = basename($path);
                $fullPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }
            $currentImages = [];
        }

        $model->image = empty($currentImages) ? null : json_encode($currentImages);
        $model->save(false);

        return ['success' => true, 'message' => 'Đã xóa hình ảnh', 'images' => $currentImages];
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
