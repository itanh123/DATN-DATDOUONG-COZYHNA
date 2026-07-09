@extends('admin.layouts.app')

@section('title', 'Product Management')

@section('content')
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
                                    <button class="p-1 hover:bg-surface-container rounded transition-colors" type="button">
                                        <span class="material-symbols-outlined text-on-surface-variant">more_vert</span>
                                    </button>
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
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
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
    </script>
@endsection

