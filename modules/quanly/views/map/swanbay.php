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
    'sonha' => 'Số nhà',
    'diachi' => 'Địa chỉ',
    'chusohuu' => 'Chủ sở hữu',
    'quyhoach' => 'Quy hoạch',
    'image' => 'Hình ảnh',
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
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-measure/dist/leaflet-measure.css"/>

<style>
:root{--bg-900:#0d1117;--bg-800:#161b22;--bg-700:#1c2128;--bg-600:#21262d;--bg-500:#30363d;--border:#30363d;--border-light:#3d444d;--text-primary:#e6edf3;--text-secondary:#8d96a0;--text-muted:#545d68;--accent:#2ea043;--accent-blue:#1f6feb;--accent-orange:#d29922;--radius:8px;--radius-sm:5px;--sidebar-w:340px;--header-h:56px;--transition:all .2s cubic-bezier(.4,0,.2,1)}
[data-theme="light"]{--bg-900:#f3f4f6;--bg-800:#fff;--bg-700:#f9fafb;--bg-600:#f3f4f6;--bg-500:#e5e7eb;--border:#e5e7eb;--border-light:#d1d5db;--text-primary:#111827;--text-secondary:#374151;--text-muted:#9ca3af}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{overflow:hidden;font-family:'Be Vietnam Pro',sans-serif}
.sb-root{position:fixed;inset:0;display:flex;background:var(--bg-900);color:var(--text-primary);z-index:99999}
.sb-sidebar{width:var(--sidebar-w);min-width:var(--sidebar-w);height:100%;display:flex;flex-direction:column;background:var(--bg-800);border-right:1px solid var(--border);z-index:1001;transition:transform .3s cubic-bezier(.4,0,.2,1);overflow:hidden}
.sb-header{height:var(--header-h);padding:0 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:12px;flex-shrink:0}
.sb-footer{border-top:1px solid var(--border);padding:10px 16px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.sb-logo{width:32px;height:32px;border-radius:var(--radius-sm);background:linear-gradient(135deg,var(--accent-blue),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;flex-shrink:0}
.sb-title{flex:1}
.sb-title h1{font-size:13px;font-weight:700;line-height:1.2}
.sb-title span{font-size:11px;color:var(--text-muted)}
.sb-actions{display:flex;gap:4px}
.icon-btn{width:28px;height:28px;border:1px solid var(--border);background:var(--bg-600);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-secondary);font-size:11px;transition:var(--transition)}
.icon-btn:hover{background:var(--bg-500);color:var(--text-primary);border-color:var(--border-light)}
.sb-nav{display:flex;border-bottom:1px solid var(--border);background:var(--bg-800);flex-shrink:0}
.sb-nav-item{flex:1;padding:10px 0;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);cursor:pointer;border-bottom:2px solid transparent;transition:var(--transition);text-transform:uppercase;letter-spacing:.4px}
.sb-nav-item:hover{color:var(--text-secondary);background:var(--bg-700)}
.sb-nav-item.active{color:var(--accent-blue);border-bottom-color:var(--accent-blue)}
.sb-nav-item i{display:block;font-size:14px;margin-bottom:3px}
.sb-content{flex:1;overflow-y:auto;overflow-x:hidden}
.sb-content::-webkit-scrollbar{width:4px}
.sb-content::-webkit-scrollbar-track{background:transparent}
.sb-content::-webkit-scrollbar-thumb{background:var(--border);border-radius:4px}
.tab-pane{display:none;padding:16px;animation:fadeUp .2s ease}
.tab-pane.active{display:block}
@keyframes fadeUp{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}
.section-label{font-size:10px;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:var(--text-muted);margin:18px 0 8px;padding-left:2px;display:flex;align-items:center;gap:6px}
.section-label::after{content:'';flex:1;height:1px;background:var(--border)}
.base-grid,.stat-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.bm-card,.layer-item,.stat-card,.search-item,.info-card{background:var(--bg-700);border:1px solid var(--border);border-radius:var(--radius)}
.bm-card{padding:14px;cursor:pointer;text-align:center;transition:var(--transition)}
.bm-card:hover{border-color:var(--border-light)}
.bm-card.active{border-color:var(--accent-blue)}
.bm-card i{font-size:18px;margin-bottom:6px;display:block}
.layer-item{margin-bottom:6px;overflow:hidden;transition:var(--transition)}
.layer-item:hover{border-color:var(--border-light)}
.layer-row{display:flex;align-items:center;gap:8px;padding:9px 10px}
.toggle{position:relative;width:32px;height:17px;flex-shrink:0}
.toggle input{opacity:0;width:0;height:0}
.slider{position:absolute;inset:0;border-radius:17px;background:var(--bg-500);transition:var(--transition)}
.slider:before{content:'';position:absolute;width:13px;height:13px;left:2px;top:2px;border-radius:50%;background:var(--text-muted);transition:var(--transition)}
.toggle input:checked + .slider{background:var(--layer-color)}
.toggle input:checked + .slider:before{background:#fff;transform:translateX(15px)}
.layer-dot{width:10px;height:10px;background:var(--layer-color);border-radius:50%;flex-shrink:0;box-shadow:0 0 0 3px color-mix(in srgb,var(--layer-color) 20%,transparent)}
.layer-dot.line{height:4px;width:16px;border-radius:2px}
.layer-dot.polygon{width:14px;height:10px;border-radius:3px;border:2px solid var(--layer-color);background:color-mix(in srgb,var(--layer-color) 20%,transparent)}
.layer-meta{flex:1;min-width:0}
.layer-name{font-size:13px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.layer-count,.mono{font-size:10px;color:var(--text-muted);font-family:'JetBrains Mono',monospace}
.layer-extra{display:none;padding:0 10px 10px 44px;border-top:1px solid var(--border);background:var(--bg-800)}
.layer-extra.open{display:block}
.opacity-row{display:flex;align-items:center;gap:8px;padding-top:8px}
.opacity-row label{width:60px;font-size:11px;color:var(--text-muted);flex-shrink:0}
.opacity-row input{flex:1}
.stat-card{padding:12px 10px;display:flex;gap:8px;align-items:center}
.stat-icon{width:32px;height:32px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0}
.stat-val{font-size:18px;font-weight:700;color:var(--text-primary)}
.stat-lbl{font-size:10px;color:var(--text-muted);margin-top:2px}
.chart-row{display:flex;align-items:center;gap:8px;margin-bottom:7px}
.chart-row span:first-child{width:90px;font-size:11px;color:var(--text-secondary);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.chart-track{flex:1;height:6px;background:var(--bg-500);border-radius:3px;overflow:hidden}
.chart-fill{height:100%;border-radius:3px;transition:width 1s cubic-bezier(.4,0,.2,1)}
.search-box{display:flex;align-items:center;gap:8px;padding:0 14px;border:1px solid var(--border);background:var(--bg-700);border-radius:20px;margin-bottom:12px;transition:var(--transition)}
.search-box:focus-within{border-color:var(--accent-blue);background:var(--bg-800)}
.search-box input{flex:1;background:transparent;border:none;outline:none;color:var(--text-primary);padding:10px 0;font-family:'Be Vietnam Pro',sans-serif}
.search-box input::placeholder{color:var(--text-muted)}
.search-item{padding:9px 10px;margin-bottom:4px;cursor:pointer;transition:var(--transition);border:1px solid transparent}
.search-item:hover{background:var(--bg-600);border-color:var(--border-light)}
.muted{color:var(--text-muted)}
.empty{text-align:center;padding:32px 0;color:var(--text-muted);font-size:13px}
.info-card table{width:100%;border-collapse:collapse}
.info-card td{padding:8px 12px;font-size:12px;border-top:1px solid var(--border)}
.info-card td:first-child{width:42%;color:var(--text-muted)}
.sb-map-wrap{position:relative;flex:1}
#sb-map{width:100%;height:100%}
.fab-group{position:absolute;right:16px;top:16px;display:flex;flex-direction:column;gap:6px;z-index:1000}
.fab,.mobile-toggle{width:36px;height:36px;display:flex;align-items:center;justify-content:center}
.fab{background:var(--bg-800);border:1px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;color:var(--text-secondary);font-size:13px;transition:var(--transition);box-shadow:0 2px 8px rgba(0,0,0,.3)}
.fab:hover{background:var(--bg-600);color:var(--text-primary);border-color:var(--border-light);transform:translateY(-1px)}
.fab.active{background:var(--accent-blue);border-color:var(--accent-blue);color:#fff}
.fab-sep{height:1px;background:var(--border);margin:2px 0}
.mobile-toggle{display:none;position:absolute;left:12px;top:12px;z-index:2000;background:var(--bg-800);border:1px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;color:var(--text-primary)}
.sb-loader{position:absolute;inset:0;background:rgba(13,17,23,.9);display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:9999;backdrop-filter:blur(4px)}
.sb-loader.hidden{display:none}
.spinner{width:36px;height:36px;border:3px solid var(--border);border-top-color:var(--accent-blue);border-radius:50%;animation:spin .8s linear infinite;margin-bottom:12px}
@keyframes spin{to{transform:rotate(360deg)}}
.sub{font-size:11px;color:var(--text-muted);margin-top:4px}
.toast{position:absolute;left:50%;bottom:24px;transform:translateX(-50%);padding:8px 16px;border-radius:20px;background:var(--bg-600);border:1px solid var(--border-light);font-size:12px;color:var(--text-primary);opacity:0;pointer-events:none;transition:opacity .3s;z-index:3000;box-shadow:0 4px 12px rgba(0,0,0,.4)}
.toast.show{opacity:1}
.leaflet-popup-content-wrapper{background:var(--bg-800)!important;border:1px solid var(--border-light)!important;border-radius:var(--radius)!important;box-shadow:0 8px 24px rgba(0,0,0,.5)!important;padding:0!important}
.leaflet-popup-tip-container{display:none}
.leaflet-popup-content{margin:0!important;width:250px!important}
.leaflet-popup-close-button{color:var(--text-muted)!important;font-size:18px!important;top:6px!important;right:8px!important;z-index:10}
.leaflet-popup-close-button:hover{color:var(--text-primary)!important}
.leaflet-interactive:focus{outline:none!important}
.leaflet-control-zoom{border:none!important;box-shadow:0 8px 24px rgba(0,0,0,.18)!important}
.leaflet-control-zoom a{background:var(--bg-800)!important;color:var(--text-primary)!important;border:1px solid var(--border)!important;width:34px;height:34px;line-height:32px;font-size:18px}
.leaflet-control-zoom a:hover{background:var(--bg-600)!important}
.leaflet-control-zoom a:first-child{border-bottom:none!important;border-radius:10px 10px 0 0}
.leaflet-control-zoom a:last-child{border-radius:0 0 10px 10px}
.leaflet-control-attribution{background:rgba(13,17,23,.7)!important;color:var(--text-muted)!important;font-size:9px!important}
.leaflet-control-attribution a{color:var(--text-muted)!important}
.popup-hd{padding:10px 12px;font-size:12px;font-weight:700;border-bottom:1px solid var(--border)}
.popup-row{display:flex;gap:8px;padding:8px 12px;font-size:12px;border-bottom:1px solid var(--border);color:var(--text-primary)}
.popup-row:last-child{border-bottom:none}
.popup-key{width:40%;color:var(--text-muted);font-weight:600}
.popup-row span:last-child{color:var(--text-primary);font-weight:500}
.popup-img-wrap{padding:8px 12px;border-bottom:1px solid var(--border)}
.popup-img{width:100%;max-height:140px;object-fit:cover;border-radius:var(--radius-sm);cursor:pointer;transition:var(--transition)}
.popup-img:hover{opacity:.85}
.popup-edit-btn{display:block;width:calc(100% - 24px);margin:8px 12px;padding:7px 0;text-align:center;background:var(--accent-blue);color:#fff;border:none;border-radius:var(--radius-sm);font-size:12px;font-weight:600;font-family:'Be Vietnam Pro',sans-serif;cursor:pointer;transition:var(--transition)}
.popup-edit-btn:hover{background:#1a5fcb}
/* ── Edit Modal ── */
.sb-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);z-index:10000;display:none;align-items:center;justify-content:center;animation:fadeIn .2s ease}
.sb-modal-overlay.active{display:flex}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
.sb-modal{background:var(--bg-800);border:1px solid var(--border-light);border-radius:12px;width:480px;max-width:94vw;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.5);animation:modalSlide .25s ease}
@keyframes modalSlide{from{opacity:0;transform:translateY(16px) scale(.97)}to{opacity:1;transform:translateY(0) scale(1)}}
.sb-modal::-webkit-scrollbar{width:4px}
.sb-modal::-webkit-scrollbar-thumb{background:var(--border);border-radius:4px}
.sb-modal-hd{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--border);position:sticky;top:0;background:var(--bg-800);z-index:1}
.sb-modal-hd h2{font-size:15px;font-weight:700;display:flex;align-items:center;gap:8px}
.sb-modal-close{width:30px;height:30px;border:1px solid var(--border);background:var(--bg-600);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-secondary);font-size:13px;transition:var(--transition)}
.sb-modal-close:hover{background:var(--bg-500);color:var(--text-primary)}
.sb-modal-body{padding:20px}
.sb-form-group{margin-bottom:14px}
.sb-form-group label{display:block;font-size:11px;font-weight:600;color:var(--text-muted);margin-bottom:5px;text-transform:uppercase;letter-spacing:.3px}
.sb-form-input{width:100%;padding:9px 12px;background:var(--bg-700);border:1px solid var(--border);border-radius:var(--radius-sm);color:var(--text-primary);font-size:13px;font-family:'Be Vietnam Pro',sans-serif;transition:var(--transition);outline:none}
.sb-form-input:focus{border-color:var(--accent-blue);background:var(--bg-800)}
.sb-form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.sb-form-row.three{grid-template-columns:1fr 1fr 1fr}
.sb-upload-area{border:2px dashed var(--border);border-radius:var(--radius);padding:20px;text-align:center;cursor:pointer;transition:var(--transition);position:relative}
.sb-upload-area:hover,.sb-upload-area.dragover{border-color:var(--accent-blue);background:color-mix(in srgb,var(--accent-blue) 6%,transparent)}
.sb-upload-area i{font-size:24px;color:var(--text-muted);margin-bottom:6px;display:block}
.sb-upload-area p{font-size:12px;color:var(--text-muted);margin:0}
.sb-upload-area input{position:absolute;inset:0;opacity:0;cursor:pointer}
.sb-upload-preview{margin-top:10px;display:none;grid-template-columns:repeat(auto-fill, minmax(80px, 1fr));gap:10px}
.sb-upload-preview.active{display:grid}
.img-thumb-wrap{position:relative;aspect-ratio:1;background:var(--bg-700);border-radius:var(--radius-sm);overflow:hidden;border:1px solid var(--border)}
.img-thumb-wrap img{width:100%;height:100%;object-fit:cover;cursor:pointer;transition:var(--transition)}
.img-thumb-wrap img:hover{transform:scale(1.05)}
.img-thumb-wrap .remove-img{position:absolute;top:2px;right:2px;width:20px;height:20px;background:rgba(220,38,38,0.85);border:none;border-radius:50%;color:#fff;font-size:10px;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:2;transition:var(--transition)}
.img-thumb-wrap .remove-img:hover{background:#dc2626;transform:scale(1.1)}
.img-count-badge{position:absolute;bottom:4px;right:4px;background:rgba(0,0,0,.6);color:#fff;font-size:10px;padding:2px 6px;border-radius:10px;backdrop-filter:blur(2px);pointer-events:none}
.sb-modal-ft{display:flex;justify-content:flex-end;gap:8px;padding:12px 20px;border-top:1px solid var(--border);position:sticky;bottom:0;background:var(--bg-800)}
.sb-btn{padding:8px 20px;border-radius:var(--radius-sm);font-size:13px;font-weight:600;font-family:'Be Vietnam Pro',sans-serif;cursor:pointer;border:1px solid var(--border);transition:var(--transition)}
.sb-btn-secondary{background:var(--bg-600);color:var(--text-primary)}
.sb-btn-secondary:hover{background:var(--bg-500)}
.sb-btn-primary{background:var(--accent-blue);color:#fff;border-color:var(--accent-blue)}
.sb-btn-primary:hover{background:#1a5fcb}
.sb-btn-danger{background:#dc2626;color:#fff;border-color:#dc2626}
.sb-btn-danger:hover{background:#b91c1c}
.sb-btn:disabled{opacity:.5;cursor:not-allowed}
/* ── Lightbox ── */
.sb-lightbox{position:fixed;inset:0;background:rgba(0,0,0,.88);backdrop-filter:blur(8px);z-index:10001;display:none;align-items:center;justify-content:center;cursor:zoom-out;animation:fadeIn .2s ease}
.sb-lightbox.active{display:flex}
.sb-lightbox img{max-width:92vw;max-height:92vh;object-fit:contain;border-radius:8px;box-shadow:0 12px 48px rgba(0,0,0,.6)}
.sb-lightbox-close{position:absolute;top:16px;right:16px;width:36px;height:36px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:50%;color:#fff;font-size:16px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:var(--transition);z-index:100}
.sb-lightbox-close:hover{background:rgba(255,255,255,.25)}
.sb-lightbox-nav{position:absolute;top:50%;transform:translateY(-50%);width:50px;height:50px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:50%;color:#fff;font-size:20px;cursor:pointer;display:none;align-items:center;justify-content:center;transition:var(--transition);z-index:90}
.sb-lightbox-nav.active{display:flex}
.sb-lightbox-nav:hover{background:rgba(255,255,255,.2);border-color:rgba(255,255,255,.3)}
.sb-lightbox-nav.prev{left:20px}
.sb-lightbox-nav.next{right:20px}
.sb-lightbox-counter{position:absolute;bottom:24px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,.6);color:#fff;font-size:13px;padding:6px 14px;border-radius:20px;backdrop-filter:blur(4px);display:none;z-index:90}
.sb-lightbox-counter.active{display:block}
@media (max-width:768px){.sb-sidebar{position:absolute;left:0;top:0;bottom:0;transform:translateX(-100%);width:88%;min-width:88%;z-index:1002}.sb-sidebar.open{transform:translateX(0);box-shadow:4px 0 20px rgba(0,0,0,.5)}.mobile-toggle{display:flex}.fab-group{top:60px;right:10px}.sb-modal{width:96vw}}
</style>

<div class="sb-root" id="sbRoot" data-theme="dark">
  <button class="mobile-toggle" id="mobileToggle" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
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
      <div class="sb-nav-item" onclick="switchTab('tabSearch',this)"><i class="fa fa-search"></i>Tìm kiếm</div>
      <div class="sb-nav-item" onclick="switchTab('tabInfo',this)"><i class="fa fa-circle-info"></i>Thông tin</div>
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
      <button class="fab" title="Vị trí của tôi" onclick="locateMe()"><i class="fa fa-crosshairs"></i></button>
      <button class="fab" title="Về vị trí ban đầu" onclick="resetView()"><i class="fa fa-house"></i></button>
      <div class="fab-sep"></div>
      <button class="fab" id="fabMeasure" title="Đo khoảng cách" onclick="toggleMeasure()"><i class="fa fa-ruler"></i></button>
      <div class="fab-sep"></div>
      <button class="fab" title="Phóng to" onclick="map.zoomIn()"><i class="fa fa-plus"></i></button>
      <button class="fab" title="Thu nhỏ" onclick="map.zoomOut()"><i class="fa fa-minus"></i></button>
    </div>
    <div class="sb-loader" id="sbLoader"><div class="spinner"></div><div>Đang khởi tạo bản đồ SwanBay</div><div class="sub" id="loaderSub">Đang chuẩn bị dữ liệu `sb_`...</div></div>
    <div class="toast" id="sbToast"></div>
  </div>

  <!-- Edit Modal -->
  <div class="sb-modal-overlay" id="editModalOverlay">
    <div class="sb-modal">
      <div class="sb-modal-hd">
        <h2><i class="fa fa-pen-to-square" style="color:var(--accent-blue)"></i> Cập nhật thửa đất</h2>
        <button class="sb-modal-close" onclick="closeEditModal()"><i class="fa fa-xmark"></i></button>
      </div>
      <div class="sb-modal-body">
        <input type="hidden" id="edit_id">
        <div class="sb-form-row three">
          <div class="sb-form-group"><label>Số tờ</label><input class="sb-form-input" id="edit_soto"></div>
          <div class="sb-form-group"><label>Số thửa</label><input class="sb-form-input" id="edit_sothua"></div>
          <div class="sb-form-group"><label>Diện tích</label><input class="sb-form-input" id="edit_shape_area"></div>
        </div>
        <div class="sb-form-group"><label>Số nhà</label><input class="sb-form-input" id="edit_sonha"></div>
        <div class="sb-form-group"><label>Địa chỉ</label><input class="sb-form-input" id="edit_diachi"></div>
        <div class="sb-form-group"><label>Chủ sở hữu</label><input class="sb-form-input" id="edit_chusohuu"></div>
        <div class="sb-form-group"><label>Quy hoạch</label><input class="sb-form-input" id="edit_quyhoach"></div>
        <div class="sb-form-group">
          <label>Hình ảnh</label>
          <div id="editCurrentImage" class="sb-upload-preview" style="margin-top:0;margin-bottom:12px"></div>
          <div class="sb-upload-area" id="uploadArea">
            <i class="fa fa-cloud-arrow-up"></i>
            <p>Kéo thả hoặc click để chọn thêm ảnh</p>
            <p style="font-size:10px;margin-top:4px;color:var(--text-muted)">jpg, jpeg, png, gif, bmp, webp, svg, ico</p>
            <input type="file" id="editImageFile" accept="image/*" multiple onchange="previewUpload(this)">
          </div>
          <div class="sb-upload-preview" id="uploadPreview"></div>
        </div>
      </div>
      <div class="sb-modal-ft">
        <button class="sb-btn sb-btn-secondary" onclick="closeEditModal()">Đóng</button>
        <button class="sb-btn sb-btn-primary" id="editSaveBtn" onclick="saveEdit()"><i class="fa fa-floppy-disk"></i> Lưu</button>
      </div>
    </div>
  </div>

  <!-- Lightbox -->
  <div class="sb-lightbox" id="sbLightbox" onclick="closeLightbox()">
    <button class="sb-lightbox-close" onclick="closeLightbox()"><i class="fa fa-xmark"></i></button>
    <button class="sb-lightbox-nav prev" id="ltBtnPrev" onclick="prevImage(event)"><i class="fa fa-chevron-left"></i></button>
    <button class="sb-lightbox-nav next" id="ltBtnNext" onclick="nextImage(event)"><i class="fa fa-chevron-right"></i></button>
    <div class="sb-lightbox-counter" id="ltCounter"></div>
    <img id="lightboxImg" src="" onclick="event.stopPropagation()">
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

const map = L.map('sb-map', { zoomControl: false, attributionControl: true }).setView(CENTER, ZOOM);
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
        .filter(([field, value]) => FIELD_LABELS[field] !== undefined && field !== 'image' && value !== null && value !== '')
        .map(([field, value]) => `<div class="popup-row"><span class="popup-key">${FIELD_LABELS[field] || field}</span><span>${value}</span></div>`)
        .join('');
    let imgHtml = '';
    if (key === 'thuadat' && props.image) {
        let imgs = [];
        try {
            const decoded = JSON.parse(props.image);
            imgs = Array.isArray(decoded) ? decoded : [props.image];
        } catch(e) { imgs = [props.image]; }
        
        if (imgs.length > 0) {
            const firstImg = fixImageUrl(imgs[0]);
            const badge = imgs.length > 1 ? `<div class="img-count-badge">+${imgs.length - 1} ảnh</div>` : '';
            const albumJson = JSON.stringify(imgs).replace(/"/g, '&quot;');
            imgHtml = `<div class="popup-img-wrap" style="position:relative"><img class="popup-img" src="${firstImg}" alt="Ảnh thửa đất" onclick="openLightbox('${firstImg}', '${albumJson}')">${badge}</div>`;
        }
    }
    let editBtn = '';
    if (key === 'thuadat' && props.id) {
        editBtn = `<button class="popup-edit-btn" onclick="openEditModal(${props.id})"><i class="fa fa-pen-to-square"></i> Cập nhật</button>`;
    }
    return `<div class="popup-hd" style="color:${cfg.color};background:color-mix(in srgb, ${cfg.color} 16%, transparent)"><i class="fa fa-${cfg.icon}"></i> ${cfg.label}</div>${imgHtml}${rows || '<div class="popup-row">Không có dữ liệu</div>'}${editBtn}`;
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
                title: props.sothua || props.sonha || props.loaimat || props.loaitru || props.covan || ('ID: ' + (props.id ?? '')),
                sub: props.diachi || props.chusohuu || props.quyhoach || props.vatlieu || cfg.label,
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
    setTimeout(() => map.invalidateSize(), 320);
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
    showToast(measuring ? 'Đo khoảng cách: Click các điểm trên bản đồ' : 'Đã tắt đo đạc');
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
    showToast('Đã tải xong ' + LOAD_ORDER.length + ' lớp dữ liệu');
})();

// ── Edit Modal Functions ──
const APP_BASE_URL = '<?= \Yii::$app->request->baseUrl ?>';
const URL_UPDATE = '<?= \yii\helpers\Url::to(['/quanly/map/swanbay-update']) ?>';
const URL_UPLOAD = '<?= \yii\helpers\Url::to(['/quanly/map/swanbay-upload']) ?>';
const URL_DELETE_IMG = '<?= \yii\helpers\Url::to(['/quanly/map/swanbay-delete-image']) ?>';

function getCsrfParam() {
    const meta = document.querySelector('meta[name="csrf-param"]');
    return meta ? meta.getAttribute('content') : '_csrf';
}
function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

function fixImageUrl(url) {
    if (!url) return '';
    if (url.startsWith('http') || url.startsWith('data:')) return url;
    if (APP_BASE_URL && url.startsWith('/resources') && !url.startsWith(APP_BASE_URL)) {
        return APP_BASE_URL + url;
    }
    return url;
}

function findThuadatProps(id) {
    if (!GEO.thuadat || !GEO.thuadat.features) return null;
    const feature = GEO.thuadat.features.find(f => f.properties && f.properties.id == id);
    return feature ? feature.properties : null;
}

function openEditModal(id) {
    const props = findThuadatProps(id);
    if (!props) { showToast('Không tìm thấy dữ liệu'); return; }
    map.closePopup();
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_soto').value = props.soto || '';
    document.getElementById('edit_sothua').value = props.sothua || '';
    document.getElementById('edit_shape_area').value = props.shape_area || '';
    document.getElementById('edit_sonha').value = props.sonha || '';
    document.getElementById('edit_diachi').value = props.diachi || '';
    document.getElementById('edit_chusohuu').value = props.chusohuu || '';
    document.getElementById('edit_quyhoach').value = props.quyhoach || '';
    // Current images
    const currentImgEl = document.getElementById('editCurrentImage');
    currentImgEl.innerHTML = '';
    if (props.image) {
        let imgs = [];
        try {
            const decoded = JSON.parse(props.image);
            imgs = Array.isArray(decoded) ? decoded : [props.image];
        } catch(e) { imgs = [props.image]; }

        if (imgs.length > 0) {
            currentImgEl.classList.add('active');
            const albumJson = JSON.stringify(imgs).replace(/"/g, '&quot;');
            imgs.forEach(path => {
                const src = fixImageUrl(path);
                const thumb = document.createElement('div');
                thumb.className = 'img-thumb-wrap';
                thumb.innerHTML = `
                    <img src="${src}" onclick="openLightbox('${src}', '${albumJson}')">
                    <button class="remove-img" onclick="deleteImage(${id}, '${path}')" title="Xóa ảnh này"><i class="fa fa-xmark"></i></button>
                `;
                currentImgEl.appendChild(thumb);
            });
        } else {
            currentImgEl.classList.remove('active');
        }
    } else {
        currentImgEl.classList.remove('active');
    }
    clearUploadPreview();
    document.getElementById('editModalOverlay').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModalOverlay').classList.remove('active');
    clearUploadPreview();
}

function previewUpload(input) {
    const files = input.files;
    const preview = document.getElementById('uploadPreview');
    preview.innerHTML = '';
    if (files.length === 0) {
        preview.classList.remove('active');
        return;
    }
    preview.classList.add('active');
    Array.from(files).forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const thumb = document.createElement('div');
            thumb.className = 'img-thumb-wrap';
            thumb.innerHTML = `<img src="${e.target.result}" title="${file.name}">`;
            preview.appendChild(thumb);
        };
        reader.readAsDataURL(file);
    });
}

function clearUploadPreview() {
    const preview = document.getElementById('uploadPreview');
    preview.innerHTML = '';
    preview.classList.remove('active');
    document.getElementById('editImageFile').value = '';
}

async function saveEdit() {
    const btn = document.getElementById('editSaveBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang lưu...';

    const id = document.getElementById('edit_id').value;
    try {
        // 1. Save text fields
        const formData = new FormData();
        formData.append(getCsrfParam(), getCsrfToken());
        formData.append('id', id);
        formData.append('soto', document.getElementById('edit_soto').value);
        formData.append('sothua', document.getElementById('edit_sothua').value);
        formData.append('shape_area', document.getElementById('edit_shape_area').value);
        formData.append('sonha', document.getElementById('edit_sonha').value);
        formData.append('diachi', document.getElementById('edit_diachi').value);
        formData.append('chusohuu', document.getElementById('edit_chusohuu').value);
        formData.append('quyhoach', document.getElementById('edit_quyhoach').value);

        const resp = await fetch(URL_UPDATE, { method: 'POST', body: formData });
        const result = await resp.json();

        if (!result.success) {
            showToast('Lỗi: ' + result.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-floppy-disk"></i> Lưu';
            return;
        }

        // 2. Upload images if selected
        const fileInput = document.getElementById('editImageFile');
        let finalImages = null;
        if (fileInput.files.length > 0) {
            const imgForm = new FormData();
            imgForm.append(getCsrfParam(), getCsrfToken());
            imgForm.append('id', id);
            Array.from(fileInput.files).forEach(file => {
                imgForm.append('image[]', file);
            });

            const imgResp = await fetch(URL_UPLOAD, { method: 'POST', body: imgForm });
            const imgResult = await imgResp.json();
            if (imgResult.success) {
                finalImages = JSON.stringify(imgResult.images);
            } else {
                showToast('Lỗi upload: ' + imgResult.message);
            }
        }

        // 3. Update local GeoJSON data
        if (GEO.thuadat && GEO.thuadat.features) {
            const feature = GEO.thuadat.features.find(f => f.properties && f.properties.id == id);
            if (feature) {
                feature.properties.soto = document.getElementById('edit_soto').value;
                feature.properties.sothua = document.getElementById('edit_sothua').value;
                feature.properties.shape_area = document.getElementById('edit_shape_area').value;
                feature.properties.sonha = document.getElementById('edit_sonha').value;
                feature.properties.diachi = document.getElementById('edit_diachi').value;
                feature.properties.chusohuu = document.getElementById('edit_chusohuu').value;
                feature.properties.quyhoach = document.getElementById('edit_quyhoach').value;
                if (finalImages) feature.properties.image = finalImages;
            }
        }

        // 4. Refresh layer popups
        if (layers.thuadat) {
            layers.thuadat.eachLayer(layer => {
                const p = layer.feature?.properties;
                if (p && p.id == id) {
                    layer.setPopupContent(popupContent('thuadat', p));
                    if (p.sothua) layer.setTooltipContent(String(p.sothua));
                }
            });
        }

        showToast('Cập nhật thành công!');
        closeEditModal();
    } catch (err) {
        showToast('Lỗi kết nối: ' + err.message);
    }
    btn.disabled = false;
    btn.innerHTML = '<i class="fa fa-floppy-disk"></i> Lưu';
}

async function deleteImage(id, imagePath = null) {
    const msg = imagePath ? 'Bạn chắc chắn muốn xóa hình ảnh này?' : 'Bạn chắc chắn muốn xóa TẤT CẢ hình ảnh?';
    if (!confirm(msg)) return;
    const formData = new FormData();
    formData.append(getCsrfParam(), getCsrfToken());
    formData.append('id', id);
    if (imagePath) formData.append('image_path', imagePath);

    try {
        const resp = await fetch(URL_DELETE_IMG, { method: 'POST', body: formData });
        const result = await resp.json();
        if (result.success) {
            // Update local data
            const feature = GEO.thuadat?.features?.find(f => f.properties?.id == id);
            if (feature) {
                if (result.images && result.images.length > 0) {
                    feature.properties.image = JSON.stringify(result.images);
                } else {
                    feature.properties.image = null;
                }
            }
            // Refresh modal if open
            if (document.getElementById('editModalOverlay').classList.contains('active')) {
                openEditModal(id);
            }
            // Update popup
            if (layers.thuadat) {
                layers.thuadat.eachLayer(layer => {
                    if (layer.feature?.properties?.id == id) {
                        layer.setPopupContent(popupContent('thuadat', layer.feature.properties));
                    }
                });
            }
            showToast('Đã xóa hình ảnh');
        } else {
            showToast('Lỗi: ' + result.message);
        }
    } catch (err) {
        showToast('Lỗi kết nối');
    }
}

// ── Lightbox Functions ──
let ltAlbum = [];
let ltIdx = 0;

function openLightbox(src, albumRaw = '') {
    if (albumRaw) {
        try {
            ltAlbum = typeof albumRaw === 'string' ? JSON.parse(albumRaw.replace(/&quot;/g, '"')) : albumRaw;
            ltIdx = ltAlbum.indexOf(ltAlbum.find(p => fixImageUrl(p) === src));
            if (ltIdx === -1) ltIdx = 0;
        } catch(e) { ltAlbum = [src]; ltIdx = 0; }
    } else {
        ltAlbum = [src];
        ltIdx = 0;
    }
    
    updateLightbox();
    document.getElementById('sbLightbox').classList.add('active');
}

function updateLightbox() {
    const src = fixImageUrl(ltAlbum[ltIdx]);
    document.getElementById('lightboxImg').src = src;
    
    const showNav = ltAlbum.length > 1;
    document.getElementById('ltBtnPrev').classList.toggle('active', showNav);
    document.getElementById('ltBtnNext').classList.toggle('active', showNav);
    document.getElementById('ltCounter').classList.toggle('active', showNav);
    document.getElementById('ltCounter').textContent = `${ltIdx + 1} / ${ltAlbum.length}`;
}

function nextImage(e) {
    if (e) e.stopPropagation();
    ltIdx = (ltIdx + 1) % ltAlbum.length;
    updateLightbox();
}

function prevImage(e) {
    if (e) e.stopPropagation();
    ltIdx = (ltIdx - 1 + ltAlbum.length) % ltAlbum.length;
    updateLightbox();
}

function closeLightbox() {
    document.getElementById('sbLightbox').classList.remove('active');
    document.getElementById('lightboxImg').src = '';
    ltAlbum = [];
}

document.addEventListener('keydown', (e) => {
    if (document.getElementById('sbLightbox').classList.contains('active')) {
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') prevImage();
        if (e.key === 'Escape') closeLightbox();
    } else if (e.key === 'Escape') {
        closeEditModal();
    }
});

// ── Drag and drop for upload area ──
const uploadArea = document.getElementById('uploadArea');
if (uploadArea) {
    uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.classList.add('dragover'); });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('editImageFile').files = files;
            previewUpload(document.getElementById('editImageFile'));
        }
    });
}
</script>
