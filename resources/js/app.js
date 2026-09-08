/**
 * Gmcosmo Online Cosmetics Store
 * Shopping Cart
 */

// ========================================
// CART
// ========================================

let cart = JSON.parse(localStorage.getItem('cart')) || [];


// ========================================
// DOM ELEMENTS
// ========================================

const elements = {
    productsGrid: document.getElementById('productsGrid'),
    categoriesList: document.getElementById('categoriesList'),
    searchInput: document.getElementById('searchInput'),
    searchClear: document.getElementById('searchClear'),
    productCount: document.getElementById('productCount'),
    emptyState: document.getElementById('emptyState'),
    resetFiltersBtn: document.getElementById('resetFiltersBtn'),

    cartModal: document.getElementById('cartModal'),
    cartTrigger: document.getElementById('cartTrigger'),
    cartBadge: document.getElementById('cartBadge'),
    cartItemsList: document.getElementById('cartItemsList'),
    cartEmptyState: document.getElementById('cartEmptyState'),
    cartFooter: document.getElementById('cartFooter'),
    cartTotalPrice: document.getElementById('cartTotalPrice'),

    purchaseForm: document.getElementById('purchaseForm'),
    userName: document.getElementById('userName'),
    userPhone: document.getElementById('userPhone'),
    nameError: document.getElementById('nameError'),
    phoneError: document.getElementById('phoneError'),

    toastNotification: document.getElementById('toastNotification'),
    toastMessage: document.getElementById('toastMessage'),

    burgerBtn: document.getElementById('burgerBtn'),
    headerNav: document.getElementById('headerNav'),
    searchTrigger: document.getElementById('searchTrigger'),
    searchBar: document.getElementById('searchBar')
};


// ========================================
// SAVE CART
// ========================================

function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}


// ========================================
// FORMAT PRICE
// ========================================

function formatPrice(price) {
    return Number(price).toLocaleString('ru-RU') + ' ₸';
}


// ========================================
// CART TOTAL
// ========================================

function getCartTotal() {
    return cart.reduce((total, item) => {
        return total + Number(item.price) * Number(item.quantity);
    }, 0);
}


// ========================================
// CART TOTAL QUANTITY
// ========================================

function getCartQuantity() {
    return cart.reduce((total, item) => {
        return total + Number(item.quantity);
    }, 0);
}


// ========================================
// OPEN CART MODAL
// ========================================

function openCartModal() {
    if (!elements.cartModal) return;

    renderCart();

    elements.cartModal.classList.add('modal--open');
    elements.cartModal.setAttribute('aria-hidden', 'false');

    document.body.style.overflow = 'hidden';
}


// ========================================
// CLOSE CART MODAL
// ========================================

function closeCartModal() {
    if (!elements.cartModal) return;

    elements.cartModal.classList.remove('modal--open');
    elements.cartModal.setAttribute('aria-hidden', 'true');

    document.body.style.overflow = '';
}


// ========================================
// ADD PRODUCT TO CART
// ========================================

function addToCart(product) {

    const existingProduct = cart.find(
        item => item.id == product.id
    );

    if (existingProduct) {

        // Егер товар корзинада бар болса,
        // quantity +1
        existingProduct.quantity += 1;

    } else {

        // Жаңа товар
        cart.push({
            id: product.id,
            name: product.name,
            price: Number(product.price),
            category: product.category,
            image: product.image,
            description: product.description,
            quantity: 1
        });
    }

    saveCart();
    renderCart();
    showNotification(`${product.name} добавлен в корзину`);
}


// ========================================
// REMOVE PRODUCT FROM CART
// ========================================

function removeFromCart(productId) {
    const oneCart = cart.find(item => item.id == productId);
    
    cart = cart.filter(
        item => item.id != productId
    );
    
  
    showNotification(`${oneCart.name} удален из корзины`);
    saveCart();
    renderCart();
    
}


// ========================================
// UPDATE QUANTITY
// ========================================

function updateCartQuantity(productId, delta) {

    const item = cart.find(
        item => item.id == productId
    );

    if (!item) return;

    item.quantity += delta;

    // Егер quantity 0 болса,
    // товарды корзинадан өшіреміз
    if (item.quantity <= 0) {

        removeFromCart(productId);
        return;
    }

    saveCart();
    renderCart();
}


// ========================================
// RENDER CART
// ========================================

function renderCart() {

    if (!elements.cartItemsList) return;

    const totalItems = getCartQuantity();

    // ------------------------------------
    // CART BADGE
    // ------------------------------------

    if (elements.cartBadge) {

        elements.cartBadge.textContent = totalItems;

        elements.cartBadge.hidden =
            totalItems === 0;
    }


    // ------------------------------------
    // PRODUCT BUTTONS
    // ------------------------------------

    const buyButtons =
        document.querySelectorAll('.product-card__btn');

    buyButtons.forEach(button => {

        const productId =
            button.getAttribute('data-buy-id');

        const isInCart = cart.some(
            item => item.id == productId
        );

        button.textContent = isInCart
            ? 'Убрать из корзины'
            : 'В корзину';
    });


    // ------------------------------------
    // EMPTY CART
    // ------------------------------------

    if (cart.length === 0) {

        elements.cartItemsList.innerHTML = '';

        if (elements.cartEmptyState) {
            elements.cartEmptyState.hidden = false;
        }

        if (elements.cartFooter) {
            elements.cartFooter.hidden = true;
        }

        if (elements.cartTotalPrice) {
            elements.cartTotalPrice.textContent =
                formatPrice(0);
        }

        return;
    }


    // ------------------------------------
    // CART HAS PRODUCTS
    // ------------------------------------

    if (elements.cartEmptyState) {
        elements.cartEmptyState.hidden = true;
    }

    if (elements.cartFooter) {
        elements.cartFooter.hidden = false;
    }


    // ------------------------------------
    // RENDER PRODUCTS
    // ------------------------------------

    elements.cartItemsList.innerHTML = cart.map(product => {

        const productTotal =
            Number(product.price) *
            Number(product.quantity);

        return `
            <div class="cart-item">

                <img
                    src="${product.image}"
                    alt="${product.name}"
                    class="cart-item__image"
                >

                <div class="cart-item__info">

                    <span class="cart-item__brand">
                        ${product.category ?? ''}
                    </span>

                    <h4 class="cart-item__name">
                        ${product.name}
                    </h4>

                    <div class="cart-item__price">
                        ${formatPrice(productTotal)}
                    </div>

                    <div class="cart-item__actions">

                        <div class="quantity-control">

                            <button
                                type="button"
                                class="quantity-control__btn"
                                data-cart-qty-change="${product.id}"
                                data-delta="-1"
                            >
                                -
                            </button>

                            <input
                                type="number"
                                class="quantity-control__input"
                                value="${product.quantity}"
                                readonly
                            >

                            <button
                                type="button"
                                class="quantity-control__btn"
                                data-cart-qty-change="${product.id}"
                                data-delta="1"
                            >
                                +
                            </button>

                        </div>

                        <button
                            type="button"
                            class="cart-item__remove"
                            data-cart-remove="${product.id}"
                        >
                            Удалить
                        </button>

                    </div>

                </div>

            </div>
        `;
    }).join('');


    // ------------------------------------
    // TOTAL PRICE
    // ------------------------------------

    if (elements.cartTotalPrice) {

        elements.cartTotalPrice.textContent =
            formatPrice(getCartTotal());
    }
}


// ========================================
// PRODUCT BUTTONS
// ========================================

document
    .querySelectorAll('.product-card__btn')
    .forEach(button => {

        button.addEventListener('click', () => {

            const product = {

                id: button.getAttribute('data-buy-id'),

                name: button.getAttribute('data-buy-name'),

                price: parseFloat(
                    button.getAttribute('data-buy-price')
                ),

                category: button.getAttribute(
                    'data-buy-category'
                ),

                image: button.getAttribute(
                    'data-buy-image'
                ),

                description: button.getAttribute(
                    'data-buy-description'
                )
            };


            const existingProduct = cart.find(
                item => item.id == product.id
            );


            // --------------------------------
            // IF PRODUCT ALREADY IN CART
            // --------------------------------

            if (existingProduct) {

                removeFromCart(product.id);

            } else {

                addToCart(product);
            }

        });
    });


// ========================================
// CART BUTTON
// ========================================

if (elements.cartTrigger) {

    elements.cartTrigger.addEventListener(
        'click',
        openCartModal
    );
}


// ========================================
// CLOSE MODAL
// ========================================

document.addEventListener('click', event => {

    const closeButton =
        event.target.closest('[data-close-modal]');

    if (closeButton) {
        closeCartModal();
    }
});


// ========================================
// ESC CLOSE
// ========================================

document.addEventListener('keydown', event => {

    if (
        event.key === 'Escape' &&
        elements.cartModal &&
        elements.cartModal.classList.contains('modal--open')
    ) {
        closeCartModal();
    }
});


// ========================================
// QUANTITY + REMOVE
// ========================================

if (elements.cartItemsList) {

    elements.cartItemsList.addEventListener(
        'click',
        event => {

            // --------------------------------
            // REMOVE
            // --------------------------------

            const removeButton =
                event.target.closest(
                    '[data-cart-remove]'
                );

            if (removeButton) {

                const productId =
                    removeButton.dataset.cartRemove;

                removeFromCart(productId);

                return;
            }


            // --------------------------------
            // QUANTITY
            // --------------------------------

            const quantityButton =
                event.target.closest(
                    '[data-cart-qty-change]'
                );

            if (quantityButton) {

                const productId =
                    quantityButton.dataset.cartQtyChange;

                const delta =
                    parseInt(
                        quantityButton.dataset.delta,
                        10
                    );

                updateCartQuantity(
                    productId,
                    delta
                );
            }
        }
    );
}


// ========================================
// PURCHASE FORM
// ========================================

if (elements.purchaseForm) {

    elements.purchaseForm.addEventListener(
        'submit',
        async event => {

            event.preventDefault();


            // --------------------------------
            // VALIDATION
            // --------------------------------

            const name =
                elements.userName.value.trim();

            const phone =
                elements.userPhone.value.trim();


            if (elements.nameError) {
                elements.nameError.textContent = '';
            }

            if (elements.phoneError) {
                elements.phoneError.textContent = '';
            }


            let hasError = false;


            if (!name) {

                if (elements.nameError) {
                    elements.nameError.textContent =
                        'Введите имя';
                }

                hasError = true;
            }


            if (!phone) {

                if (elements.phoneError) {
                    elements.phoneError.textContent =
                        'Введите телефон';
                }

                hasError = true;
            }


            if (cart.length === 0) {

                alert('Корзина пуста');

                return;
            }


            if (hasError) {
                return;
            }


            // --------------------------------
            // CSRF
            // --------------------------------

            const csrfInput =
                elements.purchaseForm.querySelector(
                    'input[name="_token"]'
                );

            if (!csrfInput) {

                console.error(
                    'CSRF token not found'
                );

                return;
            }

            const csrfToken =
                csrfInput.value;


            // --------------------------------
            // SEND ONLY ID + QUANTITY
            // --------------------------------

            const products = cart.map(item => ({
                id: item.id,
                quantity: item.quantity
            }));


            try {

                const response = await fetch(
                    '/order',
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                csrfToken
                        },

                        body: JSON.stringify({

                            name: name,

                            phone: phone,

                            products: products
                        })
                    }
                );


                const result =
                    await response.json();


                // --------------------------------
                // ERROR
                // --------------------------------

                if (!response.ok) {

                    console.error(
                        'Server error:',
                        result
                    );

                    alert(
                        result.message ??
                        'Ошибка при оформлении заказа'
                    );

                    return;
                }


                // --------------------------------
                // SUCCESS
                // --------------------------------

                if (result.success) {

                    showNotification('Спасибо! Ваша заявка принята. Мы свяжемся с вами в ближайшее время.');


                    // Корзинаны тазалау
                    cart = [];

                    saveCart();

                    renderCart();

                    // Форманы тазалау
                    elements.purchaseForm.reset();

                    // Modal жабу
                    closeCartModal();
                }


            } catch (error) {

                console.error(
                    'Request error:',
                    error
                );

                alert(
                    'Не удалось отправить заказ'
                );
            }

        }
    );
}

function showNotification(message) {
    elements.toastMessage.textContent = message;
    elements.toastNotification.classList.add('toast--visible');

    setTimeout(() => {
        elements.toastNotification.classList.remove('toast--visible');
    }, 4000);
}


// ========================================
// INITIAL RENDER
// ========================================

renderCart();


// ========================================
// EXPORT
// ========================================

export {
    renderCart,
    addToCart,
    removeFromCart,
    updateCartQuantity
};