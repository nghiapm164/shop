import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Archivo', ...defaultTheme.fontFamily.sans],
                display: ['Archivo', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    primary: '#E42508',
                    'primary-hover': '#cc1e05',
                    'primary-light': '#fee2e2',
                    ink: '#161922',
                    text: '#161922',
                    muted: '#64748b',
                    subtle: '#94a3b8',
                    border: '#e2e8f0',
                    'border-light': '#f1f5f9',
                    bg: '#ffffff',
                    'bg-soft': '#f8fafc',
                    'bg-muted': '#f1f5f9',
                },
            },
            borderRadius: {
                'card': '0px',
                'card-lg': '0px',
            },
            boxShadow: {
                'card': '0 2px 8px rgba(0, 0, 0, 0.06)',
                'card-hover': '0 12px 28px -8px rgba(0, 0, 0, 0.12)',
            },
            animation: {
                'fade-up': 'fadeSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both',
                'shimmer': 'skeleton-shimmer 1.5s infinite',
            },
        },
    },

    plugins: [forms],
};