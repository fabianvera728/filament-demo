// Modern JavaScript Store Management
class StoreManager {
    constructor() {
        this.cart = new CartManager();
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.cart.updateCounter();
        this.setupToastContainer();
    }

    setupEventListeners() {
        // Add to cart buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('.btn-add-to-cart') || e.target.closest('.btn-add-to-cart')) {
                e.preventDefault();
                const button = e.target.closest('.btn-add-to-cart');
                this.cart.addProduct(button);
            }
        });

        // Mobile menu toggle
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-toggle="mobile-menu"]')) {
                this.toggleMobileMenu();
            }
        });

        // Dropdown toggles
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-toggle="dropdown"]')) {
                e.preventDefault();
                this.toggleDropdown(e.target);
            }
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('[data-toggle="dropdown"]') && !e.target.closest('.dropdown-menu')) {
                this.closeAllDropdowns();
            }
        });
    }

    setupToastContainer() {
        if (!document.getElementById('toast-container')) {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(container);
        }
    }

    toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const isHidden = menu.classList.contains('hidden');
        
        if (isHidden) {
            menu.classList.remove('hidden');
            menu.classList.add('animate-fade-in');
        } else {
            menu.classList.add('hidden');
        }
    }

    toggleDropdown(trigger) {
        const menu = trigger.nextElementSibling;
        const isHidden = menu.classList.contains('hidden');
        
        // Close all other dropdowns
        this.closeAllDropdowns();
        
        if (isHidden) {
            menu.classList.remove('hidden');
            menu.classList.add('animate-fade-in');
        }
    }

    closeAllDropdowns() {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }

    showToast(type, message, duration = 5000) {
        const container = document.getElementById('toast-container');
        const toast = this.createToast(type, message);
        
        container.appendChild(toast);
        
        // Auto remove
        setTimeout(() => {
            this.removeToast(toast);
        }, duration);
    }

    createToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        const icons = {
            success: '✓',
            error: '✗',
            warning: '⚠',
            info: 'ℹ'
        };

        const colors = {
            success: 'text-green-600',
            error: 'text-red-600',
            warning: 'text-yellow-600',
            info: 'text-blue-600'
        };

        toast.innerHTML = `
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-5 h-5 ${colors[type]} font-bold flex items-center justify-center">
                            ${icons[type]}
                        </div>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-900">${message}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none" onclick="storeManager.removeToast(this.closest('.toast'))">
                            <span class="sr-only">Cerrar</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;

        return toast;
    }

    removeToast(toast) {
        toast.style.animation = 'slideInRight 0.3s ease-out reverse';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }
}

class CartManager {
    constructor() {
        this.baseUrl = '/tienda/carrito';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    async addProduct(button) {
        const productId = button.dataset.productId;
        const quantityInput = button.closest('.product-card')?.querySelector('.quantity-input');
        const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

        if (!productId) {
            storeManager.showToast('error', 'ID de producto no válido');
            return;
        }

        try {
            button.disabled = true;
            button.innerHTML = '<div class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></div>';

            const response = await this.makeRequest('/agregar', 'POST', {
                product_id: productId,
                quantity: quantity
            });

            if (response.success) {
                this.updateCounter(response.cart_count);
                storeManager.showToast('success', response.message);
            } else {
                storeManager.showToast('error', response.message);
            }
        } catch (error) {
            storeManager.showToast('error', error.message || 'Error al agregar producto');
        } finally {
            button.disabled = false;
            button.innerHTML = button.dataset.originalText || 'Agregar';
        }
    }

    async updateQuantity(cartId, quantity) {
        try {
            const response = await this.makeRequest(`/${cartId}`, 'PATCH', {
                quantity: quantity
            });

            if (response.success) {
                this.updateItemTotal(cartId, response.item_total);
                this.updateTotals();
                storeManager.showToast('success', 'Carrito actualizado');
                return response;
            } else {
                storeManager.showToast('error', response.message);
                throw new Error(response.message);
            }
        } catch (error) {
            storeManager.showToast('error', error.message || 'Error al actualizar cantidad');
            throw error;
        }
    }

    async removeItem(cartId) {
        try {
            const response = await this.makeRequest(`/${cartId}`, 'DELETE');

            if (response.success) {
                this.removeItemFromDOM(cartId);
                this.updateCounter(response.cart_count);
                this.updateTotals();
                storeManager.showToast('success', response.message);

                if (response.cart_count === 0) {
                    setTimeout(() => location.reload(), 1000);
                }
            } else {
                storeManager.showToast('error', response.message);
            }
        } catch (error) {
            storeManager.showToast('error', error.message || 'Error al eliminar producto');
        }
    }

    async clearCart() {
        if (!confirm('¿Estás seguro de que quieres vaciar todo el carrito?')) {
            return;
        }

        try {
            const response = await this.makeRequest('', 'DELETE');
            
            if (response.success) {
                location.reload();
            } else {
                storeManager.showToast('error', response.message);
            }
        } catch (error) {
            storeManager.showToast('error', error.message || 'Error al vaciar carrito');
        }
    }

    async makeRequest(endpoint, method, data = null) {
        const url = this.baseUrl + endpoint;
        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        if (this.csrfToken) {
            options.headers['X-CSRF-TOKEN'] = this.csrfToken;
        }

        if (data && (method === 'POST' || method === 'PATCH' || method === 'PUT')) {
            options.body = JSON.stringify(data);
        }

        const response = await fetch(url, options);
        
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
        }

        return await response.json();
    }

    updateCounter(count = null) {
        const counter = document.getElementById('cart-count');
        if (counter) {
            if (count !== null) {
                counter.textContent = count;
                counter.classList.toggle('hidden', count === 0);
            } else {
                // Fetch current count
                this.getCurrentCartCount().then(currentCount => {
                    counter.textContent = currentCount;
                    counter.classList.toggle('hidden', currentCount === 0);
                });
            }
        }
    }

    async getCurrentCartCount() {
        try {
            const response = await fetch('/tienda/carrito/count', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            return data.count || 0;
        } catch (error) {
            return 0;
        }
    }

    updateItemTotal(cartId, total) {
        const itemElement = document.querySelector(`[data-item-id="${cartId}"]`);
        if (itemElement) {
            const totalElement = itemElement.querySelector('.item-total');
            if (totalElement) {
                totalElement.textContent = `$${this.formatNumber(total)}`;
            }
        }
    }

    removeItemFromDOM(cartId) {
        const itemElement = document.querySelector(`[data-item-id="${cartId}"]`);
        if (itemElement) {
            itemElement.style.animation = 'slideUp 0.3s ease-out reverse';
            setTimeout(() => {
                itemElement.remove();
            }, 300);
        }
    }

    updateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.item-total').forEach(element => {
            const value = parseFloat(element.textContent.replace(/[$,]/g, '')) || 0;
            subtotal += value;
        });

        const tax = subtotal * 0.19;
        const total = subtotal + tax;

        const subtotalElement = document.getElementById('cart-subtotal');
        const taxElement = document.getElementById('cart-tax');
        const totalElement = document.getElementById('cart-total');

        if (subtotalElement) subtotalElement.textContent = `$${this.formatNumber(subtotal)}`;
        if (taxElement) taxElement.textContent = `$${this.formatNumber(tax)}`;
        if (totalElement) totalElement.textContent = `$${this.formatNumber(total)}`;
    }

    formatNumber(number) {
        return new Intl.NumberFormat('es-CO').format(Math.round(number));
    }
}

// Cart page specific functionality
class CartPageManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupQuantityControls();
        this.setupRemoveButtons();
        this.setupClearButton();
    }

    setupQuantityControls() {
        document.addEventListener('click', (e) => {
            if (e.target.matches('.qty-increase') || e.target.closest('.qty-increase')) {
                e.preventDefault();
                const button = e.target.closest('.qty-increase');
                const input = button.previousElementSibling;
                const currentValue = parseInt(input.value) || 1;
                input.value = currentValue + 1;
                this.updateCartItem(button.closest('.cart-item'));
            }

            if (e.target.matches('.qty-decrease') || e.target.closest('.qty-decrease')) {
                e.preventDefault();
                const button = e.target.closest('.qty-decrease');
                const input = button.nextElementSibling;
                const currentValue = parseInt(input.value) || 1;
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                    this.updateCartItem(button.closest('.cart-item'));
                }
            }
        });

        document.addEventListener('change', (e) => {
            if (e.target.matches('.quantity-input')) {
                const value = parseInt(e.target.value) || 1;
                if (value < 1) {
                    e.target.value = 1;
                }
                this.updateCartItem(e.target.closest('.cart-item'));
            }
        });
    }

    setupRemoveButtons() {
        document.addEventListener('click', (e) => {
            if (e.target.matches('.remove-item') || e.target.closest('.remove-item')) {
                e.preventDefault();
                const button = e.target.closest('.remove-item');
                const cartItem = button.closest('.cart-item');
                const itemId = cartItem.dataset.itemId;
                
                if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
                    storeManager.cart.removeItem(itemId);
                }
            }
        });
    }

    setupClearButton() {
        document.addEventListener('click', (e) => {
            if (e.target.matches('#clear-cart')) {
                e.preventDefault();
                storeManager.cart.clearCart();
            }
        });
    }

    async updateCartItem(cartItemElement) {
        const itemId = cartItemElement.dataset.itemId;
        const quantity = cartItemElement.querySelector('.quantity-input').value;
        
        try {
            await storeManager.cart.updateQuantity(itemId, quantity);
        } catch (error) {
            // Error handling is done in the CartManager
            setTimeout(() => location.reload(), 1000);
        }
    }
}

// Initialize store manager when DOM is loaded
let storeManager;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        storeManager = new StoreManager();
        
        // Initialize cart page if we're on cart page
        if (document.querySelector('.cart-item')) {
            new CartPageManager();
        }
    });
} else {
    storeManager = new StoreManager();
    
    // Initialize cart page if we're on cart page
    if (document.querySelector('.cart-item')) {
        new CartPageManager();
    }
}

// Export for global access
window.storeManager = storeManager; 