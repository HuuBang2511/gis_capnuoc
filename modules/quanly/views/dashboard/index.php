<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

// Đăng ký tài nguyên
$this->registerJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['position' => \yii\web\View::POS_HEAD]);
?>

<style>
    .dashboard-container {
        background-color: #f8f9fa;
        padding: 20px;
        min-height: 100vh;
    }
    .block-themed {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 10px;
        overflow: hidden;
    }
    .block-themed:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .chart-container {
        height: 300px;
        width: 100%;
    }
    #map {
        height: 400px;
        border-radius: 10px;
    }
</style>

<div class="dashboard-container">
    <h1 class="mb-4 text-primary">Bảng Điều Khiển Cấp Thoát Nước</h1>

    <!-- Thẻ Thống Kê -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="block block-themed stat-card text-center">
                <div class="block-header bg-primary-dark d-flex align-items-center justify-content-center">
                    <i class="fas fa-tint text-white fa-2x me-2"></i>
                    <h3 class="block-title fs-4 fw-bold text-white">Van phân phối</h3>
                </div>
                <div class="block-content p-4">
                    <div class="fs-1 fw-bold text-primary"><?= $thongke['van_mangluoi'] ?></div>
                    <a href="<?= Url::to(['capnuocgd/gd-vanphanphoi/index']) ?>" class="btn btn-outline-primary mt-2">Xem chi tiết</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-themed stat-card text-center">
                <div class="block-header bg-success d-flex align-items-center justify-content-center">
                    <i class="fas fa-gauge text-white fa-2x me-2"></i>
                    <h3 class="block-title fs-4 fw-bold text-white">Đồng hồ tổng</h3>
                </div>
                <div class="block-content p-4">
                    <div class="fs-1 fw-bold text-success"><?= $thongke['nhamay_nuoc'] ?></div>
                    <a href="<?= Url::to(['capnuocgd/gd-dongho-tong-gd/index']) ?>" class="btn btn-outline-success mt-2">Xem chi tiết</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-themed stat-card text-center">
                <div class="block-header bg-warning d-flex align-items-center justify-content-center">
                    <i class="fas fa-gauge text-white fa-2x me-2"></i>
                    <h3 class="block-title fs-4 fw-bold text-white">Đồng hồ Khách hàng</h3>
                </div>
                <div class="block-content p-4">
                    <div class="fs-1 fw-bold text-warning"><?= $thongke['dongho_kh'] ?></div>
                    <a href="<?= Url::to(['capnuocgd/gd-dongho-kh-gd/index']) ?>" class="btn btn-outline-warning mt-2">Xem chi tiết</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-themed stat-card text-center">
                <div class="block-header bg-info d-flex align-items-center justify-content-center">
                    <i class="fas fa-road text-white fa-2x me-2"></i>
                    <h3 class="block-title fs-4 fw-bold text-white">Số Km Ống cái</h3>
                </div>
                <div class="block-content p-4">
                    <div class="fs-1 fw-bold text-info"><?= $thongke['ong_phanphoi'] ?></div>
                    <a href="<?= Url::to(['capnuocgd/gd-ongnganh/index']) ?>" class="btn btn-outline-info mt-2">Xem chi tiết</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-themed stat-card text-center">
                <div class="block-header bg-danger d-flex align-items-center justify-content-center">
                    <i class="fas fa-exclamation-triangle text-white fa-2x me-2"></i>
                    <h3 class="block-title fs-4 fw-bold text-white">Sự cố điểm bể</h3>
                </div>
                <div class="block-content p-4">
                    <div class="fs-1 fw-bold text-danger"><?= $thongke['suco'] ?></div>
                    <a href="<?= Url::to(['capnuocgd/gd-suco/index']) ?>" class="btn btn-outline-danger mt-2">Xem chi tiết</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-themed stat-card text-center">
                <div class="block-header bg-secondary d-flex align-items-center justify-content-center">
                    <i class="fas fa-fire-extinguisher text-white fa-2x me-2"></i>
                    <h3 class="block-title fs-4 fw-bold text-white">Trụ PCCC</h3>
                </div>
                <div class="block-content p-4">
                    <div class="fs-1 fw-bold text-secondary"><?= $thongke['pccc'] ?></div>
                    <a href="<?= Url::to(['capnuocgd/gd-tramcuuhoa/index']) ?>" class="btn btn-outline-secondary mt-2">Xem chi tiết</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-themed stat-card text-center">
                <div class="block-header bg-primary d-flex align-items-center justify-content-center">
                    <i class="fas fa-industry text-white fa-2x me-2"></i>
                    <h3 class="block-title fs-4 fw-bold text-white">Trạm bơm</h3>
                </div>
                <div class="block-content p-4">
                    <div class="fs-1 fw-bold text-primary"><?= $thongke['trambom'] ?></div>
                    <a href="<?= Url::to(['capnuocgd/gd-trambom/index']) ?>" class="btn btn-outline-primary mt-2">Xem chi tiết</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-themed stat-card text-center">
                <div class="block-header bg-dark d-flex align-items-center justify-content-center">
                    <i class="fas fa-wrench text-white fa-2x me-2"></i>
                    <h3 class="block-title fs-4 fw-bold text-white">Hầm kỹ thuật</h3>
                </div>
                <div class="block-content p-4">
                    <div class="fs-1 fw-bold text-dark"><?= $thongke['ham'] ?></div>
                    <a href="<?= Url::to(['capnuocgd/gd-hamkythuat/index']) ?>" class="btn btn-outline-dark mt-2">Xem chi tiết</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bản đồ GIS -->
    
    <div id="chartdiv"></div>
    <!-- Biểu đồ -->
    <div class="row mt-4">
        <div class="col-12 col-xl-6">
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Tiêu thụ Nước Hàng Tháng</h3>
                </div>
                <div class="block-content">
                    <canvas id="water-consumption-chart" class="chart-container"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="block block-themed">
                <div class="block-header bg-danger">
                    <h3 class="block-title">Sự cố Rò rỉ Theo Thời Gian</h3>
                </div>
                <div class="block-content">
                    <canvas id="leak-incidents-chart" class="chart-container"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
#chartdiv {
  max-width: 100%;
  height: 800px;
  background-color:#212327;
}
</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/maps.js"></script>

<!-- Chart code -->
<script>
am4internal_webpackJsonp(
    ["4f3f"], {
        hPIJ: function(e, t, o) {
            "use strict";
            Object.defineProperty(t, "__esModule", { value: !0 });
            var a = {
                type: "FeatureCollection",
                features: 
                [
                    <?php foreach($dataMap as $i => $item): ?>
                        {
                            type: "Feature",
                            geometry: {
                                type: "MultiPolygon",
                                coordinates: [[<?=$item['coordinates'] ?>]],
                            },
                            properties: {
                                name: "<?= $item['name'] ?>",
                                id: "<?= $item['id'] ?>",
                                CNTRY: "Việt Nam",
                                TYPE: "State",
                            },
                            id: "<?= $item['id'] ?>",
                        },
                    <?php endforeach; ?>
                ]

            
            }
            window.am4geodata_usaLow = a;
        },
    }, ["hPIJ"]
);

</script>

<script>


</script>

<script src="<?= Yii::$app->homeUrl ?>js/data.js"></script>
<script src="<?= Yii::$app->homeUrl ?>js/data_total.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

am4core.ready(function() {
  var populations = {
    'QN2501': 3195153,
    'QN2502': 5777373,
    'QN2506': 1805832,
    'QN2505': 8517685,
    'QN2504': 3161105,
    'QN2503': 3161105,
  }


  // var data_timeline = [{
  //         date: "2024-05-22",
  //         list: [
  //             { id: "QN2501", name: "Area 1", waterSupply: 100000, waterLoss: 5000 },
  //             { id: "QN2502", name: "Area 2", waterSupply: 200000, waterLoss: 10000 },
  //             { id: "QN2503", name: "Area 3", waterSupply: 150000, waterLoss: 7500 },
  //             { id: "QN2504", name: "Area 4", waterSupply: 300000, waterLoss: 15000 },
  //             { id: "QN2505", name: "Area 5", waterSupply: 400000, waterLoss: 20000 },
  //             { id: "QN2506", name: "Area 6", waterSupply: 250000, waterLoss: 12500 },
  //         ],
  //     },
  //     {
  //         date: "2024-05-23",
  //         list: [
  //             { id: "QN2501", name: "Area 1", waterSupply: 200000, waterLoss: 10000 },
  //             { id: "QN2502", name: "Area 2", waterSupply: 400000, waterLoss: 20000 },
  //             { id: "QN2503", name: "Area 3", waterSupply: 300000, waterLoss: 15000 },
  //             { id: "QN2504", name: "Area 4", waterSupply: 600000, waterLoss: 30000 },
  //             { id: "QN2505", name: "Area 5", waterSupply: 800000, waterLoss: 40000 },
  //             { id: "QN2506", name: "Area 6", waterSupply: 500000, waterLoss: 25000 },
  //         ],
  //     },
  //     {
  //         date: "2024-05-24",
  //         list: [
  //             { id: "QN2501", name: "Area 1", waterSupply: 192422, waterLoss: 9621 },
  //             { id: "QN2502", name: "Area 2", waterSupply: 417455, waterLoss: 20873 },
  //             { id: "QN2503", name: "Area 3", waterSupply: 289707, waterLoss: 14485 },
  //             { id: "QN2504", name: "Area 4", waterSupply: 640554, waterLoss: 32028 },
  //             { id: "QN2505", name: "Area 5", waterSupply: 877450, waterLoss: 43873 },
  //             { id: "QN2506", name: "Area 6", waterSupply: 521597, waterLoss: 26080 },
  //         ],
  //     },
  // ];

  console.log(data_timeline)

  // var data_total_timeline = [
  //     { date: "2024-05-22", waterSupply: 1400000, waterLoss: 70000 },
  //     { date: "2024-05-23", waterSupply: 2800000, waterLoss: 140000 },
  //     { date: "2024-05-24", waterSupply: 2655480, waterLoss: 132774 },

  // ];

  var numberFormatter = new am4core.NumberFormatter();

  var backgroundColor = am4core.color("#1e2128");
  var waterSupplyColor = am4core.color("#d21a1a");
  var waterLossColor = am4core.color("#1c5fe5");
  var colors = { waterSupply: waterSupplyColor, waterLoss: waterLossColor };
  var countryColor = am4core.color("#3b3b3b");
  var countryStrokeColor = am4core.color("#000000");
  var buttonStrokeColor = am4core.color("#ffffff");
  var countryHoverColor = am4core.color("#1b1b1b");
  var activeCountryColor = am4core.color("#0f0f0f");

  // Variables
  var currentIndex;
  var currentCountry = "Cấp nước (total)";
  var lastDate = new Date(data_total_timeline[data_total_timeline.length - 1].date);
  var currentDate = lastDate;
  var currentPolygon;
  var countryDataTimeout;
  var currentType = "waterSupply";
  var currentTypeName = "Sản lượng nước sạch";
  var sliderAnimation;
  var perCapita = false;

  // Prepare data
  var countryIndexMap = {};
  var list = data_timeline[data_timeline.length - 1].list;
  for (var i = 0; i < list.length; i++) {
    var country = list[i];
    countryIndexMap[country.id] = i;
  }

  function getSlideData(index) {
    if (index == undefined) {
      index = data_timeline.length - 1;
    }
    return data_timeline[index];
  }

  var slideData = getSlideData();
  var mapData = JSON.parse(JSON.stringify(slideData.list));

  for (var i = mapData.length - 1; i >= 0; i--) {
    if (mapData[i].waterSupply == 0) {
      mapData.splice(i, 1);
    }
  }

  var max = { waterSupply: 0, waterLoss: 0 };
  var maxPC = { waterSupply: 0, waterLoss: 0 };

  for (var i = 0; i < mapData.length; i++) {
    var di = mapData[i];
    if (di.waterSupply > max.waterSupply) {
      max.waterSupply = di.waterSupply;
    }
    if (di.waterLoss > max.waterLoss) {
      max.waterLoss = di.waterLoss;
    }
  }

  // Main container
  var container = am4core.create("chartdiv", am4core.Container);
  container.width = am4core.percent(100);
  container.height = am4core.percent(100);
  container.fontSize = "0.9em";

  container.tooltip = new am4core.Tooltip();
  container.tooltip.background.fill = am4core.color("#000000");
  container.tooltip.background.stroke = waterSupplyColor;
  container.tooltip.fontSize = "0.9em";
  container.tooltip.getFillFromObject = false;
  container.tooltip.getStrokeFromObject = false;

  // Map chart
  var mapChart = container.createChild(am4maps.MapChart);
  mapChart.height = am4core.percent(60);
  mapChart.zoomControl = new am4maps.ZoomControl();
  mapChart.zoomControl.align = "right";
  mapChart.zoomControl.marginRight = 15;
  mapChart.zoomControl.valign = "middle";

  mapChart.zoomControl.minusButton.events.on("hit", showWorld);
  mapChart.seriesContainer.background.events.on("hit", showWorld);
  mapChart.seriesContainer.background.events.on("over", resetHover);
  mapChart.seriesContainer.background.fillOpacity = 0;
  mapChart.zoomEasing = am4core.ease.sinOut;

  mapChart.geodata = am4geodata_usaLow; // Thay bằng dữ liệu địa lý phù hợp
  mapChart.projection = new am4maps.projections.Mercator();
  mapChart.panBehavior = "move";
  mapChart.deltaLongitude = -10;

  // Polygon series
  var polygonSeries = mapChart.series.push(new am4maps.MapPolygonSeries());
  polygonSeries.dataFields.id = "id";
  polygonSeries.dataFields.value = "confirmedPC";
  polygonSeries.interpolationDuration = 0;
  polygonSeries.useGeodata = true;
  polygonSeries.nonScalingStroke = true;
  polygonSeries.strokeWidth = 0.5;
  polygonSeries.calculateVisualCenter = true;
  polygonSeries.data = mapData;

  var polygonTemplate = polygonSeries.mapPolygons.template;
  polygonTemplate.fill = countryColor;
  polygonTemplate.fillOpacity = 1;
  polygonTemplate.stroke = countryStrokeColor;
  polygonTemplate.strokeOpacity = 0.15;
  polygonTemplate.setStateOnChildren = true;
  polygonTemplate.tooltipPosition = "fixed";

  polygonTemplate.events.on("hit", handleCountryHit);
  polygonTemplate.events.on("over", handleCountryOver);
  polygonTemplate.events.on("out", handleCountryOut);

  polygonSeries.heatRules.push({
    target: polygonTemplate,
    property: "fill",
    min: countryColor,
    max: countryColor,
    dataField: "value",
  });

  var polygonHoverState = polygonTemplate.states.create("hover");
  polygonHoverState.transitionDuration = 1400;
  polygonHoverState.properties.fill = countryHoverColor;

  var polygonActiveState = polygonTemplate.states.create("active");
  polygonActiveState.properties.fill = activeCountryColor;

  // Bubble series
  var bubbleSeries = mapChart.series.push(new am4maps.MapImageSeries());
  bubbleSeries.data = JSON.parse(JSON.stringify(mapData));
  bubbleSeries.dataFields.value = "confirmed";
  bubbleSeries.dataFields.id = "id";

  bubbleSeries.tooltip.animationDuration = 0;
  bubbleSeries.tooltip.showInViewport = false;
  bubbleSeries.tooltip.background.fillOpacity = 0.2;
  bubbleSeries.tooltip.getStrokeFromObject = true;
  bubbleSeries.tooltip.getFillFromObject = false;
  bubbleSeries.tooltip.background.fill = am4core.color("#000000");

  var imageTemplate = bubbleSeries.mapImages.template;
  imageTemplate.nonScaling = true;
  imageTemplate.strokeOpacity = 0;
  imageTemplate.fillOpacity = 0.55;
  imageTemplate.tooltipText = "{name}: [bold]{value}[/]";
  imageTemplate.applyOnClones = true;

  imageTemplate.events.on("over", handleImageOver);
  imageTemplate.events.on("out", handleImageOut);
  imageTemplate.events.on("hit", handleImageHit);

  imageTemplate.adapter.add("tooltipY", function(tooltipY, target) {
    return -target.children.getIndex(0).radius;
  });

  var imageHoverState = imageTemplate.states.create("hover");
  imageHoverState.properties.fillOpacity = 1;

  var circle = imageTemplate.createChild(am4core.Circle);
  circle.hiddenState.properties.scale = 0.0001;
  circle.hiddenState.transitionDuration = 2000;
  circle.defaultState.transitionDuration = 2000;
  circle.defaultState.transitionEasing = am4core.ease.elasticOut;
  circle.applyOnClones = true;

  bubbleSeries.heatRules.push({
    target: circle,
    property: "radius",
    min: 3,
    max: 30,
    dataField: "value",
  });

  bubbleSeries.events.on("dataitemsvalidated", function() {
    bubbleSeries.dataItems.each((dataItem) => {
      var mapImage = dataItem.mapImage;
      var circle = mapImage.children.getIndex(0);
      if (mapImage.dataItem.value == 0) {
        circle.hide(0);
      } else if (circle.isHidden || circle.isHiding) {
        circle.show();
      }
    });
  });

  imageTemplate.adapter.add("latitude", function(latitude, target) {
    var polygon = polygonSeries.getPolygonById(target.dataItem.id);
    if (polygon) {
      target.disabled = false;
      return polygon.visualLatitude;
    } else {
      target.disabled = true;
    }
    return latitude;
  });

  imageTemplate.adapter.add("longitude", function(longitude, target) {
    var polygon = polygonSeries.getPolygonById(target.dataItem.id);
    if (polygon) {
      target.disabled = false;
      return polygon.visualLongitude;
    } else {
      target.disabled = true;
    }
    return longitude;
  });

  // Title
  var title = mapChart.titles.create();
  title.fontSize = "1.5em";
  title.text = "Bản đồ cấp nước";
  title.align = "left";
  title.horizontalCenter = "left";
  title.marginLeft = 20;
  title.paddingBottom = 10;
  title.fill = am4core.color("#ffffff");
  title.y = 20;

  // Switch button
  var absolutePerCapitaSwitch = mapChart.createChild(am4core.SwitchButton);
  absolutePerCapitaSwitch.align = "center";
  absolutePerCapitaSwitch.y = 15;
  absolutePerCapitaSwitch.leftLabel.text = "Tổng lượng";
  absolutePerCapitaSwitch.leftLabel.fill = am4core.color("#ffffff");
  absolutePerCapitaSwitch.rightLabel.fill = am4core.color("#ffffff");
  absolutePerCapitaSwitch.rightLabel.text = "Bình quân đầu người";
  absolutePerCapitaSwitch.rightLabel.interactionsEnabled = true;
  absolutePerCapitaSwitch.rightLabel.tooltipText = "Số liệu bình quân đầu người được tính dựa trên dân số của từng khu vực.";
  absolutePerCapitaSwitch.verticalCenter = "top";

  absolutePerCapitaSwitch.events.on("toggled", function() {
    if (absolutePerCapitaSwitch.isActive) {
      bubbleSeries.hide(0);
      perCapita = true;
      bubbleSeries.interpolationDuration = 0;
      polygonSeries.heatRules.getIndex(0).max = colors[currentType];
      polygonSeries.heatRules.getIndex(0).maxValue = maxPC[currentType];
      polygonSeries.mapPolygons.template.applyOnClones = true;

      sizeSlider.hide();
      filterSlider.hide();
      sizeLabel.hide();
      filterLabel.hide();

      updateCountryTooltip();
    } else {
      perCapita = false;
      polygonSeries.interpolationDuration = 0;
      bubbleSeries.interpolationDuration = 1000;
      bubbleSeries.show();
      polygonSeries.heatRules.getIndex(0).max = countryColor;
      polygonSeries.mapPolygons.template.tooltipText = undefined;
      sizeSlider.show();
      filterSlider.show();
      sizeLabel.show();
      filterLabel.show();
    }
    polygonSeries.mapPolygons.each(function(mapPolygon) {
      mapPolygon.fill = mapPolygon.fill;
      mapPolygon.defaultState.properties.fill = undefined;
    });
  });

  // Buttons and chart container
  var buttonsAndChartContainer = container.createChild(am4core.Container);
  buttonsAndChartContainer.layout = "vertical";
  buttonsAndChartContainer.height = am4core.percent(45);
  buttonsAndChartContainer.width = am4core.percent(100);
  buttonsAndChartContainer.valign = "bottom";

  var nameAndButtonsContainer = buttonsAndChartContainer.createChild(am4core.Container);
  nameAndButtonsContainer.width = am4core.percent(100);
  nameAndButtonsContainer.padding(0, 10, 5, 20);
  nameAndButtonsContainer.layout = "horizontal";

  var countryName = nameAndButtonsContainer.createChild(am4core.Label);
  countryName.fontSize = "1.1em";
  countryName.fill = am4core.color("#ffffff");
  countryName.valign = "middle";

  var buttonsContainer = nameAndButtonsContainer.createChild(am4core.Container);
  buttonsContainer.layout = "grid";
  buttonsContainer.width = am4core.percent(100);
  buttonsContainer.x = 10;
  buttonsContainer.contentAlign = "right";

  var chartAndSliderContainer = buttonsAndChartContainer.createChild(am4core.Container);
  chartAndSliderContainer.layout = "vertical";
  chartAndSliderContainer.height = am4core.percent(100);
  chartAndSliderContainer.width = am4core.percent(100);
  chartAndSliderContainer.background = new am4core.RoundedRectangle();
  chartAndSliderContainer.background.fill = am4core.color("#000000");
  chartAndSliderContainer.background.cornerRadius(30, 30, 0, 0);
  chartAndSliderContainer.background.fillOpacity = 0.25;
  chartAndSliderContainer.paddingTop = 12;
  chartAndSliderContainer.paddingBottom = 0;

  var sliderContainer = chartAndSliderContainer.createChild(am4core.Container);
  sliderContainer.width = am4core.percent(100);
  sliderContainer.padding(0, 15, 15, 10);
  sliderContainer.layout = "horizontal";

  var slider = sliderContainer.createChild(am4core.Slider);
  slider.width = am4core.percent(100);
  slider.valign = "middle";
  slider.background.opacity = 0.4;
  slider.opacity = 0.7;
  slider.background.fill = am4core.color("#ffffff");
  slider.marginLeft = 20;
  slider.marginRight = 35;
  slider.height = 15;
  slider.start = 1;

  slider.events.on("rangechanged", function(event) {
    var index = Math.round((data_timeline.length - 1) * slider.start);
    updateMapData(getSlideData(index).list);
    updateTotals(index);
  });

  slider.startGrip.events.on("drag", () => {
    stop();
    if (sliderAnimation) {
      sliderAnimation.setProgress(slider.start);
    }
  });

  var playButton = sliderContainer.createChild(am4core.PlayButton);
  playButton.valign = "middle";
  playButton.events.on("toggled", function(event) {
    if (event.target.isActive) {
      play();
    } else {
      stop();
    }
  });

  slider.startGrip.background.fill = playButton.background.fill;
  slider.startGrip.background.strokeOpacity = 0;
  slider.startGrip.icon.stroke = am4core.color("#ffffff");
  slider.startGrip.background.states.copyFrom(playButton.background.states);

  var sizeSlider = container.createChild(am4core.Slider);
  sizeSlider.orientation = "vertical";
  sizeSlider.height = am4core.percent(12);
  sizeSlider.marginLeft = 25;
  sizeSlider.align = "left";
  sizeSlider.valign = "top";
  sizeSlider.verticalCenter = "middle";
  sizeSlider.opacity = 0.7;
  sizeSlider.start = 0.5;
  sizeSlider.background.fill = am4core.color("#ffffff");
  sizeSlider.adapter.add("y", function(y, target) {
    return container.pixelHeight * (1 - buttonsAndChartContainer.percentHeight / 100) * 0.25;
  });

  sizeSlider.startGrip.background.fill = waterSupplyColor;
  sizeSlider.startGrip.background.fillOpacity = 0.8;
  sizeSlider.startGrip.background.strokeOpacity = 0;
  sizeSlider.startGrip.icon.stroke = am4core.color("#ffffff");
  sizeSlider.startGrip.background.states.getKey("hover").properties.fill = waterSupplyColor;
  sizeSlider.startGrip.background.states.getKey("down").properties.fill = waterSupplyColor;
  sizeSlider.horizontalCenter = "middle";

  sizeSlider.events.on("rangechanged", function() {
    sizeSlider.startGrip.scale = 0.75 + sizeSlider.start;
    bubbleSeries.heatRules.getIndex(0).max = 30 + sizeSlider.start * 100;
    circle.clones.each(function(clone) {
      clone.radius = clone.radius;
    });
  });

  var sizeLabel = container.createChild(am4core.Label);
  sizeLabel.text = "Kích thước tối đa của bong bóng *";
  sizeLabel.fill = am4core.color("#ffffff");
  sizeLabel.rotation = 90;
  sizeLabel.fontSize = "10px";
  sizeLabel.fillOpacity = 0.5;
  sizeLabel.horizontalCenter = "middle";
  sizeLabel.align = "left";
  sizeLabel.paddingBottom = 40;
  sizeLabel.tooltip.setBounds({ x: 0, y: 0, width: 200000, height: 200000 });
  sizeLabel.tooltip.label.wrap = true;
  sizeLabel.tooltip.label.maxWidth = 300;
  sizeLabel.tooltipText = "Một số khu vực có sản lượng nước lớn, khiến các khu vực có sản lượng nhỏ trông tương tự nhau. Thanh trượt này giúp tăng kích thước bong bóng để so sánh dễ dàng hơn.";
  sizeLabel.fill = am4core.color("#ffffff");

  sizeLabel.adapter.add("y", function(y, target) {
    return container.pixelHeight * (1 - buttonsAndChartContainer.percentHeight / 100) * 0.25;
  });

  var filterSlider = container.createChild(am4core.Slider);
  filterSlider.orientation = "vertical";
  filterSlider.height = am4core.percent(28);
  filterSlider.marginLeft = 25;
  filterSlider.align = "left";
  filterSlider.valign = "top";
  filterSlider.verticalCenter = "middle";
  filterSlider.opacity = 0.7;
  filterSlider.background.fill = am4core.color("#ffffff");
  filterSlider.adapter.add("y", function(y, target) {
    return container.pixelHeight * (1 - buttonsAndChartContainer.percentHeight / 100) * 0.7;
  });

  filterSlider.startGrip.background.fill = waterSupplyColor;
  filterSlider.startGrip.background.fillOpacity = 0.8;
  filterSlider.startGrip.background.strokeOpacity = 0;
  filterSlider.startGrip.icon.stroke = am4core.color("#ffffff");
  filterSlider.startGrip.background.states.getKey("hover").properties.fill = waterSupplyColor;
  filterSlider.startGrip.background.states.getKey("down").properties.fill = waterSupplyColor;
  filterSlider.horizontalCenter = "middle";
  filterSlider.start = 1;

  filterSlider.events.on("rangechanged", function() {
    var maxValue = max[currentType] * filterSlider.start + 1;
    if (!isNaN(maxValue) && bubbleSeries.inited) {
      bubbleSeries.heatRules.getIndex(0).maxValue = maxValue;
      circle.clones.each(function(clone) {
        if (clone.dataItem.value > maxValue) {
          clone.dataItem.hide();
        } else {
          clone.dataItem.show();
        }
        clone.radius = clone.radius;
      });
    }
  });

  var filterLabel = container.createChild(am4core.Label);
  filterLabel.text = "Lọc giá trị tối đa *";
  filterLabel.rotation = 90;
  filterLabel.fontSize = "10px";
  filterLabel.fill = am4core.color("#ffffff");
  filterLabel.fontSize = "0.8em";
  filterLabel.fillOpacity = 0.5;
  filterLabel.horizontalCenter = "middle";
  filterLabel.align = "left";
  filterLabel.paddingBottom = 40;
  filterLabel.tooltip.label.wrap = true;
  filterLabel.tooltip.label.maxWidth = 300;
  filterLabel.tooltipText = "Thanh trượt này cho phép loại bỏ các khu vực có sản lượng lớn để so sánh các khu vực có sản lượng nhỏ hơn.";
  filterLabel.fill = am4core.color("#ffffff");

  filterLabel.adapter.add("y", function(y, target) {
    return container.pixelHeight * (1 - buttonsAndChartContainer.percentHeight / 100) * 0.7;
  });

  function play() {
    if (!sliderAnimation) {
      sliderAnimation = slider.animate({ property: "start", to: 1, from: 0 }, 50000, am4core.ease.linear).pause();
      sliderAnimation.events.on("animationended", () => {
        playButton.isActive = false;
      });
    }
    if (slider.start >= 1) {
      slider.start = 0;
      sliderAnimation.start();
    }
    sliderAnimation.resume();
    playButton.isActive = true;
  }

  function stop() {
    if (sliderAnimation) {
      sliderAnimation.pause();
    }
    playButton.isActive = false;
  }

  var lineChart = chartAndSliderContainer.createChild(am4charts.XYChart);
  lineChart.fontSize = "0.8em";
  lineChart.paddingRight = 30;
  lineChart.paddingLeft = 30;
  lineChart.maskBullets = false;
  lineChart.zoomOutButton.disabled = true;
  lineChart.paddingBottom = 5;
  lineChart.paddingTop = 3;

  lineChart.data = JSON.parse(JSON.stringify(data_total_timeline));

  var dateAxis = lineChart.xAxes.push(new am4charts.DateAxis());
  dateAxis.renderer.minGridDistance = 50;
  dateAxis.renderer.grid.template.stroke = am4core.color("#000000");
  dateAxis.renderer.grid.template.strokeOpacity = 0.25;
  dateAxis.max = lastDate.getTime() + am4core.time.getDuration("day", 5);
  dateAxis.tooltip.label.fontSize = "0.8em";
  dateAxis.tooltip.background.fill = waterSupplyColor;
  dateAxis.tooltip.background.stroke = waterSupplyColor;
  dateAxis.renderer.labels.template.fill = am4core.color("#ffffff");

  var valueAxis = lineChart.yAxes.push(new am4charts.ValueAxis());
  valueAxis.renderer.opposite = true;
  valueAxis.interpolationDuration = 3000;
  valueAxis.renderer.grid.template.stroke = am4core.color("#000000");
  valueAxis.renderer.grid.template.strokeOpacity = 0.25;
  valueAxis.renderer.minGridDistance = 30;
  valueAxis.renderer.maxLabelPosition = 0.98;
  valueAxis.min = 0;
  valueAxis.tooltip.disabled = true;
  valueAxis.extraMax = 0.05;
  valueAxis.maxPrecision = 0;
  valueAxis.renderer.inside = true;
  valueAxis.renderer.labels.template.verticalCenter = "bottom";
  valueAxis.renderer.labels.template.fill = am4core.color("#ffffff");
  valueAxis.renderer.labels.template.padding(2, 2, 2, 2);
  valueAxis.adapter.add("max", function(max, target) {
    if (max < 5) {
      max = 5;
    }
    return max;
  });

  valueAxis.adapter.add("min", function(min, target) {
    if (!seriesTypeSwitch.isActive) {
      if (min < 0) {
        min = 0;
      }
    }
    return min;
  });

  lineChart.cursor = new am4charts.XYCursor();
  lineChart.cursor.maxTooltipDistance = 0;
  lineChart.cursor.behavior = "none";
  lineChart.cursor.lineY.disabled = true;
  lineChart.cursor.lineX.stroke = waterSupplyColor;
  lineChart.cursor.xAxis = dateAxis;

  am4core.getInteraction().body.events.off("down", lineChart.cursor.handleCursorDown, lineChart.cursor);
  am4core.getInteraction().body.events.off("up", lineChart.cursor.handleCursorUp, lineChart.cursor);

  lineChart.legend = new am4charts.Legend();
  lineChart.legend.parent = lineChart.plotContainer;
  lineChart.legend.labels.template.fill = am4core.color("#ffffff");
  lineChart.legend.markers.template.height = 8;
  lineChart.legend.contentAlign = "left";
  lineChart.legend.itemContainers.template.valign = "middle";
  var legendDown = false;
  lineChart.legend.itemContainers.template.events.on("down", function() {
    legendDown = true;
  });
  lineChart.legend.itemContainers.template.events.on("up", function() {
    setTimeout(function() {
      legendDown = false;
    }, 100);
  });

  var seriesTypeSwitch = lineChart.legend.createChild(am4core.SwitchButton);
  seriesTypeSwitch.leftLabel.text = "Tổng số";
  seriesTypeSwitch.rightLabel.text = "Thay đổi hàng ngày";
  seriesTypeSwitch.leftLabel.fill = am4core.color("#ffffff");
  seriesTypeSwitch.rightLabel.fill = am4core.color("#ffffff");

  seriesTypeSwitch.events.on("down", function() {
    legendDown = true;
  });
  seriesTypeSwitch.events.on("up", function() {
    setTimeout(function() {
      legendDown = false;
    }, 100);
  });

  seriesTypeSwitch.events.on("toggled", function() {
    if (seriesTypeSwitch.isActive) {
      if (!columnSeries) {
        createColumnSeries();
      }
      for (var key in columnSeries) {
        columnSeries[key].hide(0);
      }
      for (var key in series) {
        series[key].hiddenInLegend = true;
        series[key].hide();
      }
      columnSeries[currentType].show();
    } else {
      for (var key in columnSeries) {
        columnSeries[key].hiddenInLegend = true;
        columnSeries[key].hide();
      }
      for (var key in series) {
        series[key].hiddenInLegend = false;
        series[key].hide();
      }
      series[currentType].show();
    }
  });

  function updateColumnsFill() {
    if (columnSeries && columnSeries[currentType]) {
      columnSeries[currentType].columns.each(function(column) {
        if (column.dataItem.values.valueY.previousChange < 0) {
          column.fillOpacity = 0;
          column.strokeOpacity = 0.6;
        } else {
          column.fillOpacity = 0.6;
          column.strokeOpacity = 0;
        }
      });
    }
  }

  var waterSupplySeries = addSeries("waterSupply", waterSupplyColor);
  waterSupplySeries.tooltip.disabled = true;
  waterSupplySeries.hidden = false;
  var waterLossSeries = addSeries("waterLoss", waterLossColor);

  var series = { waterSupply: waterSupplySeries, waterLoss: waterLossSeries };

  function addSeries(name, color) {
    var series = lineChart.series.push(new am4charts.LineSeries());
    series.dataFields.valueY = name;
    series.dataFields.dateX = "date";
    series.name = name === "waterSupply" ? "Sản lượng nước" : "Tỷ lệ thất thoát";
    series.strokeOpacity = 0.6;
    series.stroke = color;
    series.fill = color;
    series.maskBullets = false;
    series.minBulletDistance = 10;
    series.hidden = true;
    series.hideTooltipWhileZooming = true;

    var bullet = series.bullets.push(new am4charts.CircleBullet());
    var bulletHoverState = bullet.states.create("hover");
    bullet.setStateOnChildren = true;
    bullet.circle.fillOpacity = 1;
    bullet.circle.fill = backgroundColor;
    bullet.circle.radius = 2;

    var circleHoverState = bullet.circle.states.create("hover");
    circleHoverState.properties.fillOpacity = 1;
    circleHoverState.properties.fill = color;
    circleHoverState.properties.scale = 1.4;

    series.tooltip.pointerOrientation = "down";
    series.tooltip.getStrokeFromObject = true;
    series.tooltip.getFillFromObject = false;
    series.tooltip.background.fillOpacity = 0.2;
    series.tooltip.background.fill = am4core.color("#000000");
    series.tooltip.dy = -4;
    series.tooltip.fontSize = "0.8em";
    series.tooltipText = "Total {name}: {valueY}";

    return series;
  }

  var columnSeries;

  function createColumnSeries() {
    columnSeries = {};
    columnSeries.waterSupply = addColumnSeries("waterSupply", waterSupplyColor);
    columnSeries.waterLoss = addColumnSeries("waterLoss", waterLossColor);
  }

  function addColumnSeries(name, color) {
    var series = lineChart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueY = name;
    series.dataFields.valueYShow = "previousChange";
    series.dataFields.dateX = "date";
    series.name = name === "waterSupply" ? "Sản lượng nước" : "Tỷ lệ thất thoát";
    series.hidden = true;
    series.stroke = color;
    series.fill = color;
    series.columns.template.fillOpacity = 0.6;
    series.columns.template.strokeOpacity = 0;
    series.hideTooltipWhileZooming = true;
    series.clustered = false;
    series.hiddenInLegend = true;
    series.columns.template.width = am4core.percent(50);

    series.tooltip.pointerOrientation = "down";
    series.tooltip.getStrokeFromObject = true;
    series.tooltip.getFillFromObject = false;
    series.tooltip.background.fillOpacity = 0.2;
    series.tooltip.background.fill = am4core.color("#000000");
    series.tooltip.fontSize = "0.8em";
    series.tooltipText = "{name}: {valueY.previousChange.formatNumber('+#,###|#,###|0')}";

    return series;
  }

  lineChart.plotContainer.events.on("up", function() {
    if (!legendDown) {
      slider.start = lineChart.cursor.xPosition * ((dateAxis.max - dateAxis.min) / (lastDate.getTime() - dateAxis.min));
    }
  });

  var label = lineChart.plotContainer.createChild(am4core.Label);
  label.text = "Dữ liệu ngày hiện tại có thể chưa đầy đủ.";
  label.fill = am4core.color("#ffffff");
  label.fontSize = "0.8em";
  label.paddingBottom = 4;
  label.opacity = 0.5;
  label.align = "right";
  label.horizontalCenter = "right";
  label.verticalCenter = "bottom";

  var waterSupplyButton = addButton("waterSupply", waterSupplyColor);
  var waterLossButton = addButton("waterLoss", waterLossColor);

  var buttons = { waterSupply: waterSupplyButton, waterLoss: waterLossButton };

  function addButton(name, color) {
    var button = buttonsContainer.createChild(am4core.Button);
    button.label.valign = "middle";
    button.label.fill = am4core.color("#ffffff");
    button.background.cornerRadius(30, 30, 30, 30);
    button.background.strokeOpacity = 0.3;
    button.background.fillOpacity = 0;
    button.background.stroke = buttonStrokeColor;
    button.background.padding(2, 3, 2, 3);
    button.states.create("active");
    button.setStateOnChildren = true;

    var activeHoverState = button.background.states.create("hoverActive");
    activeHoverState.properties.fillOpacity = 0;

    var circle = new am4core.Circle();
    circle.radius = 8;
    circle.fillOpacity = 0.3;
    circle.fill = buttonStrokeColor;
    circle.strokeOpacity = 0;
    circle.valign = "middle";
    circle.marginRight = 5;
    button.icon = circle;

    var circleActiveState = circle.states.create("active");
    circleActiveState.properties.fill = color;
    circleActiveState.properties.fillOpacity = 0.5;

    button.events.on("hit", handleButtonClick);
    button.dummyData = name;

    return button;
  }

  function handleButtonClick(event) {
    changeDataType(event.target.dummyData);
  }

  function changeDataType(name) {
    currentType = name;
    currentTypeName = name === "waterSupply" ? "Sản lượng nước sạch" : "Tỷ lệ thất thoát nước";

    bubbleSeries.mapImages.template.tooltipText = "[bold]{name}: {value}[/] [font-size:10px]\n" + currentTypeName;

    var activeButton = buttons[name];
    activeButton.isActive = true;
    for (var key in buttons) {
      if (buttons[key] != activeButton) {
        buttons[key].isActive = false;
      }
    }

    bubbleSeries.dataFields.value = name;
    polygonSeries.dataFields.value = name + "PC";

    bubbleSeries.dataItems.each(function(dataItem) {
      dataItem.setValue("value", dataItem.dataContext[currentType] || 0);
    });

    polygonSeries.dataItems.each(function(dataItem) {
      dataItem.setValue("value", dataItem.dataContext[currentType + "PC"] || 0);
      dataItem.mapPolygon.defaultState.properties.fill = undefined;
    });

    bubbleSeries.mapImages.template.fill = colors[name];
    bubbleSeries.mapImages.template.stroke = colors[name];
    bubbleSeries.mapImages.template.children.getIndex(0).fill = colors[name];

    dateAxis.tooltip.background.fill = colors[name];
    dateAxis.tooltip.background.stroke = colors[name];
    lineChart.cursor.lineX.stroke = colors[name];

    if (seriesTypeSwitch.isActive) {
      var currentSeries = columnSeries[name];
      currentSeries.show();
      for (var key in columnSeries) {
        if (columnSeries[key] != currentSeries) {
          columnSeries[key].hide();
        }
      }
    } else {
      var currentSeries = series[name];
      currentSeries.show();
      for (var key in series) {
        if (series[key] != currentSeries) {
          series[key].hide();
        }
      }
    }

    bubbleSeries.heatRules.getIndex(0).maxValue = max[currentType];
    polygonSeries.heatRules.getIndex(0).maxValue = maxPC[currentType];
    if (perCapita) {
      polygonSeries.heatRules.getIndex(0).max = colors[name];
      updateCountryTooltip();
    }
  }

  function selectCountry(mapPolygon) {
    resetHover();
    polygonSeries.hideTooltip();

    if (currentPolygon == mapPolygon) {
      currentPolygon.isActive = false;
      currentPolygon = undefined;
      showWorld();
      return;
    }

    currentPolygon = mapPolygon;
    var countryIndex = countryIndexMap[mapPolygon.dataItem.id];
    currentCountry = mapPolygon.dataItem.dataContext.name;

    polygonSeries.mapPolygons.each(function(polygon) {
      polygon.isActive = false;
    });

    if (countryDataTimeout) {
      clearTimeout(countryDataTimeout);
    }
    countryDataTimeout = setTimeout(function() {
      setCountryData(countryIndex);
    }, 1000);

    updateTotals(currentIndex);
    updateCountryName();

    mapPolygon.isActive = true;
    mapChart.zoomToMapObject(mapPolygon, getZoomLevel(mapPolygon));
  }

  function setCountryData(countryIndex) {
    for (var i = 0; i < lineChart.data.length; i++) {
      var di = data_timeline[i].list;
      var countryData = di[countryIndex];
      var dataContext = lineChart.data[i];
      if (countryData) {
        dataContext.waterSupply = countryData.waterSupply || 0;
        dataContext.waterLoss = countryData.waterLoss || 0;
        valueAxis.min = undefined;
        valueAxis.max = undefined;
      } else {
        dataContext.waterSupply = 0;
        dataContext.waterLoss = 0;
        valueAxis.min = 0;
        valueAxis.max = 10;
      }
    }

    lineChart.invalidateRawData();
    updateTotals(currentIndex);
    setTimeout(updateSeriesTooltip, 1000);
  }

  function updateSeriesTooltip() {
    var position = dateAxis.dateToPosition(currentDate);
    position = dateAxis.toGlobalPosition(position);
    var x = dateAxis.positionToCoordinate(position);

    lineChart.cursor.triggerMove({ x: x, y: 0 }, "soft", true);
    lineChart.series.each(function(series) {
      if (!series.isHidden) {
        series.tooltip.disabled = false;
        series.showTooltipAtDataItem(series.tooltipDataItem);
      }
    });
  }

  function rollOverCountry(mapPolygon) {
    resetHover();
    if (mapPolygon) {
      mapPolygon.isHover = true;
      var image = bubbleSeries.getImageById(mapPolygon.dataItem.id);
      if (image) {
        image.dataItem.dataContext.name = mapPolygon.dataItem.dataContext.name;
        image.isHover = true;
      }
    }
  }

  function rollOutCountry(mapPolygon) {
    var image = bubbleSeries.getImageById(mapPolygon.dataItem.id);
    resetHover();
    if (image) {
      image.isHover = false;
    }
  }

  function rotateAndZoom(mapPolygon) {
    polygonSeries.hideTooltip();
    var animation = mapChart.animate([{ property: "deltaLongitude", to: -mapPolygon.visualLongitude }, { property: "deltaLatitude", to: -mapPolygon.visualLatitude }], 1000);
    animation.events.on("animationended", function() {
      mapChart.zoomToMapObject(mapPolygon, getZoomLevel(mapPolygon));
    });
  }

  function getZoomLevel(mapPolygon) {
    var w = mapPolygon.polygon.bbox.width;
    var h = mapPolygon.polygon.bbox.height;
    return Math.min(mapChart.seriesWidth / (w * 2), mapChart.seriesHeight / (h * 2));
  }

  function showWorld() {
    currentCountry = "World";
    currentPolygon = undefined;
    resetHover();

    if (countryDataTimeout) {
      clearTimeout(countryDataTimeout);
    }

    polygonSeries.mapPolygons.each(function(polygon) {
      polygon.isActive = false;
    });

    updateCountryName();

    for (var i = 0; i < lineChart.data.length; i++) {
      var di = data_total_timeline[i];
      var dataContext = lineChart.data[i];
      dataContext.waterSupply = di.waterSupply;
      dataContext.waterLoss = di.waterLoss;
      valueAxis.min = undefined;
      valueAxis.max = undefined;
    }

    lineChart.invalidateRawData();
    updateTotals(currentIndex);
    mapChart.goHome();
  }

  function updateCountryName() {
    countryName.text = currentCountry + ", " + mapChart.dateFormatter.format(currentDate, "MMM dd, yyyy");
  }

  function updateTotals(index) {
    if (!isNaN(index)) {
      var di = data_total_timeline[index];
      var date = new Date(di.date);
      currentDate = date;

      updateCountryName();

      var position = dateAxis.dateToPosition(date);
      position = dateAxis.toGlobalPosition(position);
      var x = dateAxis.positionToCoordinate(position);

      if (lineChart.cursor) {
        lineChart.cursor.triggerMove({ x: x, y: 0 }, "soft", true);
      }
      for (var key in buttons) {
        var count = Number(lineChart.data[index][key]);
        if (!isNaN(count)) {
          buttons[key].label.text = capitalizeFirstLetter(key) + ": " + numberFormatter.format(count, '#,###');
        }
      }
      currentIndex = index;
    }
  }

  function updateMapData(data) {
    bubbleSeries.dataItems.each(function(dataItem) {
      dataItem.dataContext.waterSupply = 0;
      dataItem.dataContext.waterLoss = 0;
    });

    maxPC = { waterSupply: 0, waterLoss: 0 };

    for (var i = 0; i < data.length; i++) {
      var di = data[i];
      var image = bubbleSeries.getImageById(di.id);
      var polygon = polygonSeries.getPolygonById(di.id);

      if (image) {
        var population = Number(populations[image.dataItem.dataContext.id]) || 1;
        image.dataItem.dataContext.waterSupply = di.waterSupply || 0;
        image.dataItem.dataContext.waterLoss = di.waterLoss || 0;
      }

      if (polygon) {
        var population = Number(populations[di.id]) || 1;
        polygon.dataItem.dataContext.waterSupplyPC = di.waterSupply / population * 1000000;
        polygon.dataItem.dataContext.waterLossPC = di.waterLoss / population * 1000000;

        if (polygon.dataItem.dataContext.waterSupplyPC > maxPC.waterSupply) {
          maxPC.waterSupply = polygon.dataItem.dataContext.waterSupplyPC;
        }
        if (polygon.dataItem.dataContext.waterLossPC > maxPC.waterLoss) {
          maxPC.waterLoss = polygon.dataItem.dataContext.waterLossPC;
        }
      }
    }

    bubbleSeries.heatRules.getIndex(0).maxValue = max[currentType];
    polygonSeries.heatRules.getIndex(0).maxValue = maxPC[currentType];

    bubbleSeries.invalidateData();
    polygonSeries.invalidateData();
  }

  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  function handleImageOver(event) {
    rollOverCountry(polygonSeries.getPolygonById(event.target.dataItem.id));
  }

  function handleImageOut(event) {
    rollOutCountry(polygonSeries.getPolygonById(event.target.dataItem.id));
  }

  function handleImageHit(event) {
    selectCountry(polygonSeries.getPolygonById(event.target.dataItem.id));
  }

  function handleCountryHit(event) {
    selectCountry(event.target);
  }

  function handleCountryOver(event) {
    rollOverCountry(event.target);
  }

  function handleCountryOut(event) {
    rollOutCountry(event.target);
  }

  function resetHover() {
    polygonSeries.mapPolygons.each(function(polygon) {
      polygon.isHover = false;
    });

    bubbleSeries.mapImages.each(function(image) {
      image.isHover = false;
    });
  }

  function updateCountryTooltip() {
    polygonSeries.mapPolygons.template.tooltipText = "[bold]{name}: {value.formatNumber('#.')}[/]\n[font-size:10px]" + currentTypeName + " trên một triệu dân";
  }

  container.events.on("layoutvalidated", function() {
    dateAxis.tooltip.hide();
    lineChart.cursor.hide();
    updateTotals(currentIndex);
  });

  updateCountryName();
  changeDataType("waterSupply");
  setTimeout(updateSeriesTooltip, 3000);

});




}); // end am4core.ready()
</script>

<!-- HTML -->



<script>
    // Đảm bảo Chart.js đã tải trước khi khởi tạo
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo Bản đồ
        // var map = L.map('map').setView([10.7769, 106.7009], 13);
        // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //     attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        // }).addTo(map);
        // L.marker([10.7769, 106.7009]).addTo(map).bindPopup('Trạm Bơm Chính');
        // L.marker([10.7800, 106.7100]).addTo(map).bindPopup('Van Phân Phối');
        // L.marker([10.7700, 106.6900]).addTo(map).bindPopup('Sự cố Rò rỉ');

        // Biểu đồ Tiêu thụ Nước
        const ctx = document.getElementById('water-consumption-chart').getContext('2d');
        if (ctx) {
            const waterConsumptionChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
                    datasets: [{
                        label: 'Tiêu thụ Nước (m³)',
                        data: [12000, 15000, 13000, 17000, 16000, 18000, 20000, 19000, 21000, 22000, 23000, 24000],
                        fill: true,
                        backgroundColor: 'rgba(0, 123, 255, 0.2)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Tiêu thụ Nước Hàng Tháng',
                            font: { size: 16 }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Khối lượng (m³)' }
                        },
                        x: {
                            title: { display: true, text: 'Tháng' }
                        }
                    }
                }
            });
        }

        // Biểu đồ Sự cố Rò rỉ
        const ctx2 = document.getElementById('leak-incidents-chart').getContext('2d');
        if (ctx2) {
            const leakIncidentsChart = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
                    datasets: [{
                        label: 'Sự cố Rò rỉ',
                        data: [5, 3, 8, 2, 6, 4, 7, 3, 5, 2, 4, 6],
                        backgroundColor: 'rgba(220, 53, 69, 0.5)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Sự cố Rò rỉ Theo Thời Gian',
                            font: { size: 16 }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Số sự cố' }
                        },
                        x: {
                            title: { display: true, text: 'Tháng' }
                        }
                    }
                }
            });
        }
    });
</script>