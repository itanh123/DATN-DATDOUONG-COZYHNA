@extends('layouts.admin')

@section('title', 'Product Management')

@section('content')
<main class="ml-[280px] h-screen flex flex-col overflow-hidden p-xl">
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
        <section class="tab-pane space-y-lg pb-32" id="products-content">
            <div class="flex justify-between items-center">
                <form action="/admin/product" method="GET" class="m-0 flex gap-2">
                    <div class="flex items-center gap-2 px-3 py-1 bg-white border border-outline-variant/30 rounded-lg shadow-sm text-body-md hover:bg-surface-container-low transition-colors">
                        <span class="material-symbols-outlined text-body-md">filter_list</span>
                        <select name="status" onchange="this.form.submit()" class="border-none bg-transparent focus:ring-0 cursor-pointer py-1 pr-6 text-body-md text-on-surface">
                            <option value="">Status: All</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1 bg-white border border-outline-variant/30 rounded-lg shadow-sm text-body-md hover:bg-surface-container-low transition-colors">
                        <span class="material-symbols-outlined text-body-md">category</span>
                        <select name="category_id" onchange="this.form.submit()" class="border-none bg-transparent focus:ring-0 cursor-pointer py-1 pr-6 text-body-md text-on-surface">
                            <option value="">Category: All</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div class="flex gap-2">
                    <button class="flex items-center gap-2 px-4 py-2 border border-outline-variant/30 rounded-lg text-on-surface-variant font-label-md hover:bg-surface-container-low transition-all" type="button">
                        <span class="material-symbols-outlined">download</span> Export
                    </button>
                    @if(check_permission('create_products'))
                    <button
                        class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md shadow-sm hover:opacity-90 active:scale-95 transition-all"
                        type="button" onclick="toggleModal('productModal')">
                        <span class="material-symbols-outlined">add</span> Add Product
                    </button>
                    @endif
                </div>
            </div>


            <div class="bg-white rounded-xl border border-outline-variant/30 shadow-sm overflow-visible">
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
                                <td class="p-4 text-on-surface font-semibold">
                                    @php
                                        $defaultSize = $product->productSizes->firstWhere('is_default', true) ?? $product->productSizes->first();
                                    @endphp
                                    @if($defaultSize)
                                        {{ number_format($defaultSize->selling_price, 0, ',', '.') }} đ
                                    @else
                                        <span class="text-on-surface-variant text-label-sm">No size</span>
                                    @endif
                                </td>
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
                                    @if(check_permission('edit_products') || check_permission('delete_products'))
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
                                            @if(check_permission('edit_products'))
                                            <button
                                                type="button"
                                                class="w-full text-left px-3 py-2 hover:bg-surface-container-low transition-colors"
                                                onclick="openEditModalFromButton(this)"
                                            >Edit</button>
                                            <button
                                                type="button"
                                                class="w-full text-left px-3 py-2 hover:bg-surface-container-low transition-colors text-primary"
                                                onclick="openProductSizeModal(this)"
                                                data-id="{{ $product->id }}"
                                                data-sizes="{{ $product->productSizes->toJson() }}"
                                            >Manage Sizes</button>
                                            @endif
                                            @if(check_permission('delete_products'))
                                            <form action="/admin/product/{{ $product->id }}/delete" method="POST" class="m-0">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="w-full text-left px-3 py-2 hover:bg-surface-container-low transition-colors text-error"
                                                    onclick="return confirm('Are you sure you want to delete this product?')"
                                                >Delete</button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
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
            @if($products->hasPages())
                <div class="p-4 border-t border-outline-variant/30">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </section>


        <!-- Categories/Recipes/Sizes placeholders giữ nguyên từ giao diện hiện tại -->
        <section class="tab-pane hidden space-y-lg pb-32" id="categories-content">
            <div class="flex justify-between items-center">
                <h3 class="font-headline-md text-headline-md">Manage Categories</h3>
                @if(check_permission('create_categories'))
                <button
                    class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md shadow-sm hover:opacity-90 active:scale-95 transition-all"
                    type="button" onclick="toggleModal('categoryModal')">
                    <span class="material-symbols-outlined">add</span> Add Category
                </button>
                @endif
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-lg">
                @forelse($categories as $category)
                    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-sm overflow-visible flex flex-col group hover:shadow-md transition-shadow relative">
                        <div class="h-32 bg-surface-container-high overflow-hidden rounded-t-2xl">
                            @if($category->image)
                                <img src="{{ $category->image }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" />
                            @else
                                <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-4xl">category</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-title-md text-title-md text-on-surface truncate pr-2">{{ $category->name }}</h4>
                                @if(check_permission('edit_categories') || check_permission('delete_categories'))
                                <div class="relative">
                                    <button class="p-1 text-on-surface-variant hover:bg-surface-container rounded-full" onclick="openCategoryActions(this)">
                                        <span class="material-symbols-outlined">more_vert</span>
                                    </button>
                                    <div class="absolute right-0 mt-1 w-32 bg-white border border-outline-variant/30 rounded-lg shadow-sm z-10 hidden category-action-menu">
                                        @if(check_permission('edit_categories'))
                                        <button type="button" class="w-full text-left px-3 py-2 text-label-md hover:bg-surface-container-low transition-colors"
                                            onclick="openCategoryEditModalFromButton(this)"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            data-description="{{ $category->description }}"
                                            data-image="{{ $category->image }}"
                                            data-status="{{ $category->status }}"
                                        >Edit</button>
                                        @endif
                                        @if(check_permission('delete_categories'))
                                        <form action="/admin/category/{{ $category->id }}/delete" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-3 py-2 text-label-md text-error hover:bg-surface-container-low transition-colors" onclick="return confirm('Delete this category?')">Delete</button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                            <p class="text-body-sm text-on-surface-variant line-clamp-2 mb-3">{{ $category->description ?: 'No description' }}</p>
                            <div class="mt-auto">
                                @if($category->status)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-label-sm font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-error-container text-error text-label-sm font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-error"></span> Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full p-8 text-center text-on-surface-variant bg-surface-container-lowest rounded-2xl border border-outline-variant/30">
                        No categories found.
                    </div>
                @endforelse
            </div>
        </section>
        <section class="tab-pane hidden flex flex-col lg:flex-row gap-lg" id="recipes-content"></section>
        <section class="tab-pane hidden space-y-lg pb-32" id="sizes-content">
            <div class="flex justify-between items-center">
                <h3 class="font-headline-md text-headline-md">Manage Sizes</h3>
                @if(check_permission('create_sizes'))
                <button
                    class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md shadow-sm hover:opacity-90 active:scale-95 transition-all"
                    type="button" onclick="toggleModal('sizeModal')">
                    <span class="material-symbols-outlined">add</span> Add Size
                </button>
                @endif
            </div>
            
            <div class="bg-white rounded-xl border border-outline-variant/30 shadow-sm overflow-visible">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant/30">
                            <th class="p-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">ID</th>
                            <th class="p-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Name</th>
                            <th class="p-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Volume (ml)</th>
                            <th class="p-4 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Description</th>
                            <th class="p-4 w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($sizes ?? [] as $size)
                            <tr class="hover:bg-surface-container-lowest transition-colors group">
                                <td class="p-4 text-on-surface-variant">{{ $size->id }}</td>
                                <td class="p-4 font-semibold text-on-surface">{{ $size->name }}</td>
                                <td class="p-4 text-on-surface">{{ $size->volume_ml ?? 'N/A' }}</td>
                                <td class="p-4 text-on-surface-variant">{{ $size->description }}</td>
                                <td class="p-4 text-right opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if(check_permission('edit_sizes') || check_permission('delete_sizes'))
                                    <button
                                        class="p-1 hover:bg-surface-container rounded transition-colors action-more-size"
                                        type="button"
                                        data-id="{{ $size->id }}"
                                        data-name="{{ $size->name }}"
                                        data-volume="{{ $size->volume_ml }}"
                                        data-description="{{ $size->description }}"
                                        onclick="openSizeActions(this)"
                                    >
                                        <span class="material-symbols-outlined text-on-surface-variant">more_vert</span>
                                    </button>
                                    <div class="relative inline-block" style="position: relative;">
                                        <div class="absolute right-0 mt-2 w-40 bg-white border border-outline-variant/30 rounded-lg shadow-sm z-10 hidden size-action-menu" role="menu">
                                            @if(check_permission('edit_sizes'))
                                            <button
                                                type="button"
                                                class="w-full text-left px-3 py-2 hover:bg-surface-container-low transition-colors"
                                                onclick="openSizeEditModalFromButton(this)"
                                            >Edit</button>
                                            @endif
                                            @if(check_permission('delete_sizes'))
                                            <form action="/admin/size/{{ $size->id }}/delete" method="POST" class="m-0">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="w-full text-left px-3 py-2 hover:bg-surface-container-low transition-colors text-error"
                                                    onclick="return confirm('Are you sure you want to delete this size?')"
                                                >Delete</button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-on-surface-variant text-center">No sizes found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
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
                <form id="edit_product_form" action="" method="POST" enctype="multipart/form-data" class="space-y-lg">
                    @csrf
                    <input type="hidden" id="edit_product_id" name="product_id" />
                    @php
                        $isAdmin = session('role_code') === 'admin';
                    @endphp

                    <div>
                        <label class="block mb-2 font-medium">Product Name</label>
                        <input id="edit_name" type="text" name="name" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none disabled:bg-surface-container disabled:text-on-surface-variant" placeholder="Enter product name" {{ $isAdmin ? 'required' : 'disabled' }}>
                    </div>

                    <div class="grid grid-cols-2 gap-lg">
                        <div>
                            <label class="block mb-2 font-medium">Product Code</label>
                            <input id="edit_code" type="text" name="code" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none disabled:bg-surface-container disabled:text-on-surface-variant" placeholder="CF001" {{ $isAdmin ? 'required' : 'disabled' }}>
                        </div>

                        <div>
                            <label class="block mb-2 font-medium">Category</label>
                            <select id="edit_category_id" name="category_id" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none disabled:bg-surface-container disabled:text-on-surface-variant" {{ $isAdmin ? 'required' : 'disabled' }}>
                                <option value="">Select category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium">Description</label>
                        <textarea id="edit_description" name="description" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none disabled:bg-surface-container disabled:text-on-surface-variant" placeholder="Product description" {{ $isAdmin ? '' : 'disabled' }}></textarea>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium">Current Image</label>
                        <div id="edit_current_image_preview" class="hidden w-24 h-24 rounded-lg bg-surface-container-high overflow-hidden">
                            <img class="w-full h-full object-cover" src="" />
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium">Product Image (optional)</label>
                        <input type="file" name="image" accept="image/*" class="block w-full border border-gray-300 rounded-xl p-3 disabled:opacity-50" {{ $isAdmin ? '' : 'disabled' }}>
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

    <!-- Modal: Manage Product Sizes -->
    <div class="fixed inset-0 z-50 hidden bg-on-surface/40 backdrop-blur-sm flex items-center justify-center p-4" id="productSizeManageModal">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-xl py-lg bg-surface-container-low border-b border-outline-variant/30 flex justify-between items-center">
                <h3 class="font-headline-md text-headline-md">Manage Sizes & Prices</h3>
                <button type="button" onclick="toggleModal('productSizeManageModal')" class="p-2 hover:bg-surface-container rounded-full">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-xl overflow-y-auto flex-1">
                <form id="product_size_form" action="" method="POST" class="space-y-md">
                    @csrf
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-outline-variant/30">
                                <th class="p-3 w-10">Active</th>
                                <th class="p-3 font-label-sm text-label-sm text-on-surface-variant uppercase">Size</th>
                                <th class="p-3 font-label-sm text-label-sm text-on-surface-variant uppercase">Price (Sell)</th>
                                <th class="p-3 font-label-sm text-label-sm text-on-surface-variant uppercase">Price (Cost)</th>
                                <th class="p-3 w-10 font-label-sm text-label-sm text-on-surface-variant uppercase text-center">Default</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sizes as $s)
                                <tr class="border-b border-outline-variant/10 hover:bg-surface-container-lowest transition-colors">
                                    <td class="p-3 text-center">
                                        <input type="checkbox" id="ps_active_{{ $s->id }}" name="sizes[{{ $s->id }}][active]" value="1" class="rounded text-primary focus:ring-primary border-outline" onchange="toggleSizeRow({{ $s->id }})" />
                                    </td>
                                    <td class="p-3 font-semibold text-on-surface">
                                        {{ $s->name }} <span class="text-xs text-on-surface-variant font-normal">({{ $s->volume_ml }}ml)</span>
                                    </td>
                                    <td class="p-3">
                                        <input type="number" id="ps_sell_{{ $s->id }}" name="sizes[{{ $s->id }}][selling_price]" class="w-24 px-2 py-1 rounded border border-gray-300 focus:border-green-500 outline-none disabled:bg-gray-100" disabled>
                                    </td>
                                    <td class="p-3">
                                        <input type="number" id="ps_cost_{{ $s->id }}" name="sizes[{{ $s->id }}][cost_price]" class="w-24 px-2 py-1 rounded border border-gray-300 focus:border-green-500 outline-none disabled:bg-gray-100" disabled>
                                    </td>
                                    <td class="p-3 text-center">
                                        <input type="radio" id="ps_default_{{ $s->id }}" name="default_size_id" value="{{ $s->id }}" class="text-primary focus:ring-primary border-outline disabled:bg-gray-100" disabled>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="pt-4 border-t flex justify-end gap-3 mt-lg">
                        <button onclick="toggleModal('productSizeManageModal')" type="button" class="px-6 py-2 rounded-xl border">Cancel</button>
                        <button type="submit" class="px-8 py-2 rounded-xl bg-green-600 text-white">Save Sizes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Add Category -->
    <div class="fixed inset-0 z-50 hidden bg-on-surface/40 backdrop-blur-sm flex items-center justify-center p-4" id="categoryModal">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <div class="px-xl py-lg bg-surface-container-low border-b border-outline-variant/30 flex justify-between items-center">
                <h3 class="font-headline-md text-headline-md">Add Category</h3>
                <button type="button" onclick="toggleModal('categoryModal')" class="p-2 hover:bg-surface-container rounded-full">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-xl space-y-lg">
                <form action="/admin/category/store" method="POST" class="space-y-lg">
                    @csrf
                    <div>
                        <label class="block font-label-md mb-2">Category Name *</label>
                        <input name="name" type="text" required class="w-full px-4 py-2 border border-outline-variant/50 rounded-xl" placeholder="E.g. Coffee">
                    </div>
                    <div>
                        <label class="block font-label-md mb-2">Image URL</label>
                        <input name="image" type="text" class="w-full px-4 py-2 border border-outline-variant/50 rounded-xl" placeholder="https://...">
                    </div>
                    <div>
                        <label class="block font-label-md mb-2">Description</label>
                        <textarea name="description" class="w-full px-4 py-2 border border-outline-variant/50 rounded-xl h-24" placeholder="Brief description"></textarea>
                    </div>
                    <div class="flex items-center gap-3">
                        <input name="status" type="checkbox" checked value="1" class="rounded w-5 h-5 text-primary border-outline focus:ring-primary">
                        <label class="font-label-md">Active Category</label>
                    </div>
                    <div class="flex justify-end gap-md pt-4">
                        <button onclick="toggleModal('categoryModal')" type="button" class="px-6 py-2 rounded-xl border border-outline-variant/50">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded-xl font-bold">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Edit Category -->
    <div class="fixed inset-0 z-50 hidden bg-on-surface/40 backdrop-blur-sm flex items-center justify-center p-4" id="categoryEditModal">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <div class="px-xl py-lg bg-surface-container-low border-b border-outline-variant/30 flex justify-between items-center">
                <h3 class="font-headline-md text-headline-md">Edit Category</h3>
                <button type="button" onclick="toggleModal('categoryEditModal')" class="p-2 hover:bg-surface-container rounded-full">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-xl space-y-lg">
                <form id="categoryEditForm" method="POST" class="space-y-lg">
                    @csrf
                    <div>
                        <label class="block font-label-md mb-2">Category Name *</label>
                        <input id="edit_category_name" name="name" type="text" required class="w-full px-4 py-2 border border-outline-variant/50 rounded-xl">
                    </div>
                    <div>
                        <label class="block font-label-md mb-2">Image URL</label>
                        <input id="edit_category_image" name="image" type="text" class="w-full px-4 py-2 border border-outline-variant/50 rounded-xl">
                    </div>
                    <div>
                        <label class="block font-label-md mb-2">Description</label>
                        <textarea id="edit_category_description" name="description" class="w-full px-4 py-2 border border-outline-variant/50 rounded-xl h-24"></textarea>
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="edit_category_status" name="status" type="checkbox" value="1" class="rounded w-5 h-5 text-primary border-outline focus:ring-primary">
                        <label class="font-label-md">Active Category</label>
                    </div>
                    <div class="flex justify-end gap-md pt-4">
                        <button onclick="toggleModal('categoryEditModal')" type="button" class="px-6 py-2 rounded-xl border border-outline-variant/50">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded-xl font-bold">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Add Size -->
    <div class="fixed inset-0 z-50 hidden bg-on-surface/40 backdrop-blur-sm flex items-center justify-center p-4" id="sizeModal">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <div class="px-xl py-lg bg-surface-container-low border-b border-outline-variant/30 flex justify-between items-center">
                <h3 class="font-headline-md text-headline-md">Add Size</h3>
                <button type="button" onclick="toggleModal('sizeModal')" class="p-2 hover:bg-surface-container rounded-full">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-xl space-y-lg">
                <form action="/admin/size/store" method="POST" class="space-y-lg">
                    @csrf
                    <div>
                        <label class="block mb-2 font-medium">Name</label>
                        <input type="text" name="name" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none" placeholder="e.g. M, L, XL" required>
                    </div>
                    <div>
                        <label class="block mb-2 font-medium">Volume (ml)</label>
                        <input type="number" name="volume_ml" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none" placeholder="e.g. 500">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none" placeholder="Optional description"></textarea>
                    </div>
                    <div class="pt-4 border-t flex justify-end gap-3">
                        <button onclick="toggleModal('sizeModal')" type="button" class="px-6 py-2 rounded-xl border">Cancel</button>
                        <button type="submit" class="px-8 py-2 rounded-xl bg-green-600 text-white">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Edit Size -->
    <div class="fixed inset-0 z-50 hidden bg-on-surface/40 backdrop-blur-sm flex items-center justify-center p-4" id="sizeEditModal">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <div class="px-xl py-lg bg-surface-container-low border-b border-outline-variant/30 flex justify-between items-center">
                <h3 class="font-headline-md text-headline-md">Edit Size</h3>
                <button type="button" onclick="toggleModal('sizeEditModal')" class="p-2 hover:bg-surface-container rounded-full">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-xl space-y-lg">
                <form id="edit_size_form" action="" method="POST" class="space-y-lg">
                    @csrf
                    <div>
                        <label class="block mb-2 font-medium">Name</label>
                        <input id="edit_size_name" type="text" name="name" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block mb-2 font-medium">Volume (ml)</label>
                        <input id="edit_size_volume" type="number" name="volume_ml" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium">Description</label>
                        <textarea id="edit_size_description" name="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-500 outline-none"></textarea>
                    </div>
                    <div class="pt-4 border-t flex justify-end gap-3">
                        <button onclick="toggleModal('sizeEditModal')" type="button" class="px-6 py-2 rounded-xl border">Cancel</button>
                        <button type="submit" class="px-8 py-2 rounded-xl bg-green-600 text-white">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSizeRow(sizeId) {
            const isActive = document.getElementById('ps_active_' + sizeId).checked;
            document.getElementById('ps_sell_' + sizeId).disabled = !isActive;
            document.getElementById('ps_cost_' + sizeId).disabled = !isActive;
            document.getElementById('ps_default_' + sizeId).disabled = !isActive;
            if (!isActive) {
                document.getElementById('ps_sell_' + sizeId).value = '';
                document.getElementById('ps_cost_' + sizeId).value = '';
                document.getElementById('ps_default_' + sizeId).checked = false;
            }
        }

        function openProductSizeModal(btn) {
            const id = btn.getAttribute('data-id');
            let sizes = [];
            try { sizes = JSON.parse(btn.getAttribute('data-sizes')); } catch(e) {}

            document.getElementById('product_size_form').action = `/admin/product/${id}/sizes`;

            // Reset all first
            const allSizeIds = {!! json_encode($sizes->pluck('id')) !!};
            allSizeIds.forEach(sId => {
                const chk = document.getElementById('ps_active_' + sId);
                if (chk) {
                    chk.checked = false;
                    toggleSizeRow(sId);
                }
            });

            // Populate existing
            sizes.forEach(ps => {
                const chk = document.getElementById('ps_active_' + ps.size_id);
                if (chk) {
                    chk.checked = true;
                    toggleSizeRow(ps.size_id);
                    document.getElementById('ps_sell_' + ps.size_id).value = Number(ps.selling_price);
                    document.getElementById('ps_cost_' + ps.size_id).value = Number(ps.cost_price);
                    if (ps.is_default) {
                        document.getElementById('ps_default_' + ps.size_id).checked = true;
                    }
                }
            });

            document.querySelectorAll('.product-action-menu').forEach(m => m.classList.add('hidden'));
            const modal = document.getElementById('productSizeManageModal');
            if (modal) modal.classList.remove('hidden');
        }

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

            document.querySelectorAll('.tab-btn').forEach(btn => {
                if (btn.getAttribute('data-tab') === tabId) {
                    btn.classList.add('active-tab', 'bg-white', 'shadow-sm', 'text-primary');
                    btn.classList.remove('text-on-surface-variant', 'hover:text-on-surface');
                } else {
                    btn.classList.remove('active-tab', 'bg-white', 'shadow-sm', 'text-primary');
                    btn.classList.add('text-on-surface-variant', 'hover:text-on-surface');
                }
            });
        }

        function openProductActions(button) {
            if (!button) return;
            const row = button.closest('tr');
            if (!row) return;

            const menu = row.querySelector('.product-action-menu');
            
            document.querySelectorAll('.product-action-menu').forEach(m => {
                if (m !== menu) m.classList.add('hidden');
            });
            
            if (menu) menu.classList.toggle('hidden');
        }

        function openCategoryActions(button) {
            if (!button) return;
            document.querySelectorAll('.category-action-menu').forEach(menu => {
                if (menu !== button.nextElementSibling) {
                    menu.classList.add('hidden');
                }
            });
            const menu = button.nextElementSibling;
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        function openCategoryEditModalFromButton(button) {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const description = button.getAttribute('data-description');
            const image = button.getAttribute('data-image');
            const status = button.getAttribute('data-status');

            document.getElementById('categoryEditForm').action = '/admin/category/' + id + '/update';
            document.getElementById('edit_category_name').value = name || '';
            document.getElementById('edit_category_description').value = description || '';
            document.getElementById('edit_category_image').value = image || '';
            document.getElementById('edit_category_status').checked = (status == 1);

            document.querySelectorAll('.category-action-menu').forEach(m => m.classList.add('hidden'));
            const modal = document.getElementById('categoryEditModal');
            if (modal) modal.classList.remove('hidden');
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

        function openSizeActions(button) {
            if (!button) return;
            const row = button.closest('tr');
            if (!row) return;

            const menu = row.querySelector('.size-action-menu');
            if (!menu) return;

            // Close others
            document.querySelectorAll('.size-action-menu').forEach(m => {
                if (m !== menu) m.classList.add('hidden');
            });

            menu.classList.toggle('hidden');
        }

        function openSizeEditModalFromButton(btn) {
            const row = btn.closest('tr');
            if (!row) return;
            const moreBtn = row.querySelector('.action-more-size');
            if (!moreBtn) return;

            const modal = document.getElementById('sizeEditModal');
            if (!modal) return;

            const id = moreBtn.getAttribute('data-id');
            document.getElementById('edit_size_name').value = moreBtn.getAttribute('data-name') || '';
            document.getElementById('edit_size_volume').value = moreBtn.getAttribute('data-volume') || '';
            document.getElementById('edit_size_description').value = moreBtn.getAttribute('data-description') || '';
            
            document.getElementById('edit_size_form').action = `/admin/size/${id}/update`;

            document.querySelectorAll('.size-action-menu').forEach(m => m.classList.add('hidden'));
            modal.classList.remove('hidden');
        }

        // Close action menu on outside click
        document.addEventListener('click', (e) => {
            const menu = e.target?.closest?.('.product-action-menu');
            const more = e.target?.closest?.('.action-more');
            if (!menu && !more) {
                document.querySelectorAll('.product-action-menu').forEach(m => m.classList.add('hidden'));
            }
            
            const sizeMenu = e.target?.closest?.('.size-action-menu');
            const sizeMore = e.target?.closest?.('.action-more-size');
            if (!sizeMenu && !sizeMore) {
                document.querySelectorAll('.size-action-menu').forEach(m => m.classList.add('hidden'));
            }

            const catMenu = e.target?.closest?.('.category-action-menu');
            const catMore = e.target?.closest?.('button[onclick*="openCategoryActions"]');
            if (!catMenu && !catMore) {
                document.querySelectorAll('.category-action-menu').forEach(m => m.classList.add('hidden'));
            }
        });
    </script>
</main>
@endsection

