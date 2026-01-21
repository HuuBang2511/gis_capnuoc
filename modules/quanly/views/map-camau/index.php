<?php

use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $geoJsonData array */

$this->title = 'Bản đồ GIS - Quản lý Hạ tầng';
?>

<!-- --- ASSETS --- -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-measure/dist/leaflet-measure.css" />

<!-- --- STYLES MỚI (FIX LỖI CSS) --- -->
<style>
    /* 1. RESET & FULLSCREEN CONTAINER */
    .gis-fullscreen-container {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        width: 100vw; height: 100vh;
        z-index: 99999; background: white;
        display: flex; flex-direction: row; overflow: hidden;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #333;
    }

    /* 2. SIDEBAR STYLING */
    .gis-sidebar {
        width: 360px; min-width: 360px; background: #fff;
        display: flex; flex-direction: column;
        border-right: 1px solid #e0e0e0; box-shadow: 4px 0 12px rgba(0,0,0,0.05);
        z-index: 10001; transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Header */
    .sidebar-header {
        height: 60px; background: #1a73e8; color: white;
        display: flex; align-items: center; justify-content: space-between;
        padding: 0 20px; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.12);
    }
    .sidebar-header h5 { margin: 0; font-size: 16px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

    /* --- ADDED: Style cho link trang chủ --- */
    .home-link {
        color: white; text-decoration: none; display: flex; align-items: center;
        transition: opacity 0.2s;
    }
    .home-link:hover { opacity: 0.8; text-decoration: none; color: white; }

    /* Custom Tabs */
    .sidebar-tabs { display: flex; background: #f1f3f4; border-bottom: 1px solid #e0e0e0; flex-shrink: 0; }
    .tab-item {
        flex: 1; text-align: center; padding: 14px 0; font-size: 13px; font-weight: 600; color: #5f6368;
        cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.2s; text-transform: uppercase;
    }
    .tab-item:hover { background: #e8eaed; color: #1a73e8; }
    .tab-item.active { background: #fff; color: #1a73e8; border-bottom: 3px solid #1a73e8; }

    /* Content Area */
    .sidebar-content { flex: 1; overflow-y: auto; padding: 20px; background: #fff; position: relative; }
    .tab-pane { display: none; animation: fadeIn 0.3s; }
    .tab-pane.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

    /* Layer Items */
    .layer-group-title { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #9aa0a6; margin: 15px 0 8px 0; letter-spacing: 0.5px; }
    .layer-item { display: flex; align-items: center; padding: 10px 12px; margin-bottom: 6px; border-radius: 6px; cursor: pointer; transition: background 0.2s; border: 1px solid transparent; }
    .layer-item:hover { background: #f8f9fa; border-color: #f1f3f4; }
    .layer-item input { margin-right: 12px; width: 16px; height: 16px; cursor: pointer; accent-color: #1a73e8; }
    .layer-item label { margin: 0; cursor: pointer; flex: 1; font-size: 14px; color: #3c4043; font-weight: 500; }
    .layer-legend { width: 22px; height: 22px; margin-right: 12px; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }

    /* Search Box */
    .search-wrapper { position: relative; margin-bottom: 20px; }
    .search-input { width: 100%; padding: 12px 40px 12px 16px; border: 1px solid #dadce0; border-radius: 24px; outline: none; font-size: 14px; transition: box-shadow 0.2s; box-sizing: border-box; }
    .search-input:focus { border-color: transparent; box-shadow: 0 2px 6px rgba(60,64,67,0.15); }
    .search-icon { position: absolute; right: 16px; top: 14px; color: #5f6368; }
    .result-list { max-height: calc(100vh - 250px); overflow-y: auto; }
    .result-item { padding: 12px; border-bottom: 1px solid #f1f3f4; cursor: pointer; border-radius: 4px; transition: background 0.1s; }
    .result-item:hover { background: #e8f0fe; }
    .result-title { font-weight: 600; color: #202124; font-size: 14px; display: block; margin-bottom: 2px; }
    .result-desc { font-size: 12px; color: #70757a; }

    /* Filter Card */
    .filter-card { background: #f8f9fa; border: 1px solid #e8eaed; border-radius: 8px; padding: 15px; margin-bottom: 15px; }
    .filter-label { font-size: 13px; font-weight: 600; color: #444; margin-bottom: 8px; display: block; }
    .custom-select { width: 100%; padding: 8px; border: 1px solid #dadce0; border-radius: 4px; outline: none; background: white; font-size: 13px; cursor: pointer; }
    .custom-select:focus { border-color: #1a73e8; }

    /* 3. MAP AREA */
    .map-container { flex: 1; position: relative; background: #e5e9ec; z-index: 1; }
    #map { width: 100%; height: 100%; }
    .map-toolbar { position: absolute; top: 20px; right: 20px; z-index: 1000; display: flex; flex-direction: column; gap: 10px; }
    .tool-btn { width: 40px; height: 40px; background: white; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.15); border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #5f6368; font-size: 16px; transition: all 0.2s; }
    .tool-btn:hover { background: #f8f9fa; color: #1a73e8; transform: translateY(-1px); }
    .tool-btn:active { transform: translateY(1px); box-shadow: 0 1px 3px rgba(0,0,0,0.15); }

    /* Loading & Popups */
    .loader-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.85); z-index: 2000; display: flex; flex-direction: column; justify-content: center; align-items: center; backdrop-filter: blur(2px); }
    .spinner { width: 30px; height: 30px; border: 3px solid #f3f3f3; border-top: 3px solid #1a73e8; border-radius: 50%; animation: spin 0.8s linear infinite; margin-bottom: 10px; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    .custom-popup .leaflet-popup-content-wrapper { border-radius: 8px; padding: 0; box-shadow: 0 4px 15px rgba(0,0,0,0.15); }
    .custom-popup .leaflet-popup-content { margin: 0; width: 280px !important; }
    .popup-header { background: #1a73e8; color: white; padding: 10px 15px; font-weight: 600; font-size: 14px; }
    .popup-table td, .popup-table th { padding: 8px 12px; font-size: 13px; border-bottom: 1px solid #f1f3f4; }
    .popup-table th { color: #5f6368; width: 40%; background: #f8f9fa; }

    /* Responsive */
    .mobile-toggle { display: none; position: absolute; top: 15px; left: 15px; z-index: 2000; background: white; border: none; padding: 10px; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); color: #555; }
    @media (max-width: 768px) {
        .gis-sidebar { position: absolute; height: 100%; transform: translateX(-100%); width: 85%; max-width: 320px; }
        .gis-sidebar.open { transform: translateX(0); }
        .mobile-toggle { display: block; }
        .map-toolbar { top: 70px; right: 10px; }
    }
</style>

<!-- --- HTML STRUCTURE --- -->
<div class="gis-fullscreen-container">
    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="fa fa-bars fa-lg"></i></button>

    <div class="gis-sidebar" id="sidebar">
        <div class="sidebar-header">
            <!-- Đã thêm thẻ a bọc tiêu đề để quay về trang chủ -->
            <a href="https://hpngis.com" class="home-link" title="Quay về trang chủ">
                <h5><i class="fa fa-map-marked-alt mr-2"></i> GIS HẠ TẦNG</h5>
            </a>
            <button class="btn btn-sm text-white d-md-none" onclick="toggleSidebar()"><i class="fa fa-times"></i></button>
        </div>

        <div class="sidebar-tabs">
            <div class="tab-item active" onclick="switchTab('tab-layers', this)"><i class="fa fa-layer-group mr-1"></i> Lớp</div>
            <div class="tab-item" onclick="switchTab('tab-search', this)"><i class="fa fa-search mr-1"></i> Tìm</div>
            <div class="tab-item" onclick="switchTab('tab-filter', this)"><i class="fa fa-filter mr-1"></i> Lọc</div>
        </div>

        <div class="sidebar-content">
            
            <!-- 1. TAB LỚP BẢN ĐỒ -->
            <div id="tab-layers" class="tab-pane active">
                <div class="layer-group-title">Bản đồ nền</div>
                <div class="layer-item">
                    <input type="radio" name="basemap" id="base_osm" value="osm" checked onchange="switchBaseMap('osm')">
                    <label for="base_osm">Bản đồ Đường phố (OSM)</label>
                </div>
                <div class="layer-item">
                    <input type="radio" name="basemap" id="base_sat" value="satellite" onchange="switchBaseMap('satellite')">
                    <label for="base_sat">Ảnh vệ tinh (Esri)</label>
                </div>

                <div class="layer-group-title">Dữ liệu nền</div>
                <div class="layer-item">
                    <input type="checkbox" id="chk_thuyhe" checked onchange="toggleLayer('thuyhe', this.checked)">
                    <div class="layer-legend" style="background: #e1f5fe; border: 1px solid #039be5"><i class="fa fa-water" style="color: #0288d1"></i></div>
                    <label for="chk_thuyhe">Thủy hệ</label>
                </div>
                <div class="layer-item">
                    <input type="checkbox" id="chk_giaothong" checked onchange="toggleLayer('giaothong', this.checked)">
                    <div class="layer-legend" style="background: #f5f5f5; border: 1px solid #999"><i class="fa fa-road" style="color: #666"></i></div>
                    <label for="chk_giaothong">Giao thông</label>
                </div>
                <div class="layer-item">
                    <input type="checkbox" id="chk_thuadat" checked onchange="toggleLayer('thuadat', this.checked)">
                    <div class="layer-legend" style="background: #fff9c4; border: 1px solid #fbc02d"><i class="fa fa-vector-square" style="color: #f57f17"></i></div>
                    <label for="chk_thuadat">Thửa đất (Địa chính)</label>
                </div>

                <div class="layer-group-title">Mạng lưới cấp nước</div>
                <div class="layer-item">
                    <input type="checkbox" id="chk_ong" checked onchange="toggleLayer('ong', this.checked)">
                    <div class="layer-legend" style="background: #00bcd4; height: 4px; margin-top: 2px"></div>
                    <label for="chk_ong">Đường ống phân phối</label>
                </div>
                <div class="layer-item">
                    <input type="checkbox" id="chk_tru" checked onchange="toggleLayer('tru', this.checked)">
                    <div class="layer-legend"><i class="fa fa-fire-extinguisher text-danger"></i></div>
                    <label for="chk_tru">Trụ cứu hỏa</label>
                </div>
                <div class="layer-item">
                    <input type="checkbox" id="chk_van" checked onchange="toggleLayer('van', this.checked)">
                    <div class="layer-legend"><i class="fa fa-cog text-success"></i></div>
                    <label for="chk_van">Van chặn</label>
                </div>
                <div class="layer-item">
                    <input type="checkbox" id="chk_caodo" onchange="toggleLayer('caodo', this.checked)">
                    <div class="layer-legend"><i class="fa fa-map-pin text-dark"></i></div>
                    <label for="chk_caodo">Điểm cao độ</label>
                </div>
            </div>

            <!-- 2. TAB TÌM KIẾM -->
            <div id="tab-search" class="tab-pane">
                <div class="search-wrapper">
                    <input type="text" id="searchInput" class="search-input" placeholder="Nhập số tờ, số thửa..." onkeyup="handleSearch()">
                    <i class="fa fa-search search-icon"></i>
                </div>
                <div class="text-muted small mb-3" style="font-size: 12px; padding-left: 10px;">
                    <i class="fa fa-info-circle mr-1"></i> Ví dụ: "10 5" (Tờ 10 thửa 5)
                </div>
                <div id="searchResults" class="result-list">
                     <div class="text-center text-muted mt-5 pt-3">
                        <i class="fa fa-search fa-3x mb-3" style="color:#e0e0e0"></i>
                        <p>Kết quả tìm kiếm sẽ hiện ở đây</p>
                    </div>
                </div>
            </div>

            <!-- 3. TAB BỘ LỌC (Đã cập nhật) -->
            <div id="tab-filter" class="tab-pane">
                <!-- Lọc Ống theo Cỡ ống (coong) -->
                <div class="filter-card">
                    <label class="filter-label"><i class="fa fa-grip-lines mr-1 text-primary"></i> Lọc Ống phân phối (Cỡ ống)</label>
                    <select class="custom-select" id="filterOng" onchange="applySmartFilter()">
                        <option value="all">-- Tất cả --</option>
                    </select>
                </div>

                <!-- Lọc Trụ theo Loại trụ (loaitru) -->
                <div class="filter-card">
                    <label class="filter-label"><i class="fa fa-fire-extinguisher mr-1 text-danger"></i> Lọc Trụ cứu hỏa (Loại trụ)</label>
                    <select class="custom-select" id="filterTru" onchange="applySmartFilter()">
                        <option value="all">-- Tất cả --</option>
                    </select>
                </div>

                <!-- Lọc Van theo Vật liệu (vatlieu) -->
                <div class="filter-card">
                    <label class="filter-label"><i class="fa fa-cog mr-1 text-success"></i> Lọc Van (Vật liệu)</label>
                    <select class="custom-select" id="filterVan" onchange="applySmartFilter()">
                        <option value="all">-- Tất cả --</option>
                    </select>
                </div>
                
                <button class="btn btn-outline-primary btn-block btn-sm mt-3" style="width:100%; padding: 8px; border-radius: 4px; border:1px solid #1a73e8; background:white; color:#1a73e8; font-weight:600; cursor:pointer;" onclick="resetFilters()">
                    <i class="fa fa-sync-alt mr-1"></i> Đặt lại mặc định
                </button>
            </div>

        </div>
    </div>

    <!-- MAP AREA -->
    <div class="map-container">
        <div id="map"></div>
        <div class="map-toolbar">
            <button class="tool-btn" title="Vị trí của tôi" onclick="locateUser()"><i class="fa fa-crosshairs"></i></button>
            <button class="tool-btn" title="Đo đạc" onclick="toggleMeasure()"><i class="fa fa-ruler-combined"></i></button>
            <button class="tool-btn" title="Reset góc nhìn" onclick="resetView()"><i class="fa fa-home"></i></button>
        </div>
        <div id="loading-overlay" class="loader-overlay">
            <div class="spinner"></div>
            <div style="font-weight:600; color:#555; font-size:14px;">Đang tải dữ liệu bản đồ...</div>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/leaflet-measure/dist/leaflet-measure.js"></script>

<script>
    // --- 1. DỮ LIỆU & CẤU HÌNH ---
    const geoData = <?= Json::encode($geoJsonData) ?>;
    
    const fieldDictionary = {
        'sothua': 'Số thửa', 'soto': 'Số tờ', 'chusohuu': 'Chủ sở hữu', 'quyhoach': 'Quy hoạch',
        'shape_area': 'Diện tích (m²)', 'shape_leng': 'Chiều dài (m)', 'loaimat': 'Loại mặt đường',
        'vatlieu': 'Vật liệu', 'coong': 'Cỡ ống (mm)', 'chieudai': 'Chiều dài (m)',
        'loaitru': 'Loại trụ', 'cotru': 'Cỡ trụ', 'covan': 'Cỡ van', 'caodo': 'Cao độ (m)', 'id': 'Mã ID'
    };

    // --- 2. MAP SETUP ---
    var map = L.map('map', { zoomControl: false, measureControl: true }).setView([10.738501928736335, 106.83312465486868], 14);
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    map.createPane('polygonsPane'); map.getPane('polygonsPane').style.zIndex = 400;
    map.createPane('linesPane'); map.getPane('linesPane').style.zIndex = 450;
    map.createPane('pointsPane'); map.getPane('pointsPane').style.zIndex = 600;

    var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
    var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri' });

    var layers = {};
    
    // Backup Data cho bộ lọc
    var originalPipeData = geoData.ong;
    var originalTruData = geoData.tru;
    var originalVanData = geoData.van;

    // --- 3. STYLES ---
    const styles = {
        thuyhe: { color: "#039be5", weight: 1, fillOpacity: 0.6, fillColor: "#e1f5fe" },
        thuadat: { color: "#bdbdbd", weight: 1, fillOpacity: 0.4, fillColor: "#fff9c4" }, 
        giaothong: { color: "#9e9e9e", weight: 2, fillOpacity: 0.9, fillColor: "#eeeeee" },
        ong: { color: "#00bcd4", weight: 3, opacity: 1 }, 
        highlight: { color: "#d32f2f", weight: 3, fillOpacity: 0.2 }
    };

    function getIcon(type, val) {
        if (type === 'caodo') return L.divIcon({ className: '', html: `<div style="font-size:10px; font-weight:bold; color:#333; background:rgba(255,255,255,0.8); padding:0 2px; border-radius:2px;">+${parseFloat(val).toFixed(2)}</div>`, iconSize: [30, 15] });
        if (type === 'tru') return L.divIcon({ className: '', html: `<div style='background:#f44336; width:20px; height:20px; border-radius:50%; border:2px solid white; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 4px rgba(0,0,0,0.3)'><i class='fa fa-fire-extinguisher text-white' style='font-size:10px'></i></div>`, iconSize: [20, 20], iconAnchor: [10, 10] });
        if (type === 'van') return L.divIcon({ className: '', html: `<div style='background:#4caf50; width:18px; height:18px; border-radius:50%; border:2px solid white; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 4px rgba(0,0,0,0.3)'><i class='fa fa-cog text-white' style='font-size:10px'></i></div>`, iconSize: [18, 18], iconAnchor: [9, 9] });
    }

    function createPopupContent(title, props) {
        let rows = Object.entries(props).map(([k, v]) => 
            (v && fieldDictionary[k]) ? `<tr><th>${fieldDictionary[k]}</th><td>${v}</td></tr>` : ''
        ).join('');
        return `<div class='popup-header'>${title}</div><table class='popup-table' style="width:100%; border-collapse:collapse;">${rows}</table>`;
    }

    // --- 4. LAYER INIT ---
    function initLayer(key, data, style, typeTitle, options = {}) {
        if (!data || !data.features) return;
        let pane = 'overlayPane';
        if (key === 'thuadat' || key === 'thuyhe') pane = 'polygonsPane';
        if (key === 'ong' || key === 'giaothong') pane = 'linesPane';
        if (key === 'tru' || key === 'van' || key === 'caodo') pane = 'pointsPane';

        layers[key] = L.geoJSON(data, {
            pane: pane, style: style,
            pointToLayer: options.iconType ? (f, ll) => L.marker(ll, { icon: getIcon(options.iconType, options.iconType === 'caodo' ? f.properties.caodo : null) }) : null,
            onEachFeature: (feature, layer) => {
                layer.bindPopup(createPopupContent(typeTitle, feature.properties), { className: 'custom-popup' });
                if (!options.iconType) {
                    layer.on('mouseover', function() { this.setStyle({ weight: 4, color: '#ff9800', opacity: 1 }); if(key==='thuadat') this.bringToFront(); });
                    layer.on('mouseout', function() { layers[key].resetStyle(this); if(key==='thuadat') this.bringToBack(); });
                }
                if (key === 'thuadat' && feature.properties.sothua) {
                    layer.bindTooltip(`${feature.properties.soto}/${feature.properties.sothua}`, { permanent: true, direction: 'center', className: 'label-thuadat', opacity: 0.8 }).closeTooltip();
                }
            }
        });
        if (options.visible !== false) layers[key].addTo(map);
    }

    // Helper: Tự động điền options cho Select Box từ dữ liệu thực tế
    function populateFilterOptions(data, property, selectId) {
        if (!data || !data.features) return;
        const select = document.getElementById(selectId);
        const uniqueValues = new Set();
        
        data.features.forEach(f => {
            if (f.properties && f.properties[property]) {
                uniqueValues.add(f.properties[property].trim());
            }
        });

        // Sort và thêm vào select
        Array.from(uniqueValues).sort().forEach(val => {
            const opt = document.createElement('option');
            opt.value = val;
            opt.innerHTML = val;
            select.appendChild(opt);
        });
    }

    // Khởi tạo Async
    setTimeout(() => {
        initLayer('thuyhe', geoData.thuyhe, styles.thuyhe, 'Thủy hệ', { visible: true });
        initLayer('giaothong', geoData.giaothong, styles.giaothong, 'Giao thông', { visible: true });
        initLayer('thuadat', geoData.thuadat, styles.thuadat, 'Thửa đất', { visible: true });
        
        initLayer('ong', geoData.ong, styles.ong, 'Đường ống', { visible: true });
        initLayer('tru', geoData.tru, null, 'Trụ cứu hỏa', { iconType: 'tru', visible: true });
        initLayer('van', geoData.van, null, 'Van chặn', { iconType: 'van', visible: true });
        
        initLayer('caodo', geoData.caodo, null, 'Cao độ', { iconType: 'caodo', visible: false });

        // Tự động điền dữ liệu cho bộ lọc
        populateFilterOptions(geoData.ong, 'coong', 'filterOng');
        populateFilterOptions(geoData.tru, 'loaitru', 'filterTru');
        populateFilterOptions(geoData.van, 'vatlieu', 'filterVan');

        if (layers.thuadat && layers.thuadat.getLayers().length > 0) map.fitBounds(layers.thuadat.getBounds());
        document.getElementById('loading-overlay').style.display = 'none';

        map.on('zoomend', function() {
            if (map.hasLayer(layers.thuadat)) {
                map.getZoom() >= 18 ? layers.thuadat.eachLayer(l => l.openTooltip()) : layers.thuadat.eachLayer(l => l.closeTooltip());
            }
        });
    }, 500);

    // --- 5. FUNCTIONS ---
    function switchTab(tabId, el) {
        document.querySelectorAll('.tab-pane').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        el.classList.add('active');
    }

    function toggleSidebar() { document.getElementById('sidebar').classList.toggle('open'); setTimeout(() => map.invalidateSize(), 300); }
    function switchBaseMap(type) { type === 'osm' ? (map.addLayer(osmLayer), map.removeLayer(satelliteLayer)) : (map.addLayer(satelliteLayer), map.removeLayer(osmLayer)); }
    function toggleLayer(key, checked) { if(!layers[key]) return; checked ? map.addLayer(layers[key]) : map.removeLayer(layers[key]); }

    function locateUser() {
        map.locate({setView: true, maxZoom: 17});
        map.once('locationfound', e => { L.marker(e.latlng).addTo(map).bindPopup("Vị trí của bạn").openPopup(); L.circle(e.latlng, e.accuracy).addTo(map); });
        map.once('locationerror', () => alert("Không thể xác định vị trí."));
    }
    
    var measureControl = new L.Control.Measure({ position: 'topleft', primaryLengthUnit: 'meters', activeColor: '#ff9800' });
    measureControl.addTo(map);
    function toggleMeasure() {
        var btn = document.querySelector('.leaflet-control-measure-toggle');
        if(btn) btn.click(); else alert("Đang tải công cụ đo...");
    }

    function resetView() { layers.thuadat ? map.fitBounds(layers.thuadat.getBounds()) : map.setView([10.762622, 106.660172], 14); }

    function handleSearch() {
        const query = document.getElementById('searchInput').value.toLowerCase().trim();
        const resultsDiv = document.getElementById('searchResults');
        resultsDiv.innerHTML = '';
        if (query.length < 1) return;

        let count = 0;
        const features = geoData.thuadat ? geoData.thuadat.features : [];
        for (let f of features) {
            const p = f.properties;
            const text = `${p.soto} ${p.sothua} ${p.chusohuu || ''}`.toLowerCase();
            if (text.includes(query)) {
                const item = document.createElement('div');
                item.className = 'result-item';
                item.innerHTML = `<span class="result-title">Tờ ${p.soto} - Thửa ${p.sothua}</span><span class="result-desc">${p.chusohuu || 'Chưa có thông tin chủ'}</span>`;
                item.onclick = () => {
                    const l = L.geoJSON(f); map.fitBounds(l.getBounds(), { maxZoom: 19 });
                    L.geoJSON(f, { style: {color:'red', weight:4, fill:false} }).addTo(map).bindPopup(createPopupContent('Kết quả', p)).openPopup();
                    if(window.innerWidth < 768) toggleSidebar();
                };
                resultsDiv.appendChild(item);
                if (++count > 10) break;
            }
        }
        if (count === 0) resultsDiv.innerHTML = '<div class="text-center text-muted p-3 small">Không tìm thấy kết quả</div>';
    }

    // --- FILTER LOGIC (UPDATED) ---
    function applySmartFilter() {
        // Lấy giá trị từ các Select box
        const valOng = document.getElementById('filterOng').value; // coong
        const valTru = document.getElementById('filterTru').value; // loaitru
        const valVan = document.getElementById('filterVan').value; // vatlieu

        // 1. Lọc Ống phân phối (theo coong)
        if (map.hasLayer(layers.ong)) map.removeLayer(layers.ong);
        let filteredOng = JSON.parse(JSON.stringify(originalPipeData));
        if (valOng !== 'all') {
            filteredOng.features = filteredOng.features.filter(f => f.properties.coong && f.properties.coong == valOng);
        }
        layers.ong = L.geoJSON(filteredOng, {
            pane: 'linesPane', style: styles.ong,
            onEachFeature: (f, l) => l.bindPopup(createPopupContent('Đường ống', f.properties))
        }).addTo(map);

        // 2. Lọc Trụ cứu hỏa (theo loaitru)
        if (map.hasLayer(layers.tru)) map.removeLayer(layers.tru);
        let filteredTru = JSON.parse(JSON.stringify(originalTruData));
        if (valTru !== 'all') {
            filteredTru.features = filteredTru.features.filter(f => f.properties.loaitru && f.properties.loaitru == valTru);
        }
        layers.tru = L.geoJSON(filteredTru, {
            pane: 'pointsPane',
            pointToLayer: (f, ll) => L.marker(ll, { icon: getIcon('tru', null) }),
            onEachFeature: (f, l) => l.bindPopup(createPopupContent('Trụ cứu hỏa', f.properties))
        }).addTo(map);

        // 3. Lọc Van (theo vatlieu)
        if (map.hasLayer(layers.van)) map.removeLayer(layers.van);
        let filteredVan = JSON.parse(JSON.stringify(originalVanData));
        if (valVan !== 'all') {
            filteredVan.features = filteredVan.features.filter(f => f.properties.vatlieu && f.properties.vatlieu == valVan);
        }
        layers.van = L.geoJSON(filteredVan, {
            pane: 'pointsPane',
            pointToLayer: (f, ll) => L.marker(ll, { icon: getIcon('van', null) }),
            onEachFeature: (f, l) => l.bindPopup(createPopupContent('Van chặn', f.properties))
        }).addTo(map);
    }

    function resetFilters() {
        document.getElementById('filterOng').value = 'all';
        document.getElementById('filterTru').value = 'all';
        document.getElementById('filterVan').value = 'all';
        applySmartFilter();
    }
</script>