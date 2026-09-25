<!-- Модалка корзины -->
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

                <button id="openModalPurchase" class="btn btn--primary form__submit">Оформить заказ</button>
            </div>
        </div>
    </div>