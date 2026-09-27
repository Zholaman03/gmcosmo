@php
    $status = strtolower((string) ($order->status ?? 'pending'));
    $statusClasses = [
        'pending' => 'bg-amber-100 text-amber-800 ring-amber-200',
        'processing' => 'bg-blue-100 text-blue-800 ring-blue-200',
        'completed' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        'cancelled' => 'bg-rose-100 text-rose-800 ring-rose-200',
        'default' => 'bg-slate-100 text-slate-700 ring-slate-200',
    ];
    $statusLabel = match($status) {
        'pending' => 'В ожидании',
        'processing' => 'В обработке',
        'completed' => 'Завершён',
        'cancelled' => 'Отменён',
        default => ucfirst($status),
    };

    $paymentStatus = strtolower((string) ($order->payment_status ?? 'unpaid'));
    $isPaid = in_array($paymentStatus, ['paid', 'succeeded', 'success'], true);
    $isPaymentPending = in_array($paymentStatus, ['pending', 'processing'], true);
    $paymentLabel = $isPaid ? 'Оплачено' : ($isPaymentPending ? 'Ожидает оплаты' : 'Не оплачено');
    $paymentClasses = $isPaid
        ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
        : 'bg-amber-50 text-amber-700 ring-amber-200';
    $paymentDot = $isPaid ? 'bg-emerald-500' : 'bg-amber-500';

    // wa.me принимает только цифры (без +, пробелов и скобок)
    $phoneDigits = preg_replace('/\D+/', '', (string) $order->phone);
    $isFinal = in_array($status, ['completed', 'cancelled'], true);
    $itemsCount = $order->items->count();
@endphp

<article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

    {{-- ШАПКА: номер, статус, дата и главная сумма --}}
    <header class="flex flex-col gap-3 border-b border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div class="flex flex-wrap items-center gap-x-3 gap-y-2">
            <h3 class="text-lg font-bold text-slate-900">Заказ #{{ $order->id }}</h3>
            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusClasses[$status] ?? $statusClasses['default'] }}">
                {{ $statusLabel }}
            </span>
            <span class="text-sm text-slate-500">{{ $order->created_at?->format('d.m.Y, H:i') ?: '—' }}</span>
        </div>
        <div class="flex items-baseline gap-2 sm:flex-col sm:items-end sm:gap-0">
            <span class="text-xs uppercase tracking-wider text-slate-400">Итого</span>
            <span class="text-2xl font-extrabold text-slate-900">{{ number_format($order->total_price, 0, '.', ' ') }} ₸</span>
        </div>
    </header>

    <div class="space-y-5 p-5 sm:p-6">

        {{-- КЛИЕНТ И КОНТАКТЫ: крупные кнопки «Позвонить» и «WhatsApp» --}}
        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 p-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-slate-900 text-base font-bold text-white" aria-hidden="true">
                    {{ mb_strtoupper(mb_substr((string) $order->name, 0, 1)) ?: '?' }}
                </div>
                <div class="min-w-0">
                    <div class="truncate font-semibold text-slate-900">{{ $order->name }}</div>
                    <div class="text-sm text-slate-500">{{ $order->phone }}</div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 sm:flex">
                <a href="tel:+{{ $phoneDigits }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400">
                    <span aria-hidden="true">📞</span> Позвонить
                </a>
                <a href="https://wa.me/{{ $phoneDigits }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400">
                    <span aria-hidden="true">💬</span> WhatsApp
                </a>
            </div>
        </section>

        {{-- ДОСТАВКА И ОПЛАТА --}}
        <section class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-slate-50 p-4">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Адрес доставки</div>
                <div class="mt-1 break-words font-medium text-slate-800" id="address-{{ $order->id }}">{{ $order->address ?: 'Не указан' }}</div>
                @if($order->address)
                    <button type="button"
                            class="mt-2 text-xs font-semibold text-blue-600 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-400"
                            onclick="navigator.clipboard.writeText(document.getElementById('address-{{ $order->id }}').innerText); this.textContent='Скопировано ✓'; setTimeout(() => this.textContent='Скопировать адрес', 1500);">
                        Скопировать адрес
                    </button>
                @endif
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Способ доставки</div>
                <div class="mt-1 font-medium text-slate-800">{{ $order->deliveryMethod?->name ?: 'Не указан' }}</div>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Оплата</div>
                <div class="mt-1.5">
                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $paymentClasses }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $paymentDot }}"></span>{{ $paymentLabel }}
                    </span>
                </div>
            </div>
        </section>
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Сообщение: Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum perferendis tenetur hic cupiditate sapiente dolores ratione adipisci, deserunt suscipit dolorem quis ab temporibus maiores magnam aut. Id ad aliquam veritatis.</p>
        {{-- ТОВАРЫ: сворачиваются, если позиций много --}}
        <section>
            <details class="group" @if($itemsCount <= 3) open @endif>
                <summary class="flex cursor-pointer list-none items-center justify-between rounded-xl px-1 py-2 text-sm font-semibold text-slate-700 hover:text-slate-900">
                    <span>Товары ({{ $itemsCount }} поз., {{ $order->total_quantity }} шт.)</span>
                    <span class="text-slate-400 transition group-open:rotate-180" aria-hidden="true">▾</span>
                </summary>

                <ul class="mt-2 divide-y divide-slate-100 rounded-2xl border border-slate-200">
                    @foreach($order->items as $item)
                        <li class="flex items-center gap-3 p-3 sm:gap-4">
                            <img src="{{ $item->product->image_url ?? asset('images/images.jpg') }}"
                                 alt="{{ $item->product->name ?? 'Товар' }}"
                                 loading="lazy"
                                 class="h-14 w-14 shrink-0 rounded-xl border border-slate-200 bg-white object-cover sm:h-16 sm:w-16">
                            <div class="min-w-0 flex-1">
                                <div class="line-clamp-2 text-sm font-semibold text-slate-900 sm:text-base">{{ $item->product->name ?? 'Без названия' }}</div>
                                <div class="mt-0.5 text-xs text-slate-500 sm:text-sm">
                                    {{ number_format($item->price, 0, '.', ' ') }} ₸ × {{ $item->quantity }}
                                </div>
                            </div>
                            <div class="shrink-0 text-sm font-bold text-slate-900 sm:text-base">
                                {{ number_format($item->price * $item->quantity, 0, '.', ' ') }} ₸
                            </div>
                        </li>
                    @endforeach
                </ul>
            </details>
        </section>

        {{-- ДЕЙСТВИЯ --}}
        <footer class="flex flex-col gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:items-center sm:justify-between">
            @if($isFinal)
                <div class="rounded-xl px-4 py-2.5 text-sm font-medium {{ $status === 'completed' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                    {{ $status === 'completed' ? '✓ Заказ выполнен' : '× Заказ отменён' }}
                </div>
            @else
                <div class="text-xs text-slate-400">Действия необратимы — потребуется подтверждение</div>
                <div class="flex flex-col-reverse gap-2 sm:flex-row">
                    <form action="{{ route('admin.orders.cancel', $order) }}" method="POST"
                          onsubmit="if(!confirm('Отменить заказ #{{ $order->id }}?')) return false; this.querySelector('button').disabled = true;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-rose-200 bg-white px-5 py-3 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-300 disabled:opacity-50 sm:w-auto">
                            <span aria-hidden="true">×</span> Отменить
                        </button>
                    </form>
                    <form action="{{ route('admin.orders.complete', $order) }}" method="POST"
                          onsubmit="if(!confirm('Завершить заказ #{{ $order->id }}?')) return false; this.querySelector('button').disabled = true;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 disabled:opacity-50 sm:w-auto">
                            <span aria-hidden="true">✓</span> Завершить
                        </button>
                    </form>
                </div>
            @endif
        </footer>
    </div>
</article>