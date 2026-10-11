@extends('admin.dashboard')

@section('content')
    <div class="min-h-screen bg-slate-50 px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Управление продажами</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Создать распродажу</h1>
                <p class="mt-2 text-sm text-slate-600">Установите скидку и период действия этой акции.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800" role="alert">
                    <p class="font-semibold">Пожалуйста, исправьте следующие ошибки:</p>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.sales.store') }}" method="POST" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                @csrf
                <div class="space-y-6 p-6 sm:p-8">
                    <div>
                        <label for="product_name" class="mb-2 block text-sm font-semibold text-slate-700">Название товара</label>
                        <input type="text" id="product_name" name="product_name" value="{{ old('product_name') }}" required placeholder="например, Летняя коллекция"
                               class="block w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                    <div>
                        <label for="discount_percent" class="mb-2 block text-sm font-semibold text-slate-700">Процент скидки</label>
                        <div class="relative">
                            <input type="number" id="discount_percent" name="discount_percent" value="{{ old('discount_percent') }}" min="0" max="100" required placeholder="20"
                                   class="block w-full rounded-lg border border-slate-300 px-4 py-3 pr-12 text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                            <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-sm font-medium text-slate-500">%</span>
                        </div>
                        <p class="mt-2 text-xs text-slate-500">Выберите значение от 0 до 100.</p>
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="start_date" class="mb-2 block text-sm font-semibold text-slate-700">Дата начала <span class="font-normal text-slate-500">(необязательно)</span></label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                                   class="block w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div>
                            <label for="end_date" class="mb-2 block text-sm font-semibold text-slate-700">Дата окончания <span class="font-normal text-slate-500">(необязательно)</span></label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                                   class="block w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                    </div>
                    <label for="is_active" class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span>
                            <span class="block text-sm font-semibold text-slate-800">Акция активна</span>
                            <span class="mt-1 block text-sm text-slate-500">Клиенты могут использовать эту акцию во время её активности.</span>
                        </span>
                    </label>
                </div>
                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end sm:px-8">
                    <a href="{{ route('admin.sales') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Отмена</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Создать распродажу</button>
                </div>
            </form>
        </div>
    </div>
@endsection