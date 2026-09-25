@extends('admin.dashboard')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <h1 class="text-2xl font-bold">Способы доставки</h1>

    @if (session('success'))
        <div class="rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg bg-red-100 px-4 py-3 text-sm text-red-800">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <h2 class="mb-4 text-lg font-semibold">Добавить способ доставки</h2>
        <form method="POST" action="{{ url('admin/delivery-methods') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <div>
                <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Способ доставки</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="Способ доставки" disabled class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                Активный
            </label>
            <div class="md:col-span-2">
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white" disabled>Добавить</button>
                <p class="mt-2 text-xs text-gray-500">* Добавление новых способов доставки временно отключено.</p>
            </div>
        </form>
    </section>

    <section class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
        <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Управление способами доставки</h2></div>
        @forelse ($deliveryMethods ?? [] as $method)
            <div class="flex flex-col gap-4 border-b p-6 last:border-0 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-semibold">{{ $method->name }}</h3>
                        <span class="rounded-full px-2 py-1 text-xs {{ $method->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $method->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
    
                </div>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('admin.delivery_methods.update', $method->id) }}" onsubmit="return confirm('Disable this delivery method?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="rounded-lg {{ $method->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} px-3 py-2 text-sm text-white">
                            {{ $method->is_active ? 'Отключить' : 'Включить' }}
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="px-6 py-8 text-center text-sm text-gray-500">Ни одной метод доставки не добавлено.</p>
        @endforelse
    </section>
</div>
@endsection