
document.addEventListener('DOMContentLoaded', function() {
    // === Toast Notification ===
    function showToast(message, type = 'success') {
        const existingToast = document.getElementById('checkout-toast');
        if (existingToast) existingToast.remove();

        const colors = {
            success: { bg: 'bg-primary', text: 'text-on-primary', icon: 'check_circle' },
            error:   { bg: 'bg-error',   text: 'text-on-error',   icon: 'error' },
            warning: { bg: 'bg-[#7d5800]', text: 'text-white',    icon: 'warning' },
            info:    { bg: 'bg-secondary', text: 'text-on-secondary', icon: 'info' },
        };
        const c = colors[type] || colors.info;

        const toast = document.createElement('div');
        toast.id = 'checkout-toast';
        toast.className = 'fixed top-24 left-1/2 -translate-x-1/2 z-[200] min-w-[320px] max-w-sm shadow-2xl rounded-xl overflow-hidden transition-all duration-500';
        toast.innerHTML = `
            <div class="${c.bg} ${c.text} px-4 py-3 flex items-start gap-3">
                <span class="material-symbols-outlined mt-0.5 flex-shrink-0">${c.icon}</span>
                <span class="text-sm flex-1 leading-snug">${message}</span>
                <button onclick="this.closest('#checkout-toast').remove()" class="hover:opacity-70 flex-shrink-0">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => {
            const el = document.getElementById('checkout-toast');
            if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 500); }
        }, 8000);
    }

    // Initialize TomSelect for all dropdowns
    const tsOptions = {
        create: false,
        sortField: {field: "text", direction: "asc"},
        placeholder: 'Chọn...',
        maxOptions: 500,
        dropdownParent: 'body',
    };
    
    const tsSaved = document.getElementById('saved_address_select') ? new TomSelect('#saved_address_select', {
        create: false, placeholder: '-- Nhập địa chỉ mới --', dropdownParent: 'body'
    }) : null;
    
    const tsProvince = new TomSelect('#province_select', tsOptions);
    const tsDistrict = new TomSelect('#district_select', tsOptions);
    const tsWard = new TomSelect('#ward_select', tsOptions);

    // Load Provinces (Force Ninh Binh)
    const options = [{value: 'Tỉnh Ninh Bình', text: 'Tỉnh Ninh Bình', code: 37}];
    tsProvince.addOptions(options);
    tsProvince.refreshOptions(false);


    tsProvince.on('change', function(value) {
        tsDistrict.clearOptions();
        tsDistrict.clear();
        tsWard.clearOptions();
        tsWard.clear();
        tsWard.disable();
        
        if (value) {
            const option = tsProvince.options[value];
            if (option && option.code) {
                tsDistrict.enable();
                tsDistrict.addOption({value: '', text: 'Đang tải...'});
                Promise.all([
                    fetch(`https://provinces.open-api.vn/api/v1/p/35?depth=2`).then(res => res.json()),
                    fetch(`https://provinces.open-api.vn/api/v1/p/36?depth=2`).then(res => res.json()),
                    fetch(`https://provinces.open-api.vn/api/v1/p/37?depth=2`).then(res => res.json())
                ]).then(results => {
                    tsDistrict.clearOptions();
                    let allDistricts = [];
                    results.forEach(data => {
                        if (data.districts) {
                            allDistricts = allDistricts.concat(data.districts);
                        }
                    });
                    
                    // Sắp xếp theo tên
                    allDistricts.sort((a, b) => a.name.localeCompare(b.name));
                    
                    const options = allDistricts.map(d => ({value: d.name, text: d.name, code: d.code}));
                    tsDistrict.addOptions(options);
                    tsDistrict.refreshOptions(false);
                }).catch(err => {
                    console.error('Lỗi khi tải quận/huyện:', err);
                    tsDistrict.clearOptions();
                    tsDistrict.addOption({value: '', text: 'Lỗi tải dữ liệu'});
                });
            } else {
                tsDistrict.disable();
            }
        } else {
            tsDistrict.disable();
        }
    });

    tsDistrict.on('change', function(value) {
        tsWard.clearOptions();
        tsWard.clear();
        
        if (value) {
            const option = tsDistrict.options[value];
            if (option && option.code) {
                tsWard.enable();
                tsWard.addOption({value: '', text: 'Đang tải...'});
                fetch(`https://provinces.open-api.vn/api/v1/d/${option.code}?depth=2`)
                    .then(res => res.json())
                    .then(data => {
                        tsWard.clearOptions();
                        const options = data.wards.map(w => ({value: w.name, text: w.name, code: w.code}));
                        tsWard.addOptions(options);
                        tsWard.refreshOptions(false);
                    });
            } else {
                tsWard.disable();
            }
        } else {
            tsWard.disable();
        }
    });
    
    // Set value and disable after listeners are attached
    tsProvince.setValue('Tỉnh Ninh Bình');
    tsProvince.disable();

    let polygonLayer = null;
    let currentBoundaryGeoJSON = null;

    tsWard.on('change', async function(value) {
        
        const province = tsProvince.getValue();
        const district = tsDistrict.getValue();
        const ward = value;
        
        if (province && district && ward) {
            // Fetch boundary
            try {
                const response = await fetch(`/api/boundary?province=${encodeURIComponent(province)}&district=${encodeURIComponent(district)}&ward=${encodeURIComponent(ward)}`);
                const data = await response.json();
                
                if (data.success && data.geojson) {
                    currentBoundaryGeoJSON = data.geojson;
                    
                    // Nếu map đang mở, chỉ vẽ lại ranh giới, không dịch chuyển marker hay tâm bản đồ
                    if (map && document.getElementById('map_container').style.display !== 'none') {
                        if (polygonLayer) map.removeLayer(polygonLayer);
                        polygonLayer = L.geoJSON(currentBoundaryGeoJSON, {
                            style: { color: '#006e1c', weight: 2, opacity: 0.6, fillOpacity: 0.1 }
                        }).addTo(map);
                    }
                    // Bỏ tự động mở map/zoom về tâm xã ở đây để tránh map nhảy lung tung
                } else {
                    currentBoundaryGeoJSON = null;
                    if(polygonLayer && map) {
                        map.removeLayer(polygonLayer);
                    }
                }
            } catch (e) {
                console.error("Lỗi lấy ranh giới", e);
            }
        }
    });

    // Leaflet Map Integration
    let map = null;
    let marker = null;
    let currentCustomerCoords = null;
    let storeCoords = null;
    
    // Init store coords immediately
    const sl = parseFloat("0");
    const slon = parseFloat("0");
    if (!isNaN(sl) && !isNaN(slon)) {
        storeCoords = { lat: sl, lon: slon };
    }
    
    // Parse shipping tiers passed from backend
    let shippingTiers = [];
    try {
        shippingTiers = null;
    } catch(e) { console.error('Lỗi parse shipping tiers'); }

    function validateMarkerPosition(lat, lng) {
        return true; // Bỏ qua kiểm tra ranh giới, cho phép ghim tự do
    }

    let isReverseGeocoding = false;

    function fuzzyMatch(target, optionsObj) {
        if (!target) return null;
        const cleanTarget = target.toLowerCase().replace(/^(tỉnh|thành phố|quận|huyện|thị xã|phường|xã|thị trấn)\s+/i, '').trim();
        for (let key in optionsObj) {
            let optName = optionsObj[key].text;
            let cleanOpt = optName.toLowerCase().replace(/^(tỉnh|thành phố|quận|huyện|thị xã|phường|xã|thị trấn)\s+/i, '').trim();
            if (cleanOpt === cleanTarget || cleanOpt.includes(cleanTarget) || cleanTarget.includes(cleanOpt)) {
                return key; 
            }
        }
        return null;
    }

    async function reverseGeocode(lat, lng) {
        try {
            isReverseGeocoding = true;
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`;
            const res = await fetch(url, { headers: { 'Accept-Language': 'vi' } });
            const data = await res.json();
            
            if (data && data.address) {
                const addr = data.address;
                const provName = addr.state || addr.city || addr.province;
                const distName = addr.county || addr.city_district || addr.district || addr.borough;
                const wardName = addr.suburb || addr.village || addr.quarter || addr.hamlet || addr.town;
                const roadName = addr.road || '';
                const houseNumber = addr.house_number || '';
                
                const fullStreet = houseNumber ? `${houseNumber} ${roadName}` : roadName;
                if (fullStreet) {
                    document.getElementById('specific_address').value = fullStreet.trim();
                }
                
                let matchedProv = fuzzyMatch(provName, tsProvince.options);
                if (!matchedProv) {
                    matchedProv = 'Tỉnh Ninh Bình'; // Force match if outside
                }
                
                if (matchedProv) {
                    tsProvince.setValue(matchedProv, true);
                    
                    tsDistrict.enable();
                    tsDistrict.clear(true);
                    tsWard.clear(true);
                    tsWard.disable();
                    
                    const distRes1 = await fetch(`https://provinces.open-api.vn/api/v1/p/35?depth=3`);
                    const distRes2 = await fetch(`https://provinces.open-api.vn/api/v1/p/36?depth=3`);
                    const distRes3 = await fetch(`https://provinces.open-api.vn/api/v1/p/37?depth=3`);
                    
                    const dataP1 = await distRes1.json();
                    const dataP2 = await distRes2.json();
                    const dataP3 = await distRes3.json();
                    
                    const dataP = {
                        districts: (dataP1.districts || []).concat(dataP2.districts || []).concat(dataP3.districts || [])
                    };
                    
                    tsDistrict.clearOptions();
                    tsDistrict.addOptions(dataP.districts.map(d => ({value: d.name, text: d.name, code: d.code})));
                    tsDistrict.refreshOptions(false);
                    
                    let possibleNames = [
                        addr.county, addr.city_district, addr.district, addr.borough,
                        addr.suburb, addr.village, addr.quarter, addr.hamlet, addr.town,
                        addr.city, addr.state, addr.province, addr.municipality
                    ].filter(Boolean);
                    
                    // Thêm các bí danh (aliases) để xử lý việc sáp nhập hành chính
                    let aliases = [];
                    possibleNames.forEach(n => {
                        let lower = n.toLowerCase().trim();
                        if (lower === 'ninh bình' || lower === 'thành phố ninh bình' || lower === 'huyện hoa lư') {
                            aliases.push('Hoa Lư');
                        }
                    });
                    possibleNames.push(...aliases);
                    
                    let matchedDist = null;
                    let targetDistObj = null;
                    let targetWardVal = null;
                    
                    // 1. Tìm Huyện trực tiếp
                    for (let d of dataP.districts) {
                        for (let name of possibleNames) {
                            if (fuzzyMatch(name, { [d.name]: {text: d.name} })) {
                                matchedDist = d.name;
                                targetDistObj = d;
                                break;
                            }
                        }
                        if (matchedDist) break;
                    }
                    
                    // 2. Tìm Xã trong Huyện (hoặc tìm cả Huyện+Xã nếu chưa có Huyện)
                    if (targetDistObj) {
                        for (let w of targetDistObj.wards) {
                            for (let name of possibleNames) {
                                if (fuzzyMatch(name, { [w.name]: {text: w.name} })) {
                                    targetWardVal = w.name;
                                    break;
                                }
                            }
                            if (targetWardVal) break;
                        }
                    } else {
                        // Deep search
                        for (let d of dataP.districts) {
                            for (let w of d.wards) {
                                for (let name of possibleNames) {
                                    if (fuzzyMatch(name, { [w.name]: {text: w.name} })) {
                                        matchedDist = d.name;
                                        targetDistObj = d;
                                        targetWardVal = w.name;
                                        break;
                                    }
                                }
                                if (targetWardVal) break;
                            }
                            if (targetWardVal) break;
                        }
                    }
                    
                    if (matchedDist && targetDistObj) {
                        tsDistrict.setValue(matchedDist, true);
                        
                        tsWard.enable();
                        tsWard.clearOptions();
                        tsWard.addOptions(targetDistObj.wards.map(w => ({value: w.name, text: w.name})));
                        tsWard.refreshOptions(false);
                        
                        if (targetWardVal) {
                            tsWard.setValue(targetWardVal, true);
                        }
                    }
                }
            }
        } catch(e) {
            console.error('Lỗi định vị ngược', e);
        } finally {
            isReverseGeocoding = false;
        }
    }

    async function handleMapInteraction(lat, lng, isDrag = false) {
        marker.setLatLng([lat, lng]);
        currentCustomerCoords = { lat: lat, lon: lng };
        document.getElementById('input_delivery_lat').value = lat;
        document.getElementById('input_delivery_lon').value = lng;
        
        // Reverse geocode
        await reverseGeocode(lat, lng);
        
        await calculateShippingWithCoords();
    }

    function initMap(lat, lon, autoFitBoundary = false) {
        document.getElementById('map_container').style.display = 'block';
        if (!map) {
            map = L.map('map').setView([lat, lon], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            marker = L.marker([lat, lon], {draggable: true}).addTo(map);

            marker.on('dragend', function (e) {
                const position = marker.getLatLng();
                handleMapInteraction(position.lat, position.lng, true);
            });
            
            map.on('click', function(e) {
                handleMapInteraction(e.latlng.lat, e.latlng.lng);
            });
        } else {
            if (!autoFitBoundary) {
                map.setView([lat, lon], 15);
            }
            marker.setLatLng([lat, lon]);
        }
        
        // Draw Boundary
        if (currentBoundaryGeoJSON) {
            if (polygonLayer) {
                map.removeLayer(polygonLayer);
            }
            polygonLayer = L.geoJSON(currentBoundaryGeoJSON, {
                style: {
                    color: '#006e1c',
                    weight: 2,
                    opacity: 0.6,
                    fillOpacity: 0.1
                }
            }).addTo(map);
            
            if (autoFitBoundary) {
                map.fitBounds(polygonLayer.getBounds());
            }
        }
        
        // Only set coords if it's valid
        if (validateMarkerPosition(lat, lon)) {
            currentCustomerCoords = { lat: lat, lon: lon };
            document.getElementById('input_delivery_lat').value = lat;
            document.getElementById('input_delivery_lon').value = lon;
        } else {
            showShippingError('Vị trí mặc định nằm ngoài xã/phường. Vui lòng chọn lại trên bản đồ.');
        }
    }

    document.getElementById('btn_find_location').addEventListener('click', async function() {
        const p = tsProvince.getValue();
        const d = tsDistrict.getValue();
        const w = tsWard.getValue();
        const specific = document.getElementById('specific_address').value;

        if (!p || !d || !w) {
            showShippingWarning('Vui lòng chọn đầy đủ Tỉnh, Huyện, Xã trước khi tìm vị trí.');
            return;
        }

        const btn = this;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = 'Đang tìm...';
        btn.disabled = true;

        const coords = await getCoordinatesWithFallback(specific, w, d, p);
        if (coords) {
            initMap(coords.lat, coords.lon);
            await calculateShippingWithCoords();
        } else {
            showShippingError('Không thể tìm thấy vị trí. Vui lòng thử lại.');
        }

        btn.innerHTML = originalHtml;
        btn.disabled = false;
    });

    document.getElementById('btn_current_location').addEventListener('click', function() {
        if (!navigator.geolocation) {
            showShippingError('Trình duyệt của bạn không hỗ trợ định vị.');
            return;
        }

        const btn = this;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span>';
        btn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            async function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                initMap(lat, lon);
                await calculateShippingWithCoords();
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            },
            function(error) {
                showShippingError('Không thể lấy vị trí. Vui lòng cho phép quyền truy cập vị trí trên trình duyệt.');
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    });

    function cleanAddress(addr) {
        if (!addr) return '';
        return addr.replace(/^(Tỉnh|Thành phố|Huyện|Quận|Thị xã|Xã|Phường|Thị trấn)\s+/i, '').trim();
    }

    async function geocode(address) {
        try {
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1&email=contact@cozyhna.com&countrycodes=vn`;
            const response = await fetch(url, { headers: { 'Accept-Language': 'vi' } });
            const data = await response.json();
            if (data && data.length > 0) {
                return { lat: data[0].lat, lon: data[0].lon, address: address };
            }
            return null;
        } catch (e) {
            console.error('Geocode error:', e);
            return null;
        }
    }

    async function getCoordinatesWithFallback(specific, ward, district, province) {
        const cWard = cleanAddress(ward);
        const cDist = cleanAddress(district);
        const cProv = cleanAddress(province);

        if (specific) {
            let coords = await geocode(`${specific}, ${cWard}, ${cDist}, ${cProv}`);
            if (coords) {
                document.getElementById('map_helper_text').innerText = "Vị trí đã được tìm thấy. Bạn có thể kéo ghim (marker) nếu chưa hoàn toàn chính xác.";
                return coords;
            }
        }
        
        let coords = await geocode(`${cWard}, ${cDist}, ${cProv}`);
        if (coords) {
            document.getElementById('map_helper_text').innerText = "Chỉ tìm được vị trí tương đối của Xã/Phường. Vui lòng KÉO GHIM đến ĐÚNG nhà bạn để tính phí chính xác.";
            return coords;
        }
        
        coords = await geocode(`${cDist}, ${cProv}`);
        return coords;
    }

    async function getDistanceOSRM(lon1, lat1, lon2, lat2) {
        try {
            const url = `https://router.project-osrm.org/route/v1/driving/${lon1},${lat1};${lon2},${lat2}?overview=false`;
            const response = await fetch(url);
            const data = await response.json();
            if (data.code === 'Ok' && data.routes.length > 0) {
                return data.routes[0].distance / 1000; // meters to km
            }
        } catch(e) {
            console.error(e);
        }
        return null;
    }

    let isCalculating = false;

    async function calculateShippingWithCoords() {
        if (isCalculating || !currentCustomerCoords) return;
        isCalculating = true;
        
        document.getElementById('display_distance').innerText = 'Đang tính toán...';
        document.getElementById('display_shipping_fee').innerText = '...';

        if (!storeCoords) {
            const storeLat = "0";
            const storeLon = "0";
            
            if (storeLat && storeLon) {
                storeCoords = { lat: parseFloat(storeLat), lon: parseFloat(storeLon) };
            } else {
                // Tọa độ CozyHNA giả định nếu admin quên set
                storeCoords = { lat: 21.0285, lon: 105.8542 }; 
            }
        }

        let distanceKm = null;
        if (storeCoords && currentCustomerCoords) {
            const distance = await getDistanceOSRM(storeCoords.lon, storeCoords.lat, currentCustomerCoords.lon, currentCustomerCoords.lat);
            if (distance !== null) {
                distanceKm = parseFloat(distance.toFixed(1));
            }
        }
        
        isCalculating = false;
        
        const baseFee = 0;
        const feePerKm = 0;
        const maxRadius = 0;

        if (distanceKm === null) {
            showShippingWarning('Không thể tính khoảng cách tự động. Phí ship tạm tính là ' + new Intl.NumberFormat('vi-VN').format(baseFee) + 'đ.');
            updateCheckoutUI(0, baseFee, 'Không xác định', new Intl.NumberFormat('vi-VN').format(baseFee) + ' đ *');
            return;
        }

        if (maxRadius > 0 && distanceKm > maxRadius) {
            showShippingError(`Khoảng cách giao hàng (${distanceKm} km) vượt quá giới hạn (${maxRadius} km).`);
            updateCheckoutUI(0, 0, distanceKm + ' km ❌', 'Ngoài vùng giao hàng');
            return;
        }

        // Tính phí ship theo bảng giá (Tiered Pricing)
        let shippingFee = null;
        
        if (shippingTiers.length > 0) {
            for (let i = 0; i < shippingTiers.length; i++) {
                if (distanceKm <= shippingTiers[i].max_km) {
                    shippingFee = shippingTiers[i].fee;
                    break;
                }
            }
            if (shippingFee === null) {
                // Vượt mốc cao nhất
                const highestTier = shippingTiers[shippingTiers.length - 1];
                const extraKm = distanceKm - highestTier.max_km;
                shippingFee = highestTier.fee + Math.round(extraKm * feePerKm);
            }
        } else {
            // Cũ (không có bảng giá)
            shippingFee = Math.max(feePerKm > 0 ? distanceKm * feePerKm : baseFee, baseFee);
        }

        shippingFee = Math.round(shippingFee / 1000) * 1000;
        
        updateCheckoutUI(distanceKm, shippingFee, distanceKm + ' km', new Intl.NumberFormat('vi-VN').format(shippingFee) + ' đ');
    }

    function updateCheckoutUI(distanceKm, fee, distText, feeText) {
        document.getElementById('input_distance_km').value = distanceKm;
        document.getElementById('input_shipping_fee').value = fee;
        document.getElementById('display_distance').innerText = distText;
        document.getElementById('display_shipping_fee').innerText = feeText;
        updateTotal(fee);
    }

    function showShippingWarning(msg) {
        showToast(msg, 'warning');
    }

    function showShippingError(msg) {
        showToast(msg, 'error');
    }

    function updateTotal(shippingFee) {
        const subtotal = 0;
        const discount = 0;
        const total = subtotal - discount + shippingFee;
        document.getElementById('display_total').innerText = new Intl.NumberFormat('vi-VN').format(total) + ' đ';
    }

    // Removed auto-find address on blur to prevent overwriting manual pins

    if (tsSaved) {
        tsSaved.on('change', function(value) {
            const el = tsSaved.getItem(value);
            const rawOption = tsSaved.options[value];
            
            if (!value || value === "") {
                document.getElementById('receiver_name').value = '';
                document.getElementById('receiver_phone').value = '';
                document.getElementById('specific_address').value = '';
                tsProvince.setValue('');
                document.getElementById('map_container').style.display = 'none';
                return;
            }
            
            // To get original DOM dataset we would need the raw HTML, but TomSelect stores dataset in $option if configured, 
            // actually we can fetch it from the original select element.
            const originalSelect = document.getElementById('saved_address_select');
            const originalOption = originalSelect.querySelector(`option[value="${value}"]`);
            
            if (originalOption) {
                document.getElementById('receiver_name').value = originalOption.dataset.name || '';
                document.getElementById('receiver_phone').value = originalOption.dataset.phone || '';
                document.getElementById('specific_address').value = originalOption.dataset.address || '';
                
                const pName = originalOption.dataset.province;
                const dName = originalOption.dataset.district;
                const wName = originalOption.dataset.ward;
                
                tsProvince.setValue(pName);
                
                // wait for district to load
                setTimeout(() => {
                    tsDistrict.setValue(dName);
                    setTimeout(() => {
                        tsWard.setValue(wName);
                        // Trigger map automatically when saved address is fully loaded
                        setTimeout(() => {
                            if (document.getElementById('specific_address').value.trim().length > 3) {
                                document.getElementById('btn_find_location').click();
                            }
                        }, 800);
                    }, 500); // Wait for ward load
                }, 500); // Wait for district load
            }
        });
    }

    document.getElementById('checkout-form').addEventListener('submit', async function(e) {
        const latInput = document.getElementById('input_delivery_lat');
        const lonInput = document.getElementById('input_delivery_lon');
        
        if (!latInput.value || !lonInput.value) {
            e.preventDefault();
            const btn = document.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Đang định vị...';
            btn.disabled = true;
            
            const p = tsProvince.getValue();
            const d = tsDistrict.getValue();
            const w = tsWard.getValue();
            const specific = document.getElementById('specific_address').value;
            
            if (!p || !d || !w || !specific) {
                showShippingError('Vui lòng điền đầy đủ địa chỉ để lấy tọa độ giao hàng.');
                btn.innerHTML = originalText;
                btn.disabled = false;
                return;
            }
            
            const coords = await getCoordinatesWithFallback(specific, w, d, p);
            if (coords) {
                latInput.value = coords.lat;
                lonInput.value = coords.lon;
                this.submit(); // Nộp form
            } else {
                showShippingError('Không thể tự động tìm thấy tọa độ địa chỉ. Vui lòng kiểm tra lại địa chỉ hoặc chọn vị trí trên bản đồ.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
    });

});
