@extends('layouts.admin')

@section('title', 'Quản lý Giao hàng')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@section('content')
<main class="md:ml-[280px] pt-16 min-h-screen">
    <div class="px-4 md:px-lg py-xl space-y-xl max-w-[800px] mx-auto">
        <div class="flex items-center justify-between mb-lg">
            <h1 class="text-on-surface font-display-sm text-display-sm">Thiết lập Giao hàng</h1>
        </div>

        @if(session('success'))
        <div class="p-md bg-green-100 text-green-800 rounded-xl mb-md">
            {{ session('success') }}
        </div>
        @endif

        <form action="/admin/delivery-settings" method="POST" class="bg-white p-lg rounded-2xl border border-outline-variant shadow-sm space-y-md">
            @csrf
            
            <div class="space-y-md">
                <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Địa chỉ cửa hàng *</label>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-md">
                    <div>
                        <select id="province_select" name="store_province" required class="no-choices w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors" data-selected="{{ old('store_province', $settings['store_province'] ?? '') }}">
                            <option value="">Chọn Tỉnh/Thành phố</option>
                        </select>
                    </div>
                    <div>
                        <select id="district_select" name="store_district" required disabled class="no-choices w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors" data-selected="{{ old('store_district', $settings['store_district'] ?? '') }}">
                            <option value="">Chọn Quận/Huyện</option>
                        </select>
                    </div>
                    <div>
                        <select id="ward_select" name="store_ward" required disabled class="no-choices w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors" data-selected="{{ old('store_ward', $settings['store_ward'] ?? '') }}">
                            <option value="">Chọn Phường/Xã</option>
                        </select>
                    </div>
                </div>
                
                <input type="text" name="store_specific_address" value="{{ old('store_specific_address', $settings['store_specific_address'] ?? '') }}" required
                       class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md mt-sm"
                       placeholder="Ví dụ: Số 123, Đường XYZ">
                <p class="text-xs text-on-surface-variant mt-1">Được dùng làm điểm xuất phát để tính khoảng cách giao hàng nếu không có tọa độ cụ thể.</p>
            </div>

            <div class="space-y-md">
                <div class="flex items-center justify-between mb-xs">
                    <label class="block font-label-md text-label-md text-on-surface-variant">Tọa độ cửa hàng (Tùy chọn nhưng khuyên dùng)</label>
                    <button type="button" onclick="getCurrentLocation()" class="text-primary hover:text-primary/80 font-label-sm text-sm flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">my_location</span>
                        Lấy tọa độ hiện tại
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <input type="text" id="store_lat" name="store_lat" value="{{ old('store_lat', $settings['store_lat'] ?? '') }}" 
                               class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md"
                               placeholder="Vĩ độ (Latitude) - VD: 20.5592">
                    </div>
                    <div>
                        <input type="text" id="store_lon" name="store_lon" value="{{ old('store_lon', $settings['store_lon'] ?? '') }}" 
                               class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md"
                               placeholder="Kinh độ (Longitude) - VD: 105.8824">
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Bản đồ (Click hoặc Kéo thả ghim để chọn vị trí cửa hàng)</label>
                    <div id="admin_map" class="w-full h-64 border border-outline-variant shadow-inner rounded-xl z-10" style="z-index: 10;"></div>
                </div>
                
                <p class="text-xs text-on-surface-variant mt-1">Hệ thống bản đồ miễn phí có thể không tìm chính xác địa chỉ xã/huyện của bạn, dẫn đến sai số lớn khi tính phí ship. Việc cung cấp tọa độ này sẽ giúp tính phí ship chính xác tuyệt đối.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Phí giao hàng / km (VNĐ) *</label>
                    <input type="number" name="fee_per_km" value="{{ old('fee_per_km', $settings['fee_per_km'] ?? 0) }}" required min="0"
                           class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md"
                           placeholder="Ví dụ: 5000">
                    @error('fee_per_km')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Bán kính tối đa (km) *</label>
                    <input type="number" name="max_delivery_radius" value="{{ old('max_delivery_radius', $settings['max_delivery_radius'] ?? 0) }}" required min="0"
                           class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md"
                           placeholder="Ví dụ: 10">
                    <p class="text-xs text-on-surface-variant mt-1">Hệ thống sẽ từ chối đơn xa hơn mức này.</p>
                    @error('max_delivery_radius')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-md">
                <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Bảng giá giao hàng theo khoảng cách *</label>
                <div class="bg-surface-container-low border border-outline-variant rounded-xl p-md">
                    <p class="text-sm text-on-surface-variant mb-4">Hệ thống sẽ lấy giá tương ứng với mốc khoảng cách tối đa. Nếu vượt quá mốc lớn nhất, sẽ cộng thêm phí phát sinh (Phí giao hàng / km bên trên).</p>
                    <div id="tiers_container" class="space-y-sm">
                        <!-- Tiers will be rendered here by JS -->
                    </div>
                    <button type="button" onclick="addTier()" class="mt-4 px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary/10 text-sm font-medium">
                        + Thêm mốc giá
                    </button>
                    <input type="hidden" name="shipping_tiers" id="shipping_tiers_input" value="{{ old('shipping_tiers', $settings['shipping_tiers'] ?? '') }}">
                </div>
                @error('shipping_tiers')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mt-md">
                <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Đơn hàng tối thiểu để giao (VNĐ) *</label>
                <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $settings['min_order_amount'] ?? 0) }}" required min="0"
                       class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md"
                       placeholder="Ví dụ: 300000">
                <p class="text-xs text-on-surface-variant mt-1">Đơn hàng phải có tổng tiền bằng hoặc lớn hơn mức này mới được đặt.</p>
                @error('min_order_amount')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="pt-md border-t border-outline-variant">
                <button type="submit" class="px-xl py-md bg-primary text-on-primary font-label-lg text-label-lg rounded-full hover:bg-primary/90 transition-colors">
                    Lưu cài đặt
                </button>
            </div>
        </form>
    </div>
</main>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectProvince = document.getElementById('province_select');
    const selectDistrict = document.getElementById('district_select');
    const selectWard = document.getElementById('ward_select');

    const initialProvince = selectProvince.getAttribute('data-selected');
    const initialDistrict = selectDistrict.getAttribute('data-selected');
    const initialWard = selectWard.getAttribute('data-selected');

    // Load Provinces using ESGOO API (more stable)
    fetch('https://esgoo.net/api-tinhthanh/1/0.htm')
        .then(res => res.json())
        .then(data => {
            if (data.error === 0) {
                data.data.forEach(p => {
                    const option = document.createElement('option');
                    // Store full_name as the value (e.g. "Tỉnh Hà Nội")
                    option.value = p.full_name;
                    option.text = p.full_name;
                    option.dataset.code = p.id;
                    selectProvince.appendChild(option);
                });
                
                if (initialProvince) {
                    selectProvince.value = initialProvince;
                    // Trigger change to load districts if a province was pre-selected
                    const event = new Event('change');
                    selectProvince.dispatchEvent(event);
                }
            }
        })
        .catch(err => console.error('Lỗi khi tải tỉnh/thành phố:', err));

    selectProvince.addEventListener('change', function() {
        selectDistrict.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        selectWard.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        selectDistrict.disabled = true;
        selectWard.disabled = true;
        
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value && selectedOption && selectedOption.dataset.code) {
            selectDistrict.disabled = false;
            selectDistrict.options[0].text = 'Đang tải...';
            
            fetch(`https://esgoo.net/api-tinhthanh/2/${selectedOption.dataset.code}.htm`)
                .then(res => res.json())
                .then(data => {
                    selectDistrict.options[0].text = 'Chọn Quận/Huyện';
                    if (data.error === 0) {
                        data.data.forEach(d => {
                            const option = document.createElement('option');
                            option.value = d.full_name;
                            option.text = d.full_name;
                            option.dataset.code = d.id;
                            selectDistrict.appendChild(option);
                        });
                        
                        if (initialDistrict && this.value === initialProvince) {
                            selectDistrict.value = initialDistrict;
                            const event = new Event('change');
                            selectDistrict.dispatchEvent(event);
                        }
                    }
                })
                .catch(err => {
                    selectDistrict.options[0].text = 'Lỗi kết nối';
                });
        }
    });

    selectDistrict.addEventListener('change', function() {
        selectWard.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        selectWard.disabled = true;
        
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value && selectedOption && selectedOption.dataset.code) {
            selectWard.disabled = false;
            selectWard.options[0].text = 'Đang tải...';
            
            fetch(`https://esgoo.net/api-tinhthanh/3/${selectedOption.dataset.code}.htm`)
                .then(res => res.json())
                .then(data => {
                    selectWard.options[0].text = 'Chọn Phường/Xã';
                    if (data.error === 0) {
                        data.data.forEach(w => {
                            const option = document.createElement('option');
                            option.value = w.full_name;
                            option.text = w.full_name;
                            selectWard.appendChild(option);
                        });
                        
                        if (initialWard && this.value === initialDistrict) {
                            selectWard.value = initialWard;
                        }
                    }
                })
                .catch(err => {
                    selectWard.options[0].text = 'Lỗi kết nối';
                });
        }
    });

    const tiersContainer = document.getElementById('tiers_container');
    const tiersInput = document.getElementById('shipping_tiers_input');
    let tiers = [];
    
    try {
        if (tiersInput.value) {
            tiers = JSON.parse(tiersInput.value);
        }
    } catch (e) {
        console.error("Invalid shipping tiers JSON");
    }

    function renderTiers() {
        tiersContainer.innerHTML = '';
        if (tiers.length === 0) {
            tiersContainer.innerHTML = '<p class="text-sm text-on-surface-variant italic">Chưa có mốc giá nào. Hệ thống sẽ dùng giá mặc định.</p>';
        }
        
        tiers.forEach((tier, index) => {
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2';
            row.innerHTML = `
                <span class="text-sm">Từ 0 đến </span>
                <input type="number" min="0" step="0.1" value="${tier.max_km}" onchange="updateTier(${index}, 'max_km', this.value)" class="w-20 p-2 rounded-lg border border-outline-variant text-sm" placeholder="{{ __('Km') }}">
                <span class="text-sm">km có phí: </span>
                <input type="number" min="0" step="1000" value="${tier.fee}" onchange="updateTier(${index}, 'fee', this.value)" class="w-32 p-2 rounded-lg border border-outline-variant text-sm" placeholder="VNĐ">
                <button type="button" onclick="removeTier(${index})" class="text-error hover:bg-error/10 p-2 rounded-lg">
                    <span class="material-symbols-outlined text-[20px]">delete</span>
                </button>
            `;
            tiersContainer.appendChild(row);
        });
        
        tiersInput.value = JSON.stringify(tiers);
    }

    window.addTier = function() {
        tiers.push({ max_km: 0, fee: 0 });
        renderTiers();
    };

    window.removeTier = function(index) {
        tiers.splice(index, 1);
        renderTiers();
    };

    window.updateTier = function(index, field, value) {
        tiers[index][field] = parseFloat(value) || 0;
        tiersInput.value = JSON.stringify(tiers);
    };

    // Initial render
    renderTiers();

    window.getCurrentLocation = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                document.getElementById('store_lat').value = lat;
                document.getElementById('store_lon').value = lon;
                
                if (adminMap && adminMarker) {
                    adminMap.setView([lat, lon], 15);
                    adminMarker.setLatLng([lat, lon]);
                }
            }, function() {
                alert("Không thể lấy vị trí của bạn.");
            });
        } else {
            alert("Trình duyệt của bạn không hỗ trợ lấy vị trí.");
        }
    }
    
    // --- Admin Map Logic ---
    let adminMap = null;
    let adminMarker = null;
    
    function initAdminMap() {
        const latInput = document.getElementById('store_lat');
        const lonInput = document.getElementById('store_lon');
        
        // Mặc định Hà Nội nếu chưa có
        let lat = parseFloat(latInput.value) || 21.0285;
        let lon = parseFloat(lonInput.value) || 105.8542;
        
        adminMap = L.map('admin_map').setView([lat, lon], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(adminMap);
        
        adminMarker = L.marker([lat, lon], {draggable: true}).addTo(adminMap);
        
        adminMarker.on('dragend', function (e) {
            const position = adminMarker.getLatLng();
            latInput.value = position.lat;
            lonInput.value = position.lng;
        });
        
        adminMap.on('click', function(e) {
            adminMarker.setLatLng(e.latlng);
            latInput.value = e.latlng.lat;
            lonInput.value = e.latlng.lng;
        });
        
        latInput.addEventListener('change', function() {
            if (this.value && lonInput.value) {
                adminMap.setView([this.value, lonInput.value], 15);
                adminMarker.setLatLng([this.value, lonInput.value]);
            }
        });
        
        lonInput.addEventListener('change', function() {
            if (latInput.value && this.value) {
                adminMap.setView([latInput.value, this.value], 15);
                adminMarker.setLatLng([latInput.value, this.value]);
            }
        });
    }
    
    // Đợi 1 chút để container hiển thị đầy đủ
    setTimeout(initAdminMap, 500);
});
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush
@endsection
