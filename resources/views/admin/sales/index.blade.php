@extends('admin.dashboard')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-600">Распродажи</p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight text-gray-900">Продажи</h1>
                <p class="mt-2 text-sm text-gray-500">Управляйте вашими продуктами и периодами распродаж.</p>
            </div>
            <a href="{{ route('admin.sales.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10 4a.75.75 0 0 1 .75.75v4.5h4.5a.75.75 0 0 1 0 1.5h-4.5v4.5a.75.75 0 0 1-1.5 0v-4.5h-4.5a.75.75 0 0 1 0-1.5h4.5v-4.5A.75.75 0 0 1 10 4Z" /></svg>
                Создать распродажу
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
                {{ session('success') }}
            </div>
        @endif

        <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col gap-1 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-base font-semibold text-gray-900">Все распродажи</h2>
                <p class="text-sm text-gray-500">{{ $sales->total() }} {{ \Illuminate\Support\Str::plural('распродажа', $sales->total()) }}</p>
            </div>

            @if($sales->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">ID</th>
                                <th scope="col" class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Продукт</th>
                                <th scope="col" class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Скидка</th>
                                <th scope="col" class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Начало</th>
                                <th scope="col" class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Окончание</th>
                                <th scope="col" class="whitespace-nowrap px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Статус</th>
                                <th scope="col" class="whitespace-nowrap px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($sales as $sale)
                                <tr class="transition hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500">#{{ $sale->id }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-gray-900">{{ $sale->product_name }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-gray-900">{{ $sale->discount_percent }}%</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">{{ $sale->start_date }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">{{ $sale->end_date }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm">
                                        @if($sale->is_active)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Активна</span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600"><span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>Неактивна</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.sales.edit', $sale->id) }}" class="rounded-md px-3 py-1.5 font-medium text-indigo-600 transition hover:bg-indigo-50 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Редактировать</a>
                                            <form action="{{ route('admin.sales.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this sale?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md px-3 py-1.5 font-medium text-rose-600 transition hover:bg-rose-50 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500">Удалить</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($sales->hasPages())
                    <div class="border-t border-gray-200 px-5 py-4">{{ $sales->links() }}</div>
                @endif
            @else
                <div class="px-6 py-16 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" /></svg>
                    </div>
                    <h3 class="mt-4 text-sm font-semibold text-gray-900">Продажи еще не созданы</h3>
                    <p class="mt-1 text-sm text-gray-500">Создайте продажу, чтобы начать продвигать продукт.</p>
                    <a href="{{ route('admin.sales.create') }}" class="mt-5 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Создать продажу</a>
                </div>
            @endif
        </section>
    </div>
@endsection