@extends('admin.dashboard')

@section('content')
<div class="space-y-4">
    <h2 class="text-lg font-bold text-gray-800">Заказы</h2>

    @forelse($orders as $order)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Mobile Order Header Stack -->
        <div class="bg-gray-50 p-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between gap-2 sm:items-center">
            <div class="flex items-center justify-between">
                <span class="text-base font-bold text-gray-800">Заказ #{{ $order->id }}</span>
                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 sm:hidden">
                    {{ $order->status }}
                </span>
            </div>
            <div class="text-xs sm:text-sm text-gray-600 grid grid-cols-2 sm:flex sm:items-center gap-2">
                <div><strong class="text-gray-400 sm:text-gray-600">Клиент:</strong> {{ $order->name }}</div>
                <div><strong class="text-gray-400 sm:text-gray-600">Тел:</strong> {{ $order->phone }}</div>
                <div><strong class="text-gray-400 sm:text-gray-600">Кол-во:</strong> {{ $order->total_quantity }}</div>
                <div class="text-indigo-600 font-bold text-sm sm:text-base">
                    {{ number_format($order->total_price, 0, '.', ' ') }} ₸
                </div>
            </div>
        </div>

        <!-- Product Items View -->
        <div class="p-4 divide-y divide-gray-100">
            @foreach($order->items as $item)
            <div class="py-3 flex items-center justify-between gap-3">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/images.jpg') }}"
                        alt="{{ $item->product->name }}"
                        class="w-12 h-12 sm:w-16 sm:h-16 object-cover rounded-lg border flex-shrink-0">
                    <div class="min-w-0">
                        <h4 class="font-semibold text-sm sm:text-base text-gray-800 truncate">{{ $item->product->name }}</h4>
                        <p class="text-xs sm:text-sm text-gray-500">
                            {{ number_format($item->price, 0, '.', ' ') }} ₸ × {{ $item->quantity }}
                        </p>
                    </div>
                </div>
                <div class="text-right text-xs sm:text-sm font-semibold text-gray-700 whitespace-nowrap">
                    {{ number_format($item->price * $item->quantity, 0, '.', ' ') }} ₸
                </div>

            </div>
            @endforeach
        </div>

        <div class="p-4 border-t border-gray-200 text-right">
            <form action="{{ route('admin.orders.delete', $order->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот заказ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">Удалить заказ</button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white p-6 rounded-xl text-center text-gray-500 text-sm">
        Нет доступных заказов.
    </div>
    @endforelse
</div>
@endsection