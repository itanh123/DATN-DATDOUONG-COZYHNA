@extends('layouts.customer')

@section('title', 'Product Detail')

@section('content')
<main class="max-w-container-max mx-auto px-md py-lg opacity-40 select-none">
<header class="flex justify-between items-center mb-xl">
<h1 class="font-headline-lg text-headline-lg text-primary">CozyHNA</h1>
<div class="flex gap-md">
<span class="material-symbols-outlined text-on-surface-variant">search</span>
<span class="material-symbols-outlined text-on-surface-variant">shopping_cart</span>
</div>
</header>
<div class="grid grid-cols-2 md:grid-cols-4 gap-md">
<div class="aspect-square bg-surface-container rounded-2xl"></div>
<div class="aspect-square bg-surface-container rounded-2xl"></div>
<div class="aspect-square bg-surface-container rounded-2xl"></div>
<div class="aspect-square bg-surface-container rounded-2xl"></div>
</div>
</main>
@endsection

@push('scripts')
<script>

        let quantity = 1;
        const unitPrice = 4.20;

        function updateTổng cộngs() {
            document.getElementById('qtyVal').innerText = quantity;
            document.getElementById('totalPrice').innerText = '$' + (quantity * unitPrice).toFixed(2);
        }

        function incrementQty() {
            quantity++;
            updateTổng cộngs();
        }

        function decrementQty() {
            if (quantity > 1) {
                quantity--;
                updateTổng cộngs();
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
