<?php

use app\modules\APPConfig;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use app\widgets\maps\LeafletMapAsset;
use yii\helpers\Url;
use kartik\file\FileInput;

/**
 * @var yii\web\View $this
 * @var app\models\GdSuco $model
 * @var app\models\HinhAnh $hinhanh
 * @var kartik\form\ActiveForm $form
 * @var array $categories
 */

LeafletMapAsset::register($this);

// 1. Gán biến gọn gàng hơn
$controller = Yii::$app->controller;
$actionId = $controller->action->id;
$this->title = Yii::t('app', ($controller->label[$actionId] ?? $actionId) . ' ' . $controller->title);
$this->params['breadcrumbs'][] = ['label' => ($controller->label['search'] ?? 'Search') . ' ' . $controller->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// 2. Xử lý file ảnh hiệu quả và an toàn hơn
$initialPreview = [];
if (!empty($model->file)) {
    // Giải mã JSON một lần và kiểm tra kết quả
    $filePaths = json_decode($model->file, true);
    if (is_array($filePaths)) {
        // Dùng array_map để code ngắn gọn hơn
        $initialPreview = array_map(function ($path) {
            return Url::to($path, true);
        }, $filePaths);
    }
}

// 3. Xử lý CSS đúng chuẩn Yii2
if (!$model->isNewRecord) {
    $css = 'button.btn-close.fileinput-remove { display: none; }';
    $this->registerCss($css);
}

// 4. Xác định tọa độ trung tâm một cách rõ ràng
$defaultLat = 10.804305026919454;
$defaultLng = 106.71788692474367;
$lat = $model->lat ?? $defaultLat;
$lng = $model->long ?? $defaultLng;

// Ưu tiên geojson nếu có
if (!empty($model->geojson)) {
    $geojson = json_decode($model->geojson, true);
    // Dùng toán tử ?? để có giá trị mặc định an toàn
    $lng = $geojson['coordinates'][0] ?? $lng;
    $lat = $geojson['coordinates'][1] ?? $lat;
}
?>

<div class="gd-suco-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="block block-themed">
        <div class="block-header">
            <h2 class="block-title"><?= Html::encode($this->title) ?></h2>
        </div>
        <div class="block-content">
            <div class="row">
                <div class="col-lg-12">
                    <div id="map" style="height: 400px; width: 100%; border-radius: 4px; z-index: 1;"></div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-6">
                    <?= $form->field($model, 'long')->textInput(['id' => 'inputX', 'value' => $lng])->label('Kinh độ') ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'lat')->textInput(['id' => 'inputY', 'value' => $lat])->label('Vĩ độ') ?>
                </div>
            </div>

            <!-- ... Các trường form khác không đổi ... -->
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'masuco')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'madanhba')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'sonha')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'duong')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'ngayphathien')->widget(DatePicker::class) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'nguoiphathien')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'ngaysuachua')->widget(DatePicker::class) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'nguoisuachua')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'donvisuachua')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'hinhthucphuchoi')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'vitriphathien')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'nguyennhan')->widget(Select2::class, [
                        'data' => ArrayHelper::map($categories['dm_suco_nguyennhan'] ?? [], 'ma', 'ten'),
                        'options' => ['prompt' => 'Chọn nguyên nhân'],
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'bienphapxuly')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'duongkinho')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'vatlieu_ong')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'tailapmatduong')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'ghichu')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'kichthuocp')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'tontai')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'xulysuco_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map($categories['dm_xulysuco'] ?? [], 'id', 'ten'),
                        'options' => ['prompt' => 'Chọn'],
                    ]) ?>
                </div>
            </div>
            <!-- ... Kết thúc các trường form ... -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="block block-bordered">
                        <div class="pt-1">
                            <div class="row px-3">
                                <div class="col-lg-12">
                                    <?php
                                    $fileInputPluginOptions = [
                                        'initialPreviewAsData' => true,
                                        'allowedFileExtensions' => ['png', 'jpg', 'jpeg'],
                                        'showPreview' => true,
                                        'showCaption' => true,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                    ];

                                    if (!empty($initialPreview)) {
                                        $fileInputPluginOptions['initialPreview'] = $initialPreview;
                                        $fileInputPluginOptions['overwriteInitial'] = true;
                                    }

                                    echo $form->field($hinhanh, 'imageupload')->widget(FileInput::class, [
                                        'options' => ['multiple' => true],
                                        'pluginOptions' => $fileInputPluginOptions
                                    ])->label('Hình ảnh');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="form-group col-lg-12">
                    <?= Html::submitButton('Lưu', ['class' => 'btn btn-primary float-left']) ?>
                    <?= Html::button('Quay lại', ['class' => 'btn btn-light float-right', 'onclick' => "history.back()"]) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<JS
const mapCenterLat = {$lat};
const mapCenterLng = {$lng};
const map = L.map('map').setView([mapCenterLat, mapCenterLng], 18);

// Base layers
const baseLayers = {
    "Bản đồ Google": L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 22,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map),
    "Ảnh vệ tinh": L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
        maxZoom: 22,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    }),
};

// Marker
const icon = L.icon({
    iconUrl: 'https://auth.hcmgis.vn/uploads/icon/icons8-placeholder-64.png',
    iconSize: [40, 40],
    iconAnchor: [20, 40],
    popupAnchor: [0, -40],
});

const marker = L.marker([mapCenterLat, mapCenterLng], {
    draggable: true,
    icon: icon,
}).addTo(map);

marker.on('dragend', function(event) {
    const position = event.target.getLatLng();
    map.panTo(position);
    $('#inputY').val(position.lat);
    $('#inputX').val(position.lng);
});

// --- CẬP NHẬT: THÊM TRỰC TIẾP CÁC LỚP WMS VÀO BẢN ĐỒ ---

const wmsBaseUrl = 'https://nongdanviet.net/geoserver/giscapnuoc/wms';
const wmsDefaultOptions = {
    format: 'image/png',
    transparent: true,
    maxZoom: 22,
};

// Cấu hình các lớp WMS
const wmsLayersConfig = [
    // { layers: 'giscapnuoc:gd_data_logger' },
    { layers: 'giscapnuoc:v2_4326_DMA' },
    { layers: 'giscapnuoc:gd_hamkythuat' },
    { layers: 'giscapnuoc:gd_ongcai',},
    { layers: 'giscapnuoc:gd_ongnganh' },
    { layers: 'giscapnuoc:v2_4326_ONGTRUYENDAN' },
    { layers: 'giscapnuoc:v2_gd_suco' },
    { layers: 'giscapnuoc:gd_dongho_kh_gd' },
    { layers: 'giscapnuoc:gd_dongho_tong_gd' },
    { layers: 'giscapnuoc:gd_trambom' },
    { layers: 'giscapnuoc:gd_tramcuuhoa' },
    { layers: 'giscapnuoc:gd_vanphanphoi' },
];

// Lặp qua cấu hình và thêm từng lớp WMS vào bản đồ
wmsLayersConfig.forEach(layerInfo => {
    const options = { ...wmsDefaultOptions, ...layerInfo };
    L.tileLayer.wms(wmsBaseUrl, options).addTo(map);
});

// Chỉ thêm bảng điều khiển cho các lớp nền (base layers)
L.control.layers(baseLayers).addTo(map);

JS;
$this->registerJs($js, \yii\web\View::POS_READY);
?>
