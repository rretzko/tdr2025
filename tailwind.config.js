import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    safelist: [
        'accent-red-500',
        'bg-yellow-600',
        'bg-yellow-700',
        'bg-gray-200',
        'bg-green-200',
        'bg-green-400',
        'bg-green-600',
        'bg-red-200',
        'bg-yellow-200',
        'border-indigo-500',
        'text-green-800',
        'text-lime-600',
        'text-orange-600',
        'text-pink-600',
        'text-red-800',
        'text-yellow-600',
        'text-yellow-800',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            backgroundColor: ['checked'],
            borderColor: ['checked'],
        },
    },

    plugins: [forms],
};
