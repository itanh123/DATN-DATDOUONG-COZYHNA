@extends('admin.layouts.app')

@section('title', 'Product Management')

@section('content')
    @if (session('success'))
        <div class="mb-lg px-xl py-lg bg-green-50 border border-green-200 text-green-800 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-lg px-xl py-lg bg-error-container/20 border border-error-container text-error rounded-xl">
            <div class="font-semibold mb-2">There are some problems with your submission:</div>
            <ul class="list-disc pl-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex items-center justify-between mb-xl">

        <div class="flex items-center gap-4">
            <h2 class="font-headline-md text-headline-md text-on-surface">Product Management</h2>
            <div class="h-6 w-px bg-outline-variant/50"></div>
            <div class="flex bg-surface-container-low p-1 rounded-lg">
                <button
                    class="tab-btn px-4 py-1.5 rounded-md font-label-md transition-all active-tab bg-white shadow-sm text-primary"
                    data-tab="products" type="button" onclick="switchTab('products')">Products</button>
                <button
                    class="tab-btn px-4 py-1.5 rounded-md font-label-md transition-all text-on-surface-variant hover:text-on-surface"
                    data-tab="categories" type="button" onclick="switchTab('categories')">Categories</button>
                <button
                    class="tab-btn px-4 py-1.5 rounded-md font-label-md transition-all text-on-surface-variant hover:text-on-surface"
                    data-tab="recipes" type="button" onclick="switchTab('recipes')">Recipes &amp; Ingredients</button>
                <button
                    class="tab-btn px-4 py-1.5 rounded-md font-label-md transition-all text-on-surface-variant hover:text-on-surface"
                    data-tab="sizes" type="button" onclick="switchTab('sizes')">Sizes</button>
            </div>
        </div>

        <div class="flex items-center gap-md">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input
                    class="pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 text-body-md w-64 transition-all"
                    placeholder="Search items..." type="text" />
            </div>
            <button class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors relative">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-2 right-2 w-2 h-2 bg-primary rounded-full"></span>
            </button>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto custom-scrollbar bg-surface-container-lowest p-xl">
        <!-- Products Tab Content -->
        <section class="tab-pane space-y-lg" id="products-content">
            <div class="flex justify-between items-center">
                <div class="flex gap-2">
                    <div class="flex items-center gap-2 px-3 py-2 bg-white border border-outline-variant/30 rounded-lg shadow-sm text-body-md cursor-pointer hover:bg-surface-container-low transition-colors">
                        <span class="material-symbols-outlined text-body-md">filter_list</span>
                        <span>Status: All</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-2 bg-white border border-outline-variant/30 rounded-lg shadow-sm text-body-md cursor-pointer hover:bg-surface-container-low transition-colors">
                        <span class="material-symbols-outlined text-body-md">category</span>
                        <span>Category: All</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button class="flex items-center gap-2 px-4 py-2 border border-outline-variant/30 rounded-lg text-on-surface-variant font-label-md hover:bg-surface-container-low transition-all" type="button">
                        <span class="material-symbols-outlined">download</span> Export
                    </button>
                    <button
                        class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md shadow-sm hover:opacity-90 active:scale-95 transition-all"
                        type="button" onclick="toggleModal('productModal')">
                        <span class="material-symbols-outlined">add</span> Add Product
                    </button>
                </div>
            </div>


            <div class="bg-white rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant/30">
                            <th class="p-4 w-10">
                                <input class="rounded text-primary focus:ring-primary border-outline" type="checkbox" />
                            </th>
                            <th class="p-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Product</th>
                            <th class="p-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Category</th>
                            <th class="p-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Price</th>
                            <th class="p-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Inventory</th>
                            <th class="p-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Status</th>
                            <th class="p-4 w-10"></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($products as $product)
                            <tr class="hover:bg-surface-container-lowest transition-colors group">
                                <td class="p-4">
                                    <input class="rounded text-primary focus:ring-primary border-outline" type="checkbox" />
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-surface-container-high overflow-hidden">
                                            @if($product->image)
                                                <img class="w-full h-full object-cover" src="{{ $product->image }}" />
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-body-md text-body-md font-semibold text-on-surface">{{ $product->name }}</p>
                                            <p class="text-label-md text-on-surface-variant">SKU: {{ $product->code }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 bg-secondary-container/20 text-secondary text-label-md rounded-full">
                                        {{ $product->category->name ?? '' }}
                                    </span>
                                </td>
                                <td class="p-4 text-on-surface font-semibold">$0.00</td>
                                <td class="p-4 text-on-surface-variant text-body-md">N/A</td>
                                <td class="p-4">
                                    @if($product->status)
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-label-md font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-error-container text-error text-label-md font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-error"></span> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button
                                        class="p-1 hover:bg-surface-container rounded transition-colors action-more"
                                        type="button"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-code="{{ $product->code }}"
                                        data-category-id="{{ $product->category_id }}"
                                        data-description="{{ $product->description }}"
                                        data-status="{{ $product->status }}"
                                        data-image="{{ $product->image }}"
                                        onclick="openProductActions(this)"
                                    >
                                        <span class="material-symbols-outlined text-on-surface-variant">more_vert</span>
                                    </button>
                                    <div class="relative inline-block" style="position: relative;">
                                        <div class="absolute right-0 mt-2 w-40 bg-white border border-outline-variant/30 rounded-lg shadow-sm z-10 hidden product-action-menu" role="menu">
                                            <button
                                                type="button"
                                                class="w-full text-left px-3 py-2 hover:bg-surface-container-low transition-colors"
                                                onclick="openEditModalFromButton(this)"
                                            >Edit</button>
                                            <form action="/admin/product/{{ $product->id }}/delete" method="POST" class="m-0">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="w-full text-left px-3 py-2 hover:bg-surface-container-low transition-colors text-error"
                                                    onclick="return confirm('Are you sure you want to delete this product?')"
                                                >Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-4 text-on-surface-variant">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>


        <!-- Categories/Recipes/Sizes placeholders giữ nguyên từ giao diện hiện tại -->
        <section class="tab-pane hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-lg" id="categories-content"></section>
        <section class="tab-pane hidden flex flex-col lg:flex-row gap-lg" id="recipes-content"></section>
        <section class="tab-pane hidden" id="sizes-content"></section>
    </div>

    <!-- Modal: Edit Product -->
    <div class="fixed inset-0 z-50 hidden bg-on-surface/40 backdrop-blur-sm flex items-center justify-center p-4" id="productEditModal">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <div class="px-xl py-lg bg-surface-container-low border-b border-outline-variant/30 flex justify-between items-center">
                <h3 class="font-headline-md text-headline-md">Edit Product</h3>
                <button type="button" onclick="toggleModal('productEditModal')" class="p-2 hover:bg-surface-container rounded-full">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="p-xl space-y-lg">
                <form id="edit_product_form" action="/admin/product/0/update" method="POST" enctype="multipart/form-data" class="space-y-lg">
                    @csrf

                    <input type="hidden" id="edit_product_id" name="product_id" />

                    <div>
                        <label class="block mb-2 font-medium">Product Name</label>
                        <input id="edit_name" type="text" name="name" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none" placeholder="Enter product name" required>
                    </div>

                    <div class="grid grid-cols-2 gap-lg">
                        <div>
                            <label class="block mb-2 font-medium">Product Code</label>
                            <input id="edit_code" type="text" name="code" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none" placeholder="CF001" required>
                        </div>

                        <div>
                            <label class="block mb-2 font-medium">Category</label>
                            <select id="edit_category_id" name="category_id" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none" required>
                                <option value="">Select category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium">Description</label>
                        <textarea id="edit_description" name="description" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none" placeholder="Product description"></textarea>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium">Current Image</label>
                        <div id="edit_current_image_preview" class="hidden w-24 h-24 rounded-lg bg-surface-container-high overflow-hidden">
                            <img class="w-full h-full object-cover" src="" />
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium">Product Image (optional)</label>
                        <input type="file" name="image" accept="image/*" class="block w-full border border-gray-300 rounded-xl p-3">
                    </div>

                    <div>
                        <label class="block mb-3 font-medium">Status</label>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2">
                                <input id="edit_status_active" type="radio" name="status" value="1" required>
                                Active
                            </label>
                            <label class="flex items-center gap-2">
                                <input id="edit_status_inactive" type="radio" name="status" value="0" required>
                                Inactive
                            </label>
                        </div>
                    </div>

                    <div class="px-xl py-lg border-t flex justify-end gap-3">
                        <button onclick="toggleModal('productEditModal')" type="button" class="px-6 py-2 rounded-xl border">Cancel</button>
                        <button type="submit" class="px-8 py-2 rounded-xl bg-green-600 text-white">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Add New Product -->
    <div class="fixed inset-0 z-50 hidden bg-on-surface/40 backdrop-blur-sm flex items-center justify-center p-4" id="productModal">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <div class="px-xl py-lg bg-surface-container-low border-b border-outline-variant/30 flex justify-between items-center">
                <h3 class="font-headline-md text-headline-md">Add Product</h3>
                <button type="button" onclick="toggleModal('productModal')" class="p-2 hover:bg-surface-container rounded-full">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="p-xl space-y-lg">
                <form action="/admin/product/store" method="POST" enctype="multipart/form-data" class="space-y-lg">
                    @csrf

                    <div>
                        <label class="block mb-2 font-medium">Product Name</label>
                        <input type="text" name="name" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none" placeholder="Enter product name" required>
                    </div>

                    <div class="grid grid-cols-2 gap-lg">
                        <div>
                            <label class="block mb-2 font-medium">Product Code</label>
                            <input type="text" name="code" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none" placeholder="CF001" required>
                        </div>

                        <div>
                            <label class="block mb-2 font-medium">Category</label>
                            <select name="category_id" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none" required>
                                <option value="">Select category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium">Description</label>
                        <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none" placeholder="Product description"></textarea>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium">Product Image</label>
                        <input type="file" name="image" accept="image/*" class="block w-full border border-gray-300 rounded-xl p-3" required>
                    </div>

                    <div>
                        <label class="block mb-3 font-medium">Status</label>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="status" value="1" checked required>
                                Active
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="status" value="0" required>
                                Inactive
                            </label>
                        </div>
                    </div>

                    <div class="px-xl py-lg border-t flex justify-end gap-3">
                        <button onclick="toggleModal('productModal')" type="button" class="px-6 py-2 rounded-xl border">Cancel</button>
                        <button type="submit" class="px-8 py-2 rounded-xl bg-green-600 text-white">Save Product</button>
                    </div>

                    <p class="text-xs text-on-surface-variant">
                        Nếu bị lỗi <b>"The code has already been taken"</b> thì bạn đang nhập <b>Product Code</b> trùng (cột <b>code</b> unique trong database) — hãy nhập code khác.
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;

            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }


        function switchTab(tabId) {
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));
            const el = document.getElementById(`${tabId}-content`);
            if (el) el.classList.remove('hidden');
        }

        function openProductActions(button) {
            if (!button) return;
            const row = button.closest('tr');
            if (!row) return;

            const menu = row.querySelector('.product-action-menu');
            if (!menu) return;

            // Close others
            document.querySelectorAll('.product-action-menu').forEach(m => {
                if (m !== menu) m.classList.add('hidden');
            });

            menu.classList.toggle('hidden');
        }

        function openEditModalFromButton(btn) {
            const row = btn.closest('tr');
            if (!row) return;
            const moreBtn = row.querySelector('.action-more');
            if (!moreBtn) return;

            openEditModal(moreBtn);
        }

        function openEditModal(button) {
            const modal = document.getElementById('productEditModal');
            if (!modal) return;

            // Prefill
            const id = button.getAttribute('data-id');
            if (!id) return;

            document.getElementById('edit_product_id').value = id;
            document.getElementById('edit_name').value = button.getAttribute('data-name') ?? '';
            document.getElementById('edit_code').value = button.getAttribute('data-code') ?? '';
            document.getElementById('edit_category_id').value = button.getAttribute('data-category-id') ?? '';
            document.getElementById('edit_description').value = button.getAttribute('data-description') ?? '';
            const statusVal = button.getAttribute('data-status') ?? '0';

            // Ensure radio correct even when statusVal is boolean-like string.
            const normalizedStatus = String(statusVal) === '1' ? '1' : '0';

            const radioActive = document.getElementById('edit_status_active');
            const radioInactive = document.getElementById('edit_status_inactive');
            if (normalizedStatus === '1') {
                if (radioActive) radioActive.checked = true;
            } else {
                if (radioInactive) radioInactive.checked = true;
            }



            // Update form action
            const form = document.getElementById('edit_product_form');
            form.action = `/admin/product/${id}/update`;

            // Image preview
            const currentImg = button.getAttribute('data-image');
            const preview = document.getElementById('edit_current_image_preview');
            if (preview) {
                if (currentImg) {
                    preview.classList.remove('hidden');
                    preview.querySelector('img').src = currentImg;
                } else {
                    preview.classList.add('hidden');
                }
            }

            // close dropdown
            document.querySelectorAll('.product-action-menu').forEach(m => m.classList.add('hidden'));

            modal.classList.remove('hidden');
        }

        // Close action menu on outside click
        document.addEventListener('click', (e) => {
            const menu = e.target?.closest?.('.product-action-menu');
            const more = e.target?.closest?.('.action-more');
            if (!menu && !more) {
                document.querySelectorAll('.product-action-menu').forEach(m => m.classList.add('hidden'));
            }
        });
    </script>
@endsection

