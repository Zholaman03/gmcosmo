@extends('admin.dashboard')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-6xl">
        <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <a href="{{ route('admin.products') }}" class="mb-3 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-indigo-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Назад
                </a>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Изменить товар</h1>
                <p class="mt-1 text-sm text-slate-500">Обновить информацию о товаре</p>
            </div>
            <form action="{{ route('admin.products.destroy', $product->id ?? $product) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот товар?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-xl border border-red-200 px-5 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Удалить товар</button>
            </form>
        </div>

        @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Пожалуйста, исправьте следующие ошибки:</p>
            <ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('admin.products.update', $product->id ?? $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <h2 class="text-lg font-semibold text-slate-900">Информация о продукте</h2>
                        <p class="mb-6 mt-1 text-sm text-slate-500">Используйте ясное название и описание, чтобы помочь клиентам найти ваш продукт.</p>
                        <div class="space-y-5">
                            <div>
                                <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Название <span class="text-red-500">*</span></label>
                                <input id="name" name="name" type="text" required value="{{ old('name', $product->name ?? '') }}" placeholder="e.g. Premium skincare set" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm outline-none transition focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="description" class="mb-2 block text-sm font-medium text-slate-700">Описание</label>
                                <textarea id="description" name="description" rows="6" placeholder="Tell customers about this product..." class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm outline-none transition focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $product->description ?? '') }}</textarea>
                            </div>
                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="category" class="mb-2 block text-sm font-medium text-slate-700">Категория</label>
                                    <select id="category" name="category_id" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm outline-none transition focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Выберите категорию</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <h2 class="text-lg font-semibold text-slate-900">Цена</h2>
                        <p class="mb-6 mt-1 text-sm text-slate-500">Дайте вашему продукту правильную цену</p>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div><label for="price" class="mb-2 block text-sm font-medium text-slate-700">Цена<span class="text-red-500">*</span></label>
                                <div class="flex overflow-hidden rounded-xl border border-slate-300 focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500"><span class="bg-slate-50 px-4 py-3 text-sm text-slate-500">$</span><input id="price" name="price" type="number" step="0.01" min="0" required value="{{ old('price', $product->price ?? '') }}" class="w-full border-0 px-3 py-3 text-sm outline-none focus:ring-0"></div>
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="space-y-6">
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-slate-900">Фото продукта</h2>
                        <p class="mb-4 mt-1 text-sm text-slate-500">JPG, PNG or WEBP up to 5MB.</p>
                        <label for="image" id="upload-label" class="mt-6 flex min-h-56 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-white px-5 text-center transition hover:border-indigo-400 hover:bg-indigo-50/40">
                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5V19a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2.5M16 8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </span>
                            <span class="mt-4 text-sm font-semibold text-gray-800">Загрузить изображение</span>
                            <span class="mt-1 text-xs leading-5 text-gray-500">PNG, JPG, GIF или SVG<br>до 2 МБ</span>
                            <!-- multiple атрибуты жоқ, сондықтан ТЕК БІР сурет жүктеледі -->
                            <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/gif,image/svg+xml" class="sr-only">
                        </label>
                        <div id="preview-container" class="hidden mt-6 relative min-h-56 w-full overflow-hidden rounded-xl border border-gray-200">
                            <img id="preview-image" src="" alt="Жүктелген сурет" class="h-56 w-full object-cover">

                            <!-- Өшіру батырмасы -->
                            <button type="button" id="remove-btn" class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-gray-900/60 text-white backdrop-blur transition hover:bg-red-600">
                                ✕
                            </button>
                        </div>
                    </section>
                    <section>
                        @if (!empty($product->image))
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-900">Текущее изображение</h2>
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name ?? 'Изображение товара' }}" class="mt-4 h-56 w-full rounded-xl object-cover">
                        </div>
                        @endif
                    </section>
                </aside>
            </div>
            <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-200 pt-6 sm:flex-row"><a href="{{ url()->previous() }}" class="rounded-xl border border-slate-300 px-6 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Отменить</a><button type="submit" class="rounded-xl bg-indigo-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Сохранить</button></div>
        </form>
    </div>
</div>
@endsection