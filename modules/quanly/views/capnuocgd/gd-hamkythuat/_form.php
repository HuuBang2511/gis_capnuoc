<?php

use app\modules\APPConfig;
use app\widgets\maps\controls\Layers;
use app\widgets\maps\layers\DraggableMarker;
use app\widgets\maps\layers\TileLayer;
use app\widgets\maps\types\LatLng;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use app\widgets\maps\LeafletMapAsset;
use app\widgets\maps\LeafletMap;
use app\widgets\maps\plugins\leaflet_measure\LeafletMeasureAsset;
use app\widgets\maps\LeafletDrawAsset;

LeafletMapAsset::register($this);
LeafletDrawAsset::register($this);
LeafletMeasureAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\capnuocgd\GdHamkythuat */
/* @var $form yii\widgets\ActiveForm */

$requestedAction = Yii::$app->requestedAction;
$controller = $requestedAction->controller;
$label = $controller->label;

$this->title = Yii::t('app', $label[$requestedAction->id] . ' ' . $controller->title);
$this->params['breadcrumbs'][] = ['label' => $label['search'] . ' ' . $controller->title, 'url' => $controller->url];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="gd-hamkythuat-form">

    <div class="block block-themed">

        <div class="block-header">
            <h2 class="block-title"><?= $this->title ?></h2>
        </div>
        <div class="block-content">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row pb-2">
                <div class="col-lg-12">
                    <div id="map" style="height: 500px"></div>
                    <?= Html::hiddenInput('GdHamkythuat[geojson]', $model->geojson, ['id' => 'geojson']) ?>

                    
                    <script>
                        // center of the map
                        var center = [10.804291919691535, 106.69527258767485];

                        // Create the map
                        var map = L.map('map').setView(center, 14);

                        L.tileLayer('http://{s}.google.com/vt/lyrs=' + 'r' + '&x={x}&y={y}&z={z}', {
                            maxZoom: 24,
                            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                        }).addTo(map);

                        var baseMaps = {
                                "Bản đồ Google": L.tileLayer('http://{s}.google.com/vt/lyrs=' + 'r' + '&x={x}&y={y}&z={z}', {
                                    maxZoom: 24,
                                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                                }),
                                "Ảnh vệ tinh": L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                                    maxZoom: 24,
                                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                                }),
                                // "MapBox": L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoic2thZGFtYmkiLCJhIjoiY2lqdndsZGg3MGNua3U1bTVmcnRqM2xvbiJ9.9I5ggqzhUVrErEQ328syYQ#3/0.00/0.00', {
                                //     maxZoom: 18,
                                //     attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                                //     id: 'streets-v9',
                                // }),
                                // "OpenStreetMap": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                //     attribution: '© <a href="https://www.openstreetmap.org" target="_blank">OpenStreetMap</a>',
                                //     maxZoom: 18
                                // }),
                        };
// Thêm lớp L.Control.Locate
                        // var locateControl = new L.Control.Locate({
                        //     position: 'bottomleft',
                        //     strings: {
                        //         title: "Hiện vị trí",
                        //         popup: "Bạn đang ở đây"
                        //     },
                        //     drawCircle: true,
                        //     follow: true,
                        // });

                        // Thêm lớp locateControl vào bản đồ
                        // map.addControl(locateControl);

                        console.log(map);

                        var layerControl = L.control.layers(baseMaps).addTo(map);
                        // add a marker in the given location
                        //L.marker(center).addTo(map);

                        // Initialise the FeatureGroup to store editable layers
                        var editableLayers = new L.FeatureGroup();
                        map.addLayer(editableLayers);   

                        var drawPluginOptions = {
                            position: 'topleft',
                            draw: {
                                polygon: {
                                    allowIntersection: false, // Restricts shapes to simple polygons
                                    drawError: {
                                        color: '#e1e100', // Color the shape will turn when intersects
                                        message: '<strong>Oh không!<strong> bạn ko thể vẽ ở đó!' // Message that will show when intersect
                                    },
                                    shapeOptions: {
                                        color: '#97009c'
                                    }
                                },
                                // disable toolbar item by setting it to false
                                polyline: false,
                                circle: false, // Turns off this drawing tool
                                circlemarker: false, // Turns off this drawing tool
                                rectangle: true,
                                marker: false,
                            },
                            edit: {
                                featureGroup: editableLayers, //REQUIRED!!
                                remove: true,
                                edit: true,
                            }
                        };


                        // Initialise the draw control and pass it the FeatureGroup of editable layers
                        var drawControl = new L.Control.Draw(drawPluginOptions);
                        map.addControl(drawControl);

                        var dataMap = []; 

                        <?php if($model->geojson != null) :?>

                            var geojsonData = <?= $model->geojson ?>;
                            jsonData = geojsonData.coordinates;
                            type =  geojsonData.type;
                            //console.log(type);

                            var states = [];

                            if(type == 'MultiPolygon'){
                                for (var i = 0; i < jsonData.length; i++) {
                                    states.push({
                                        "type": "Feature",
                                        "properties": {
                                            "": ""
                                        },
                                        "geometry": {
                                            "type": "Polygon",
                                            "coordinates": [
                                                jsonData[i][0]
                                            ]
                                        }
                                    });
                                }
                            }
                            else{
                                states.push({
                                        "type": "Feature",
                                        "properties": {
                                            "": ""
                                        },
                                        "geometry": {
                                            "type": "Polygon",
                                            "coordinates": [
                                                jsonData[0]
                                            ]
                                        }
                                    });
                            }
                            

                            //console.log(states);

                            L.geoJSON(states, {
                                onEachFeature: function (feature, layer) {
                                    //L.polygon(layer.getLatLngs()).addTo(editableLayers);
                                    editableLayers.addLayer(layer);
                                    //console.log(layer.toGeoJSON().geometry.coordinates);
                                    data = layer.toGeoJSON().geometry.coordinates;
                                    dataMap[layer._leaflet_id] = data;
                                }
                            });
                        
                            console.log('Editable Layers Count:', editableLayers.getLayers().length);

                            var bounds = editableLayers.getBounds()
                            console.log(bounds);

                            if(bounds.isValid() === true) {
                                //Fit the map to the polygon bounds
                                map.fitBounds(bounds)



                                // Or center on the polygon
                                var centerstates = bounds.getCenter()
                                map.panTo(centerstates)
                            }
                        <?php endif;?>


                        //var editableLayers = new L.FeatureGroup();
                        map.addLayer(editableLayers);

                        map.on('draw:created', function (e) {
                
                            var type = e.layerType,
                                layer = e.layer;
                            
                            editableLayers.addLayer(layer);

                            data = layer.toGeoJSON().geometry.coordinates;

                            dataMap[layer._leaflet_id] = data;

                            console.log(dataMap);

                            //console.log(JSON.stringify(Object.assign({}, dataMap)));

                            $('#geojson').val(JSON.stringify(Object.assign({}, dataMap)));
                        });

                        map.on('draw:edited', function (e) {
                            var layers = e.layers;

                            console.log(layers);
                            
                            layers.eachLayer(function (layer) {
                                data = layer.toGeoJSON().geometry.coordinates;
                                dataMap[layer._leaflet_id] = data;
                                $('#geojson').val(JSON.stringify(Object.assign({}, dataMap)));
                            });
                        });

                        map.on('draw:deleted', function (e) {
                            var layers = e.layers;

                            layers.eachLayer(function (layer) {
                                //data = layer.toGeoJSON().geometry.coordinates;
                                //dataMap =  dataMap.filter(item => item !== data);
                                //dataMap.splice((layer._leaflet_id), 1);
                                dataMap[layer._leaflet_id] = undefined;
                                console.log(dataMap);
                            });

                            $('#geojson').val(JSON.stringify(Object.assign({}, dataMap)));
                        });


                        
                    </script>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'idhamkythu')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'loaiham')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'tenhamkyth')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'namlapdat')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'kichthuoch')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'tinhtrangh')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'donviquanl')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'soluongnap')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'vatlieunap')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'madma')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'dosau')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'docao')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'shape_leng')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'shape_area')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'donvithiet')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'donvithico')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9">
                    <?= $form->field($model, 'ghichu')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'globalid')->textInput(['maxlength' => true]) ?>
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
