@extends('admin.dashboard')

@section('content')
@php
    $totalOrders = $orders->count();
    $totalRevenue = $orders->sum('total_price');
    $pendingOrders = $orders->filter(fn($order) => strtolower((string) ($order->status ?? '')) !== 'completed')->count();
    $completedOrders = $totalOrders - $pendingOrders;
@endphp

<div class="space-y-6">
    <div class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-[0_20px_60px_-30px_rgba(15,23,42,0.35)] sm:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Orders</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Заказы</h2>
            </div>

            <div class="flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600">
                <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                {{ $totalOrders }} активных заказов
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Всего</div>
            <div class="mt-4 text-3xl font-bold text-slate-900">{{ $totalOrders }}</div>
            <div class="mt-2 text-sm text-slate-500">Общее число заказов</div>
        </div>

        <div class="rounded-2xl border border-violet-200 bg-violet-50 p-4 shadow-sm">
            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-600">Выручка</div>
            <div class="mt-4 text-3xl font-bold text-violet-700">{{ number_format($totalRevenue, 0, '.', ' ') }}</div>
            <div class="mt-2 text-sm text-violet-700/80">₸ суммарно</div>
        </div>

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600">В работе</div>
            <div class="mt-4 text-3xl font-bold text-amber-700">{{ $pendingOrders }}</div>
            <div class="mt-2 text-sm text-amber-700/80">Требуют обработки</div>
        </div>

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Завершены</div>
            <div class="mt-4 text-3xl font-bold text-emerald-700">{{ $completedOrders }}</div>
            <div class="mt-2 text-sm text-emerald-700/80">Успешные продажи</div>
        </div>
    </div>

    @forelse($orders as $order)
        @php
            $status = strtolower((string) ($order->status ?? 'pending'));
            $statusClasses = [
                'pending' => 'bg-amber-100 text-amber-700 border border-amber-200',
                'processing' => 'bg-blue-100 text-blue-700 border border-blue-200',
                'completed' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                'cancelled' => 'bg-rose-100 text-rose-700 border border-rose-200',
                'default' => 'bg-slate-100 text-slate-700 border border-slate-200',
            ];
            $statusLabel = match($status) {
                'pending' => 'В ожидании',
                'processing' => 'В обработке',
                'completed' => 'Завершён',
                'cancelled' => 'Отменён',
                default => ucfirst($status),
            };
        @endphp

        <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_20px_60px_-30px_rgba(15,23,42,0.28)]">
            <div class="border-b border-slate-200 bg-slate-50 px-5 py-4 sm:px-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="text-lg font-bold text-slate-900">Заказ #{{ $order->id }}</span>
                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$status] ?? $statusClasses['default'] }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="flex flex-col gap-2 text-sm text-slate-600 sm:flex-row sm:items-center sm:gap-5">
                        <div><span class="font-medium text-slate-500">Клиент:</span> {{ $order->name }}</div>
                        <div><span class="font-medium text-slate-500">Тел:</span> <a href="https://wa.me/{{ $order->phone }}" target="_blank" class="text-blue-500 hover:underline">{{ $order->phone }}</a></div>
                        <div><span class="font-medium text-slate-500">Кол-во:</span> {{ $order->total_quantity }}</div>
                        <div class="text-lg font-bold text-slate-900">{{ number_format($order->total_price, 0, '.', ' ') }} ₸</div>
                    </div>
                </div>
            </div>

            <div class="p-5 sm:p-6">
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-slate-50/70 p-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('images/images.jpg') }}"
                                     alt="{{ $item->product->name ?? 'Товар' }}"
                                     class="h-16 w-16 rounded-2xl object-cover border border-slate-200 bg-white shadow-sm sm:h-20 sm:w-20">

                                <div class="min-w-0">
                                    <h4 class="truncate text-base font-semibold text-slate-900">
                                        {{ $item->product->name ?? 'Без названия' }}
                                    </h4>
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ number_format($item->price, 0, '.', ' ') }} ₸ × {{ $item->quantity }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-left text-sm font-semibold text-slate-700 sm:text-right">
                                {{ number_format($item->price * $item->quantity, 0, '.', ' ') }} ₸
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 flex flex-col gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-sm text-slate-500">
                        Сумма заказа: <span class="font-semibold text-slate-900">{{ number_format($order->total_price, 0, '.', ' ') }} ₸</span>
                    </div>

                    <form action="{{ route('admin.orders.delete', $order->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот заказ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-600 transition hover:bg-rose-100">
                            Удалить заказ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="rounded-[28px] border border-dashed border-slate-300 bg-slate-50 p-10 text-center shadow-sm">
            <div class="text-lg font-semibold text-slate-700">Нет доступных заказов.</div>
            <p class="mt-2 text-sm text-slate-500">Новых заказов пока не поступало.</p>
        </div>
    @endforelse
</div>
@endsection