@extends('layouts.app')


@section('content')
<div class="container">

    <div class="search-bar" id="searchBar">
        <div class="search-bar__wrapper">
            <svg class="search-bar__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" id="searchInput" class="search-bar__input" placeholder="Поиск товара..." autocomplete="off">
            <button id="searchClear" class="search-bar__clear" aria-label="Очистить поиск" hidden>&times;</button>
        </div>
    </div>

    <nav class="categories" id="categories" aria-label="Категории товаров">
        
        <div class="categories__scroll"  id="categoriesList" >
            <a href="{{ route('product') }}" class="category-btn categories__item" data-category-id="all" aria-label="Показать все товары">
                Все
            </a>
            @foreach($categories as $category)
            <a href="{{route('category.filter', ['category' => $category->id])}}" class="category-btn categories__item" data-category-id="{{ $category->id }}" aria-label="Фильтр по категории {{ $category->name }}">
                {{ $category->name }}   
</a>
          
            @endforeach
        </div>
        
        <!-- <div class="categories__scroll" id="categoriesList">
        </div> -->
    </nav>

    <div class="catalog__meta">
        <span id="productCount" class="catalog__count">Найдено товаров: {{ $products->count() }}</span>
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
                <img src="{{ asset('images/images.jpg') }}" alt="{{ $product->name }}" class="product-card__image" loading="lazy">
            </div>
            <div class="product-card__content">
                <span class="product-card__brand">{{ $product->category->name }}</span>
                <h3 class="product-card__name">{{ $product->name }}</h3>
                <p class="product-card__description">{{ $product->description }}</p>
                <div class="product-card__footer">
                    <span class="product-card__price">{{ number_format($product->price, 2) }} KZT</span>
              
                    <button class="btn btn--primary product-card__btn"
                     data-buy-id="{{ $product->id }}"
                        data-buy-name="{{ $product->name }}"
                        data-buy-price="{{ $product->price }}"
                        data-buy-category="{{ $product->category->name }}"
                        data-buy-image="{{ asset('images/images.jpg') }}"
                        data-buy-description="{{ $product->description }}"
                     
                     >
                        В корзину
                    </button>
                </div>
            </div>
        </article>
        @endforeach
    </div>

    

</div>
@endsection
