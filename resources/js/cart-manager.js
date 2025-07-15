/**
 * Modern Cart Management System
 * Handles all cart functionality with ES6+ features
 * 
 * @author Domisoft Team
 * @version 2.0.0
 */

class CartManager {
    constructor() {
        this.apiEndpoints = {
            update: '/tienda/carrito',
            add: '/tienda/carrito/agregar',
            clear: '/tienda/carrito'
        };
        
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.cartCountElement = document.getElementById('cart-count');
        
        // Configuration
        this.config = {
            toastDuration: 3000,
            animationDuration: 300,
            maxQuantity: 99,
            minQuantity: 1,
            taxRate: 0.19
        };
        
        this.init();
    }

    /**
     * Initialize all event listeners and components
     */
    init() {
        this.bindEvents();
        this.checkCSRFToken();
        console.log('🛒 CartManager initialized successfully');
    }

    /**
     * Validate CSRF token exists
     */
    checkCSRFToken() {
        if (!this.csrfToken) {
            console.warn('⚠️ CSRF token not found. Some operations may fail.');
        }
    }

    /**
     * Bind all event listeners using event delegation
     */
    bindEvents() {
        // Quantity controls
        document.addEventListener('click', this.handleQuantityButtons.bind(this));
        
        // Input changes
        document.addEventListener('change', this.handleQuantityInput.bind(this));
        
        // Remove and clear actions
        document.addEventListener('click', this.handleRemoveActions.bind(this));
        
        // Add to cart from recommendations
        document.addEventListener('click', this.handleAddToCart.bind(this));
        
        // Modal controls
        document.addEventListener('click', this.handleModalActions.bind(this));
        
        // Keyboard shortcuts
        document.addEventListener('keydown', this.handleKeyboardShortcuts.bind(this));
        
        // Close modal when clicking outside
        document.addEventListener('click', this.handleModalBackdropClick.bind(this));
    }

    /**
     * Handle quantity increase/decrease buttons
     */
    handleQuantityButtons(event) {
        const { target } = event;
        
        if (target.closest('.qty-increase') || target.closest('.qty-decrease')) {
            event.preventDefault();
            
            const button = target.closest('.qty-increase') || target.closest('.qty-decrease');
            const isIncrease = button.classList.contains('qty-increase');
            const quantityInput = button.parentElement.querySelector('.quantity-input');
            const currentValue = parseInt(quantityInput.value) || this.config.minQuantity;
            
            let newValue;
            if (isIncrease) {
                newValue = Math.min(currentValue + 1, this.config.maxQuantity);
            } else {
                newValue = Math.max(currentValue - 1, this.config.minQuantity);
            }
            
            if (newValue !== currentValue) {
                quantityInput.value = newValue;
                this.updateCartItem(button.closest('.cart-item'));
            }
        }
    }

    /**
     * Handle quantity input changes
     */
    handleQuantityInput(event) {
        const { target } = event;
        
        if (target.classList.contains('quantity-input')) {
            const value = Math.max(
                Math.min(parseInt(target.value) || this.config.minQuantity, this.config.maxQuantity), 
                this.config.minQuantity
            );
            
            if (value !== parseInt(target.value)) {
                target.value = value;
            }
            
            this.updateCartItem(target.closest('.cart-item'));
        }
    }

    /**
     * Handle remove item and clear cart actions
     */
    async handleRemoveActions(event) {
        const { target } = event;
        
        if (target.closest('.remove-item')) {
            event.preventDefault();
            await this.removeCartItem(target.closest('.cart-item'));
        }
        
        if (target.closest('#clear-cart')) {
            event.preventDefault();
            await this.clearCart();
        }
    }

    /**
     * Handle add to cart from recommendations
     */
    async handleAddToCart(event) {
        const { target } = event;
        
        if (target.closest('.btn-add-to-cart')) {
            event.preventDefault();
            const button = target.closest('.btn-add-to-cart');
            const productId = button.dataset.productId;
            
            if (productId) {
                // Add loading state
                this.setButtonLoading(button, true);
                try {
                    await this.addToCart(productId, 1);
                } finally {
                    this.setButtonLoading(button, false);
                }
            }
        }
    }

    /**
     * Handle modal open/close actions
     */
    handleModalActions(event) {
        const { target } = event;
        
        // Open modal
        if (target.closest('[data-modal]')) {
            event.preventDefault();
            const modalId = target.closest('[data-modal]').dataset.modal;
            this.openModal(modalId);
        }
        
        // Close modal
        if (target.closest('.modal-close')) {
            event.preventDefault();
            const modal = target.closest('[id$="Modal"]');
            if (modal) {
                this.closeModal(modal.id);
            }
        }
    }

    /**
     * Handle keyboard shortcuts
     */
    handleKeyboardShortcuts(event) {
        // Close modal with Escape key
        if (event.key === 'Escape') {
            const openModal = document.querySelector('[id$="Modal"]:not(.hidden)');
            if (openModal) {
                this.closeModal(openModal.id);
            }
        }
    }

    /**
     * Handle modal backdrop clicks
     */
    handleModalBackdropClick(event) {
        if (event.target.matches('[id$="Modal"]')) {
            const modalId = event.target.id;
            this.closeModal(modalId);
        }
    }

    /**
     * Update cart item quantity
     */
    async updateCartItem(cartItemElement) {
        if (!cartItemElement) return;
        
        const itemId = cartItemElement.dataset.itemId;
        const quantity = cartItemElement.querySelector('.quantity-input').value;
        
        if (!itemId) {
            console.error('❌ Item ID not found');
            return;
        }
        
        try {
            const response = await this.apiRequest(`${this.apiEndpoints.update}/${itemId}`, {
                method: 'PATCH',
                body: JSON.stringify({ quantity: parseInt(quantity) })
            });
            
            if (response.success) {
                // Update item total display
                const itemTotalElement = cartItemElement.querySelector('.item-total');
                if (itemTotalElement) {
                    itemTotalElement.textContent = this.formatCurrency(response.item_total);
                }
                
                this.updateTotals();
                this.showToast('success', 'Carrito actualizado');
            } else {
                throw new Error(response.message);
            }
        } catch (error) {
            console.error('❌ Error updating cart item:', error);
            this.showToast('error', error.message || 'Error al actualizar cantidad');
            // Reload page on error to show correct state
            setTimeout(() => window.location.reload(), 1500);
        }
    }

    /**
     * Remove item from cart
     */
    async removeCartItem(cartItemElement) {
        if (!cartItemElement) return;
        
        const itemId = cartItemElement.dataset.itemId;
        const confirmed = await this.showConfirmDialog('¿Estás seguro de que quieres eliminar este producto?');
        
        if (!confirmed || !itemId) return;
        
        try {
            const response = await this.apiRequest(`${this.apiEndpoints.update}/${itemId}`, {
                method: 'DELETE'
            });
            
            if (response.success) {
                // Animate removal
                this.animateRemoval(cartItemElement, () => {
                    cartItemElement.remove();
                    this.updateTotals();
                    this.updateCartCount(response.cart_count);
                    
                    if (response.cart_count === 0) {
                        setTimeout(() => window.location.reload(), 500);
                    }
                });
                
                this.showToast('success', response.message);
            } else {
                throw new Error(response.message);
            }
        } catch (error) {
            console.error('❌ Error removing cart item:', error);
            this.showToast('error', error.message || 'Error al eliminar producto');
        }
    }

    /**
     * Clear entire cart
     */
    async clearCart() {
        const confirmed = await this.showConfirmDialog('¿Estás seguro de que quieres vaciar todo el carrito?');
        
        if (!confirmed) return;
        
        try {
            const response = await this.apiRequest(this.apiEndpoints.clear, {
                method: 'DELETE'
            });
            
            if (response.success) {
                this.showToast('success', 'Carrito vaciado correctamente');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                throw new Error(response.message);
            }
        } catch (error) {
            console.error('❌ Error clearing cart:', error);
            this.showToast('error', error.message || 'Error al vaciar carrito');
        }
    }

    /**
     * Add product to cart
     */
    async addToCart(productId, quantity = 1) {
        try {
            const response = await this.apiRequest(this.apiEndpoints.add, {
                method: 'POST',
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });
            
            if (response.success) {
                this.showToast('success', response.message);
                this.updateCartCount(response.cart_count);
            } else {
                throw new Error(response.message);
            }
        } catch (error) {
            console.error('❌ Error adding to cart:', error);
            this.showToast('error', error.message || 'Error al agregar producto');
        }
    }

    /**
     * Update cart totals display
     */
    updateTotals() {
        const itemTotals = document.querySelectorAll('.item-total');
        let subtotal = 0;
        
        itemTotals.forEach(element => {
            const value = this.parseCurrency(element.textContent) || 0;
            subtotal += value;
        });
        
        const tax = subtotal * this.config.taxRate;
        const total = subtotal + tax;
        
        // Update display elements with animation
        this.animateValueChange('cart-subtotal', this.formatCurrency(subtotal));
        this.animateValueChange('cart-tax', this.formatCurrency(tax));
        this.animateValueChange('cart-total', this.formatCurrency(total));
    }

    /**
     * Update cart count in navigation
     */
    updateCartCount(count) {
        if (this.cartCountElement) {
            this.cartCountElement.textContent = count;
            this.cartCountElement.classList.toggle('hidden', count === 0);
            
            // Add pulse animation for visual feedback
            if (count > 0) {
                this.cartCountElement.classList.add('animate-pulse');
                setTimeout(() => {
                    this.cartCountElement.classList.remove('animate-pulse');
                }, 1000);
            }
        }
    }

    /**
     * Update element content safely with animation
     */
    updateElement(id, content) {
        const element = document.getElementById(id);
        if (element && element.textContent !== content) {
            element.textContent = content;
        }
    }

    /**
     * Animate value changes
     */
    animateValueChange(elementId, newValue) {
        const element = document.getElementById(elementId);
        if (element && element.textContent !== newValue) {
            element.style.transition = 'opacity 0.2s ease';
            element.style.opacity = '0.7';
            
            setTimeout(() => {
                element.textContent = newValue;
                element.style.opacity = '1';
            }, 100);
        }
    }

    /**
     * Animate element removal
     */
    animateRemoval(element, callback) {
        element.style.transition = 'all 0.3s ease';
        element.style.transform = 'translateX(-100%)';
        element.style.opacity = '0';
        
        setTimeout(callback, this.config.animationDuration);
    }

    /**
     * Set button loading state
     */
    setButtonLoading(button, isLoading) {
        if (isLoading) {
            button.disabled = true;
            button.style.opacity = '0.6';
            button.style.cursor = 'not-allowed';
        } else {
            button.disabled = false;
            button.style.opacity = '';
            button.style.cursor = '';
        }
    }

    /**
     * Make API request with proper headers and error handling
     */
    async apiRequest(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        };
        
        try {
            const response = await fetch(url, { ...defaultOptions, ...options });
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            if (error.name === 'TypeError' && error.message.includes('Failed to fetch')) {
                throw new Error('Error de conexión. Verifica tu conexión a internet.');
            }
            throw error;
        }
    }

    /**
     * Show toast notification with enhanced styling
     */
    showToast(type, message) {
        const isSuccess = type === 'success';
        const bgColor = isSuccess ? 'bg-green-500' : 'bg-red-500';
        const icon = isSuccess 
            ? '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
            : '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
        
        const toast = document.createElement('div');
        toast.className = `toast ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg fixed top-4 right-4 z-50 max-w-sm`;
        toast.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">${icon}</div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="inline-flex text-white hover:text-gray-200 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        toast.style.transform = 'translateX(100%)';
        toast.style.transition = 'transform 0.3s ease';
        setTimeout(() => toast.style.transform = 'translateX(0)', 10);
        
        // Auto remove
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), this.config.animationDuration);
        }, this.config.toastDuration);
    }

    /**
     * Show confirmation dialog (can be enhanced with custom modal)
     */
    showConfirmDialog(message) {
        return new Promise(resolve => {
            const result = confirm(message);
            resolve(result);
        });
    }

    /**
     * Open modal with animation
     */
    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Add animation
            const modalContent = modal.querySelector('.relative');
            if (modalContent) {
                modalContent.style.transform = 'scale(0.95)';
                modalContent.style.opacity = '0';
                modalContent.style.transition = 'all 0.2s ease';
                
                setTimeout(() => {
                    modalContent.style.transform = 'scale(1)';
                    modalContent.style.opacity = '1';
                }, 10);
            }
        }
    }

    /**
     * Close modal with animation
     */
    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const modalContent = modal.querySelector('.relative');
            
            if (modalContent) {
                modalContent.style.transition = 'all 0.2s ease';
                modalContent.style.transform = 'scale(0.95)';
                modalContent.style.opacity = '0';
                
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 200);
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
    }

    /**
     * Format number as currency
     */
    formatCurrency(amount) {
        return `$${new Intl.NumberFormat('es-CO').format(Math.round(amount))}`;
    }

    /**
     * Parse currency string to number
     */
    parseCurrency(currencyString) {
        return parseFloat(currencyString.replace(/[$,.\s]/g, '')) || 0;
    }

    /**
     * Destroy cart manager and cleanup
     */
    destroy() {
        // Remove event listeners
        document.removeEventListener('click', this.handleQuantityButtons);
        document.removeEventListener('change', this.handleQuantityInput);
        document.removeEventListener('click', this.handleRemoveActions);
        document.removeEventListener('click', this.handleAddToCart);
        document.removeEventListener('click', this.handleModalActions);
        document.removeEventListener('keydown', this.handleKeyboardShortcuts);
        document.removeEventListener('click', this.handleModalBackdropClick);
        
        console.log('🛒 CartManager destroyed');
    }
}

// Export for module usage
export default CartManager;

// Also make it available globally for non-module usage
window.CartManager = CartManager; 