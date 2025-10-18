<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Bản đồ thiết bị IoT</title>

    <!-- Thư viện CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        /* Cài đặt cơ bản */
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #111827; /* gray-900 */
            overflow: hidden;
            height: 100%;
        }

        /* Trình bao bọc bản đồ */
        #iot-map-wrapper {
            position: relative;
            width: 100%;
            height: 100vh;
        }
        #map {
            height: 100%;
            width: 100%;
            background-color: #111827;
        }

        /* Lớp phủ tải và spinner */
        .loader-overlay {
            position: absolute;
            inset: 0;
            background-color: rgba(17, 24, 39, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            transition: opacity 0.3s ease;
            pointer-events: none; /* Cho phép tương tác với bản đồ khi ẩn */
        }
        .loader {
            border: 4px solid #374151; /* gray-700 */
            border-top: 4px solid #3b82f6; /* blue-500 */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Tiêu đề trang */
        .page-title {
            position: fixed;
            top: 1rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1001;
            background: rgba(31, 41, 55, 0.7); /* gray-800 với độ trong suốt */
            backdrop-filter: blur(5px);
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            border: 1px solid #374151; /* gray-700 */
            color: white;
            font-size: 1.125rem;
            font-weight: 700;
        }

        /* Tùy chỉnh Popup của Leaflet */
        .leaflet-popup-content-wrapper {
            background-color: #1f2937; /* gray-800 */
            color: #e5e7eb; /* gray-200 */
            border-radius: 8px;
            border: 1px solid #4b5563; /* gray-600 */
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        }
        .leaflet-popup-content {
            margin: 0;
            font-size: 14px;
            width: auto !important; /* Cho phép co giãn */
            min-width: 320px; /* Chiều rộng tối thiểu */
            max-width: 400px;
        }
        .leaflet-popup-tip {
            background: #1f2937; /* gray-800 */
        }
        
        /* Nội dung Popup */
        .popup-header {
            padding: 12px 16px;
            font-weight: 700;
            font-size: 16px;
            border-bottom: 1px solid #374151; /* gray-700 */
        }
        .popup-body {
            padding: 12px 16px;
        }
        .popup-body-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }
        .popup-body-row strong {
            color: #9ca3af; /* gray-400 */
            margin-right: 8px;
            flex-shrink: 0;
        }
        .popup-body-row span {
            word-break: break-all;
            text-align: right;
        }
        .popup-body-details {
             font-size: 12px;
        }

        /* Vùng chứa biểu đồ trong Popup */
        .chart-container {
            position: relative;
            height: 180px;
            padding: 12px;
        }
        .chart-message-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 12px;
        }

        /* Biểu tượng Marker tùy chỉnh */
        .custom-marker-icon {
            text-align: center;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            width: 32px;
            height: 32px;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.5);
            transition: transform 0.2s ease;
        }
        .custom-marker-icon:hover {
            transform: scale(1.2);
        }
        .prv-marker { background-color: #3b82f6; } /* blue-500 */
        .qtcln-marker { background-color: #10b981; } /* emerald-500 */
        
    </style>

    <!-- Thư viện JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div id="iot-map-wrapper">
        <h1 class="page-title">Bản đồ thiết bị IoT</h1>
        
        <div id="loader-overlay" class="loader-overlay"><div class="loader"></div></div>
        
        <div id="map"></div>
        
        <div id="error-container" class="hidden fixed bottom-4 left-4 max-w-sm p-4 bg-red-900 border border-red-700 rounded-lg text-red-300 z-[1001] flex items-start">
            <span id="error-message" class="flex-grow"></span>
            <button onclick="App.utils.hideError()" class="ml-4 text-red-200 hover:text-white">&times;</button>
        </div>
    </div>

    <script>
        const App = {
            // Cấu hình và hằng số
            config: {
                apiBaseUrl: 'https://iot-apis.saigonvalve.vn/v1',
                map: {
                    initialView: [10.7769, 106.7009],
                    initialZoom: 12,
                },
                credentials: {
                    emailOrUsername: "demo2",
                    password: "Demo@@22"
                }
            },

            // Trạng thái ứng dụng
            state: {
                map: null,
                deviceLayerGroup: null,
                chartInstance: null,
                accessToken: null,
            },

            // Tham chiếu đến các phần tử UI
            ui: {
                loaderOverlay: null,
                errorContainer: null,
                errorMessage: null,
            },

            /**
             * Khởi tạo ứng dụng
             */
            async init() {
                this.ui.loaderOverlay = document.getElementById('loader-overlay');
                this.ui.errorContainer = document.getElementById('error-container');
                this.ui.errorMessage = document.getElementById('error-message');

                this.utils.showLoader();
                this.map.init();

                try {
                    const loginData = await this.api.login();
                    this.state.accessToken = loginData.accessToken;
                    const roleId = loginData.userData?.roleId;

                    if (!roleId) {
                        throw new Error("Không thể lấy Role ID từ phản hồi đăng nhập.");
                    }

                    const devices = await this.api.getDevices(roleId);
                    if (!devices || devices.length === 0) {
                        this.utils.showError("Không tìm thấy thiết bị nào.", false);
                    }

                    this.map.addDevicesToMap(devices);

                } catch (error) {
                    console.error("Lỗi khởi tạo:", error);
                    this.utils.showError(error.message);
                } finally {
                    this.utils.hideLoader();
                }
            },
            
            // Các hàm tương tác với API
            api: {
                async login() {
                    const response = await fetch(`${App.config.apiBaseUrl}/auth/login`, {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify(App.config.credentials)
                    });
                    if (!response.ok) throw new Error(`Đăng nhập thất bại: ${response.statusText}`);
                    return await response.json();
                },

                async getDevices(roleId) {
                    const response = await fetch(`${App.config.apiBaseUrl}/role/device?roleId=${roleId}`, {
                        headers: { Authorization: `Bearer ${App.state.accessToken}` }
                    });
                    if (!response.ok) throw new Error(`Không thể lấy danh sách thiết bị: ${response.statusText}`);
                    const data = await response.json();
                    return data.data || [];
                },
                
                async getDeviceData(device) {
                    const endpoint = device.dataType === 'PRV' ? 'vga-data' : 'carbonate-hardness-data';
                    const url = `${App.config.apiBaseUrl}/${endpoint}?deviceId=${device.deviceId}&sort=DESC&perPage=13&page=1`;
                    const response = await fetch(url, { headers: { Authorization: `Bearer ${App.state.accessToken}` } });
                    if (!response.ok) throw new Error(`Không thể lấy dữ liệu biểu đồ: ${response.statusText}`);
                    const result = await response.json();
                    return (result.data || []).reverse();
                }
            },

            // Các hàm liên quan đến bản đồ
            map: {
                init() {
                    App.state.map = L.map('map').setView(App.config.map.initialView, App.config.map.initialZoom);
                    const dark = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: 'CartoDB' }).addTo(App.state.map);
                    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: 'OpenStreetMap' });
                    const satellite = L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', { subdomains:['mt0','mt1','mt2','mt3'], attribution: 'Google' });

                    const baseMaps = { "Bản đồ tối": dark, "Đường phố": osm, "Vệ tinh": satellite };
                    App.state.deviceLayerGroup = L.featureGroup().addTo(App.state.map);
                    L.control.layers(baseMaps, { "Thiết bị": App.state.deviceLayerGroup }).addTo(App.state.map);
                },

                addDevicesToMap(devices) {
                    devices.forEach(device => {
                        const lat = parseFloat(device.device?.latitude);
                        const lng = parseFloat(device.device?.longitude);

                        if (isNaN(lat) || isNaN(lng)) {
                            console.warn(`Bỏ qua thiết bị ${device.deviceId} vì tọa độ không hợp lệ.`);
                            return;
                        }
                        
                        const isPrv = device.device?.name?.toLowerCase().includes('prv');
                        device.dataType = isPrv ? 'PRV' : 'QTCLN';

                        const icon = L.divIcon({
                            html: `<i class="fa-solid ${isPrv ? 'fa-faucet' : 'fa-flask-vial'}"></i>`,
                            className: `custom-marker-icon ${isPrv ? 'prv-marker' : 'qtcln-marker'}`,
                            iconSize: [32, 32],
                            iconAnchor: [16, 16]
                        });

                        const marker = L.marker([lat, lng], { icon });
                        marker.bindPopup(() => App.popup.createContent(device), { minWidth: 320 });
                        marker.on('popupopen', (e) => App.popup.onOpen(e, device));
                        App.state.deviceLayerGroup.addLayer(marker);
                    });

                    if (App.state.deviceLayerGroup.getLayers().length > 0) {
                        App.state.map.fitBounds(App.state.deviceLayerGroup.getBounds().pad(0.2));
                    }
                }
            },
            
            // Các hàm liên quan đến Popup
            popup: {
                createContent(device) {
                    return `
                        <div class="popup-header">${device.device?.name || 'Thiết bị không tên'}</div>
                        <div class="popup-body">
                            <div class="popup-body-row"><strong>ID:</strong><span>${device.deviceId}</span></div>
                            <div class="popup-body-row"><strong>Loại:</strong><span>${device.dataType}</span></div>
                            <div class="popup-body-details mt-2 border-t border-gray-700 pt-2">
                                <p class="text-gray-400 text-center">Đang tải dữ liệu mới nhất...</p>
                            </div>
                        </div>
                        <div class="chart-container">
                            <div class="chart-message-overlay">
                                <div class="loader"></div>
                            </div>
                            <canvas class="device-chart"></canvas>
                        </div>
                    `;
                },

                onOpen(e, device) {
                    const popupElement = e.popup.getElement();
                    if (popupElement) {
                        App.chart.render(popupElement, device);
                    }
                },

                updateBody(popupElement, device, latestData) {
                    const detailsContainer = popupElement.querySelector('.popup-body-details');
                    if (!detailsContainer) return;

                    let detailsHtml = '';
                    const updateTime = new Date(+latestData.ts).toLocaleString('vi-VN');

                    if (device.dataType === 'PRV') {
                        detailsHtml = `
                            <div class="popup-body-row"><strong>Áp suất trước:</strong><span>${latestData.pressureBeforeValve}</span></div>
                            <div class="popup-body-row"><strong>Áp suất sau:</strong><span>${latestData.pressureAfterValve}</span></div>
                            <div class="popup-body-row"><strong>Lưu lượng:</strong><span>${latestData.waterflow}</span></div>
                            <div class="popup-body-row"><strong>Lưu lượng tổng:</strong><span>${latestData.Q_TONG}</span></div>
                        `;
                    } else { // QTCLN
                        detailsHtml = `
                            <div class="popup-body-row"><strong>pH:</strong><span>${latestData.ph}</span></div>
                            <div class="popup-body-row"><strong>Amoni:</strong><span>${latestData.amoni}</span></div>
                            <div class="popup-body-row"><strong>DO:</strong><span>${latestData.dissolvedOxygen}</span></div>
                            <div class="popup-body-row"><strong>COD:</strong><span>${latestData.chemicalOxygenDemand}</span></div>
                            <div class="popup-body-row"><strong>TSS:</strong><span>${latestData.totalSuspendedSolids}</span></div>
                        `;
                    }
                    detailsHtml += `<div class="popup-body-row mt-1 pt-1 border-t border-gray-600"><strong>Cập nhật:</strong><span>${updateTime}</span></div>`;

                    detailsContainer.innerHTML = detailsHtml;
                }
            },
            
            // Hàm vẽ biểu đồ
            chart: {
                async render(popupElement, device) {
                    const container = popupElement.querySelector('.chart-container');
                    const canvas = container.querySelector('.device-chart');
                    const messageOverlay = container.querySelector('.chart-message-overlay');
                    
                    if (App.state.chartInstance) {
                        App.state.chartInstance.destroy();
                    }

                    try {
                        const data = await App.api.getDeviceData(device);
                        
                        if (data.length > 0) {
                            const latestData = data[data.length - 1];
                            App.popup.updateBody(popupElement, device, latestData);
                        } else {
                            messageOverlay.innerHTML = '<p class="text-gray-400">Không có dữ liệu để hiển thị.</p>';
                            const detailsContainer = popupElement.querySelector('.popup-body-details');
                            if (detailsContainer) {
                                detailsContainer.innerHTML = '<p class="text-gray-400 text-center">Không có dữ liệu mới nhất.</p>';
                            }
                            return;
                        }

                        const labels = data.map(d => new Date(+d.ts).toLocaleTimeString('vi-VN'));
                        let datasets;

                        if (device.dataType === 'PRV') {
                            datasets = [
                                { label: 'Áp suất trước', data: data.map(d => d.pressureBeforeValve), borderColor: '#3b82f6', tension: 0.1, borderWidth: 2, pointRadius: 2 },
                                { label: 'Áp suất sau', data: data.map(d => d.pressureAfterValve), borderColor: '#ef4444', tension: 0.1, borderWidth: 2, pointRadius: 2 },
                                { label: 'Lưu lượng', data: data.map(d => d.waterflow), borderColor: '#f97316', tension: 0.1, borderWidth: 2, pointRadius: 2 }
                            ];
                        } else {
                            datasets = [
                                { label: 'pH', data: data.map(d => d.ph), borderColor: '#10b981', tension: 0.1, borderWidth: 2, pointRadius: 2 },
                                { label: 'Amoni', data: data.map(d => d.amoni), borderColor: '#f59e0b', tension: 0.1, borderWidth: 2, pointRadius: 2 },
                                { label: 'DO', data: data.map(d => d.dissolvedOxygen), borderColor: '#6366f1', tension: 0.1, borderWidth: 2, pointRadius: 2 },
                                { label: 'COD', data: data.map(d => d.chemicalOxygenDemand), borderColor: '#ec4899', tension: 0.1, borderWidth: 2, pointRadius: 2 },
                                { label: 'TSS', data: data.map(d => d.totalSuspendedSolids), borderColor: '#8b5cf6', tension: 0.1, borderWidth: 2, pointRadius: 2 }
                            ];
                        }
                        
                        messageOverlay.style.display = 'none';

                        App.state.chartInstance = new Chart(canvas, {
                            type: 'line',
                            data: { labels, datasets },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: { 
                                    x: { ticks: { color: '#9ca3af' }, grid: { color: 'rgba(156, 163, 175, 0.1)' } }, 
                                    y: { ticks: { color: '#9ca3af' }, grid: { color: 'rgba(156, 163, 175, 0.1)' } } 
                                },
                                plugins: { 
                                    legend: { labels: { color: '#e5e7eb', boxWidth: 15, padding: 15 } } 
                                }
                            }
                        });

                    } catch (error) {
                        console.error("Lỗi vẽ biểu đồ:", error);
                        messageOverlay.innerHTML = `<p class="text-red-400 text-center">${error.message}</p>`;
                    }
                }
            },
            
            utils: {
                showLoader() { App.ui.loaderOverlay.style.opacity = '1'; App.ui.loaderOverlay.classList.remove('hidden'); },
                hideLoader() { 
                    App.ui.loaderOverlay.style.opacity = '0';
                    setTimeout(() => App.ui.loaderOverlay.classList.add('hidden'), 300); 
                },
                showError(message, autoHide = true) {
                    App.ui.errorMessage.textContent = message;
                    App.ui.errorContainer.classList.remove('hidden');
                    if(autoHide) {
                       setTimeout(() => this.hideError(), 5000);
                    }
                },
                hideError() { App.ui.errorContainer.classList.add('hidden'); }
            }
        };

        document.addEventListener('DOMContentLoaded', () => App.init());
    </script>
</body>
</html>

