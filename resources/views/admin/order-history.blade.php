@extends('admin.dashboard')

@section('content')
<div class="space-y-6">
    <div class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-[0_20px_60px_-30px_rgba(15,23,42,0.35)] sm:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Archive</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">История заказов</h2>
                <p class="mt-2 text-sm text-slate-500">Завершённые заказы · {{ $orders->count() }}</p>
            </div>
            <a href="{{ route('admin.orders') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">К текущим заказам</a>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700" role="alert">{{ session('success') }}</div>
    @endif

    @forelse($orders as $order)
        @include('admin.partials.card', ['order' => $order])
    @empty
        <div class="rounded-[28px] border border-dashed border-slate-300 bg-slate-50 p-10 text-center shadow-sm">
            <div class="text-lg font-semibold text-slate-700">История пока пуста</div>
            <p class="mt-2 text-sm text-slate-500">Завершённые заказы появятся здесь.</p>
        </div>
    @endforelse
</div>
@endsection
