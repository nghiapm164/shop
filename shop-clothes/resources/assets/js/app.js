/* ============================================
   YODY SHOP - MAIN JAVASCRIPT
   ============================================ */

document.addEventListener('DOMContentLoaded', function() {
    initCarousels();
    initInteractions();
});

/**
 * Initialize all carousels on the page
 */
function initCarousels() {
    const carousels = document.querySelectorAll('.carousel');
    
    carousels.forEach((carousel, index) => {
        const inner = carousel.querySelector('.carousel-inner');
        const items = carousel.querySelectorAll('.carousel-item');
        const dots = carousel.querySelectorAll('.carousel-dot');
        
        if (items.length === 0) return;
        
        let currentIndex = 0;
        
        // Auto-rotate carousel every 5 seconds
        let autoRotateInterval = setInterval(() => {
            currentIndex = (currentIndex + 1) % items.length;
            updateCarousel();
        }, 5000);
        
        // Dot click handlers
        dots.forEach((dot, dotIndex) => {
            dot.addEventListener('click', () => {
                clearInterval(autoRotateInterval);
                currentIndex = dotIndex;
                updateCarousel();
                
                // Restart auto-rotate
                autoRotateInterval = setInterval(() => {
                    currentIndex = (currentIndex + 1) % items.length;
                    updateCarousel();
                }, 5000);
            });
        });
        
        function updateCarousel() {
            // Update transform for carousel-inner
            const offset = -currentIndex * 100;
            inner.style.transform = `translateX(${offset}%)`;
            
            // Update active dot
            dots.forEach((dot, dotIndex) => {
                if (dotIndex === currentIndex) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }
        
        // Initial state
        updateCarousel();
    });
}

/**
 * Initialize various interactions
 */
function initInteractions() {
    // Category card hover effects
    const categoryCards = document.querySelectorAll('.category-card');
    categoryCards.forEach(card => {
        card.addEventListener('click', function() {
            const categoryName = this.querySelector('h3').textContent;
            console.log('Clicked category:', categoryName);
            // You can add navigation logic here
        });
    });
    
    // Product card interactions
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('click', function() {
            const productName = this.querySelector('.product-name').textContent;
            console.log('Clicked product:', productName);
            // You can add product detail page navigation
        });
    });
    
    // Search functionality
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchQuery = this.value.trim();
                if (searchQuery) {
                    console.log('Search for:', searchQuery);
                    // You can add search logic here
                }
            }
        });
    }
    
    // Button interactions
    const buttons = document.querySelectorAll('.btn-primary, .btn-secondary');
    buttons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Add ripple effect on click
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
}

/**
 * Utility function to smoothly scroll to element
 */
function scrollToElement(selector) {
    const element = document.querySelector(selector);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

/**
 * Utility function to format Vietnamese currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

/**
 * Add to cart functionality
 */
function addToCart(productId, productName) {
    console.log('Added to cart:', productName, 'ID:', productId);
    // Show notification
    showNotification('Đã thêm vào giỏ hàng: ' + productName);
}

/**
 * Show notification
 */
function showNotification(message) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #10b981;
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideInUp 0.3s ease;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutDown 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

/**
 * Initialize tooltips
 */
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltipText = this.getAttribute('data-tooltip');
            // Create and show tooltip
            console.log('Tooltip:', tooltipText);
        });
    });
}

// Add CSS animations dynamically
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideOutDown {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(30px);
        }
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
