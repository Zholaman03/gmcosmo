@extends('admin.dashboard')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <a href="{{ route('admin.products') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-indigo-600">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.56l3.22 3.22a.75.75 0 1 1-1.06 1.06l-4.5-4.5a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 1 1 1.06 1.06l-3.22 3.22h10.69A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                </svg>
                Все товары
            </a>
            <h2 class="mt-3 text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Добавить товар</h2>
            <p class="mt-1 text-sm text-gray-500">Заполните основные сведения о новом продукте.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700">
            <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
            Новый продукт
        </span>
    </div>

    @if($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
        <p class="font-semibold">Проверьте заполнение формы</p>
        <ul class="mt-2 list-inside list-disc space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        @csrf
        <div class="grid gap-0 lg:grid-cols-[1fr_0.82fr]">
            <div class="space-y-6 p-5 sm:p-8">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Информация о товаре</h3>
                    <p class="mt-1 text-sm text-gray-500">Эти данные будут отображаться в каталоге.</p>
                </div>

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Название товара <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus placeholder="Например, Увлажняющий крем" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-medium text-gray-700">Описание</label>
                    <textarea name="description" id="description" rows="6" placeholder="Расскажите о свойствах и преимуществах товара" class="block w-full resize-y rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">{{ old('description') }}</textarea>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="price" class="mb-2 block text-sm font-medium text-gray-700">Цена <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required placeholder="0.00" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 pr-12 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-sm text-gray-400">₸</span>
                        </div>
                    </div>
                    <div>
                        <label for="category_id" class="mb-2 block text-sm font-medium text-gray-700">Категория <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id" required class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                            <option value="">Выберите категорию</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id')==$category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 bg-gray-50/70 p-5 sm:p-8 lg:border-l lg:border-t-0">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Изображение</h3>
                    <p class="mt-1 text-sm text-gray-500">Добавьте четкое изображение товара.</p>
                </div>
                <div class="relative w-full max-w-md">
                    
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

                    <!-- Жүктелген суретті көрсететін блок (бастапқыда жасырулы) -->
                    <div id="preview-container" class="hidden mt-6 relative min-h-56 w-full overflow-hidden rounded-xl border border-gray-200">
                        <img id="preview-image" src="" alt="Жүктелген сурет" class="h-56 w-full object-cover">

                        <!-- Өшіру батырмасы -->
                        <button type="button" id="remove-btn" class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-gray-900/60 text-white backdrop-blur transition hover:bg-red-600">
                            ✕
                        </button>
                    </div>
                </div>
                <p class="mt-4 text-xs leading-5 text-gray-500">Рекомендуемый размер: 1200 × 1200 px. Изображение можно пропустить.</p>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-gray-200 bg-gray-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-8">
            <a href="{{ route('admin.products') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-100">Отмена</a>
            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20">Создать товар</button>
        </div>
    </form>
</div>
@endsection