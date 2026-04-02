<?php
/**
 * View: Bản đồ KDT Tăng Long - Quản lý hạ tầng đô thị
 * Các lớp: Ranh, Thửa đất, Trạm biến áp, Cấp nước, Cây xanh, Chiếu sáng, Giao thông, Hạ thế
 */
use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $layers array - GeoJSON data keyed by layer name */

$this->title = 'Bản đồ KDT Tăng Long';
?>

<!-- ======================== ASSETS ======================== -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-measure/dist/leaflet-measure.css"/>

<!-- ======================== STYLES ======================== -->
<style>
/* ── Variables ── */
:root {
    --bg-900: #0d1117;
    --bg-800: #161b22;
    --bg-700: #1c2128;
    --bg-600: #21262d;
    --bg-500: #30363d;
    --border: #30363d;
    --border-light: #3d444d;
    --text-primary: #e6edf3;
    --text-secondary: #8d96a0;
    --text-muted: #545d68;
    --accent: #2ea043;
    --accent-blue: #1f6feb;
    --accent-orange: #d29922;
    --accent-red: #da3633;
    --accent-cyan: #1abc9c;
    --radius: 8px;
    --radius-sm: 5px;
    --sidebar-w: 340px;
    --header-h: 56px;
    --transition: all 0.2s cubic-bezier(0.4,0,0.2,1);

    /* Layer colors */
    --c-ranh:      #f0b429;
    --c-thuadat:   #7c3aed;
    --c-trambiap:  #ef4444;
    --c-capnuoc:   #06b6d4;
    --c-cayxanh:   #22c55e;
    --c-chieusang: #f59e0b;
    --c-giaothong: #94a3b8;
    --c-hathe:     #a855f7;
}
[data-theme="light"] {
    --bg-900: #f3f4f6;
    --bg-800: #ffffff;
    --bg-700: #f9fafb;
    --bg-600: #f3f4f6;
    --bg-500: #e5e7eb;
    --border: #e5e7eb;
    --border-light: #d1d5db;
    --text-primary: #111827;
    --text-secondary: #374151;
    --text-muted: #9ca3af;
}

/* ── Reset ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { overflow: hidden; }

/* ── Root Container ── */
.tl-root {
    position: fixed; inset: 0;
    display: flex;
    font-family: 'Be Vietnam Pro', sans-serif;
    background: var(--bg-900);
    color: var(--text-primary);
    z-index: 99999;
}

/* ════════════════════════════════
   SIDEBAR
═════════════════════════════════ */
.tl-sidebar {
    width: var(--sidebar-w);
    min-width: var(--sidebar-w);
    height: 100%;
    display: flex;
    flex-direction: column;
    background: var(--bg-800);
    border-right: 1px solid var(--border);
    z-index: 1001;
    transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
    overflow: hidden;
}

/* Header */
.tl-header {
    height: var(--header-h);
    background: var(--bg-800);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 16px;
    flex-shrink: 0;
}
.tl-logo {
    width: 32px; height: 32px;
    background: linear-gradient(135deg, var(--accent-blue), var(--accent));
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; color: white; flex-shrink: 0;
}
.tl-title { flex: 1; }
.tl-title h1 { font-size: 13px; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
.tl-title span { font-size: 11px; color: var(--text-muted); font-weight: 400; }
.tl-header-actions { display: flex; gap: 4px; }
.icon-btn {
    width: 28px; height: 28px;
    border: 1px solid var(--border);
    background: var(--bg-600);
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--text-secondary); font-size: 11px;
    transition: var(--transition);
}
.icon-btn:hover { background: var(--bg-500); color: var(--text-primary); border-color: var(--border-light); }

/* Nav Tabs */
.tl-nav {
    display: flex;
    border-bottom: 1px solid var(--border);
    background: var(--bg-800);
    flex-shrink: 0;
}
.tl-nav-item {
    flex: 1; padding: 10px 0;
    text-align: center; font-size: 11px; font-weight: 600;
    color: var(--text-muted); cursor: pointer; border-bottom: 2px solid transparent;
    transition: var(--transition); text-transform: uppercase; letter-spacing: 0.4px;
}
.tl-nav-item:hover { color: var(--text-secondary); background: var(--bg-700); }
.tl-nav-item.active { color: var(--accent-blue); border-bottom-color: var(--accent-blue); background: var(--bg-800); }
.tl-nav-item i { display: block; font-size: 14px; margin-bottom: 3px; }

/* Content */
.tl-content { flex: 1; overflow-y: auto; overflow-x: hidden; }
.tl-content::-webkit-scrollbar { width: 4px; }
.tl-content::-webkit-scrollbar-track { background: transparent; }
.tl-content::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
.tab-pane { display: none; padding: 16px; animation: fadeUp 0.2s ease; }
.tab-pane.active { display: block; }
@keyframes fadeUp { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }

/* ── Section Label ── */
.section-label {
    font-size: 10px; font-weight: 700; letter-spacing: 0.8px;
    text-transform: uppercase; color: var(--text-muted);
    margin: 18px 0 8px 0; padding-left: 2px;
    display: flex; align-items: center; gap: 6px;
}
.section-label::after {
    content: ''; flex: 1; height: 1px; background: var(--border);
}

/* ── Basemap Toggle ── */
.basemap-toggle {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 6px; margin-bottom: 4px;
}
.bm-option { position: relative; cursor: pointer; border-radius: var(--radius-sm); overflow: hidden; border: 2px solid transparent; transition: var(--transition); }
.bm-option:hover { border-color: var(--border-light); }
.bm-option.active { border-color: var(--accent-blue); }
.bm-option input { position: absolute; opacity: 0; pointer-events: none; }
.bm-thumb { height: 52px; background: var(--bg-600); display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 4px; }
.bm-thumb i { font-size: 18px; color: var(--text-secondary); }
.bm-label { font-size: 10px; font-weight: 600; color: var(--text-muted); }
.bm-option.active .bm-thumb i, .bm-option.active .bm-label { color: var(--accent-blue); }

/* ── Layer Item ── */
.layer-item {
    background: var(--bg-700);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    margin-bottom: 6px;
    overflow: hidden;
    transition: var(--transition);
}
.layer-item:hover { border-color: var(--border-light); }
.layer-row {
    display: flex; align-items: center;
    padding: 9px 10px; gap: 8px; cursor: pointer;
}
.layer-toggle {
    position: relative; width: 32px; height: 17px; flex-shrink: 0;
}
.layer-toggle input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
    position: absolute; inset: 0;
    background: var(--bg-500); border-radius: 17px; cursor: pointer;
    transition: var(--transition);
}
.toggle-slider::before {
    content: ''; position: absolute;
    width: 13px; height: 13px; border-radius: 50%;
    background: var(--text-muted); top: 2px; left: 2px;
    transition: var(--transition);
}
.layer-toggle input:checked + .toggle-slider { background: var(--layer-color, var(--accent-blue)); }
.layer-toggle input:checked + .toggle-slider::before { background: white; transform: translateX(15px); }

.layer-dot {
    width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
    background: var(--layer-color, #888);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--layer-color, #888) 20%, transparent);
}
.layer-dot.line { border-radius: 2px; height: 4px; width: 16px; }
.layer-dot.polygon { border-radius: 3px; height: 10px; width: 14px; border: 2px solid var(--layer-color, #888); background: color-mix(in srgb, var(--layer-color,#888) 20%, transparent); }

.layer-meta { flex: 1; min-width: 0; }
.layer-name { font-size: 13px; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.layer-count { font-size: 10px; color: var(--text-muted); font-family: 'JetBrains Mono', monospace; }
.layer-expand-btn { color: var(--text-muted); font-size: 10px; cursor: pointer; transition: var(--transition); padding: 2px 4px; }
.layer-expand-btn:hover { color: var(--text-primary); }

.layer-extra {
    padding: 0 10px 10px 44px;
    border-top: 1px solid var(--border);
    background: var(--bg-800);
    display: none;
}
.layer-extra.open { display: block; }
.opacity-row { display: flex; align-items: center; gap: 8px; padding-top: 8px; }
.opacity-row label { font-size: 11px; color: var(--text-muted); width: 60px; flex-shrink: 0; }
.opacity-slider {
    flex: 1; -webkit-appearance: none; height: 3px;
    background: var(--bg-500); border-radius: 3px; outline: none;
}
.opacity-slider::-webkit-slider-thumb {
    -webkit-appearance: none; width: 12px; height: 12px; border-radius: 50%;
    background: var(--accent-blue); cursor: pointer;
    border: 2px solid var(--bg-800);
}
.opacity-val { font-size: 11px; color: var(--text-muted); font-family: 'JetBrains Mono', monospace; width: 30px; text-align: right; }

/* ── Statistics Panel ── */
.stat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 16px; }
.stat-card {
    background: var(--bg-700); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 12px 10px;
    display: flex; align-items: center; gap: 8px;
}
.stat-icon { width: 32px; height: 32px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0; }
.stat-body { min-width: 0; }
.stat-val { font-size: 18px; font-weight: 700; color: var(--text-primary); font-family: 'JetBrains Mono', monospace; line-height: 1; }
.stat-lbl { font-size: 10px; color: var(--text-muted); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* Chart bar */
.chart-bar-row { display: flex; align-items: center; gap: 8px; margin-bottom: 7px; }
.chart-bar-label { font-size: 11px; color: var(--text-secondary); width: 90px; flex-shrink: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.chart-bar-track { flex: 1; height: 6px; background: var(--bg-500); border-radius: 3px; overflow: hidden; }
.chart-bar-fill { height: 100%; border-radius: 3px; transition: width 1s cubic-bezier(0.4,0,0.2,1); }
.chart-bar-count { font-size: 10px; color: var(--text-muted); font-family: 'JetBrains Mono', monospace; width: 28px; text-align: right; flex-shrink: 0; }

/* ── Search ── */
.search-box {
    display: flex; align-items: center;
    background: var(--bg-700); border: 1px solid var(--border);
    border-radius: 20px; padding: 0 14px; gap: 8px; margin-bottom: 12px;
    transition: var(--transition);
}
.search-box:focus-within { border-color: var(--accent-blue); background: var(--bg-800); }
.search-box i { color: var(--text-muted); font-size: 13px; }
.search-box input { background: none; border: none; outline: none; flex: 1; font-size: 13px; color: var(--text-primary); padding: 10px 0; font-family: 'Be Vietnam Pro', sans-serif; }
.search-box input::placeholder { color: var(--text-muted); }
.search-result-item {
    padding: 9px 10px; border-radius: var(--radius-sm);
    cursor: pointer; border: 1px solid transparent; margin-bottom: 4px;
    transition: var(--transition); background: var(--bg-700);
}
.search-result-item:hover { background: var(--bg-600); border-color: var(--border-light); }
.sri-title { font-size: 12px; font-weight: 600; color: var(--text-primary); }
.sri-sub { font-size: 11px; color: var(--text-muted); }
.no-result { text-align: center; padding: 40px 0; color: var(--text-muted); font-size: 13px; }
.no-result i { font-size: 36px; display: block; margin-bottom: 12px; opacity: 0.3; }

/* ── Info Panel ── */
.info-placeholder { text-align: center; padding: 50px 0; color: var(--text-muted); }
.info-placeholder i { font-size: 40px; display: block; margin-bottom: 12px; opacity: 0.2; }
.info-placeholder p { font-size: 13px; }
.info-card { background: var(--bg-700); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; }
.info-card-header { padding: 10px 14px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 6px; }
.info-table { width: 100%; border-collapse: collapse; }
.info-table tr:not(:last-child) td { border-bottom: 1px solid var(--border); }
.info-table td { padding: 7px 14px; font-size: 12px; }
.info-table td:first-child { color: var(--text-muted); width: 45%; }
.info-table td:last-child { color: var(--text-primary); font-weight: 500; word-break: break-word; }

/* ── Footer ── */
.tl-footer {
    border-top: 1px solid var(--border);
    padding: 10px 16px;
    display: flex; align-items: center; justify-content: space-between;
    flex-shrink: 0;
}
.coord-display { font-size: 10px; color: var(--text-muted); font-family: 'JetBrains Mono', monospace; }
.zoom-display { font-size: 10px; color: var(--text-muted); font-family: 'JetBrains Mono', monospace; }

/* ════════════════════════════════
   MAP AREA
═════════════════════════════════ */
.tl-map-wrap { flex: 1; position: relative; }
#tl-map { width: 100%; height: 100%; }

/* Map Fab Toolbar */
.map-fab-group {
    position: absolute; right: 16px; top: 16px;
    display: flex; flex-direction: column; gap: 6px; z-index: 1000;
}
.map-fab {
    width: 36px; height: 36px;
    background: var(--bg-800); border: 1px solid var(--border);
    border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--text-secondary); font-size: 13px;
    transition: var(--transition); box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}
.map-fab:hover { background: var(--bg-600); color: var(--text-primary); border-color: var(--border-light); transform: translateY(-1px); }
.map-fab.active { background: var(--accent-blue); color: white; border-color: var(--accent-blue); }
.fab-sep { height: 1px; background: var(--border); margin: 2px 0; }

/* Mobile sidebar toggle */
.mobile-toggle {
    display: none; position: absolute; left: 12px; top: 12px; z-index: 2000;
    background: var(--bg-800); border: 1px solid var(--border);
    border-radius: var(--radius-sm); width: 36px; height: 36px;
    align-items: center; justify-content: center; cursor: pointer; color: var(--text-primary);
}

/* Loading */
.tl-loader {
    position: absolute; inset: 0;
    background: rgba(13,17,23,0.9);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    z-index: 9999; backdrop-filter: blur(4px);
}
.tl-loader.hidden { display: none; }
.tl-spinner {
    width: 36px; height: 36px;
    border: 3px solid var(--border);
    border-top-color: var(--accent-blue);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-bottom: 12px;
}
@keyframes spin { to { transform: rotate(360deg); } }
.tl-loader-text { font-size: 13px; color: var(--text-secondary); font-weight: 500; }
.tl-loader-sub { font-size: 11px; color: var(--text-muted); margin-top: 4px; }

/* ── Toast ── */
.tl-toast {
    position: absolute; bottom: 24px; left: 50%; transform: translateX(-50%);
    background: var(--bg-600); border: 1px solid var(--border-light);
    border-radius: 20px; padding: 8px 16px; font-size: 12px; color: var(--text-primary);
    z-index: 3000; white-space: nowrap; box-shadow: 0 4px 12px rgba(0,0,0,0.4);
    opacity: 0; pointer-events: none; transition: opacity 0.3s;
}
.tl-toast.show { opacity: 1; }

/* ── Custom Leaflet Popup ── */
.leaflet-popup-content-wrapper {
    background: var(--bg-800) !important;
    border: 1px solid var(--border-light) !important;
    border-radius: var(--radius) !important;
    box-shadow: 0 8px 24px rgba(0,0,0,0.5) !important;
    padding: 0 !important;
}
.leaflet-popup-content { margin: 0 !important; width: 260px !important; }
.leaflet-popup-tip-container { display: none; }
.leaflet-popup-close-button {
    color: var(--text-muted) !important; font-size: 18px !important;
    top: 6px !important; right: 8px !important; z-index: 10;
}
.leaflet-popup-close-button:hover { color: var(--text-primary) !important; }
.popup-hd { padding: 10px 14px; font-size: 12px; font-weight: 700; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 6px; border-radius: var(--radius) var(--radius) 0 0; }
.popup-body { max-height: 220px; overflow-y: auto; }
.popup-row { display: flex; padding: 6px 14px; font-size: 11px; border-bottom: 1px solid var(--border); }
.popup-row:last-child { border: none; }
.popup-key { color: var(--text-muted); width: 45%; flex-shrink: 0; }
.popup-val { color: var(--text-primary); font-weight: 500; word-break: break-word; }

/* Leaflet overrides */
.leaflet-control-zoom a { background: var(--bg-800) !important; color: var(--text-primary) !important; border-color: var(--border) !important; }
.leaflet-control-zoom a:hover { background: var(--bg-600) !important; }
.leaflet-control-attribution { background: rgba(13,17,23,0.7) !important; color: var(--text-muted) !important; font-size: 9px !important; }
.leaflet-control-attribution a { color: var(--text-muted) !important; }

/* ── Responsive ── */
@media (max-width: 768px) {
    .tl-sidebar { position: absolute; height: 100%; transform: translateX(-100%); width: 88%; z-index: 1002; }
    .tl-sidebar.open { transform: translateX(0); box-shadow: 4px 0 20px rgba(0,0,0,0.5); }
    .mobile-toggle { display: flex; }
    .map-fab-group { top: 60px; right: 10px; }
}
</style>

<!-- ======================== HTML ======================== -->
<div class="tl-root" id="tlRoot" data-theme="dark">
    <!-- Mobile Toggle -->
    <button class="mobile-toggle" id="mobileToggle" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>

    <!-- ═══ SIDEBAR ═══ -->
    <aside class="tl-sidebar" id="tlSidebar">
        <!-- Header -->
        <div class="tl-header">
            <div class="tl-logo"><i class="fa fa-map"></i></div>
            <div class="tl-title">
                <h1>KĐT Tân Long</h1>
                <span>Quản lý hạ tầng đô thị</span>
            </div>
            <div class="tl-header-actions">
                <button class="icon-btn" onclick="toggleTheme()" title="Đổi giao diện"><i class="fa fa-circle-half-stroke" id="themeIcon"></i></button>
                <button class="icon-btn" onclick="exportMapPng()" title="Xuất ảnh bản đồ"><i class="fa fa-download"></i></button>
            </div>
        </div>

        <!-- Nav -->
        <nav class="tl-nav">
            <div class="tl-nav-item active" onclick="switchTab('tabLayers', this)">
                <i class="fa fa-layer-group"></i>Lớp
            </div>
            <div class="tl-nav-item" onclick="switchTab('tabStats', this)">
                <i class="fa fa-chart-bar"></i>Thống kê
            </div>
            <div class="tl-nav-item" onclick="switchTab('tabSearch', this)">
                <i class="fa fa-search"></i>Tìm kiếm
            </div>
            <div class="tl-nav-item" onclick="switchTab('tabInfo', this)" id="tabInfoNav">
                <i class="fa fa-info-circle"></i>Thông tin
            </div>
        </nav>

        <!-- ─── Content ─── -->
        <div class="tl-content">

            <!-- TAB: LAYERS -->
            <div class="tab-pane active" id="tabLayers">
                <div class="section-label">Bản đồ nền</div>
                <div class="basemap-toggle">
                    <label class="bm-option active" id="bm_osm_label">
                        <input type="radio" name="bm" checked onchange="switchBasemap('osm')">
                        <div class="bm-thumb"><i class="fa fa-map" style="color:var(--accent-blue)"></i><span class="bm-label">OpenStreetMap</span></div>
                    </label>
                    <label class="bm-option" id="bm_sat_label">
                        <input type="radio" name="bm" onchange="switchBasemap('sat')">
                        <div class="bm-thumb"><i class="fa fa-satellite-dish" style="color:var(--accent-orange)"></i><span class="bm-label">Vệ tinh Esri</span></div>
                    </label>
                </div>

                <div class="section-label">Dữ liệu không gian</div>

                <!-- Ranh giới -->
                <div class="layer-item" style="--layer-color: var(--c-ranh)">
                    <div class="layer-row">
                        <label class="layer-toggle"><input type="checkbox" id="chk_ranh" checked onchange="toggleLayer('ranh',this.checked)"><span class="toggle-slider"></span></label>
                        <div class="layer-dot line" style="background:var(--c-ranh)"></div>
                        <div class="layer-meta"><div class="layer-name">Ranh giới KĐT</div><div class="layer-count" id="cnt_ranh">0 đối tượng</div></div>
                        <span class="layer-expand-btn" onclick="toggleExpand('ranh_extra',this)"><i class="fa fa-chevron-down"></i></span>
                    </div>
                    <div class="layer-extra" id="ranh_extra">
                        <div class="opacity-row">
                            <label>Độ mờ</label>
                            <input type="range" class="opacity-slider" min="0" max="100" value="80" oninput="setOpacity('ranh',this.value,this)">
                            <span class="opacity-val">80%</span>
                        </div>
                    </div>
                </div>

                <!-- Thửa đất -->
                <div class="layer-item" style="--layer-color: var(--c-thuadat)">
                    <div class="layer-row">
                        <label class="layer-toggle"><input type="checkbox" id="chk_thuadat" checked onchange="toggleLayer('thuadat',this.checked)"><span class="toggle-slider"></span></label>
                        <div class="layer-dot polygon" style="--layer-color:var(--c-thuadat)"></div>
                        <div class="layer-meta"><div class="layer-name">Thửa đất</div><div class="layer-count" id="cnt_thuadat">0 đối tượng</div></div>
                        <span class="layer-expand-btn" onclick="toggleExpand('thuadat_extra',this)"><i class="fa fa-chevron-down"></i></span>
                    </div>
                    <div class="layer-extra" id="thuadat_extra">
                        <div class="opacity-row">
                            <label>Độ mờ</label>
                            <input type="range" class="opacity-slider" min="0" max="100" value="60" oninput="setOpacity('thuadat',this.value,this)">
                            <span class="opacity-val">60%</span>
                        </div>
                    </div>
                </div>

                <!-- Giao thông -->
                <div class="layer-item" style="--layer-color: var(--c-giaothong)">
                    <div class="layer-row">
                        <label class="layer-toggle"><input type="checkbox" id="chk_giaothong" checked onchange="toggleLayer('giaothong',this.checked)"><span class="toggle-slider"></span></label>
                        <div class="layer-dot line" style="background:var(--c-giaothong)"></div>
                        <div class="layer-meta"><div class="layer-name">Giao thông</div><div class="layer-count" id="cnt_giaothong">0 đối tượng</div></div>
                        <span class="layer-expand-btn" onclick="toggleExpand('giaothong_extra',this)"><i class="fa fa-chevron-down"></i></span>
                    </div>
                    <div class="layer-extra" id="giaothong_extra">
                        <div class="opacity-row">
                            <label>Độ mờ</label>
                            <input type="range" class="opacity-slider" min="0" max="100" value="80" oninput="setOpacity('giaothong',this.value,this)">
                            <span class="opacity-val">80%</span>
                        </div>
                    </div>
                </div>

                <!-- Cấp nước -->
                <div class="layer-item" style="--layer-color: var(--c-capnuoc)">
                    <div class="layer-row">
                        <label class="layer-toggle"><input type="checkbox" id="chk_capnuoc" checked onchange="toggleLayer('capnuoc',this.checked)"><span class="toggle-slider"></span></label>
                        <div class="layer-dot line" style="background:var(--c-capnuoc)"></div>
                        <div class="layer-meta"><div class="layer-name">Cấp nước</div><div class="layer-count" id="cnt_capnuoc">0 đối tượng</div></div>
                        <span class="layer-expand-btn" onclick="toggleExpand('capnuoc_extra',this)"><i class="fa fa-chevron-down"></i></span>
                    </div>
                    <div class="layer-extra" id="capnuoc_extra">
                        <div class="opacity-row">
                            <label>Độ mờ</label>
                            <input type="range" class="opacity-slider" min="0" max="100" value="90" oninput="setOpacity('capnuoc',this.value,this)">
                            <span class="opacity-val">90%</span>
                        </div>
                    </div>
                </div>

                <!-- Hạ thế -->
                <div class="layer-item" style="--layer-color: var(--c-hathe)">
                    <div class="layer-row">
                        <label class="layer-toggle"><input type="checkbox" id="chk_hathe" checked onchange="toggleLayer('hathe',this.checked)"><span class="toggle-slider"></span></label>
                        <div class="layer-dot line" style="background:var(--c-hathe)"></div>
                        <div class="layer-meta"><div class="layer-name">Hạ thế</div><div class="layer-count" id="cnt_hathe">0 đối tượng</div></div>
                        <span class="layer-expand-btn" onclick="toggleExpand('hathe_extra',this)"><i class="fa fa-chevron-down"></i></span>
                    </div>
                    <div class="layer-extra" id="hathe_extra">
                        <div class="opacity-row">
                            <label>Độ mờ</label>
                            <input type="range" class="opacity-slider" min="0" max="100" value="80" oninput="setOpacity('hathe',this.value,this)">
                            <span class="opacity-val">80%</span>
                        </div>
                    </div>
                </div>

                <div class="section-label">Điểm đối tượng</div>

                <!-- Trạm biến áp -->
                <div class="layer-item" style="--layer-color: var(--c-trambiap)">
                    <div class="layer-row">
                        <label class="layer-toggle"><input type="checkbox" id="chk_trambiap" checked onchange="toggleLayer('trambiap',this.checked)"><span class="toggle-slider"></span></label>
                        <div class="layer-dot" style="background:var(--c-trambiap)"></div>
                        <div class="layer-meta"><div class="layer-name">Trạm biến áp</div><div class="layer-count" id="cnt_trambiap">0 đối tượng</div></div>
                        <span class="layer-expand-btn" onclick="toggleExpand('trambiap_extra',this)"><i class="fa fa-chevron-down"></i></span>
                    </div>
                    <div class="layer-extra" id="trambiap_extra">
                        <div class="opacity-row">
                            <label>Độ mờ</label>
                            <input type="range" class="opacity-slider" min="0" max="100" value="100" oninput="setOpacity('trambiap',this.value,this)">
                            <span class="opacity-val">100%</span>
                        </div>
                    </div>
                </div>

                <!-- Cây xanh -->
                <div class="layer-item" style="--layer-color: var(--c-cayxanh)">
                    <div class="layer-row">
                        <label class="layer-toggle"><input type="checkbox" id="chk_cayxanh" checked onchange="toggleLayer('cayxanh',this.checked)"><span class="toggle-slider"></span></label>
                        <div class="layer-dot" style="background:var(--c-cayxanh)"></div>
                        <div class="layer-meta"><div class="layer-name">Cây xanh</div><div class="layer-count" id="cnt_cayxanh">0 đối tượng</div></div>
                        <span class="layer-expand-btn" onclick="toggleExpand('cayxanh_extra',this)"><i class="fa fa-chevron-down"></i></span>
                    </div>
                    <div class="layer-extra" id="cayxanh_extra">
                        <div class="opacity-row">
                            <label>Độ mờ</label>
                            <input type="range" class="opacity-slider" min="0" max="100" value="100" oninput="setOpacity('cayxanh',this.value,this)">
                            <span class="opacity-val">100%</span>
                        </div>
                    </div>
                </div>

                <!-- Chiếu sáng -->
                <div class="layer-item" style="--layer-color: var(--c-chieusang)">
                    <div class="layer-row">
                        <label class="layer-toggle"><input type="checkbox" id="chk_chieusang" checked onchange="toggleLayer('chieusang',this.checked)"><span class="toggle-slider"></span></label>
                        <div class="layer-dot" style="background:var(--c-chieusang)"></div>
                        <div class="layer-meta"><div class="layer-name">Chiếu sáng</div><div class="layer-count" id="cnt_chieusang">0 đối tượng</div></div>
                        <span class="layer-expand-btn" onclick="toggleExpand('chieusang_extra',this)"><i class="fa fa-chevron-down"></i></span>
                    </div>
                    <div class="layer-extra" id="chieusang_extra">
                        <div class="opacity-row">
                            <label>Độ mờ</label>
                            <input type="range" class="opacity-slider" min="0" max="100" value="100" oninput="setOpacity('chieusang',this.value,this)">
                            <span class="opacity-val">100%</span>
                        </div>
                    </div>
                </div>
            </div><!-- /tabLayers -->

            <!-- TAB: STATS -->
            <div class="tab-pane" id="tabStats">
                <div class="section-label">Tổng quan</div>
                <div class="stat-grid">
                    <div class="stat-card">
                        <div class="stat-icon" style="background:color-mix(in srgb,var(--c-thuadat) 15%,transparent)">
                            <i class="fa fa-vector-square" style="color:var(--c-thuadat)"></i>
                        </div>
                        <div class="stat-body">
                            <div class="stat-val" id="s_thuadat">—</div>
                            <div class="stat-lbl">Thửa đất</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background:color-mix(in srgb,var(--c-giaothong) 15%,transparent)">
                            <i class="fa fa-road" style="color:var(--c-giaothong)"></i>
                        </div>
                        <div class="stat-body">
                            <div class="stat-val" id="s_giaothong">—</div>
                            <div class="stat-lbl">Đoạn đường</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background:color-mix(in srgb,var(--c-cayxanh) 15%,transparent)">
                            <i class="fa fa-tree" style="color:var(--c-cayxanh)"></i>
                        </div>
                        <div class="stat-body">
                            <div class="stat-val" id="s_cayxanh">—</div>
                            <div class="stat-lbl">Cây xanh</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background:color-mix(in srgb,var(--c-chieusang) 15%,transparent)">
                            <i class="fa fa-lightbulb" style="color:var(--c-chieusang)"></i>
                        </div>
                        <div class="stat-body">
                            <div class="stat-val" id="s_chieusang">—</div>
                            <div class="stat-lbl">Đèn chiếu sáng</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background:color-mix(in srgb,var(--c-trambiap) 15%,transparent)">
                            <i class="fa fa-bolt" style="color:var(--c-trambiap)"></i>
                        </div>
                        <div class="stat-body">
                            <div class="stat-val" id="s_trambiap">—</div>
                            <div class="stat-lbl">Trạm biến áp</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background:color-mix(in srgb,var(--c-capnuoc) 15%,transparent)">
                            <i class="fa fa-droplet" style="color:var(--c-capnuoc)"></i>
                        </div>
                        <div class="stat-body">
                            <div class="stat-val" id="s_capnuoc">—</div>
                            <div class="stat-lbl">Tuyến cấp nước</div>
                        </div>
                    </div>
                </div>

                <div class="section-label">Phân bố lớp dữ liệu</div>
                <div id="chartBars"></div>
            </div><!-- /tabStats -->

            <!-- TAB: SEARCH -->
            <div class="tab-pane" id="tabSearch">
                <div class="search-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Tìm thửa đất, chủ hộ, mã số..." oninput="doSearch(this.value)">
                </div>
                <div id="searchScopeRow" style="display:flex;gap:6px;margin-bottom:12px;flex-wrap:wrap;">
                    <label style="font-size:11px;color:var(--text-muted);display:flex;align-items:center;gap:4px;cursor:pointer;">
                        <input type="checkbox" id="scope_thuadat" checked style="accent-color:var(--c-thuadat)"> Thửa đất
                    </label>
                    <label style="font-size:11px;color:var(--text-muted);display:flex;align-items:center;gap:4px;cursor:pointer;">
                        <input type="checkbox" id="scope_cayxanh" checked style="accent-color:var(--c-cayxanh)"> Cây xanh
                    </label>
                    <label style="font-size:11px;color:var(--text-muted);display:flex;align-items:center;gap:4px;cursor:pointer;">
                        <input type="checkbox" id="scope_trambiap" checked style="accent-color:var(--c-trambiap)"> Trạm BA
                    </label>
                </div>
                <div id="searchResults">
                    <div class="no-result"><i class="fa fa-search"></i>Nhập từ khóa để tìm kiếm</div>
                </div>
            </div><!-- /tabSearch -->

            <!-- TAB: INFO -->
            <div class="tab-pane" id="tabInfo">
                <div class="info-placeholder" id="infoPlaceholder">
                    <i class="fa fa-mouse-pointer"></i>
                    <p>Click vào đối tượng trên bản đồ để xem thông tin chi tiết</p>
                </div>
                <div id="infoContent" style="display:none"></div>
            </div><!-- /tabInfo -->

        </div><!-- /tl-content -->

        <!-- Footer with coords -->
        <div class="tl-footer">
            <span class="coord-display" id="coordDisplay">—, —</span>
            <span class="zoom-display" id="zoomDisplay">Zoom: —</span>
        </div>
    </aside><!-- /sidebar -->

    <!-- ═══ MAP ═══ -->
    <div class="tl-map-wrap">
        <div id="tl-map"></div>

        <!-- FAB Tools -->
        <div class="map-fab-group">
            <button class="map-fab" title="Vị trí của tôi" onclick="locateMe()"><i class="fa fa-crosshairs"></i></button>
            <button class="map-fab" title="Về vị trí ban đầu" onclick="resetView()"><i class="fa fa-house"></i></button>
            <div class="fab-sep"></div>
            <button class="map-fab" id="fabMeasure" title="Đo khoảng cách" onclick="toggleMeasure()"><i class="fa fa-ruler"></i></button>
            <div class="fab-sep"></div>
            <button class="map-fab" title="Phóng to" onclick="map.zoomIn()"><i class="fa fa-plus"></i></button>
            <button class="map-fab" title="Thu nhỏ" onclick="map.zoomOut()"><i class="fa fa-minus"></i></button>
        </div>

        <!-- Loader -->
        <div class="tl-loader" id="tlLoader">
            <div class="tl-spinner"></div>
            <div class="tl-loader-text">Đang tải dữ liệu bản đồ</div>
            <div class="tl-loader-sub" id="loaderSub">Khởi tạo hệ thống...</div>
        </div>

        <!-- Toast -->
        <div class="tl-toast" id="tlToast"></div>
    </div>
</div>

<!-- ======================== SCRIPTS ======================== -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-measure/dist/leaflet-measure.js"></script>

<script>

const GEO = <?= \yii\helpers\Json::encode($layers) ?>;

// ═══════════════════════════════════════════════════════════
//  CONFIG
// ═══════════════════════════════════════════════════════════
const CENTER = [10.738501928736335, 106.83312465486868];
const ZOOM   = 15;

const LAYER_CFG = {
    ranh:      { label: 'Ranh giới KĐT', type: 'line',    color: '#f0b429', weight: 2.5, fillOpacity: 0, opacity: 0.8 },
    thuadat:   { label: 'Thửa đất',      type: 'polygon', color: '#7c3aed', weight: 1,   fillOpacity: 0.15, fillColor: '#7c3aed', opacity: 0.6 },
    giaothong: { label: 'Giao thông',    type: 'line',    color: '#94a3b8', weight: 2,   fillOpacity: 0.7, fillColor: '#cbd5e1', opacity: 0.8 },
    capnuoc:   { label: 'Cấp nước',      type: 'line',    color: '#06b6d4', weight: 2,   opacity: 0.9 },
    hathe:     { label: 'Hạ thế',        type: 'point',    color: '#a855f7', weight: 2,   opacity: 0.8, icon: 'plug'  },
    trambiap:  { label: 'Trạm biến áp',  type: 'point',   color: '#ef4444', icon: 'bolt' },
    cayxanh:   { label: 'Cây xanh',      type: 'point',   color: '#22c55e', icon: 'tree' },
    chieusang: { label: 'Chiếu sáng',    type: 'point',   color: '#f59e0b', icon: 'lightbulb' },
};

const FIELD_LABELS = {
    id: 'ID', geom: null, OBJECTID: null,
    loai_dat: 'Loại đất', so_thua: 'Số thửa', tinhhinh_xd: 'Tình hình XD',
    chu_ho: 'Chủ hộ', Shape_Area: 'Diện tích (m²)', Shape_Leng: 'Chu vi (m)',
    ma_so: 'Mã số', loai_mba: 'Loại MBA', nam: 'Năm lắp đặt', tinh_trang: 'Tình trạng',
    FID_: null, Entity: null, Layer: null, Color: null, LineWt: null, RefName: null, Linetype: null, Elevation: null,
    loai_cay: 'Loại cây', nam_trong: 'Năm trồng',
    loai_den: 'Loại đèn',
    osm_id: 'OSM ID', code: null, fclass: 'Phân loại', name: 'Tên đường',
    ref: 'Tham chiếu', oneway: 'Một chiều', maxspeed: 'Tốc độ tối đa',
    layer: null, bridge: 'Cầu', tunnel: 'Hầm',
    ma: 'Mã',
    FID_Polygo: null, Handle: null, Layer_2: null, LyrFrzn: null, LyrLock: null, LyrOn: null,
    LyrVPFrzn: null, LyrHandle: null, EntColor: null, LyrColor: null, BlkColor: null,
    EntLinetyp: null, LyrLnType: null, BlkLinetyp: null, Thickness: null,
    EntLineWt: null, LyrLineWt: null, BlkLineWt: null, LTScale: null,
    ExtX: null, ExtY: null, ExtZ: null, DocName: null, DocPath: null, DocType: null, DocVer: null,
    FID_Polygo: null,
};

// ═══════════════════════════════════════════════════════════
//  MAP INIT
// ═══════════════════════════════════════════════════════════
const map = L.map('tl-map', { zoomControl: false, attributionControl: true }).setView(CENTER, ZOOM);

// Panes
['polygons','lines','points'].forEach((p, i) => {
    map.createPane(p + 'Pane');
    map.getPane(p + 'Pane').style.zIndex = 400 + i * 50;
});

// Basemaps
const OSM_LAYER = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap', maxZoom: 22
}).addTo(map);
const SAT_LAYER = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: '© Esri', maxZoom: 22
});

// Layers store
const layers = {};
const opacityStore = {};

// ═══════════════════════════════════════════════════════════
//  HELPERS
// ═══════════════════════════════════════════════════════════
function makeIcon(color, faIcon) {
    return L.divIcon({
        className: '',
        html: `<div style="
            background:${color}; width:22px; height:22px; border-radius:50%;
            border:2.5px solid rgba(255,255,255,0.8);
            display:flex; align-items:center; justify-content:center;
            box-shadow:0 2px 8px rgba(0,0,0,0.5);
            font-size:10px; color:white;
        "><i class="fa fa-${faIcon}"></i></div>`,
        iconSize: [22, 22],
        iconAnchor: [11, 11],
        popupAnchor: [0, -12],
    });
}

function popupContent(layerKey, props) {
    const cfg = LAYER_CFG[layerKey];
    const rows = Object.entries(props)
        .filter(([k]) => FIELD_LABELS[k] !== null && FIELD_LABELS[k] !== undefined && FIELD_LABELS[k] !== null)
        .filter(([, v]) => v !== null && v !== '' && v !== 0)
        .map(([k, v]) => `<div class="popup-row"><span class="popup-key">${FIELD_LABELS[k] || k}</span><span class="popup-val">${v}</span></div>`)
        .join('');
    return `<div class="popup-hd" style="background:color-mix(in srgb,${cfg.color} 20%,transparent)">
        <i class="fa fa-${cfg.icon || 'circle'}" style="color:${cfg.color}"></i>
        ${cfg.label}
    </div><div class="popup-body">${rows || '<div class="popup-row"><span class="popup-val" style="color:var(--text-muted)">Không có dữ liệu</span></div>'}</div>`;
}

function showInfoTab(layerKey, props) {
    const cfg = LAYER_CFG[layerKey];
    const rows = Object.entries(props)
        .filter(([k]) => FIELD_LABELS[k] !== null && FIELD_LABELS[k] !== undefined)
        .filter(([, v]) => v !== null && v !== '' && v !== 0)
        .map(([k, v]) => `<tr><td>${FIELD_LABELS[k] || k}</td><td>${v}</td></tr>`)
        .join('');

    document.getElementById('infoPlaceholder').style.display = 'none';
    document.getElementById('infoContent').style.display = 'block';
    document.getElementById('infoContent').innerHTML = `
        <div class="info-card">
            <div class="info-card-header" style="background:color-mix(in srgb,${cfg.color} 15%,transparent); color:${cfg.color}">
                <i class="fa fa-${cfg.icon || 'circle'}"></i> ${cfg.label}
            </div>
            <table class="info-table">${rows || '<tr><td colspan="2" style="color:var(--text-muted)">Không có dữ liệu</td></tr>'}</table>
        </div>`;

    // Auto switch to Info tab
    const infoTabEl = document.querySelector('[onclick="switchTab(\'tabInfo\', this)"]');
    if (infoTabEl) infoTabEl.click();
}

function setLoaderSub(txt) { document.getElementById('loaderSub').textContent = txt; }

function updateCount(key, count) {
    const el = document.getElementById('cnt_' + key);
    if (el) el.textContent = count.toLocaleString('vi-VN') + ' đối tượng';
    // stats tab
    const sel = document.getElementById('s_' + key);
    if (sel) sel.textContent = count.toLocaleString('vi-VN');
}

function showToast(msg) {
    const t = document.getElementById('tlToast');
    t.textContent = msg; t.classList.add('show');
    clearTimeout(t._tid);
    t._tid = setTimeout(() => t.classList.remove('show'), 2500);
}

// ═══════════════════════════════════════════════════════════
//  LAYER INIT
// ═══════════════════════════════════════════════════════════
function initLayer(key, data) {
    if (!data || !data.features || data.features.length === 0) {
        updateCount(key, 0);
        return;
    }
    const cfg = LAYER_CFG[key];
    const count = data.features.length;
    updateCount(key, count);

    let pane = 'polygonsPanePane';
    if (cfg.type === 'line') pane = 'linesPanePanePane';
    if (cfg.type === 'point') pane = 'pointsPanePanePane';
    // simpler:
    if (cfg.type === 'polygon') pane = 'polygonsPane';
    else if (cfg.type === 'line') pane = 'linesPane';
    else pane = 'pointsPane';

    const gStyle = {
        color: cfg.color,
        weight: cfg.weight || 2,
        opacity: cfg.opacity || 0.8,
        fillColor: cfg.fillColor || cfg.color,
        fillOpacity: cfg.fillOpacity !== undefined ? cfg.fillOpacity : 0,
    };

    layers[key] = L.geoJSON(data, {
        pane: pane,
        style: cfg.type !== 'point' ? gStyle : undefined,
        pointToLayer: cfg.type === 'point' ? (f, ll) => L.marker(ll, { icon: makeIcon(cfg.color, cfg.icon), pane: pane }) : undefined,
        onEachFeature: (feature, layer) => {
            const props = feature.properties || {};
            layer.bindPopup(popupContent(key, props), { className: 'tl-popup', maxWidth: 280 });
            layer.on('click', () => showInfoTab(key, props));
            if (cfg.type !== 'point') {
                layer.on('mouseover', function() {
                    this.setStyle({ weight: (cfg.weight||2) + 2, color: '#fff', opacity: 1 });
                    this.bringToFront();
                });
                layer.on('mouseout', function() {
                    layers[key].resetStyle(this);
                });
            }
            // Tooltip for thuadat
            if (key === 'thuadat' && props.so_thua) {
                layer.bindTooltip(
                    `<span style="font-size:10px;font-family:'JetBrains Mono',monospace;font-weight:600">${props.so_thua}</span>`,
                    { permanent: false, direction: 'center', className: 'tl-tooltip' }
                );
            }
        }
    }).addTo(map);

    opacityStore[key] = 1.0;
}

// ═══════════════════════════════════════════════════════════
//  STATISTICS CHART
// ═══════════════════════════════════════════════════════════
function buildChart() {
    const data = Object.entries(LAYER_CFG).map(([key, cfg]) => ({
        key, label: cfg.label, color: cfg.color,
        count: (GEO[key] && GEO[key].features) ? GEO[key].features.length : 0,
    })).filter(d => d.count > 0).sort((a, b) => b.count - a.count);

    const max = Math.max(...data.map(d => d.count), 1);
    const container = document.getElementById('chartBars');
    container.innerHTML = data.map(d => `
        <div class="chart-bar-row">
            <span class="chart-bar-label" title="${d.label}">${d.label}</span>
            <div class="chart-bar-track">
                <div class="chart-bar-fill" style="background:${d.color};width:0%" data-pct="${Math.round(d.count/max*100)}"></div>
            </div>
            <span class="chart-bar-count">${d.count}</span>
        </div>
    `).join('');
    // Animate
    setTimeout(() => {
        container.querySelectorAll('.chart-bar-fill').forEach(el => {
            el.style.width = el.dataset.pct + '%';
        });
    }, 100);
}

// ═══════════════════════════════════════════════════════════
//  SEARCH
// ═══════════════════════════════════════════════════════════
function doSearch(q) {
    const r = document.getElementById('searchResults');
    q = q.trim().toLowerCase();
    if (!q) { r.innerHTML = '<div class="no-result"><i class="fa fa-search"></i>Nhập từ khóa để tìm kiếm</div>'; return; }

    const scopes = {
        thuadat: document.getElementById('scope_thuadat')?.checked,
        cayxanh: document.getElementById('scope_cayxanh')?.checked,
        trambiap: document.getElementById('scope_trambiap')?.checked,
    };

    let results = [];
    Object.entries(scopes).forEach(([key, active]) => {
        if (!active || !GEO[key] || !GEO[key].features) return;
        const cfg = LAYER_CFG[key];
        GEO[key].features.forEach(f => {
            const p = f.properties || {};
            const text = Object.values(p).filter(Boolean).join(' ').toLowerCase();
            if (!text.includes(q)) return;
            const title = p.so_thua || p.loai_cay || p.ma_so || p.name || ('ID: ' + p.id);
            const sub = p.chu_ho || p.tinh_trang || p.loai_mba || cfg.label;
            results.push({ key, feature: f, title, sub, color: cfg.color, icon: cfg.icon || 'circle' });
            if (results.length >= 20) return;
        });
    });

    if (!results.length) {
        r.innerHTML = '<div class="no-result"><i class="fa fa-search"></i>Không tìm thấy kết quả</div>';
        return;
    }

    r.innerHTML = results.map((res, i) => `
        <div class="search-result-item" onclick="zoomToFeature(${i})">
            <div style="display:flex;align-items:center;gap:8px">
                <i class="fa fa-${res.icon}" style="color:${res.color};font-size:13px;width:16px;text-align:center"></i>
                <div>
                    <div class="sri-title">${res.title}</div>
                    <div class="sri-sub">${res.sub}</div>
                </div>
            </div>
        </div>
    `).join('');
    window._searchResults = results;
}

function zoomToFeature(idx) {
    const res = window._searchResults[idx];
    if (!res) return;
    const tmp = L.geoJSON(res.feature);
    const b = tmp.getBounds();
    if (b.isValid()) map.fitBounds(b, { maxZoom: 19, padding: [40, 40] });
    // Flash highlight
    const hl = L.geoJSON(res.feature, {
        style: { color: '#fff', weight: 3, fillColor: LAYER_CFG[res.key].color, fillOpacity: 0.4 }
    }).addTo(map);
    setTimeout(() => map.removeLayer(hl), 2000);
    if (window.innerWidth < 768) toggleSidebar();
}

// ═══════════════════════════════════════════════════════════
//  UI CONTROLS
// ═══════════════════════════════════════════════════════════
function switchTab(id, el) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tl-nav-item').forEach(n => n.classList.remove('active'));
    document.getElementById(id)?.classList.add('active');
    el.classList.add('active');
}

function toggleExpand(id, btn) {
    const el = document.getElementById(id);
    el.classList.toggle('open');
    btn.querySelector('i').style.transform = el.classList.contains('open') ? 'rotate(180deg)' : '';
}

function toggleLayer(key, on) {
    if (!layers[key]) return;
    on ? map.addLayer(layers[key]) : map.removeLayer(layers[key]);
}

function setOpacity(key, val, input) {
    const opacity = val / 100;
    opacityStore[key] = opacity;
    const valEl = input.closest('.layer-extra').querySelector('.opacity-val');
    if (valEl) valEl.textContent = val + '%';
    if (!layers[key]) return;
    layers[key].eachLayer(l => {
        if (l.setOpacity) l.setOpacity(opacity); // markers
        else if (l.setStyle) l.setStyle({ opacity, fillOpacity: opacity * (LAYER_CFG[key].fillOpacity || 0) });
    });
}

function switchBasemap(type) {
    document.getElementById('bm_osm_label').classList.toggle('active', type === 'osm');
    document.getElementById('bm_sat_label').classList.toggle('active', type === 'sat');
    if (type === 'osm') { map.addLayer(OSM_LAYER); map.removeLayer(SAT_LAYER); }
    else { map.addLayer(SAT_LAYER); map.removeLayer(OSM_LAYER); }
}

function toggleSidebar() {
    document.getElementById('tlSidebar').classList.toggle('open');
    setTimeout(() => map.invalidateSize(), 320);
}

function toggleTheme() {
    const root = document.getElementById('tlRoot');
    const isDark = root.getAttribute('data-theme') === 'dark';
    root.setAttribute('data-theme', isDark ? 'light' : 'dark');
    document.getElementById('themeIcon').className = isDark ? 'fa fa-moon' : 'fa fa-circle-half-stroke';
    showToast(isDark ? 'Giao diện sáng' : 'Giao diện tối');
}

let measuring = false;
let measureControl;
function toggleMeasure() {
    if (!measureControl) {
        measureControl = new L.Control.Measure({ position: 'bottomleft', primaryLengthUnit: 'meters', secondaryLengthUnit: 'kilometers', primaryAreaUnit: 'sqmeters' });
        measureControl.addTo(map);
    }
    measuring = !measuring;
    document.getElementById('fabMeasure').classList.toggle('active', measuring);
    const btn = document.querySelector('.leaflet-control-measure-toggle');
    if (btn) btn.click();
    showToast(measuring ? 'Đo khoảng cách: Click các điểm trên bản đồ' : 'Đã tắt đo đạc');
}

function locateMe() {
    map.locate({ setView: true, maxZoom: 17 });
    map.once('locationfound', e => {
        L.circleMarker(e.latlng, { radius: 8, color: '#1f6feb', fillColor: '#1f6feb', fillOpacity: 0.5, weight: 3 })
            .addTo(map).bindPopup('📍 Vị trí của bạn').openPopup();
        showToast('Đã xác định vị trí');
    });
    map.once('locationerror', () => showToast('Không thể xác định vị trí'));
}

function resetView() {
    const hasData = Object.values(layers).find(l => l && l.getBounds && l.getBounds().isValid && l.getBounds().isValid());
    if (hasData) map.fitBounds(hasData.getBounds(), { padding: [20, 20] });
    else map.setView(CENTER, ZOOM);
}

function exportMapPng() {
    showToast('Tính năng xuất ảnh cần thêm plugin leaflet-image');
}

// ═══════════════════════════════════════════════════════════
//  COORDINATE DISPLAY
// ═══════════════════════════════════════════════════════════
map.on('mousemove', e => {
    document.getElementById('coordDisplay').textContent =
        `${e.latlng.lat.toFixed(6)}, ${e.latlng.lng.toFixed(6)}`;
});
map.on('zoomend', () => {
    document.getElementById('zoomDisplay').textContent = 'Zoom: ' + map.getZoom();
});

// ═══════════════════════════════════════════════════════════
//  BOOTSTRAP
// ═══════════════════════════════════════════════════════════
const LOAD_ORDER = ['ranh','thuadat','giaothong','capnuoc','hathe','trambiap','cayxanh','chieusang'];
const loader = document.getElementById('tlLoader');

(async function bootstrap() {
    for (let i = 0; i < LOAD_ORDER.length; i++) {
        const key = LOAD_ORDER[i];
        setLoaderSub(`Đang tải: ${LAYER_CFG[key].label}...`);
        await new Promise(res => setTimeout(res, 80)); // slight delay for UX
        initLayer(key, GEO[key]);
    }

    buildChart();
    document.getElementById('zoomDisplay').textContent = 'Zoom: ' + map.getZoom();

    // Fit to data
    const validLayers = Object.values(layers).filter(l => l);
    if (validLayers.length > 0) {
        const group = L.featureGroup(validLayers);
        if (group.getBounds().isValid()) {
            map.fitBounds(group.getBounds(), { padding: [20, 20] });
        }
    }

    loader.classList.add('hidden');
    showToast('Đã tải xong ' + LOAD_ORDER.length + ' lớp dữ liệu');
})();
</script>