import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from "tailwindcss/colors.js";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    safelist: [
        'accent-red-500',
        'bg-yellow-50',
        'bg-yellow-100',
        'bg-yellow-200',
        'bg-yellow-300',
        'bg-yellow-400',
        'bg-yellow-500',
        'bg-yellow-600',
        'bg-yellow-700',
        'bg-gray-200',
        'bg-gray-300',
        'bg-gray-400',
        'bg-green-200',
        'bg-green-400',
        'bg-green-600',
        'bg-red-200',
        'bg-yellow-200',
        'bg-blue-200',
        'bg-blue-400',
        'bg-blue-600',
        'bg-blue-800',
        'border-indigo-500',
        'border-b-black',
        'border-b-blue-500',
        'text-green-800',
        'text-lime-600',
        'text-orange-600',
        'text-pink-600',
        'text-red-800',
        'text-yellow-600',
        'text-yellow-800',
        'text-blue-800',
        'text-blue-600',
        'text-blue-400',
        'text-blue-200',
        'hover:bg-blue-400',
        'hover:bg-blue-600',
        'hover:bg-gray-200',
        'hover:bg-gray-300',
        'hover:bg-gray-400',
        'hover:bg-yellow-400',
        'hover:text-white',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            backgroundColor: ['checked'],
            borderColor: ['checked'],
            colors: {
                fuchsia: colors.fuchsia,
            }
        },
    },

    plugins: [forms],
};
