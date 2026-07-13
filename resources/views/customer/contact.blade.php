@extends('layouts.customer')

@section('title', 'Contact')

@section('content')
<main class="pt-24 pb-2xl">
<!-- Header Giâytion -->
<section class="max-w-container-max mx-auto px-lg mb-2xl text-center">
<h1 class="font-display-lg text-display-lg mb-md text-primary">Get in Touch</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mx-auto">
                Have a question about our organic beverages or need assistance with your order? Our team is here to help you experience vitality in every sip.
            </p>
</section>
<!-- Main Content Grid -->
<section class="max-w-container-max mx-auto px-lg grid grid-cols-1 lg:grid-cols-12 gap-lg items-start">
<!-- Contact Info Cards (Left Column) -->
<div class="lg:col-span-4 space-y-md">
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow">
<div class="flex items-center gap-md mb-sm">
<div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center">
<span class="material-symbols-outlined text-on-secondary-container" data-icon="call">call</span>
</div>
<h3 class="font-title-lg text-title-lg">Số điện thoại</h3>
</div>
<p class="text-on-surface-variant">+1 (800) 123-4567</p>
<p class="text-label-md text-label-md text-primary mt-xs uppercase">Mon - Fri, 9am - 6pm EST</p>
</div>
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow">
<div class="flex items-center gap-md mb-sm">
<div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center">
<span class="material-symbols-outlined text-on-secondary-container" data-icon="mail">mail</span>
</div>
<h3 class="font-title-lg text-title-lg">Email</h3>
</div>
<p class="text-on-surface-variant">hello@cozyhna.com</p>
<p class="text-label-md text-label-md text-primary mt-xs uppercase">Response within 24 hours</p>
</div>
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow">
<div class="flex items-center gap-md mb-sm">
<div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center">
<span class="material-symbols-outlined text-on-secondary-container" data-icon="location_on">location_on</span>
</div>
<h3 class="font-title-lg text-title-lg">Headquarters</h3>
</div>
<p class="text-on-surface-variant">742 Vitality Lane, Green District</p>
<p class="text-on-surface-variant">Portland, OR 97201</p>
</div>
<!-- Quick FAQ Link -->
<div class="bg-primary-container/10 p-lg rounded-xl border border-primary/20 mt-lg">
<h4 class="font-title-lg text-title-lg text-primary mb-xs">Need a quick answer?</h4>
<p class="text-body-md text-body-md mb-md">Check our Frequently Asked Questions for instant help.</p>
<a class="inline-flex items-center gap-xs font-semibold text-primary hover:underline" href="#">
                        Visit FAQ Center
                        <span class="material-symbols-outlined text-sm" data-icon="arrow_forward">arrow_forward</span>
</a>
</div>
</div>
<!-- Contact Form (Right Column) -->
<div class="lg:col-span-8">
<div class="bg-surface-container-lowest p-xl rounded-xl border border-outline-variant/30 shadow-sm">
<h2 class="font-headline-md text-headline-md mb-xl">Send us a Message</h2>
<form class="grid grid-cols-1 md:grid-cols-2 gap-lg" id="contactForm">
<div class="space-y-xs">
<label class="font-label-md text-label-md text-on-surface-variant uppercase ml-base" for="name">Full Name</label>
<input class="w-full px-md py-sm rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary bg-white transition-all outline-none" id="name" name="name" placeholder="John Doe" required="" type="text"/>
</div>
<div class="space-y-xs">
<label class="font-label-md text-label-md text-on-surface-variant uppercase ml-base" for="email">Email Address</label>
<input class="w-full px-md py-sm rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary bg-white transition-all outline-none" id="email" name="email" placeholder="john@example.com" required="" type="email"/>
</div>
<div class="md:col-span-2 space-y-xs">
<label class="font-label-md text-label-md text-on-surface-variant uppercase ml-base" for="subject">Subject</label>
<select class="w-full px-md py-sm rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary bg-white transition-all outline-none" id="subject" name="subject" required="">
<option value="">Select a reason</option>
<option value="order">Order Trạng thái</option>
<option value="product">Product Inquiry</option>
<option value="partnership">Partnership</option>
<option value="other">Other</option>
</select>
</div>
<div class="md:col-span-2 space-y-xs">
<label class="font-label-md text-label-md text-on-surface-variant uppercase ml-base" for="message">Message</label>
<textarea class="w-full px-md py-sm rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary bg-white transition-all outline-none resize-none" id="message" name="message" placeholder="How can we help you today?" required="" rows="5"></textarea>
</div>
<div class="md:col-span-2 flex justify-end pt-md">
<button class="bg-primary-container text-on-primary-container font-headline-md text-headline-md px-xl py-md rounded-full shadow-md hover:shadow-lg active:scale-95 transition-all flex items-center gap-md" type="submit">
                                Send Message
                                <span class="material-symbols-outlined" data-icon="send">send</span>
</button>
</div>
</form>
</div>
</div>
</section>
<!-- Store Locations Giâytion -->
<section class="max-w-container-max mx-auto px-lg mt-2xl">
<div class="flex flex-col md:flex-row justify-between items-end mb-xl gap-md">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-surface">Store Locations</h2>
<p class="text-on-surface-variant">Find a CozyHNA experience near you.</p>
</div>
<button class="px-lg py-sm border-2 border-primary text-primary font-semibold rounded-full hover:bg-primary/5 transition-colors">
                    Xem Tất Cả 24 Locations
                </button>
</div>
<!-- Asymmetric Bento Grid for Stores -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-lg h-auto md:h-[500px]">
<!-- Large Map Column -->
<div class="md:col-span-2 relative rounded-xl overflow-hidden shadow-sm group">
<div class="absolute inset-0 bg-gray-200" data-location="Portland, Oregon" style="">
<img class="w-full h-full object-cover grayscale-0 group-hover:scale-105 transition-transform duration-700" data-alt="A clean, minimalist custom vector map of Portland, Oregon, showing major streets and landmarks in a soft color palette of mint green, pale blue, and warm white. Interactive location pins with the CozyHNA logo are placed strategically. The style is modern, airy, and high-end, fitting a premium beverage brand's digital aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBi-dROciW1kpE9AFOoNOpTm9s5Ra3HlYUrcWPUUNkA2VZqQkaxgNMtlBCgHtmtbthc163PekHC9NRU_WDlEg5beE4UjGcqK1bTxIOnbNhmEVtLms9cRtJD3hCkN90ezCh31F3lXuHou-ANARtKmLcly_utg5vglA7XaKldsP7mOojfwWHgGIaYIZeI1IxVbWiUdffUhNrz9umVXstVIzOSRCVtXxbftI7jx4bu16V1lOwNeeMebDBgrJYhUhj4LEkq00FFP27N"/>
</div>
<div class="absolute bottom-lg left-lg glass-card p-md rounded-lg shadow-xl max-w-xs">
<h4 class="font-title-lg text-title-lg text-primary">Portland Flagship</h4>
<p class="text-sm mb-md">742 Vitality Lane, OR</p>
<div class="flex items-center gap-xs text-secondary font-semibold">
<span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                            Open until 8:00 PM
                        </div>
</div>
</div>
<!-- Featured Store Cards -->
<div class="flex flex-col gap-lg">
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant/30 flex-1 flex flex-col justify-between group cursor-pointer hover:border-primary/50 transition-all">
<div>
<span class="text-label-sm text-label-sm bg-secondary-container text-on-secondary-container px-sm py-1 rounded-full uppercase tracking-wider">Seattle Central</span>
<h4 class="font-title-lg text-title-lg mt-sm">Pike Place Market</h4>
<p class="text-body-md text-body-md text-on-surface-variant mt-xs">A bustling hub for our fresh organic cold brews.</p>
</div>
<div class="flex justify-between items-center mt-md">
<span class="text-label-md text-label-md text-on-surface-variant">1.2 miles away</span>
<span class="material-symbols-outlined text-primary group-hover:translate-x-1 transition-transform" data-icon="directions">directions</span>
</div>
</div>
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant/30 flex-1 flex flex-col justify-between group cursor-pointer hover:border-primary/50 transition-all">
<div>
<span class="text-label-sm text-label-sm bg-secondary-container text-on-secondary-container px-sm py-1 rounded-full uppercase tracking-wider">San Francisco</span>
<h4 class="font-title-lg text-title-lg mt-sm">Mission District</h4>
<p class="text-body-md text-body-md text-on-surface-variant mt-xs">Experience our limited edition botanical infusions.</p>
</div>
<div class="flex justify-between items-center mt-md">
<span class="text-label-md text-label-md text-on-surface-variant">Open Now</span>
<span class="material-symbols-outlined text-primary group-hover:translate-x-1 transition-transform" data-icon="directions">directions</span>
</div>
</div>
</div>
</div>
</section>
</main>
@endsection

@push('scripts')
<script>

        // Micro-interactions for form
        const form = document.getElementById('contactForm');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin" data-icon="progress_activity">progress_activity</span> Sending...';
            btn.classList.add('opacity-80', 'pointer-events-none');
            
            setTimeout(() => {
                btn.innerHTML = '<span class="material-symbols-outlined" data-icon="check_circle">check_circle</span> Sent!';
                btn.classList.replace('bg-primary-container', 'bg-secondary-container');
                form.reset();
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.replace('bg-secondary-container', 'bg-primary-container');
                    btn.classList.remove('opacity-80', 'pointer-events-none');
                }, 3000);
            }, 1500);
        });

        // Simple scroll reveal effect
        window.addEventListener('scroll', () => {
            const cards = document.querySelectorAll('.bg-surface-container-lowest');
            cards.forEach(card => {
                const rect = card.getBoundingClientRect();
                if (rect.top < window.innerHeight - 50) {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }
            });
        });
    
</script>
@endpush
