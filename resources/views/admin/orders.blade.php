@extends('admin.dashboard')

@section('content')
@php
    $filterLabels = [
        'active' => 'Все активные',
        'unpaid' => 'Не оплачены',
        'waiting' => 'Ожидают обработки',
        'cancelled' => 'Отменённые',
    ];
@endphp

<div class="space-y-6">
    <div class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-[0_20px_60px_-30px_rgba(15,23,42,0.35)] sm:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Orders</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Заказы</h2>
                <p class="mt-2 text-sm text-slate-500">{{ $filterLabels[$filter] }} · {{ $orders->count() }} заказов</p>
            </div>
            <a href="{{ route('admin.orders.history') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">История заказов</a>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700" role="alert">{{ session('success') }}</div>
    @endif

    <nav class="grid gap-2 rounded-2xl border border-slate-200 bg-white p-2 shadow-sm sm:grid-cols-2 xl:grid-cols-4" aria-label="Фильтр заказов">
        @foreach($filterLabels as $key => $label)
            <a href="{{ route('admin.orders', ['filter' => $key]) }}" class="flex items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold transition {{ $filter === $key ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                <span>{{ $label }}</span>
                <span class="rounded-full px-2 py-0.5 text-xs {{ $filter === $key ? 'bg-white/15 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $counts[$key] }}</span>
            </a>
        @endforeach
    </nav>

    @forelse($orders as $order)
        @include('admin.partials.order-card', ['order' => $order])
    @empty
        <div class="rounded-[28px] border border-dashed border-slate-300 bg-slate-50 p-10 text-center shadow-sm">
            <div class="text-lg font-semibold text-slate-700">В этом разделе пока нет заказов</div>
            <p class="mt-2 text-sm text-slate-500">Выберите другой фильтр или дождитесь нового заказа.</p>
        </div>
    @endforelse
</div>
@endsection
