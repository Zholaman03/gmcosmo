@extends('layouts.app')


@section('content')
<div class="container">
    <form action="{{route('product')}}" method="GET">
    <div class="search-bar" id="searchBar">
        <div class="search-bar__wrapper">
            <svg class="search-bar__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="search" name="search" id="searchInput" class="search-bar__input" value="{{ request('search') }}" placeholder="Поиск товара..." autocomplete="off">
            <button id="searchClear" class="search-bar__clear" aria-label="Очистить поиск" hidden>&times;</button>
            <button type="submit"  class="search-bar__button btn btn--primary" aria-label="Найти товары">
                Поиск
            </button>
        </div>

    </div>
    </form>

    <nav class="w-full my-4" id="categories" aria-label="Категории товаров">
        <!-- Көлденең скролл бар контейнер (no-scrollbar арқылы скроллбар жасырылады) -->
        <div class="flex items-center gap-2 overflow-x-auto py-2 px-1 scrollbar-none [ms-overflow-style:none] [scrollbar-width:none]" id="categoriesList">

            <!-- "Все" категориясы -->
            <a href="{{ route('product') }}"
                class="category-btn shrink-0 px-5 py-2 text-sm font-medium rounded-full border border-gray-200 bg-white text-gray-700 hover:bg-gray-900 hover:text-white hover:border-gray-900 active:scale-95 transition-all duration-200 ease-in-out shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-1"
                data-category-id="all"
                aria-label="Показать все товары">
                Все
            </a>

            <!-- Категориялар циклы -->
            @foreach($categories as $category)
            <a href="{{ route('category.filter', ['category' => $category->id]) }}"
                class="category-btn shrink-0 px-5 py-2 text-sm font-medium rounded-full border border-gray-200 bg-white text-gray-700 hover:bg-gray-900 hover:text-white hover:border-gray-900 active:scale-95 transition-all duration-200 ease-in-out shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-1"
                data-category-id="{{ $category->id }}"
                aria-label="Фильтр по категории {{ $category->name }}">
                {{ $category->name }}
            </a>
            @endforeach

        </div>
    </nav>

    <div class="catalog__meta">
        <span id="productCount" class="catalog__count">Найдено товаров: {{ $products->total() }}</span>
    </div>
    @if($products->isEmpty())
    <div class="empty-state" id="emptyState">
        <p class="empty-state__title">Товары не найдены</p>
        <p class="empty-state__subtitle">Попробуйте изменить параметры поиска или фильтрации</p>
        <button class="btn btn--secondary" id="resetFiltersBtn">Сбросить фильтры</button>
    </div>
    @endif

    <div class="products-grid" id="productsGrid">
        @foreach($products as $product)
        <article class="product-card">
            <div class="product-card__image-container">
                @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-card__image" loading="lazy">
                @else
                <img src="{{ asset('images/images.jpg') }}" alt="{{ $product->name }}" class="product-card__image" loading="lazy">
                @endif
            </div>
            <div class="product-card__content">
                <span class="product-card__brand">{{ $product->category->name }}</span>
                <h3 class="product-card__name">{{ $product->name }}</h3>
                <p class="product-card__description ">{{ $product->description }}</p>
                <div class="product-card__footer">
                    <span class="product-card__price">{{ number_format($product->price, 2) }} KZT</span>

                    <button class="btn btn--primary product-card__btn"
                        data-buy-id="{{ $product->id }}"
                        data-buy-name="{{ $product->name }}"
                        data-buy-price="{{ $product->price }}"
                        data-buy-category="{{ $product->category->name }}"
                        data-buy-image="{{ $product->image ? asset('storage/' . $product->image) : asset('images/images.jpg') }}"
                        data-buy-description="{{ $product->description }}">
                        В корзину
                    </button>
                </div>
            </div>
        </article>
        @endforeach

    </div>

    @if($products->hasPages())
    <div class="px-5 py-4">
        {{ $products->links() }}
    </div>
    @endif

</div>
@endsection