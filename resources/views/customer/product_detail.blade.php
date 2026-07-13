@extends('layouts.customer')

@section('title', 'Product Detail')

@section('content')
<main class="max-w-container-max mx-auto px-md py-lg">
<header class="flex justify-between items-center mb-xl">
<h1 class="font-headline-lg text-headline-lg text-primary">CozyHNA</h1>
<div class="flex gap-md">
<span class="material-symbols-outlined text-on-surface-variant">search</span>
<span class="material-symbols-outlined text-on-surface-variant">shopping_cart</span>
</div>
</header>
<div class="grid grid-cols-2 md:grid-cols-4 gap-md">
<div class="aspect-square bg-surface-container rounded-2xl cursor-pointer hover:shadow-md transition-shadow" onclick="openDrawer()"></div>
<div class="aspect-square bg-surface-container rounded-2xl cursor-pointer hover:shadow-md transition-shadow" onclick="openDrawer()"></div>
<div class="aspect-square bg-surface-container rounded-2xl cursor-pointer hover:shadow-md transition-shadow" onclick="openDrawer()"></div>
<div class="aspect-square bg-surface-container rounded-2xl cursor-pointer hover:shadow-md transition-shadow" onclick="openDrawer()"></div>
</div>
</main>
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
                        <h2 class="font-headline-md text-headline-md text-on-surface">Honey Lavender Cold Brew</h2>
                        <span class="font-headline-md text-headline-md text-primary">$4.20</span>
                    </div>
                    <div class="flex items-center gap-xs mb-md">
                        <span class="material-symbols-outlined text-tertiary text-sm"
                            style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="font-label-sm text-label-sm text-on-surface font-bold">4.9</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">(1.2k reviews)</span>
                    </div>
                    <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
                        Our signature brew with floral notes and natural honey. Hand-crafted daily for a smooth,
                        refreshing experience that balances organic sweetness with bold caffeine.
                    </p>
                </section>
                <!-- Customization Options -->
                <section class="px-lg py-md space-y-xl">
                    <!-- Size Selector -->
                    <div>
                        <label
                            class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-sm block">Size</label>
                        <div class="flex gap-sm">
                            <button
                                class="flex-1 py-md rounded-2xl border-2 border-primary bg-primary-container text-on-primary-container font-bold text-label-md active:scale-95 transition-all">Small</button>
                            <button
                                class="flex-1 py-md rounded-2xl border border-outline-variant text-on-surface-variant hover:border-primary active:scale-95 transition-all">Medium</button>
                            <button
                                class="flex-1 py-md rounded-2xl border border-outline-variant text-on-surface-variant hover:border-primary active:scale-95 transition-all">Large</button>
                        </div>
                    </div>
                    
                    
                    <!-- Expandable Info Sections -->
                    <div class="space-y-xs">
                        <details class="group">
                            <summary
                                class="flex justify-between items-center py-md cursor-pointer list-none border-b border-outline-variant/10">
                                <span
                                    class="font-label-md text-label-md text-on-surface font-semibold">Ingredients</span>
                                <span
                                    class="material-symbols-outlined transition-transform group-open:rotate-180">expand_more</span>
                            </summary>
                            <div class="py-md text-body-md text-on-surface-variant leading-relaxed px-base">
                                Organic cold brew coffee beans, pure spring water, lavender blossom extract, raw
                                wildflower honey, a splash of oat milk.
                            </div>
                        </details>
                        <details class="group">
                            <summary
                                class="flex justify-between items-center py-md cursor-pointer list-none border-b border-outline-variant/10">
                                <span class="font-label-md text-label-md text-on-surface font-semibold">Nutrition
                                    Facts</span>
                                <span
                                    class="material-symbols-outlined transition-transform group-open:rotate-180">expand_more</span>
                            </summary>
                            <div class="py-md text-body-md text-on-surface-variant px-base">
                                <div class="flex justify-between mb-xs"><span>Calories</span><span>120 kcal</span></div>
                                <div class="flex justify-between mb-xs"><span>Total Sugar</span><span>14g</span></div>
                                <div class="flex justify-between"><span>Caffeine</span><span>180mg</span></div>
                            </div>
                        </details>
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
                    <!-- Related Products Section -->
                    <div class="pb-xl">
                        <label
                            class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-md block">You
                            May Also Like</label>
                        <div class="flex gap-md overflow-x-auto hide-scrollbar pb-xs">
                            <div class="min-w-[140px] group cursor-pointer">
                                <div
                                    class="aspect-square rounded-2xl bg-surface-container overflow-hidden mb-xs relative">
                                    <div class="w-full h-full bg-cover bg-center group-hover:scale-105 transition-transform duration-500"
                                        data-alt="A glass of Matcha Latte with a beautiful swirl of green and white, set against a bright, airy cafe background. The design is modern and clean with a focus on fresh organic ingredients."
                                        style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCLEH1KcISZu2xppB1WP_8so5ZEL9HehNCi9tuAFQJpITDBX7I0Q4IbYpSDtIDBun7uRAB73H5TjaSnco9hrc7LVW75MkTBMkkPk3UmAnNueyGmon9w6g5qkDKyyG8IFQnBj8DNAL3SoBR4igkcGzDClu5fIO2aQfnkbTdddq4kbe8pn1WVssXe6PFSZeQFsvgvU7OTZ-pttiAjbx1cdQAtTHXbWMUDuWHrYYDjsEkhKIfKN2zAXhK9ljwYDOLeni860MdN_erX')">
                                    </div>
                                </div>
                                <p class="text-label-md font-bold text-on-surface truncate">Matcha Latte</p>
                                <p class="text-label-sm text-primary">$5.50</p>
                            </div>
                            <div class="min-w-[140px] group cursor-pointer">
                                <div
                                    class="aspect-square rounded-2xl bg-surface-container overflow-hidden mb-xs relative">
                                    <div class="w-full h-full bg-cover bg-center group-hover:scale-105 transition-transform duration-500"
                                        data-alt="A delicious looking Cinnamon Swirl latte with steam rising from the top, decorated with a sprinkle of spice. Minimalist and high-end photographic style."
                                        style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCkVljJ2rohy2SC6VjitJmpOYPd1ZXc-cfX1UOg0NJBUJ2pI6iJ3isNE7ZvQPZH8QIQq01H9KAkSpdnil8WuHxK4EVjkoArlTPAZf2ndD3x_JgadJlHYWT1GWJjdsCT0L06GxiMwX_pIuD1lG1xWZIVK_tvsncCeMzr0p_3a1QkPs3T2anmh44lqDRa4EHy1MfNOe4zI4AH0DlRPcgEDCw8ZK1skO8LMCznos3owr_nJ8DRU5tX6sCGgAsmM4hqsn45Vzl2-aV-')">
                                    </div>
                                </div>
                                <p class="text-label-md font-bold text-on-surface truncate">Cinnamon Swirl</p>
                                <p class="text-label-sm text-primary">$4.80</p>
                            </div>
                            <div class="min-w-[140px] group cursor-pointer">
                                <div
                                    class="aspect-square rounded-2xl bg-surface-container overflow-hidden mb-xs relative">
                                    <div class="w-full h-full bg-cover bg-center group-hover:scale-105 transition-transform duration-500"
                                        data-alt="Refreshing Rose Petal Hibiscus iced tea in a tall glass with floating petals, bright vibrant pink color."
                                        style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuD0G_2FJ_ZJj7MH8dQRKX0wsoiEontRx7TCDMVnzCXjRUSq6IQxi_rDSkad0C9A7f3VlAsDivzAXuhu74WIlAuHDbTti8vaePW9vMKNIiIuoPHzcIXe4-6Efglton9Mu7yVgwgR8tFUGdVhb8OSl1SO1RClPgoMWOSKQE6qdeC0I2sfe_X-eevOs2R6_ANx5Ij1O5ZWtA_O4Srv2czdf65L2Mr0j1YNKcXv1nHs7E-9pH8zYkLkbtAGwcvQ8ZsL8nbQyddifUUx')">
                                    </div>
                                </div>
                                <p class="text-label-md font-bold text-on-surface truncate">Hibiscus Rose</p>
                                <p class="text-label-sm text-primary">$4.50</p>
                            </div>
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
                    class="w-full py-lg bg-primary text-on-primary font-bold rounded-2xl shadow-md hover:shadow-lg active:scale-95 transition-all flex justify-center items-center gap-md">
                    <span>Add to Cart</span>
                    <span class="material-symbols-outlined">shopping_bag</span>
                </button>
            </footer>
        </aside>
    </div>
@endsection

@push('scripts')
<script>

        let quantity = 1;
        const unitPrice = 4.20;

        function updateTotals() {
            document.getElementById('qtyVal').innerText = quantity;
            document.getElementById('totalPrice').innerText = '$' + (quantity * unitPrice).toFixed(2);
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

        function openDrawer() {
            const drawer = document.getElementById('productDrawer');
            const overlay = document.getElementById('drawerOverlay');
            overlay.style.display = 'flex';
            // Force a reflow
            void overlay.offsetWidth;
            overlay.style.opacity = '1';
            drawer.style.transform = 'translateX(0)';
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

