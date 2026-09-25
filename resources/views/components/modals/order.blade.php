<!-- Модалка оформления заказа (открывается по клику на "Оформить заказ") -->
    <div class="order-modal" id="orderModal" aria-hidden="true" role="dialog" aria-labelledby="orderModalTitle">
        <div class="order-modal__overlay" data-close-order-modal></div>
        <div class="order-modal__container">
            <button class="order-modal__close" id="orderModalCloseBtn" aria-label="Закрыть" data-close-order-modal>&times;</button>
            <h2 class="order-modal__title" id="orderModalTitle">Оформление заказа</h2>

            <form id="orderForm" novalidate>
                @csrf
                <div class="order-form__field" id="fieldName">
                    <label for="orderName">Имя *</label>
                    <input type="text" id="orderName" name="name" placeholder="Ваше имя" required>
                    <span class="order-form__error">Пожалуйста, укажите имя</span>
                </div>

                <div class="order-form__field" id="fieldPhone">
                    <label for="orderPhone">Телефон *</label>
                    <input type="tel" id="orderPhone" name="phone" placeholder="+7 (___) ___-__-__" required>
                    <span class="order-form__error">Проверьте номер телефона</span>
                </div>

                <div class="order-form__field" id="fieldAddress">
                    <label for="orderAddress" id="orderAddressLabel">Адрес доставки *</label>
                    <input type="text" id="orderAddress" name="address" placeholder="Город, улица, дом, квартира" required>
                    <span class="order-form__error">Укажите адрес доставки</span>
                </div>

                <div class="order-form__field">
                    <span class="order-form__label">Способ доставки *</span>

                    <div class="delivery-options" id="deliveryOptions">
                        @foreach($DeliveryMethods as $DeliveryMethod)
                        <div class="delivery-option">
                            <input
                                type="radio"
                                id="delivery-{{ $DeliveryMethod->code }}"
                                name="delivery"
                                value="{{ $DeliveryMethod->code }}"
                                @checked($loop->first)
                            >
                            <label for="delivery-{{ $DeliveryMethod->code }}">
                                @switch($DeliveryMethod->code)
                                @case('pickup_delivery')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9l9-6 9 6v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"></path>
                                    <path d="M9 21V12h6v9"></path>
                                </svg>
                                @break
                                @case('courier_delivery')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="7" width="15" height="12" rx="2"></rect>
                                    <path d="M16 11h4l3 3v5h-7z"></path>
                                    <circle cx="6" cy="19.5" r="1.5"></circle>
                                    <circle cx="18.5" cy="19.5" r="1.5"></circle>
                                </svg>
                                @break
                                @case('post_delivery')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                    <path d="M3 7l9 6 9-6"></path>
                                </svg>
                                @break
                                @default
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9"></circle>
                                </svg>
                                @endswitch
                                {{ $DeliveryMethod->name }}
                            </label>
                        </div>
                        @endforeach
                
                    </div>
                </div>

                <div class="order-form__field">
                    <label for="orderComment">Комментарий к заказу</label>
                    <textarea id="orderComment" name="comment" rows="3" placeholder="Необязательно"></textarea>
                </div>

                <div class="order-form__summary">
                    <span>Итого к оплате:</span>
                    <strong id="orderTotalPrice">0 ₸</strong>
                </div>

                <button type="submit" class="order-form__submit" id="orderSubmitBtn">Подтвердить заказ</button>
            </form>
        </div>
    </div>