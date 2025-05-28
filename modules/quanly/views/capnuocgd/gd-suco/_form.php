<?php

use app\modules\APPConfig;
use app\widgets\maps\layers\LayerGroup;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use app\widgets\maps\types\LatLng;
use app\widgets\maps\layers\DraggableMarker;
use app\widgets\maps\LeafletMap;
use app\widgets\maps\layers\TileLayer;
use \app\widgets\maps\controls\Layers;
use app\widgets\maps\LeafletMapAsset;
use yii\helpers\Url;

use kartik\file\FileInput;

LeafletMapAsset::register($this);


$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;

$this->title = Yii::t('app', $label[$requestedAction->id] . ' ' . $controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['search'] . ' ' . $controller->title, 'url' => $controller->url];
$this->params['breadcrumbs'][] = $this->title;

    if($model->file != null){
        $filehinhanh  = [];
        $model->file = json_decode($model->file, true);

        foreach($model->file as $i => $item){
            $filehinhanh[] = Yii::$app->homeUrl.$item;
        }
    }
?>

<style>
    <?php if(!$model->isNewRecord): ?>
    button.btn-close.fileinput-remove {
        display: none;
    }
    <?php endif; ?>
</style>


<div class="gd-suco-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="block block-themed">

        <div class="block-header">
            <h2 class="block-title"><?= $this->title ?></h2>
        </div>
        <div class="block-content">
        <div class="row">
                <div class="col-lg-12">
                    <?php
                    if ($model->geojson != null) {
                        $geojson = json_decode($model->geojson, true);
                        $center = new LatLng(['lat' => $geojson['coordinates'][1], 'lng' => $geojson['coordinates'][0]]);
                    } elseif ($model->lat != null) {
                        $center = new LatLng(['lat' => $model->lat, 'lng' => $model->long]);
                    }
                    else {
                        $center = new LatLng(['lat' => 10.804207610432567, 'lng' => 106.6952618580442]);
                    }
                    $marker = new DraggableMarker([
                        'center' => $center,
                        'inputX' => '#inputX',
                        'inputY' => '#inputY',
                    ]);

                    $basemaps = [];
                    foreach (APPConfig::$BASEMAP as $i => $item) {
                        $basemaps[] = new TileLayer($item);
                    }
                    $layercontrol = new Layers();
                    $layercontrol->setBaseLayers($basemaps);


                    $leaflet = new LeafletMap([
                        'center' => $center
                    ]);
                    $leaflet->addLayer($basemaps[0]);
                    $leaflet->addControl($layercontrol);
                    $leaflet->addLayer($marker);

                    echo $leaflet->widget(['styleOptions' => ['height' => '400px']]);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'long')->textInput([
                        'id' => 'inputX',
                        'value' => isset($geojson['coordinates'][0]) ? $geojson['coordinates'][0] : 106.6952618580442
                    ])->label('Kinh độ') ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'lat')->textInput([
                        'id' => 'inputY',
                        'value' => isset($geojson['coordinates'][1]) ? $geojson['coordinates'][1] : 10.804207610432567
                    ])->label('Vĩ độ') ?>
                </div>
            </div>
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
                    <?= $form->field($model, 'ngayphathien')->widget(\kartik\widgets\DatePicker::className()) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'nguoiphathien')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'ngaysuachua')->widget(\kartik\widgets\DatePicker::className()) ?>
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
                    <?= $form->field($model, 'nguyennhan')->widget(Select2::className(), [
                        'data' => ArrayHelper::map($categories['dm_suco_nguyennhan'], 'ma', 'ten'),
                        'options' => [
                            'prompt' => 'Chọn nguyên nhân'
                        ],
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
                    <?= $form->field($model, 'xulysuco_id')->widget(Select2::className(), [
                        'data' => ArrayHelper::map($categories['dm_xulysuco'], 'id', 'ten'),
                        'options' => [
                            'prompt' => 'Chọn'
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="block block-bordered">
                        <div class="pt-1">
                            <div class="row px-3">
                                <?php if($model->isNewRecord): ?>
                                <div class="col-lg-12">
                                    <?= $form->field($hinhanh, 'imageupload')->widget(FileInput::className(), [
                                            'options'=>[
                                                'multiple'=>true
                                            ],
                                            'pluginOptions' => [
                                                'initialPreviewAsData' => true,
                                                'allowedFileExtensions' => ['png', 'jpg', 'jpeg'],
                                                'showPreview' => true,
                                                'showCaption' => true,
                                                'showRemove' => true,
                                                'showUpload' => false,
                                            ]
                                        ])->label('Hình ảnh');
                                    ?>
                                </div>
                                <?php else: ?>
                                <?php if($model->file!= null): ?>
                                <div class="col-lg-12">
                                    <?= $form->field($hinhanh, 'imageupload')->widget(FileInput::className(), [
                                            'options'=>[
                                                'multiple'=>true
                                            ],
                                            'pluginOptions' => [
                                                'overwriteInitial' => true,
                                                'initialPreview' => $filehinhanh,
                                                'initialPreviewAsData' => true,
                                                //'initialPreviewFileType' => 'pdf',
                                                'allowedFileExtensions' => ['png', 'jpg', 'jpeg'],
                                                'showPreview' => true,
                                                'showCaption' => true,
                                                'showRemove' => true,
                                                'showUpload' => false,
                                            ]
                                        ])->label('Hình ảnh');
                                    ?>
                                </div>
                                <?php else: ?>
                                <div class="col-lg-12">
                                    <?= $form->field($hinhanh, 'imageupload')->widget(FileInput::className(), [
                                            'options'=>[
                                                'multiple'=>true
                                            ],
                                            'pluginOptions' => [
                                                'initialPreviewAsData' => true,
                                                'allowedFileExtensions' =>['png', 'jpg', 'jpeg'],
                                                'showPreview' => true,
                                                'showCaption' => true,
                                                'showRemove' => true,
                                                'showUpload' => false,
                                            ]
                                        ])->label('Hình ảnh');
                                    ?>
                                </div>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-lg-12">
                    <?= Html::submitButton('Lưu', ['class' => 'btn btn-primary float-left']) ?>
                    <?= Html::button('Quay lại', ['class' => 'btn btn-light float-right', 'type' => 'button', 'onclick' => "history.back()"]) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
//10.804291919691535, 106.69527258767485
<script type="module">
    var map = L.map('map').setView([<?= ($model->lat != null) ? $model->lat : 10.804291919691535  ?>, <?= ($model->long != null) ? $model->long : 106.69527258767485 ?>], 18);
    var icon = L.icon({
        iconUrl: 'https://auth.hcmgis.vn/uploads/icon/icons8-placeholder-64.png',
        iconSize: [40, 40],
        iconAnchor: [20, 20],
        popupAnchor: [0, -48],
    });
    var marker = new L.marker([<?= ($model->lat != null) ? $model->lat : 10.804291919691535  ?>, <?= ($model->long != null) ? $model->long : 106.69527258767485 ?>], {
        'draggable': 'true',
        'icon': icon,
    });

    var baseLayers = {
        "Bản đồ Google": L.tileLayer('http://{s}.google.com/vt/lyrs=' + 'r' + '&x={x}&y={y}&z={z}', {
            maxZoom: 22,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map),
        "Ảnh vệ tinh": L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxZoom: 22,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        }),
    };
    var locateControl = new L.Control.Locate({
        position: 'bottomleft',
        strings: {
            title: "Hiện vị trí",
            popup: "Bạn đang ở đây"
        },
        drawCircle: true,
        follow: true,
    });

    // Thêm lớp locateControl vào bản đồ
    map.addControl(locateControl);

    var overLayers = {};

    L.control.layers(baseLayers, overLayers).addTo(map);
    // map.addLayer(hcmgis, true);
    var x = 10.804291919691535;
    var y = 106.69527258767485;

    marker.on('dragend', function(event) {
        var marker = event.target;
        var position = marker.getLatLng();
        marker.setLatLng(new L.LatLng(position.lat, position.lng), {
            draggable: 'true'
        });
        map.panTo(new L.LatLng(position.lat, position.lng))
        $('#inputY').val(position.lat);
        $('#inputX').val(position.lng);
    });
    map.addLayer(marker);
                            var wmsTDLayer = L.tileLayer.wms('http://103.9.77.108:8080/geoserver/giadinh/wms', {
                            layers: 'giadinh:gd_thuadat',
                            format: 'image/png',
                            transparent: true,
                            minZoom: 18,
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);
                                                    var wmsHamLayer = L.tileLayer.wms('http://103.9.77.108:8080/geoserver/giadinh/wms', {
                            layers: 'giadinh:gd_hamkythuat',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);


                                                                            var wmsGTLayer = L.tileLayer.wms('http://103.9.77.108:8080/geoserver/giadinh/wms', {
                            layers: 'giadinh:gd_giaothong',
                            format: 'image/png',
                            transparent: true,
                            minZoom: 18,
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        var wmsOngCaiDHLayer = L.tileLayer.wms('http://103.9.77.108:8080/geoserver/giadinh/wms', {
                            layers: 'giadinh:gd_ongcai',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: "status = 1", // Thêm điều kiện lọc tinhtrang là 'DH'
                            minZoom: 18,
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        var wmsOngNganhLayer = L.tileLayer.wms('http://103.9.77.108:8080/geoserver/giadinh/wms', {
                            layers: 'giadinh:gd_ongnganh',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            minZoom: 18, // Đặt maxZoom là 22
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        var wmsTramCuuHoaLayer = L.tileLayer.wms('http://103.9.77.108:8080/geoserver/giadinh/wms', {
                            layers: 'giadinh:gd_tramcuuhoa',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            minZoom: 20,
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

                        var wmsVanPhanPhoiLayer = L.tileLayer.wms('http://103.9.77.108:8080/geoserver/giadinh/wms', {
                            layers: 'giadinh:gd_vanphanphoi',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            minZoom: 20,
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);

    var wmsDonghoKhLayer = L.tileLayer.wms('http://103.9.77.108:8080/geoserver/giadinh/wms', {
                            layers: 'giadinh:gd_dongho_kh_gd',
                            format: 'image/png',
                            transparent: true,
                            CQL_FILTER: 'status = 1',
                            minZoom: 18,
                            maxZoom: 22 // Đặt maxZoom là 22
                        }).addTo(map);


</script>