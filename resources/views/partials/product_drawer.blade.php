    <!-- Slide-over Overlay Mask -->
    <div class="fixed inset-0 z-[60] flex justify-end drawer-mask transition-opacity duration-300 bg-black/40" id="drawerOverlay" style="opacity: 0; display: none;" onclick="closeDrawer()">
        <!-- Product Detail Drawer -->
        <aside
            class="w-full max-w-[500px] h-full bg-surface shadow-2xl flex flex-col transform transition-transform duration-500 ease-out translate-x-full relative"
            id="productDrawer" onclick="event.stopPropagation()">
            <!-- Header Controls -->
            <div class="absolute top-md left-md right-md z-[70] flex justify-between items-center pointer-events-none">
                <button
                    class="pointer-events-auto bg-surface/90 backdrop-blur-md p-base rounded-full shadow-sm border border-outline-variant/30 text-on-surface active:scale-95 transition-transform"
                    onclick="closeDrawer()">
                    <span class="material-symbols-outlined p-2">close</span>
                </button>
                <button
                    class="pointer-events-auto bg-surface/90 backdrop-blur-md p-base rounded-full shadow-sm border border-outline-variant/30 text-error active:scale-95 transition-transform"
                    id="favoriteBtn" onclick="toggleFavorite()">
                    <span class="material-symbols-outlined p-2" id="favoriteIcon">favorite</span>
                </button>
            </div>
            <!-- Scrollable Content Area -->
            <div class="flex-1 overflow-y-auto hide-scrollbar">
                <!-- Image Carousel -->
                <section class="relative w-full aspect-square bg-surface-container-low overflow-hidden">
                    <div class="flex transition-transform duration-500 h-full" id="carouselTrack">
                        <div class="w-full shrink-0 h-full">
                            <div class="w-full h-full bg-cover bg-center"
                                data-alt="A macro close-up of a refreshing Honey Lavender Cold Brew coffee with visible condensation on the glass. The coffee is layered with creamy milk and topped with fresh lavender sprigs. The lighting is soft and natural, emphasizing the organic textures and fresh ingredients against a clean, minimalist background in the CozyHNA brand style."
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCgTKWPOi0WEjv-xakNZ4sda7u4LDGrZ5cpRWZAiZ825oJ_IS1S_YsPEMl-p89rXnb8UpWMi59GHbpjlEFRQBOyK1JLAzBpYd-aBbWSqJLhNH5rWptvZIPTz4_kbV0FeLkIDbkLKrtuMOCOOxXBlO-COwuqIevrgIrbUuXw9-htg8sUn3GhOAJqFX2U-Ak5Fj0r4WV0sY6JxVs2Jgx_cY6tS5NUwTc7HXGsDfUm3KW0pM2k3flxolRY9NgfpDM6FEmz0vrhctQQ')">
                            </div>
                        </div>
                        <div class="w-full shrink-0 h-full">
                            <div class="w-full h-full bg-cover bg-center"
                                data-alt="A top-down view of a cold brew coffee set on a minimalist wooden tray with a small jar of raw honey and a sprig of fresh lavender. The scene is bathed in bright, airy morning light. The composition is clean and modern, following the premium-organic design aesthetic with soft shadows and high-key tones."
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAq3K-xETGf6OyTtsY0uNQulhmVdqoyQFfERecTHENlHgRALa8CRhRq34oCtWA7fnYUgGjx6T_RJLqh_TNqu25zpk5jC6F-Ng2baIHsXIw1q8oqocbMPAVG97Qth3YoOCIQqAsa5hRsEcZFDfN63JZUCBEr96OASYsfqtuogway0mHwoMbdqFtWju1KvLAVQhifJydcweguVlsWIU5A2EJNpP5mPnaWalttayWQI8sl-kABbBJwVqH5FNSKf1mZQm5swnGUUC4n')">
                            </div>
                        </div>
                    </div>
                    <!-- Carousel Indicators -->
                    <div class="absolute bottom-lg left-1/2 -translate-x-1/2 flex gap-xs">
                        <div class="w-2 h-2 rounded-full bg-primary"></div>
                        <div class="w-2 h-2 rounded-full bg-outline-variant"></div>
                    </div>
                </section>
                <!-- Product Info Header -->
                <section class="px-lg pt-lg pb-md border-b border-outline-variant/10">
                    <div class="flex justify-between items-start mb-xs">
                        <h2 id="drawerProductName" class="font-headline-md text-headline-md text-on-surface">Honey Lavender Cold Brew</h2>
                        <span id="drawerProductPrice" class="font-headline-md text-headline-md text-primary">$4.20</span>
                    </div>
                    <div class="flex items-center gap-xs mb-md">
                        <span class="material-symbols-outlined text-tertiary text-sm"
                            style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="font-label-sm text-label-sm text-on-surface font-bold" id="drawerProductRating">0.0</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant" id="drawerProductReviews">(0 reviews)</span>
                    </div>
                    <p id="drawerProductDesc" class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
                        Our signature brew with floral notes and natural honey. Hand-crafted daily for a smooth,
                        refreshing experience that balances organic sweetness with bold caffeine.
                    </p>
                </section>
                <!-- Customization Options -->
                <section class="px-lg py-md space-y-xl">
                    <!-- Size Selector -->
                    <div id="drawerSizeSection" style="display: none;">
                        <label
                            class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-sm block">Size</label>
                        <div class="flex gap-sm" id="drawerSizeSelector">
                            <!-- Dynamically generated size buttons -->
                        </div>
                    </div>
                    
                  
                 
                    <!-- Special Notes -->
                    <div>
                        <label
                            class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-sm block">Special
                            Notes</label>
                        <textarea
                            class="w-full p-md rounded-2xl bg-surface-container-lowest border border-outline-variant focus:border-primary focus:ring-0 text-body-md placeholder:text-outline h-24 transition-colors"
                            placeholder="e.g., extra lavender sprig, separate honey..."></textarea>
                    </div>
                    <!-- Reviews Section -->
                    <div class="pb-xl" id="drawerReviewsSection" style="display: none;">
                        <label
                            class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-md block">Đánh giá từ khách hàng</label>
                        <div class="space-y-4" id="drawerReviewsList">
                            <!-- Dynamically generated reviews -->
                        </div>
                    </div>
                    <!-- Related Products Section -->
                    <div class="pb-xl" id="drawerRelatedSection" style="display: none;">
                        <label
                            class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-md block">You
                            May Also Like</label>
                        <div class="flex gap-md overflow-x-auto hide-scrollbar pb-xs" id="drawerRelatedProducts">
                            <!-- Dynamically generated related products -->
                        </div>
                    </div>
                </section>
            </div>
            <!-- Bottom Action Bar (Fixed) -->
            <footer
                class="p-lg bg-surface border-t border-outline-variant/10 shadow-[0_-4px_12px_rgba(0,0,0,0.03)] flex flex-col gap-md">
                <div class="flex items-center justify-between">
                    <div
                        class="flex items-center gap-md bg-surface-container rounded-full px-sm py-1 border border-outline-variant/20">
                        <button class="p-2 text-on-surface-variant active:scale-75 transition-transform"
                            onclick="decrementQty()"><span
                                class="material-symbols-outlined text-md">remove</span></button>
                        <span class="font-bold text-body-lg min-w-[20px] text-center" id="qtyVal">1</span>
                        <button class="p-2 text-on-surface-variant active:scale-75 transition-transform"
                            onclick="incrementQty()"><span class="material-symbols-outlined text-md">add</span></button>
                    </div>
                    <div class="text-right">
                        <span class="text-label-sm text-on-surface-variant block">Total Price</span>
                        <span class="font-headline-md text-headline-md text-on-surface" id="totalPrice">$4.20</span>
                    </div>
                </div>
                <button
                    onclick="addToCartFromDrawer()"
                    class="w-full py-lg bg-primary text-on-primary font-bold rounded-2xl shadow-md hover:shadow-lg active:scale-95 transition-all flex justify-center items-center gap-md">
                    <span>Add to Cart</span>
                    <span class="material-symbols-outlined">shopping_bag</span>
                </button>
            </footer>
        </aside>
    </div>


@push('scripts')
<script>
        window.allStoreProducts = {!! isset($products) ? $products->toJson() : '[]' !!};
        let quantity = 1;
        let unitPrice = 0;

        function updateTotals() {
            document.getElementById('qtyVal').innerText = quantity;
            // Format total to Vietnamese Dong format if it's a large number, else keep default
            let total = quantity * unitPrice;
            let formattedTotal = total > 100 ? new Intl.NumberFormat('vi-VN').format(total) + ' đ' : '$' + total.toFixed(2);
            document.getElementById('totalPrice').innerText = formattedTotal;
        }

        function incrementQty() {
            quantity++;
            updateTotals();
        }

        function decrementQty() {
            if (quantity > 1) {
                quantity--;
                updateTotals();
            }
        }

        function toggleFavorite() {
            const icon = document.getElementById('favoriteIcon');
            const isFilled = icon.style.fontVariationSettings.includes("'FILL' 1");
            icon.style.fontVariationSettings = isFilled ? "'FILL' 0" : "'FILL' 1";
            
            const btn = document.getElementById('favoriteBtn');
            if (!isFilled) {
                btn.classList.add('bg-error-container');
                btn.classList.remove('bg-surface/90');
            } else {
                btn.classList.remove('bg-error-container');
                btn.classList.add('bg-surface/90');
            }
        }

        let currentProductForCart = null;
        let currentSizeForCart = null;

        async function addToCartFromDrawer() {
            if (!currentProductForCart) return;

            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: currentProductForCart.id,
                        size_id: currentSizeForCart ? currentSizeForCart.id : null,
                        quantity: quantity
                    })
                });
                
                const data = await response.json();
                if (data.success) {
                    window.serverCartCount = data.cartCount;
                    if (typeof updateCartBadge === 'function') {
                        updateCartBadge();
                    }

                    // alert('Đã thêm ' + currentProductForCart.name + ' vào giỏ hàng!');
                    closeDrawer();
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                    if (data.message === 'Vui lòng đăng nhập') {
                        window.location.href = '/login';
                    }
                }
            } catch (err) {
                console.error(err);
                alert('Lỗi kết nối mạng khi thêm vào giỏ hàng');
            }
        }

        function openDrawer(element = null) {
            const drawer = document.getElementById('productDrawer');
            const overlay = document.getElementById('drawerOverlay');
            
            if (element && element.dataset.product) {
                try {
                    const product = JSON.parse(element.dataset.product);
                    document.getElementById('drawerProductName').innerText = product.name || 'Sản phẩm';
                    
                    const sizeSection = document.getElementById('drawerSizeSection');
                    const sizeSelector = document.getElementById('drawerSizeSelector');
                    
                    if (sizeSelector) sizeSelector.innerHTML = '';
                    
                    currentProductForCart = product;
                    currentSizeForCart = null;

                    if (product.product_sizes && product.product_sizes.length > 0) {
                        if (sizeSection) sizeSection.style.display = 'block';
                        
                        let defaultPs = product.product_sizes.find(ps => ps.is_default) || product.product_sizes[0];
                        unitPrice = parseFloat(defaultPs.selling_price) || 0;
                        currentSizeForCart = defaultPs.size || null;
                        document.getElementById('drawerProductPrice').innerText = new Intl.NumberFormat('vi-VN').format(unitPrice) + ' đ';

                        product.product_sizes.forEach(ps => {
                            if (!ps.status) return; // Skip inactive sizes
                            
                            const btn = document.createElement('button');
                            const isSelected = (ps.id === defaultPs.id);
                            
                            btn.className = isSelected 
                                ? 'flex-1 py-md rounded-2xl border-2 border-primary bg-primary-container text-on-primary-container font-bold text-label-md active:scale-95 transition-all'
                                : 'flex-1 py-md rounded-2xl border border-outline-variant text-on-surface-variant hover:border-primary active:scale-95 transition-all';
                            
                            // Safe fallback in case size object is missing
                            btn.innerText = ps.size ? ps.size.name : 'Size';
                            
                            btn.onclick = () => {
                                Array.from(sizeSelector.children).forEach(b => {
                                    b.className = 'flex-1 py-md rounded-2xl border border-outline-variant text-on-surface-variant hover:border-primary active:scale-95 transition-all';
                                });
                                btn.className = 'flex-1 py-md rounded-2xl border-2 border-primary bg-primary-container text-on-primary-container font-bold text-label-md active:scale-95 transition-all';
                                
                                unitPrice = parseFloat(ps.selling_price) || 0;
                                currentSizeForCart = ps.size || null;
                                document.getElementById('drawerProductPrice').innerText = new Intl.NumberFormat('vi-VN').format(unitPrice) + ' đ';
                                updateTotals();
                            };
                            
                            if (sizeSelector) sizeSelector.appendChild(btn);
                        });
                    } else {
                        if (sizeSection) sizeSection.style.display = 'none';
                        unitPrice = parseFloat(product.price) || 0;
                        document.getElementById('drawerProductPrice').innerText = new Intl.NumberFormat('vi-VN').format(unitPrice) + ' đ';
                    }

                    if (product.description) {
                        const descEl = document.getElementById('drawerProductDesc');
                        if (descEl) descEl.innerHTML = product.description;
                    }

                    // Update rating
                    document.getElementById('drawerProductRating').innerText = product.average_rating ? parseFloat(product.average_rating).toFixed(1) : '0.0';
                    document.getElementById('drawerProductReviews').innerText = `(${product.review_count || 0} reviews)`;
                    
                    if (product.image) {
                        const track = document.getElementById('carouselTrack');
                        if (track) {
                            const images = track.querySelectorAll('.bg-cover');
                            images.forEach(img => {
                                img.style.backgroundImage = `url('${product.image}')`;
                            });
                        }
                    }
                    
                    // Related Products
                    const relatedContainer = document.getElementById('drawerRelatedProducts');
                    if (relatedContainer) {
                        relatedContainer.innerHTML = '';
                        const categoryId = product.category_id;
                        const relatedProducts = (window.allStoreProducts || []).filter(p => p.category_id === categoryId && p.id !== product.id).slice(0, 4);

                        if (relatedProducts.length > 0) {
                            document.getElementById('drawerRelatedSection').style.display = 'block';
                            relatedProducts.forEach(rp => {
                                let defaultPs = rp.product_sizes && rp.product_sizes.length > 0 
                                    ? (rp.product_sizes.find(ps => ps.is_default) || rp.product_sizes[0]) 
                                    : null;
                                let displayPrice = defaultPs ? parseFloat(defaultPs.selling_price) : 0;
                                let priceStr = new Intl.NumberFormat('vi-VN').format(displayPrice) + ' đ';

                                const div = document.createElement('div');
                                div.className = 'min-w-[140px] group cursor-pointer';
                                div.onclick = () => {
                                    const mockEl = document.createElement('div');
                                    mockEl.dataset.product = JSON.stringify(rp);
                                    openDrawer(mockEl);
                                    
                                    // Scroll drawer back to top for better UX
                                    const drawerContainer = document.getElementById('productDrawer');
                                    if (drawerContainer) {
                                        const scrollable = drawerContainer.querySelector('.overflow-y-auto');
                                        if (scrollable) scrollable.scrollTop = 0;
                                    }
                                };

                                div.innerHTML = `
                                    <div class="aspect-square rounded-2xl bg-surface-container overflow-hidden mb-xs relative">
                                        <div class="w-full h-full bg-cover bg-center group-hover:scale-105 transition-transform duration-500"
                                            style="background-image: url('${rp.image || ''}')">
                                        </div>
                                    </div>
                                    <p class="text-label-md font-bold text-on-surface truncate">${rp.name}</p>
                                    <p class="text-label-sm text-primary">${priceStr}</p>
                                `;
                                relatedContainer.appendChild(div);
                            });
                        } else {
                            document.getElementById('drawerRelatedSection').style.display = 'none';
                        }
                    }

                    // Render Reviews
                    try {
                        const reviewsContainer = document.getElementById('drawerReviewsList');
                        const reviewsSection = document.getElementById('drawerReviewsSection');
                        if (reviewsContainer && reviewsSection) {
                            reviewsContainer.innerHTML = '';
                            let approvedReviews = (product.reviews || []).filter(r => r.status === 'approved');
                            
                            reviewsSection.style.display = 'block';
                            
                            if (approvedReviews.length > 0) {
                                approvedReviews.forEach(review => {
                                    let starsHtml = '';
                                    for (let i = 1; i <= 5; i++) {
                                        if (i <= review.rating) {
                                            starsHtml += `<span class="material-symbols-outlined text-[#FFD700] text-sm" style="font-variation-settings: 'FILL' 1;">star</span>`;
                                        } else {
                                            starsHtml += `<span class="material-symbols-outlined text-outline-variant text-sm" style="font-variation-settings: 'FILL' 1;">star</span>`;
                                        }
                                    }
                                    
                                    const dateStr = new Date(review.created_at).toLocaleDateString('vi-VN');
                                    let userName = review.user ? (review.user.name || review.user.username || 'Khách hàng') : 'Khách hàng';
                                    userName = String(userName); // Đảm bảo luôn là chuỗi
                                    
                                    if (userName.length > 3) {
                                        userName = userName.substring(0, userName.length - 3) + '***';
                                    } else {
                                        userName = userName.substring(0, 1) + '***';
                                    }

                                    const reviewDiv = document.createElement('div');
                                    reviewDiv.className = 'bg-surface-container-lowest p-md rounded-xl border border-outline-variant/30';
                                    reviewDiv.innerHTML = `
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="font-bold text-label-md">${userName}</p>
                                                <div class="flex">${starsHtml}</div>
                                            </div>
                                            <span class="text-label-sm text-on-surface-variant">${dateStr}</span>
                                        </div>
                                        <p class="text-body-md text-on-surface mt-2">${review.comment || ''}</p>
                                    `;
                                    reviewsContainer.appendChild(reviewDiv);
                                });
                            } else {
                                reviewsContainer.innerHTML = '<p class="text-body-md text-on-surface-variant italic">Chưa có đánh giá nào.</p>';
                            }
                        }
                    } catch (reviewError) {
                        console.error('Error rendering reviews:', reviewError);
                    }

                    quantity = 1;
                    updateTotals();
                } catch(e) {
                    console.error('Error parsing product data', e);
                    document.getElementById('drawerProductName').innerText = 'Error: ' + e.message;
                }
            }

            if (overlay) overlay.style.display = 'flex';
            // Force a reflow
            if (overlay) void overlay.offsetWidth;
            if (overlay) overlay.style.opacity = '1';
            if (drawer) drawer.style.transform = 'translateX(0)';
        }

        function closeDrawer() {
            const drawer = document.getElementById('productDrawer');
            const overlay = document.getElementById('drawerOverlay');
            drawer.style.transform = 'translateX(100%)';
            overlay.style.opacity = '0';
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 500);
        }

        // Simple carousel logic
        let currentSlide = 0;
        const track = document.getElementById('carouselTrack');
        const indicators = document.querySelectorAll('.rounded-full.w-2.h-2');
        
        setInterval(() => {
            currentSlide = (currentSlide + 1) % 2;
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
            indicators.forEach((ind, idx) => {
                ind.classList.toggle('bg-primary', idx === currentSlide);
                ind.classList.toggle('bg-outline-variant', idx !== currentSlide);
            });
        }, 5000);
    
</script>
@endpush

