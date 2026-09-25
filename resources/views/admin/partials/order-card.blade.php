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
    $paymentStatus = strtolower((string) ($order->payment_status ?? 'unpaid'));
    $paymentLabel = match($paymentStatus) {
        'paid', 'succeeded', 'success' => 'Оплачено',
        'pending', 'processing' => 'Ожидает оплаты',
        default => 'Не оплачено',
    };
    $paymentClasses = in_array($paymentStatus, ['paid', 'succeeded', 'success'], true)
        ? 'bg-emerald-50 text-emerald-700'
        : 'bg-amber-50 text-amber-700';
@endphp

<div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_20px_60px_-30px_rgba(15,23,42,0.28)]">
    <div class="border-b border-slate-200 bg-slate-50 px-5 py-4 sm:px-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-lg font-bold text-slate-900">Заказ #{{ $order->id }}</span>
                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$status] ?? $statusClasses['default'] }}">{{ $statusLabel }}</span>
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
        <div class="mb-5 grid gap-3 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 text-sm sm:grid-cols-2 xl:grid-cols-4">
            <div>
                <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Адрес доставки</div>
                <div class="mt-1 font-medium text-slate-800">{{ $order->address ?: 'Не указан' }}</div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Способ доставки</div>
                <div class="mt-1 font-medium text-slate-800">{{ $order->deliveryMethod?->name ?: 'Не указан' }}</div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Оплата</div>
                <div class="mt-1"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $paymentClasses }}">{{ $paymentLabel }}</span></div>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Создан</div>
                <div class="mt-1 font-medium text-slate-800">{{ $order->created_at?->format('d.m.Y, H:i') ?: '—' }}</div>
            </div>
        </div>

        <div class="space-y-4">
            @foreach($order->items as $item)
                <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-slate-50/70 p-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/images.jpg') }}" alt="{{ $item->product->name ?? 'Товар' }}" class="h-16 w-16 rounded-2xl object-cover border border-slate-200 bg-white shadow-sm sm:h-20 sm:w-20">
                        <div class="min-w-0">
                            <h4 class="truncate text-base font-semibold text-slate-900">{{ $item->product->name ?? 'Без названия' }}</h4>
                            <p class="mt-1 text-sm text-slate-500">{{ number_format($item->price, 0, '.', ' ') }} ₸ × {{ $item->quantity }}</p>
                        </div>
                    </div>
                    <div class="text-left text-sm font-semibold text-slate-700 sm:text-right">{{ number_format($item->price * $item->quantity, 0, '.', ' ') }} ₸</div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 flex flex-col gap-4 border-t border-slate-200 pt-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="text-sm text-slate-500">Сумма заказа: <span class="font-semibold text-slate-900">{{ number_format($order->total_price, 0, '.', ' ') }} ₸</span></div>
            @if(!in_array($status, ['completed', 'cancelled'], true))
                <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:justify-end">
                    <form action="{{ route('admin.orders.complete', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700 sm:w-auto"><span aria-hidden="true">✓</span> Завершить</button>
                    </form>
                    <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Отменить заказ #{{ $order->id }}?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 sm:w-auto"><span aria-hidden="true">×</span> Отменить</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
