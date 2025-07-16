<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Bản đồ thiết bị IoT</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <!-- Leaflet.draw CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <!-- Leaflet-measure CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-measure@3.1.0/dist/leaflet-measure.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        html, body {
            margin: 0; padding: 0; font-family: 'Inter', sans-serif;
            background-color: #111827; overflow: hidden; height: 100%; width: 100%;
        }
        #iot-map-wrapper {
            position: relative; width: 100%; height: 100vh;
        }
        #map {
            height: 100%; width: 100%; background-color: #111827;
        }
        .loader-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(17, 24, 39, 0.8); display: flex;
            justify-content: center; align-items: center; z-index: 2000;
            transition: opacity 0.3s ease-in-out;
        }
        .loader {
            border: 4px solid #374151; border-top: 4px solid #3b82f6;
            border-radius: 50%; width: 50px; height: 50px;
            animation: spin 1s linear infinite;
        }
        .page-title {
            position: fixed; top: 1rem; left: 50%; transform: translateX(-50%);
            z-index: 1001; background: rgba(31, 41, 55, 0.7);
            backdrop-filter: blur(5px); padding: 0.5rem 1.5rem;
            border-radius: 0.5rem; border: 1px solid #374151; color: white;
            font-size: 1.125rem; font-weight: 700;
        }
        /* Tùy chỉnh Popup của Leaflet */
        .leaflet-popup-content-wrapper {
            background-color: #1f2937; color: #e5e7eb; border-radius: 8px;
            border: 1px solid #4b5563; box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        }
        .leaflet-popup-content { margin: 0; font-size: 14px; line-height: 1.6; width: 400px !important; }
        .popup-header { padding: 12px 16px; font-weight: 700; font-size: 16px; border-bottom: 1px solid #374151; }
        .popup-body { padding: 12px 16px; max-height: 200px; overflow-y: auto; }
        .popup-body div { display: flex; justify-content: space-between; padding: 4px 0; }
        .popup-body strong { color: #9ca3af; }
        .leaflet-popup-tip { background: #1f2937; }
        .leaflet-container a.leaflet-popup-close-button { color: #e5e7eb; padding: 8px 8px 0 0; }
        
        /* Tùy chỉnh icon marker */
        .custom-marker-icon {
            text-align: center; color: white; border-radius: 50%; line-height: 30px; font-size: 16px;
            width: 30px; height: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.5); border: 2px solid white;
            transition: transform 0.2s ease;
        }
        .custom-marker-icon:hover { transform: scale(1.2); }
        .prv-marker { background-color: #3b82f6; }
        .qtcln-marker { background-color: #10b981; }

        /* Tùy chỉnh giao diện công cụ Leaflet */
        .leaflet-control-container .leaflet-bar a,
        .leaflet-control-container .leaflet-bar a:hover {
            background-color: #1f2937 !important; color: #e5e7eb !important;
            border-bottom: 1px solid #374151 !important;
        }
        .leaflet-control-layers, .leaflet-draw-toolbar, .leaflet-measure-path-prompt {
            background-color: rgba(31, 41, 55, 0.8) !important;
            backdrop-filter: blur(5px);
            border-radius: 4px;
            color: #e5e7eb;
        }
        .chart-container { padding: 16px; }
        .chart-loader { height: 150px; display: flex; justify-content: center; align-items: center; }

        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- Leaflet.draw JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <!-- Leaflet-measure JS -->
    <script src="https://cdn.jsdelivr.net/npm/leaflet-measure@3.1.0/dist/leaflet-measure.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div id="iot-map-wrapper">
        <h1 class="page-title">Bản đồ thiết bị IoT</h1>
        <div id="loader-overlay" class="loader-overlay"><div class="loader"></div></div>
        <div id="map"></div>
        <div id="error-container" class="hidden fixed bottom-4 left-4 p-4 bg-red-900 border border-red-700 rounded-lg text-red-300 z-[1001]"></div>
    </div>

    <script>
        const API_BASE_URL = 'https://test.iot-apis.saigonvalve.vn/v1';
        const UI = {
            loaderOverlay: document.getElementById('loader-overlay'),
            errorContainer: document.getElementById('error-container'),
        };
        
        let map = null;
        let deviceLayerGroup = null;
        let chartInstance = null;
        let currentAccessToken = null;

        /**
         * Tạo nội dung HTML cho popup của marker.
         */
        function createPopupContent(device) {
            let header = `<div class="popup-header">${device.device?.name || 'Không có tên'}</div>`;
            let body = `<div class="popup-body">`;
            body += `<div><strong>ID Thiết bị:</strong><span>${device.deviceId}</span></div>`;
            body += `<div><strong>Loại:</strong><span>${device.dataType}</span></div>`;
            body += `</div>`;
            let chart = `<div class="chart-container"><div class="chart-loader"><div class="loader"></div></div><canvas id="device-chart"></canvas></div>`;
            return header + body + chart;
        }

        /**
         * Lấy dữ liệu lịch sử và vẽ biểu đồ.
         */
        async function fetchAndRenderChart(device) {
            if (!currentAccessToken) return;
            const chartCanvas = document.getElementById('device-chart');
            const chartLoader = document.querySelector('.chart-loader');
            if (!chartCanvas || !chartLoader) return;

            chartLoader.style.display = 'flex';
            chartCanvas.style.display = 'none';
            if (chartInstance) chartInstance.destroy();

            try {
                const isPrv = device.dataType === 'PRV';
                const endpoint = isPrv ? 'vga-data' : 'carbonate-hardness-data';
                const dataUrl = `${API_BASE_URL}/${endpoint}?deviceId=${device.deviceId}&sort=DESC&perPage=13&page=1`;
                const response = await fetch(dataUrl, { headers: { 'Authorization': `Bearer ${currentAccessToken}` } });
                if (!response.ok) throw new Error('Lấy dữ liệu biểu đồ thất bại');
                
                const result = await response.json();
                const historicalData = result.data?.reverse() || []; // Đảo ngược để hiển thị từ cũ đến mới

                if (historicalData.length === 0) {
                    chartLoader.innerHTML = '<p class="text-gray-400">Không có dữ liệu lịch sử.</p>';
                    return;
                }

                const labels = historicalData.map(d => new Date(parseInt(d.ts)).toLocaleTimeString('vi-VN'));
                let datasets = [];

                if (isPrv) {
                    datasets = [
                        { label: 'Áp suất trước van', data: historicalData.map(d => d.pressureBeforeValve), borderColor: '#3b82f6', tension: 0.1 },
                        { label: 'Áp suất sau van', data: historicalData.map(d => d.pressureAfterValve), borderColor: '#ef4444', tension: 0.1 },
                        { label: 'Lưu lượng', data: historicalData.map(d => d.waterflow), borderColor: '#f97316', tension: 0.1 }
                    ];
                } else { // QTCLN
                    datasets = [
                        { label: 'pH', data: historicalData.map(d => d.ph), borderColor: '#10b981', tension: 0.1 },
                        { label: 'Amoni', data: historicalData.map(d => d.amoni), borderColor: '#f59e0b', tension: 0.1 },
                        { label: 'DO', data: historicalData.map(d => d.dissolvedOxygen), borderColor: '#6366f1', tension: 0.1 }
                    ];
                }

                chartLoader.style.display = 'none';
                chartCanvas.style.display = 'block';

                chartInstance = new Chart(chartCanvas, {
                    type: 'line',
                    data: { labels, datasets },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: { y: { ticks: { color: '#9ca3af' } }, x: { ticks: { color: '#9ca3af' } } },
                        plugins: { legend: { labels: { color: '#e5e7eb' } } }
                    }
                });

            } catch (error) {
                console.error("Lỗi vẽ biểu đồ:", error);
                chartLoader.innerHTML = `<p class="text-red-400">${error.message}</p>`;
            }
        }

        /**
         * Khởi tạo bản đồ và các công cụ.
         */
        function initMap() {
            map = L.map('map', { zoomControl: false }).setView([10.7769, 106.7009], 12);
            L.control.zoom({ position: 'topright' }).addTo(map);

            const baseMaps = {
                "Bản đồ tối": L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19, attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>' }),
                "Vệ tinh": L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}',{ maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'], attribution: 'Google' }),
                "Đường phố": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>' })
            };
            baseMaps["Bản đồ tối"].addTo(map);

            const drawnItems = new L.FeatureGroup().addTo(map);
            deviceLayerGroup = L.featureGroup().addTo(map);
            
            L.control.layers(baseMaps, { "Thiết bị": deviceLayerGroup, "Lớp vẽ": drawnItems }, { position: 'topright' }).addTo(map);

            map.addControl(new L.Control.Draw({ position: 'topright', edit: { featureGroup: drawnItems }, draw: { polygon: { showArea: true }, polyline: true, rectangle: true, circle: false, marker: false, circlemarker: false } }));
            map.on(L.Draw.Event.CREATED, (event) => drawnItems.addLayer(event.layer));

            (new L.Control.Measure({ position: 'topright', primaryLengthUnit: 'meters', primaryAreaUnit: 'sqmeters', activeColor: '#3b82f6', completedColor: '#10b981' })).addTo(map);
            
            L.Control.Home = L.Control.extend({
                onAdd: function(map) {
                    const btn = L.DomUtil.create('a', 'leaflet-bar-part leaflet-bar-part-single');
                    btn.innerHTML = '<i class="fa fa-home" style="font-size:1.2em; line-height:1.4;"></i>';
                    btn.href = '#'; btn.title = 'Về khung nhìn mặc định'; btn.role = 'button';
                    L.DomEvent.on(btn, 'click', (e) => {
                        L.DomEvent.stop(e);
                        if (deviceLayerGroup.getLayers().length > 0) map.fitBounds(deviceLayerGroup.getBounds().pad(0.2));
                        else map.setView([10.7769, 106.7009], 12);
                    });
                    return btn;
                }
            });
            const homeControl = new L.Control.Home({ position: 'topright' });
            const customBar = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
            customBar.appendChild(homeControl.onAdd(map));
            map.getContainer().querySelector('.leaflet-top.leaflet-right').prepend(customBar);
        }

        /**
         * Hiển thị các thiết bị lên bản đồ đã được khởi tạo.
         */
        function addDevicesToMap(devices) {
            devices.forEach(device => {
                // **FIXED**: Xác định và gán `dataType` cho mỗi thiết bị
                const isPrv = device.device?.name?.toLowerCase().includes('prv');
                device.dataType = isPrv ? 'PRV' : 'QTCLN';

                if (device.device?.latitude && device.device?.longitude) {
                    const lat = parseFloat(device.device.latitude);
                    const lng = parseFloat(device.device.longitude);

                    if (!isNaN(lat) && !isNaN(lng)) {
                        const customIcon = L.divIcon({
                            html: `<i class="fa-solid ${isPrv ? 'fa-faucet' : 'fa-flask-vial'}"></i>`,
                            className: `custom-marker-icon ${isPrv ? 'prv-marker' : 'qtcln-marker'}`,
                            iconSize: [30, 30]
                        });
                        const marker = L.marker([lat, lng], { icon: customIcon });
                        marker.bindPopup(() => createPopupContent(device), { minWidth: 400 });
                        marker.on('popupopen', () => fetchAndRenderChart(device));
                        deviceLayerGroup.addLayer(marker);
                    }
                }
            });

            if (deviceLayerGroup.getLayers().length > 0) {
                map.fitBounds(deviceLayerGroup.getBounds().pad(0.2));
            } else {
                UI.errorContainer.textContent = 'Không có thiết bị nào có tọa độ hợp lệ.';
                UI.errorContainer.classList.remove('hidden');
            }
        }

        /**
         * Hàm chính để thực hiện chuỗi lệnh gọi API.
         */
        async function main() {
            initMap();
            try {
                const loginResponse = await fetch(`${API_BASE_URL}/auth/login`, {
                    method: 'POST', headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ emailOrUsername: "demo2", password: "Demo@@22" })
                });
                if (!loginResponse.ok) throw new Error(`Đăng nhập thất bại: ${loginResponse.statusText}`);
                const { accessToken, userData } = await loginResponse.json();
                if (!accessToken || !userData?.roleId) throw new Error('Không nhận được accessToken hoặc roleId.');
                currentAccessToken = accessToken;

                const devicesResponse = await fetch(`${API_BASE_URL}/role/device?roleId=${userData.roleId}`, {
                    headers: { 'Authorization': `Bearer ${accessToken}` }
                });
                if (!devicesResponse.ok) throw new Error(`Không thể lấy danh sách thiết bị: ${devicesResponse.statusText}`);
                const devicesResponseData = await devicesResponse.json();
                const devices = Array.isArray(devicesResponseData) ? devicesResponseData : devicesResponseData.devices || devicesResponseData.data;
                if (!devices?.length) throw new Error("Không tìm thấy thiết bị nào.");

                addDevicesToMap(devices);

            } catch (error) {
                console.error("Lỗi quy trình:", error);
                UI.errorContainer.textContent = `Lỗi: ${error.message}`;
                UI.errorContainer.classList.remove('hidden');
            } finally {
                UI.loaderOverlay.style.opacity = '0';
                UI.loaderOverlay.style.pointerEvents = 'none';
                setTimeout(() => UI.loaderOverlay.classList.add('hidden'), 300);
            }
        }

        document.addEventListener('DOMContentLoaded', main);
    </script>
</body>
</html>
