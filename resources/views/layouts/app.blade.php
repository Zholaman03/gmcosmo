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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

    <main>
        <!-- <section class="hero">
            <div class="container hero__container">
                <div class="hero__content">
                    <h1 class="hero__title">Gmcosmo</h1>
                    <p class="hero__subtitle">Косметика для твоей красоты</p>
                    <p class="hero__description">Выбирайте любимые средства и оставляйте заявку на покупку.</p>
                    <a href="#catalog" class="btn btn--primary hero__btn">Смотреть товары</a>
                </div>
                <div class="hero__image-wrapper">
                    <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=1000&q=80" alt="Премиальная косметика Gmcosmo" class="hero__image">
                </div>
            </div>
        </section> -->
        <section class="catalog" id="catalog">
            @yield('content')
        </section>
    </main>

      <div class="modal" id="cartModal" aria-hidden="true" role="dialog" aria-labelledby="modalTitle">
        <div class="modal__overlay" data-close-modal></div>
        <div class="modal__container modal__container--cart">
            <button class="modal__close" id="modalCloseBtn" aria-label="Закрыть" data-close-modal>&times;</button>
            <h2 class="modal__title" id="modalTitle">Корзина</h2>

            <!-- Cart Items Container -->
            <div class="cart-body" id="cartBody">
                <div class="cart-items" id="cartItemsList">
                    <!-- Cart items rendered dynamically -->
                </div>

                <div class="cart-empty" id="cartEmptyState" hidden>
                    <p class="cart-empty__text">Ваша корзина пуста</p>
                    <button class="btn btn--secondary" data-close-modal>Перейти к покупкам</button>
                </div>
            </div>

            <!-- Checkout Section -->
            <div class="cart-footer" id="cartFooter">
                <div class="cart-summary">
                    <span>Итого к оплате:</span>
                    <strong id="cartTotalPrice">0 ₸</strong>
                </div>

                <form id="purchaseForm" class="form" novalidate>
                    @csrf
                    <div class="form__group">
                        <label for="userName" class="form__label">Имя</label>
                        <input type="text" id="userName" class="form__input" placeholder="Ваше имя">
                        <span class="form__error" id="nameError"></span>
                    </div>

                    <div class="form__group">
                        <label for="userPhone" class="form__label">Телефон</label>
                        <input type="tel" id="userPhone" class="form__input" placeholder="+7 (777) 123-45-67">
                        <span class="form__error" id="phoneError"></span>
                    </div>

                    <button type="submit" id="purchaseSubmitBtn" class="btn btn--primary form__submit">Оформить заказ</button>
                </form>
            </div>
        </div>
    </div>
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