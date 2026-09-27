{{--
    Список заказов, сгруппированный по датам.
    Ожидает $orders (paginator или collection), отсортированный по created_at DESC.
    Путь к карточке поправьте под свой проект.
--}}
@php
    $collection = method_exists($orders, 'getCollection') ? $orders->getCollection() : collect($orders);

    // Группируем по дню создания (в часовом поясе приложения)
    $groups = $collection->groupBy(fn ($o) => $o->created_at?->format('Y-m-d') ?? 'unknown');

    $today = now()->startOfDay();
@endphp

<div class="space-y-8">
    @forelse($groups as $dateKey => $dayOrders)
        @php
            $date = $dateKey === 'unknown' ? null : \Illuminate\Support\Carbon::parse($dateKey)->locale('ru');

            if (!$date) {
                $title = 'Без даты';
                $subtitle = null;
            } elseif ($date->isSameDay($today)) {
                $title = 'Сегодня';
                $subtitle = $date->isoFormat('D MMMM, dddd');
            } elseif ($date->isSameDay($today->copy()->subDay())) {
                $title = 'Вчера';
                $subtitle = $date->isoFormat('D MMMM, dddd');
            } else {
                $title = $date->isoFormat('D MMMM YYYY');
                $subtitle = $date->isoFormat('dddd');
            }

            // Сумма за день — без отменённых заказов
            $activeOrders = $dayOrders->reject(fn ($o) => strtolower((string) $o->status) === 'cancelled');
            $daySum = $activeOrders->sum('total_price');
            $pendingCount = $dayOrders->filter(fn ($o) => in_array(strtolower((string) $o->status), ['pending', 'processing'], true))->count();
        @endphp

        <section aria-labelledby="day-{{ $dateKey }}">
            {{-- Заголовок дня: прилипает при прокрутке --}}
            <div class="sticky top-0 z-10 -mx-1 mb-4 flex flex-col gap-1 rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 backdrop-blur sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-baseline gap-3">
                    <h2 id="day-{{ $dateKey }}" class="text-lg font-bold text-slate-900">{{ $title }}</h2>
                    @if($subtitle)
                        <span class="text-sm capitalize text-slate-500">{{ $subtitle }}</span>
                    @endif
                </div>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate-600">
                    <span>Заказов: <b class="text-slate-900">{{ $dayOrders->count() }}</b></span>
                    @if($pendingCount > 0)
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">
                            Требуют внимания: {{ $pendingCount }}
                        </span>
                    @endif
                    <span>Сумма: <b class="text-slate-900">{{ number_format($daySum, 0, '.', ' ') }} ₸</b></span>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($dayOrders as $order)
                    @include('admin.partials.card', ['order' => $order])
                @endforeach
            </div>
        </section>
    @empty
        <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500">
            Заказов пока нет
        </div>
    @endforelse

    @if(method_exists($orders, 'links'))
        <div>{{ $orders->links() }}</div>
    @endif
</div>