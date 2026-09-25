<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gmcosmo — Косметика для твоей красоты</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/payment-modal.css', 'resources/js/app.js', 'resources/js/showmodal.js', 'resources/js/check.js'])

    <!--
        Стили ниже относятся только к новому модальному окну оформления заказа
        и к адаптивным правкам. Рекомендую со временем перенести их
        в resources/css/payment-modal.css, чтобы не хранить стили в разметке.
    -->
</head>

<body>
    <header class="header">
        <div class="container header__container">
            <button class="header__burger" id="burgerBtn" aria-label="Открыть меню">
                <span></span>
                <span></span>
            </button>
            <a href="#" class="header__logo">Gmcosmo</a>
            <nav class="header__nav" id="headerNav">
                <a href="#catalog" class="header__link">Каталог</a>
                <a href="#categories" class="header__link">Категории</a>
                
            </nav>
            <div class="header__actions">
                <button class="header__search-trigger" id="searchTrigger" aria-label="Поиск">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
                <button class="header__cart-btn" id="cartTrigger" aria-label="Корзина">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <span class="header__cart-badge" id="cartBadge" hidden>0</span>
                </button>
            </div>
        </div>
    </header>
    @include('components.modals.cart')
     @include('components.modals.order')
     @include('components.modals.check')
    <main>
        <section class="catalog" id="catalog">
            @yield('content')
        </section>
    </main>
    

    

    <div class="toast" id="toastNotification" role="alert" aria-live="polite">
        <div class="toast__content">
            <svg class="toast__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span id="toastMessage"></span>
        </div>
    </div>


</body>

</html>