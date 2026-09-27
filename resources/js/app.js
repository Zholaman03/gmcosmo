/**
 * Gmcosmo Online Cosmetics Store
 * Shopping Cart
 */

// ========================================
// CART
// ========================================

export let cart = JSON.parse(localStorage.getItem('cart')) || [];

const MAX_QUANTITY = 5;


// ========================================
// DOM ELEMENTS
// ========================================

const elements = {
    cartModal: document.getElementById('cartModal'),
    cartTrigger: document.getElementById('cartTrigger'),
    cartBadge: document.getElementById('cartBadge'),

    cartItemsList: document.getElementById('cartItemsList'),
    cartEmptyState: document.getElementById('cartEmptyState'),
    cartFooter: document.getElementById('cartFooter'),
    cartTotalPrice: document.getElementById('cartTotalPrice'),

    toastNotification: document.getElementById('toastNotification'),
    toastMessage: document.getElementById('toastMessage'),
};


// ========================================
// SAVE CART
// ========================================

export function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}


// ========================================
// HELPERS
// ========================================

function formatPrice(price) {
    return `${Number(price).toLocaleString('ru-RU')} ₸`;
}

function getCartTotal() {
    return cart.reduce(
        (total, item) =>
            total + Number(item.price) * Number(item.quantity),
        0
    );
}

function getCartQuantity() {
    return cart.reduce(
        (total, item) => total + Number(item.quantity),
        0
    );
}

function findCartItem(productId) {
    return cart.find(item => item.id == productId);
}


// ========================================
// NOTIFICATION
// ========================================

function showNotification(message) {

    if (
        !elements.toastMessage ||
        !elements.toastNotification
    ) {
        return;
    }

    elements.toastMessage.textContent = message;

    elements.toastNotification.classList.add(
        'toast--visible'
    );

    setTimeout(() => {
        elements.toastNotification.classList.remove(
            'toast--visible'
        );
    }, 4000);
}


// ========================================
// OPEN CART
// ========================================

function openCartModal() {

    if (!elements.cartModal) {
        return;
    }

    renderCart();

    elements.cartModal.classList.add('modal--open');

    elements.cartModal.setAttribute(
        'aria-hidden',
        'false'
    );

    document.body.style.overflow = 'hidden';
}


// ========================================
// CLOSE CART
// ========================================

export function closeCartModal() {

    if (!elements.cartModal) {
        return;
    }

    elements.cartModal.classList.remove(
        'modal--open'
    );

    elements.cartModal.setAttribute(
        'aria-hidden',
        'true'
    );

    document.body.style.overflow = '';
}


// ========================================
// ADD TO CART
// ========================================

function addToCart(product) {

    const existingProduct = findCartItem(product.id);

    if (existingProduct) {

        if (existingProduct.quantity >= MAX_QUANTITY) {
            showNotification(
                `Максимальное количество: ${MAX_QUANTITY}`
            );

            return;
        }

        existingProduct.quantity++;

    } else {

        cart.push({
            id: product.id,
            name: product.name,
            price: Number(product.price),
            category: product.category,
            image: product.image,
            quantity: 1
        });
    }

    saveCart();
    renderCart();

    showNotification(
        `${product.name} добавлен в корзину`
    );
}


// ========================================
// REMOVE FROM CART
// ========================================

function removeFromCart(productId) {

    const product = findCartItem(productId);

    if (!product) {
        return;
    }

    cart = cart.filter(
        item => item.id != productId
    );

    saveCart();
    renderCart();

    showNotification(
        `${product.name} удален из корзины`
    );
}


// ========================================
// UPDATE QUANTITY
// ========================================

function updateCartQuantity(productId, delta) {

    const item = findCartItem(productId);

    if (!item) {
        return;
    }

    const newQuantity = item.quantity + delta;

    // ------------------------------------
    // MAX QUANTITY
    // ------------------------------------

    if (newQuantity > MAX_QUANTITY) {

        showNotification(
            `Максимальное количество: ${MAX_QUANTITY}`
        );

        return;
    }

    // ------------------------------------
    // REMOVE IF ZERO
    // ------------------------------------

    if (newQuantity <= 0) {

        removeFromCart(productId);

        return;
    }

    item.quantity = newQuantity;

    saveCart();
    renderCart();
}


// ========================================
// CREATE PRODUCT FROM BUTTON
// ========================================

function getProductFromButton(button) {

    return {
        id: button.dataset.buyId,
        name: button.dataset.buyName,
        price: Number(button.dataset.buyPrice),
        category: button.dataset.buyCategory,
        image: button.dataset.buyImage
    };
}


// ========================================
// UPDATE PRODUCT BUTTONS
// ========================================

function updateProductButtons() {

    document
        .querySelectorAll('.product-card__btn')
        .forEach(button => {

            const productId =
                button.dataset.buyId;

            const isInCart = cart.some(
                item => item.id == productId
            );

            button.textContent = isInCart
                ? 'Убрать из корзины'
                : 'В корзину';
        });
}


// ========================================
// UPDATE CART UI
// ========================================

function updateCartSummary() {

    const totalItems = getCartQuantity();

    if (elements.cartBadge) {

        elements.cartBadge.textContent =
            totalItems;

        elements.cartBadge.hidden =
            totalItems === 0;
    }

    if (elements.cartTotalPrice) {

        elements.cartTotalPrice.textContent =
            formatPrice(getCartTotal());
    }
}


// ========================================
// RENDER EMPTY CART
// ========================================

function renderEmptyCart() {

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
}


// ========================================
// RENDER CART ITEMS
// ========================================

function renderCartItems() {

    elements.cartItemsList.innerHTML =
        cart.map(product => {

            const productTotal =
                Number(product.price) *
                Number(product.quantity);

            const isMaxQuantity =
                product.quantity >= MAX_QUANTITY;

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
                                    ${isMaxQuantity ? 'disabled' : ''}
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
        })
        .join('');
}


// ========================================
// RENDER CART
// ========================================

function renderCart() {

    if (!elements.cartItemsList) {
        return;
    }

    updateCartSummary();
    updateProductButtons();

    if (cart.length === 0) {
        renderEmptyCart();
        return;
    }

    if (elements.cartEmptyState) {
        elements.cartEmptyState.hidden = true;
    }

    if (elements.cartFooter) {
        elements.cartFooter.hidden = false;
    }

    renderCartItems();
}


// ========================================
// PRODUCT BUTTONS
// ========================================

document
    .querySelectorAll('.product-card__btn')
    .forEach(button => {

        button.addEventListener('click', () => {

            const product =
                getProductFromButton(button);
        
            const existingProduct =
                findCartItem(product.id);

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
// ESC
// ========================================

document.addEventListener('keydown', event => {

    if (
        event.key === 'Escape' &&
        elements.cartModal?.classList.contains(
            'modal--open'
        )
    ) {
        closeCartModal();
    }
});


// ========================================
// CART ACTIONS
// ========================================

if (elements.cartItemsList) {

    elements.cartItemsList.addEventListener(
        'click',
        event => {

            // REMOVE
            const removeButton =
                event.target.closest(
                    '[data-cart-remove]'
                );

            if (removeButton) {

                removeFromCart(
                    removeButton.dataset.cartRemove
                );

                return;
            }


            // QUANTITY
            const quantityButton =
                event.target.closest(
                    '[data-cart-qty-change]'
                );

            if (!quantityButton) {
                return;
            }

            updateCartQuantity(
                quantityButton.dataset.cartQtyChange,
                Number(quantityButton.dataset.delta)
            );
        }
    );
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