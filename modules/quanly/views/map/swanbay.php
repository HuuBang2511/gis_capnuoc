<?php
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $layers array */

$this->title = 'Bản đồ SwanBay';

$layerMeta = [
    'thuadat' => ['label' => 'Thửa đất', 'type' => 'polygon', 'color' => '#7c3aed', 'icon' => 'draw-polygon', 'opacity' => 60],
    'thuyhe' => ['label' => 'Thủy hệ', 'type' => 'polygon', 'color' => '#0ea5e9', 'icon' => 'water', 'opacity' => 55],
    'giaothong' => ['label' => 'Giao thông', 'type' => 'line', 'color' => '#94a3b8', 'icon' => 'road', 'opacity' => 85],
    'ongphanphoi' => ['label' => 'Ống phân phối', 'type' => 'line', 'color' => '#06b6d4', 'icon' => 'wave-square', 'opacity' => 95],
    'trucuuhoa' => ['label' => 'Trụ cứu hỏa', 'type' => 'point', 'color' => '#ef4444', 'icon' => 'faucet-drip', 'opacity' => 100],
    'van' => ['label' => 'Van', 'type' => 'point', 'color' => '#f59e0b', 'icon' => 'gear', 'opacity' => 100],
    'caodo' => ['label' => 'Cao độ', 'type' => 'point', 'color' => '#22c55e', 'icon' => 'mountain', 'opacity' => 100],
];

$fieldLabels = [
    'id' => 'ID',
    'objectid' => 'Object ID',
    'shape_leng' => 'Chiều dài',
    'shape_area' => 'Diện tích',
    'sothua' => 'Số thửa',
    'soto' => 'Số tờ',
    'chusohuu' => 'Chủ sở hữu',
    'quyhoach' => 'Quy hoạch',
    'loaimat' => 'Loại mặt',
    'vatlieu' => 'Vật liệu',
    'coong' => 'Cỡ ống',
    'chieudai' => 'Chiều dài',
    'loaitru' => 'Loại trụ',
    'cotru' => 'Cỡ trụ',
    'covan' => 'Cỡ van',
    'caodo' => 'Cao độ',
];
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-measure/dist/leaflet-measure.css"/>

<style>
:root{--bg:#0d1117;--panel:#161b22;--soft:#1f2630;--line:#30363d;--text:#e6edf3;--muted:#8d96a0;--accent:#1f6feb;--ok:#2ea043;--radius:10px;--sidebar:340px}
[data-theme="light"]{--bg:#f3f4f6;--panel:#fff;--soft:#f8fafc;--line:#d8dee4;--text:#111827;--muted:#6b7280}
*{box-sizing:border-box;margin:0;padding:0} body{overflow:hidden;font-family:'Be Vietnam Pro',sans-serif}
.sb-root{position:fixed;inset:0;display:flex;background:var(--bg);color:var(--text);z-index:99999}
.sb-sidebar{width:var(--sidebar);min-width:var(--sidebar);background:var(--panel);border-right:1px solid var(--line);display:flex;flex-direction:column;z-index:10}
.sb-header,.sb-footer{padding:14px 16px;border-bottom:1px solid var(--line);display:flex;align-items:center;gap:12px}
.sb-footer{border-top:1px solid var(--line);border-bottom:none;justify-content:space-between;font-size:11px;color:var(--muted)}
.sb-logo{width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,var(--accent),var(--ok));display:flex;align-items:center;justify-content:center;color:#fff}
.sb-title{flex:1}.sb-title h1{font-size:14px}.sb-title span{font-size:11px;color:var(--muted)}
.icon-btn,.fab,.mobile-toggle{border:1px solid var(--line);background:var(--soft);color:var(--muted);cursor:pointer;border-radius:8px}
.icon-btn{width:30px;height:30px}.sb-actions{display:flex;gap:6px}
.sb-nav{display:grid;grid-template-columns:repeat(4,1fr);border-bottom:1px solid var(--line)}
.sb-nav-item{padding:10px 0;text-align:center;font-size:11px;font-weight:600;color:var(--muted);cursor:pointer;border-bottom:2px solid transparent}
.sb-nav-item.active{color:var(--accent);border-bottom-color:var(--accent)}
.sb-nav-item i{display:block;font-size:13px;margin-bottom:4px}
.sb-content{flex:1;overflow:auto}.tab-pane{display:none;padding:16px}.tab-pane.active{display:block}
.section-label{font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin:0 0 10px}
.base-grid,.stat-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.bm-card,.layer-item,.stat-card,.search-item,.info-card{background:var(--soft);border:1px solid var(--line);border-radius:var(--radius)}
.bm-card{padding:14px;cursor:pointer;text-align:center}.bm-card.active{border-color:var(--accent)}
.bm-card i{font-size:18px;margin-bottom:6px;display:block}
.layer-item{margin-bottom:8px;overflow:hidden}.layer-row{display:flex;align-items:center;gap:8px;padding:10px}
.toggle{position:relative;width:34px;height:18px}.toggle input{opacity:0;width:0;height:0}
.slider{position:absolute;inset:0;border-radius:999px;background:#4b5563}.slider:before{content:'';position:absolute;width:14px;height:14px;left:2px;top:2px;border-radius:50%;background:#fff;transition:.2s}
.toggle input:checked + .slider{background:var(--layer-color)}.toggle input:checked + .slider:before{transform:translateX(16px)}
.layer-dot{width:12px;height:12px;background:var(--layer-color);border-radius:50%}.layer-dot.line{height:4px;width:18px;border-radius:999px}.layer-dot.polygon{width:14px;height:10px;border-radius:3px;border:2px solid var(--layer-color);background:color-mix(in srgb,var(--layer-color) 20%,transparent)}
.layer-meta{flex:1}.layer-name{font-size:13px;font-weight:600}.layer-count,.mono{font-size:10px;color:var(--muted);font-family:Consolas,monospace}
.layer-extra{display:none;padding:0 10px 10px 44px;border-top:1px solid var(--line)}.layer-extra.open{display:block}
.opacity-row{display:flex;align-items:center;gap:8px;padding-top:8px}.opacity-row label{width:54px;font-size:11px;color:var(--muted)} .opacity-row input{flex:1}
.stat-card{padding:12px 10px;display:flex;gap:8px;align-items:center}.stat-icon{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center}
.stat-val{font-size:18px;font-weight:700}.stat-lbl{font-size:10px;color:var(--muted)}
.chart-row{display:flex;align-items:center;gap:8px;margin-top:8px}.chart-row span:first-child{width:94px;font-size:11px;color:var(--muted)}.chart-track{flex:1;height:6px;background:var(--line);border-radius:999px;overflow:hidden}.chart-fill{height:100%;border-radius:999px;transition:width .8s}
.search-box{display:flex;align-items:center;gap:8px;padding:0 12px;border:1px solid var(--line);background:var(--soft);border-radius:999px;margin-bottom:12px}
.search-box input{flex:1;background:transparent;border:none;outline:none;color:var(--text);padding:10px 0}
.search-item{padding:10px;margin-bottom:6px;cursor:pointer}.muted{color:var(--muted)} .empty{text-align:center;padding:32px 0;color:var(--muted);font-size:13px}
.info-card table{width:100%;border-collapse:collapse}.info-card td{padding:8px 12px;font-size:12px;border-top:1px solid var(--line)}.info-card td:first-child{width:42%;color:var(--muted)}
.sb-map-wrap{position:relative;flex:1} #sb-map{width:100%;height:100%}
.fab-group{position:absolute;top:16px;right:16px;display:flex;flex-direction:column;gap:6px;z-index:1000}.fab,.mobile-toggle{width:36px;height:36px;display:flex;align-items:center;justify-content:center}
.fab.active{background:var(--accent);border-color:var(--accent);color:#fff}.mobile-toggle{display:none;position:absolute;top:12px;left:12px;z-index:1100}
.sb-loader{position:absolute;inset:0;background:rgba(13,17,23,.88);display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:2000}.sb-loader.hidden{display:none}
.spinner{width:38px;height:38px;border:3px solid #334155;border-top-color:var(--accent);border-radius:50%;animation:spin .8s linear infinite;margin-bottom:10px}.sub{font-size:11px;color:#94a3b8}
.toast{position:absolute;left:50%;bottom:24px;transform:translateX(-50%);padding:8px 16px;border-radius:999px;background:var(--soft);border:1px solid var(--line);opacity:0;pointer-events:none;transition:.25s;z-index:1500}.toast.show{opacity:1}
.leaflet-popup-content-wrapper{background:var(--panel)!important;border:1px solid var(--line)!important;border-radius:10px!important}.leaflet-popup-tip-container{display:none}.leaflet-popup-content{margin:0!important;width:250px!important}
.leaflet-popup-close-button{color:#cbd5e1!important}.leaflet-popup-close-button:hover{color:#fff!important}
.leaflet-interactive:focus{outline:none!important}
.leaflet-control-zoom{border:none!important;box-shadow:0 8px 24px rgba(0,0,0,.18)!important}
.leaflet-control-zoom a{background:var(--panel)!important;color:var(--text)!important;border:1px solid var(--line)!important;width:34px;height:34px;line-height:32px;font-size:18px}
.leaflet-control-zoom a:hover{background:var(--soft)!important}
.leaflet-control-zoom a:first-child{border-bottom:none!important;border-radius:10px 10px 0 0}
.leaflet-control-zoom a:last-child{border-radius:0 0 10px 10px}
.popup-hd{padding:10px 12px;font-size:12px;font-weight:700;border-bottom:1px solid var(--line)}
.popup-row{display:flex;gap:8px;padding:8px 12px;font-size:12px;border-bottom:1px solid var(--line);color:var(--text)}
.popup-row:last-child{border-bottom:none}
.popup-key{width:40%;color:#cbd5e1;font-weight:600}
.popup-row span:last-child{color:var(--text);font-weight:500}
@keyframes spin{to{transform:rotate(360deg)}} @media (max-width:768px){.sb-sidebar{position:absolute;left:0;top:0;bottom:0;transform:translateX(-100%);transition:.25s}.sb-sidebar.open{transform:translateX(0)}.mobile-toggle{display:flex}.fab-group{top:60px}}
</style>

<div class="sb-root" id="sbRoot" data-theme="dark">
  <button class="mobile-toggle" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
  <aside class="sb-sidebar" id="sbSidebar">
    <div class="sb-header">
      <div class="sb-logo"><i class="fa fa-map"></i></div>
      <div class="sb-title"><h1>Khu đô thị SwanBay</h1><span>Quản lý hạ tầng đô thị</span></div>
      <div class="sb-actions">
        <button class="icon-btn" onclick="toggleTheme()" title="Đổi giao diện"><i class="fa fa-circle-half-stroke" id="themeIcon"></i></button>
        <button class="icon-btn" onclick="resetView()" title="Về vùng dữ liệu"><i class="fa fa-house"></i></button>
      </div>
    </div>
    <nav class="sb-nav">
      <div class="sb-nav-item active" onclick="switchTab('tabLayers',this)"><i class="fa fa-layer-group"></i>Lớp</div>
      <div class="sb-nav-item" onclick="switchTab('tabStats',this)"><i class="fa fa-chart-bar"></i>Thống kê</div>
      <div class="sb-nav-item" onclick="switchTab('tabSearch',this)"><i class="fa fa-search"></i>Tìm</div>
      <div class="sb-nav-item" onclick="switchTab('tabInfo',this)"><i class="fa fa-circle-info"></i>Info</div>
    </nav>
    <div class="sb-content">
      <section class="tab-pane active" id="tabLayers">
        <div class="section-label">Bản đồ nền</div>
        <div class="base-grid">
          <div class="bm-card active" id="bm_osm_label" onclick="switchBasemap('osm')"><i class="fa fa-map"></i>OpenStreetMap</div>
          <div class="bm-card" id="bm_sat_label" onclick="switchBasemap('sat')"><i class="fa fa-satellite"></i>Satellite</div>
        </div>
        <div class="section-label" style="margin-top:16px">Lớp SwanBay</div>
        <?php foreach ($layerMeta as $key => $cfg): $dot = $cfg['type'] === 'polygon' ? 'polygon' : ($cfg['type'] === 'line' ? 'line' : ''); ?>
          <div class="layer-item" style="--layer-color: <?= $cfg['color'] ?>">
            <div class="layer-row">
              <label class="toggle"><input type="checkbox" checked onchange="toggleLayer('<?= $key ?>',this.checked)"><span class="slider"></span></label>
              <div class="layer-dot <?= $dot ?>"></div>
              <div class="layer-meta"><div class="layer-name"><?= $cfg['label'] ?></div><div class="layer-count" id="cnt_<?= $key ?>">0 đối tượng</div></div>
              <span style="cursor:pointer;color:var(--muted)" onclick="toggleExpand('<?= $key ?>_extra',this)"><i class="fa fa-chevron-down"></i></span>
            </div>
            <div class="layer-extra" id="<?= $key ?>_extra">
              <div class="opacity-row"><label>Độ mờ</label><input type="range" min="0" max="100" value="<?= $cfg['opacity'] ?>" oninput="setOpacity('<?= $key ?>',this.value,this)"><span class="mono"><?= $cfg['opacity'] ?>%</span></div>
            </div>
          </div>
        <?php endforeach; ?>
      </section>
      <section class="tab-pane" id="tabStats">
        <div class="stat-grid">
          <?php foreach (['thuadat','giaothong','ongphanphoi','trucuuhoa'] as $key): ?>
            <div class="stat-card">
              <div class="stat-icon" style="background:color-mix(in srgb, <?= $layerMeta[$key]['color'] ?> 15%, transparent);color:<?= $layerMeta[$key]['color'] ?>;"><i class="fa fa-<?= $layerMeta[$key]['icon'] ?>"></i></div>
              <div><div class="stat-val mono" id="s_<?= $key ?>">0</div><div class="stat-lbl"><?= $layerMeta[$key]['label'] ?></div></div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="section-label">Phân bố đối tượng</div>
        <div id="chartBars"></div>
      </section>
      <section class="tab-pane" id="tabSearch">
        <div class="search-box"><i class="fa fa-search muted"></i><input id="searchInput" type="text" placeholder="Tìm theo thuộc tính..." oninput="doSearch(this.value)"></div>
        <div style="display:grid;gap:6px;margin-bottom:12px;font-size:12px;color:var(--muted)">
          <label><input type="checkbox" id="scope_thuadat" checked> Thửa đất</label>
          <label><input type="checkbox" id="scope_giaothong" checked> Giao thông</label>
          <label><input type="checkbox" id="scope_trucuuhoa" checked> Trụ cứu hỏa</label>
          <label><input type="checkbox" id="scope_van" checked> Van</label>
        </div>
        <div id="searchResults" class="empty">Nhập từ khóa để tìm kiếm</div>
      </section>
      <section class="tab-pane" id="tabInfo">
        <div id="infoPlaceholder" class="empty"><i class="fa fa-circle-info" style="font-size:32px;display:block;margin-bottom:10px"></i>Chọn một đối tượng trên bản đồ để xem chi tiết.</div>
        <div id="infoContent" style="display:none"></div>
      </section>
    </div>
    <div class="sb-footer"><div class="mono" id="coordDisplay">0.000000, 0.000000</div><div class="mono" id="zoomDisplay">Zoom: --</div></div>
  </aside>
  <div class="sb-map-wrap">
    <div id="sb-map"></div>
    <div class="fab-group">
      <button class="fab" onclick="resetView()"><i class="fa fa-house"></i></button>
      <button class="fab" onclick="locateMe()"><i class="fa fa-location-crosshairs"></i></button>
      <button class="fab" id="fabMeasure" onclick="toggleMeasure()"><i class="fa fa-ruler-combined"></i></button>
    </div>
    <div class="sb-loader" id="sbLoader"><div class="spinner"></div><div>Đang khởi tạo bản đồ SwanBay</div><div class="sub" id="loaderSub">Đang chuẩn bị dữ liệu `sb_`...</div></div>
    <div class="toast" id="sbToast"></div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-measure/dist/leaflet-measure.js"></script>
<script>
const GEO = <?= Json::htmlEncode($layers) ?>;
const LAYER_CFG = <?= Json::htmlEncode($layerMeta) ?>;
const FIELD_LABELS = <?= Json::htmlEncode($fieldLabels) ?>;
const LOAD_ORDER = Object.keys(LAYER_CFG);
const CENTER = [10.738501928736335, 106.83312465486868];
const ZOOM = 15;

const map = L.map('sb-map', { zoomControl: true, attributionControl: true }).setView(CENTER, ZOOM);
['polygons', 'lines', 'points'].forEach((name, index) => {
    map.createPane(name + 'Pane');
    map.getPane(name + 'Pane').style.zIndex = 400 + index * 50;
});

const OSM_LAYER = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap', maxZoom: 22 }).addTo(map);
const SAT_LAYER = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri', maxZoom: 22 });
const layers = {};

function makeIcon(color, icon) {
    return L.divIcon({
        className: '',
        html: `<div style="background:${color};width:22px;height:22px;border-radius:50%;border:2px solid rgba(255,255,255,.85);display:flex;align-items:center;justify-content:center;color:#fff;font-size:10px;box-shadow:0 2px 8px rgba(0,0,0,.45)"><i class="fa fa-${icon}"></i></div>`,
        iconSize: [22, 22],
        iconAnchor: [11, 11],
        popupAnchor: [0, -12]
    });
}

function popupContent(key, props) {
    const cfg = LAYER_CFG[key];
    const rows = Object.entries(props)
        .filter(([field, value]) => FIELD_LABELS[field] !== undefined && value !== null && value !== '')
        .map(([field, value]) => `<div class="popup-row"><span class="popup-key">${FIELD_LABELS[field] || field}</span><span>${value}</span></div>`)
        .join('');
    return `<div class="popup-hd" style="color:${cfg.color};background:color-mix(in srgb, ${cfg.color} 16%, transparent)"><i class="fa fa-${cfg.icon}"></i> ${cfg.label}</div>${rows || '<div class="popup-row">Không có dữ liệu</div>'}`;
}

function showInfoTab(key, props) {
    const cfg = LAYER_CFG[key];
    const rows = Object.entries(props)
        .filter(([field, value]) => FIELD_LABELS[field] !== undefined && value !== null && value !== '')
        .map(([field, value]) => `<tr><td>${FIELD_LABELS[field] || field}</td><td>${value}</td></tr>`)
        .join('');
    document.getElementById('infoPlaceholder').style.display = 'none';
    document.getElementById('infoContent').style.display = 'block';
    document.getElementById('infoContent').innerHTML = `<div class="info-card"><div class="popup-hd" style="color:${cfg.color};background:color-mix(in srgb, ${cfg.color} 16%, transparent)"><i class="fa fa-${cfg.icon}"></i> ${cfg.label}</div><table>${rows || '<tr><td colspan="2">Không có dữ liệu</td></tr>'}</table></div>`;
    document.querySelector('[onclick="switchTab(\'tabInfo\',this)"]')?.click();
}

function updateCount(key, count) {
    document.getElementById('cnt_' + key).textContent = count.toLocaleString('vi-VN') + ' đối tượng';
    const stat = document.getElementById('s_' + key);
    if (stat) stat.textContent = count.toLocaleString('vi-VN');
}

function initLayer(key, data) {
    if (!data || !data.features || !data.features.length) {
        updateCount(key, 0);
        return;
    }
    const cfg = LAYER_CFG[key];
    const pane = cfg.type === 'polygon' ? 'polygonsPane' : (cfg.type === 'line' ? 'linesPane' : 'pointsPane');
    layers[key] = L.geoJSON(data, {
        pane,
        style: cfg.type !== 'point' ? {
            color: cfg.color,
            weight: cfg.type === 'line' ? 2.4 : 1.2,
            opacity: cfg.opacity / 100,
            fillColor: cfg.color,
            fillOpacity: cfg.type === 'polygon' ? (cfg.opacity / 100) * 0.35 : 0
        } : undefined,
        pointToLayer: cfg.type === 'point' ? (_, latlng) => L.marker(latlng, { icon: makeIcon(cfg.color, cfg.icon), pane, keyboard: false }) : undefined,
        onEachFeature: (feature, layer) => {
            const props = feature.properties || {};
            if (layer.options) layer.options.keyboard = false;
            layer.bindPopup(popupContent(key, props), { maxWidth: 280 });
            layer.on('click', () => showInfoTab(key, props));
            if (cfg.type !== 'point') {
                layer.on('mouseover', function() { this.setStyle({ color: '#fff', weight: (cfg.type === 'line' ? 2.4 : 1.2) + 1.2, opacity: 1 }); });
                layer.on('mouseout', function() { layers[key].resetStyle(this); });
            }
            if (key === 'thuadat' && props.sothua) {
                layer.bindTooltip(String(props.sothua), { direction: 'center' });
            }
        }
    }).addTo(map);
    updateCount(key, data.features.length);
}

function buildChart() {
    const items = Object.entries(LAYER_CFG).map(([key, cfg]) => ({
        key,
        label: cfg.label,
        color: cfg.color,
        count: GEO[key]?.features?.length || 0
    })).filter(item => item.count > 0).sort((a, b) => b.count - a.count);
    const max = Math.max(...items.map(item => item.count), 1);
    const el = document.getElementById('chartBars');
    el.innerHTML = items.map(item => `<div class="chart-row"><span>${item.label}</span><div class="chart-track"><div class="chart-fill" style="background:${item.color};width:0%" data-pct="${Math.round(item.count / max * 100)}"></div></div><span class="mono">${item.count}</span></div>`).join('');
    setTimeout(() => el.querySelectorAll('.chart-fill').forEach(node => { node.style.width = node.dataset.pct + '%'; }), 80);
}

function doSearch(query) {
    query = query.trim().toLowerCase();
    const resultEl = document.getElementById('searchResults');
    if (!query) {
        resultEl.className = 'empty';
        resultEl.innerHTML = 'Nhập từ khóa để tìm kiếm';
        return;
    }
    const scopes = {
        thuadat: document.getElementById('scope_thuadat').checked,
        giaothong: document.getElementById('scope_giaothong').checked,
        trucuuhoa: document.getElementById('scope_trucuuhoa').checked,
        van: document.getElementById('scope_van').checked
    };
    const results = [];
    Object.entries(scopes).forEach(([key, active]) => {
        if (!active || !GEO[key]?.features) return;
        GEO[key].features.forEach(feature => {
            if (results.length >= 20) return;
            const props = feature.properties || {};
            const text = Object.values(props).filter(Boolean).join(' ').toLowerCase();
            if (!text.includes(query)) return;
            const cfg = LAYER_CFG[key];
            results.push({
                key,
                feature,
                title: props.sothua || props.loaimat || props.loaitru || props.covan || ('ID: ' + (props.id ?? '')),
                sub: props.chusohuu || props.quyhoach || props.vatlieu || cfg.label,
                color: cfg.color,
                icon: cfg.icon
            });
        });
    });
    if (!results.length) {
        resultEl.className = 'empty';
        resultEl.innerHTML = 'Không tìm thấy kết quả';
        return;
    }
    window._searchResults = results;
    resultEl.className = '';
    resultEl.innerHTML = results.map((item, index) => `<div class="search-item" onclick="zoomToFeature(${index})"><div style="display:flex;gap:8px;align-items:center"><i class="fa fa-${item.icon}" style="color:${item.color};width:16px;text-align:center"></i><div><div style="font-size:12px;font-weight:600">${item.title}</div><div class="muted" style="font-size:11px">${item.sub}</div></div></div></div>`).join('');
}

function zoomToFeature(index) {
    const item = window._searchResults[index];
    if (!item) return;
    const temp = L.geoJSON(item.feature);
    const bounds = temp.getBounds();
    if (bounds.isValid()) map.fitBounds(bounds, { maxZoom: 19, padding: [40, 40] });
    const highlight = L.geoJSON(item.feature, { style: { color: '#fff', weight: 3, fillColor: LAYER_CFG[item.key].color, fillOpacity: 0.35 } }).addTo(map);
    setTimeout(() => map.removeLayer(highlight), 1800);
    if (window.innerWidth < 768) toggleSidebar();
}

function switchTab(id, el) {
    document.querySelectorAll('.tab-pane').forEach(node => node.classList.remove('active'));
    document.querySelectorAll('.sb-nav-item').forEach(node => node.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    el.classList.add('active');
}

function toggleExpand(id, trigger) {
    const panel = document.getElementById(id);
    panel.classList.toggle('open');
    trigger.querySelector('i').style.transform = panel.classList.contains('open') ? 'rotate(180deg)' : '';
}

function toggleLayer(key, enabled) {
    if (!layers[key]) return;
    if (enabled) map.addLayer(layers[key]); else map.removeLayer(layers[key]);
}

function setOpacity(key, value, input) {
    const opacity = value / 100;
    input.parentElement.querySelector('.mono').textContent = value + '%';
    if (!layers[key]) return;
    layers[key].eachLayer(layer => {
        if (layer.setOpacity) layer.setOpacity(opacity);
        else if (layer.setStyle) layer.setStyle({ opacity, fillOpacity: LAYER_CFG[key].type === 'polygon' ? opacity * 0.35 : 0 });
    });
}

function switchBasemap(type) {
    document.getElementById('bm_osm_label').classList.toggle('active', type === 'osm');
    document.getElementById('bm_sat_label').classList.toggle('active', type === 'sat');
    if (type === 'osm') { map.addLayer(OSM_LAYER); map.removeLayer(SAT_LAYER); }
    else { map.addLayer(SAT_LAYER); map.removeLayer(OSM_LAYER); }
}

function toggleSidebar() {
    document.getElementById('sbSidebar').classList.toggle('open');
    setTimeout(() => map.invalidateSize(), 250);
}

function toggleTheme() {
    const root = document.getElementById('sbRoot');
    const dark = root.getAttribute('data-theme') === 'dark';
    root.setAttribute('data-theme', dark ? 'light' : 'dark');
    document.getElementById('themeIcon').className = dark ? 'fa fa-moon' : 'fa fa-circle-half-stroke';
    showToast(dark ? 'Giao diện sáng' : 'Giao diện tối');
}

function showToast(message) {
    const toast = document.getElementById('sbToast');
    toast.textContent = message;
    toast.classList.add('show');
    clearTimeout(toast._id);
    toast._id = setTimeout(() => toast.classList.remove('show'), 2200);
}

let measureControl;
let measuring = false;
function toggleMeasure() {
    if (!measureControl) {
        measureControl = new L.Control.Measure({ position: 'bottomleft', primaryLengthUnit: 'meters', secondaryLengthUnit: 'kilometers', primaryAreaUnit: 'sqmeters' });
        measureControl.addTo(map);
    }
    measuring = !measuring;
    document.getElementById('fabMeasure').classList.toggle('active', measuring);
    document.querySelector('.leaflet-control-measure-toggle')?.click();
}

function locateMe() {
    map.locate({ setView: true, maxZoom: 17 });
    map.once('locationfound', event => {
        L.circleMarker(event.latlng, { radius: 8, color: '#1f6feb', fillColor: '#1f6feb', fillOpacity: .45, weight: 3 }).addTo(map).bindPopup('Vị trí của bạn').openPopup();
        showToast('Đã xác định vị trí');
    });
    map.once('locationerror', () => showToast('Không thể xác định vị trí'));
}

function resetView() {
    const valid = Object.values(layers).filter(layer => layer && layer.getBounds && layer.getBounds().isValid());
    if (!valid.length) return map.setView(CENTER, ZOOM);
    const group = L.featureGroup(valid);
    if (group.getBounds().isValid()) map.fitBounds(group.getBounds(), { padding: [20, 20] });
}

map.on('mousemove', event => {
    document.getElementById('coordDisplay').textContent = `${event.latlng.lat.toFixed(6)}, ${event.latlng.lng.toFixed(6)}`;
});
map.on('zoomend', () => {
    document.getElementById('zoomDisplay').textContent = 'Zoom: ' + map.getZoom();
});

(async function bootstrap() {
    for (const key of LOAD_ORDER) {
        document.getElementById('loaderSub').textContent = 'Đang tải: ' + LAYER_CFG[key].label + '...';
        await new Promise(resolve => setTimeout(resolve, 80));
        initLayer(key, GEO[key]);
    }
    buildChart();
    document.getElementById('zoomDisplay').textContent = 'Zoom: ' + map.getZoom();
    resetView();
    document.getElementById('sbLoader').classList.add('hidden');
    showToast('Đã tải xong ' + LOAD_ORDER.length + ' lớp dữ liệu sb_');
})();
</script>
