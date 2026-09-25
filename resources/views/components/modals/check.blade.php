<!-- Модалка проверки оплаты -->
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4" id="checkPaymentModal" tabindex="-1" role="dialog" aria-labelledby="checkPaymentModalLabel" aria-hidden="true">
    <div class="w-full max-w-lg">
        <div class="flex max-h-[85vh] flex-col overflow-hidden rounded-lg bg-white shadow-xl dark:bg-gray-800">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-white" id="checkPaymentModalLabel">Проверка оплаты</h5>
                <button type="button" class="text-2xl leading-none text-gray-400 transition hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:hover:text-gray-200" data-close-payment-modal aria-label="Закрыть">&times;</button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-5">
                <div id="paymentOrderMeta" class="mb-4 text-sm text-gray-600 dark:text-gray-300">Загрузка данных...</div>

                <div id="paymentItemsList" class="divide-y divide-gray-100 rounded-md border border-gray-100 dark:divide-gray-700 dark:border-gray-700">
                    <!-- Товары заказа рендерятся сюда динамически -->
                </div>

                <div id="paymentCheckResult" class="mt-4" role="status"></div>
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                <button type="button" class="rounded-md bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600" data-close-payment-modal>Закрыть</button>
                <button type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500" id="checkPaymentButton">Проверить оплату</button>
            </div>
        </div>
    </div>
</div>