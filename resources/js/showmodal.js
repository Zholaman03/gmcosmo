import api from "./api";
import { cart, saveCart, closeCartModal, renderCart } from "./app";
(function () {




    const orderModal = document.getElementById('orderModal');
    const openBtn = document.getElementById('openModalPurchase');
    const closeEls = orderModal.querySelectorAll('[data-close-order-modal]');
    const cartTotalPrice = document.getElementById('cartTotalPrice');
    const orderTotalPrice = document.getElementById('orderTotalPrice');
    const form = document.getElementById('orderForm');
    const fieldAddress = document.getElementById('fieldAddress');
    const orderAddress = document.getElementById('orderAddress');
    const orderAddressLabel = document.getElementById('orderAddressLabel');
    const deliveryRadios = document.querySelectorAll('input[name="delivery"]');



    const deliveryConfig = {
        pickup_delivery: { showAddress: false, label: 'Адрес доставки *', placeholder: '' },
        courier_delivery: { showAddress: true, label: 'Адрес доставки *', placeholder: 'Город, улица, дом, квартира' },
        post_delivery: { showAddress: true, label: 'Индекс и отделение Казпочты *', placeholder: 'Например: 010000, отделение №1' }
    };



    function applyDeliveryMode(value) {
        // Защита: если разметка формы неполная — не падаем
        // if (!fieldAddress || !orderAddress || !orderAddressLabel) {
        //     return;
        // }

        const cfg = deliveryConfig[value] || deliveryConfig.pickup_delivery;
        if (cfg.showAddress) {
            fieldAddress.hidden = false;
            orderAddress.required = true;
            orderAddressLabel.textContent = cfg.label;
            orderAddress.placeholder = cfg.placeholder;
        } else {
            fieldAddress.hidden = true;
            orderAddress.required = false;
            fieldAddress.classList.remove('order-form__field--invalid');
        }
    }

    deliveryRadios.forEach(function (radio) {

        radio.addEventListener('change', function () {
            applyDeliveryMode(this.value);
        });
    });

    // Устанавливаем режим по умолчанию — берём значение реально отмеченного radio
    const initialDelivery = form.querySelector('input[name="delivery"]:checked');

    applyDeliveryMode(initialDelivery ? initialDelivery.value : 'pickup_delivery');

    function openOrderModal() {
        // подставляем актуальную сумму из корзины
        if (cartTotalPrice && orderTotalPrice) {
            orderTotalPrice.textContent = cartTotalPrice.textContent;
        }
        orderModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeOrderModal() {
        orderModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    if (openBtn) {
        openBtn.addEventListener('click', function (e) {
            e.preventDefault();
            openOrderModal();
        });
    }

    closeEls.forEach(function (el) {
        el.addEventListener('click', closeOrderModal);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && orderModal.getAttribute('aria-hidden') === 'false') {
            closeOrderModal();
        }
    });

    function validateField(input, fieldId, validatorFn) {
        const field = document.getElementById(fieldId);
        const valid = validatorFn(input.value.trim());
        field.classList.toggle('order-form__field--invalid', !valid);
        return valid;
    }

    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const name = document.getElementById('orderName');
            const phone = document.getElementById('orderPhone');
            const address = document.getElementById('orderAddress');
            const deliveryValue = form.querySelector('input[name="delivery"]:checked').value;

            const nameValid = validateField(name, 'fieldName', v => v.length >= 2);
            const phoneValid = validateField(phone, 'fieldPhone', v => /^[+]?[\d\s().-]{7,20}$/.test(v));
            const addressValid = deliveryValue === 'pickup_delivery'
                ? true
                : validateField(address, 'fieldAddress', v => v.length >= 5);

            if (!nameValid || !phoneValid || !addressValid) {
                return;
            }
            // здесь подключите реальную отправку заказа (fetch/axios на бэкенд)
            const submitBtn = document.getElementById('orderSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Отправляем...';

            try {
                const result = await api.post('/api/order',
                    {

                        name: name.value,
                        delivery_method_code: deliveryValue,
                        phone: phone.value,
                        status: 'paid',
                        address: address.value,
                        products: cart
                    }


                )
                if (result.success) {
                    localStorage.setItem('lastOrder', JSON.stringify({
                        orderId: result.order_id, // <-- поправьте под реальное поле из ответа API
                        deliveryLabel: result.delivery_method,
                        address: 'address',
                        items: cart.map(function (item) {
                            return {
                                name: item.name,
                                qty: item.qty || item.quantity || 1,
                                price: item.price + ' ₸',
                                total: (item.price * (item.qty || item.quantity || 1)) + ' ₸'
                            };
                        })
                    }));
                    cart.length = 0;
                    // saveCart();
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Подтвердить заказ';

                    closeOrderModal();
                    closeCartModal();

                    const toast = document.getElementById('toastNotification');
                    const toastMessage = document.getElementById('toastMessage');
                    if (toast && toastMessage) {
                        toastMessage.textContent = 'Заказ успешно оформлен!';
                        toast.classList.add('toast--visible');
                        setTimeout(() => toast.classList.remove('toast--visible'), 3000);
                    }

                    form.reset();
                    applyDeliveryMode('pickup_delivery');

                    openPaymentModal();
                    // renderCart();
                    // window.location.href = result.qr_image_url;
                }
            } catch (error) {
                console.error('Order error:', error);

                alert(
                    error.data?.message ??
                    'Не удалось оформить заказ'
                );

            } finally {

                submitBtn.disabled = false;
                submitBtn.textContent = 'Подтвердить заказ';

            }





        });
    }
})();