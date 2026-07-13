@extends('layouts.admin')

@section('title', 'Sản phẩm')

@section('content')
<main class="ml-[280px] h-screen flex flex-col overflow-hidden">
<!-- Top Header -->
<header class="h-16 flex items-center justify-between px-xl bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 sticky top-0 z-30">
<div class="flex items-center gap-4">
<h2 class="font-headline-md text-headline-md text-on-surface">Product Management</h2>
<div class="h-6 w-px bg-outline-variant/50"></div>
<div class="flex bg-surface-container-low p-1 rounded-lg">
<button class="tab-btn px-4 py-1.5 rounded-md font-label-md transition-all active-tab bg-white shadow-sm text-primary" data-tab="products" onclick="switchTab('products')">Sản phẩm</button>
<button class="tab-btn px-4 py-1.5 rounded-md font-label-md transition-all text-on-surface-variant hover:text-on-surface" data-tab="categories" onclick="switchTab('categories')">Categories</button>
<button class="tab-btn px-4 py-1.5 rounded-md font-label-md transition-all text-on-surface-variant hover:text-on-surface" data-tab="recipes" onclick="switchTab('recipes')">Recipes & Ingredients</button>
<button class="tab-btn px-4 py-1.5 rounded-md font-label-md transition-all text-on-surface-variant hover:text-on-surface" data-tab="sizes" onclick="switchTab('sizes')">Sizes</button>
</div>
</div>
<div class="flex items-center gap-md">
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
<input class="pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 text-body-md w-64 transition-all" placeholder="Search items..." type="text"/>
</div>
<button class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors relative">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-2 right-2 w-2 h-2 bg-primary rounded-full"></span>
</button>
<div class="flex items-center gap-2 border-l border-outline-variant/30 pl-4">
<img class="w-8 h-8 rounded-full border border-primary/20" data-alt="A professional headshot of a female administrative manager with a friendly expression, wearing a clean olive green blazer. The background is a blurred high-end cafe interior with warm lighting. The aesthetic is clean, sharp, and reflects a modern SaaS admin user profile." src="https://lh3.googleusercontent.com/aida-public/AB6AXuD2uapHi3hVEabMv9iRYSDDmILO_wpMweW4uFl0p9gWTOlqCClI5F9eiFBkDd6GNdHiWfIUa40AFDRc_OjrmS9--W-sX-q-1NqwzJkNCaPMwUtUW-FDotL0B5WQzcHB71m5ByrjzjjvpVHTRVQW3k064Zcx2O2xpkpy2LasjnNOQJalNQ1PkO4wOLU-RIRgYp2NVzdAeGY_4wUSR48S94lWPjrihXiFg5nxInvcwzV7eR274LIEQJWzE0R-avGP04PeAkJj7IBb"/>
<div class="hidden xl:block">
<p class="font-label-md text-label-md text-on-surface">Admin User</p>
<p class="text-[10px] text-on-surface-variant">Store Manager</p>
</div>
</div>
</div>
</header>
<!-- Dynamic Content Area -->
<div class="flex-1 overflow-y-auto custom-scrollbar bg-surface-container-lowest p-xl">
<!-- Sản phẩm Tab Content -->
<section class="tab-pane space-y-lg" id="products-content">
<div class="flex justify-between items-center">
<div class="flex gap-2">
<div class="flex items-center gap-2 px-3 py-2 bg-white border border-outline-variant/30 rounded-lg shadow-sm text-body-md cursor-pointer hover:bg-surface-container-low transition-colors">
<span class="material-symbols-outlined text-body-md">filter_list</span>
<span>Trạng thái: All</span>
</div>
<div class="flex items-center gap-2 px-3 py-2 bg-white border border-outline-variant/30 rounded-lg shadow-sm text-body-md cursor-pointer hover:bg-surface-container-low transition-colors">
<span class="material-symbols-outlined text-body-md">category</span>
<span>Category: All</span>
</div>
</div>
<div class="flex gap-2">
<button class="flex items-center gap-2 px-4 py-2 border border-outline-variant/30 rounded-lg text-on-surface-variant font-label-md hover:bg-surface-container-low transition-all">
<span class="material-symbols-outlined">download</span> Export
                        </button>
<button class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md shadow-sm hover:opacity-90 active:scale-95 transition-all" onclick="toggleModal('productModal')">
<span class="material-symbols-outlined">add</span> Add Product
                        </button>
</div>
</div>
<div class="bg-white rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low border-b border-outline-variant/30">
<th class="p-4 w-10"><input class="rounded text-primary focus:ring-primary border-outline" type="checkbox"/></th>
<th class="p-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Product</th>
<th class="p-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Category</th>
<th class="p-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Price</th>
<th class="p-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Kho hàng</th>
<th class="p-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Trạng thái</th>
<th class="p-4 w-10"></th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/10">
<!-- Table Row 1 -->
<tr class="hover:bg-surface-container-lowest transition-colors group">
<td class="p-4"><input class="rounded text-primary focus:ring-primary border-outline" type="checkbox"/></td>
<td class="p-4">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-lg bg-surface-container-high overflow-hidden">
<img class="w-full h-full object-cover" data-alt="Close up of a refreshing iced matcha latte with vibrant green layers, topped with creamy white foam, served in a minimalist glass cup. Soft organic lighting highlights the texture. High-end beverage aesthetic for a modern cafe menu management interface." src="https://lh3.googleusercontent.com/aida-public/AB6AXuA9MIJ0nPJifoUglWwNoYp3-XuVgF2jSp7ku6GWkPDK4Ob7mvpFbMErl_q41hHPmCpGtTIBV0i12H8pCFfbZvvF_Fx_7mAahZjeJlxojv3QRbiL4fSPKihzDMowi1-H0z2UtAefhAitkJ_RdQNjJB2DSqsrLq_JBp6pvITq_tiYzdB0rl-lGdgLeaR6SNa6JazL-KYSdz1lEhToyHtiHyyDyLGAFJ9eLtA3k0bajYPlaOXCeMTiLledrKLyCGRS-uR52R3Pql-o"/>
</div>
<div>
<p class="font-body-md text-body-md font-semibold text-on-surface">Ceremonial Matcha Latte</p>
<p class="text-label-md text-on-surface-variant">SKU: ML-001</p>
</div>
</div>
</td>
<td class="p-4"><span class="px-2 py-1 bg-secondary-container/20 text-secondary text-label-md rounded-full">Tea Lattes</span></td>
<td class="p-4 text-on-surface font-semibold">$5.50</td>
<td class="p-4 text-on-surface-variant text-body-md">In Stock (142)</td>
<td class="p-4">
<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-label-md font-bold">
<span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Hoạt động
                                    </span>
</td>
<td class="p-4 text-right opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1 hover:bg-surface-container rounded transition-colors"><span class="material-symbols-outlined text-on-surface-variant">more_vert</span></button>
</td>
</tr>
<!-- Table Row 2 -->
<tr class="hover:bg-surface-container-lowest transition-colors group">
<td class="p-4"><input class="rounded text-primary focus:ring-primary border-outline" type="checkbox"/></td>
<td class="p-4">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-lg bg-surface-container-high overflow-hidden">
<img class="w-full h-full object-cover" data-alt="A smooth, dark espresso being poured into a small ceramic demitasse. Rich crema is visible on the surface. Phútimalist, professional lighting with deep shadows to emphasize quality and freshness of the coffee. Neutral background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDl-F7pB_jprguOZrCZjcS7nuNzkZXsnqVL3v8F-n-6feTIuRqmUKbHxoUphnHo6M2uJckTqxzIx38eDwDqJOdel3oWMZ8QdMbpQl62N9qJCQtfhPrgCxNpGQ_AFkb3lbgLhATzLOJHsgnV7Xmk2CferBFhMi7mCZoBMFQZX4p1u9CqPHJubf-av86Y66iaSlKbis6YAtgPysF905Tqw157CW0NXCDLIja05_0e5SIMbTtVvYICTUoSmTlFlcLabtJrtWEa1WQZ"/>
</div>
<div>
<p class="font-body-md text-body-md font-semibold text-on-surface">Double Espresso Shot</p>
<p class="text-label-md text-on-surface-variant">SKU: ES-202</p>
</div>
</div>
</td>
<td class="p-4"><span class="px-2 py-1 bg-secondary-container/20 text-secondary text-label-md rounded-full">Classic Coffee</span></td>
<td class="p-4 text-on-surface font-semibold">$3.25</td>
<td class="p-4 text-on-surface-variant text-body-md">Low Stock (12)</td>
<td class="p-4">
<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-label-md font-bold">
<span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Hoạt động
                                    </span>
</td>
<td class="p-4 text-right opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1 hover:bg-surface-container rounded transition-colors"><span class="material-symbols-outlined text-on-surface-variant">more_vert</span></button>
</td>
</tr>
<!-- Table Row 3 -->
<tr class="hover:bg-surface-container-lowest transition-colors group">
<td class="p-4"><input class="rounded text-primary focus:ring-primary border-outline" type="checkbox"/></td>
<td class="p-4">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-lg bg-surface-container-high overflow-hidden">
<img class="w-full h-full object-cover" data-alt="A stack of golden brown, freshly baked almond croissants on a white marble surface. Flaky texture and powdered sugar topping are highlighted by soft, natural morning light. Warm, organic, premium bakery aesthetic for an administrative product management view." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDM1o2hnt9w08pLmwQCcytn6ds3HdO6UFLsVX1FseqqasaFpF8UwtroGboWrBa3lrcbvKgrIkq2nJ8sPPYF1bcgA-IpR6ZSZKaZHJoGXjxAVvOXFbmDfq7cMFgcrHV1wj8HJ8zzBkQtK2toq-lg_8FAypzrfhWcVibqF7k4OW2OBb9A4ndgdsGFqFVXandJf7Fhz0WcZoV0Jlgc88Fh0Y00vPwmv4n8pQjgnCgN3t97dKRM0z0zYGdpSDDAq03V9W3JmJIguodA"/>
</div>
<div>
<p class="font-body-md text-body-md font-semibold text-on-surface">Almond Croissant</p>
<p class="text-label-md text-on-surface-variant">SKU: BK-501</p>
</div>
</div>
</td>
<td class="p-4"><span class="px-2 py-1 bg-secondary-container/20 text-secondary text-label-md rounded-full">Bakery</span></td>
<td class="p-4 text-on-surface font-semibold">$4.75</td>
<td class="p-4 text-on-surface-variant text-body-md">Out of Stock</td>
<td class="p-4">
<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-error-container text-error text-label-md font-bold">
<span class="w-1.5 h-1.5 rounded-full bg-error"></span> Draft
                                    </span>
</td>
<td class="p-4 text-right opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1 hover:bg-surface-container rounded transition-colors"><span class="material-symbols-outlined text-on-surface-variant">more_vert</span></button>
</td>
</tr>
</tbody>
</table>
<div class="p-4 bg-surface-container-low flex justify-between items-center text-label-md text-on-surface-variant">
<p>Showing 1-10 of 124 products</p>
<div class="flex gap-2">
<button class="p-1 hover:bg-white rounded transition-colors border border-transparent hover:border-outline-variant/30"><span class="material-symbols-outlined">chevron_left</span></button>
<button class="w-8 h-8 flex items-center justify-center bg-primary text-on-primary rounded-lg font-bold">1</button>
<button class="w-8 h-8 flex items-center justify-center hover:bg-white rounded-lg transition-colors border border-transparent hover:border-outline-variant/30">2</button>
<button class="w-8 h-8 flex items-center justify-center hover:bg-white rounded-lg transition-colors border border-transparent hover:border-outline-variant/30">3</button>
<button class="p-1 hover:bg-white rounded transition-colors border border-transparent hover:border-outline-variant/30"><span class="material-symbols-outlined">chevron_right</span></button>
</div>
</div>
</div>
</section>
<!-- Categories Tab Content (Hidden by Default) -->
<section class="tab-pane hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-lg" id="categories-content">
<div class="category-card bg-white p-lg rounded-2xl border border-outline-variant/30 shadow-sm hover:shadow-md hover:border-primary/30 transition-all group cursor-pointer">
<div class="w-full h-32 rounded-xl bg-surface-container mb-md flex items-center justify-center text-primary/40 group-hover:text-primary transition-colors overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform" data-alt="A flat-lay photograph of various coffee beans, an espresso cup, and a latte art heart. The colors are earthy browns and creamy whites, with soft shadows and a minimalist vibe. Designed to represent the 'Coffee' category in a high-end SaaS product management system." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCW_MQ45RiDMMFEPgvMVXujJfAhnkTVcb9vvexjUYkr8GMm_jmZiL1z0E_iSWmgDcu4V_te6kXcLTEV-PR20eFpS1TV6-m9te31b8BBO-bVV3ieW1VVSmwefVRKalQOjOJH2ZzD8AGiờRbCTBC7LiyGF7TOOXqrdrPoVGRG5f60CzHMaYmm68hxg6H3kKkgN2vzvWL3p_IkV2QYLr2VzY9hite7gYGFweTtRx1K_-pZt5rJcKZlOKibMu6AewZwFIvg5csy0fpq"/>
</div>
<div class="flex justify-between items-start">
<div>
<h3 class="font-title-lg text-title-lg text-on-surface">Hot Coffee</h3>
<p class="text-label-md text-on-surface-variant">24 Sản phẩm</p>
</div>
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">edit</span>
</div>
</div>
<div class="category-card bg-white p-lg rounded-2xl border border-outline-variant/30 shadow-sm hover:shadow-md hover:border-primary/30 transition-all group cursor-pointer">
<div class="w-full h-32 rounded-xl bg-surface-container mb-md flex items-center justify-center text-primary/40 group-hover:text-primary transition-colors overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform" data-alt="A tall glass of layered iced coffee with swirls of milk and ice cubes. The lighting is bright and summery, emphasizing freshness. Phútimalist aesthetic with a soft blue background to denote the 'Iced Beverages' category." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCXgr9ac86--X0MhmNA6FXI8tpQhLu41yH8oTCf11bsSSQ8Lnsct4pEvOCSWKVgZE7mxbJpVEmB17-5prvhDeF6rdD1C2UNrutRc0wHcNDrut73NJ-Zls_KSjXOPSv67WOxH9qoXSbiusOOM_ECH62ZE9xXl2NiJvIYAf_gcNaSyuox1Mt9bE4UW7O4PTyPy2H7B1Wvvdyw7EpwFDFvX1m9T1nUtdybNJdIHlZXsmIhqtf8fsNOIWhVDNrw_irKKI7suvlVUA_9"/>
</div>
<div class="flex justify-between items-start">
<div>
<h3 class="font-title-lg text-title-lg text-on-surface">Cold Brew</h3>
<p class="text-label-md text-on-surface-variant">12 Sản phẩm</p>
</div>
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">edit</span>
</div>
</div>
<div class="category-card bg-white p-lg rounded-2xl border border-outline-variant/30 shadow-sm hover:shadow-md hover:border-primary/30 transition-all group cursor-pointer">
<div class="w-full h-32 rounded-xl bg-surface-container mb-md flex items-center justify-center text-primary/40 group-hover:text-primary transition-colors overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform" data-alt="Close up of a wooden bowl filled with high-quality loose leaf green tea. The lighting is soft and natural, emphasizing the organic texture of the tea leaves. Professional clean style for a premium tea category." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDjyf0J-vzjQdaKgWb3Qj8WKijEP-PQEKDwNPO5SvcZ81CWwQMLmbwUuS8Mnv9e-rcl6h-ZMFwD-M82GBuAAoxAB1bG-fBtxMgvPemwuOD8oYk9sYyzKBnDoFswLXlTGLJpOqhTbZvZMFCFbhtdJNxcp8Zy_kzzb_OXYFgBpis6IJUN-o828-xdxGUEiu_AMF4oYv6JfyzHIcTMp832wJsF0Gxn82KIUMe5l8BBN2nsp6VYTTqJcrXzZDd0N0Fb1RRm6JdJEepm"/>
</div>
<div class="flex justify-between items-start">
<div>
<h3 class="font-title-lg text-title-lg text-on-surface">Premium Teas</h3>
<p class="text-label-md text-on-surface-variant">18 Sản phẩm</p>
</div>
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">edit</span>
</div>
</div>
<!-- Add Category Placeholder -->
<div class="bg-surface-container-low border-2 border-dashed border-outline-variant/50 p-lg rounded-2xl flex flex-col items-center justify-center text-on-surface-variant hover:border-primary/50 hover:bg-white transition-all group cursor-pointer">
<span class="material-symbols-outlined text-4xl mb-2 group-hover:text-primary transition-colors">add_circle</span>
<p class="font-bold">Thêm Mới Category</p>
</div>
</section>
<!-- Recipes & Ingredients Tab (Hidden by Default) -->
<section class="tab-pane hidden flex flex-col lg:flex-row gap-lg" id="recipes-content">
<!-- Ingredients Kho hàng -->
<div class="flex-1 bg-white p-lg rounded-2xl border border-outline-variant/30 shadow-sm">
<div class="flex justify-between items-center mb-lg">
<h3 class="font-title-lg text-title-lg text-on-surface">Ingredients Kho hàng</h3>
<button class="text-primary font-bold text-label-md flex items-center gap-1"><span class="material-symbols-outlined">add</span> Update Stock</button>
</div>
<div class="space-y-md">
<div class="flex items-center justify-between p-md border border-outline-variant/20 rounded-xl hover:bg-surface-container-low transition-colors">
<div class="flex items-center gap-4">
<div class="p-2 bg-secondary-container/20 rounded-lg text-secondary"><span class="material-symbols-outlined">opacity</span></div>
<div>
<p class="font-bold text-on-surface">Whole Milk (Oatly)</p>
<p class="text-label-md text-on-surface-variant">Unit: Liter</p>
</div>
</div>
<div class="text-right">
<p class="font-bold text-on-surface">42.5 L</p>
<div class="w-32 h-2 bg-surface-container-high rounded-full overflow-hidden mt-1">
<div class="h-full bg-primary w-[65%]"></div>
</div>
</div>
</div>
<div class="flex items-center justify-between p-md border border-outline-variant/20 rounded-xl hover:bg-surface-container-low transition-colors">
<div class="flex items-center gap-4">
<div class="p-2 bg-secondary-container/20 rounded-lg text-secondary"><span class="material-symbols-outlined">coffee_maker</span></div>
<div>
<p class="font-bold text-on-surface">Espresso Roast Beans</p>
<p class="text-label-md text-on-surface-variant">Unit: Kilogram</p>
</div>
</div>
<div class="text-right">
<p class="font-bold text-error">2.1 kg</p>
<div class="w-32 h-2 bg-surface-container-high rounded-full overflow-hidden mt-1">
<div class="h-full bg-error w-[15%]"></div>
</div>
</div>
</div>
<div class="flex items-center justify-between p-md border border-outline-variant/20 rounded-xl hover:bg-surface-container-low transition-colors">
<div class="flex items-center gap-4">
<div class="p-2 bg-secondary-container/20 rounded-lg text-secondary"><span class="material-symbols-outlined">liquor</span></div>
<div>
<p class="font-bold text-on-surface">Vanilla Syrup</p>
<p class="text-label-md text-on-surface-variant">Unit: Bottle (1L)</p>
</div>
</div>
<div class="text-right">
<p class="font-bold text-on-surface">8 bottles</p>
<div class="w-32 h-2 bg-surface-container-high rounded-full overflow-hidden mt-1">
<div class="h-full bg-primary w-[80%]"></div>
</div>
</div>
</div>
</div>
</div>
<!-- Hoạt động Recipes Panel -->
<div class="w-full lg:w-96 bg-white p-lg rounded-2xl border border-outline-variant/30 shadow-sm">
<h3 class="font-title-lg text-title-lg text-on-surface mb-lg">Quick Recipes</h3>
<div class="space-y-4">
<div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/10 group cursor-pointer hover:border-primary/40 transition-colors">
<div class="flex items-center gap-3 mb-2">
<div class="w-8 h-8 rounded bg-primary-container/20 text-primary flex items-center justify-center font-bold">M</div>
<h4 class="font-bold text-on-surface">Matcha Latte</h4>
</div>
<ul class="text-label-md text-on-surface-variant space-y-1 pl-11">
<li>• 1.5g Ceremonial Matcha</li>
<li>• 250ml Whole Milk</li>
<li>• 15ml Liquid Cane Sugar</li>
</ul>
</div>
<div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/10 group cursor-pointer hover:border-primary/40 transition-colors">
<div class="flex items-center gap-3 mb-2">
<div class="w-8 h-8 rounded bg-primary-container/20 text-primary flex items-center justify-center font-bold">C</div>
<h4 class="font-bold text-on-surface">Caramel Macchiato</h4>
</div>
<ul class="text-label-md text-on-surface-variant space-y-1 pl-11">
<li>• 2 shots Espresso</li>
<li>• 200ml Steamed Milk</li>
<li>• 20ml Caramel Drizzle</li>
</ul>
</div>
</div>
</div>
</section>
<!-- Sizes Tab Content (Hidden by Default) -->
<section class="tab-pane hidden" id="sizes-content">
<div class="max-w-3xl bg-white rounded-2xl border border-outline-variant/30 shadow-sm">
<div class="p-lg border-b border-outline-variant/30 flex justify-between items-center">
<div>
<h3 class="font-title-lg text-title-lg text-on-surface">Global Size Presets</h3>
<p class="text-body-md text-on-surface-variant">Define volume and name mappings across all beverages.</p>
</div>
<button class="bg-primary text-on-primary px-4 py-2 rounded-lg font-bold text-label-md shadow-sm hover:opacity-90">Add Size</button>
</div>
<div class="divide-y divide-outline-variant/10">
<div class="p-lg flex items-center justify-between">
<div class="flex items-center gap-6">
<div class="w-12 h-16 bg-surface-container-high rounded flex items-center justify-center text-primary-fixed-dim border-2 border-primary/20"><span class="material-symbols-outlined" style="font-size: 1.5rem">local_cafe</span></div>
<div>
<p class="font-bold text-on-surface">Small (Tall)</p>
<p class="text-label-md text-on-surface-variant">Volume: 12 fl oz / 355 ml</p>
</div>
</div>
<div class="flex gap-2">
<button class="p-2 hover:bg-surface-container rounded-lg text-on-surface-variant"><span class="material-symbols-outlined">edit</span></button>
<button class="p-2 hover:bg-error-container/20 rounded-lg text-error"><span class="material-symbols-outlined">delete</span></button>
</div>
</div>
<div class="p-lg flex items-center justify-between">
<div class="flex items-center gap-6">
<div class="w-12 h-20 bg-surface-container-high rounded flex items-center justify-center text-primary-fixed-dim border-2 border-primary/20"><span class="material-symbols-outlined" style="font-size: 2rem">local_cafe</span></div>
<div>
<p class="font-bold text-on-surface">Medium (Grande)</p>
<p class="text-label-md text-on-surface-variant">Volume: 16 fl oz / 473 ml</p>
</div>
</div>
<div class="flex gap-2">
<button class="p-2 hover:bg-surface-container rounded-lg text-on-surface-variant"><span class="material-symbols-outlined">edit</span></button>
<button class="p-2 hover:bg-error-container/20 rounded-lg text-error"><span class="material-symbols-outlined">delete</span></button>
</div>
</div>
</div>
</div>
</section>
</div>
</main>
@endsection

@push('scripts')
<script>

        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.children[0].classList.add('scale-100');
                    modal.children[0].classList.remove('scale-95');
                }, 10);
            } else {
                modal.children[0].classList.add('scale-95');
                modal.children[0].classList.remove('scale-100');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 150);
            }
        }

        function switchTab(tabId) {
            // Hide all contents
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));
            // Show target content
            document.getElementById(`${tabId}-content`).classList.remove('hidden');
            
            // Update Tab UI
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active-tab', 'bg-white', 'shadow-sm', 'text-primary');
                btn.classList.add('text-on-surface-variant');
            });
            const activeBtn = document.querySelector(`[data-tab="${tabId}"]`);
            activeBtn.classList.add('active-tab', 'bg-white', 'shadow-sm', 'text-primary');
            activeBtn.classList.remove('text-on-surface-variant');
        }

        // Search bar interaction
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.classList.add('scale-[1.02]');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.classList.remove('scale-[1.02]');
        });

        // Close modal on escape key
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const openModal = document.querySelector('#productModal:not(.hidden)');
                if (openModal) toggleModal('productModal');
            }
        });
    
</script>
@endpush
