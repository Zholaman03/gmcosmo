
import api from './api';
import { cart, renderCart } from './app';

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('checkPaymentModal');

    if (!modal) {
        return;
    }

    const orderNumber = document.getElementById('paymentOrderNumber');
    const result = document.getElementById('paymentCheckResult');

    const closeEls = modal.querySelectorAll(
        '[data-close-payment-modal]'
    );

    // ========================================
    // PAYMENT POLLING
    // ========================================

    let paymentPolling = null;

    const POLLING_INTERVAL = 3000; // 3 секунды
    const MAX_ATTEMPTS = 100;      // 5 минут

    let pollingAttempts = 0;


    // ========================================
    // GET LAST ORDER
    // ========================================

    function getOrder() {
        try {
            return JSON.parse(
                localStorage.getItem('lastOrder') || 'null'
            );
        } catch (e) {
            console.error('Failed to parse lastOrder:', e);

            return null;
        }
    }


    // ========================================
    // STOP POLLING
    // ========================================

    function stopPaymentPolling() {
        if (paymentPolling !== null) {
            clearInterval(paymentPolling);
            paymentPolling = null;
        }

        pollingAttempts = 0;
    }


    // ========================================
    // CHECK PAYMENT STATUS
    // ========================================

    async function checkPaymentStatus(orderId) {
        try {
            const response = await api.get(
                `/api/payment/status/${orderId}`
            );

            /*
             * Если api.js использует Axios,
             * Laravel JSON находится в response.data
             */
            const data = response;

           

            if (data.status === 'paid') {
                stopPaymentPolling();

                result.innerHTML = `
                    <span class="font-medium text-green-600">
                        Оплата подтверждена ✓
                    </span>
                `;

                // Корзину можно очистить
                cart.length = 0;
                localStorage.removeItem('lastOrder');
                renderCart();

                return true;
            }

            if (data.status === 'failed') {
                stopPaymentPolling();

                result.innerHTML = `
                    <span class="font-medium text-red-600">
                        Оплата не прошла.
                    </span>
                `;

                return true;
            }

            // pending / waiting / другое состояние
            result.innerHTML = `
                <span class="text-gray-500 dark:text-gray-400">
                    Ожидаем подтверждение оплаты...
                </span>
            `;

            return false;

        } catch (error) {
            console.error('Payment status error:', error);

            /*
             * Ошибка одного запроса не останавливает polling.
             * Следующий запрос попробует снова.
             */
            result.innerHTML = `
                <span class="text-yellow-600">
                    Проверяем оплату...
                </span>
            `;

            return false;
        }
    }


    // ========================================
    // START POLLING
    // ========================================

    function startPaymentPolling(orderId) {
        // На всякий случай останавливаем старый polling
        stopPaymentPolling();

        pollingAttempts = 0;

        /*
         * Сначала проверяем сразу,
         * чтобы не ждать первые 3 секунды.
         */
        checkPaymentStatus(orderId);

        paymentPolling = setInterval(async function () {
            pollingAttempts++;

            const paid = await checkPaymentStatus(orderId);

            if (paid) {
                return;
            }

            /*
             * Максимум 100 проверок.
             *
             * 100 × 3 секунды ≈ 5 минут
             */
            if (pollingAttempts >= MAX_ATTEMPTS) {
                stopPaymentPolling();

                result.innerHTML = `
                    <div class="space-y-2">
                        <p class="text-yellow-600">
                            Не удалось автоматически подтвердить оплату.
                        </p>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Если вы уже оплатили заказ, статус может обновиться немного позже.
                        </p>
                    </div>
                `;
            }

        }, POLLING_INTERVAL);
    }


    // ========================================
    // OPEN MODAL
    // ========================================

    function openPaymentModal() {
        const order = getOrder();

        if (!order?.orderId) {
            console.error('Order not found');

            return;
        }

        orderNumber.textContent = order.orderId;

        result.innerHTML = `
            <span class="text-gray-500 dark:text-gray-400">
                Проверяем статус оплаты...
            </span>
        `;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        modal.setAttribute('aria-hidden', 'false');

        document.body.style.overflow = 'hidden';

        // Запускаем polling
        startPaymentPolling(order.orderId);
    }


    // ========================================
    // CLOSE MODAL
    // ========================================

    function closePaymentModal() {
        // Останавливаем polling
        stopPaymentPolling();

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        modal.setAttribute('aria-hidden', 'true');

        document.body.style.overflow = '';
    }


    // ========================================
    // CLOSE BUTTONS
    // ========================================

    closeEls.forEach(function (el) {
        el.addEventListener('click', closePaymentModal);
    });


    // ========================================
    // ESC
    // ========================================

    document.addEventListener('keydown', function (e) {
        if (
            e.key === 'Escape' &&
            modal.getAttribute('aria-hidden') === 'false'
        ) {
            closePaymentModal();
        }
    });


    // ========================================
    // GLOBAL FUNCTIONS
    // ========================================

    window.openPaymentModal = openPaymentModal;
    window.closePaymentModal = closePaymentModal;
});
