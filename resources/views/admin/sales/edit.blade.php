@extends('admin.dashboard')

@section('content')
    <div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Управление продажами</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Редактировать акцию</h1>
                <p class="mt-2 text-sm text-slate-600">Обновите детали предложения, график и доступность.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800" role="alert">
                    <p class="font-semibold">Пожалуйста, исправьте следующие проблемы:</p>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.sales.update', $sale->id) }}" method="POST" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                @csrf
                @method('PUT')

                <div class="space-y-8 p-6 sm:p-8">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Детали предложения</h2>
                        <p class="mt-1 text-sm text-slate-500">Укажите продукт и скидку, которые увидят покупатели.</p>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="product_name" class="mb-2 block text-sm font-medium text-slate-700">Название продукта</label>
                            <input type="text" id="product_name" name="product_name" value="{{ old('product_name', $sale->product_name) }}" required class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                        </div>
                        <div>
                            <label for="discount_percent" class="mb-2 block text-sm font-medium text-slate-700">Процент скидки</label>
                            <div class="relative">
                                <input type="number" id="discount_percent" name="discount_percent" value="{{ old('discount_percent', $sale->discount_percent) }}" min="0" max="100" required class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 pr-10 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-sm text-slate-500">%</span>
                            </div>
                            <p class="mt-1.5 text-xs text-slate-500">Введите значение от 0 до 100.</p>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-7">
                        <h2 class="text-lg font-semibold text-slate-900">Расписание акции</h2>
                        <p class="mt-1 text-sm text-slate-500">Выберите, когда это предложение будет доступно.</p>
                        <div class="mt-5 grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="start_date" class="mb-2 block text-sm font-medium text-slate-700">Дата начала</label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $sale->start_date) }}" class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                            </div>
                            <div>
                                <label for="end_date" class="mb-2 block text-sm font-medium text-slate-700">Дата окончания</label>
                                <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $sale->end_date) }}" class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 rounded-xl bg-slate-50 p-4">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $sale->is_active) ? 'checked' : '' }} class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <label for="is_active" class="text-sm font-medium text-slate-900">Активная акция</label>
                            <p class="mt-1 text-sm text-slate-500">Когда включено, эта акция доступна клиентам.</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end sm:px-8">
                    <button type="submit" class="inline-flex justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20">Сохранить изменения</button>
                </div>
            </form>

            <section class="mt-8 flex flex-col gap-4 rounded-2xl border border-red-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-8" aria-labelledby="delete-sale-heading">
                <div>
                    <h2 id="delete-sale-heading" class="font-semibold text-slate-900">Удалить эту акцию</h2>
                    <p class="mt-1 text-sm text-slate-500">Это действие является постоянным и его нельзя отменить.</p>
                </div>
                <form action="{{ route('admin.sales.destroy', $sale->id) }}" method="POST" class="shrink-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Вы уверены, что хотите удалить эту акцию?')" class="inline-flex w-full justify-center rounded-lg border border-red-300 px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-4 focus:ring-red-500/10 sm:w-auto">Удалить акцию</button>
                </form>
            </section>
        </div>
    </div>
@endsection