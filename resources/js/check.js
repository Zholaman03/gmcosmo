document.addEventListener('DOMContentLoaded', function () {
    const checkPaymentModal = document.getElementById('checkPaymentModal');
    if (!checkPaymentModal) {
        return;
    }

    const paymentOrderMeta = document.getElementById('paymentOrderMeta');
    const paymentItemsList = document.getElementById('paymentItemsList');
    const paymentCheckResult = document.getElementById('paymentCheckResult');
    const checkPaymentButton = document.getElementById('checkPaymentButton');
    const paymentCloseEls = checkPaymentModal.querySelectorAll('[data-close-payment-modal]');

    function renderOrderSummary() {
        if (!paymentOrderMeta || !paymentItemsList) {
            return;
        }

        let order = null;
        try {
            order = JSON.parse(localStorage.getItem('lastOrder') || 'null');
        } catch (e) {
            order = null;
        }

        paymentOrderMeta.innerHTML = order
            ? '<div><strong>Номер заказа:</strong> ' + (order.orderId ?? '—') + '</div>' +
              '<div><strong>Способ доставки:</strong> ' + (order.deliveryLabel || '—') + '</div>' +
              '<div><strong>Адресс:</strong> '+(order.address || '-') + '</div>'
            : '<div>Данные о заказе не найдены.</div>';

        const items = Array.isArray(order?.items) ? order.items : [];

        if (items.length === 0) {
            paymentItemsList.innerHTML =
                '<div class="px-3 py-3 text-sm text-gray-500 dark:text-gray-400">Товары не найдены.</div>';
        } else {
            paymentItemsList.innerHTML = items.map(function (item) {
                return (
                    '<div class="flex items-center justify-between gap-3 px-3 py-3 text-sm">' +
                        '<div class="min-w-0">' +
                            '<div class="truncate font-medium text-gray-800 dark:text-gray-100">' + item.name + '</div>' +
                            '<div class="text-xs text-gray-500 dark:text-gray-400">' + item.qty + ' шт. × ' + item.price + '</div>' +
                        '</div>' +
                        '<div class="shrink-0 font-medium text-gray-800 dark:text-gray-100">' + item.total + '</div>' +
                    '</div>'
                );
            }).join('');
        }

        const existingTotal = document.getElementById('paymentOrderTotal');
        if (existingTotal) {
            existingTotal.remove();
        }

        const grandTotal = items.reduce(function (sum, i) {
            return sum + parseFloat(i.total);
        }, 0);

        paymentItemsList.insertAdjacentHTML(
            'afterend',
            '<div id="paymentOrderTotal" class="mt-3 flex justify-between border-t border-gray-100 pt-3 text-sm font-semibold text-gray-900 dark:border-gray-700 dark:text-white">' +
                '<span>Итого:</span><span>' + grandTotal + ' ₸</span>' +
            '</div>'
        );
    }

    function openPaymentModal() {
        renderOrderSummary();
        if (paymentCheckResult) {
            paymentCheckResult.innerHTML = '';
        }
        checkPaymentModal.classList.remove('hidden');
        checkPaymentModal.classList.add('flex');
        checkPaymentModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closePaymentModal() {
        checkPaymentModal.classList.add('hidden');
        checkPaymentModal.classList.remove('flex');
        checkPaymentModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    paymentCloseEls.forEach(function (el) {
        el.addEventListener('click', closePaymentModal);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && checkPaymentModal.getAttribute('aria-hidden') === 'false') {
            closePaymentModal();
        }
    });

    if (checkPaymentButton) {
        checkPaymentButton.addEventListener('click', function () {
            let order = null;
            try {
                order = JSON.parse(localStorage.getItem('lastOrder') || 'null');
            } catch (e) {
                order = null;
            }

            if (!order || !order.orderId) {
                paymentCheckResult.innerHTML = '<span class="text-red-600">Не удалось определить заказ для проверки.</span>';
                return;
            }

            checkPaymentButton.disabled = true;
            checkPaymentButton.textContent = 'Проверяем...';
            paymentCheckResult.innerHTML = '<span class="text-gray-500">Проверка статуса оплаты...</span>';

            // TODO: замените URL на реальный эндпоинт проверки оплаты
            fetch('/api/orders/' + encodeURIComponent(order.orderId) + '/payment-status', {
                headers: { 'Accept': 'application/json' }
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Request failed');
                    }
                    return response.json();
                })
                .then(function (data) {
                    checkPaymentButton.disabled = false;
                    checkPaymentButton.textContent = 'Проверить оплату';

                    if (data && data.paid) {
                        paymentCheckResult.innerHTML = '<span class="text-green-600 font-medium">Оплата подтверждена ✓</span>';
                    } else {
                        paymentCheckResult.innerHTML = '<span class="text-yellow-600">Оплата пока не найдена. Попробуйте позже.</span>';
                    }
                })
                .catch(function () {
                    checkPaymentButton.disabled = false;
                    checkPaymentButton.textContent = 'Проверить оплату';
                    paymentCheckResult.innerHTML = '<span class="text-red-600">Не удалось проверить оплату. Попробуйте ещё раз.</span>';
                });
        });
    }

    // Открыть модалку можно из любого места: window.openPaymentModal()
    window.openPaymentModal = openPaymentModal;
    window.closePaymentModal = closePaymentModal;
});