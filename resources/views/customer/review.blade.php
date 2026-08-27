@extends('layouts.customer')

@section('title', 'Đánh giá Đơn hàng')

@section('content')
<main class="mt-24 pb-24 max-w-container-max mx-auto px-4 md:px-lg font-sans">
    <div class="mb-xl text-center md:text-left">
        <h1 class="font-headline-lg text-headline-lg text-on-background font-bold">Đánh giá sản phẩm</h1>
        <p class="font-body-md text-body-md text-on-surface-variant mt-2">Đơn hàng: <span class="font-bold text-primary">{{ $order->code }}</span></p>
    </div>

    @if(session('error'))
        <div class="mb-md p-md bg-red-100 text-red-800 rounded-2xl font-body-md flex items-center gap-sm">
            <span class="material-symbols-outlined text-red-600">error</span>
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('orders.submitReview', $order->id) }}" method="POST" class="space-y-lg">
        @csrf

        @foreach($order->items as $index => $item)
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/15 shadow-sm p-lg">
            <div class="flex items-start gap-md mb-md pb-md border-b border-outline-variant/10">
                <div class="w-16 h-16 rounded-xl bg-surface-container overflow-hidden flex-shrink-0">
                    <div class="w-full h-full flex items-center justify-center bg-amber-50 text-amber-700">
                        <span class="material-symbols-outlined">local_cafe</span>
                    </div>
                </div>
                <div>
                    <h3 class="font-title-lg text-title-lg font-bold">{{ $item->product_name }}</h3>
                    <p class="text-sm text-on-surface-variant">Size: {{ $item->size_name ?? 'Mặc định' }}</p>
                </div>
            </div>

            <input type="hidden" name="reviews[{{ $index }}][product_id]" value="{{ $item->product_id }}">

            <div class="mb-md">
                <label class="block font-label-lg text-label-lg mb-2 font-bold text-on-surface">Chất lượng sản phẩm</label>
                <div class="flex items-center gap-2 star-rating-container" data-index="{{ $index }}">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="material-symbols-outlined cursor-pointer text-outline-variant hover:text-amber-400 text-3xl transition-colors star-icon" data-value="{{ $i }}">star</span>
                    @endfor
                    <span class="rating-text font-label-lg font-bold text-amber-500 ml-3">Tuyệt vời</span>
                </div>
                <input type="hidden" name="reviews[{{ $index }}][rating]" class="rating-input" value="5" required>
            </div>

            <div>
                <label class="block font-label-lg text-label-lg mb-2 font-bold text-on-surface">Nhận xét của bạn (Không bắt buộc)</label>
                <textarea name="reviews[{{ $index }}][comment]" rows="3" class="w-full border border-outline-variant rounded-xl p-3 bg-surface text-on-surface focus:outline-primary focus:ring-1 focus:ring-primary transition-all" placeholder="Hãy chia sẻ cảm nhận của bạn về món này nhé..."></textarea>
            </div>
        </div>
        @endforeach

        @if($order->shipper_id)
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/15 shadow-sm p-lg mt-4">
            <div class="flex items-center gap-md mb-md pb-md border-b border-outline-variant/10">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-3xl">two_wheeler</span>
                </div>
                <div>
                    <h3 class="font-title-lg text-title-lg font-bold">Đánh giá Shipper</h3>
                    <p class="text-sm text-on-surface-variant">Shipper đã giao đơn hàng cho bạn</p>
                </div>
            </div>

            <div class="mb-md">
                <label class="block font-label-lg text-label-lg mb-2 font-bold text-on-surface">Thái độ & Tốc độ giao hàng</label>
                <div class="flex items-center gap-2 star-rating-container">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="material-symbols-outlined cursor-pointer text-outline-variant hover:text-amber-400 text-3xl transition-colors star-icon" data-value="{{ $i }}">star</span>
                    @endfor
                    <span class="rating-text font-label-lg font-bold text-amber-500 ml-3">Tuyệt vời</span>
                </div>
                <input type="hidden" name="shipper_rating" class="rating-input" value="5">
            </div>
        </div>
        @endif

        <div class="flex justify-end gap-md">
            <a href="{{ route('customer.orders') }}" class="px-xl py-md rounded-xl font-label-md text-label-md border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors">Hủy</a>
            <button type="submit" class="px-xl py-md rounded-xl font-label-md text-label-md font-bold bg-primary text-on-primary hover:bg-primary/90 transition-colors shadow-md">Gửi Đánh Giá</button>
        </div>
    </form>
</main>

<style>
    .star-icon.active {
        color: #fbbf24 !important; /* amber-400 */
        font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24 !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const containers = document.querySelectorAll('.star-rating-container');
        
        const ratingTexts = {
            1: 'Tệ',
            2: 'Tạm được',
            3: 'Bình thường',
            4: 'Tốt',
            5: 'Tuyệt vời'
        };

        function updateStars(stars, value, textEl) {
            stars.forEach(s => {
                const sVal = parseInt(s.getAttribute('data-value'));
                if (sVal <= value) {
                    s.classList.add('active');
                    s.style.color = '#fbbf24';
                    s.style.fontVariationSettings = "'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24";
                } else {
                    s.classList.remove('active');
                    s.style.color = '';
                    s.style.fontVariationSettings = "'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24";
                }
            });
            if (textEl) {
                textEl.innerText = ratingTexts[value] || '';
            }
        }

        containers.forEach(container => {
            const stars = container.querySelectorAll('.star-icon');
            const input = container.parentElement.querySelector('.rating-input');
            const textEl = container.querySelector('.rating-text');
            
            // Set default 5 stars
            updateStars(stars, parseInt(input.value) || 5, textEl);
            
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = parseInt(this.getAttribute('data-value'));
                    input.value = value;
                    updateStars(stars, value, textEl);
                });
                
                // Hover effects
                star.addEventListener('mouseenter', function() {
                    const value = parseInt(this.getAttribute('data-value'));
                    updateStars(stars, value, textEl);
                });
                
                star.addEventListener('mouseleave', function() {
                    const currentValue = parseInt(input.value) || 5;
                    updateStars(stars, currentValue, textEl);
                });
            });
        });
    });
</script>
@endsection
