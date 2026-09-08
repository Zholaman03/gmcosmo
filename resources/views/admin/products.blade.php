@extends('admin.dashboard')

@section('content')
<div class="space-y-4">
    <h2 class="text-lg font-bold text-gray-800">Все товары</h2>

    @if($products->isNotEmpty())
        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-left text-sm text-gray-700">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 font-semibold">#</th>
                        <th class="px-4 py-3 font-semibold">Название</th>
                        <th class="px-4 py-3 font-semibold">Цена</th>
                        <th class="px-4 py-3 font-semibold">Категория</th>
                        <th class="px-4 py-3 font-semibold">Статус</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                @if($product->slug)
                                    <div class="text-xs text-gray-500">{{ $product->slug }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ number_format($product->price ?? 0, 2, ',', ' ') }} ₽</td>
                            <td class="px-4 py-3">
                                {{ $product->category->name ?? 'Без категории' }}
                            </td>
                            <td class="px-4 py-3">
                                @if(($product->is_active ?? false) || ($product->status ?? '') === 'active')
                                    <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Активен</span>
                                @else
                                    <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Неактивен</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center text-sm text-gray-500">
            Товары не найдены.
        </div>
    @endif
</div>
@endsection