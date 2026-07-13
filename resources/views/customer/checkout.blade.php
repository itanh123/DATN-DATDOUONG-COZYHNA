@extends('layouts.customer')

@section('title', 'Thanh Toán')

@section('content')
<main class="mt-24 pb-24 max-w-container-max mx-auto px-4 md:px-lg">
<div class="mb-xl">
<h1 class="font-headline-lg text-headline-lg text-on-background">Thanh Toán</h1>
<p class="font-body-md text-body-md text-on-surface-variant">Complete your order and we'll start brewing.</p>
</div>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-start">
<!-- Left Column: Order Details -->
<div class="lg:col-span-8 space-y-lg">
<!-- Your Cart Giâytion -->
<section class="bg-surface-container-lowest rounded-xl p-lg custom-shadow">
<div class="flex items-center justify-between mb-md">
<h2 class="font-title-lg text-title-lg flex items-center gap-xs">
<span class="material-symbols-outlined text-primary">shopping_basket</span>
                            Your Cart
                        </h2>
<span class="font-label-md text-label-md text-on-surface-variant">2 items</span>
</div>
<div class="divide-y divide-outline-variant/20">
<!-- Item 1 -->
<div class="py-md flex items-center gap-md">
<div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
<img class="w-full h-full object-cover" data-alt="A premium aesthetic close-up of a Cold Brew Mật Ong Hoa Oải Hương in a tall glass, garnished with fresh lavender sprigs. The lighting is soft and airy, typical of a high-end minimalist cafe. Background features clean white tiles and light wood textures, sticking to the premium-organic design language." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDg_qWwo2LxVlFCTF4qk7lPC-nYVAOMnKZFIkJgFrQAfNeOxdEuPjo_KZgVJq-qDf2uD19cLIrn71l47REmG8rFLRNXobZVqqjoLniixGDjc9Sj9XKD5XlzcyIMXaPGznG3C4REMaTMdU3J5hJBvOwrVk0jajgvi0y4eE_h5L1D7pE0s7pVVmhSB_UTbD3OfAanpP5bp9sH4lGJcYhOgzJu0_kxID4PMrKZKdhhuiOjhdTxuWxpfTLK-UREz3GkCnizcJYhJMop"/>
</div>
<div class="flex-grow">
<h3 class="font-body-lg text-body-lg font-semibold">Cold Brew Mật Ong Hoa Oải Hương</h3>
<p class="font-label-md text-label-md text-on-surface-variant">Large • Oat Milk • Extra Honey</p>
</div>
<div class="text-right">
<p class="font-body-lg text-body-lg font-bold text-primary">$6.50</p>
<div class="flex items-center gap-xs mt-xs">
<button class="w-6 h-6 flex items-center justify-center rounded-full border border-outline-variant hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined text-[16px]">remove</span>
</button>
<span class="font-body-md text-body-md w-4 text-center">1</span>
<button class="w-6 h-6 flex items-center justify-center rounded-full border border-outline-variant hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined text-[16px]">add</span>
</button>
</div>
</div>
</div>
<!-- Item 2 -->
<div class="py-md flex items-center gap-md">
<div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
<img class="w-full h-full object-cover" data-alt="A top-down view of a creamy Caramel Macchiato with intricate latte art and a drizzle of golden caramel. The cup sits on a minimalist marble saucer. The scene is bright and professional, emphasizing freshness and quality within a clean, modern organic coffee shop environment." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBfApsLJLjVH05bcRMqeJ18XSOPwDXdYyj-I2jhUlBTLUA6ogn-rNfTDlUpTaQJZ-nnbwunZXagTsimiZ3ghjZeoOS8GI6Hlkgj50hPDfVpSXzXdc-mtjX5PtaqN8a0KLGtQyi3NDxsPrNOgdzH6vQGPZZENZZWVNAXDFCLERBj20W21oxs5PPDrzexFPoql0hBC3_m2aCQ9unU1Y1C2hxE8tdaMs2WvBAC-3qCmhP-qJHFU43v5-I9V8MjnHRGSbGFEpdm1CE0"/>
</div>
<div class="flex-grow">
<h3 class="font-body-lg text-body-lg font-semibold">Caramel Macchiato</h3>
<p class="font-label-md text-label-md text-on-surface-variant">Regular • Caramel Drizzle</p>
</div>
<div class="text-right">
<p class="font-body-lg text-body-lg font-bold text-primary">$5.25</p>
<div class="flex items-center gap-xs mt-xs">
<button class="w-6 h-6 flex items-center justify-center rounded-full border border-outline-variant hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined text-[16px]">remove</span>
</button>
<span class="font-body-md text-body-md w-4 text-center">1</span>
<button class="w-6 h-6 flex items-center justify-center rounded-full border border-outline-variant hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined text-[16px]">add</span>
</button>
</div>
</div>
</div>
</div>
</section>
<!-- Địa Chỉ Giao Hàng -->
<section class="bg-surface-container-lowest rounded-xl p-lg custom-shadow">
<div class="flex items-center justify-between mb-md">
<h2 class="font-title-lg text-title-lg flex items-center gap-xs">
<span class="material-symbols-outlined text-primary">location_on</span>
                            Địa Chỉ Giao Hàng
                        </h2>
<button class="text-primary font-label-md text-label-md hover:underline decoration-2 underline-offset-4 transition-all" onclick="toggleModal()">Change</button>
</div>
<div class="p-md rounded-lg bg-surface-container-low border border-outline-variant/30">
<p class="font-body-lg text-body-lg font-medium">Home Office</p>
<p class="font-body-md text-body-md text-on-surface-variant">123 Organic Lane, Suite 405</p>
<p class="font-body-md text-body-md text-on-surface-variant">Palo Alto, CA 94301</p>
</div>
</section>
<!-- Delivery Method -->
<section class="bg-surface-container-lowest rounded-xl p-lg custom-shadow">
<h2 class="font-title-lg text-title-lg flex items-center gap-xs mb-md">
<span class="material-symbols-outlined text-primary">local_shipping</span>
                        Delivery Method
                    </h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-md">
<label class="cursor-pointer group">
<input checked="" class="hidden peer" name="delivery" type="radio"/>
<div class="p-md rounded-xl border border-outline-variant/30 peer-checked:border-primary peer-checked:bg-primary/5 group-hover:bg-surface-container-high transition-all">
<div class="flex items-center justify-between">
<span class="font-body-lg text-body-lg font-semibold">Standard</span>
<span class="font-body-lg text-body-lg text-primary">$3.00</span>
</div>
<p class="font-label-md text-label-md text-on-surface-variant mt-xs">Estimated: 15-25 mins</p>
</div>
</label>
<label class="cursor-pointer group">
<input class="hidden peer" name="delivery" type="radio"/>
<div class="p-md rounded-xl border border-outline-variant/30 peer-checked:border-primary peer-checked:bg-primary/5 group-hover:bg-surface-container-high transition-all">
<div class="flex items-center justify-between">
<span class="font-body-lg text-body-lg font-semibold">Express</span>
<span class="font-body-lg text-body-lg text-primary">$5.50</span>
</div>
<p class="font-label-md text-label-md text-on-surface-variant mt-xs">Estimated: 8-12 mins</p>
</div>
</label>
</div>
</section>
<!-- Phương Thức Thanh Toán -->
<section class="bg-surface-container-lowest rounded-xl p-lg custom-shadow">
<h2 class="font-title-lg text-title-lg flex items-center gap-xs mb-md">
<span class="material-symbols-outlined text-primary">payments</span>
                        Phương Thức Thanh Toán
                    </h2>
<div class="space-y-sm">
<label class="flex items-center gap-md p-md rounded-xl border border-outline-variant/30 cursor-pointer hover:bg-surface-container-high transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
<input checked="" class="w-5 h-5 text-primary border-outline focus:ring-primary" name="payment" type="radio"/>
<span class="material-symbols-outlined text-on-surface-variant">credit_card</span>
<div class="flex-grow">
<span class="font-body-lg text-body-lg font-medium">Credit Card</span>
<p class="font-label-md text-label-md text-on-surface-variant">Visa ending in 4242</p>
</div>
<span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
</label>
<label class="flex items-center gap-md p-md rounded-xl border border-outline-variant/30 cursor-pointer hover:bg-surface-container-high transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
<input class="w-5 h-5 text-primary border-outline focus:ring-primary" name="payment" type="radio"/>
<span class="material-symbols-outlined text-on-surface-variant">branding_watermark</span>
<span class="font-body-lg text-body-lg font-medium flex-grow">Apple Pay</span>
<span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
</label>
<label class="flex items-center gap-md p-md rounded-xl border border-outline-variant/30 cursor-pointer hover:bg-surface-container-high transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
<input class="w-5 h-5 text-primary border-outline focus:ring-primary" name="payment" type="radio"/>
<span class="material-symbols-outlined text-on-surface-variant">payments</span>
<span class="font-body-lg text-body-lg font-medium flex-grow">Cash on Delivery</span>
<span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
</label>
</div>
</section>
</div>
<!-- Right Column: Sticky Summary -->
<aside class="lg:col-span-4 lg:sticky lg:top-24 space-y-md">
<div class="bg-surface-container-lowest rounded-xl p-lg custom-shadow border border-outline-variant/10">
<h2 class="font-title-lg text-title-lg mb-md">Tóm Tắt Đơn Hàng</h2>
<div class="space-y-sm mb-lg">
<div class="flex justify-between font-body-md text-body-md text-on-surface-variant">
<span>Tạm tính</span>
<span>$11.75</span>
</div>
<div class="flex justify-between font-body-md text-body-md text-on-surface-variant">
<span>Delivery Fee</span>
<span>$3.00</span>
</div>
<div class="flex justify-between font-body-md text-body-md text-on-surface-variant">
<span>Thuế</span>
<span>$1.15</span>
</div>
<div class="pt-sm border-t border-outline-variant/20">
<div class="flex justify-between font-headline-md text-headline-md text-on-background">
<span>Tổng cộng Price</span>
<span>$15.90</span>
</div>
</div>
</div>
<!-- Voucher Field -->
<div class="relative mb-lg">
<input class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder:text-on-surface-variant/50" placeholder="Promo code" type="text"/>
<button class="absolute right-2 top-1.5 px-3 py-1 bg-primary text-on-primary rounded-md font-label-md text-label-md hover:bg-primary-container transition-colors">Apply</button>
</div>
<!-- Đặt Hàng CTA -->
<button class="w-full py-md bg-primary-container text-on-primary-container font-headline-md text-headline-md rounded-xl hover:shadow-lg active:scale-[0.98] transition-all flex items-center justify-center gap-sm">
                        Đặt Hàng
                        <span class="material-symbols-outlined">arrow_forward</span>
</button>
<p class="mt-md text-center font-label-md text-label-md text-on-surface-variant">
                        By placing your order, you agree to CozyHNA's <br/> <a class="underline" href="#">Terms of Service</a>
</p>
</div>
<!-- Eco-Friendly Badge -->
<div class="p-md rounded-xl bg-secondary-container/20 flex items-center gap-md border border-secondary-container/30">
<span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">eco</span>
<div>
<p class="font-label-md text-label-md font-bold text-secondary">Sustainable Giao hàng</p>
<p class="font-label-sm text-label-sm text-on-secondary-container">Your order will be delivered using 100% compostable packaging.</p>
</div>
</div>
</aside>
</div>
</main>
@endsection

@push('scripts')
<script>

        function toggleModal() {
            const modal = document.getElementById('addressModal');
            const content = modal.firstElementChild;
            if (modal.classList.contains('opacity-0')) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                content.classList.remove('translate-y-4');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('opacity-0', 'pointer-events-none');
                content.classList.add('translate-y-4');
                document.body.style.overflow = 'auto';
            }
        }

        // Add some interaction logic for the quantity buttons
        document.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const icon = btn.querySelector('.material-symbols-outlined');
                if(icon && (icon.innerText === 'add' || icon.innerText === 'remove')) {
                    const span = btn.parentElement.querySelector('span:not(.material-symbols-outlined)');
                    let count = parseInt(span.innerText);
                    if(icon.innerText === 'add') count++;
                    else if(count > 0) count--;
                    span.innerText = count;
                }
            });
        });
    
</script>
@endpush
