@extends('layouts.store')

@section('title', 'Domisoft - Tu delivery favorito')

@section('content')

{{-- Hero Section Component --}}
<x-home.hero-section />

{{-- Stats Section Component --}}
<x-home.stats-section />

{{-- Promotions Banner Component --}}
<x-home.promotions-banner />

{{-- Categories Section Component --}}
<x-home.categories-section :categories="$categories" />

{{-- Featured Products Component --}}
<x-home.featured-products :featured-products="$featuredProducts" />

{{-- Testimonials Section Component --}}
<x-home.testimonials-section />

{{-- Popular Products Component --}}
<x-home.popular-products :popular-products="$popularProducts" />

{{-- Features Section Component --}}
<x-home.features-section />

{{-- Franchises Section Component --}}
<x-home.franchises-section :franchises="$franchises" />

{{-- Newsletter Section Component --}}
<x-home.newsletter-section />

@push('styles')
<style>
/* Enhanced animations and effects */
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounce-slow {
    0%, 20%, 53%, 80%, 100% {
        transform: translateY(0);
    }
    40%, 43% {
        transform: translateY(-10px);
    }
    70% {
        transform: translateY(-5px);
    }
}

@keyframes floating {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    33% {
        transform: translateY(-10px) rotate(5deg);
    }
    66% {
        transform: translateY(-5px) rotate(-3deg);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 1s ease-out;
}

.animate-bounce-slow {
    animation: bounce-slow 3s infinite;
}

.floating-element {
    animation: floating 6s ease-in-out infinite;
}

.particle {
    position: absolute;
    width: 8px;
    height: 8px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.floating-particle {
    animation: floating 8s ease-in-out infinite;
}

.counter {
    transition: all 0.3s ease;
}

.testimonial-card {
    transform: perspective(1000px) rotateX(0deg);
    transition: all 0.3s ease;
}

.testimonial-card:hover {
    transform: perspective(1000px) rotateX(5deg);
}

/* Line clamp utilities */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Enhanced hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

.group:hover .group-hover\:rotate-12 {
    transform: rotate(12deg);
}

.group:hover .group-hover\:bounce {
    animation: bounce 1s;
}

.group:hover .group-hover\:translate-x-1 {
    transform: translateX(0.25rem);
}

.group:hover .group-hover\:translate-x-2 {
    transform: translateX(0.5rem);
}

/* Loading spinner */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
@endpush

@push('scripts')
<script>
// Enhanced store functionality
document.addEventListener('DOMContentLoaded', function() {
    // Animate counters when in viewport
    const counters = document.querySelectorAll('.counter');
    const animateCounters = () => {
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const count = parseInt(counter.innerText);
            const increment = target / 100;
            
            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(animateCounters, 20);
            } else {
                counter.innerText = target;
            }
        });
    };
    
    // Intersection Observer for counter animation
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe stats section
    const statsSection = document.getElementById('estadisticas');
    if (statsSection) {
        observer.observe(statsSection);
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Enhanced product card hover effects
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Parallax effect for hero section
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const particles = document.querySelectorAll('.particle');
        
        particles.forEach((particle, index) => {
            const speed = (index + 1) * 0.5;
            particle.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });
});
</script>
@endpush
@endsection