@extends('admin.dashboard')

@section('content')
@php
    $totalProducts = $products->count();
    $activeProducts = $products->filter(fn($product) => ($product->is_active ?? false) || ($product->status ?? '') === 'active')->count();
    $inactiveProducts = $totalProducts - $activeProducts;
    $categoryCount = $products->pluck('category_id')->filter()->unique()->count();
@endphp

<div class="space-y-6">
    <div class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-[0_20px_60px_-30px_rgba(15,23,42,0.35)] sm:p-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Inventory</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Все товары</h2>
            </div>

            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800">
                + Добавить товар
            </a>
        </div>
    </div>
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700" role="alert">
            {{ session('success') }}
        </div>
    @endif
    

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Всего</div>
            <div class="mt-4 text-3xl font-bold text-slate-900">{{ $totalProducts }}</div>
            <div class="mt-2 text-sm text-slate-500">Товаров в каталоге</div>
        </div>

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Активные</div>
            <div class="mt-4 text-3xl font-bold text-emerald-700">{{ $activeProducts }}</div>
            <div class="mt-2 text-sm text-emerald-700/80">Показываются покупателям</div>
        </div>

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 shadow-sm">
            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-600">Неактивные</div>
            <div class="mt-4 text-3xl font-bold text-rose-700">{{ $inactiveProducts }}</div>
            <div class="mt-2 text-sm text-rose-700/80">Требуют внимания</div>
        </div>

        <div class="rounded-2xl border border-violet-200 bg-violet-50 p-4 shadow-sm">
            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-600">Категории</div>
            <div class="mt-4 text-3xl font-bold text-violet-700">{{ $categoryCount }}</div>
            <div class="mt-2 text-sm text-violet-700/80">Разделов каталога</div>
        </div>
    </div>

    @if($products->isNotEmpty())
        <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_20px_60px_-30px_rgba(15,23,42,0.30)]">
            <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                <div class="flex items-center justify-between gap-3">
                    <div class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Каталог</div>
                    <span class="rounded-full bg-slate-900 px-2.5 py-1 text-xs font-medium text-white">{{ $totalProducts }} items</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">#</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Название</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Цена</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Категория</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Статус</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach($products as $product)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-5 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-900">{{ $product->name }}</div>
                                    @if($product->slug)
                                        <div class="mt-1 text-xs text-slate-500">{{ $product->slug }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-semibold text-slate-900">{{ number_format($product->price ?? 0, 2, ',', ' ') }} ₽</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">
                                        {{ $product->category->name ?? 'Без категории' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100">
                                        <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                                        Изменить
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($products->hasPages())
                    <div class="px-5 py-4">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="rounded-[28px] border border-dashed border-slate-300 bg-slate-50 p-10 text-center shadow-sm">
            <div class="text-lg font-semibold text-slate-700">Товары не найдены.</div>
            <p class="mt-2 text-sm text-slate-500">Добавьте первый товар, чтобы начать наполнение каталога.</p>
        </div>
    @endif
</div>
@endsection