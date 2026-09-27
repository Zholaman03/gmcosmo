
<!-- Модалка ожидания оплаты -->
<div
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"
    id="checkPaymentModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="checkPaymentModalLabel"
    aria-hidden="true"
>
    <div class="w-full max-w-md">
        <div class="overflow-hidden rounded-lg bg-white shadow-xl dark:bg-gray-800">

            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h5
                    class="text-lg font-semibold text-gray-900 dark:text-white"
                    id="checkPaymentModalLabel"
                >
                    Ожидание оплаты
                </h5>

                <button
                    type="button"
                    class="text-2xl leading-none text-gray-400 transition hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:hover:text-gray-200"
                    data-close-payment-modal
                    aria-label="Закрыть"
                >
                    &times;
                </button>
            </div>

            <!-- Body -->
            <div class="px-6 py-6 text-center">

                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Заказ №
                    <strong
                        id="paymentOrderNumber"
                        class="text-gray-900 dark:text-white"
                    ></strong>
                </p>

                <div
                    id="paymentCheckResult"
                    class="mt-5 text-sm"
                    role="status"
                    aria-live="polite"
                >
                    Проверяем статус оплаты...
                </div>

            </div>

            <!-- Footer -->
            <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                <button
                    type="button"
                    class="w-full rounded-md bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                    data-close-payment-modal
                >
                    Закрыть
                </button>
            </div>

        </div>
    </div>
</div>
```
